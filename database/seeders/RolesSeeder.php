<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        $permissions = [
            'orders' => [
                'C' => 'create-orders',
                'R' => 'read-orders',
                'U' => 'update-orders',
                'D' => 'delete-orders',
            ],
            'clients' => [
                'C' => 'create-clients',
                'R' => 'read-clients',
                'U' => 'update-clients',
                'D' => 'delete-clients',
            ],
            'products' => [
                'C' => 'create-products',
                'R' => 'read-products',
                'U' => 'update-products',
                'D' => 'delete-products',
            ],
            'categories' => [
                'C' => 'create-categories',
                'R' => 'read-categories',
                'U' => 'update-categories',
                'D' => 'delete-categories',
            ],
//            'categories' => [
//                'C' => 'create',
//                'R' => 'read',
//                'U' => 'update',
//                'D' => 'delete',
//            ],
        ];

        $data = [
            'admin' => [
                $permissions['orders']['C'],
                $permissions['orders']['R'],
                $permissions['orders']['U'],
                $permissions['orders']['D'],

                $permissions['clients']['C'],
                $permissions['clients']['R'],
                $permissions['clients']['U'],
                $permissions['clients']['D'],

                $permissions['products']['C'],
                $permissions['products']['R'],
                $permissions['products']['U'],
                $permissions['products']['D'],

                $permissions['categories']['C'],
                $permissions['categories']['R'],
                $permissions['categories']['U'],
                $permissions['categories']['D'],
            ],
            'manager' => [
                $permissions['orders']['C'],
                $permissions['orders']['R'],
                $permissions['orders']['U'],

                $permissions['clients']['C'],
                $permissions['clients']['R'],
                $permissions['clients']['U'],
            ],
            'content-manager' => [
                $permissions['products']['C'],
                $permissions['products']['R'],
                $permissions['products']['U'],

                $permissions['categories']['C'],
                $permissions['categories']['R'],
                $permissions['categories']['U'],
            ],
        ];

        foreach ($data as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                $role = Role::create(['name' => $roleName]);
            }

            $rolePermissions = [];

            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();

                if (!$permission) {
                    $permission = Permission::create(['name' => $permissionName]);
                }

                $rolePermissions[] = $permission;
            }

            $role->syncPermissions($rolePermissions);
        }
    }
}
