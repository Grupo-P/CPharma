@extends('layouts.modelUser')

@section('title')
  Dosificaciones
@endsection

<style>
	* {
      box-sizing: border-box;
    }
    .autocomplete {
      position: relative;
      display: inline-block;
    }
    input {
      border: 1px solid transparent;
      background-color: #fff;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }
    input[type=text] {
    	text-align: center;
    	font-size: 1.8em;
      background-color: #fff;
      width: 100%;
      height: 76px;
    }
    input[type=text]:focus {
		 	outline: 0;
    }
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      top: 100%;
      left: 0;
      right: 0;
    }
    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff; 
      border-bottom: 1px solid #d4d4d4; 
    }
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
    }
    .autocomplete-active {
      background-color: #17a2b8 !important; 
      color: #ffffff; 
    }
		.center th {
	    vertical-align: middle;
	    text-align: center;
	  }
	  .aum-icon {
    	text-align: center;
    	font-size: 1.8em;
    }
</style>

@section('content')

	<?php 
	  include(app_path().'\functions\config.php');
	  include(app_path().'\functions\functions.php');
	  include(app_path().'\functions\querys_mysql.php');
	  include(app_path().'\functions\querys_sqlserver.php');

	  $CodJson = '';

  	if (isset($_GET['Id'])) {
      echo'</br>El codigo de barra es: '.$_GET['CodBar'];
     	echo'</br>El id del articulo es: '.$_GET['Id'];
      //R2_Historico_Producto($_GET['SEDE'],$_GET['Id']);
      //FG_Guardar_Auditoria('CONSULTAR','REPORTE','Historico de productos');
  	} 
  	else {
  		//$sql1 = RCPQ_Lista_Articulos_CodBarra();
      //$CodJson = FG_Armar_Json($sql1,'FTN');

			echo'
		  <table class="table table-borderless col-12">
				<thead class="center">
					<tr>
						<th scope="col" colspan="3">
							<h1 class="text-info">CONSULTE EL PRECIO AQUI</h1>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr class="bg-white" style="border: 4px solid #17a2b8;">
						<td align="center" class="text-info"><h1><i class="fas fa-barcode aum-icon"</i></h1></td>
						<td align="center" style="border: 4px solid #17a2b8;">
							<input id="myInputCB" type="text" name="CodBar" placeholder="Haga scan del codigo de barra">
						</td>
						<td align="center" class="text-info"><h1><i class="fas fa-search aum-icon"</i></h1></td>
					</tr>
				</tbody>
			</table>
			';
  	}
	?>
@endsection

@section('scriptsPie')
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsCB = eval(<?php echo $CodJson ?>);
      autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myIdCB"), ArrJsCB);
    </script> 
  <?php
    }
  ?>  
@endsection

<?php
/**********************************************************************************/
  /*
    TITULO: RCPQ_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function RCPQ_Lista_Articulos_CodBarra() {
    $sql = "
      SELECT
      (SELECT CodigoBarra
      FROM InvCodigoBarra 
      WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
      AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY CodigoBarra ASC
    ";
    return $sql;
  }
  /*
  	<td>
			<!-- Barra de consulta -->
      <form autocomplete="off" action="">
        <div class="autocomplete" style="width:90%;">
          <input id="myInputCB" type="text" name="CodBar" placeholder="Haga scan del codigo de barra" onkeyup="conteoCB()">
          <input id="myIdCB" name="Id" type="hidden">
        </div>
        <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r(FG_Mi_Ubicacion());
        echo'">
        <input type="submit" value="Buscar" class="btn btn-outline-info">
    	</form>
    	<!-- Barra de consulta -->
		</td>
 	*/
 

 /*
 <tr class="bg-info">
						<td align="center" class="text-white bg-info" style="border-radius: 25px 0px 0px 25px;"><h1><i class="fas fa-barcode aum-icon"</i></h1></td>
						<td align="center" class="bg-white">
							<input id="myInputCB" type="text" name="CodBar" placeholder="Haga scan del codigo de barra">
						</td>
						<td align="center" class="text-white bg-info" style="border-radius: 0px 25px 25px 0px;"><h1><i class="fas fa-search aum-icon"</i></h1></td>
					</tr>
  */
?>