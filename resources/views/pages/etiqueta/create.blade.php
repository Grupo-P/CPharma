<?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\Querys.php');
    include(app_path().'\functions\funciones.php');
    include(app_path().'\functions\reportes.php');

    $SedeConnection = 'FTN';

    $conn = ConectarSmartpharma($SedeConnection);
    $connCPharma = ConectarXampp();

    $sql = QExistenciaActual();
    $result = sqlsrv_query($conn,$sql);
    
    $contador = 0;

    while(($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))&&($contador<3)) {
        $IdArticulo = $row["IdArticulo"];
        $CodigoInterno = $row["CodigoInterno"];
        $Descripcion=$row["Descripcion"];
        
        $Existencia = intval($row["Existencia"]);
        
        echo'<br><br>*************************';
        echo'<br>IdArticulo: '.$IdArticulo;
        echo'<br>CodigoInterno: '.$CodigoInterno;
        echo'<br>Descripcion: '.$Descripcion;
        echo'<br>Existencia: '.$Existencia;
        echo'<br>*************************';

        $sqlCPharma = QEtiquetaArticulo($IdArticulo);

        $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
        $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
        $IdArticuloCPharma = $RowCPharma['id_articulo'];

        if(is_null($IdArticuloCPharma)){

            $condicion = 'NO CLASIFICADO';
            $clasificacion = 'PENDIENTE';
            $estatus = 'ACTIVO';
            $user = 'SYSTEM';
            $date = new DateTime('now');
            $date = $date->format("Y-m-d H:i:s");

            $sqlCP = QGuardarEtiquetaArticulo($IdArticulo,$CodigoInterno,$Descripcion,$condicion,$clasificacion,$estatus,$user,$date);
            mysqli_query($connCPharma,$sqlCP);
        }

        $contador++;
    }

    mysqli_close($connCPharma);
    sqlsrv_close($conn);
?>