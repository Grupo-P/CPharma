@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Productos en Caida
  </h1>
  <hr class="row align-items-start col-12">

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');
  include(app_path().'\functions\reportes.php');

  $InicioCarga = new DateTime("now");
  
  /*********BORRAR DESPUES**********/
  $_GET['SEDE'] = 'FTN';
  /*********BORRAR DESPUES**********/

  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';
  
  ReporteProductosEnCaida($_GET['SEDE']);
  //GuardarAuditoria('CONSULTAR','REPORTE','Activacion de proveedores');

  $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'<br/>Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
?>
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">  
    </script>
    <script src="{{ asset('assets/js/filter.js') }}"> 
    </script>
@endsection