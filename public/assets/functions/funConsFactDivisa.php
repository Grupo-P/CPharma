<?php
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $cajaId = $_POST["cajaId"];
	$respuesta = array();
  $respuesta = FG_Articulos_Sugeridos($cajaId);
    if( (is_array($respuesta)) && (!empty($respuesta)) ){
      echo json_encode($respuesta);
    }
    else{
      echo json_encode('UNICO');
    }
?>
<?php
  /**********************************************************************************/
  /*
    TITULO: SQL_Consulta_Caja
    FUNCION: busca el detalle temporal de las facturas en curso en la caja seleccionada
    DESAROLLADO POR: SERGIO COVA
  */
  function SQL_Consulta_Caja($cajaId){
    $sql = "
    SELECT TOP 1
    VenCaja.CodigoCaja AS NombreCaja,
    (ROUND(CAST((VenVenta.M_MontoTotalVenta) AS DECIMAL(38,2)),2,0)) AS TotalFactura,
    GenPersona.Nombre AS NombreCliente,
    GenPersona.Apellido AS ApellidoCliente
    FROM VenCaja
    INNER JOIN VenVenta ON VenVenta.VenCajaId = VenCaja.Id
    LEFT JOIN VenCliente ON VenCliente.Id = VenVenta.VenClienteId
    LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
    WHERE
    (VenCaja.Id = '$cajaId')
    AND (estadoVenta = 1)
    ORDER BY VenVenta.FechaDocumentoVenta DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SQL_Articulos_Sugeridos
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function FG_Articulos_Sugeridos($cajaId){
    $arrayFactura = array();

    $RutaUrl = FG_Mi_Ubicacion();
    //$RutaUrl = 'FAU';
    $SedeConnection = $RutaUrl;
  	$conn = FG_Conectar_Smartpharma($SedeConnection);

  	$sql = SQL_Consulta_Caja($cajaId);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
    $NombreCompleto = "".FG_Limpiar_Texto($row['NombreCliente'])." ".FG_Limpiar_Texto($row['ApellidoCliente']);

    $arrayFactura["NombreCaja"] = $row['NombreCaja']; 
    $arrayFactura["TotalFactura"] = $row['TotalFactura'];
    $arrayFactura["NombreCliente"] = $NombreCompleto;

    return $arrayFactura;
  }
?>
