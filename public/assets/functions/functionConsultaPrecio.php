<?php 
    include('C:\xampp\htdocs\CPharma\app\functions\config.php');
    include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
    include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
    include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

    $IdArticulo = $_POST["IdArticulo"];
    $precio = FG_Precio_Consultor($IdArticulo);

    $SedeConnection = FG_Mi_Ubicacion();
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = SQG_Lite_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $existencia = ($row['Existencia']) ? $row['Existencia'] : 0;

    $result = ['precio' => $precio, 'existencia' => $existencia];

    FG_Registro_Datos($IdArticulo,$precio);
    echo json_encode($result);
?>
<?php
	/**********************************************************************************/
  /*
    TITULO: FG_Registro_Datos
    FUNCION: registra el articulo escaneado en la base de datos
    DESAROLLADO POR: SERGIO COVA
  */
 	function FG_Registro_Datos($IdArticulo,$precio){
 		$SedeConnection = FG_Mi_Ubicacion();
      	$conn = FG_Conectar_Smartpharma($SedeConnection);
      	$connCPharma = FG_Conectar_CPharma();

      	$sql = SQG_Lite_Detalle_Articulo($IdArticulo);
        $result = sqlsrv_query($conn,$sql);
        $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

        $id_articulo = $row["IdArticulo"];
        $codigo_interno = $row["CodigoInterno"];
        $codigo_barra = $row["CodigoBarra"];
        $descripcion = $row["Descripcion"];
        $nombre_maquina = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $fecha_actual = new DateTime('now');
    	$fecha_completa = $fecha_actual->format("Y-m-d H:i:s");
    	$fecha_captura = $fecha_actual->format("Y-m-d");

        if (
            $nombre_maquina == 'CONSULTOR-FAU-1' ||
            $nombre_maquina == 'CONSULTOR-FAU-2' ||
            $nombre_maquina == 'CONSULTOR-FAU-3' ||

            $nombre_maquina == 'CONSULTOR-FTN-1' ||
            $nombre_maquina == 'CONSULTOR-FTN-2' ||
            $nombre_maquina == 'CONSULTOR-FTN-3' ||

            $nombre_maquina == 'CONSULTOR-FLL-1' ||
            $nombre_maquina == 'CONSULTOR-FLL-2' ||
            $nombre_maquina == 'CONSULTOR-FLL-3' ||

            $nombre_maquina == 'CONSULTOR-FSM-1' ||
            $nombre_maquina == 'CONSULTOR-FSM-2' ||
            $nombre_maquina == 'CONSULTOR-FSM-3' ||
            $nombre_maquina == 'CONSULTOR-FSM-4'
        ) {
            $sqlCPharma = "INSERT INTO consultor(id_articulo, codigo_interno, codigo_barra, descripcion, precio, nombre_maquina, fecha_captura, created_at, updated_at) VALUES ('$id_articulo','$codigo_interno','$codigo_barra','$descripcion','$precio','$nombre_maquina','$fecha_captura','$fecha_completa','$fecha_completa')";
            mysqli_query($connCPharma,$sqlCPharma);
            sqlsrv_close($conn);
            mysqli_close($connCPharma);
        }
 	}
?>
