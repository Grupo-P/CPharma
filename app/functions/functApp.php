<?php
ini_set('max_execution_time', 300);

function ConsultaDB ( $sql , $SedeConnection) {
	
	$iso_sql = utf8_decode($sql);
	$conn = FG_Conectar_Smartpharma($SedeConnection);

		if( $conn ) {					
			$stmt = sqlsrv_query( $conn, $iso_sql);
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			$i = 0;
			$final[$i] = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
			$i++;
			if (  $final[0] != NULL ) {	
				while( $result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
						$final[$i] = $result;
						$i++;
				}
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close( $conn );
			return $final;	
			} else {
				sqlsrv_free_stmt( $stmt);			
				sqlsrv_close( $conn );
				return NULL;
			}
		}else{
			die( print_r( sqlsrv_errors(), true));
		}
}

function UI_simple_creator ($componentes){
	$componentes;
	$i=0;
	$co=0;
	$tab=1;	
	$ClavesCoin = array();
	$flagRepetido = false;
	$k=0;

	if ($componentes != NULL){
		
		while (isset($componentes[$k]))  {
			$ite=0;	
			$ClavesCoin = array_keys(array_column($componentes, 0 ), $componentes[$k][0] );
			
			while (isset($ClavesCoin[$ite])) {
				if($ite == 0){
					$precio=$componentes[$ClavesCoin[$ite]][5];
				}
				else if($precio == $componentes[$ClavesCoin[$ite]][5] ){
					if (stristr($componentes[$ClavesCoin[0]][6],$componentes[$ClavesCoin[$ite]][6]) === false) {
						$componentes[$ClavesCoin[0]][6]=$componentes[$ClavesCoin[0]][6]."+".$componentes[$ClavesCoin[$ite]][6];
					} 
					
					if (stristr($componentes[$ClavesCoin[0]][11],$componentes[$ClavesCoin[$ite]][11]) === false) {
						$componentes[$ClavesCoin[0]][11]=$componentes[$ClavesCoin[0]][11]."+".$componentes[$ClavesCoin[$ite]][11];
					}
						
				} 
				else {
					$componentes[$ClavesCoin[0]][4]=$componentes[$ClavesCoin[0]][4]+$componentes[$ClavesCoin[$ite]][4];
					$precio = $componentes[$ClavesCoin[$ite]][5];
					
				}		
				$ite++;
			}	
			
			$ite = count($ClavesCoin);
			
			while ($ite > 1) {
				array_splice($componentes, $ClavesCoin[$ite-1], 1);
				$ite--;
			}
			$k++;
		}

		echo "<table cellspacing='0' cellpadding='0' id='tablaResultado' >";
		echo "<thead>";
		echo"<tr class='TituloTabla'> <th style='width:8%' class='primero'>Código</th> <th style='width:24%' class=''>Descripción</th>  <th style='width:8%' class=''>Precio</th> <th style='width:8%' class=''>Cant.</th> <th style='width:10%' class=''>Ult. Lote</th> <th style='width:15%' class=''>Componente</th> <th style='width:15%' class=''>Aplicación</th> <th style='width:4%' class=''>Recp.</th> <th style='width:4%' class=''>Ubic.</th> <th style='width:4%' class=' ultimo'><img src='css/images/carrito.jpg' height='20px' width='20px'/></th> </tr>";
		echo "<Div class='clear'></div></thead>  <tbody>";
			
			
		foreach ($componentes as list($a, $b, $c , $d, $e, $f, $g, $h, $r, $s, $w, $y)) {
		
			$comps = explode( '+', $g );
			$patos = explode( '+', $y );
			
			//colores alternos vista
			/*if (  is_int($co/2) ) {
				$color = "Color1";
			}
			else {
				$color = "Color2";
			}
			$co++;
			*/
			//echo "<tr class=' ".$color." opMe'>";
			echo "<tr >";
			echo "<td style='width:8%' class='infoNu'>".utf8_encode($a)."</td>
					<td style='width:24%' class='Descr'>".utf8_encode($c)."</td>";
			// si el precio es 0 lo pone como "-"
			if (utf8_encode($e) == 0) {
				echo"<td style='width:8%' class='infoFe'>-</td>";
			}
			else {
				switch ($w) {
					case 0:
						echo"<td style='width:8%' class='infoNu'> BsS.  ".utf8_encode(number_format($f,2,',','.'))."</td>";
						break;
					case 1:
						echo"<td style='width:8%' class='infoNu'> BsS.  ".utf8_encode(number_format($f*imp1,2,',','.'))."</td>";
						break;
				}
			}
			echo "<td style='width:8%' class='infoNu Cantdip'>".utf8_encode(intval($e))."</td>";
			
			// si la ultimo lote es 0 lo pone como "N/D" 
			if ($h == NULL) {
				
				echo"<td style='width:10%' class='infoFe'>SUPERVISOR</td>";
			}
			else 
				if ( date_format($h, 'd-m-Y') == '01-01-0001' ) {
					echo"<td style='width:10%' class='infoFe'> - </td>";
				} else {
					echo "<td style='width:10%' class='infoFe'>".date_format($h, 'Y-m-d')."</td>";
				}

			
			echo "<td style='width:15%' class='paCompo' tabindex='".$tab."'><table cellspacing='0' cellpadding='0' >";	
				foreach ($comps as $temp) {
					echo "<tr class='compo' >";
					$temp = trim($temp, " \t\n\r\0\x0B");
					echo "<td >".utf8_encode($temp)."</td>";	
					echo "</tr>";
				}		
			//$tab++;
			echo "</table></td>";

			echo "<td style='width:15%' class='paPato' tabindex='".$tab."'><table cellspacing='0' cellpadding='0' >";	
				foreach ($patos as $temp) {
					echo "<tr class='patos' >";
					$temp = trim($temp, " \t\n\r\0\x0B");
					echo "<td >".utf8_encode($temp)."</td>";	
					echo "</tr>";
				}		
			//$tab++;
			echo "</table></td>";
			
			echo "<td style='width:4%' class='infoFe'>".utf8_encode($r)."</td>";
			echo "<td style='width:4%' class='infoFe'>".utf8_encode($s)."</td>";
			
			echo "<td  style='width:4%' class='infoFe botonMas'>";
			echo "<img src='css/images/mas.png' height='15px' width='15px'/>";
			echo "</td>";
		}

		echo "</tr></tbody>";
		echo "</table>";

	} 
	
	ELSE {
		echo "<div class='menuMe'>NO SE ENCONTRARON RESULTADOS</div>";
	}

}
//end of "UI_simple_creator" method


