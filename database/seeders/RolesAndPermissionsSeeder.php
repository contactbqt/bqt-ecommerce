<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define default permissions
        $permissions = [
            'permission list',
            'permission create',
            'permission edit',
            'permission delete',

            'role list',
            'role create',
            'role edit',
            'role delete',

            'user list',
            'user create',
            'user edit',
            'user delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define a role for super admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super admin']); //No need to define any permissions here

        // Create a Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password'), 'user_type' => 'backend']
        );

        $superAdmin->assignRole('Super Admin');
        
        $myAdmin = User::firstOrCreate(
            ['email' => 'santanu.kundu@gmail.com'],
            ['name' => 'Santanu Kundu', 'password' => bcrypt('password'), 'user_type' => 'backend']
        );

        $myAdmin->assignRole('Super Admin');
        
        // Define roles and assign existing permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

    }
}
