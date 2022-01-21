@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-barcode"></i>
		Codigos de Barra (Principal)
	</h1>
	<hr class="row align-items-start col-12">

	<?php
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');
		include(app_path().'\functions\querys_mysql.php');
		include(app_path().'\functions\querys_sqlserver.php');
        $_GET['SEDE'] = FG_Mi_Ubicacion();

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		codigos_barra_principal($_GET['SEDE']);

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: codigos_barra_principal
		DESAROLLADO POR: SERGIO COVA
 	*/
	function codigos_barra_principal($SedeConnection) {
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		$sql = buscar_codigos_es_principal();
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
	      	    <th scope="col" class="CP-sticky">Codigo Intero</th>
	      	    <th scope="col" class="CP-sticky">Codigo Barra</th>
	      	    <th scope="col" class="CP-sticky">Descripcion</th>
                <th scope="col" class="CP-sticky">Tipo Codigo Barra</th>
                <th scope="col" class="CP-sticky">Es Principal</th>
		    </tr>
	  	</thead>
  	<tbody>
		';
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$InvArticuloId = $row['InvArticuloId'];

            $sql1 = "SELECT InvArticulo.CodigoArticulo, InvArticulo.Descripcion FROM InvArticulo WHERE InvArticulo.Id = $InvArticuloId";
            $result1 = sqlsrv_query($conn,$sql1);
            $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

		    $sql2 = "SELECT * from InvCodigoBarra where InvCodigoBarra.InvArticuloId = $InvArticuloId AND InvCodigoBarra.EsPrincipal = 1";
		    $result2 = sqlsrv_query($conn,$sql2);

            while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
                $CodigoArticulo = $row1['CodigoArticulo'];
                $Descripcion = FG_Limpiar_Texto($row1['Descripcion']);
                $CodigoBarra = $row2['CodigoBarra'];
                $TipoCodigoBarra = $row2['TipoCodigoBarra'];
                $EsPrincipal = $row2['EsPrincipal'];

                echo '<tr>';
                echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
                echo '<td align="center">'.$CodigoArticulo.'</td>';
                echo '<td align="center">'.$CodigoBarra.'</td>';
                echo '<td align="center">'.$Descripcion.'</td>';
                echo '<td align="center">'.$TipoCodigoBarra.'</td>';
                echo '<td align="center">'.$EsPrincipal.'</td>';
                echo '</tr>';
                $contador++;
            }
  	    }
	  	echo '
  		</tbody>
		</table>';
		sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: buscar_codigos_es_principal
		DESAROLLADO POR: SERGIO COVA
	 */
	function buscar_codigos_es_principal() {
		$sql = "SELECT
            count(*) as cuenta,
            InvCodigoBarra.InvArticuloId
            from InvCodigoBarra
            where InvCodigoBarra.EsPrincipal = 1
            group by InvCodigoBarra.InvArticuloId
            having count(*) > 1
		";
		return $sql;
	}
?>
