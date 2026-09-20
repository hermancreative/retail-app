<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Permissions available across the ERP modules.
     *
     * @var array<int, string>
     */
    public const PERMISSIONS = [
        'view-dashboard',
        'manage-master-data',
        'manage-inventory',
        'manage-purchasing',
        'manage-sales',
        'process-pos',
        'manage-finance',
        'manage-settings',
        'manage-users',
    ];

    /**
     * Roles mapped to the permissions they are granted.
     *
     * @var array<string, array<int, string>>
     */
    public const ROLES = [
        'Admin' => self::PERMISSIONS,
        'Manager' => [
            'view-dashboard',
            'manage-master-data',
            'manage-inventory',
            'manage-purchasing',
            'manage-sales',
            'manage-finance',
            'manage-settings',
        ],
        'Cashier' => [
            'view-dashboard',
            'process-pos',
        ],
        'Purchasing Staff' => [
            'view-dashboard',
            'manage-master-data',
            'manage-purchasing',
        ],
        'Warehouse Staff' => [
            'view-dashboard',
            'manage-inventory',
        ],
        'Finance Staff' => [
            'view-dashboard',
            'manage-finance',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::ROLES as $role => $permissions) {
            Role::findOrCreate($role, 'web')->syncPermissions($permissions);
        }

        // Migrate existing plain `role` column values to the new role system.
        User::query()->each(function (User $user): void {
            if ($user->roles()->exists()) {
                return;
            }

            $role = $user->role === 'admin' ? 'Admin' : 'Manager';

            $user->assignRole($role);
        });
    }
}
