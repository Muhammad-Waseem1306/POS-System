<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\PermissionGrouper;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'cashier',
            'sales_associate',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $permissions = PermissionGrouper::allDefinedNames();

        $admin = Role::where('name', 'Admin')->first();

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $admin->givePermissionTo($permission);
            $permission->assignRole($admin);
        }

        $cashierRole = Role::where('name', 'cashier')->first();
        $salesRole = Role::where('name', 'sales_associate')->first();

        $cashierPermissions = [
            'sale_create',
            'sale_view',
            'customer_view',
            'product_create',
            'product_view',
            'product_update',
            'product_delete',
            'product_import',
        ];

        $salesPermissions = [
            'sale_create',
            'sale_view',
        ];

        foreach ($cashierPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $cashierRole->givePermissionTo($permission);
        }

        foreach ($salesPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $salesRole->givePermissionTo($permission);
        }
    }
}
