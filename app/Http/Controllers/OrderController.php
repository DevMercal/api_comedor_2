<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use App\Models\EmployeeMadePayment;
use App\Models\OrderExtra;
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

        $query = Order::with(['employeePayment', 'extras']);

        if ($date) {
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
            if ($order->payment_support) {
                $order->payment_support = asset(Storage::url($order->payment_support));
            }
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
            'order.id_employee' => 'required',
            'order.id_order_status' => 'required',
            'order.id_orders_consumption' => 'required',
            'order.payment_support' => 'required|mimes:png,jpeg,jpeg|max:4096',
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
        DB::beginTransaction();

        try {
            // 2. Obtener los datos anidados del cuerpo de la solicitud (JSON).
            $orderData = $request->input('order');
            $employeePaymentData = $request->input('employeePayment');
            $extrasData = $request->input('extras'); // Se espera un array de IDs de extras.
            // Subir la imagen si existe
            $paymentSupportPath = null;
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
                'id_employee' => $orderData['id_employee'],
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
    public function show($numberOrder)
    {
        //
        $order = Order::where('number_order', $numberOrder)
                        ->with(['employeePayment', 'extras'])
                        ->first();
        if (!$order) {
            return response()->json([
                'status' => 404,
                'message' => 'Order no encontrada'
            ], 404);
        }else {
            $order->payment_support = asset(Storage::url($order->payment_support));
            return response()->json([
                'status' => 200,
                'order' => $order
            ], 200);
        }
    }
    public function update(UpdateOrderRequest $request, Order $order)
    {
        
    }
}
