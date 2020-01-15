@extends('layouts.modelUser')

@section('title')
  Consulta de precio
@endsection

@section('scriptsCabecera')
<script src="{{asset('assets/jquery/jquery-2.2.2.min.js')}}"></script>
<script src="{{asset('assets/jquery/jquery-ui.js')}}"></script>
<script src="{{asset('assets/jquery/jquery.number.js')}}"></script>
<script src="{{asset('assets/jquery/funciones.js')}}"></script>
<script src="{{asset('assets/jquery/jquery.tablesorter.js')}}"></script>
<link rel="stylesheet" href="{{asset('assets/jquery/jquery-ui.css')}}">
<link rel="stylesheet" href="{{asset('assets/cpharma/AppConsulta.css')}}">
@endsection

@section('content')

  <?php 
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');
    include(app_path().'\functions\functApp.php');
  ?>

  <!-- Modal Box -->
  <div id="selector_modo_busq" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 style="text-align: left;">Modo de busqueda</h2>
        <span class="close" style="text-align: right;">×</span> 
      </div>
      <div class="modal-body">
        <div class="modo_opciones" id="opc_1">
          <p class="opc_texto">Nombre del medicamento (producto)</p>
        </div>
        <div class="modo_opciones" id="opc_2">
          <p class="opc_texto">Principio Activo o Componente</p>
        </div>
        <div class="modo_opciones" id="opc_3">
          <p class="opc_texto">Código de barra</p>
        </div>
        <div class="modo_opciones" id="opc_4">
          <p class="opc_texto">Uso terapéutico</p>
        </div>
      </div>

    </div>

  </div>
  <!-- / Modal Box -->

  <!-- Barra de Busqueda -->
  <div class="busqueda_div_principal">

    <?php
      $SedeConnection = 'FTN';//FG_Mi_Ubicacion();
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

    <div class="busq_container">
      <div class="boton_modo busq_child">
        <div class="busq_dina_modo" tabindex="2">
          <span id="ImgBarra"></span>
        </div>
      </div>
      <div class="barra_busq busq_child">
        <input type="text" id="input_busq"  tabindex="1" 
          placeholder="Ingrese nombre del producto" 
          onfocus="this.placeholder = ''" 
          onblur="this.placeholder = 'Ingrese nombre del producto'">
        </input>
      </div>
      <div class="boton_busq busq_child">
        <div class="busq_dina_boton" tabindex="2">
          <img src="css/images/buscar.png"  class="busq_dina_img" />
        </div>
      </div>
    </div>

    
  <div class="clear"> </div>  
  </div>
  <!-- / Barra de Busqueda -->

    <div class="ListaCompo">
      <div id="log"></div>
      <div class="borrarComps"><img src='css/images/ic_delete_black_36dp_2x.png' height='20px' width='20px'/>  </div>
      <div class="clear"> </div>
    </div>  
    
  <!-- barra historial -->
    <div class="barraHistorial"></div>
  <!-- / barra historial -->
    
  <!-- / menu -->
  <!-- despliegue de info -->

  <div class="espere"> Espere un momento por favor... </div>
  <div id="error">  </div>
  <div id="correcto">  </div> 
  <div class="contApp" id="contApp">
    
  </div>

  <!-- buscar en externo -->
  <!-- <div class="consultaE" id="consultaE">
    <img src="css/images/cargando.gif" class="cargando">
    <img src="css/images/listo.png" class="listo">
    <img src="css/images/cancel.png" class="cancel">
    <div class="consultaEInfo" ></div>
  </div> -->
    
  <!-- lista de items seleccionados -->
  <!-- <div class="Dash">
    <div class="tituPri tituPriList"> 
        <div class="tituPri3">ARTICULOS A VENDER </div>
        <div class="flecha"><img src='css/images/flechaCarrito.png' height='30px' width='30px'/>  </div>
        <div class="NuCantidad"> 0 </div>
        <div class="textCantidad"> articulos(s) &nbsp &nbsp - &nbsp &nbsp Bs. </div>
        <div class="bsCantidad"> 0.00 </div>
        <div class="borrar"><img src='css/images/borrar.png' height='30px' width='30px'/>  </div>
        <div class="clear"></div>
    </div>
    <div class="contItems ItemSelec">   
    </div>
  </div> -->

  <!-- nombre de la farmacia -->
  <!-- <div class="nombreF">
  ACI
  <div class="ocultarExistencia"> 
  <input id="ocultarExistencia" type="checkbox" name="ocultar" value="1" > <div> Ocultar existencia en 0</div>
  </div>
  </div> -->

  <script type="text/javascript">
  $(document).on('ready',function(){
    
    var mostrar = 0;
    $('#consultaE').click(function(e) {
      if( mostrar == 0 ) {
        jQuery('.listo').css('display' , 'none' );
        jQuery('.cancel').css('display' , 'none' );
        $('.consultaEInfo').css('display' , 'block' );
        $('#consultaE').animate({ width: "30%" , height: "60%" }, 500 );
        mostrar = 1;
      } else {
        $('#consultaE').animate({ width: "50px" , height: "50px"  }, 500 );
        mostrar = 0;
        $('.consultaEInfo').empty();
        $('.consultaEInfo').css('display' , 'none' );
      }
      
    });
    
    var tempCant = 0; //guarda la cantidad antes de ser modificada en el carrito de compras
    var obj_com = eval(<?php echo $compoJson ?>);
    var obj_med = eval(<?php echo $medJson ?>);
    var obj_pat = eval(<?php echo $patologiaJson ?>);

    $('#input_busq').attr("placeholder", "Ingrese nombre del producto");
    $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese nombre del producto'");
    $('#input_busq').val("");
    
    var barraWidth = $('#input_busq').css("width");
    $('.ui-autocomplete').css("width", barraWidth-1 );

    var modal = document.getElementById('selector_modo_busq');
    var btn = document.getElementById("btn_selector");
    var span = document.getElementsByClassName("close")[0];
    var opc_selec = 1;
    updateModalBoxAP(opc_selec);
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
        updateModalBoxAP(opc_selec);
      }
      else {
        modal.style.display = "none";
      }
    });
    $('#opc_2').click(function(e) {
      if (opc_selec != 2){
        opc_selec = 2;
        modal.style.display = "none";
        updateModalBoxAP(opc_selec);
      }
      else {
        modal.style.display = "none";
      }
    });
    $('#opc_3').click(function(e) {
      if (opc_selec != 3){
        opc_selec = 3;
        modal.style.display = "none";
        updateModalBoxAP(opc_selec);
      }
      else {
        modal.style.display = "none";
      }
    });
    $('#opc_4').click(function(e) {
      if (opc_selec != 4){
        opc_selec = 4;
        modal.style.display = "none";
        updateModalBoxAP(opc_selec);
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
    $('.busq_dina_boton').click(function(e) { 
      switch (opc_selec) {
        case 1:
          //buscar cuando el modo de busqueda hace referencia al nombre de productos
          if ( $('#input_busq').val() != "" ) {         
            
            $('.barraHistorial').empty();
            var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
            $('.barraHistorial').append(historia_origen);

            consultaAjax( "op="+$('#input_busq').val() , "lib/busquedaM.php", 1 , '.contApp' );
            $("#input_busq").val('');
            ajustarTamano();
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
            consultaAjax( "op="+$('#input_busq').val() , "lib/busquedaB.php", 1 , '.contApp' );

            $('.barraHistorial').empty();
            var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Cod">'+$('#input_busq').val()+'</div></div>');
            $('.barraHistorial').append(historia_origen);
            $("#input_busq").val('');
            ajustarTamano();
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
    $('.busq_dina_boton').keyup(function(e) { 
      switch (opc_selec) {
        case 1:
          //buscar cuando el modo de busqueda hace referencia al nombre de productos
          if(e.keyCode == 13) {
            if ( $('#input_busq').val() != "" ) {         
              $('.barraHistorial').empty();
              var historia_origen = $('<div class="historia hOrigen"><div class="imgHist_Nom">'+$('#input_busq').val()+'</div></div>');
              $('.barraHistorial').append(historia_origen);
              
              consultaAjax( "op="+$('#input_busq').val() , "lib/busquedaM.php", 1 , '.contApp' );
              $("#input_busq").val('');
              ajustarTamano();
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

              consultaAjax( "op="+$('#input_busq').val() , "lib/busquedaM.php", 1 , '.contApp' );
              $("#input_busq").val('');
              $("#input_busq").blur();
              ajustarTamano();
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



    function updateModalBoxAP(modo_selec) {
      
      if (modo_selec == 1) {
        $('#ImgBarra').HTML('<i class="fas fa-language text-dark"></i>');
        
        $('#opc_1').find(".opc_texto").css("color", "#7bd1ef");
        $('#opc_2').find(".opc_texto").css("color", "#231f20");
        $('#opc_3').find(".opc_texto").css("color", "#231f20");
        $('#opc_4').find(".opc_texto").css("color", "#231f20");

        $('#input_busq').attr("placeholder", "Ingrese nombre del producto");
        $('#btn_selector').attr("src", "css/images/letras.png");
        $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese nombre del producto'");
        $('#input_busq').val("");

        llenarAutoComplete (modo_selec);
      }
      else if (modo_selec == 2) {
        $('#opc_1').css("background-image", "url(css/images/letras_no_activa.png)");
        $('#opc_2').css("background-image", "url(css/images/comp_activa.png)");
        $('#opc_3').css("background-image", "url(css/images/codigo_no_activa.png)");
        $('#opc_4').css("background-image", "url(css/images/pato_no_activa.png)");

        $('#opc_1').find(".opc_texto").css("color", "#231f20");
        $('#opc_2').find(".opc_texto").css("color", "#7bd1ef");
        $('#opc_3').find(".opc_texto").css("color", "#231f20");
        $('#opc_4').find(".opc_texto").css("color", "#231f20");

        $('#input_busq').attr("placeholder", "Ingrese principio activo (componentes)");
        $('#btn_selector').attr("src", "css/images/componente.png");
        $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese principio activo (componentes)'");
        $('#input_busq').val("");
        llenarAutoComplete (modo_selec);
      }
      else if (modo_selec == 3) {
        $('#opc_1').css("background-image", "url(css/images/letras_no_activa.png)");
        $('#opc_2').css("background-image", "url(css/images/comp_no_activa.png)");
        $('#opc_3').css("background-image", "url(css/images/codigo_activa.png)");
        $('#opc_4').css("background-image", "url(css/images/pato_no_activa.png)");

        $('#opc_1').find(".opc_texto").css("color", "#231f20");
        $('#opc_2').find(".opc_texto").css("color", "#231f20");
        $('#opc_3').find(".opc_texto").css("color", "#7bd1ef");
        $('#opc_4').find(".opc_texto").css("color", "#231f20");

        $('#input_busq').attr("placeholder", "Haga scan del codigo de barra");
        $('#btn_selector').attr("src", "css/images/codigo.png");
        $('#input_busq').attr("onblur", "this.placeholder = 'Haga scan del codigo de barra'");
        $('#input_busq').val("");
        llenarAutoComplete (modo_selec);
      }
      else if (modo_selec == 4) {
        $('#opc_1').css("background-image", "url(css/images/letras_no_activa.png)");
        $('#opc_2').css("background-image", "url(css/images/comp_no_activa.png)");
        $('#opc_3').css("background-image", "url(css/images/codigo_no_activa.png)");
        $('#opc_4').css("background-image", "url(css/images/past_activa.png)");
        
        $('#opc_1').find(".opc_texto").css("color", "#231f20");
        $('#opc_2').find(".opc_texto").css("color", "#231f20");
        $('#opc_3').find(".opc_texto").css("color", "#231f20");
        $('#opc_4').find(".opc_texto").css("color", "#7bd1ef");

        $('#input_busq').attr("placeholder", "Ingrese uso terapéutico");
        $('#btn_selector').attr("src", "css/images/pastilla.png");
        $('#input_busq').attr("onblur", "this.placeholder = 'Ingrese uso terapéutico'");
        $('#input_busq').val("");
        llenarAutoComplete (modo_selec);
      }
    }

    

  //ajustar carrito al centro de la pantalla
    $('.Dash').css( 'margin-left' ,( $(window).width() - $('.Dash').width() ) / 2 );
    
    $( window ).resize(function() {
      $('.Dash').css( 'margin-left' ,( $(window).width() - $('.Dash').width() ) / 2 );
      if( $('.contItems > tr:last-child td.Descr') !== null) 
      {
        $('.contItems > tr:last-child td.Descr').width($('#tablaResultado  tr  .Descr').width());
      }
    });
    
    

  //borrar carrito
    $('.borrar').click(function(e) {
      $('.contItems').empty();
      $('.contItems').css( 'height', 'auto' ) ;
      ajustarTamano();
      $('.NuCantidad').text(0); 
      precio = 0.00;
      $('.bsCantidad').text(precio.toFixed(2));
    });
    
    
  /*    
    //calcular tamaño de la ventana
        $('.Dash').css('top',$(window).height()-200);
        if ( ($(window).width()-960)/2 < 0 ){
          var EspList = $(window).width();
          $('.Dash').css( 'left',0);
        } else {
          var EspList = ($(window).width()-960)/2;
          $('.Dash').css('left',EspList);
        };  
    
      
      $( window ).resize(function() {
        $('.Dash').css('top',$(window).height()-200);
        if ( ($(window).width()-960)/2 < 0 ){
          var EspList = $(window).width();
          $('.Dash').css( 'left',0);
        } else {
          var EspList = ($(window).width()-960)/2;
          $('.Dash').css('left',EspList);
        };    
      });
      
      $(window).scroll(function() {
        $('.Dash').css('top',$(window).height()-200);
        if ( ($(window).width()-960)/2 < 0 ){
          var EspList = $(window).width();
          $('.Dash').css( 'left',0);
        } else {
          var EspList = ($(window).width()-960)/2;
          $('.Dash').css('left',EspList);
        };  
      });
  */

  // actualiza la suma los articulos en el carrito
  /*
  // rutina vieja de actualizar carrito de compras
    $('.contItems').on("focusout", ".cantPe"  , function() {
      if ($(this).val() > 0) 
      {
      precio2 = $(this).parent().prev().text().toString().replace(/\./g, '');
      precio2 = precio2.toString().replace(/\,/g, '.');
      var precio = precio2.split(" ");
      precio = parseFloat(precio[precio.length-1]);
      
      var total = $('.bsCantidad').text().toString().replace(/\./g, '');
      total = total.toString().replace(/\,/g, '.');
      if ($(this).data("guardado") == 0 ){
        $(this).data ("guardado", 1)
        $('.NuCantidad').text(parseInt($('.NuCantidad').text()) + parseInt($(this).val()));   
        $('.bsCantidad').text(parseFloat(total) + precio*parseInt($(this).val()) ); 
        
      } else {
        $('.NuCantidad').text(parseInt($('.NuCantidad').text()) + parseInt($(this).val()) - tempCant ); 
        $('.bsCantidad').text( (parseFloat(total) + precio*parseFloat($(this).val()) - tempCant*precio) );    
      }
      precio=parseFloat($('.bsCantidad').text());
      $('.bsCantidad').text($.number(precio , 2 , ',' , '.'));
      } else {
        $(this).val(tempCant);
      }
    });

    //graba la cantidad inicial del articulo para luego restarla
    $('.contItems').on("focusin", ".cantPe"  , function() {   
      tempCant = parseInt($(this).val()); 
      //valida si se le da enter para guardar el nuevo valor
      $('.cantPe').keyup(function(e) { 
        if(e.keyCode == 13) {
          $('#input_busq').focus();
        }
      });
    });
  */  
  //  cierre de rutina vieja de actualizar carrito de compras 

    $('.contItems').on("focusout", ".cantPe"  , function() {
      var SumCantid = 0;
      var total = 0;
      $('.opWi').each(function(){ 
        SumCantid = SumCantid + parseInt($(this).children('.Cantdip').children('.cantPe').val());
        precio2 = $(this).children('td:nth-child(3)').text().toString().replace(/\./g, '');
        precio2 = precio2.toString().replace(/\,/g, '.');
        var precio = precio2.split(" ");
        total = total + parseFloat(precio[precio.length-1]) * parseInt($(this).children('.Cantdip').children('.cantPe').val());
      } );
      $('.NuCantidad').text(SumCantid); 
      $('.bsCantidad').text($.number(total , 2 , ',' , '.'));
    }); 
    
    $('.contItems').on("focusin", ".cantPe"  , function() {   
      $('.cantPe').keyup(function(e) { 
        if(e.keyCode == 13) {
          $('#input_busq').focus();
        }
      });
    });

  // cierre actualiza la suma los articulos en el carrito

  // borra articulos en el carrito
  $('.contItems').on("click", ".botonMenos"  , function() {   
    var agarrar = $(this).parent();
    var tempTa = 0;
    var tempTa2 = agarrar.height();
    agarrar.remove();
  // fin rutina vieja para ajustar cantidades y precio

    var SumCantid = 0;
    var total = 0;
    $('.opWi').each(function(){ 
      SumCantid = SumCantid + parseInt($(this).children('.Cantdip').children('.cantPe').val());
      precio2 = $(this).children('td:nth-child(3)').text().toString().replace(/\./g, '');
      precio2 = precio2.toString().replace(/\,/g, '.');
      var precio = precio2.split(" ");
      total = total + parseFloat(precio[precio.length-1]) * parseInt($(this).children('.Cantdip').children('.cantPe').val());
    } );
    $('.NuCantidad').text(SumCantid); 
    $('.bsCantidad').text($.number(total , 2 , ',' , '.'));
    
    //ajustar tamaño del carrito de compras  <25% es el tamaño max del carrito
    tamanoMaxCarrito =  parseInt(($(window).height()*25) / 100 ) - $('.tituPri').height() ;
    $('.opWi').each(function(){ tempTa = tempTa + $('.opWi').height(); } );
    
    if ( tempTa <= tamanoMaxCarrito ) {
      if (tempTa == 0) {
        ajustarTamano();
      } else {
        $('.contItems').css( 'height', 'auto' ) ;
        ajustarTamano();
      }
      
    }   
  });

  });   
  </script>
@endsection