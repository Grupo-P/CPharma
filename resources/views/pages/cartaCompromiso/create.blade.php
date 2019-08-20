@extends('layouts.model')

@section('title')
    Compromisos
@endsection

<script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>

@section('content')
  <!-- Modal Error De Fecha Recepcion Mayor A Fecha Tope -->
  <div class="modal fade" id="errorModalCenter1" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle1">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha tope (Compromiso)</b> no puede ser menor que <b>la fecha de recepcion (Articulo)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error De Fecha Tope Menor A Fecha De Recepcion -->
  <div class="modal fade" id="errorModalCenter2" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle2">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha de vencimiento (Articulo)</b> no puede ser mayor que <b>la fecha tope (Compromiso)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error De Fecha De Recepcion Mayor A Fecha De Vencimiento -->
  <div class="modal fade" id="errorModalCenter3" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle3">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha de recepcion (Articulo)</b> no puede ser mayor que <b>la fecha de vencimiento (Articulo)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error De Fecha De Recepcion Mayor A Fecha De Vencimiento -->
  <div class="modal fade" id="errorModalCenter4" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle4">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha de factura</b> debe ser mayor que <b>la fecha de recepcion (Articulo)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>
    Agregar Compromisos
  </h1>
  <hr class="row align-items-start col-12">

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\querys.php');
    include(app_path().'\functions\funciones.php');
    include(app_path().'\functions\reportes.php');

    $ArtJson = "";
    
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
      /*CASO 5: CARGA AL HABER COMPLETADO LA Compromiso
                Se pasa a la solicitud de fechas*/
      if (isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");

      if($_GET['fecha_vencimiento'] == ''){
        $fecha_documento = new DateTime($_GET['fecha_documento']);
        $fecha_recepcion = new DateTime($_GET['fecha_recepcion']);
        $fecha_tope = new DateTime($_GET['fecha_tope']);

        $diferencia1 = $fecha_recepcion->diff($fecha_tope);
        $diferencia1_numero = (int)$diferencia1->format('%R%a');

        $diferencia4 = $fecha_documento->diff($fecha_recepcion);
        $diferencia4_numero = (int)$diferencia4->format('%R%a');

        if(($diferencia1_numero > 0) && ($diferencia4_numero >= 0)) {
        ?>

          <form action="{{url('/cartaCompromiso')}}" method="POST" id="redireccionado">
            @csrf
            <input id="proveedor" name="proveedor" type="hidden" value="<?php print_r($_GET['proveedor']); ?>">
            <input id="articulo" name="articulo" type="hidden" value="<?php print_r($_GET['articulo']); ?>">
            <input id="lote" name="lote" type="hidden" value="<?php print_r($_GET['lote']); ?>">
            <input id="fecha_documento" name="fecha_documento" type="hidden" value="<?php print_r($_GET['fecha_documento']); ?>">
            <input id="fecha_recepcion" name="fecha_recepcion" type="hidden" value="<?php print_r($_GET['fecha_recepcion']); ?>">
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="0000-00-00">
            <input id="fecha_tope" name="fecha_tope" type="hidden" value="<?php print_r($_GET['fecha_tope']); ?>">
            <input id="causa" name="causa" type="hidden" value="<?php print_r(trim($_GET['causa'])); ?>">
            <input id="nota" name="nota" type="hidden" value="<?php print_r(trim($_GET['nota'])); ?>">
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r(trim($_GET['SEDE'])); ?>">
          </form>

          <script>
            document.getElementById('redireccionado').submit();
          </script>
          <?php
        }
        else {
          if($diferencia1_numero <= 0) {
            ?>
              <script>
                $('#errorModalCenter1').modal('show');
              </script>
            <?php
          }

          if($diferencia4_numero < 0) {
            ?>
              <script>
                $('#errorModalCenter4').modal('show');
              </script>
            <?php
          }

          CartaDeCompromiso($_GET['SEDE'],$_GET['IdProv'],$_GET['proveedor'],$_GET['IdFact'],$_GET['IdArt']);
        }
      }
      else {
        $fecha_documento = new DateTime($_GET['fecha_documento']);
        $fecha_recepcion = new DateTime($_GET['fecha_recepcion']);
        $fecha_vencimiento = new DateTime($_GET['fecha_vencimiento']);
        $fecha_tope = new DateTime($_GET['fecha_tope']);

        $diferencia1 = $fecha_recepcion->diff($fecha_tope);
        $diferencia1_numero = (int)$diferencia1->format('%R%a');

        $diferencia2 = $fecha_vencimiento->diff($fecha_tope);
        $diferencia2_numero = (int)$diferencia2->format('%R%a');

        $diferencia3 = $fecha_recepcion->diff($fecha_vencimiento);
        $diferencia3_numero = (int)$diferencia3->format('%R%a');

        $diferencia4 = $fecha_documento->diff($fecha_recepcion);
        $diferencia4_numero = (int)$diferencia4->format('%R%a');

        if(($diferencia1_numero > 0) 
          && ($diferencia2_numero <= 0) 
          && ($diferencia3_numero > 0) 
          && ($diferencia4_numero >= 0)) {
          ?>
          <form action="{{url('/cartaCompromiso')}}" method="POST" id="redireccionado">
            @csrf
            <input id="proveedor" name="proveedor" type="hidden" value="<?php print_r($_GET['proveedor']); ?>">
            <input id="articulo" name="articulo" type="hidden" value="<?php print_r($_GET['articulo']); ?>">
            <input id="lote" name="lote" type="hidden" value="<?php print_r($_GET['lote']); ?>">
            <input id="fecha_documento" name="fecha_documento" type="hidden" value="<?php print_r($_GET['fecha_documento']); ?>">
            <input id="fecha_recepcion" name="fecha_recepcion" type="hidden" value="<?php print_r($_GET['fecha_recepcion']); ?>">
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="<?php print_r($_GET['fecha_vencimiento']); ?>">
            <input id="fecha_tope" name="fecha_tope" type="hidden" value="<?php print_r($_GET['fecha_tope']); ?>">
            <input id="causa" name="causa" type="hidden" value="<?php print_r(trim($_GET['causa'])); ?>">
            <input id="nota" name="nota" type="hidden" value="<?php print_r(trim($_GET['nota'])); ?>">
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r(trim($_GET['SEDE'])); ?>">
          </form>

          <script>
            document.getElementById('redireccionado').submit();
          </script>
          <?php
        }
        else {
          if($diferencia1_numero <= 0) {
            ?>
              <script>
                $('#errorModalCenter1').modal('show');
              </script>
            <?php
          }

          if($diferencia2_numero > 0) {
            ?>
              <script>
                $('#errorModalCenter2').modal('show');
              </script>
            <?php
          }

          if($diferencia3_numero <= 0) {
            ?>
              <script>
                $('#errorModalCenter3').modal('show');
              </script>
            <?php
          }

          if($diferencia4_numero < 0) {
            ?>
              <script>
                $('#errorModalCenter4').modal('show');
              </script>
            <?php
          }

          CartaDeCompromiso($_GET['SEDE'],$_GET['IdProv'],$_GET['proveedor'],$_GET['IdFact'],$_GET['IdArt']);
        }
      }

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
  </script>
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
    </script>

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