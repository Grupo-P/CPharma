<?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\querys.php');
    include(app_path().'\functions\funciones.php');
    include(app_path().'\functions\reportes.php');

    $EstoyEn = MiUbicacion();
    echo'Hola estoy en: '.$EstoyEn;
?>