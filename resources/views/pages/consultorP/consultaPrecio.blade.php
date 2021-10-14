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
    .CP-Container {
      width: 95%;
      margin-left: 2.5%;
    }

    .ultimasConsultasTable > thead > tr > th, .ultimasConsultasTable > thead > tr > td {
      vertical-align: middle;
    }
</style>

@section('content')

	<?php


	  include(app_path().'\functions\config.php');
	  include(app_path().'\functions\functions.php');
	  include(app_path().'\functions\querys_mysql.php');
	  include(app_path().'\functions\querys_sqlserver.php');

      $ultimasUnidades = DB::select("SELECT * FROM configuracions WHERE variable = 'UltimasUnidadesExistencia' LIMIT 1")[0]->valor;

    $SedeConnection = FG_Mi_Ubicacion();
    $RutaUrl = FG_Mi_Ubicacion();

	  $CodJson = '';
    $ArtJson = '';

		$sql1 = RCPQ_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$SedeConnection);

    $sql1 = RCPQ_Lista_Articulos_Descripcion();
    $ArtJson = FG_Armar_Json($sql1,$SedeConnection);

    $connCPharma = FG_Conectar_CPharma();
    $TasaVenta = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));

    mysqli_query($connCPharma, "SET NAMES utf8");

    $ultimasConsultas = mysqli_query($connCPharma, RCPQ_Articulos_Escaneados());

    mysqli_close($connCPharma);
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
          <td align="center" class="text-success"><h1><i class="fas fa-barcode aum-icon-cod"></i></h1></td>
          <td align="center" style="border: 4px solid #17a2b8;">
            <input id="inputCodBar" type="text" name="CodBar" autofocus="autofocus">
          </td>
          <td align="center" class="text-success"><h1><i class="fas fa-search aum-icon-lup"></i></h1></td>
        </tr>
      </tbody>
    </table>

    <table class="table table-borderless col-12" id="tablaError">
      <thead class="center">
        <th class="bg-white text-danger border border-white">
          <h3>NO SE ENCONTRARON RESULTADOS</h3></th>
      </thead>
    </table>

    <table class="table table-borderless table-striped col-12" id="tablaResuldado">
      <thead class="center">
        <th class="bg-success text-white border border-white"><h4>CÓDIGO DE BARRA</h4></th>
        <th class="bg-success text-white border border-white"><h4>DESCRIPCIÓN</h4></th>
        <th class="bg-success text-white border border-white"><h4>PRECIO BSS</h4></th>
        <th class="bg-success text-white border border-white"><h4>PRECIO BSD</h4></th>
        <?php
          if (_ConsultorDolar_ == "SI") {
           echo '<th class="bg-success text-white border border-white"><h4>PRECIO $</h4></th>';
          }
        ?>
      </thead>
      <tbody>
        <tr>
          <td align="center" class="text-danger">
            <h4><b><p id="PCodBarrScan"></p></b></h4>
          </td>
          <td align="center" class="text-danger">
            <h4><b><p id="PDescripScan"></p></b></h4>
          </td>
          <td align="center" class="text-danger">
            <h4><b><p id="PPrecioScan"></p></b></h4>
          </td>
          <td align="center" class="text-danger">
            <h4><b><p id="PPrecioDigitalScan"></p></b></h4>
          </td>
          <?php
          if (_ConsultorDolar_ == "SI") {
             echo '
              <td align="center" class="text-danger">
                <h4><b><p id="PPrecioDolarScan"></p></b></h4>
              </td>
             ';
            }
          ?>
        </tr>

        <tr class="trUltimoStock" style="background-color: white;"></tr>

        <tr>
          <td align="center" style="background-color: white;" class="text-dark" colspan="5">
            <b><p>Nuestros precios incluyen IVA (En caso de aplicar)</p></b>
          </td>
        </tr>
      </tbody>
    </table>

    <table class="table table-borderless table-striped col-12" id="tablaSugerido">
      <thead class="center">
        <th class="bg-info text-white border border-white" colspan="5"><h4>Articulos sugeridos</h4></th>
      </thead>
      <thead class="center">
        <th class="bg-info text-white border border-white"><h5>Código de barra</h5></th>
        <th class="bg-info text-white border border-white"><h5>Descripción</h5></th>
        <th class="bg-info text-white border border-white"><h5>Precio $</h5></th>
        <th class="bg-info text-white border border-white"><h5>Precio BsS</h5></th>
        <th class="bg-info text-white border border-white"><h5>Precio BsD</h5></th>
      </thead>
      <tbody id="bodySugerido"></tbody>
    </table>

    <?php
      if (_ConsultorDolar_ == "SI") {
       echo ' <div id="DivTasa" style="width: 100%" class="text-center">
          <label id="TasaVenta" class="text-center" style="font-size:1.5rem">
            <strong>Tasa del dia: '.SigVe.' '.number_format($TasaVenta,2,"," ,"." ).' / Bs.D '.number_format(antesReconversion($TasaVenta),2,"," ,"." ).'</strong>
          </label>
          <br>
          <label class="text-danger text-center">Nuestra tasa esta sujeta a cambios sin previo aviso</label>
        </div>';
      }
    ?>

    <div class="row">
      <div class="col-md-6">
        <div id="carouselExampleIndicators" class="carousel slide d-block w-100 bg-white" data-ride="carousel" data-wrap="true" data-interval="3000" data-pause="false">
          <div class="carousel-inner" id="divPromocion">
          </div>
          <!--<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>-->
        </div>
      </div>

      <div class="col-md-6" style="margin-top: 15%" id="ultimasConsultas">
        <h3 class="text-center">ULTIMOS ESCANEOS</h3>

        <table id="ultimasConsultasTable" class="table table-borderless table-striped col-12">
          <thead>
            <th class="align-middle bg-success text-center text-white border border-default p-3">CÓDIGO DE BARRA</th>
            <th class="align-middle bg-success text-center text-white border border-default p-3">DESCRIPCIÓN</th>
            <th class="align-middle bg-success text-center text-white border border-default p-3">PRECIO BS</th>
            <th class="align-middle bg-success text-center text-white border border-default p-3">PRECIO BS.D</th>
            <th class="align-middle bg-success text-center text-white border border-default p-3">PRECIO $</th>
            <th class="align-middle bg-success text-center text-white border border-default p-3">FECHA Y HORA</th>
          </thead>

          <tbody id="ultimasConsultasTbody">
            <?php while ($row = mysqli_fetch_assoc($ultimasConsultas)): ?>
              <tr>
                <td class="align-middle border border-success p-3"><b><?php echo $row['codigoBarra']; ?></b></td>
                <td class="align-middle border border-success p-3"><b><?php echo $row['descripcion']; ?></b></td>
                <td class="align-middle border border-success p-3"><b><?php echo $row['precio_bs']; ?></b></td>
                <td class="align-middle border border-success p-3"><b><?php echo $row['precio_d']; ?></b></td>
                <td class="align-middle border border-success p-3"><b><?php echo $row['precio_ds']; ?></b></td>
                <td class="align-middle border border-success p-3"><b><?php echo $row['fecha']; ?></b></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

