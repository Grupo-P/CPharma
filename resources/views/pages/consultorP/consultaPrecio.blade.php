@extends('layouts.modelUser')

@section('title')
  Consulta de precio
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
    	font-size: 1.6em;
      background-color: #fff;
      width: 100%;
      height: 50px;
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
	  .aum-icon-lup {
    	text-align: center;
    	font-size: 1em;
    }
    .aum-icon-cod {
      text-align: center;
      font-size: 1.2em;
    }
</style>

@section('content')

	<?php 
	  include(app_path().'\functions\config.php');
	  include(app_path().'\functions\functions.php');
	  include(app_path().'\functions\querys_mysql.php');
	  include(app_path().'\functions\querys_sqlserver.php');

    $URL = app_path().'\functions\functionConsultaPrecio.php';

	  $CodJson = '';

		$sql1 = RCPQ_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,'FTN');

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
						<td align="center" class="text-info"><h1><i class="fas fa-barcode aum-icon-cod"</i></h1></td>
						<td align="center" style="border: 4px solid #17a2b8;">
							<input id="inputCodBar" type="text" name="CodBar" autofocus="autofocus">
						</td>
						<td align="center" class="text-info"><h1><i class="fas fa-search aum-icon-lup"</i></h1></td>
					</tr>
				</tbody>
			</table>
		';
	?>
    <table class="table table-borderless col-12">
      <thead class="center">
        <th class="bg-info text-white border border-white"><h5>Código de barra</h5></th>
        <th class="bg-info text-white border border-white"><h5>Descripción</h5></th>
        <th class="bg-info text-white border border-white"><h5>Precio   BsS</h5></th>
      </thead>
      <tbody>
        <tr>
          <td align="center" class="text-black">
            <b><p id="PCodBarrScan"></p></b>
          </td>
          <td align="center" class="text-black">
            <b><p id="PDescripScan"></p></b>
          </td>
          <td align="center" class="text-black">
            <b>BsS. 70.601,85</b>
          </td>
        </tr>
      </tbody>
    </table>
  
    </br>
    <table class="table table-borderless table-striped col-12">
      <thead class="center">
        <th class="bg-secondary text-white border border-white" colspan="3"><h5>Articulos sugeridos</h5></th>
      </thead>
      <thead class="center">
        <th class="bg-secondary text-white border border-white"><h5>Código de barra</h5></th>
        <th class="bg-secondary text-white border border-white"><h5>Descripción</h5></th>
        <th class="bg-secondary text-white border border-white"><h5>Precio  BsS</h5></th>
      </thead>
      <tbody>
        <tr>
          <td align="center" class="text-black"><b>7591585111050</b></td>
          <td align="center" class="text-black"><b>CARIBAN COMPR 10MG X 30</b></td>
          <td align="center" class="text-black"><b>BsS. 70.601,85</b></td>
        </tr>
        <tr>
          <td align="center" class="text-black"><b>7591062900894</b></td>
          <td align="center" class="text-black"><b>OFAFLAN SUSP GTA 15 MG/ML X 30 ML</b></td>
          <td align="center" class="text-black"><b>BsS. 709,74</b></td>
        </tr>
        <tr>
          <td align="center" class="text-black"><b>7730698007243</b></td>
          <td align="center" class="text-black"><b>ABRETIA CAP 60MG X 14</b></td>
          <td align="center" class="text-black"><b>BsS. 163.140,14</b></td>
        </tr>
        <tr>
          <td align="center" class="text-black"><b>7730698007243</b></td>
          <td align="center" class="text-black"><b>ABRETIA CAP 60MG X 14</b></td>
          <td align="center" class="text-black"><b>BsS. 163.140,14</b></td>
        </tr>
      </tbody>
    </table>
@endsection

@section('scriptsPie')
	<script>
    const URL =  'http://localhost/CPharma/app/functions/functionConsultaPrecio.php';

		$('#inputCodBar').attr("placeholder", "Haga scan del codigo de barra");
		$('#inputCodBar').attr("onblur", "this.placeholder = 'Haga scan del codigo de barra'");
		$('#inputCodBar').attr("onfocus", "this.placeholder = ''");

		$('#inputCodBar').keyup(function(e){
	    if(e.keyCode == 13) {
        var CodBarrScan = $('#inputCodBar').val();
       
        var indiceCodBarScan = ArrJsCB.indexOf(CodBarrScan);
        var indiceDescScan = indiceCodBarScan+1;
        var indiceIdScan = indiceCodBarScan+2;

        var parametro = {
          "IdArticulo":ArrJsCB[indiceIdScan]
        };

        $.ajax({
          data: parametro,
          url: URL,
          type: "POST",
          success: function(data) {
            console.log(data);
          }
         });
      
        $('#PCodBarrScan').html(ArrJsCB[indiceCodBarScan]);
        $('#PDescripScan').html(ArrJsCB[indiceDescScan]);
        $('#inputCodBar').val(''); 

        console.log('CodigoBarra: '+ArrJsCB[indiceCodBarScan]);
        console.log('Descripcion: '+ArrJsCB[indiceDescScan]);
        console.log('Id: '+ArrJsCB[indiceIdScan]);
      }   
    });
	</script>
     
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      const ArrJsCB = eval(<?php echo $CodJson ?>);
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
      SELECT CodigoBarra,
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvCodigoBarra
      INNER JOIN InvArticulo ON InvArticulo.Id = InvCodigoBarra.InvArticuloId
    ";
    return $sql;
  }
?>