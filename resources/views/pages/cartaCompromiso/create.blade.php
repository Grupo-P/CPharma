@extends('layouts.model')

@section('title')
    Carta de compromiso
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>
    Agregar carta de compromiso
  </h1>

  <hr class="row align-items-start col-12">
  <form action="/cartaCompromiso/" method="POST" style="display:inline;">  
    @csrf                       
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>
  <hr class="row align-items-start col-12">
  
  {{-- OJO REEMPLAZAR "FTN" POR LA CAPTURA DE LA SEDE DE LA FUNCION DE OPTETOS --}}

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\querys.php');
    include(app_path().'\functions\funciones.php');
    include(app_path().'\functions\reportes.php');

    $ArtJson = "";
    
    if(isset($_GET['Id'])) {
      /*CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR
                Se pasa a la carga de las facturas del proveedor*/
      $InicioCarga = new DateTime("now");
      
      ReporteProveedorFactura("FTN",$_GET['Id'],$_GET['Nombre']);
      
      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['TroquelN'])) {
      /*CASO 5: ACTUALIZAR EL TROQUEL
                Se pasa a mostrar el resultado de actualizar el troquel*/
      $InicioCarga = new DateTime("now");

      ReporteTroquel("FTN",$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact'],$_GET['IdArt'],$_GET['TroquelN']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['IdArt'])) {
      /*CASO 4: CARGA AL HABER SELECCIONADO UNA ARTICULO
                Se pasa a la actualizacion del troquel*/
      $InicioCarga = new DateTime("now");

      ReporteArticuloTroquel("FTN",$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact'],$_GET['IdArt']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['IdFact'])){
    /*CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA 
              Se pasa a la seleccion del articulo*/
      $InicioCarga = new DateTime("now");

      ReporteFacturaArticulo("FTN",$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      /*CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
                Se pasa a la seleccion del proveedor*/
      $InicioCarga = new DateTime("now");

      $sql = QListaProveedores();
      $ArtJson = armarJson($sql,"FTN");

      echo '
      <form autocomplete="off" action="">
        <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del proveedor " onkeyup="conteo()" required>
          <input id="myId" name="Id" type="hidden">
          <td>
          <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r("FTN");
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