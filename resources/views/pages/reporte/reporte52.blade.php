@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Lotes a la baja
	</h1>
	<hr class="row align-items-start col-12">

	<?php
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');
		include(app_path().'\functions\querys_mysql.php');
		include(app_path().'\functions\querys_sqlserver.php');

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		R52_lotes_baja($_GET['SEDE']);
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Activacion de proveedores');

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Reporte1_Activacion_Proveedores
		FUNCION: Arma el reporte de activacion de proveedores
		RETORNO: Lista de activacio de proveedores
		DESAROLLADO POR: SERGIO COVA
 	*/
	function R52_lotes_baja($SedeConnection) {
		$conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();
		$sql = R52Q_lotes_baja();
		$result = sqlsrv_query($conn,$sql);
		$contador = 1;

        echo '<table class="table table-bordered col-12 sortable">
        <thead class="thead-dark">
        <tr>
            <th scope="col" colspan="2">LEYENDA</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td scope="col">
            <ul>
                <li>
                    <span>Este reporte genera todos los lotes de articulos con existencia mayor a cero en el almacen princial con mas de un lote e indica si bajo o subio con respecto al anterior, tambien indica los % y dias transcurridos</span>
                </li>
            </ul>
            <ul>
                <li>
                    <span>A pesar de la configuracion de los articulos en el sistema, todo el analisis se hace en base a costos dolarizados segun las tasas de su momento de llegada a la tienda.</span>
                </li>
            </ul>
            </td>
        </tr>
        </tbody>
        </table>';

		echo '
		<div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
		</div>
		<br/>
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Codigo Articulo</th>
                <th scope="col" class="CP-sticky">Codigo Barra</th>
                <th scope="col" class="CP-sticky">Descripcion</th>
	      	    <th scope="col" class="CP-sticky">Numero Lote</th>
                <th scope="col" class="CP-sticky">Existencia</th>
                <th scope="col" class="CP-sticky">Costo Lote</th>
                <th scope="col" class="CP-sticky">Fecha Creacion</th>
                <th scope="col" class="CP-sticky">Fecha Entrada</th>
                <th scope="col" class="CP-sticky">Dolarizado</th>
                <th scope="col" class="CP-sticky">Costo Final $</th>
                <th scope="col" class="CP-sticky">Bajo?</th>
                <th scope="col" class="CP-sticky">Subio?</th>
                <th scope="col" class="CP-sticky">Dias</th>
                <th scope="col" class="CP-sticky">%</th>
		    </tr>
	  	</thead>
  	<tbody>
		';
        $IdArticuloAnterior = '';
        $CostoArticuloAnterior = 0;
        $lotesBaja = 0;
        $lotesAlza = 0;
        $sumPromedios = 0;
        $sumDias = 0;

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row['IdArticulo'];
            $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
            $Dolarizado = FG_Producto_Dolarizado($row["Dolarizado"]);
            $costoBs = $row['M_PrecioCompraBruto'];
            $fechaCreacion = $row['Auditoria_FechaCreacion']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$fechaCreacion);

			echo '<tr>';
            echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.intval($row['CodigoArticulo']).'</td>';
            echo '<td align="center">'.($row['CodigoBarra']).'</td>';
			echo
            '<td align="left" class="descripcion CP-barrido">
            <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
                .$Descripcion.
            '</a>
            </td>';

            echo '<td align="center">'.intval($row['numero']).'</td>';
            echo '<td align="center">'.intval($row['Existencia']).'</td>';
            echo '<td class="precio" align="center">'.number_format($costoBs,2,"," ,"." ).'</td>';
			echo '<td align="center">'.$fechaCreacion.'</td>';
            echo '<td align="center">'.($row['FechaEntrada'])->format('d-m-Y').'</td>';
			echo '<td align="center">'.$Dolarizado.'</td>';

            if($Tasa!=0){
                $costoDolar = $costoBs/$Tasa;
                echo '<td class="precio_ds" align="center">'.number_format($costoDolar,2,"," ,"." ).'</td>';
            }else{
                echo '<td align="center">-</td>';
            }

            if($IdArticuloAnterior != $IdArticulo){
                $IdArticuloAnterior = $IdArticulo;
                $CostoArticuloAnterior = $costoBs;
                echo '<td align="center">-</td>';
                echo '<td align="center">-</td>';
                echo '<td align="center">-</td>';
                echo '<td align="center">-</td>';
            }else{
                if($costoBs < $CostoArticuloAnterior){
                    echo '<td align="center"> SI </td>';
                    echo '<td align="center"> - </td>';
                    $RangoDias = $row['RangoDias'];
                    echo '<td align="center">'.intval($RangoDias).'</td>';
                    $porcentaje = (($costoBs-$CostoArticuloAnterior)/$CostoArticuloAnterior)*100;
                    echo '<td class="precio_ds" align="center">'.number_format($porcentaje,3,"," ,"." ).' %</td>';

                    $lotesBaja++;
                    $sumPromedios += $porcentaje;
                    $sumDias += $RangoDias;
                }
                else if($costoBs > $CostoArticuloAnterior){
                    echo '<td align="center"> - </td>';
                    echo '<td align="center"> SI </td>';
                    $RangoDias = $row['RangoDias'];
                    echo '<td align="center">'.intval($RangoDias).'</td>';
                    $porcentaje = (($costoBs-$CostoArticuloAnterior)/$CostoArticuloAnterior)*100;
                    echo '<td class="precio_ds" align="center">'.number_format($porcentaje,3,"," ,"." ).' %</td>';

                    $lotesAlza++;
                    $sumPromedios += $porcentaje;
                    $sumDias += $RangoDias;
                }
                else{
                    echo '<td align="center">-</td>';
                    echo '<td align="center">-</td>';
                    echo '<td align="center">-</td>';
                    echo '<td align="center">-</td>';
                }
                $IdArticuloAnterior = $IdArticulo;
                $CostoArticuloAnterior = $costoBs;
            }

			echo '</tr>';
			$contador++;
  	}
	  	echo '
  		</tbody>
		</table>';


        echo '<table class="table table-bordered sortable table-striped table-sm">
        <thead class="thead-dark">
        <tr>
            <th scope="col" colspan="2">Resumen</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td scope="col">
                Lotes Evaluados
            </td>
            <td scope="col">
                '.($contador-1).'
            </td>
        </tr>
        <tr>
            <td scope="col">
                Lotes a la baja
            </td>
            <td scope="col">
                '.$lotesBaja.'
            </td>
        </tr>
        <tr>
            <td scope="col">
                Lotes a la alza
            </td>
            <td scope="col">
                '.$lotesAlza.'
            </td>
        </tr>
        <tr>
            <td scope="col">
                Promedio de %
            </td>
            <td scope="col">
                '.$sumPromedios/($lotesBaja+$lotesAlza).'
            </td>
        </tr>
        <tr>
            <td scope="col">
                Promedio de dias
            </td>
            <td scope="col">
                '.$sumDias/($lotesBaja+$lotesAlza).'
            </td>
        </tr>
        </tbody>
        </table>';

		sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: R52Q_lotes_baja
		FUNCION: Query que genera la lista de los proveedores con la diferencia en dias desde el ultimo despacho de mercancia.
		RETORNO: Lista de proveedores con diferencia en dias respecto al dia actual
		DESAROLLADO POR: SERGIO COVA
	 */
	function R52Q_lotes_baja() {
		$sql = "
        SELECT
        InvArticulo.id as IdArticulo,
        InvArticulo.CodigoArticulo,
        (SELECT CodigoBarra
            FROM InvCodigoBarra
            WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
            AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
        InvArticulo.Descripcion,
        invlote.id as IdLote,
        invlote.numero,
        (ROUND(CAST((InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0))  AS Existencia,
        InvLote.M_PrecioCompraBruto,
        InvLote.M_PrecioTroquelado,
        InvLote.Auditoria_FechaCreacion,
        InvLote.FechaEntrada,
        --Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Dolarizados'
            OR  InvAtributo.Descripcion = 'Giordany'
            OR  InvAtributo.Descripcion = 'giordany')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
        DATEDIFF(DAY,CONVERT(DATE,InvLote.Auditoria_FechaCreacion),GETDATE()) As RangoDias
        FROM InvArticulo
        LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
        LEFT JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
        where InvLoteAlmacen.existencia >0 and InvLoteAlmacen.InvAlmacenId=1
        order by InvArticulo.id, InvLote.Auditoria_FechaCreacion
		";
		return $sql;
	}
?>
