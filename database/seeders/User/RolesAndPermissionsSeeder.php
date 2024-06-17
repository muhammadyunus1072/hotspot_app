<?php

namespace Database\Seeders\User;

use App\Helpers\PermissionHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            PermissionHelper::ACCESS_DASHBOARD => [PermissionHelper::TYPE_READ],
            PermissionHelper::ACCESS_USER => PermissionHelper::TYPE_ALL,
            PermissionHelper::ACCESS_ROLE => PermissionHelper::TYPE_ALL,
            PermissionHelper::ACCESS_PERMISSION => PermissionHelper::TYPE_ALL,

            PermissionHelper::ACCESS_MONTHLY_HOTSPOT => PermissionHelper::TYPE_ALL,
            PermissionHelper::ACCESS_PRODUCT => PermissionHelper::TYPE_ALL,
            PermissionHelper::ACCESS_PAYMENT_METHOD => PermissionHelper::TYPE_ALL,
            PermissionHelper::ACCESS_TRANSACTION => PermissionHelper::TYPE_ALL,
            PermissionHelper::ACCESS_HOTSPOT_MEMBER => PermissionHelper::TYPE_ALL,

            PermissionHelper::ACCESS_BILL => PermissionHelper::TYPE_ALL,
        ];
        foreach ($permissions as $access => $types) {
            foreach ($types as $type) {
                Permission::create(['name' => PermissionHelper::transform($access, $type)]);
            }
        }

        // create role
        $roles = [
            "Admin" => [
                PermissionHelper::ACCESS_DASHBOARD => [PermissionHelper::TYPE_READ],
                PermissionHelper::ACCESS_USER => PermissionHelper::TYPE_ALL,
                PermissionHelper::ACCESS_PERMISSION => PermissionHelper::TYPE_ALL,
                PermissionHelper::ACCESS_ROLE => PermissionHelper::TYPE_ALL,
                
                PermissionHelper::ACCESS_MONTHLY_HOTSPOT => PermissionHelper::TYPE_ALL,
                PermissionHelper::ACCESS_PRODUCT => PermissionHelper::TYPE_ALL,
                PermissionHelper::ACCESS_PAYMENT_METHOD => PermissionHelper::TYPE_ALL,
                PermissionHelper::ACCESS_TRANSACTION => PermissionHelper::TYPE_ALL,
                PermissionHelper::ACCESS_HOTSPOT_MEMBER => PermissionHelper::TYPE_ALL,
            ],
            "Member" => [
                PermissionHelper::ACCESS_DASHBOARD => [PermissionHelper::TYPE_READ],
                PermissionHelper::ACCESS_BILL => [PermissionHelper::TYPE_READ]
            ]
        ];
        foreach ($roles as $name => $permissions) {
            $role = Role::create(['name' => $name]);

            foreach ($permissions as $access => $types) {
                foreach ($types as $type) {
                    $role->givePermissionTo(PermissionHelper::transform($access, $type));
                }
            }
        }
    }
}
