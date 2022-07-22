@extends('layouts.modelUserH')

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
    background-color: white;
	}
	.etq thead{
		border-top: 5px solid #dc3545;
		border-right: 5px solid #dc3545;
		border-left: 5px solid #dc3545;
		border-radius: 25px;
    background-color: white;
	}
	.etq tbody{
		border-bottom: 5px solid #dc3545;
		border-right: 5px solid #dc3545;
		border-left: 5px solid #dc3545;
		border-radius: 25px;
    background-color: white;
	}
	.rowCenter{
		width: 40cm;
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
		height: 8cm;
	}
	.rowDer{
		height: 6cm;
	}
	.rowIzq{
		height: 6cm;
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
		font-size: 6.5em;
	}
  .aumento1{
    font-size: 4.5em;
  }

  .aumentoPrecio{
    font-size:10em;
  }
  .preciopromo{
    color: #dc3545;
  }
  .divPromo{
    margin-left: -300px;
    width: 40cm;
    border: 15px solid #dc3545;
    border-radius: 25px;
    background-color: #dc3545;
  }
  .MensajePromo{
    color: white;
    background-color: #dc3545;
    width: 100%;
    font-size: 5rem;
    text-align: center;
  }
  /* cuando vayamos a imprimir ... */
  @media print{
    /* indicamos el salto de pagina */
    .saltoDePagina{
      display:block;
      page-break-before:always;
    }
  }
</style>