//start of "UI_composed_creator" method
function UI_composed_creator ($componentes, $parametros, $cads, $flag, $excluirComp, $cat){
	
	
	$i=0;
	$co=0;
	$tab=1;	
	$ClavesCoin = array();
	$flagRepetido = false;
	$k=0;
	// para validar existencia en los componenetes o usos terapeuticos
	$sumCant= 0;
	
	$cadComp = $cads;
	$tempComponentes = $parametros;
	$aux1 = 6; $aux2 = 11;
	if ($flag == 1) { $aux1 = 11; $aux2= 6; }

	//buscar repetidos
	if ($componentes != NULL){
		
		while (isset($componentes[$k]))  {
			$ite=0;	
			$ClavesCoin = array_keys(array_column($componentes, 0 ), $componentes[$k][0] );
				
			while (isset($ClavesCoin[$ite])) {	
				if($ite == 0){
					$precio=$componentes[$ClavesCoin[$ite]][5];
				}
				else if($precio == $componentes[$ClavesCoin[$ite]][5] ){			
					if (stristr($componentes[$ClavesCoin[0]][$aux1],$componentes[$ClavesCoin[$ite]][$aux1]) === false) {
						$componentes[$ClavesCoin[0]][$aux1]=$componentes[$ClavesCoin[0]][$aux1]."+".$componentes[$ClavesCoin[$ite]][$aux1];
					}
					else {
						if (stristr($componentes[$ClavesCoin[0]][$aux2],$componentes[$ClavesCoin[$ite]][$aux2]) === false) {
							$componentes[$ClavesCoin[0]][$aux2]=$componentes[$ClavesCoin[0]][$aux2]."+".$componentes[$ClavesCoin[$ite]][$aux2];
						}
					}	
				}
				else {
					$componentes[$ClavesCoin[0]][4]=$componentes[$ClavesCoin[0]][4]+$componentes[$ClavesCoin[$ite]][4];
					$precio = $componentes[$ClavesCoin[$ite]][5];
					
				}		
				$ite++;
			}
			
			$ite = count($ClavesCoin);
			
			while ($ite > 1) {
				array_splice($componentes, $ClavesCoin[$ite-1], 1);
				$ite--;
			}
			$k++;
	}
	
	
	// ordenar arreglo
	$final = array();
	$ClavesCopiar = array();

	//Poner en formato utf8 tanto los componentes como los uso terapeuticos para evitar que en conteo y eliminacion de dupilcados...
	// los caracteres especiales (ejemplo: 'ñ') causen que cometa errores.
	$var_aux = 0;
	foreach($componentes as $corregir){
		$temporal_01 = utf8_encode($corregir[$aux1]);
		$temporal_02 = utf8_encode($corregir[$aux2]);
		
		$componentes[$var_aux][$aux1] = $temporal_01;
		$componentes[$var_aux][$aux2] = $temporal_02; 
		$var_aux++;
	}

	if (count($tempComponentes) == 1 ){ //revisa si solo tiene un componente la busqueda
		//buscar medicinas que coincidan todos los componenetes buscados
		$ClavesCoin = array_keys(array_column($componentes, $aux1 ), $cadComp );
		//validar si la existencia del componenete es 0 para guardarlo en fallas
		$sumCant = 0;
		foreach($ClavesCoin as $clave)
		{
			$sumCant = $sumCant + $componentes[$clave][4];
		}
		
		if ($sumCant == 0) {
			if (in_array($cadComp, $excluirComp) == false ) {
				$insertar = "INSERT INTO dbo.faltantes VALUES ('".$cadComp."', GETDATE() , '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".$cat."' )";
				InsertarDB($insertar);
			}
		}	
		
		//guarda los campos a copiar
		$ClavesCopiar = array_merge($ClavesCopiar, $ClavesCoin);

	} else 
		if ( count($tempComponentes) == 2 ) { // revisa si tiene dos componentes la busqueda
			//buscar medicinas que coincidan todos los componenetes buscados
			$ClavesCoin = array_keys(array_column($componentes, $aux1 ), $cadComp );
			
			//validar si la existencia del componenete es 0 para guardarlo en fallas
			$sumCant = 0;
			foreach($ClavesCoin as $clave)
			{
				$sumCant = $sumCant + $componentes[$clave][4];
			}
			if ($sumCant == 0) {
				if (in_array($cadComp, $excluirComp) == false ) {
					$insertar = "INSERT INTO dbo.faltantes VALUES ('".$cadComp."', GETDATE() , '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".$cat."' )";
					InsertarDB($insertar);
				}
			}	
		
			//guarda los campos a copiar
			$ClavesCopiar = array_merge($ClavesCopiar, $ClavesCoin);
			
			//buscar medicinas que tenga uno de los componentes buscados
			foreach($tempComponentes as $compo ) {
				$ClavesCoin = array_keys(array_column($componentes, $aux1 ), $compo );
				
				//validar si la existencia del componenete es 0 para guardarlo en fallas
				$sumCant = 0;
				foreach($ClavesCoin as $clave)
				{
					$sumCant = $sumCant + $componentes[$clave][4];
				}
				if ($sumCant == 0) {
					if (in_array($compo, $excluirComp) == false ) {
						$insertar = "INSERT INTO dbo.faltantes VALUES ('".$compo."', GETDATE() , '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".$cat."' )";
						InsertarDB($insertar);
					}
				}	
			
				//guarda los campos a copiar					
				$ClavesCopiar = array_merge($ClavesCopiar, $ClavesCoin);
				
			}		
		} else { //busca si tiene mas de 2 componente 
			//buscar medicinas que coincidan todos los componenetes buscados
			$ClavesCoin = array_keys(array_column($componentes, $aux1 ), $cadComp );
			
			//validar si la existencia del componenete es 0 para guardarlo en fallas
			$sumCant = 0;
			foreach($ClavesCoin as $clave)
			{
				$sumCant = $sumCant + $componentes[$clave][4];
			}
			if ($sumCant == 0) {
				if (in_array($cadComp, $excluirComp) == false ) {
					$insertar = "INSERT INTO dbo.faltantes VALUES ('".$cadComp."', GETDATE() , '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".$cat."' )";
					InsertarDB($insertar);
				}
			}	
		
			//guarda los campos a copiar
			$ClavesCopiar = array_merge($ClavesCopiar, $ClavesCoin);
			
			// buscar medicinas que tengan mas de un componente de los buscados pero no todos los buscados	
			for($x=0;$x<count($tempComponentes);$x++){
				$cadComp2 = $tempComponentes[$x];
				for($y=$x+1;$y<count($tempComponentes);$y++){	
					$cadComp2 = $cadComp2."+".$tempComponentes[$y];
					if ($cadComp2 != $cadComp){
						$ClavesCoin = array_keys(array_column($componentes, $aux1 ), $cadComp2 );
						//validar si la existencia del componenete es 0 para guardarlo en fallas
						$sumCant = 0;
						foreach($ClavesCoin as $clave)
						{
							$sumCant = $sumCant + $componentes[$clave][4];
						}
						if ($sumCant == 0) {
							if (in_array($cadComp, $excluirComp) == false ) {
								$insertar = "INSERT INTO dbo.faltantes VALUES ('".$cadComp2."', GETDATE() , '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".$cat."' )";
								InsertarDB($insertar);
							}
						}	
					
						//guarda los campos a copiar
						$ClavesCopiar = array_merge($ClavesCopiar, $ClavesCoin);
					}
				}	
			}
			
			//buscar medicinas que tenga uno de los componentes buscados
			foreach($tempComponentes as $compo ) {
				$ClavesCoin = array_keys(array_column($componentes, $aux1 ), $compo );
				
				//validar si la existencia del componenete es 0 para guardarlo en fallas
				$sumCant = 0;
				foreach($ClavesCoin as $clave)
				{
					$sumCant = $sumCant + $componentes[$clave][4];
				}
				if ($sumCant == 0) {
					if (in_array($compo, $excluirComp) == false ) {
						$insertar = "INSERT INTO dbo.faltantes VALUES ('".$compo."', GETDATE() , '".gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".$cat."' )";
						InsertarDB($insertar);
					}
				}	
			
				//guarda los campos a copiar	
				$ClavesCopiar = array_merge($ClavesCopiar, $ClavesCoin);
			}
			
		}
	//copia los resultados al nuevo arreglo a imprimir 
	foreach($ClavesCopiar as $claves ) {
		$final[]= $componentes[$claves];
	}
	//ordena las claves en orden descendente para que al borrar no perder referencia 
	rsort($ClavesCopiar);

	//borras los copiados
	foreach($ClavesCopiar as $claves ) {
		array_splice($componentes, $claves, 1);
	}
	
	// final ordenar arreglo
	
	echo "<table cellspacing='0' cellpadding='0' id='tablaResultado' >";
		echo "<thead>";
			echo"<tr class='TituloTabla'> <th style='width:8%' class='primero'>Código</th> <th style='width:24%' class=''>Descripción</th>  <th style='width:8%' class=''>Precio</th> <th style='width:8%' class=''>Cant.</th> <th style='width:10%' class=''>Ult. Lote</th> <th style='width:15%' class=''>Componente</th> <th style='width:15%' class=''>Aplicación</th> <th style='width:4%' class=''>Recp.</th> <th style='width:4%' class=''>Ubic.</th> <th style='width:4%' class=' ultimo'><img src='css/images/carrito.jpg' height='20px' width='20px'/></th> </tr>";
		echo "</thead>  <tbody>";
		
		//muestra arreglo ordenado de medicinas
		foreach ($final as list($a, $b, $c , $d, $e, $f, $g, $h, $r, $s, $w, $y)) 
		{
			$comps = explode( '+', $g );
			$patos = explode( '+', $y );
			
			//colores alternos vista
			/*
			if (  is_int($co/2) ) {
				$color = "Color1";
			}else {
				$color = "Color2";
			}
		
			$co++;
			*/
			
			//echo "<tr class=' ".$color." opMe'>";
			echo "<tr >";
			
			
			echo "<td style='width:8%' class='infoNu'>".utf8_encode($a)."</td>
					<td style='width:24%' class='Descr'>".utf8_encode($c)."</td>";
			// si el cantidad es 0 lo pone como "-"
			if (utf8_encode($e) == 0) {
				echo"<td style='width:8%' class='infoFe'>-</td>";
			}else {
				switch ($w) {
					case 0:
						echo"<td style='width:8%' class='infoNu'> BsS.  ".utf8_encode(number_format($f,2,',','.'))."</td>";
						break;
					case 1:
						echo"<td style='width:8%' class='infoNu'> BsS.  ".utf8_encode(number_format($f*imp1,2,',','.'))."</td>";
						break;
				}
			}
			echo "<td style='width:8%' class='infoNu Cantdip'>".utf8_encode(intval($e))."</td>";
			// si la ultimo lote  es 0 lo pone como "N/D" 
			if ($h == NULL) {
				
				echo"<td style='width:10%' class='infoFe'>LIBERAR</td>";
			}
			else 
				if ( date_format($h, 'd-m-Y') == '01-01-0001' ) {
					echo"<td style='width:10%' class='infoFe'> - </td>";
				} else {
					echo "<td style='width:10%' class='infoFe'>".date_format($h, 'Y-m-d')."</td>";
				}
			
			echo "<td style='width:15%' class='paCompo' tabindex='".$tab."'><table cellspacing='0' cellpadding='0' >";	
				foreach ($comps as $temp) {
					echo "<tr class='compo' >";
						$temp = trim($temp, " \t\n\r\0\x0B");
						echo "<td >".$temp."</td>";	
					echo "</tr>";
				}		
			echo "</table></td>";

			echo "<td style='width:15%' class='paPato' tabindex='".$tab."'><table cellspacing='0' cellpadding='0' >";	
				foreach ($patos as $temp) {
					echo "<tr class='patos'>";
						$temp = trim($temp, " \t\n\r\0\x0B");
						echo "<td >".$temp."</td>";	
					echo "</tr>";
				}		
			//$tab++;
			echo "</table></td>";
			
			echo "<td style='width:4%' class='infoFe'>".utf8_encode($r)."</td>";
			echo "<td style='width:4%' class='infoFe'>".utf8_encode($s)."</td>";
			
			
			echo "<td  style='width:4%' class='infoFe botonMas'>";
				echo "<img src='css/images/mas.png' height='15px' width='15px'/>";
			echo "</td>";
		}
		echo "</tr>  </tbody>";
		echo "</table>";
		
	if (count($componentes) > 0){
		echo "<div class='advertenciaM alpha omega grid_24'>¡CUIDADO! los siguientes articulos tienen mas componentes de los solicitados </div>";

		echo "<table cellspacing='0' cellpadding='0' id='tablaResultadoExtras' >";
		echo "<thead>";
			echo"<tr class='TituloTabla'> <th style='width:8%' class='primero'>Código</th> <th style='width:24%' class=''>Descripción</th>  <th style='width:8%' class=''>Precio</th> <th style='width:8%' class=''>Cant.</th> <th style='width:10%' class=''>Ult. Lote</th> <th style='width:15%' class=''>Componente</th> <th style='width:15%' class=''>Aplicación</th> <th style='width:4%' class=''>Recp.</th> <th style='width:4%' class=''>Ubic.</th> <th style='width:4%' class=' ultimo'><img src='css/images/carrito.jpg' height='20px' width='20px'/></th> </tr>";
		echo "</thead>  <tbody>";
		
		//muestra el resto del arreglo
		foreach ($componentes as list($a, $b, $c , $d, $e, $f, $g, $h, $r, $s, $w, $y)) {
			$comps = explode( '+', $g );
			$patos = explode( '+', $y );
			/*
			//colores alternos vista
			if (  is_int($co/2) ) {
				$color = "Color1";
			}else {
				$color = "Color2";
			}
			$co++;
			*/
			//echo "<tr class=' ".$color." '>";
			echo "<tr >";
			echo "<td style='width:8%' class='infoNu'>".utf8_encode($a)."</td>
					<td style='width:24%' class='Descr'>".utf8_encode($c)."</td>";
			// si el precio es 0 lo pone como "-"
			
			if (utf8_encode($e) == 0) {
				//echo"<td style='width:8%' class='opMe'>-</td>";
				echo"<td style='width:8%' >-</td>";
			}else {
				switch ($w) {
					case 0:
						echo"<td style='width:8%' class='infoNu'> BsS.  ".utf8_encode(number_format($f,2,',','.'))."</td>";
						break;
					case 1:
						echo"<td style='width:8%' class='infoNu'> BsS.  ".utf8_encode(number_format($f*imp1,2,',','.'))."</td>";
						break;
				}
			}
			echo "<td style='width:8%' class='infoNu Cantdip'>".utf8_encode(intval($e))."</td>";
			// si la ultimo lote  es 0 lo pone como "N/D" 
			if ($h == NULL) {
				
				echo"<td style='width:10%' class='infoFe'>LIBERAR</td>";
			}
			else 
				if ( date_format($h, 'd-m-Y') == '01-01-0001' ) {
					echo"<td style='width:10%' class='infoFe'> - </td>";
				} else {
					echo "<td style='width:10%' class='infoFe'>".date_format($h, 'Y-m-d')."</td>";
				}
			
			echo "<td style='width:15%' class='paCompo' tabindex='".$tab."'><table cellspacing='0' cellpadding='0' >";	
				foreach ($comps as $temp) {
					echo "<tr class='compo'>";
						$temp = trim($temp, " \t\n\r\0\x0B");
						echo "<td >".$temp."</td>";	
					echo "</tr>";
				}		
			echo "</table></td>";

			echo "<td style='width:15%' class='paPato' tabindex='".$tab."'><table cellspacing='0' cellpadding='0' >";	
				foreach ($patos as $temp) {
					echo "<tr class='patos'>";
						$temp = trim($temp, " \t\n\r\0\x0B");
						echo "<td >".$temp."</td>";	
					echo "</tr>";
				}		
			//$tab++;
			echo "</table></td>";
			
			//echo "<td style='width:4%' class='opMe'>".utf8_encode($r)."</td>";
			//echo "<td style='width:4%' class='opMe'>".utf8_encode($s)."</td>";
			echo "<td style='width:4%' >".utf8_encode($r)."</td>";
			echo "<td style='width:4%' >".utf8_encode($s)."</td>";
			
			// echo "<td  style='width:4%' class='opMe'>";
			echo "<td  style='width:4%' class='infoFe botonMas'>";
				echo "<img src='css/images/mas.png' height='15px' width='15px'/>";
			echo "</td>";
		}
		echo "</tr>  </tbody>";
		echo "</table>";
	}	
		
	} ELSE {
		echo "<div class='menuMe'>NO SE ENCONTRARON RESULTADOS DISPONIBLES A LA VENTA</div>";
		
	}

}

