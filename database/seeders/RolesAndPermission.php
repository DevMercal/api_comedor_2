<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de permisos (usamos updateOrCreate para evitar el error de "Already exists")
        $permission = [
            /*PERMISOS DE MODELO DE USUARIOS */
            ['name' => 'view_user', 'description' => 'Ver usuarios'],
            ['name' => 'update_user', 'description' => 'Editar usuarios'], 
            ['name' => 'show_user', 'description' => 'Ver un usuario'],
            ['name' => 'create_user', 'description' => 'Registrar usuario'],
            /*PERMISOS DE MODELO DE BANCOS */
            ['name' => 'view_bank', 'description' => 'Ver bancos'],
            ['name' => 'create_bank', 'description' => 'Registrar banco'],
            ['name' => 'update_bank', 'description' => 'Editar banco'],
            /*PERMISOS DE MODELO DE PAGOS REALIZADOS POR EMPLEADOS. */
            ['name' => 'view_employee_payment', 'description' => 'Ver pagos realizados'],
            ['name' => 'show_employee_payment', 'description' => 'Ver un pago realizado'],
            /*PERMISOS DE MODELO DE EMPLEADOS */
            ['name' => 'view_employee', 'description' => 'Ver empleados'],
            ['name' => 'create_employee', 'description' => 'Registrar empleado'],
            ['name' => 'show_employee', 'description' => 'Ver un solo empleado'],
            ['name' => 'update_employee', 'description' => 'Editar empleado'],
            /*PERMISOS DE MODELO DE TASA BCV DEL DIA */
            ['name' => 'view_today_rate', 'description' => 'Ver tasa del dia'],
            /*PERMISOS DE MODELO DE EXTRAS */
            ['name' => 'view_extra', 'description' => 'Ver extras'],
            ['name' => 'update_extra', 'description' => 'Editar extra'],
            ['name' => 'show_extra', 'description' => 'Ver un extra'],
            ['name' => 'create_extra', 'description' => 'Registrar extra'],
            /*PERMISOS DE MODELO DE MENU */
            ['name' => 'view_menu', 'description' => 'Ver menu'],
            ['name' => 'update_menu', 'description' => 'Editar menu'],
            ['name' => 'show_menu', 'description' => 'Ver un menu'],
            ['name' => 'create_menu', 'description' => 'Cargar menu'],
            ['name' => 'create_menu_bluk', 'description' => 'Ver menu multiple'],
            ['name' => 'delete_menu', 'description' => 'Eliminar menu'],
            /* PERMISOS DE MODELO DE NUMEROS DE PEDIDOS POR DIA. */
            ['name' => 'view_number_order_day', 'description' => 'Ver numeros de pedidos por dia'],
            ['name' => 'create_number_order_day', 'description' => 'Registrar numeros de pedidos por dia'],
            ['name' => 'update_number_order_day', 'description' => 'Editar numeros de pedidos por dia'],
            /*PERMISOS DE MODELO DE ORDENES/PEDIDOS */
            ['name' => 'view_order', 'description' => 'Ver pedidos'],
            ['name' => 'create_order', 'description' => 'Registrar pedido'],
            ['name' => 'create_bluk_order', 'description' => 'Registrar Multiples pedidos'],
            ['name' => 'update_consumtion_order', 'description' => 'Actualizar consumo de Pedido'],
            ['name' => 'show_take_orden', 'description' => 'Ver un solo pedido'],
            ['name' => 'show_take_orden_employee', 'description' => 'Ver orden de empleado'],
            ['name' => 'view_order_monthly', 'description' => 'Ver Pedidos por Mes'],
            /*PERMISOS DE MODELO DE ESTATUS DE ORDEN/PEDIDOS */
            ['name' => 'view_status_order', 'description' => 'Ver estatus de Pedidos'],
            /*PERMISOS DE MODELO DE METODO DE PAGO */
            ['name' => 'view_payment_method', 'description' => 'Ver metodos de Pago'],
            /*PERMISOS DE MODELO DE TIEMPO DE TOKEN */
            ['name' => 'view_time_token', 'description' => 'Ver tiempos de token'],
            ['name' => 'create_time_token', 'description' => 'Registrar tiempo de token'],
            /*PERMISOS DE MODELO DE MESES DE EXPIRACIÓN */
            ['name' => 'view_expiry_month', 'description' => 'Ver meses de expiración de contraseña.'],
            ['name' => 'create_expiry_month', 'description' => 'Registrar mes de expiración de contraseña.']
        ];

        foreach ($permission as $p) {
            Permission::updateOrCreate(
                ['name' => $p['name'], 'guard_name' =>  'api'],
                ['description' => $p['description']]
            );
        }

        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super-admin', 'guard_name' => 'api']);
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
        $roleUser = Role::firstOrCreate(['name' => 'Usuario', 'guard_name' => 'api']);

        $roleSuperAdmin->syncPermissions(Permission::all());

        $roleUser->syncPermissions([
            'view_bank', 
            'view_employee_payment',
            'show_employee_payment',
            'view_employee',
            'view_today_rate',
            'view_extra',
            'view_menu',
            'view_order',
            'create_order',
            'show_take_orden',
            'show_take_orden_employee',
            'view_status_order',
            'view_payment_method'
        ]);

        $userData = [
            ['email' => 'moicastillo@mercal.gob.ve', 'password' => '12345678', 'cedula' => '18467449', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1', 'role' => $roleAdmin],
            ['email' => 'danrangel@mercal.gob.ve', 'password' => '12345678', 'cedula' => '27047631', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1', 'role' => $roleAdmin],
            ['email' => 'kleinysp@mercal.gob.ve', 'password' => '12345678', 'cedula' => '20327830', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1', 'role' => $roleAdmin],
            ['email' => 'artrangel@mercal.gob.ve', 'password' => '12345678', 'cedula' => '22036006', 'id_time_token' => '5', 'id_expiry_month' => '3', 'is_active' => '1', 'role' => $roleAdmin],
        ]; 

        foreach ($userData as $user) {
            $user = User::updateOrCreate(
                [
                    'email' => $user['email'],
                    'cedula' => $user['cedula'],
                    'password' => bcrypt($user['password']),
                    'id_time_token' => $user['id_time_token'],
                    'id_expiry_month' => $user['id_expiry_month'],
                    'is_active' => $user['is_active']
                ],
            );
            $user->syncRoles([$user['role']]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
