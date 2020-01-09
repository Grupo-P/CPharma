<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $IdArticulo = $_POST["IdArticulo"];
  $clasificacion = $_POST["clasificacion"];
  $tipo = $_POST["tipo"];
  $dia = $_POST["dia"];
  $Etiqueta = FG_Generer_Etiqueta_Unica($IdArticulo,$clasificacion,$tipo,$dia);
  echo''.$Etiqueta;
?>
<?php
/**********************************************************************************/
	/*
		TITULO: FG_Generer_Etiqueta_Unica
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		DESARROLLADO POR: SERGIO COVA
 	*/
	function FG_Generer_Etiqueta_Unica($IdArticulo,$clasificacion,$tipo,$dia) {
		$SedeConnection = 'FTN';//FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
  	$connCPharma = FG_Conectar_CPharma();
  	$arraySugeridos = array();
		$Etiqueta = '';	

  	$FHoy = date("Y-m-d");
		$FManana = date("Y-m-d",strtotime($FHoy."+1 days"));
		$FAyer = date("Y-m-d",strtotime($FHoy."-1 days"));

		if($dia=='HOY'){
			$arraySugeridos = FG_Obtener_Casos_Etiqueta($conn,$FHoy,$FManana);
			$ArrayUnique = unique_multidim_array($arraySugeridos,'IdArticulo');
			$FechaCambio = $FHoy;
  	}
  	else if($dia=='AYER'){
  		$arraySugeridos = FG_Obtener_Casos_Etiqueta($conn,$FAyer,$FHoy);
  		$ArrayUnique = unique_multidim_array($arraySugeridos,'IdArticulo');
			$FechaCambio = $FAyer;
  	}

  	$result1 = $connCPharma->query("SELECT COUNT(*) AS Cuenta FROM etiquetas WHERE id_articulo = '$IdArticulo' AND clasificacion = '$clasificacion'");
		$row1= $result1->fetch_assoc();
		$Cuenta = $row1['Cuenta'];

		if($Cuenta==1){

			foreach ($ArrayUnique as $Array) {
		    if (in_array($IdArticulo,$Array)) {
		    	$Dolarizado = FG_Producto_Dolarizado($Array['Dolarizado']);

		    	if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){
						$Etiqueta = FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio);
					}
					else if(($Dolarizado=='NO')&&($tipo!='DOLARIZADO')){
						$Etiqueta = FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio);
					}
					else if($tipo=='TODO'){
						$Etiqueta = FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio);
					}
					else{
						$Etiqueta = 'EL ARTICULO NO PERTENECE A LA CATEGORIA SELECCIONADA';
					}
		    }
	   	}
		}
		else{
			$Etiqueta = 'EL ARTICULO NO PERTENECE A LA CATEGORIA SELECCIONADA';
		}
		
		return $Etiqueta;
		mysqli_close($connCPharma);
    sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Etiqueta_Unica
		FUNCION: crea la etiqueta para el caso que corresponda
		DESARROLLADO POR: SERGIO COVA
 	*/
 	function FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,$IsPrecioAyer,$FechaCambio) {
 		$Etiqueta = '';

 		$sql2 = SQG_Detalle_Articulo($IdArticulo);
		$result2 = sqlsrv_query($conn,$sql2);
		$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

		$CodigoArticulo = $row2["CodigoInterno"];
    $CodigoBarra = $row2["CodigoBarra"];
    $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
    $Existencia = $row2["Existencia"];
    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
    $IsTroquelado = $row2["Troquelado"];
    $IsIVA = $row2["Impuesto"];
    $UtilidadArticulo = $row2["UtilidadArticulo"];
    $UtilidadCategoria = $row2["UtilidadCategoria"];
    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
    $CondicionExistencia = 'CON_EXISTENCIA';

		if(intval($Existencia)>0){

			$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

			if($IsPrecioAyer==true){

				$sqlCC = MySQL_DiasCero_PrecioAyer($IdArticulo,$FechaCambio);
				$resultCC = mysqli_query($connCPharma,$sqlCC);
				$rowCC = mysqli_fetch_assoc($resultCC);
				$PrecioAyer = $rowCC["precio"];

				if( floatval(round($PrecioHoy,2)) != floatval($PrecioAyer) ){

					if($Dolarizado=='SI'){
						$simbolo = '*';
					}
					else{
						$simbolo = '';
					}
					
					$Etiqueta = $Etiqueta.'
						<table class="etq" style="display: inline;">
							<thead class="etq">
								<tr>
									<td class="centrado titulo rowCenter" colspan="2">
										Código: '.$CodigoBarra.'
									</td>
								</tr>	
							</thead>
							<tbody class="etq">
								<tr rowspan="2">
									<td class="centrado descripcion aumento rowCenter" colspan="2">
										<strong>'.$Descripcion.'</strong> 
									</td>
								</tr>
								';
									if( floatval(round($PrecioHoy,2)) < floatval($PrecioAyer) ){
									$Etiqueta = $Etiqueta.'
										<tr>
											<td class="izquierda rowIzq rowIzqA" style="color:red;">
												Precio Bs. Antes
											</td>
											<td class="derecha rowDer rowDerA" style="color:red;">
												<del>
												'.number_format ($PrecioAyer,2,"," ,"." ).'
												</del>
											</td>
										</tr>
									';
									}
								$Etiqueta = $Etiqueta.'
								<tr>
									<td class="izquierda rowIzq rowIzqA aumento">
										<strong>Total a Pagar Bs.</strong>
									</td>
									<td class="derecha rowDer rowDerA aumento">
										<strong>
										'.number_format ($PrecioHoy,2,"," ,"." ).'
										</strong>
									</td>
								</tr>
								<tr>
									<td class="izquierda dolarizado rowIzq rowIzqA">
									</td>
									<td class="derecha rowDer rowDerA">
										<strong>'.$simbolo.'</strong> '.date("d-m-Y").'
									</td>
								</tr>				
							</tbody>
						</table>
					';
				}
				else{
					$Etiqueta = 'EL ARTICULO NO POSEE CAMBIO DE PRECIO';
				}
			}
			else if($IsPrecioAyer==false){

				if($Dolarizado=='SI'){
					$simbolo = '*';
				}
				else{
					$simbolo = '';
				}
				
				$Etiqueta = $Etiqueta.'
					<table class="etq" style="display: inline;">
						<thead class="etq">
							<tr>
								<td class="centrado titulo rowCenter" colspan="2">
									Código: '.$CodigoBarra.'
								</td>
							</tr>	
						</thead>
						<tbody class="etq">
							<tr rowspan="2">
								<td class="centrado descripcion aumento rowCenter" colspan="2">
									<strong>'.$Descripcion.'</strong> 
								</td>
							</tr>
							<tr>
								<td class="izquierda rowIzq rowIzqA aumento">
									<strong>Total a Pagar Bs.</strong>
								</td>
								<td class="derecha rowDer rowDerA aumento">
									<strong>
									'.number_format ($PrecioHoy,2,"," ,"." ).'
									</strong>
								</td>
							</tr>
							<tr>
								<td class="izquierda dolarizado rowIzq rowIzqA">
								</td>
								<td class="derecha rowDer rowDerA">
									<strong>'.$simbolo.'</strong> '.date("d-m-Y").'
								</td>
							</tr>				
						</tbody>
					</table>
				';
			}	
		}
		else{
			$Etiqueta = 'EL ARTICULO NO POSEE EXISTENCIA';
		}
		return $Etiqueta;
 	}
?>