?>



<script>
    
//Funcion post-venta   !!! FALTA
/*
function postVenta(DOM) {
    var info = DOM.text();
    var postVenta = DOM.parent().siblings();
    
    postVenta.each(function (index) 
    { 	
        info += '~'+$(this).text();
    });
    return info;
}*/




//Devolver el medicamento/producto asociado a la busqueda secundaria
function medicamentoAsociado(DOM) {
    var temp;
    var campo = DOM.parent().parent().parent().siblings().get(1);
	temp = $(campo).text();
    
    return temp;
}

//Devolver la cantidad del medicamento/producto asociado a la busqueda secundaria
function cantMedicamentoAsociado(DOM) {
    var temp;
    var campo = DOM.parent().parent().parent().siblings().get(3);
	temp = $(campo).text();
    
    return temp;
}



//Devolver todos los componentes para rellenar el campo de busqueda
function llenarCombo (DOM) {
    var tempComponentes = DOM.text();
    var siblingsComp = DOM.siblings();
    siblingsComp.each(function (index) 
    { 	
        tempComponentes += ','+$(this).text();
    });
    return tempComponentes;
}
    
    
// click sobre el componente para buscar !!FALTA POST VENTA
$('.compo').click(function() {
	$('#input_busq').val("");
	if($(this).text() != "-"){
		
		var DOM = $(this);
        var tempComponentes = llenarCombo(DOM);
        var item = medicamentoAsociado(DOM);
		var cantItem = cantMedicamentoAsociado(DOM);

		$('.barraHistorial').append('<div class="sepHist"> > </div>');
		var historia_subsecuente = $('<div class="historia"><div class="imgHist_Comp">'+tempComponentes+'</div><div> ('+item+')</div></div>');
		$('.barraHistorial').append(historia_subsecuente);

		if (cantItem == 0){
			insertarAjax( "op="+item, "lib/analytics.php");
		}  
		
		consultaAjax( "tempComponentes="+tempComponentes , "lib/busquedaC.php", 1 , '.contApp' );
		consultaAjaxRE( "tempComponentes="+tempComponentes , "http://192.168.1.35/lib/busquedaCE.php", 0 , '.consultaEInfo' );
		consultaAjaxRE( "tempComponentes="+tempComponentes , "http://192.168.7.34/lib/busquedaCE.php", 0 , '.consultaEInfo' );
		consultaAjaxRE( "tempComponentes="+tempComponentes , "http://192.168.12.35/lib/busquedaCE.php", 0 , '.consultaEInfo' );
		ajustarTamano();
	}
});

