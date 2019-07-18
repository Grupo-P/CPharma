@extends('layouts.model')

@section('title')
    Carta de compromiso
@endsection

<script>
  /*
    TITULO: enviarFormulario
    PARAMETROS : No aplica
    FUNCION: Realizar la validacion de todas las fechas y permitir o no el envio
    RETORNO: true o false

    Variables:
      - Variables de entrada:
        * formulario: Formulario principal con los campos del registro
        * fecha_vencimiento: La fecha de vencimiento del articulo
        * fecha_tope: La fecha limite de la carta de compromiso
        * fecha_recepcion: La de recepcion del articulo
  */
 
  function enviarFormulario() {
    var formulario = document.getElementById("form_registros");
    var fecha_vencimiento = document.getElementById("fecha_vencimiento").value;
    var fecha_tope = document.getElementById("fecha_tope").value;
    var fecha_recepcion = document.getElementById("fecha_recepcion").value;

    if((fecha_tope <= fecha_vencimiento) && (fecha_tope > fecha_recepcion)) {
      formulario.submit();
      return true;
    } 
    else {
      $('#errorValidation').modal('show');
      return false;
    }
  }
</script>

@section('content')
  <div class="modal fade" id="errorValidation" tabindex="-1" role="dialog" aria-labelledby="errorValidationTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorValidationTitle">
            <i class="fas fa-exclamation-triangle text-danger"></i>{{session('Error')}}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha tope</b> debe ser menor o igual a <b>la fecha de vencimiento</b> y mayor a la <b>fecha de recepci&oacute;n</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-success" data-dismiss="modal">
            Aceptar
          </button>
        </div>
      </div>
    </div>
  </div>
<<<<<<< HEAD

=======
  
>>>>>>> CartaDeCompromisoReplanteado
  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>
    Agregar carta de compromiso
  </h1>
  <hr class="row align-items-start col-12">

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\querys.php');
    include(app_path().'\functions\funciones.php');
    include(app_path().'\functions\reportes.php');

    $ArtJson = "";
    //OJO ELIMINAR $_GET['SEDE']="FTN";
    $_GET['SEDE']="FTN";
    
    if(isset($_GET['Id'])) {
      /*CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR
                Se pasa a la carga de las facturas del proveedor*/
      if (isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';
      $InicioCarga = new DateTime("now");
      
      ReporteProveedorFactura($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);
      
      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['lote'])){
      /*CASO 5: CARGA AL HABER COMPLETADO LA CARTA DE COMPROMISO
                Se pasa a la solicitud de fechas*/
      if (isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");

      GuardarCartaDeCompromiso($_GET['articulo'],$_GET['lote'],$_GET['fecha_vencimiento'],$_GET['proveedor'],$_GET['fecha_recepcion'],$_GET['fecha_tope'],$_GET['causa'],$_GET['nota']);

      $sql = QListaProveedores();
      $ArtJson = armarJson($sql,$_GET['SEDE']);

      echo '
      <form autocomplete="off" action="">
        <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del proveedor " onkeyup="conteo()" required>
          <input id="myId" name="Id" type="hidden">
          <td>
          <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
          </td>
        </div>
        <input type="submit" value="Buscar" class="btn btn-outline-success">
      </form>
      ';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['IdArt'])) {
      /*CASO 4: CARGA AL HABER SELECCIONADO UNA ARTICULO
                Se pasa a la solicitud de fechas*/
      if(isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");

      CartaDeCompromiso($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact'],$_GET['IdArt']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['IdFact'])){
    /*CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA 
              Se pasa a la seleccion del articulo*/
      if(isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");

      ReporteFacturaArticulo($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      /*CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
                Se pasa a la seleccion del proveedor*/
      if(isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");

      $sql = QListaProveedores();
      $ArtJson = armarJson($sql,$_GET['SEDE']);

      echo '
      <form autocomplete="off" action="">
        <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del proveedor " onkeyup="conteo()" required>
          <input id="myId" name="Id" type="hidden">
          <td>
          <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
          </td>
        </div>
        <input type="submit" value="Buscar" class="btn btn-outline-success">
      </form>
      ';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    } 
  ?>

  <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
<<<<<<< HEAD
    //$('#errorValidation').modal('show');
=======
    $('#exampleModalCenter').modal('show');
>>>>>>> CartaDeCompromisoReplanteado
  </script>
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
    </script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>

    <style>
    * {
      box-sizing: border-box;
    }

    /*the container must be positioned relative:*/
    .autocomplete {
      position: relative;
      display: inline-block;
    }

    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }

    input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
    }

    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      /*position the autocomplete items to be the same width as the container:*/
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

    /*when hovering an item:*/
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
    }

    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
      background-color: DodgerBlue !important; 
      color: #ffffff; 
    }
    </style>
@endsection

@section('scriptsFoot')
  <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      ArrJs = eval(<?php echo $ArtJson ?>);
      autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
    </script> 
  <?php
    }
  ?>  
@endsection