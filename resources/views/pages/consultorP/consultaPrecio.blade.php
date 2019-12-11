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

    $SedeConnection = 'FTN';//FG_Mi_Ubicacion();
    $RutaUrl = FG_Mi_Ubicacion();

	  $CodJson = '';
    $ArtJson = '';

		$sql1 = RCPQ_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$SedeConnection);

    $sql1 = RCPQ_Lista_Articulos_Descripcion();
    $ArtJson = FG_Armar_Json($sql1,$SedeConnection);
	?>
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
          <td align="center" class="text-info"><h1><i class="fas fa-barcode aum-icon-cod"></i></h1></td>
          <td align="center" style="border: 4px solid #17a2b8;">
            <input id="inputCodBar" type="text" name="CodBar" autofocus="autofocus">
          </td>
          <td align="center" class="text-info"><h1><i class="fas fa-search aum-icon-lup"></i></h1></td>
        </tr>
      </tbody>
    </table>
    
    <table class="table table-borderless col-12" id="tablaError">
      <thead class="center">
        <th class="bg-white text-dark border border-white">
          <h3>NO SE ENCONTRARON RESULTADOS</h3></th>
      </thead>
    </table>

    <table class="table table-borderless col-12" id="tablaResuldado">
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
            <b><p id="PPrecioScan"></p></b>
          </td>
        </tr>
        <tr>
          <td align="center" class="text-info" colspan="3">
            <b><p>* Los precios aqui expresados contienen IVA (En caso de que aplique)</p></b>
          </td>
        </tr>
      </tbody>
    </table>

    <table class="table table-borderless table-striped col-12" id="tablaSugerido">
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
      </tbody>
    </table>
@endsection

@section('scriptsPie')
  <script type="text/javascript">
    const SedeConnectionJs = '<?php echo $RutaUrl;?>'
  </script>

  <script>
    function dominio(SedeConnectionJs){
      var dominio = '';
      switch(SedeConnectionJs) {
        case 'FTN':
          dominio = 'http://cpharmaftn.com/';
          return dominio;
        break;
        case 'FLL':
          dominio = 'http://cpharmafll.com/';
          return dominio;
        break;
        case 'FAU':
          dominio = 'http://cpharmafau.com/';
          return dominio;
        break;
        case 'GP':
          dominio = 'http://cpharmade.com/';
          return dominio;
        break;
      }
    }
    function formateoPrecio(cantidad, decimales) {
      //Transformamos el numero en string
      cantidad += ''; 
      //Eliminar cualquier caracter diferente a (.) o numeros
      cantidad = parseFloat(cantidad.replace(/[^0-9\.]/g, '')); 
      //Validar los decimales
      decimales = decimales || 0; 
      //Si el numero es cero o texto alphanumerico retornamos cero
      if(isNaN(cantidad) || cantidad === 0)  {
          return parseFloat(0).toFixed(decimales);
      }
      //Si el valor es mayor o menor que cero formateamos a moneda
      cantidad = '' + cantidad.toFixed(decimales);
      var cantidad_parts = cantidad.split('.'),
      regexp = /(\d+)(\d{3})/;
      while(regexp.test(cantidad_parts[0])) {
          cantidad_parts[0] = cantidad_parts[0].replace(regexp, '$1' + '.' + '$2');
      }
      //Retornamos el valor formateado
      return cantidad_parts.join(',');
    }
    function limpiarPantalla(){
      $('#tablaError').hide();
      $('#tablaResuldado').hide();
      $('#tablaSugerido').hide();
    }
  </script>

	<script>
    var dominio = dominio(SedeConnectionJs);
    const URL = ''+dominio+'assets/functions/functionConsultaPrecio.php';

		$('#inputCodBar').attr("placeholder", "Haga scan del codigo de barra");
		$('#inputCodBar').attr("onblur", "this.placeholder = 'Haga scan del codigo de barra'");
		$('#inputCodBar').attr("onfocus", "this.placeholder = ''");

    limpiarPantalla();

		$('#inputCodBar').keyup(function(e){
	    if(e.keyCode == 13) {

        var CodBarrScan = $('#inputCodBar').val();
        var indiceCodBarScan = ArrJsCB.indexOf(CodBarrScan);
        var indiceIdScan = indiceCodBarScan+1;

        var indiceIdScanDesc = ArrJs.indexOf(ArrJsCB[indiceIdScan]);
        var indiceScanDesc = indiceIdScanDesc-1;
        
        if( (indiceCodBarScan>0) && (indiceScanDesc)>0 ) {

          $('#tablaError').hide();
          $('#tablaResuldado').show();
          $('#tablaSugerido').show();

          var parametro = {
          "IdArticulo":ArrJsCB[indiceIdScan]
          };

          $.ajax({
            data: parametro,
            url: URL,
            type: "POST",
            success: function(data) {
              var precio = formateoPrecio(data,2);
              $('#PPrecioScan').html('BsS. '+precio);
            }
           });

          $('#PCodBarrScan').html(ArrJsCB[indiceCodBarScan]);
          $('#PDescripScan').html(ArrJs[indiceScanDesc]); 
          $('#inputCodBar').val(''); 
          setTimeout(limpiarPantalla,15000);
        }
        else {
          $('#tablaSugerido').hide();
          $('#tablaResuldado').hide();
          $('#tablaError').show();
          $('#PCodBarrScan').html('');
          $('#PDescripScan').html('');
          $('#PPrecioScan').html(''); 
          $('#inputCodBar').val('');
          setTimeout(limpiarPantalla,5000);
        }
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
   <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      const ArrJs = eval(<?php echo $ArtJson ?>);
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
      InvArticulo.Id
      FROM InvCodigoBarra
      INNER JOIN InvArticulo ON InvArticulo.Id = InvCodigoBarra.InvArticuloId
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: RCPQ_Lista_Articulos_Descripcion
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function RCPQ_Lista_Articulos_Descripcion() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
    ";
    return $sql;
  }
?>