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
        $roleMaster = Role::create(['name' => 'Master']); //All
        $roleGerente = Role::create(['name' => 'Gerente']); //Index, Show, Create, Edit, Active, Inactive
        $roleSupervisor = Role::create(['name' => 'Supervisor']); //Index, Show, Create, Edit
        $roleUsuario = Role::create(['name' => 'Usuario']); //Index, Show

        //Permisos Core General
        Permission::create(['name' => 'dashboard'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.demo.AdminLTEDemo'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.health'])->syncRoles([$roleMaster]);

        //Permisos Core Parametro
        Permission::create(['name' => 'core.parametros.index'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.parametros.show'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.parametros.create'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.parametros.edit'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.parametros.active'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.parametros.inactive'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.parametros.destroy'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.parametros.restore'])->syncRoles([$roleMaster]);

        //Permisos Core Parametro
        Permission::create(['name' => 'core.usuarios.profile'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.usuarios.index'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.usuarios.show'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.usuarios.create'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.usuarios.edit'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.usuarios.active'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.usuarios.inactive'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.usuarios.destroy'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.usuarios.restore'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.usuarios.lock'])->syncRoles([$roleMaster]);
    }
}
