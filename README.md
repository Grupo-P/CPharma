# PBASE

Proyecto base para todos los repositorios.

## Release v.1.0.0.

- Auditorías
- Conexiones
- Dashboard
- Estados del servidor
- Hístorico de estados del servidor
- Licencia
- Parámetros
- Permisos
- Roles
- Sandboxs
- Usuarios

## Consideraciones Generales

- La creación de tablas, modelos, controladores, seeders y demás elementos deben realizarse haciendo uso de los comandos.
- Se debe respetar los prefijos de las tablas y tenerlos en cuenta para el arbol de directorios del proyecto.
- Ante alguna duda es necesario aclararla con el equipo sin tomar decisiones individuales.

## Pasos para levantar un CRUD

- Crear Migracion.
```sh
    $ php artisan make:migration create_core_conexiones_table
	$ php artisan migrate
```
    
- Crear Modelo y completar el codigo necesario.
```sh
    $ php artisan make:model Core/Conexion
```

- Crear Factory y completar el codigo necesario.
```sh
    $ php artisan make:factory Core/ConexionFactory
```

- Crear Seeder y completar el codigo necesario.
```sh
    $ php artisan make:seeder Core/ConexionSeeder
```

- Probar que lo anterior funcione correctamente.
```sh
    $ php artisan migrate:fresh --seed
```

- Crear Controlador y completar el codigo necesario. 
    Nota: No olvidar el constructor
```sh
    $ php artisan make:controller Core/ConexionController -r
```

- Crear y ajustar las routas.
```sh
    ver => routes/web.php [Seccion Conexiones]
```

- Crear y ajustar los permisos
```sh
    ver => RoleSeeder.php
    ver => RoleController.php
```

- Ajustar el menu
    Nota: Incluir el nuevo elemento al menu
```sh
    ver => config/adminlte.php
```

- Probar que lo anterior funcione correctamente.
```sh
    $ php artisan migrate:fresh --seed
```

- Crear las vistas
    Nota: Incluir el nuevo elemento al menu
```sh
    Ver => resources/views/core/conexiones
```

- Probar que lo anterior funcione correctamente.
```sh
    $ php artisan migrate:fresh --seed
```