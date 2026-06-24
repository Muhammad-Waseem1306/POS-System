<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StartUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System setup - no demo data included for client deployment
        
        $role = Role::create(['name' => 'Admin']);
        $this->call([
            UnitSeeder::class,
            CurrencySeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Seed the protected system supplier (id=1 is reserved and cannot be deleted)
        Supplier::firstOrCreate(['id' => 1], [
            'name' => 'Own Supplier',
            'phone' => '0000000000',
            'address' => 'System',
        ]);
    }
}
