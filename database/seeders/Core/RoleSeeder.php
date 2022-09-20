<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Roles Demos
        $role1 = Role::create(['name' => 'Master']);
        $role2 = Role::create(['name' => 'Usuario']);

        //Permisos Core General
        Permission::create(['name' => 'dashboard']);
        Permission::create(['name' => 'core.demo.AdminLTEDemo']);
        Permission::create(['name' => 'core.health']);
    
        //Permisos Core Parametro
        Permission::create(['name' => 'core.parametros.index']);
        Permission::create(['name' => 'core.parametros.create']);
        Permission::create(['name' => 'core.parametros.edit']);
        Permission::create(['name' => 'core.parametros.destroy']);
        Permission::create(['name' => 'core.parametros.restore']);
        Permission::create(['name' => 'core.parametros.active']);
        Permission::create(['name' => 'core.parametros.inactive']);
    }
}
