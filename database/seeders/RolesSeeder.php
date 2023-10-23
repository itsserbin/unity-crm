<?php

namespace Database\Seeders;

use App\Models\User;
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
            'api-tokens' => [
                'C' => 'create-api-tokens',
                'R' => 'read-api-tokens',
                'U' => 'update-api-tokens',
                'D' => 'delete-api-tokens',
            ],
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
            'images' => [
                'C' => 'create-images',
                'R' => 'read-images',
                'U' => 'update-images',
                'D' => 'delete-images',
            ],
            'sources' => [
                'C' => 'create-sources',
                'R' => 'read-sources',
                'U' => 'update-sources',
                'D' => 'delete-sources',
            ],
            'statuses' => [
                'C' => 'create-statuses',
                'R' => 'read-statuses',
                'U' => 'update-statuses',
                'D' => 'delete-statuses',
            ],
            'delivery-services' => [
                'C' => 'create-delivery-services',
                'R' => 'read-delivery-services',
                'U' => 'update-delivery-services',
                'D' => 'delete-delivery-services',
            ],
            'users' => [
                'C' => 'create-users',
                'R' => 'read-users',
                'U' => 'update-users',
                'D' => 'delete-users',
            ],
            'roles' => [
                'C' => 'create-roles',
                'R' => 'read-roles',
                'U' => 'update-roles',
                'D' => 'delete-roles',
            ],
            'order-statistics' => [
                'C' => 'create-order-statistics',
                'R' => 'read-order-statistics',
                'U' => 'update-order-statistics',
                'D' => 'delete-order-statistics',
            ],
            'profit-statistics' => [
                'C' => 'create-profit-statistics',
                'R' => 'read-profit-statistics',
                'U' => 'update-profit-statistics',
                'D' => 'delete-profit-statistics',
            ],
            'profit-and-loss-statistics' => [
                'C' => 'create-profit-and-loss-statistics',
                'R' => 'read-profit-and-loss-statistics',
                'U' => 'update-profit-and-loss-statistics',
                'D' => 'delete-profit-and-loss-statistics',
            ],
            'cash-flow-statistics' => [
                'C' => 'create-cash-flow-statistics',
                'R' => 'read-cash-flow-statistics',
                'U' => 'update-cash-flow-statistics',
                'D' => 'delete-cash-flow-statistics',
            ],
            'bank-accounts' => [
                'C' => 'create-bank-accounts',
                'R' => 'read-bank-accounts',
                'U' => 'update-bank-accounts',
                'D' => 'delete-bank-accounts',
            ],
            'bank-account-movements' => [
                'C' => 'create-bank-account-movements',
                'R' => 'read-bank-account-movements',
                'U' => 'update-bank-account-movements',
                'D' => 'delete-bank-account-movements',
            ],
            'finance-accounts' => [
                'C' => 'create-finance-accounts',
                'R' => 'read-finance-accounts',
                'U' => 'update-finance-accounts',
                'D' => 'delete-finance-accounts',
            ],
            'bank-account-movement-categories' => [
                'C' => 'create-bank-account-movement-categories',
                'R' => 'read-bank-account-movement-categories',
                'U' => 'update-bank-account-movement-categories',
                'D' => 'delete-bank-account-movement-categories',
            ],
        ];

        $data = [
            'admin' => [
                $permissions['api-tokens']['C'],
                $permissions['api-tokens']['R'],
                $permissions['api-tokens']['U'],
                $permissions['api-tokens']['D'],

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

                $permissions['images']['C'],
                $permissions['images']['R'],
                $permissions['images']['U'],
                $permissions['images']['D'],

                $permissions['categories']['C'],
                $permissions['categories']['R'],
                $permissions['categories']['U'],
                $permissions['categories']['D'],

                $permissions['users']['C'],
                $permissions['users']['R'],
                $permissions['users']['U'],
                $permissions['users']['D'],

                $permissions['roles']['C'],
                $permissions['roles']['R'],
                $permissions['roles']['U'],
                $permissions['roles']['D'],

                $permissions['sources']['C'],
                $permissions['sources']['R'],
                $permissions['sources']['U'],
                $permissions['sources']['D'],

                $permissions['statuses']['C'],
                $permissions['statuses']['R'],
                $permissions['statuses']['U'],
                $permissions['statuses']['D'],

                $permissions['delivery-services']['C'],
                $permissions['delivery-services']['R'],
                $permissions['delivery-services']['U'],
                $permissions['delivery-services']['D'],

                $permissions['order-statistics']['C'],
                $permissions['order-statistics']['R'],
                $permissions['order-statistics']['U'],
                $permissions['order-statistics']['D'],

                $permissions['profit-statistics']['C'],
                $permissions['profit-statistics']['R'],
                $permissions['profit-statistics']['U'],
                $permissions['profit-statistics']['D'],

                $permissions['profit-and-loss-statistics']['C'],
                $permissions['profit-and-loss-statistics']['R'],
                $permissions['profit-and-loss-statistics']['U'],
                $permissions['profit-and-loss-statistics']['D'],

                $permissions['cash-flow-statistics']['C'],
                $permissions['cash-flow-statistics']['R'],
                $permissions['cash-flow-statistics']['U'],
                $permissions['cash-flow-statistics']['D'],

                $permissions['bank-accounts']['C'],
                $permissions['bank-accounts']['R'],
                $permissions['bank-accounts']['U'],
                $permissions['bank-accounts']['D'],

                $permissions['bank-account-movements']['C'],
                $permissions['bank-account-movements']['R'],
                $permissions['bank-account-movements']['U'],
                $permissions['bank-account-movements']['D'],

                $permissions['finance-accounts']['C'],
                $permissions['finance-accounts']['R'],
                $permissions['finance-accounts']['U'],
                $permissions['finance-accounts']['D'],

                $permissions['bank-account-movement-categories']['C'],
                $permissions['bank-account-movement-categories']['R'],
                $permissions['bank-account-movement-categories']['U'],
                $permissions['bank-account-movement-categories']['D'],
            ],
            'manager' => [
                $permissions['orders']['C'],
                $permissions['orders']['R'],
                $permissions['orders']['U'],

                $permissions['clients']['C'],
                $permissions['clients']['R'],
                $permissions['clients']['U'],

                $permissions['order-statistics']['C'],
                $permissions['order-statistics']['R'],
                $permissions['order-statistics']['U'],
            ],
            'content-manager' => [
                $permissions['products']['C'],
                $permissions['products']['R'],
                $permissions['products']['U'],

                $permissions['categories']['C'],
                $permissions['categories']['R'],
                $permissions['categories']['U'],

                $permissions['images']['C'],
                $permissions['images']['R'],
                $permissions['images']['U'],
                $permissions['images']['D'],
            ],
            'bookkeeper' => [
                $permissions['order-statistics']['C'],
                $permissions['order-statistics']['R'],
                $permissions['order-statistics']['U'],
                $permissions['order-statistics']['D'],

                $permissions['profit-statistics']['C'],
                $permissions['profit-statistics']['R'],
                $permissions['profit-statistics']['U'],
                $permissions['profit-statistics']['D'],

                $permissions['profit-and-loss-statistics']['C'],
                $permissions['profit-and-loss-statistics']['R'],
                $permissions['profit-and-loss-statistics']['U'],
                $permissions['profit-and-loss-statistics']['D'],

                $permissions['cash-flow-statistics']['C'],
                $permissions['cash-flow-statistics']['R'],
                $permissions['cash-flow-statistics']['U'],
                $permissions['cash-flow-statistics']['D'],

                $permissions['bank-accounts']['C'],
                $permissions['bank-accounts']['R'],
                $permissions['bank-accounts']['U'],
                $permissions['bank-accounts']['D'],

                $permissions['bank-account-movements']['C'],
                $permissions['bank-account-movements']['R'],
                $permissions['bank-account-movements']['U'],
                $permissions['bank-account-movements']['D'],

                $permissions['finance-accounts']['C'],
                $permissions['finance-accounts']['R'],
                $permissions['finance-accounts']['U'],
                $permissions['finance-accounts']['D'],

                $permissions['bank-account-movement-categories']['C'],
                $permissions['bank-account-movement-categories']['R'],
                $permissions['bank-account-movement-categories']['U'],
                $permissions['bank-account-movement-categories']['D'],
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
