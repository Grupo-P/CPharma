@extends('layouts.modelUser')

@section('title')
  Etiqueta
@endsection

<style>
  @font-face {
    font-family: CocoGoose;
    src: url('/assets/fonts/cocoGoose/cocogoose_normal.otf');
    font-weight: 600;
  }

	* {
    box-sizing: border-box;
    --color-naranja: #FF5800;
    --color-azul: #00B4DA;
    --color-fondo: #fff;
  }

  /* Scan */
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

  /* Etiqueta */
  #DivEtiquetas {
    width: 100%;
    max-width: 100%;
    overflow: hidden;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
  }

  .divPromo{
    overflow: hidden;
    position: relative;
    width: 25cm;
    border: 2px solid var(--color-naranja);
    border-radius: 25px;
    background-color: var(--color-fondo);
    margin: 1px;
    padding-bottom: 12px;
    padding-top: 8px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
  }

  /* Medidas */
  .piscina {
    min-width: 16.5cm;
    min-height: 15.5cm;
    max-width: 16.5cm;
    max-height: 15.5cm;
  }

  .estibas {
    min-width: 21.55cm;
    min-height: 17cm;
    max-width: 21.55cm;
    max-height: 17cm;
  }

  .anaquel {
    min-width: 6.5cm;
    min-height: 6cm;
    max-width: 6.5cm;
    max-height: 6cm;
  }

  /* Imagen */
  .anaquel .imagen-ofertero {
    padding: 6px 0px;
  }

  .imagen-ofertero {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 12px 0px;
  }

  .anaquel .imagen-ofertero img {
    width: 100px !important;
  }

  .piscina .imagen-ofertero img {
    width: 380px !important;
  }

  .estibas .imagen-ofertero img {
    width: 480px !important;
  }

  /* Titulo */
  .anaquel .titulo {
    font-size: 12px !important;
  }
  .anaquel .titulo p {
    max-width: 74%;
    line-height: 14px;
    margin-bottom: 6px;
  }

  .piscina .titulo {
    font-size: 34px !important;
  }
  .piscina .titulo p {
    max-width: 74%;
    line-height: 38px;
  }

  .titulo {
    font-family: CocoGoose;
    text-align: center;
    color: var(--color-naranja);
    font-weight: bold;
    font-size: 38px;
    display: flex;
    justify-content: center;
  }

  .titulo p {
    max-width: 74%;
    line-height: 42px;
  }

  /* Arriba */
  .anaquel .arriba {
    font-size: 12px !important;
  }

  .anaquel .codigo {
    margin-left: 2px !important;
  }

  .anaquel .fecha {
    margin-right: 2px !important;
  }

  .piscina .arriba {
    font-size: 22px !important;
  }

  .piscina .codigo {
    margin-left: 6px !important;
  }

  .piscina .fecha {
    margin-right: 6px !important;
  }

  .arriba {
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;
    color: var(--color-azul);
    font-size: 25px;
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
  }

  .arriba .codigo {
    writing-mode:vertical-rl;
    transform: rotate(-180deg);
    margin-left: 10px;
  }

  .arriba .fecha {
    writing-mode: vertical-rl;
    margin-right: 10px;
  }

  /* Precio */
  .anaquel .precios {
    margin-bottom: 0px !important;
    padding-top: 0px !important;
  }

  .anaquel .precios .precio {
    border: 1px solid var(--color-naranja);
    padding: 4px 12px;
    border-radius: 12px;
  }

  .precios {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    margin-bottom: 20px;
    padding-top: 12px;
  }

  .piscina .precios .precio {
    padding: 4px 24px;
    border-radius: 26px;
  }

  .precios .precio {
    display: flex;
    flex-direction: column;
    align-items: center;
    border: 2px solid var(--color-naranja);
    padding: 4px 28px;
    border-radius: 30px;
  }

  .precios .precio .monto {
    font-family: CocoGoose;
    text-transform: uppercase;
  }

  /* Antes */
  .anaquel .precios .precio.antes {
    margin-right: 4px !important;
    font-size: 14px !important;
    line-height: 14px !important;
  }

  .piscina .precios .precio.antes {
    margin-right: 10px !important;
    font-size: 28px !important;
    line-height: 26px !important;
  }

  .precios .precio.antes {
    margin-right: 12px;
    font-size: 32px !important;
    color: var(--color-azul) !important;
    line-height: 30px;
  }

  .anaquel .precios .precio.antes .text {
    font-size: 16px !important;
    margin-bottom: 0px !important;
  }

  .piscina .precios .precio.antes .text {
    font-size: 24px !important;
    margin-bottom: 3px !important;
  }

  .precios .precio.antes .text {
    font-size: 26px;
    margin-bottom: 4px;
  }

  .precios .precio.antes .monto {
    text-decoration:line-through !important;
  }

  /* Ahora */
  .anaquel .precios .precio.ahora {
    font-size: 12px !important;
    line-height: 14px !important;
  }

  .anaquel .precios .precio.ahora .monto {
    font-size: 16px !important;
    line-height: 20px;
    margin-top: 0px !important;
    padding-bottom: 0px !important;
  }

  .piscina .precios .precio.ahora {
    font-size: 30px !important;
    line-height: 28px !important;
  }

  .piscina .precios .precio.ahora .monto {
    font-size: 46px !important;
    line-height: 34px;
    margin-top: 8px !important;
    padding-bottom: 2px !important;
  }

  .precios .precio.ahora {
    font-size: 32px !important;
    color: var(--color-azul) !important;
    line-height: 28px;
  }

  .precios .precio.ahora .monto {
    color: var(--color-naranja) !important;
    font-size: 48px !important;
    line-height: 36px;
    margin-top: 10px;
    padding-bottom: 3px;
  }

  /* cuando vayamos a imprimir ... */
  @media print{
    footer, .ocultar-imprimir, #tablaError {
      display: none !important;
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
    $tamano = $_GET['tamano'];

    $SedeConnection = FG_Mi_Ubicacion();
    $RutaUrl = FG_Mi_Ubicacion();

	  $CodJson = '';
    $ArtJson = '';

		$sql1 = RCPQ_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$SedeConnection);

    $sql1 = RCPQ_Lista_Articulos_Descripcion();
    $ArtJson = FG_Armar_Json($sql1,$SedeConnection);

    $concatedado = ''.$clasificacion.' '.$tipo.' | '.$tamano.' OFERTERO';
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

    <table class="table table-borderless col-12 ocultar-imprimir">
      <thead class="center">
        <tr>
          <th scope="col" colspan="3">
            <h1 class="text-info">GENERAR ETIQUETA DE OFERTERO ({{ $tamano }})</h1>
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
    const SedeConnectionJs = '<?php echo $RutaUrl;?>';
    const tipo = '<?php echo $tipo;?>'
    const clasificacion = '<?php echo $clasificacion;?>';
    const tamano = '<?php echo $tamano ?>';
    var FrasePromo = '';
    var contador = 0;
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
                dominio = 'http://cpharmafsm.com/';
                return dominio;
            break;
            case 'FEC':
                dominio = 'http://cpharmafec.com/';
                return dominio;
            break;
            case 'KD73':
                dominio = 'http://cpharmakd73.com/';
                return dominio;
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
    //var dominio = "http://cpharmagpde.com/"
    const URLEtiquetaUnica = ''+dominio+'assets/functions/ofertero/functionEtiquetaOfertero.php';

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
            "clasificacion":clasificacion,
            "tamano":tamano
          };

          //Incio Armado tablaResuldado
          $.ajax({
            data: parametro,
            url: URLEtiquetaUnica,
            type: "POST",
            success: function(data) {
              var respuesta = data;
              var letras = respuesta.substr(0,2);
              var medida = (tamano == 'ANAQUEL' ? 'anaquel': (tamano == 'ESTIBA' ? 'estibas':'piscina'));

              if(letras=='EL'){
              	$("#MsnError").html(respuesta);
              }
              else{

                var nuevoDiv = '<div class="divPromo ' + medida +'">';
                nuevoDiv += "<div class='imagen-ofertero'><img src='assets/img/ofertero_logo.PNG'/></div>";
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
