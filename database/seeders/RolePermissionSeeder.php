<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions_list = [];
        $permission = Permission::create(['name' => 'add invoice','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;

        $permission = Permission::create(['name' => 'edit invoice','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;

        $permission = Permission::create(['name' => 'view all invoices','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;
        
        $permission = Permission::create(['name' => 'delete invoice','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;

        $permission = Permission::create(['name' => 'add user','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;

        $permission = Permission::create(['name' => 'edit user','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;

        $permission = Permission::create(['name' => 'view all users','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;
        
        $permission = Permission::create(['name' => 'delete user','guard_name' => 'sanctum']);
        $permissions_list[] = $permission->id;

        $user = \App\Models\User::find(1);
        $user->permissions()->attach($permissions_list);

		
    }
}