// click sobre el uso terapeutico para buscar !!FALTA POST VENTA
$('.patos' ).click(function() {
	$('#input_busq').val("");
	if($(this).text() != "-"){
		
		var DOM = $(this);
        var tempUsoTerapeutico = llenarCombo(DOM);
		var item = medicamentoAsociado(DOM);
        var cantItem = cantMedicamentoAsociado(DOM);
        
        $('.barraHistorial').append('<div class="sepHist"> > </div>');
		var historia_subsecuente = $('<div class="historia"><div class="imgHist_Tera">'+tempUsoTerapeutico+'</div><div> ('+item+')</div></div>');
		$('.barraHistorial').append(historia_subsecuente);
		ajustarTamano();

		if (cantItem == 0){
			insertarAjax( "op="+item, "lib/analytics.php");
		}  
		consultaAjax( "tempPatologias="+tempUsoTerapeutico , "lib/busquedaP.php", 1 , '.contApp' );
	}
});
//darle enter a un componente para buscar 
$('.paCompo').keyup(function(e){
    if(e.keyCode == 13) {
        $('#input_busq').val("");
        if($(this).text() != "-") {
            
			var DOM = $(this).find("tr:first");
            var tempComponentes = llenarCombo(DOM);
			var item = medicamentoAsociado(DOM);
			var cantItem = cantMedicamentoAsociado(DOM);
            
			$('.barraHistorial').append('<div class="sepHist"> > </div>');
			var historia_subsecuente = $('<div class="historia"><div class="imgHist_Comp">'+tempComponentes+'</div><div> ('+item+')</div></div>');
			$('.barraHistorial').append(historia_subsecuente);
			ajustarTamano();

		if (cantItem == 0){
			insertarAjax( "op="+item, "lib/analytics.php");
		}  
            consultaAjax( "tempComponentes="+tempComponentes , "lib/busquedaC.php", 1 , '.contApp' );
        }
    }
});
//darle enter a un uso terapeutico para buscar 
$('.paPato').keyup(function(e){
    if(e.keyCode == 13) {
        $('#input_busq').val("");
        if($(this).text() != "-") {
            
			var DOM = $(this).find("tr:first");
            var tempUsoTerapeutico = llenarCombo(DOM);
			var item = medicamentoAsociado(DOM);
			var cantItem = cantMedicamentoAsociado(DOM);
            
            $('.barraHistorial').append('<div class="sepHist"> > </div>');
			var historia_subsecuente = $('<div class="historia"><div class="imgHist_Tera">'+tempUsoTerapeutico+'</div><div> ('+item+')</div></div>');
			$('.barraHistorial').append(historia_subsecuente);

			if (cantItem == 0){
				insertarAjax( "op="+item, "lib/analytics.php");
			}  
            consultaAjax( "tempPatologias="+tempUsoTerapeutico , "lib/busquedaP.php", 1 , '.contApp' );
			ajustarTamano();
        }
    }
});
			

