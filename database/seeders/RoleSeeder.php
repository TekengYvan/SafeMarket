<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage users',
            'manage products',
            'manage orders',
            'view kyc',
            'verify kyc',
            'negotiate',
            'sell products',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $vendor = Role::firstOrCreate(['name' => 'vendor']);
        $vendor->givePermissionTo([
            'negotiate',
            'sell products',
            'manage products',
        ]);

        $buyer = Role::firstOrCreate(['name' => 'buyer']);
        $buyer->givePermissionTo([
            'negotiate',
        ]);
    }
}
