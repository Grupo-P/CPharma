<?php
    use compras\Configuracion;
?>
@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Articulos sin imagen
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

		R45_Imagenes_Articulos($_GET['SEDE']);
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos sin imagen');

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: R45_Imagenes_Articulos
		DESAROLLADO POR: SERGIO COVA
 	*/
	function R45_Imagenes_Articulos($SedeConnection) {
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		$sql = R45Q_Articulos_Existencia();
		$result = sqlsrv_query($conn,$sql);
		$contador = 1;

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
	      	    <th scope="col" class="CP-sticky">Codigo Interno</th>
	      	    <th scope="col" class="CP-sticky">Codigo Barra</th>
	      	    <th scope="col" class="CP-sticky">Descripcion</th>
                <th scope="col" class="CP-sticky">Existencia</th>
                <th scope="col" class="CP-sticky">Tipo</th>
		    </tr>
	  	</thead>
  	<tbody>
		';

        $Error = "";
        $configuracion =  Configuracion::select('valor')->where('variable','URL_interna')->get();

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $imprimir = false;

            //Inicio de validacion de imagen
                $path = "".$configuracion[0]->valor;
                if(file_exists($path)){
                    $Imagenes  = scandir($path);
                    if(count($Imagenes)>0){
                        $codigoBarra = $row['CodigoBarra'];
                        $imagenBuscar = $codigoBarra;

                        foreach ($Imagenes as $imagen){
                            $imagenAlmacenada = explode(".", $imagen);

                            if($imagenAlmacenada[0]==$imagenBuscar){
                                $imprimir = true;
                                continue;
                            }
                        }
                    }else{
                        $Error = "Error: lectura imagen";
                    }
                }else{
                    $Error = "Error: valide la URL";
                }
            //Fin de validacion de imagen

            if($imprimir==false && $Error==""){
                echo '<tr>';
                echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
                echo '<td align="center">'.($row['CodigoInterno']).'</td>';
                echo '<td align="center">'.($row['CodigoBarra']).'</td>';
                echo '<td align="center">'.FG_Limpiar_Texto($row['Descripcion']).'</td>';
                echo '<td align="center">'.($row['Existencia']).'</td>';
                echo '<td align="center">'.($row['Tipo']).'</td>';
                echo '</tr>';
                $contador++;
            }
  	    }

        if($Error != ""){
            echo '<p class="text-danger text-center h3">'.$Error.'</p>';
        }

	  	echo '
  		</tbody>
		</table>';
		sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: R45Q_Articulos_Existencia
		DESAROLLADO POR: SERGIO COVA
	 */
	function R45Q_Articulos_Existencia() {
		$sql = "
        select
        InvArticulo.CodigoArticulo as CodigoInterno,
        --Codigo de Barra
            (SELECT CodigoBarra
            FROM InvCodigoBarra
            WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
            AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
        InvArticulo.Descripcion,
         --Existencia (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
        --Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
           iif(
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Medicina')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) = 0, 'Miscelaneos','Medicinas') AS Tipo
        from
        InvArticulo
        where
        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0
        order by Descripcion asc
		";
		return $sql;
	}
?>