// agregar al Carrito de Compra/listaprueba

$( '.botonMas' ).click(function() {
	var agarrar = $(this).parent();

	if ( $(agarrar).children('.Cantdip').text() == 0) 
	{
		 $(agarrar).effect("highlight", {color: 'red'}, 600);
	} else 
	{	
		var repetido = null;
		$('.opWi').each( function()
		{ 
			if ( parseInt($(this).children('td:first-child').text() ) ==  parseInt($(agarrar).children('td:first-child').text() )   )
			{
				repetido = $(this);
			}
		});
		
			if (  repetido != null  )
			{
				 $(repetido).children('.Cantdip').children('.cantPe').val( parseInt($(repetido).children('.Cantdip').children('.cantPe').val()) + 1 );
				 $(repetido).children('.Cantdip').children('.cantPe').blur().focus();
			} else 
			{
				$(agarrar).clone(true, true).appendTo( '.contItems');
				$('.contItems > tr:last-child').removeClass('even');
				$('.contItems > tr:last-child').removeClass('odd');
				$('.contItems > tr:last-child').addClass('opWi');	
				$('.contItems > tr:last-child td.Descr').width($('#tablaResultado  tr  .Descr').width());
				$('.contItems > tr:last-child > td:last-child ').removeClass('botonMas').addClass('botonMenos').empty().append('<img src="css/images/menos.png" height="15px" width="15px">');		
				$('.contItems > tr:last-child > td:nth-child(4) ').empty().append('<input class="cantPe" type="number" value="1">');
				$('.contItems > tr:last-child > td:nth-child(4) input').focus();
				
				//ajustar tamaño del carrito de compras  <25% es el tamaño max del carrito
				if (parseInt($(window).height()*0.25) == parseInt($('.Dash').height() ) ) {
					$('.contItems').css( 'height', parseInt($('.Dash').height() )  - parseInt($('.tituPri').height() )) ;
					ajustarTamano();
				} else {
					ajustarTamano();
				}
			};
		
				
		
	}	
});




