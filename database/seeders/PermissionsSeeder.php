<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    private array $data = [];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->loadData();
        $this->seedRoles();
    }

    private function loadData(): void
    {
        $this->data = permissions_roles();
    }

    private function seedRoles():void{
        foreach ($this->data as $roleName => $permissions){
            $role = Role::create(['name'=>$roleName, 'guard_name'=>'api']);
            $this->seedRolePermissions($role,$permissions);
        }
    }

    private function seedRolePermissions(Role $role, array $modelPermissions): void
    {
        foreach ($modelPermissions as $model => $permission) {
            $buildedPermissions = collect($permission)
                ->crossJoin($model)
                ->map(function ($item) {
                    $permission = implode('-', $item);
                    Permission::findOrCreate($permission, 'api');

                    return $permission;
                })->toArray();

            $role->givePermissionTo($buildedPermissions);
        }
    }
}
