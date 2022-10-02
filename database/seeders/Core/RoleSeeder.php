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
        //Roles
        $roleMaster = Role::create(['name' => 'Master']); //All
        $roleGerente = Role::create(['name' => 'Gerente']); //Index, Show, Create, Edit, Active, Inactive
        $roleSupervisor = Role::create(['name' => 'Supervisor']); //Index, Show, Create, Edit
        $roleUsuario = Role::create(['name' => 'Usuario']); //Index, Show

        //Permisos Core General
        Permission::create(['name' => 'dashboard', 'description' => 'Dashboard'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.health', 'description' => 'Estado del servidor'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.demo.sandbox1', 'description' => 'Sandbox 1'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.demo.sandbox2', 'description' => 'Sandbox 2'])->syncRoles([$roleMaster]);

        //Permisos Core Menús
        Permission::create(['name' => 'menu.configuraciones', 'description' => 'Menú Configuraciones'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'menu.credenciales', 'description' => 'Menú Credenciales'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'menu.herramientas', 'description' => 'Menú Herramientas'])->syncRoles([$roleMaster]);

        //Permisos Core Usuarios
        Permission::create(['name' => 'core.usuarios.index', 'description' => 'Lista de usuarios'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.usuarios.show', 'description' => 'Ver usuario'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.usuarios.create', 'description' => 'Crear usuario'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.usuarios.edit', 'description' => 'Editar usuario'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.usuarios.active', 'description' => 'Activar usuario'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.usuarios.inactive', 'description' => 'Inactivar usuario'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.usuarios.destroy', 'description' => 'Borrar usuario'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.usuarios.restore', 'description' => 'Restaurar usuario'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.usuarios.profile', 'description' => 'Perfil de usuario'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.usuarios.lock', 'description' => 'Solicitar cambio de contraseña'])->syncRoles([$roleMaster]);

        //Permisos Core Parámetro
        Permission::create(['name' => 'core.parametros.index', 'description' => 'Lista de parámetros'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.parametros.show', 'description' => 'Ver parámetros'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.parametros.create', 'description' => 'Crear parámetro'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.parametros.edit', 'description' => 'Editar parámetro'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.parametros.active', 'description' => 'Activar parámetro'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.parametros.inactive', 'description' => 'Inactivar parámetro'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.parametros.destroy', 'description' => 'Borrar parámetro'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.parametros.restore', 'description' => 'Restaurar parámetro'])->syncRoles([$roleMaster]);

        //Permisos Core Roles
        Permission::create(['name' => 'core.roles.index', 'description' => 'Lista de roles'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.roles.show', 'description' => 'Ver rol'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.roles.create', 'description' => 'Crear rol'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.roles.edit', 'description' => 'Editar rol'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.roles.active', 'description' => 'Activar rol'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.roles.inactive', 'description' => 'Inactivar rol'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.roles.destroy', 'description' => 'Borrar rol'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.roles.restore', 'description' => 'Restaurar rol'])->syncRoles([$roleMaster]);

        //Permisos Core Permisos
        Permission::create(['name' => 'core.permisos.index', 'description' => 'Lista de permisos'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.permisos.show', 'description' => 'Ver permisos'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.permisos.create', 'description' => 'Crear permiso'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.permisos.edit', 'description' => 'Editar permiso'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.permisos.active', 'description' => 'Activar permiso'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.permisos.inactive', 'description' => 'Inactivar permiso'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.permisos.destroy', 'description' => 'Borrar permiso'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.permisos.restore', 'description' => 'Restaurar permiso'])->syncRoles([$roleMaster]);        

        //Permisos Core Licencia
        Permission::create(['name' => 'core.licencias.index', 'description' => 'Lista de licencias'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.licencias.edit', 'description' => 'Editar licencia'])->syncRoles([$roleMaster]);

        //Permisos Core Conexión
        Permission::create(['name' => 'core.conexiones.index', 'description' => 'Lista de conexiones'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.conexiones.show', 'description' => 'Ver conexiones'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor,$roleUsuario]);
        Permission::create(['name' => 'core.conexiones.create', 'description' => 'Crear conexión'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.conexiones.edit', 'description' => 'Editar conexión'])->syncRoles([$roleMaster,$roleGerente,$roleSupervisor]);
        Permission::create(['name' => 'core.conexiones.active', 'description' => 'Activar conexión'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.conexiones.inactive', 'description' => 'Inactivar conexión'])->syncRoles([$roleMaster,$roleGerente]);
        Permission::create(['name' => 'core.conexiones.destroy', 'description' => 'Borrar conexión'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.conexiones.restore', 'description' => 'Restaurar conexión'])->syncRoles([$roleMaster]);

        //Permisos Core Auditoría
        Permission::create(['name' => 'core.auditorias.index', 'description' => 'Lista de auditorias'])->syncRoles([$roleMaster]);
        Permission::create(['name' => 'core.auditorias.show', 'description' => 'Ver auditoría'])->syncRoles([$roleMaster]);
    }
}