@endsection

@section('scriptsPie')

  <script src="https://momentjs.com/downloads/moment.js"></script>


  <script type="text/javascript">
    const SedeConnectionJs = '<?php echo $RutaUrl;?>'

    document.body.style.zoom="80%";
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
        }
    }
    /************************************************************************/
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
    /************************************************************************/
    function limpiarPantalla(){
      $('#tablaError').hide();
      $('#tablaResuldado').hide();
      $('#tablaSugerido').hide();
      $('#DivTasa').hide();
      $('.trUltimoStock').html('');
      mostrarCarousel();
    }
    /************************************************************************/
    function mostrarCarousel(){
      $.ajax({
        data: '',
        url: URLTablaPromocion,
        type: "POST",
        success: function(data) {

          if(JSON.parse(data)!="UNICO"){
            var respuesta = JSON.parse(data);

            var limiteRespuesta = respuesta.length;
            /*Armado del elemento del carousel*/
            var contenedor = $("#divPromocion").html();
            var nuevaFila = '';

            var j = 0;
            while (j<limiteRespuesta){
              var pop = respuesta.pop();
              var precio = formateoPrecio(pop['Precio'],2);
              var precioDolar = formateoPrecio(pop['PrecioDolar'],2);
              var precioDigital = formateoPrecio(parseFloat(pop['Precio'])/1000000, 2);
              var descripcion = pop['Descripcion'];
              var codigo = pop['CodigoBarra'];
              var URLImag = URLImagen+codigo+'.jpg';

              /*Armado del elemento del carousel*/
              if(j==0){
                nuevaFila += '<div class="carousel-item active">';
              }
              else{
                nuevaFila += '<div class="carousel-item">';
              }
              nuevaFila += '<div class="row justify-content-center">';
              nuevaFila += '<img class="d-block w-100" src="'+URLImag+'"></br></br></br>';
              nuevaFila += '</div>';

              nuevaFila += '<div class="carousel-caption d-none d-md-block h-25 w-100" style="right: 0; left: 0; background-color:rgba(0, 0, 0,0.6)">';
              nuevaFila += '<h2 class="text-white">Bs.S '+precio+' / $' + precioDolar + '</h2>';

              nuevaFila += '<h2 class="text-white">Bs.D '+precioDigital+'</h2>';
              nuevaFila += '<h4 class="text-white">'+descripcion+'</h4>';
              nuevaFila += '</div>';
              nuevaFila += '</div>';

              /*Ingreso del al carousel*/
              $("#divPromocion").html(contenedor+nuevaFila);
              j++;
            }

            $('#carouselExampleIndicators').show();
          }
          else{
            $('#divPromocion').html('');
            $('#carouselExampleIndicators').hide();
          }

          $("#ultimasConsultas").show();
        }
      });
    }
  </script>

	<script>
    var dominio = dominio(SedeConnectionJs);
    const URLTablaResuldado = ''+dominio+'assets/functions/functionConsultaPrecio.php';
    const URLTablaSugerido = ''+dominio+'assets/functions/functionConsultaSugerido.php';
    const URLTablaPromocion = ''+dominio+'assets/functions/functionConsultaPromo.php';
    const URLImagen = ''+dominio+'assets/promocion/';
    const URLTasaVenta = ''+dominio+'assets/functions/functionActDolar.php';

    var TasaVenta;
    var TasaVentaMostrar;

    var timeOut;
    var precio;

		$('#inputCodBar').attr("placeholder", "Haga scan del codigo de barra");
		$('#inputCodBar').attr("onblur", "this.placeholder = 'Haga scan del codigo de barra'");
		$('#inputCodBar').attr("onfocus", "this.placeholder = ''");

    limpiarPantalla();

		$('#inputCodBar').keyup(function(e){
	    if(e.keyCode == 13) {

        precio = '';

        var CodBarrScan = $('#inputCodBar').val();
        var indiceCodBarScan = ArrJsCB.indexOf(CodBarrScan);
        var indiceIdScan = indiceCodBarScan+1;

        var indiceIdScanDesc = ArrJs.indexOf(ArrJsCB[indiceIdScan]);
        var indiceScanDesc = indiceIdScanDesc-1;

        if( (indiceCodBarScan>0) && (indiceScanDesc)>0 ) {

          $('#tablaError').hide();
          $('#divPromocion').html('');
          $('#ultimasConsultas').hide();
          $('#carouselExampleIndicators').hide();
          $('#tablaResuldado').show();

          var parametro = {
          "IdArticulo":ArrJsCB[indiceIdScan]
          };

          //Incio Actualizacion Tasa Venta
          $.ajax({
            data: parametro,
            url: URLTasaVenta,
            type: "POST",
            success: function(data) {
              TasaVenta = data;
              TasaVentaMostrar = formateoPrecio(TasaVenta,2);
              TasaVentaMostrarDigital = formateoPrecio(TasaVenta/1000000,2);
              $('#TasaVenta').html('<strong>Tasa del dia: Bs.S '+TasaVentaMostrar+' / Bs.D '+TasaVentaMostrarDigital+'</strong>');
              $('#DivTasa').show();
            }
           });

          //Incio Armado tablaResuldado
          $.ajax({
            data: parametro,
            url: URLTablaResuldado,
            type: "POST",
            success: function(data) {
                data = JSON.parse(data);

              precio = formateoPrecio(data.precio,2);
              $('#PPrecioScan').html('BsS. '+precio);
              //const TasaVentaD = eval(<?php /*echo $TasaVenta*/ ?>);
              precioDolar = formateoPrecio(data.precio/TasaVenta,2);
              $('#PPrecioDolarScan').html('$. '+precioDolar);

              precioDigital = formateoPrecio(parseFloat(data.precio)/1000000, 2);
              $('#PPrecioDigitalScan').html('BsD. '+precioDigital);

              for (var i = 0; i <= $('#ultimasConsultasTbody').find('tr').length - 1; i++) {
                console.log($('#ultimasConsultasTbody').find('tr').eq(i).find('td').eq(0).html());
                console.log(ArrJsCB[indiceCodBarScan]);

                if ($('#ultimasConsultasTbody').find('tr').eq(i).find('td').eq(0).html() == '<b>' + ArrJsCB[indiceCodBarScan] + '</b>') {
                  $('#ultimasConsultasTbody').find('tr').eq(i).remove();
                }
              }

              if ($('#ultimasConsultasTbody').find('tr').length == 3) {
                $('#ultimasConsultasTbody').find('tr').eq(2).remove();
              }

              html = `
                <tr>
                  <td class="border border-success p-3"><b>${ArrJsCB[indiceCodBarScan]}</b></td>
                  <td class="border border-success p-3"><b>${ArrJs[indiceScanDesc]}</b></td>
                  <td class="border border-success p-3"><b>${precio}</b></td>
                  <td class="border border-success p-3"><b>${precioDigital}</b></td>
                  <td class="border border-success p-3"><b>${precioDolar}</b></td>
                  <td class="border border-success p-3"><b>${moment().format('DD/MM/YYYY hh:mm A')}</b></td>
                </tr>
              `;
              $('#ultimasConsultasTbody').prepend(html);

              ultimasUnidades = '<?php echo $ultimasUnidades; ?>';

              if (ultimasUnidades > data.existencia) {
                $('.trUltimoStock').html('<td colspan="5" align="center" style="font-size:1.5rem" class="text-danger CP-Latir" colspan="3"><b><p><i class="fas fa-exclamation-triangle"></i> ¡ÚLTIMAS UNIDADES EN EXISTENCIA!</p></b></td>');
              }
            }
           });

          $('#PCodBarrScan').html(ArrJsCB[indiceCodBarScan]);
          $('#PDescripScan').html(ArrJs[indiceScanDesc]);
          $('#inputCodBar').val('');

          $('#PPrecioScan').html('');
          $('#PPrecioDolarScan').html('');
          $('#PPrecioDigitalScan').html();


          //Fin Armado tablaResuldado

          //Incio Armado tablaSugerido
          $.ajax({
            data: parametro,
            url: URLTablaSugerido,
            type: "POST",
            success: function(data) {
              if(JSON.parse(data)!="UNICO"){
                $('#tablaSugerido').show();
                var respuesta = JSON.parse(data);
                var limite = 3;
                var limiteRespuesta = respuesta.length;
                /*Armado de la fila*/
                var contenedor = $("#bodySugerido").html('');
                var nuevaFila = '<tr>';

                var i = 0;
                while (i<limite && i<=limiteRespuesta){
                  var precioE = formateoPrecio(respuesta[i]['Precio']/TasaVenta,2);
                  var precioI = formateoPrecio(respuesta[i]['Precio'],2);
                  var precioD = formateoPrecio(respuesta[i]['Precio']/1000000,2);

                  /*Armado Fila PCodBarrSug*/
                  nuevaFila += '<td align="center" class="text-black">';
                  nuevaFila += '<b><p>'+respuesta[i]['CodigoBarra']+'</p></b>';
                  nuevaFila += '</td>';
                  /*Armado Fila PDescripSug*/
                  nuevaFila += '<td align="center" class="text-black">';
                  nuevaFila += '<b><p>'+respuesta[i]['Descripcion']+'</p></b>';
                  nuevaFila += '</td>';
                  /*Armado Fila PPrecioSug*/
                  nuevaFila += '<td align="center" class="text-black">';
                  nuevaFila += '<h4><b><p>$ '+precioE+'</p></b></h4>';
                  nuevaFila += '</td>';
                  nuevaFila += '<td align="center" class="text-black">';
                  nuevaFila += '<h4><b><p>BsS. '+precioI+'</p></b></h4>';
                  nuevaFila += '</td>';
                  nuevaFila += '<td align="center" class="text-black">';
                  nuevaFila += '<h4><b><p>BsD. '+precioD+'</p></b></h4>';
                  nuevaFila += '</td>';
                  nuevaFila += '</tr>';
                  /*Ingreso de la fila a la tabla*/
                  $("#bodySugerido").html(contenedor+nuevaFila);
                  i++;
                }
                nuevaFila += '<tr>';
                nuevaFila += '<td align="center" class="text-dark" colspan="3">';
                nuevaFila += '<b><p>Nuestros precios incluyen IVA (En caso de aplicar)</p></b>';
                nuevaFila += '</td>';
                nuevaFila += '</tr>';
                /*Ingreso de la fila a la tabla*/
                $("#bodySugerido").html(contenedor+nuevaFila);
              }
              else{
                $('#tablaSugerido').hide();
              }
            }
          });
          //Fin Armado tablaSugerido
          if(timeOut!=0){
            clearTimeout(timeOut);
          }
          timeOut = setTimeout(limpiarPantalla,10000);
        }
        else {
          $('#divPromocion').html('');
          $('#carouselExampleIndicators').hide();
          $('#tablaSugerido').hide();
          $('#tablaResuldado').hide();
          $('#tablaError').show();
          $('#PCodBarrScan').html('');
          $('#PDescripScan').html('');
          $('#PPrecioScan').html('');
          $('#inputCodBar').val('');
          if(timeOut!=0){
            clearTimeout(timeOut);
          }
          timeOut = setTimeout(limpiarPantalla,5000);
        }
      }
    });

    $(document).ready(function(){
      $('#carouselExampleIndicators').carousel({
          interval: 3000,
          cycle: true,
          pause: null
      });
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

  /**********************************************************************************/
  /*
    TITULO: RCPQ_Articulos_Escaneados
    FUNCION: Armar una lista de articulos escaneados anteriormente
    RETORNO: Lista de escaneados anteriormente
    DESAROLLADO POR: SERGIO COVA
  */
  function RCPQ_Articulos_Escaneados() {
    $sql = "
      Select codigoBarra, descripcion,
        (SELECT DATE_FORMAT(consultor.created_at, '%d/%m/%Y %h:%i %p') from consultor WHERE consultor.codigo_barra = codigoBarra ORDER by consultor.created_at DESC limit 1) as fecha,
        (SELECT FORMAT(consultor.precio,2,'de_DE') FROM consultor WHERE consultor.codigo_barra = codigoBarra ORDER by consultor.created_at DESC limit 1) as precio_bs,
        (SELECT FORMAT(consultor.precio/1000000,2,'de_DE') FROM consultor WHERE consultor.codigo_barra = codigoBarra ORDER by consultor.created_at DESC limit 1) as precio_d,
        (SELECT FORMAT(consultor.precio/(select dolars.tasa from dolars order BY id DESC limit 1),2,'de_DE') FROM consultor WHERE consultor.codigo_barra = codigoBarra ORDER by consultor.created_at DESC limit 1) as precio_ds
      FROM
      (SELECT DISTINCT
      consultor.codigo_barra as codigoBarra,
      consultor.descripcion as descripcion
      FROM consultor
      order by consultor.id DESC
      limit 3) as consulta
    ";
    return $sql;
  }
?>
