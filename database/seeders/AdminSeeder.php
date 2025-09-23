<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Permissions' => [
                'delete-permissions',
                'edit-permissions',
                'create-permissions',
                'view-permissions',
            ],
            'Users' => [
                'users-management',
                'delete-users',
                'edit-users',
                'create-users',
                'view-users',
            ],
            'Roles' => [
                'roles-management',
                'delete-roles',
                'edit-roles',
                'create-roles',
                'view-roles',
            ],
            'District' => [
                'district-management',
                'district-delete',
                'district-edit',
                'district-create',
            ],
            'Zone' => [
                'zone-management',
                'zone-delete',
                'zone-edit',
                'zone-create',
            ],
            'Candidate' => [
                'candidate-management',
                'candidate-delete',
                'candidate-edit',
                'candidate-create',
                'candidate-view',
            ],
        ];

        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission, 'guard_name' => 'web'],
                    ['module' => $module]
                );
            }
        }

        // Admin role assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'role_id'  => $adminRole->id,
            ]
        );

        if (!$admin->hasRole($adminRole->name)) {
            $admin->assignRole($adminRole);
        }

        Role::firstOrCreate(['name' => 'User']);
    }
}