//re ordenar tabla
$.tablesorter.defaults.widgets = ['zebra']; 
$('#tablaResultado').tablesorter({ 
            headers: { 
                2: { 
                    sorter:'currency' 
                }, 
				4: { 
                    sorter:'isoDate' 
                } 
            } 
        }); 



$.tablesorter.defaults.widgets = ['zebra']; 
$('#tablaResultadoExtras').tablesorter({ 
            headers: { 
                2: { 
                    sorter:'currency' 
                }, 
				4: { 
                    sorter:'isoDate' 
                } 
            } 
        }); 




/*
$( '.TituloTabla' ).click(function() {
	acomodarColorM($('.opMe'));
});
*/


// click sobre la historia de componenetes para repetir esa busqueda !!FALTA POST VENTA
$(".imgHist_Comp").unbind();
$('.imgHist_Comp').click(function() {
	$('#input_busq').val("");
		
		var DOM = $(this);
		var tempComponentes = DOM.text();
        var tempItem = DOM.siblings().get(0);
		var item = $(tempItem).text();
		//var info = postVenta(DOM);

		$('.barraHistorial').append('<div class="sepHist"> > </div>');
		var historia_subsecuente = $('<div class="historia"><div class="imgHist_Comp">'+tempComponentes+'</div><div> '+item+'</div></div>');
		$('.barraHistorial').append(historia_subsecuente);

       // insertarAjax( "op="+info, "lib/analytics.php");
		consultaAjax( "tempComponentes="+tempComponentes, "lib/busquedaC.php", 1 , '.contApp' );
		ajustarTamano();
});

