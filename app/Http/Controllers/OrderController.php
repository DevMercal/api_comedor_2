<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use App\Models\EmployeeMadePayment;
use App\Models\OrderExtra;
use App\Models\numberOrdersDay;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $date = $request->input('date');

        $query = Order::with(['employeePayment', 'extras', 'employees', 'orderStatus', 'orderConsumption', 'paymentMethod']);

        if ($date) {
            $query->whereDate('date_order', $date);
        }else {
            $date = now()->toDateString();
            $query->whereDate('date_order', $date);
        }
        $orders = $query->get();
        if ($orders->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No se encontraros registros de pedidos.'
            ], 404);
        }

        foreach ($orders as $order) {
            if ($order->payment_support != 'N/A') {
                $order->payment_support = asset(Storage::url($order->payment_support));
            }
            /*if ($order->payment_support) {
                $order->payment_support = asset(Storage::url($order->payment_support));
            }*/
        }

        return response()->json([
            'status' => 200,
            'orders' => $orders
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order.special_event' => 'required|string',
            'order.authorized' => 'required|string',
            'order.authorized_person' => 'required|string',
            'order.id_payment_method' => 'required',
            'order.reference' => 'required|numeric',
            'order.total_amount' => 'required|string',
            'order.cedula' => 'required|numeric|exists:employees,cedula',
            'order.id_order_status' => 'required',
            'order.id_orders_consumption' => 'required',
            'order.payment_support' => 'sometimes|mimes:png,jpeg,jpeg|max:1024',
            'employeePayment.cedula_employee' => 'required|string',
            'employeePayment.name_employee' => 'required|string',
            'employeePayment.phone_employee' => 'required|string',
            'employeePayment.management' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }
        // 1. Iniciar una transacción de base de datos.
        // Esto asegura que todas las operaciones de guardado se ejecuten o se reviertan juntas,
        // garantizando la integridad de los datos.


            // Obtener la fecha de hoy sin la hora
            $today = Carbon::today()->toDateString();

            // Contar los pedidos creados en la fecha actual
            $dailyOrdersCount = Order::whereDate('date_order', $today)->count();
            // Obtener el límite de pedidos del día
            $dailyLimitOrder = numberOrdersDay::all()->first();

            // Asegurarse de que el límite existe antes de usarlo
            $orderLimit = $dailyLimitOrder ? $dailyLimitOrder->numbers_orders_day : 0;

            // Aquí comienza la validación
            // La condición correcta es: si el conteo de pedidos es mayor o igual al límite.
            if ($dailyOrdersCount >= $orderLimit) {
                // Si el conteo de pedidos es igual o supera el límite, no se permite un nuevo pedido.
                return response()->json([
                    'status' => 400,
                    'message' => 'Se ha superado el número máximo de pedidos para hoy.'
                ], 400);
            }
        DB::beginTransaction();

        try {
            // 2. Obtener los datos anidados del cuerpo de la solicitud (JSON).
            $orderData = $request->input('order');
            $employeePaymentData = $request->input('employeePayment');
            $extrasData = $request->input('extras'); // Se espera un array de IDs de extras.
            // Subir la imagen si existe

            $paymentSupportPath = 'N/A';
            if ($request->hasFile('order.payment_support')) {
                $paymentSupportPath = $request->file('order.payment_support')->storePublicly('payment_supports', 'public');
            }

            // 3. Generar un nuevo número de pedido secuencial.
            // Primero, se busca el último número de pedido en la tabla para asegurarnos de la secuencia.
            $lastOrder = Order::orderBy('number_order', 'desc')->first();
            $newOrderNumber = $lastOrder ? $lastOrder->number_order + 1 : 1;


            $currentToday = Carbon::now()->toDateString();
            // 4. Crear el registro de la nueva orden en la tabla 'orders'.
            // Se utiliza el array de datos extraído de la solicitud.
            $order = Order::create([
                'number_order' => $newOrderNumber,
                'special_event' => $orderData['special_event'],
                'authorized' => $orderData['authorized'],
                'authorized_person' => $orderData['authorized_person'],
                'id_payment_method' => $orderData['id_payment_method'],
                'reference' => $orderData['reference'],
                'total_amount' => $orderData['total_amount'],
                'cedula' => $orderData['cedula'],
                'id_order_status' => $orderData['id_order_status'],
                'id_orders_consumption' => $orderData['id_orders_consumption'],
                'date_order' => $currentToday,
                'payment_support' => $paymentSupportPath
            ]);

            // 5. Crear el registro del pago del empleado.
            // Se vincula con la orden recién creada usando el 'number_order'.
            EmployeeMadePayment::create([
                'id_employee_made_payment' => $employeePaymentData['cedula_employee'],
                'cedula_employee' => $employeePaymentData['cedula_employee'],
                'name_employee' => $employeePaymentData['name_employee'],
                'phone_employee' => $employeePaymentData['phone_employee'],
                'management' => $employeePaymentData['management'],
                'id_order' => $newOrderNumber, // Vinculación con el número de pedido.
            ]);

            // 6. Recorrer el array de extras y guardarlos en la tabla pivote 'order_extras'.
            // Cada extra se guarda con el ID de la orden a la que pertenece.
            foreach ($extrasData as $extraId) {
                OrderExtra::create([
                    'id_order' => $newOrderNumber,
                    'id_extra' => $extraId,
                ]);
            }

            // 7. Si todo va bien, confirmar la transacción.
            DB::commit();

            return response()->json([
                'status' => 201,
                'order' => $newOrderNumber
            ], 201);

        } catch (\Exception $e) {
            // 8. En caso de error, revertir la transacción.
            // Esto elimina cualquier registro que se haya creado en este intento.
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Error al guardar los registros: ' . $e->getMessage()
            ], 500);
        }
    }
    public function show($cedula)
    {
        //
        $today = Carbon::today()->toDateString();
        $order = Order::where('cedula', $cedula)
                        ->whereDate('date_order', $today)
                        ->with(['employeePayment', 'extras' , 'employees', 'orderStatus', 'orderConsumption', 'paymentMethod'])
                        ->first();
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Order no encontrada'
            ], 404);
        }else {
            if ($order->payment_support != 'N/A') {
                $order->payment_support = asset(Storage::url($order->payment_support));
            }
            return response()->json([
                'status' => 200,
                'order' => $order
            ], 200);
        }
    }
    /*public function update(UpdateOrderRequest $request, Order $order)
    {
        
    }*/

    public function consumptionOrder(Request $request, $numberOrder){
        try {
            $today = Carbon::today()->toDateString();
            $order = Order::where('number_order', $numberOrder)
                            ->whereDate('date_order', $today)
                            ->first();
            if (!$order) {
                $orderExistNotDay = Order::where('date_order', $today)->first();

                if ($orderExistNotDay) {
                    $message = 'La orden no es del dia.';
                }else {
                    $message = 'Orden no encontrada.';
                }
                return response()->json([
                    'status' => 404,
                    'message' => $message
                ], 404);
            }
            $validation = Validator::make($request->all(), [
                'consumption' => 'required|integer|in:3'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => $validation->errors()
                ], 422);
            }

            $order->id_orders_consumption = $request->input('consumption');
            $order->save();

            return response()->json([
                'status' => 200,
                'message' => 'Ticket consumido.'
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function blukStore(Request $request){
        // 1. Validación para un array de objetos
        $validator = Validator::make($request->all(), [
            // Validación para cada elemento del array (el asterisco '*')
            '*.order.special_event' => 'required|string|max:20',
            '*.order.authorized' => 'required|string|max:25',
            '*.order.authorized_person' => 'required|string|max:80',
            '*.order.id_payment_method' => 'required|exists:payment_methods,id', // Asumiendo 'id' en la tabla
            '*.order.reference' => 'required|numeric',
            '*.order.total_amount' => 'required|string', // Se valida como string para manejar formatos, pero debe ser un monto
            '*.order.cedula' => 'required|numeric|exists:employees,cedula',
            '*.order.id_order_status' => 'required|exists:order_statuses,id', // Asumiendo 'id' en la tabla
            '*.order.id_orders_consumption' => 'required|exists:orders_consumption,id', // Asumiendo 'id' en la tabla
            // NOTA: Se cambia de 'file' a 'string' y se hace opcional. El cliente debe subir el archivo aparte.
            '*.order.payment_support' => 'required|mimes:png,jpeg,jpeg|max:1024', 
            // Validación de los datos del empleado de pago anidado
            '*.employeePayment.cedula_employee' => 'required|string|max:255',
            '*.employeePayment.name_employee' => 'required|string|max:255',
            '*.employeePayment.phone_employee' => 'required|string|max:255',
            '*.employeePayment.management' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Error de validación en uno o más pedidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener la fecha de hoy para el límite
        $today = Carbon::today()->toDateString();
        $dailyOrdersCount = Order::whereDate('date_order', $today)->count();
        $dailyLimitOrder = numberOrdersDay::first();
        $orderLimit = $dailyLimitOrder ? $dailyLimitOrder->numbers_orders_day : 0;
        $ordersToProcess = $request->all();
        $numberOfNewOrders = count($ordersToProcess);

        // Validar si la cantidad de pedidos excede el límite
        if (($dailyOrdersCount + $numberOfNewOrders) > $orderLimit) {
            return response()->json([
                'status' => 400,
                'message' => 'La cantidad de nuevos pedidos (' . $numberOfNewOrders . ') excede el límite máximo para hoy. Límite restante: ' . ($orderLimit - $dailyOrdersCount)
            ], 400);
        }

        DB::beginTransaction();

        $newOrderNumbers = []; // Array para almacenar los números de pedidos creados

        try {
            // Recorrer cada objeto de pedido en el array de la solicitud
            foreach ($ordersToProcess as $orderRequest) {
                $orderData = $orderRequest['order'];
                $employeePaymentData = $orderRequest['employeePayment'];
                $extrasData = $orderRequest['extras'] ?? [];

                // 3. Generar un nuevo número de pedido secuencial.
                // Debe hacerse DENTRO del bucle para garantizar la secuencia.
                $lastOrder = Order::orderBy('number_order', 'desc')->lockForUpdate()->first(); // lockForUpdate para evitar números duplicados en transacciones concurrentes
                $newOrderNumber = $lastOrder ? $lastOrder->number_order + 1 : 1;

                $currentToday = Carbon::now()->toDateString();
                
                // 4. Crear el registro de la nueva orden en la tabla 'orders'.
                $order = Order::create([
                    'number_order' => $newOrderNumber,
                    'special_event' => $orderData['special_event'],
                    'authorized' => $orderData['authorized'],
                    'authorized_person' => $orderData['authorized_person'],
                    'id_payment_method' => $orderData['id_payment_method'],
                    'reference' => $orderData['reference'],
                    'total_amount' => $orderData['total_amount'],
                    'cedula' => $orderData['cedula'],
                    'id_order_status' => $orderData['id_order_status'],
                    'id_orders_consumption' => $orderData['id_orders_consumption'],
                    'date_order' => $currentToday,
                    // Se espera que 'payment_support' sea una ruta guardada previamente.
                    'payment_support' => $orderData['payment_support'] ?? null 
                ]);

                // 5. Crear el registro del pago del empleado.
                EmployeeMadePayment::create([
                    'id_employee_made_payment' => $employeePaymentData['cedula_employee'],
                    'cedula_employee' => $employeePaymentData['cedula_employee'],
                    'name_employee' => $employeePaymentData['name_employee'],
                    'phone_employee' => $employeePaymentData['phone_employee'],
                    'management' => $employeePaymentData['management'],
                    'id_order' => $newOrderNumber, // Vinculación con el número de pedido.
                ]);

                // 6. Guardar los extras en la tabla pivote 'order_extras'.
                foreach ($extrasData as $extraId) {
                    OrderExtra::create([
                        'id_order' => $newOrderNumber,
                        'id_extra' => $extraId,
                    ]);
                }
                
                $newOrderNumbers[] = $newOrderNumber; // Agregar el número a la lista de respuestas
            }

            // 7. Si todo va bien, confirmar la transacción.
            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Pedidos creados exitosamente.',
                'orders' => $newOrderNumbers
            ], 201);

        } catch (\Exception $e) {
            // 8. En caso de error, revertir la transacción.
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Error al guardar uno o más registros: ' . $e->getMessage()
            ], 500);
        }
    }
    public function bulkStoreWithFiles(Request $request)
    {
        // 1. Validación de los campos del formulario multipart/form-data
        $initialValidator = Validator::make($request->all(), [
            'orders_json' => 'required|string', // El array de pedidos como un string JSON
            'payment_supports' => 'nullable|array', // El array de archivos
            'payment_supports.*' => 'sometimes|mimes:png,jpg,jpeg|max:1024', // Validación para cada archivo
        ]);

        if ($initialValidator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Error de validación en la estructura de la solicitud o los archivos.',
                'errors' => $initialValidator->errors()
            ], 422);
        }

        // 2. Decodificar el string JSON de pedidos
        try {
            $ordersToProcess = json_decode($request->input('orders_json'), true);
            if (!is_array($ordersToProcess)) {
                 throw new \Exception('El campo "orders_json" no es un array JSON válido.');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 422,
                'message' => 'El campo "orders_json" contiene un JSON inválido.',
                'error_detail' => $e->getMessage()
            ], 422);
        }
        // 3. Obtener los archivos subidos. Inicializar a array vacío si no se enviaron archivos.
        // Usamos array_filter para eliminar posibles elementos null y ?? [] para manejar la ausencia.
        $paymentSupports = array_filter($request->file('payment_supports') ?? []);
        $numberOfNewOrders = count($ordersToProcess);

        // Calcular el índice máximo permitido para los archivos (base 0).
        // Si no hay archivos, el índice máximo es -1.
        $maxFileIndex = count($paymentSupports) > 0 ? count($paymentSupports) - 1 : -1;

        /*if ($numberOfNewOrders !== count($paymentSupports)) {
             return response()->json([
                'status' => 422,
                'message' => 'El número de pedidos en el JSON no coincide con el número de archivos de soporte de pago.',
                'details' => ['json_count' => $numberOfNewOrders, 'files_count' => count($paymentSupports)]
            ], 422);
            $maxFileIndex = count($paymentSupports) > 0 ? count($paymentSupports) - 1 : -1;
        }*/
        
        // 4. Validación de los datos anidados de cada pedido (usando la matriz decodificada)
        $nestedValidator = Validator::make($ordersToProcess, [
            '*.order.special_event' => 'required|string|max:20',
            '*.order.authorized' => 'required|string|max:25',
            '*.order.authorized_person' => 'required|string|max:80',
            '*.order.id_payment_method' => 'required|exists:payment_methods,id_payment_method',
            '*.order.reference' => 'required|numeric',
            '*.order.total_amount' => 'required|string',
            '*.order.cedula' => 'required|numeric|exists:employees,cedula',
            '*.order.id_order_status' => 'required|exists:order_statuses,id_order_status',
            '*.order.id_orders_consumption' => 'required|exists:order_consumptions,id_orders_consumption',
            '*.order.payment_support_index' => 'nullable|numeric|min:0|max:' . $maxFileIndex,
            '*.employeePayment.cedula_employee' => 'required|string|max:255',
            '*.employeePayment.name_employee' => 'required|string|max:255',
            '*.employeePayment.phone_employee' => 'required|string|max:255',
            '*.employeePayment.management' => 'required|string|max:255',
            '*.extras' => 'nullable|array',
            '*.extras.*' => 'numeric|exists:extras,id_extra',
        ]);
        
        if ($nestedValidator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Error de validación en la estructura de datos anidada de uno o más pedidos.',
                'errors' => $nestedValidator->errors()
            ], 422);
        }

        // 5. Validación de límite diario (código similar al que tenías)
        $today = Carbon::today()->toDateString();
        $dailyOrdersCount = Order::whereDate('date_order', $today)->count();
        $dailyLimitOrder = numberOrdersDay::first();
        $orderLimit = $dailyLimitOrder ? $dailyLimitOrder->numbers_orders_day : 0;

        if (($dailyOrdersCount + $numberOfNewOrders) > $orderLimit) {
            return response()->json([
                'status' => 400,
                'message' => 'La cantidad de nuevos pedidos (' . $numberOfNewOrders . ') excede el límite máximo para hoy. Límite restante: ' . max(0, $orderLimit - $dailyOrdersCount)
            ], 400);
        }
        
        // 6. Iniciar transacción y procesamiento
        DB::beginTransaction();

        $newOrderNumbers = []; 
        $uploadedFilePaths = []; // Para rastrear archivos y eliminarlos si falla

        try {
            foreach ($ordersToProcess as $index => $orderRequest) {
                
                $orderData = $orderRequest['order'];
                $employeePaymentData = $orderRequest['employeePayment'];
                $extrasData = $orderRequest['extras'] ?? [];
                
                 // --- LÓGICA CLAVE: DETERMINAR EL VALOR DE payment_support (N/A o RUTA) ---
                $paymentSupportIndex = $orderData['payment_support_index'] ?? null;
                $paymentSupportPath = 'N/A'; // Valor por defecto

                // Verificar si se ha proporcionado un índice numérico válido que apunte a un archivo real
                if (is_numeric($paymentSupportIndex) && (int)$paymentSupportIndex >= 0 && isset($paymentSupports[(int)$paymentSupportIndex])) {
                    
                    // Si el índice es válido y el archivo existe: subirlo.
                    $file = $paymentSupports[(int)$paymentSupportIndex];
                    $paymentSupportPath = $file->storePublicly('payment_supports', 'public');
                    $uploadedFilePaths[] = $paymentSupportPath; // Guardar la ruta para el rollback
                }
                
                // Generar nuevo número de pedido secuencial (con bloqueo)
                $lastOrder = Order::orderBy('number_order', 'desc')->lockForUpdate()->first();
                $newOrderNumber = $lastOrder ? $lastOrder->number_order + 1 : 1;

                $currentToday = Carbon::now()->toDateString();
                
                // Crear el registro de la nueva orden
                Order::create([
                    'number_order' => $newOrderNumber,
                    'special_event' => $orderData['special_event'],
                    'authorized' => $orderData['authorized'],
                    'authorized_person' => $orderData['authorized_person'],
                    'id_payment_method' => $orderData['id_payment_method'],
                    'reference' => $orderData['reference'],
                    'total_amount' => $orderData['total_amount'],
                    'cedula' => $orderData['cedula'],
                    'id_order_status' => $orderData['id_order_status'],
                    'id_orders_consumption' => $orderData['id_orders_consumption'],
                    'date_order' => $currentToday,
                    'payment_support' => $paymentSupportPath // Se guarda la ruta
                ]);

                // Crear el registro del pago del empleado.
                EmployeeMadePayment::create([
                    'id_employee_made_payment' => $employeePaymentData['cedula_employee'],
                    'cedula_employee' => $employeePaymentData['cedula_employee'],
                    'name_employee' => $employeePaymentData['name_employee'],
                    'phone_employee' => $employeePaymentData['phone_employee'],
                    'management' => $employeePaymentData['management'],
                    'id_order' => $newOrderNumber,
                ]);

                // Guardar los extras
                foreach ($extrasData as $extraId) {
                    OrderExtra::create([
                        'id_order' => $newOrderNumber,
                        'id_extra' => $extraId,
                    ]);
                }
                
                $newOrderNumbers[] = $newOrderNumber; 
            }

            // 7. Commit
            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Pedidos creados exitosamente.',
                'orders' => $newOrderNumbers
            ], 201);

        } catch (\Exception $e) {
            // 8. Rollback y limpieza de archivos
            DB::rollBack();
            
            // Eliminar todos los archivos que se subieron en esta transacción
            foreach ($uploadedFilePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            
            return response()->json([
                'status' => 500,
                'message' => 'Error al guardar uno o más registros. Se revirtieron los cambios y se eliminaron los archivos.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}