@section('content')

	<?php
	  include(app_path().'\functions\config.php');
	  include(app_path().'\functions\functions.php');
	  include(app_path().'\functions\querys_mysql.php');
	  include(app_path().'\functions\querys_sqlserver.php');

		$tipo = $_GET['tipo'];
    $clasificacion = $_GET['clasificacion'];

    $SedeConnection = FG_Mi_Ubicacion();
    $RutaUrl = FG_Mi_Ubicacion();

	  $CodJson = '';
    $ArtJson = '';

		$sql1 = RCPQ_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$SedeConnection);

    $sql1 = RCPQ_Lista_Articulos_Descripcion();
    $ArtJson = FG_Armar_Json($sql1,$SedeConnection);

    $concatedado = ''.$clasificacion.' '.$tipo.' (GRANDE) PROMOCION';
    FG_Guardar_Auditoria('GENERAR','ETIQUETA',$concatedado);
	?>
    <!-- Modal Box -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header text-white bg-info">
            <h2 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-tag"></i> Frase de promocion</h2>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="modo_opciones link-opc" id="opc_1">
              <h3><i class="fas fa-tag aum-icon-lup"></i> PRECIO ESPECIAL</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_2">
              <h3><i class="fas fa-tag aum-icon-lup"></i> ARTICULO NUEVO</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_3">
              <h3><i class="fas fa-tag aum-icon-lup"></i> PRECIO OFERTA</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_4">
              <h3><i class="fas fa-tag aum-icon-lup"></i> ARTICULO EN PROMOCION</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / Modal Box -->

    <table class="table table-borderless col-12">
      <thead class="center">
        <tr>
          <th scope="col" colspan="3">
            <h1 class="text-info">GENERAR ETIQUETA DE PROMOCION (Grande)</h1>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr class="bg-white" style="border: 4px solid #17a2b8;">
          <td align="center" class="text-success"><h1><i class="fas fa-tag aum-icon-cod" id="btn_selector"></i></h1></td>
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
    const clasificacion = '<?php echo $clasificacion;?>'
    var FrasePromo = '';
    var contador = 0;

      @if(isset($_GET['CodigoBarra']))
        $(document).ready(function () {
            $('#inputCodBar').val({{ $_GET['CodigoBarra'] }});

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
            "tipo":tipo,
            "clasificacion":clasificacion
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

                var nuevoDiv = '<div class="divPromo">';
                nuevoDiv += '<p class="MensajePromo"><strong>'+FrasePromo+'</strong></p>';
                nuevoDiv += respuesta
                nuevoDiv += '</div>';
                    var contenedor = $("#DivEtiquetas").html();
                                $("#DivEtiquetas").html(contenedor+nuevoDiv+'<br>');

                if(contador==1){
                  var contenedor = $("#DivEtiquetas").html();
                  var nuevoDiv = '<div class="saltoDePagina"></div>';
                  $("#DivEtiquetas").html(contenedor+nuevoDiv);
                  contador=0;
                }
                else{
                  contador++;
                }
              }
            }
           });
          //Fin Armado tablaResuldado
        }
        else {
          $("#MsnError").html('NO SE ENCONTRARON RESULTADOS');
        }
        });
      @endif

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
                dominio = 'http://cpharmatest.com/';
                return dominio;
            break;
            case 'ARG':
                dominio = 'http://cpharmade.com/';
                return dominio;
            break;
            case 'DBs':
                dominio = 'http://cpharmade.com/';
                return dominio;
            break;
            case 'KDI':
                dominio = 'http://cpharmakdi.com/';
                return dominio;
            break;
            case 'FSM':
                $dominio = 'http://cpharmafsm.com/';
                return $dominio;
            break;
        }
    }

    function updateModalBox(modo_selec) {
      if (modo_selec == 1) {
        FrasePromo = 'PRECIO ESPECIAL';
      }
      else if (modo_selec == 2) {
        FrasePromo = 'ARTICULO NUEVO';
      }
      else if (modo_selec == 3) {
        FrasePromo = 'PRECIO OFERTA';
      }
      else if (modo_selec == 4) {
        FrasePromo = 'ARTICULO EN PROMOCION';
      }
    }
  </script>

	<script>
    var dominio = dominio(SedeConnectionJs);
    const URLEtiquetaUnica = ''+dominio+'assets/functions/functionEtiquetaPromocion.php';

		$('#inputCodBar').attr("placeholder", "Haga scan o escriba el codigo de barra");
		$('#inputCodBar').attr("onblur", "this.placeholder = 'Haga scan o escriba el codigo de barra'");
		$('#inputCodBar').attr("onfocus", "this.placeholder = ''");

    var modal = document.getElementById('exampleModalCenter');
    var btn = document.getElementById("btn_selector");
    var span = document.getElementsByClassName("close")[0];
    var opc_selec = 1;
    updateModalBox(opc_selec);

    btn.onclick = function() {
      $('#exampleModalCenter').modal('show');
    }
    span.onclick = function() {
      $('#exampleModalCenter').modal('hide');
    }
    window.onclick = function(event) {
      if (event.target == modal) {
        $('#exampleModalCenter').modal('hide');
      }
    }
    $('#opc_1').click(function(e) {
      if (opc_selec != 1){
        opc_selec = 1;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });
    $('#opc_2').click(function(e) {
      if (opc_selec != 2){
        opc_selec = 2;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });
    $('#opc_3').click(function(e) {
      if (opc_selec != 3){
        opc_selec = 3;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });
    $('#opc_4').click(function(e) {
      if (opc_selec != 4){
        opc_selec = 4;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });

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
          	"tipo":tipo,
            "clasificacion":clasificacion
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

                var nuevoDiv = '<div class="divPromo">';
                nuevoDiv += '<p class="MensajePromo"><strong>'+FrasePromo+'</strong></p>';
                nuevoDiv += respuesta
                nuevoDiv += '</div>';
            	 	var contenedor = $("#DivEtiquetas").html();
								$("#DivEtiquetas").html(contenedor+nuevoDiv+'<br>');

                if(contador==1){
                  var contenedor = $("#DivEtiquetas").html();
                  var nuevoDiv = '<div class="saltoDePagina"></div>';
                  $("#DivEtiquetas").html(contenedor+nuevoDiv);
                  contador=0;
                }
                else{
                  contador++;
                }
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
