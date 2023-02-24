@extends('layouts.modelConsultor')

@section('title')
  Consulta de precio
@endsection

@section('scriptsCabecera')
  <script src="{{asset('assets/jquery/jquery-2.2.2.min.js')}}"></script>
  <script src="{{asset('assets/jquery/jquery-ui.js')}}"></script>
  <script src="{{asset('assets/jquery/jquery.number.js')}}"></script>
  <script src="{{asset('assets/jquery/funciones.js')}}"></script>
  <script src="{{asset('assets/jquery/jquery.tablesorter.js')}}"></script>
  <link rel="stylesheet" href="{{asset('assets/jquery/jquery-ui.css')}}"/>
  <link rel="stylesheet" href="{{asset('assets/cpharma/AppConsulta.css')}}"/>
@endsection

@section('content')

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');
    include(app_path().'\functions\functApp.php');

    $RutaUrl = FG_Mi_Ubicacion();
    $SedeConnection = $RutaUrl;
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql_01 = "SELECT Descripcion FROM InvArticulo";
    $medicamentos = ConsultaDB($sql_01,$SedeConnection);
    $temp_med = FG_array_flatten_recursive($medicamentos);
    $medJson = json_encode($temp_med);

    $sql_02 = "SELECT Nombre FROM InvComponente";
    $componentes = ConsultaDB($sql_02,$SedeConnection);
    $temp_compo  = FG_array_flatten_recursive($componentes);
    $compoJson = json_encode($temp_compo);

    $sql_03 = "SELECT Descripcion FROM InvUso";
    $patologias = ConsultaDB($sql_03,$SedeConnection);
    $temp_patologia  = FG_array_flatten_recursive($patologias);
    $patologiaJson = json_encode($temp_patologia);
  ?>

  <!-- Modal Box -->
  <div id="selector_modo_busq" class="modal" style="z-index: 100;">
    <div class="modal-content">
      <div class="modal-header">
        <h3 style="text-align: left;">Modo de busqueda</h3>
        <span class="close" style="text-align: right;">×</span>
      </div>
      <div class="modal-body">
        <div class="modo_opciones" id="opc_1">
          <i class="fas fa-language opc_texto"></i>
          <p class="opc_texto">Nombre del medicamento (producto)</p>
        </div>
        <div class="modo_opciones" id="opc_2">
          <i class="fas fa-dna opc_texto"></i>
          <p class="opc_texto">Principio Activo o Componente</p>
        </div>
        <div class="modo_opciones" id="opc_3">
          <i class="fas fa-barcode opc_texto"></i>
          <p class="opc_texto">Código de barra</p>
        </div>
        <div class="modo_opciones" id="opc_4">
          <i class="fas fa-pills opc_texto"></i>
          <p class="opc_texto">Uso terapéutico</p>
        </div>
      </div>
    </div>
  </div>
  <!-- / Modal Box -->

  <!-- Barra de Busqueda -->
  <div class="busqueda_div_principal" style="text-align: center;">
    <!-- <h2 class="text-info" style="text-align: center;">{{FG_Nombre_Sede(FG_Mi_Ubicacion())}}</h2> -->
    <h2 class="text-info" style="text-align: center;">{{FG_Nombre_Sede(FG_Mi_Ubicacion())}}</h2>
    <div class="busq_container">
      <div class="boton_modo busq_child">
        <p class="busq_dina_modo text-white" id="btn_selector" style="font-size: 2em; text-align: center; vertical-align: middle;"><i id="icon_selector" class="fas fa-language"></i></p>
      </div>
      <div class="barra_busq busq_child">
        <input type="text" id="input_busq"  tabindex="1"
          placeholder="Ingrese el nombre del producto"
          onfocus="this.placeholder = ''"
          onblur="this.placeholder = 'Ingrese el nombre del producto'">
        </input>
      </div>
      <div class="boton_busq busq_child">
        <p class="busq_dina_modo text-white" id="btn_search" style="font-size: 2em; text-align: center; vertical-align: middle;"><i class="fas fa-search"></i></p>
      </div>
    </div>
  </div>

  <!-- / Barra de Busqueda -->
    <div class="ListaCompo">
      <div id="log"></div>
      <div class="borrarComps text-info">
        <i id="icon_selector" class="fas fa-trash text-info"></i>
      </div>
      <div class="clear"> </div>
    </div>
  <!-- barra historial -->
    <div class="barraHistorial"></div>
  <!-- / barra historial -->

  <!-- / menu -->
  <!-- despliegue de info -->

  <div class="espere"> Espere un momento por favor... </div>
  <div id="error" class="txt-danger"></div>
  <div class="contApp" id="contApp">

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
          <th scope="col" class="CP-sticky">Codigo interno</th>
          <th scope="col" class="CP-sticky">Codigo de barra</th>
          <th scope="col" class="CP-sticky">Descripcion</th>
          <th scope="col" class="CP-sticky">Precio</br>(Con IVA) <?php echo SigVe?></td>
          <th scope="col" class="CP-sticky">Precio</br>(Con IVA) <?php echo SigDolar?></td>
          <th scope="col" class="CP-sticky">Dolarizado?</td>
          <th scope="col" class="CP-sticky">Gravado?</td>
          <th scope="col" class="CP-sticky">Costo <?php echo SigVe?></td>
          <th scope="col" class="CP-sticky">Costo <?php echo SigDolar?></br>aprox.</td>
          <th scope="col" class="CP-sticky">Existencia</td>
          <th scope="col" class="CP-sticky">Ultimo Lote</td>
          <th scope="col" class="CP-sticky">Componente</td>
          <th scope="col" class="CP-sticky">Aplicacion</td>
          <th scope="col" class="CP-sticky">Ultima Venta</td>
          <th scope="col" class="CP-sticky">Ultimo Proveedor</td>
        </tr>
      </thead>
      <tbody id="tbodyapp">
      </tbody>
    </table>
  </div>

  <script type="text/javascript">
    $('#tbodyapp').hide();
    $('#contApp').hide();

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
        }
    }

    var SedeConnectionJs = '<?php echo $RutaUrl;?>';
    var dominio = dominio(SedeConnectionJs);
    const URLConsulNomb = ''+dominio+'assets/functions/funConsCompNomb.php';
    const URLConsulCod = ''+dominio+'assets/functions/funConsCompCod.php';

    $(document).on('ready',function(){

      var obj_com = eval(<?php echo $compoJson ?>);
      var obj_med = eval(<?php echo $medJson ?>);
      var obj_pat = eval(<?php echo $patologiaJson ?>);

      $('#input_busq').attr("placeholder", "Ingrese el nombre del producto");
      $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese el nombre del producto'");
      $('#input_busq').val("");

      var barraWidth = $('#input_busq').css("width");
      $('.ui-autocomplete').css("width", barraWidth-1 );

      var modal = document.getElementById('selector_modo_busq');
      var btn = document.getElementById("btn_selector");
      var icon = document.getElementById("icon_selector");
      var span = document.getElementsByClassName("close")[0];
      var opc_selec = 1;
      llenarAutoComplete(opc_selec);
      /*
          la variable 'opc_selec' siempre inicia por defecto con valor de 1
          1 = medicamento
          2 = componente
          3 = codigo barra
          4 = uso terapéutico
      */

      /* Inicio de OnClicks referentes al Modal Box */
      btn.onclick = function() {
        modal.style.display = "block";
      }
      span.onclick = function() {
        modal.style.display = "none";
      }
      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
      $('#opc_1').click(function(e) {
        if (opc_selec != 1){
          opc_selec = 1;
          modal.style.display = "none";
          updateModalBox(opc_selec);
        }
        else {
          modal.style.display = "none";
        }
      });
      $('#opc_2').click(function(e) {
        if (opc_selec != 2){
          opc_selec = 2;
          modal.style.display = "none";
          updateModalBox(opc_selec);
        }
        else {
          modal.style.display = "none";
        }
      });
      $('#opc_3').click(function(e) {
        if (opc_selec != 3){
          opc_selec = 3;
          modal.style.display = "none";
          updateModalBox(opc_selec);
        }
        else {
          modal.style.display = "none";
        }
      });
      $('#opc_4').click(function(e) {
        if (opc_selec != 4){
          opc_selec = 4;
          modal.style.display = "none";
          updateModalBox(opc_selec);
        }
        else {
          modal.style.display = "none";
        }
      });
      /* Final de OnClicks referentes al Modal Box */

      /* Div referente a los componentes para la busqueda */
      function log(message) {
        if ($("#log").text() == '') {
          $("#log").append(message);
          $('.ListaCompo').css("display", "block");
        }
        else {
          $("#log").append(','+message);
          $('.ListaCompo').css("display", "block");
        }
        $("#log").scrollTop( 0 );
      }

      //Autocomplete para la busqueda por nombre medicamento
      function llenarAutoComplete (modo_selec) {

        switch (modo_selec) {
          case 1:
            $( "#input_busq" ).autocomplete({
              source: obj_med,
              delay: 150,
              minLength: 3,
              open: function(e, ui) {
                //using the 'open' event to capture the originally typed text
                var self = $(this),
                val = self.val();
                //saving original search term in 'data'.
                self.data('searchTerm', val);
              },
              focus: function(e, ui) {
                return false;
              },
              select: function( e, ui ) {
                var self = $(this),
                  keyPressed = e.keyCode,
                  keyWasEnter = e.keyCode === 13,
                  useSelection = false,
                  val = self.data('searchTerm');
                if (keyPressed) {
                  if (keyWasEnter) {
                    e.preventDefault();
                  }
                }
                return useSelection;
              }
            });
            $("#input_busq").autocomplete( "enable" );
          break;
          case 2:
            //Autocomplete para la busqueda por Principio Activo
            $( "#input_busq" ).autocomplete({
              source: obj_com,
              delay: 150,
              minLength: 3,
              select: function( event, ui ) {

                log(ui.item ? "" + ui.item.value : "" + this.value );

                var selectedObj = ui.item;
                var temp = selectedObj.value;
                var enviar = temp.replace("+","*");
                ui.item.value = '';
              }
            });
            $("#input_busq").autocomplete( "enable" );
          break;
          case 3:
            //Este tipo de busqueda no requiere de un modulo .autoComplete
            $("#input_busq").autocomplete( "disable" );
          break;
          case 4:
            //Autocomplete para la busqueda por uso terapéutico
            $( "#input_busq" ).autocomplete({
              source: obj_pat,
              delay: 150,
              minLength: 3,
              select: function( event, ui ) {

                log(ui.item ? "" + ui.item.value : "" + this.value );

                var selectedObj = ui.item;
                var temp = selectedObj.value;
                var enviar = temp.replace("+","*");
                ui.item.value = '';
              }
            });
            $("#input_busq").autocomplete( "enable" );
          break;
          default:
          break;
        }
      }

      //Darle click al boton de busqueda
      $('#btn_search').click(function(e) {
        switch (opc_selec) {
          case 1:
            //buscar cuando el modo de busqueda hace referencia al nombre de productos
            if ( $('#input_busq').val() != "" ) {

              $('.barraHistorial').empty();
              var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
              $('.barraHistorial').append(historia_origen);

              //Inicio de la busqueda y el armado de la tabla
                var busq = $('#input_busq').val();
                var parametro = {
                "Descripcion":busq
                };

                $.ajax({
                  data: parametro,
                  url: URLConsulNomb,
                  type: "POST",
                  success: function(data) {
                    //alert(data);
                    var contenedor = $("#tbodyapp").html();
                    contenedor = '';
                    $("#tbodyapp").html(contenedor+data);
                    $('#contApp').show();
                    $('#tbodyapp').show();
                    $('#error').hide();
                  }
                 });
              //Fin de la busqueda y el armado de la tabla
             $("#input_busq").val('');
            }
          break;
          case 2:
            //buscar cuando el modo de busqueda hace referencia a los componentes
            if ( $('#log').text() != "" ) {
              consultaAjax( "tempComponentes="+$('#log').text() , "lib/busquedaC.php", 1 , '.contApp' );

              $('.barraHistorial').empty();
              var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Comp">'+$('#log').text()+'</div></div>');
              $('.barraHistorial').append(historia_origen);

              $("#input_busq").val('');
              $('#log').empty();
              $('.ListaCompo').css("display", "none");
              ajustarTamano();
            }
          break;
          case 3:
            //buscar cuando el modo de busqueda hace referencia al codigo de barra especifico de un producto
            if ( $('#input_busq').val() != "" ) {

              $('.barraHistorial').empty();
              var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
              $('.barraHistorial').append(historia_origen);

              //Inicio de la busqueda y el armado de la tabla
                var busq = $('#input_busq').val();
                var parametro = {
                "codbar":busq
                };

                $.ajax({
                  data: parametro,
                  url: URLConsulCod,
                  type: "POST",
                  success: function(data) {
                    //alert(data);
                    var contenedor = $("#tbodyapp").html();
                    contenedor = '';
                    $("#tbodyapp").html(contenedor+data);
                    $('#contApp').show();
                    $('#tbodyapp').show();
                    $('#error').hide();
                  }
                 });
              //Fin de la busqueda y el armado de la tabla
             $("#input_busq").val('');
            }
          break;
          case 4:
            //buscar cuando el modo de busqueda hace referencia a los uso terapéutico
            if ( $('#log').text() != "" ) {
              consultaAjax( "tempPatologias="+$('#log').text() , "lib/busquedaP.php", 1 , '.contApp' );

              $('.barraHistorial').empty();
              var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Tera">'+$('#log').text()+'</div></div>');
              $('.barraHistorial').append(historia_origen);

              $("#input_busq").val('');
              $('#log').empty();
              $('.ListaCompo').css("display", "none");
              ajustarTamano();
            }
          break;
          default:
          break;
        }
      });

      //Presionar Enter sobre el boton de busqueda (tabulado)
      $('#input_busq').keyup(function(e) {
        switch (opc_selec) {
          case 1:
            //buscar cuando el modo de busqueda hace referencia al nombre de productos
            if(e.keyCode == 13) {
               if ( $('#input_busq').val() != "" ) {

                $('.barraHistorial').empty();
                var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
                $('.barraHistorial').append(historia_origen);

                //Inicio de la busqueda y el armado de la tabla
                  var busq = $('#input_busq').val();
                  var parametro = {
                  "Descripcion":busq
                  };

                  $.ajax({
                    data: parametro,
                    url: URLConsulNomb,
                    type: "POST",
                    success: function(data) {
                      //alert(data);
                      var contenedor = $("#tbodyapp").html();
                      contenedor = '';
                      $("#tbodyapp").html(contenedor+data);
                      $('#contApp').show();
                      $('#tbodyapp').show();
                      $('#error').hide();
                    }
                   });
                //Fin de la busqueda y el armado de la tabla
                $("#input_busq").val('');
              }
            }
            break;
          case 2:
            //buscar cuando el modo de busqueda hace referencia a los componentes
            if (e.keyCode == 13) {
              if ( $('#log').text() != "" ) {
                consultaAjax( "tempComponentes="+$('#log').text() , "lib/busquedaC.php", 1 , '.contApp' );

                $('.barraHistorial').empty();
                var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Comp">'+$('#log').text()+'</div></div>');
                $('.barraHistorial').append(historia_origen);

                $("#input_busq").val('');
                $('#log').empty();
                $('.ListaCompo').css("display", "none");
                ajustarTamano();
              }
            }
            break;
          case 3:
            //buscar cuando el modo de busqueda hace referencia al codigo de barra especifico de un producto
            if (e.keyCode == 13) {
              if ( $('#input_busq').val() != "" ) {

                $('.barraHistorial').empty();
                var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
                $('.barraHistorial').append(historia_origen);

                //Inicio de la busqueda y el armado de la tabla
                  var busq = $('#input_busq').val();
                  var parametro = {
                  "codbar":busq
                  };

                  $.ajax({
                    data: parametro,
                    url: URLConsulCod,
                    type: "POST",
                    success: function(data) {
                      //alert(data);
                      var contenedor = $("#tbodyapp").html();
                      contenedor = '';
                      $("#tbodyapp").html(contenedor+data);
                      $('#contApp').show();
                      $('#tbodyapp').show();
                      $('#error').hide();
                    }
                   });
                //Fin de la busqueda y el armado de la tabla
               $("#input_busq").val('');
              }
            }
            break;
          case 4:
            //buscar cuando el modo de busqueda hace referencia a los usos terapeuticos
            if (e.keyCode == 13) {
              if ( $('#log').text() != "" ) {
                consultaAjax( "tempPatologias="+$('#log').text() , "lib/busquedaP.php", 1 , '.contApp' );
                $('.barraHistorial').empty();
                var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Tera">'+$('#log').text()+'</div></div>');
                $('.barraHistorial').append(historia_origen);

                $("#input_busq").val('');
                $('#log').empty();
                $('.ListaCompo').css("display", "none");
                ajustarTamano();
              }
            }
            break;
          default:

            break;
        }
      });

      //Presionar Enter dentro de la barra de busqueda
      $('#input_busq').keyup(function(e) {
        switch (opc_selec) {
          case 1:
            //cuando el modo de busqueda hace referencia al nombre de productos
            if(e.keyCode == 13) {

              if ( $('#input_busq').val() != "" ) {

                $('.barraHistorial').empty();
                var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
                $('.barraHistorial').append(historia_origen);

                //Inicio de la busqueda y el armado de la tabla
                  var busq = $('#input_busq').val();
                  var parametro = {
                  "Descripcion":busq
                  };

                  $.ajax({
                    data: parametro,
                    url: URLConsulNomb,
                    type: "POST",
                    success: function(data) {
                      //alert(data);
                      var contenedor = $("#contApp").html();
                      contenedor = '';
                      $("#contApp").html(contenedor+data);
                      $('#contApp').show();
                    }
                   });
                //Fin de la busqueda y el armado de la tabla
                $("#input_busq").val('');
              }
            }
            break;
          case 2:
            //No hace falta cambiar el comportamiento por defecto
            ajustarTamano();
            break;
          case 3:
            //buscar cuando el modo de busqueda hace referencia al codigo de barra especifico de un producto
            if (e.keyCode == 13) {
              if ( $('#input_busq').val() != "" ) {
                consultaAjax( "op="+$('#input_busq').val() , "lib/busquedaB.php", 1 , '.contApp' );

                $('.barraHistorial').empty();
                var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Cod">'+$('#input_busq').val()+'</div></div>');
                $('.barraHistorial').append(historia_origen);
                $("#input_busq").val('');
                ajustarTamano();
              }
            }
            break;
          case 4:
            //No hace falta cambiar el comportamiento por defecto
            ajustarTamano();
            break;
          default:

            break;
        }
      });

      // borrar lista de componentes seleccionados
      $('.borrarComps').click(function(e) {
        $('#log').empty();
        $("#input_busq").val("");
        $('.ListaCompo').css("display", "none");
      });

      function updateModalBox(modo_selec) {

        if (modo_selec == 1) {
          $("#icon_selector").removeClass("fas fa-language");
          $("#icon_selector").removeClass("fas fa-dna");
          $("#icon_selector").removeClass("fas fa-barcode");
          $("#icon_selector").removeClass("fas fa-pills");
          $("#icon_selector").addClass("fas fa-language");

          $('#input_busq').attr("placeholder", "Ingrese el nombre del producto");
          $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese el nombre del producto'");
          $('#input_busq').val("");
          llenarAutoComplete (modo_selec);
        }
        else if (modo_selec == 2) {
          $("#icon_selector").removeClass("fas fa-language");
          $("#icon_selector").removeClass("fas fa-dna");
          $("#icon_selector").removeClass("fas fa-barcode");
          $("#icon_selector").removeClass("fas fa-pills");
          $("#icon_selector").addClass("fas fa-dna");

          $('#input_busq').attr("placeholder", "Ingrese el principio activo (componentes)");
          $('#btn_selector').attr("src", "css/images/componente.png");
          $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese el principio activo (componentes)'");
          $('#input_busq').val("");
          llenarAutoComplete (modo_selec);
        }
        else if (modo_selec == 3) {
          $("#icon_selector").removeClass("fas fa-language");
          $("#icon_selector").removeClass("fas fa-dna");
          $("#icon_selector").removeClass("fas fa-barcode");
          $("#icon_selector").removeClass("fas fa-pills");
          $("#icon_selector").addClass("fas fa-barcode");

          $('#input_busq').attr("placeholder", "Haga scan del codigo de barra");
          $('#btn_selector').attr("src", "css/images/codigo.png");
          $('#input_busq').attr("onblur", "this.placeholder = 'Haga scan del codigo de barra'");
          $('#input_busq').val("");
          llenarAutoComplete (modo_selec);
        }
        else if (modo_selec == 4) {
          $("#icon_selector").removeClass("fas fa-language");
          $("#icon_selector").removeClass("fas fa-dna");
          $("#icon_selector").removeClass("fas fa-barcode");
          $("#icon_selector").removeClass("fas fa-pills");
          $("#icon_selector").addClass("fas fa-pills");

          $('#input_busq').attr("placeholder", "Ingrese el uso terapéutico");
          $('#btn_selector').attr("src", "css/images/pastilla.png");
          $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese el uso terapéutico'");
          $('#input_busq').val("");
          llenarAutoComplete (modo_selec);
        }
      }
    });
  </script>
@endsection
