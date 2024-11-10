<?php
  include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $CodigoBarra = $_POST["codbar"];
 	if ($CodigoBarra!='') {
 		$respuesta = consultaMedicamentoCod($CodigoBarra);
	  echo $respuesta;
 	}
?>
<?php
	function consultaMedicamentoCod($CodigoBarra){
		$SedeConnection = FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

  	$sql = IdArticulo_CodigoBarra($CodigoBarra);
    $result = sqlsrv_query($conn,$sql);

    $tableResponse = '';

    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
    	$IdArticulo = $row['IdArticulo'];
    	$sql1 =	SQG_Detalle_Articulo($IdArticulo);
    	$result1 = sqlsrv_query($conn,$sql1);

    	while($row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)) {
    		$CodigoArticulo = $row1["CodigoInterno"];
        $CodigoBarra = $row1["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
        $Existencia = $row1["Existencia"];
        $ExistenciaAlmacen1 = $row1["ExistenciaAlmacen1"];
        $ExistenciaAlmacen2 = $row1["ExistenciaAlmacen2"];
        $IsTroquelado = $row1["Troquelado"];
        $IsIVA = $row1["Impuesto"];
        $UtilidadArticulo = $row1["UtilidadArticulo"];
        $UtilidadCategoria = $row1["UtilidadCategoria"];
        $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
        $PrecioCompraBrutoAlmacen1 = $row1["PrecioCompraBrutoAlmacen1"];
        $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
        $PrecioCompraBrutoAlmacen2 = $row1["PrecioCompraBrutoAlmacen2"];
        $PrecioCompraBruto = $row1["PrecioCompraBruto"];
        $Dolarizado = $row1["Dolarizado"];
        $CondicionExistencia = 'CON_EXISTENCIA';

        $fechaActual = new DateTime('now');
        $fechaActual = date_format($fechaActual,'Y-m-d');

        if($row1["UltimoLote"]!=''){
          $UltimoLote = $row1["UltimoLote"]->format('Y-m-d');
        }
        else{
          $UltimoLote = '-';
        }

        if($row1["UltimaVenta"]!=''){
          $UltimaVenta = $row1["UltimaVenta"]->format('Y-m-d');
        }
        else{
          $UltimaVenta = '-';
        }

        if($row1["UltimoProveedorNombre"]!=''){
          $NombreProveedor = $row1["UltimoProveedorNombre"];
          $IdProveedor = $row1["UltimoProveedorID"];
        }
        else{
          $NombreProveedor = '-';
          $IdProveedor = 0;
        }

        $Componente = FG_Limpiar_Texto(FG_Componente_Articulo($conn,$IdArticulo));
        $Aplicacion = FG_Limpiar_Texto(FG_Aplicacion_Articulo($conn,$IdArticulo));
        $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
        $Gravado = FG_Producto_Gravado($IsIVA);
        $TasaMercado = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
        $TasaVenta = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));
        $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
        $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
        $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
        $PrecioDolares = ($TasaVenta)?number_format($Precio/$TasaVenta,2,"," ,"." ):"0,00";

      	$tableResponse = $tableResponse. '<tr>';
        $tableResponse = $tableResponse. '<td>'.intval($CodigoArticulo).'</td>';
	      $tableResponse = $tableResponse. '<td>'.$CodigoBarra.'</td>';
	      $tableResponse = $tableResponse.
	      '<td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
              .$Descripcion
          .'</a>
        </td>';
        $tableResponse = $tableResponse. '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
        $tableResponse = $tableResponse. '<td align="center">'.$PrecioDolares.'</td>';
	      $tableResponse = $tableResponse. '<td align="center">'.$Dolarizado.'</td>';
        $tableResponse = $tableResponse. '<td align="center">'.$Gravado.'</td>';

        if($Dolarizado=='NO'){
          $tableResponse = $tableResponse. '<td align="center">'.number_format($PrecioCompraBruto,2,"," ,"." ).'</td>';
          $tableResponse = $tableResponse. '<td>-</td>';
        }
        else if($Dolarizado=='SI'){
          if($IsIVA==FG_ID_Impuesto_Sede()){
            $Costo = ($TasaMercado)?(($Precio/Impuesto) * $Utilidad) / $TasaMercado:"0";
            $tableResponse = $tableResponse. '<td>-</td>';
            $tableResponse = $tableResponse. '<td align="center">'.number_format($Costo,2,"," ,"." ).'</td>';
          }
          else{
            $Costo = ($TasaMercado)?($Precio * $Utilidad) / $TasaMercado:"0";
            $tableResponse = $tableResponse. '<td>-</td>';
            $tableResponse = $tableResponse. '<td align="center">'.number_format($Costo,2,"," ,"." ).'</td>';
          }
        }
        $tableResponse = $tableResponse.
        '<td align="center" class="CP-barrido">
        <a href="/reporte6?pedido=15&fechaInicio='.$UltimoLote.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&flag=BsqDescrip&Descrip='.$Descripcion.'&IdD='.$IdArticulo.'&CodBar=&IdCB=" style="text-decoration: none; color: black;" target="_blank">'
          .intval($Existencia).
        '</a>
        </td>';

        $tableResponse = $tableResponse.
        '<td align="center" class="CP-barrido">
        <a href="/reporte12?fechaInicio='.$UltimoLote.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'&CodBar=&IdCB=&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$UltimoLote.
        '</a>
        </td>';

        $tableResponse = $tableResponse. '<td>'.$Componente.'</td>';
        $tableResponse = $tableResponse. '<td>'.$Aplicacion.'</td>';
        $tableResponse = $tableResponse. '<td>'.$UltimaVenta.'</td>';
	      $tableResponse = $tableResponse.
        '<td align="left" class="CP-barrido">
          <a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
            .$NombreProveedor.
          '</a>
        </td>';
        $tableResponse = $tableResponse. '</tr>';
    	}
    }

    mysqli_close($connCPharma);
    sqlsrv_close($conn);
    return $tableResponse;
	}
	/**********************************************************************************/
  /*
    TITULO: R6Q_IdArticulo_CodigoBarra
    FUNCION: Busca el id del articulo dado el codigo de barra
    RETORNO: id del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function IdArticulo_CodigoBarra($CodigoBarra) {
    $sql = "
      SELECT InvCodigoBarra.InvArticuloId AS IdArticulo FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$CodigoBarra'
    ";
    return $sql;
  }
?>
