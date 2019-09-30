@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
    </script>

    <style>
    	* {
	    	box-sizing: border-box;
	    }

        input {
			border: 1px solid transparent;
			background-color: #f1f1f1;
			border-radius: 5px;
			padding: 10px;
			font-size: 16px;
        }

        input[type=text] {
			background-color: #f1f1f1;
			width: 100%;
        }

        .barrido {
            text-decoration: none;
            transition: width 1s, height 1s, transform 1s;
        }

        .barrido:hover {
            text-decoration: none;
            transition: width 1s, height 1s, transform 1s;
            transform: translate(20px,0px);
        }
    </style>
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice"></i>
        Productos para surtir
    </h1>
    <hr class="row align-items-start col-12">
    
    <?php
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\querys.php');
        include(app_path().'\functions\funciones.php');

        //--------- Borrar esta linea ---------//
        $_GET['SEDE'] = 'FTN';
        //--------- Borrar esta linea ---------//

        if(isset($_GET['fechaInicio'])) {

            $InicioCarga = new DateTime("now");

            if(isset($_GET['SEDE'])) {
                
                echo '
                	<h1 class="h5 text-success" align="left">
                		<i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE'])
                	.'</h1>
                ';
            }

            echo '<hr class="row align-items-start col-12">';

            R9_Productos_Surtir($_GET['SEDE'], $_GET['fechaInicio'], $_GET['fechaFin']);
        	GuardarAuditoria('CONSULTAR', 'REPORTE', 'Productos para surtir');

            $FinCarga = new DateTime("now");
            $IntervalCarga = $InicioCarga->diff($FinCarga);
            echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
        }
        else {
            if(isset($_GET['SEDE'])) {

                echo '
                	<h1 class="h5 text-success" align="left">
                		<i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE'])
                	.'</h1>
                ';
            }
            echo '<hr class="row align-items-start col-12">';

            echo '
                <form autocomplete="off" action="" target="_blank">
                    <table style="width:100%;">
                        <tr>
                            <td align="center">Fecha Inicio:</td>
                            <td>
                            	<input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
                            </td>
                            <td align="center">Fecha Fin:</td>
                            <td align="right">
                            	<input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
                            </td>
                            <td align="right">
                            	<input id="SEDE" name="SEDE" type="hidden" value="';
	                            	print_r($_GET['SEDE']);
	                            echo'">
                            	<input type="submit" value="Buscar" class="btn btn-outline-success">
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }
    ?>
@endsection

<?php

	/*
	TITULO: R9_Productos_Surtir
			PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
						[$FInicial] Fecha inicial del rango donde se buscara
						[$FFinal] Fecha final del rango donde se buscara
			FUNCION: Arma una lista con los productos surtidos y sus fechas
			RETORNO: No aplica
			AUTOR: Ing. Manuel Henriquez
	*/

	function R9_Productos_Surtir($SedeConnection, $FInicial, $FFinal) {
		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion = $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = R9Q_Productos_Surtir($FInicial,$FFinal);
		$result = sqlsrv_query($conn, $sql);

		echo '
			<div class="input-group md-form form-sm form-1 pl-0">
				<div class="input-group-prepend">
					<span class="input-group-text purple lighten-3" id="basic-text1">
						<i class="fas fa-search text-white" aria-hidden="true"></i>
		        	</span>
		  		</div>
		  		<input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
			</div>
			<br/>
		';

		echo'
			<h6 align="center">Periodo desde el '
				. date("d-m-Y", strtotime($FInicial)) . ' al '
				. date("d-m-Y", strtotime($FFinalImpresion))
			. '</h6>
		';

		echo'
			<table class="table table-striped table-bordered col-12 sortable" id="myTable">
				<thead class="thead-dark">
				    <tr>
				    	<th scope="col">#</th>
				    	<th scope="col">Codigo</th>
				      	<th scope="col">Descripcion</th>
				      	<th scope="col">Existencia</th>
				      	<th scope="col">Ultimo Lote</br>(En Rango)</th>
				      	<th scope="col">Tiempo en Tienda</br>(Dias)</th>
				    </tr>
		  		</thead>
		  		<tbody>
		';

		$contador = 1;

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			
			$sql2 = QExistenciaArticulo($IdArticulo,0);
			$result2 = sqlsrv_query($conn,$sql2);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$Existencia = $row2["Existencia"];

			$sql3 = QTiempoEnTienda($IdArticulo);
			$result3 = sqlsrv_query($conn,$sql3);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$TiempoTienda = $row3["TiempoTienda"];
			$FechaRegistro = $row3["FechaTienda"];

			if(!is_null($FechaRegistro)) {
				$Diferencia1 = ValidarFechas($FechaRegistro->format("Y-m-d"), $FInicial);
				$Diferencia2 = ValidarFechas($FechaRegistro->format("Y-m-d"), $FFinalImpresion);
				
				if(($Diferencia1 <= 0) && ($Diferencia2 >= 0)) {

					if($Existencia > 0) {
						echo '
							<tr>
								<td align="center"><strong>'.intval($contador).'</strong></td>
								<td align="left">'.$row["CodigoArticulo"].'</td>
								<td align="left" class="barrido">
									<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
										.utf8_encode(addslashes($row["Descripcion"]))
									.'</a>
								</td>
								<td align="center">'.intval($Existencia).'</td>
								<td align="center">'.$FechaRegistro->format("d-m-Y").'</td>
								<td align="center">'.$TiempoTienda.'</td>
							</tr>
						';
					}
					
					$contador++;
				}

			}

	  	}
	  	echo '
	  		</tbody>
		</table>';

		sqlsrv_close($conn);
	}

	/*
		TITULO: R9Q_Productos_Surtir
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
		FUNCION: Consulta las Veces Compradas a proveedores y las unidades compradas
		RETORNO: Tabla con los articulos, las veces compradas y las unidades compradas
		Autos: Ing. Manuel Henriquez
	*/
	function R9Q_Productos_Surtir($FInicial, $FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesCompradasProveedor,
			SUM(ComFacturaDetalle.CantidadFacturada) AS UnidadesCompradasProveedor,
			CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRegistro
			FROM ComFacturaDetalle
			INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
			WHERE (ComFactura.FechaRegistro > '$FInicial') 
			AND (ComFactura.FechaRegistro < '$FFinal')
			GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, FechaRegistro
			ORDER BY FechaRegistro ASC
		";
		return $sql;
	}
?>