@extends('layouts.modelUser')

@section('title')
  Etiqueta
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
	.etq table{
		display: inline;
		margin:-2px;
    border-radius: 25px;
	}
	.etq thead{
		border-top: 5px solid #17a2b8;
		border-right: 5px solid #17a2b8;
		border-left: 5px solid #17a2b8;
		border-radius: 25px;
	}
	.etq tbody{
		border-bottom: 5px solid #17a2b8;
		border-right: 5px solid #17a2b8;
		border-left: 5px solid #17a2b8;
		border-radius: 25px;
	}
	.rowCenter{
		width: 16cm;
	}
	.rowIzqA{
		width: 7cm;
	}
	.rowDerA{
		width: 9cm;
	}
	.titulo{
		height: 1cm;
		font-size: 1.1em;
	}
	.descripcion{
		height: 3cm;
	}
	.rowDer{
		height: 2cm;
	}
	.rowIzq{
		height: 2cm;
	}
	.centrado{
		text-align: center;
		text-transform: uppercase;
	}
	.derecha{
		text-align: right;
		text-transform: uppercase;
	}
	.izquierda{
		text-align: left;
		text-transform: uppercase;
	}
	.aumento{
		font-size: 1.8em;
	}
</style>

@section('content')

	<?php 
	  include(app_path().'\functions\config.php');
	  include(app_path().'\functions\functions.php');
	  include(app_path().'\functions\querys_mysql.php');
	  include(app_path().'\functions\querys_sqlserver.php');

		$tipo = $_GET['tipo'];

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
            <h1 class="text-info">GENERAR ETIQUETA DE PROMOCION</h1>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr class="bg-white" style="border: 4px solid #17a2b8;">
          <td align="center" class="text-success"><h1><i class="fas fa-tag aum-icon-cod"></i></h1></td>
          <td align="center" style="border: 4px solid #17a2b8;">
            <input id="inputCodBar" type="text" name="CodBar" autofocus="autofocus">
          </td>
          <td align="center" class="text-success"><h1><i class="fas fa-print aum-icon-lup" onclick="window.print();"></i></h1></td>
        </tr>
      </tbody>
    </table>
    
    <table class="table table-borderless col-12" id="tablaError">
      <thead class="center">
        <th class="bg-white text-danger border border-white">
          <h3 id="MsnError"></h3></th>
      </thead>
    </table>

    <div id="DivEtiquetas"></div>

@endsection

@section('scriptsPie')
  <script type="text/javascript">
    const SedeConnectionJs = '<?php echo $RutaUrl;?>'
    const tipo = '<?php echo $tipo;?>'
  </script>

  <script>
    /************************************************************************/
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
  </script>

	<script>
    var dominio = dominio(SedeConnectionJs);
    const URLEtiquetaUnica = ''+dominio+'assets/functions/functionEtiquetaUnica.php';

		$('#inputCodBar').attr("placeholder", "Haga scan o escriba el codigo de barra");
		$('#inputCodBar').attr("onblur", "this.placeholder = 'Haga scan o escriba el codigo de barra'");
		$('#inputCodBar').attr("onfocus", "this.placeholder = ''");

		$('#inputCodBar').keyup(function(e){
	    if(e.keyCode == 13) {

    	 	$("#MsnError").html('');
    	 
        var CodBarrScan = $('#inputCodBar').val();
        var indiceCodBarScan = ArrJsCB.indexOf(CodBarrScan);
        var indiceIdScan = indiceCodBarScan+1;

        var indiceIdScanDesc = ArrJs.indexOf(ArrJsCB[indiceIdScan]);
        var indiceScanDesc = indiceIdScanDesc-1;

        $('#inputCodBar').val('');
        
        if( (indiceCodBarScan>0) && (indiceScanDesc)>0 ) {

          var parametro = {
          	"IdArticulo":ArrJsCB[indiceIdScan],
          	"tipo":tipo
          };

          //Incio Armado tablaResuldado
          $.ajax({
            data: parametro,
            url: URLEtiquetaUnica,
            type: "POST",
            success: function(data) {
              var respuesta = data;
              var letras = respuesta.substr(0,2);

              if(letras=='EL'){
              	$("#MsnError").html(respuesta);
              }
              else{
            	 	var contenedor = $("#DivEtiquetas").html();	
								$("#DivEtiquetas").html(contenedor+respuesta);
              }
            }
           });
          //Fin Armado tablaResuldado
        }
        else {
          $("#MsnError").html('NO SE ENCONTRARON RESULTADOS');
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