// click sobre la historia de componenetes para repetir esa busqueda !!FALTA POST VENTA
$(".imgHist_Tera").unbind();
$('.imgHist_Tera').click(function() {
	$('#input_busq').val("");
		
		var DOM = $(this);
		var tempComponentes = DOM.text();
        var tempItem = DOM.siblings().get(0);
		var item = $(tempItem).text();
		//var info = postVenta(DOM);

		$('.barraHistorial').append('<div class="sepHist"> > </div>');
		var historia_subsecuente = $('<div class="historia"><div class="imgHist_Tera">'+tempComponentes+'</div><div> '+item+'</div></div>');
		$('.barraHistorial').append(historia_subsecuente);

       // insertarAjax( "op="+info, "lib/analytics.php");
		consultaAjax( "tempPatologias="+tempComponentes , "lib/busquedaP.php", 1 , '.contApp' );
		ajustarTamano();
});

//$(".hOrigen").unbind();
// click sobre la historia de productos para repetir esa busqueda !!FALTA POST VENTA
$(".imgHist_Nom").unbind();
$('.imgHist_Nom').click(function() {
	$('#input_busq').val("");
		
		var DOM = $(this);
		var tempComponentes = DOM.text();
        //var tempItem = DOM.siblings().get(0);
		var item = "(Coincidencia textual)";
		//var info = postVenta(DOM);

		$('.barraHistorial').append('<div class="sepHist"> > </div>');
		var historia_subsecuente = $('<div class="historia"><div class="imgHist_Nom">'+tempComponentes+'</div><div> '+item+'</div></div>');
		$('.barraHistorial').append(historia_subsecuente);

       // insertarAjax( "op="+info, "lib/analytics.php");
		consultaAjax( "op="+tempComponentes , "lib/busquedaM.php", 1 , '.contApp' );
		ajustarTamano();
});


//ocultar existencias en 0

$('#ocultarExistencia').click(function() {
	ocultarCeros();
});

</script>