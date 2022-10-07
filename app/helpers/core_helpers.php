<?php
if (! function_exists('application_name')) {
    function application_name()
    {
        return 'PBase';
    }
}

if (! function_exists('application_icon')) {
    function application_icon()
    {
        return 'fas fa-home';
    }
}

if (! function_exists('last_version')) {
    function last_version()
    {
        return 'v.1.0.0.';
    }
}

if (! function_exists('release_changes')) {
    function release_changes()
    {
        $relase_changes = '
        <ul>
            <li>Auditorías</li>
            <li>Conexiones</li>
            <li>Dashboard</li>
            <li>Estados del servidor</li>
            <li>Hístorico de estados del servidor</li>
            <li>Licencia</li>
            <li>Parámetros</li>
            <li>Permisos</li>
            <li>Roles</li>
            <li>Sandboxs</li>
            <li>Usuarios</li>
        </ul>
        ';
        echo $relase_changes;
    }
}
?>