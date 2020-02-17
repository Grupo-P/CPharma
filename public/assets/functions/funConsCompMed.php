<?php 
  include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $Descripcion = $_POST["Descripcion"];
 	if ($Descripcion!='') {
 		$respuesta = consultaMedicamento($Descripcion);
	  echo $respuesta;
 	}
?>
<?php
	function consultaMedicamento($Descripcion){ 
		$SedeConnection = FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

  	$sql = Descripcion_Like($Descripcion);
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

        if($row1["UltimoLote"]!=''){
          $UltimoLote = $row1["UltimoLote"]->format('d-m-Y');
        }
        else{
          $UltimoLote = '-';
        }

        if($row1["UltimaVenta"]!=''){
          $UltimaVenta = $row1["UltimaVenta"]->format('d-m-Y');
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

      	$tableResponse = $tableResponse. '<tr>';
        $tableResponse = $tableResponse. '<td>'.$CodigoArticulo.'</td>';
	      $tableResponse = $tableResponse. '<td>'.$CodigoBarra.'</td>';
	      $tableResponse = $tableResponse.
	      '<td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
              .$Descripcion
          .'</a>
        </td>';
        $tableResponse = $tableResponse. '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>'; 
        $tableResponse = $tableResponse. '<td align="center">'.number_format($Precio/$TasaVenta,2,"," ,"." ).'</td>';
	      $tableResponse = $tableResponse. '<td align="center">'.$Dolarizado.'</td>';
        $tableResponse = $tableResponse. '<td align="center">'.$Gravado.'</td>';

        if($Dolarizado=='NO'){
          $tableResponse = $tableResponse. '<td align="center">'.number_format($PrecioCompraBruto,2,"," ,"." ).'</td>';
          $tableResponse = $tableResponse. '<td>-</td>';
        }
        else if($Dolarizado=='SI'){
          if($IsIVA==1){
            $Costo = (($Precio/Impuesto) * $Utilidad) / $TasaMercado;
            $tableResponse = $tableResponse. '<td>-</td>';
            $tableResponse = $tableResponse. '<td align="center">'.number_format($Costo,2,"," ,"." ).'</td>';
          }
          else{
            $Costo = ($Precio * $Utilidad) / $TasaMercado;
            $tableResponse = $tableResponse. '<td>-</td>';
            $tableResponse = $tableResponse. '<td align="center">'.number_format($Costo,2,"," ,"." ).'</td>';
          }
        }
        $tableResponse = $tableResponse. '<td align="center">'.intval($Existencia).'</td>';
        $tableResponse = $tableResponse. '<td>'.$UltimoLote.'</td>';
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
	/**********************************************************************/
	function Descripcion_Like($DescripLike) {

    $palabras = array();
    $palabras = explode(" ", $DescripLike);

    if(count($palabras)>1){
      $sql = "
        SELECT
        InvArticulo.Id AS IdArticulo
        FROM InvArticulo";

      for($i = 0; $i<count($palabras); $i++ ){
        if($i == 0){
          $sql = $sql." WHERE InvArticulo.Descripcion LIKE '%$palabras[$i]%'";
        }
        else{
          $sql = $sql." AND InvArticulo.Descripcion LIKE '%$palabras[$i]%'";
        }
      }
    }
    else{
      $sql = "
        SELECT
        InvArticulo.Id AS IdArticulo
        FROM InvArticulo
        WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
      ";   
    }
    return $sql;
  }
?>