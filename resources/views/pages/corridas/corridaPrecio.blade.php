@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
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
    background-color: DodgerBlue !important;
    color: #ffffff;
  }
  </style>
@endsection

@section('content')
<h1 class="h5 text-info">
        <i class="fas fa-funnel-dollar"></i>
        Corrida de Precios
    </h1>
    <hr class="row align-items-start col-12">

<?php
  use compras\Configuracion;
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $_GET['SEDE'] = FG_Mi_Ubicacion();

	if( (Auth::user()->departamento == 'ADMINISTRACION') || (Auth::user()->departamento == 'COSTOS')
     || ((Auth::user()->departamento == 'GERENCIA') || (Auth::user()->departamento == 'TECNOLOGIA')
           &&
           ((Auth::user()->email == 'giordany@farmacia72.com')
           || (Auth::user()->email == 'giancarlos@farmacia72.com'))
        ))  {

    $configuracion = Configuracion::where('variable','DolarCalculo')->get();

    if (isset($_GET['tipoCorrida'])) {
      
      $rango_dias = (FG_Rango_Dias(date('Y-m-d'),$configuracion[0]->updated_at->format('Y-m-d'))); 

      if($rango_dias>=3){

        mostrar_formulario($configuracion);

        echo '
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-info text-danger"></i> Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Debe actualizar la tasa de calculo, la ultima actualizacion fue hace mas de tres (3) dias.</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
        ';    

      }else{
        $InicioCarga = new DateTime("now");

        FG_Corrida_Precio($_GET['tipoCorrida'],$configuracion[0]->valor,Auth::user()->name);
        FG_Guardar_Auditoria('EJECUTAR','CORRIDA DE PRECIOS','CPHARMA');

        $connCPharma = FG_Conectar_CPharma();
        $sql = "SELECT * FROM auditoria_corridas ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result); 

        $tipocorrida = $row['tipocorrida'];
        $tipocorrida = ($tipocorrida=='bajada')?'Subida/Bajada':$tipocorrida;
        
        echo'
        <hr class="row align-items-start col-12">
        <table style="width:100%;" class="CP-stickyBar">
            <tr>
                <td style="width:90%;">
                  <div class="input-group md-form form-sm form-1 pl-0">
                <div class="input-group-prepend">
                  <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                      aria-hidden="true"></i></span>
                </div>
                <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
              </div>
                </td>
            </tr>
        </table>
        <br/>
        ';

        echo '
        <table class="table table-striped table-borderless col-12 sortable" id="myTable">
          <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Tipo de corrida</th>
                <th scope="col" class="CP-sticky">Operador</th>
                <th scope="col" class="CP-sticky">Fecha</th>
                <th scope="col" class="CP-sticky">Hora</th>
                <th scope="col" class="CP-sticky">Nota</th>
                <th scope="col" class="CP-sticky">Articulos evaluados</th>
                <th scope="col" class="CP-sticky">Articulos procesados exito</th>
                <th scope="col" class="CP-sticky">Articulos con fallas</th>
                <th scope="col" class="CP-sticky">Articulos que cambiaron</th>
                <th scope="col" class="CP-sticky">Articulos sin cambios</th>
            </tr>
          </thead>
          <tbody>
          <tr>
          ';
            echo '<td align="center"><strong>'.$row['id'].'</strong></td>';
            echo '<td align="center">'.$tipocorrida.'</td>';
            echo '<td align="center">'.$row['operador'].'</td>';
            echo '<td align="center">'.$row['fecha'].'</td>';
            echo '<td align="center">'.$row['hora'].'</td>';
            echo '<td align="center">'.$row['observacion'].'</td>';
            echo '<td align="center">'.$row['evaluados'].'</td>';
            echo '<td align="center">'.$row['exitos'].'</td>';
            echo '<td align="center">'.$row['fallas'].'</td>';
            echo '<td align="center">'.$row['cambios'].'</td>';
            echo '<td align="center">'.$row['nocambio'].'</td>';            
          echo'
          </tr>
        </tbody>
      </table>
        ';

        $FinCarga = new DateTime("now");
        $IntervalCarga = $InicioCarga->diff($FinCarga);
        echo'<br>Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
      }
    }else{
      mostrar_formulario($configuracion);
    }    
	}else{
		echo '
      <h6 align="center" class="text-danger" style="margin-top:50px; margin-bottom:150px; font-size:2rem;">Usted NO tiene permisos para acceder a este modulo</h6>';
	}
?>

<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
  $('#exampleModalCenter').modal('show')
</script>

@endsection

<?php
  function mostrar_formulario($configuracion){
    echo '
    <form id="form" autocomplete="off" action="" style="margin-bottom:50px;">
      <table style="width:100%;">
        <tr>
          <td align="center">Tasa de calculo:</td>
          <td>
            <input id="tasaCalculo" type="text" name="tasaCalculo" disabled style="width:auto;" value="'.number_format($configuracion[0]->valor,2,"," ,"." ).'">
          </td>

          <td align="center">Fecha:</td>
          <td>
            <input id="fecha" name="fecha" value="'.$configuracion[0]->updated_at->format("d/m/Y").'" type="text" disabled style="width:auto;">                          
          </td>

          <td align="center">Tipo de Corrida:</td>
          <td>
            <input type="radio" id="subida" name="tipoCorrida" value="subida" checked>
            <label for="subida">Subida</label><br>
            <input type="radio" id="subida/bajada" name="tipoCorrida" value="bajada">
            <label for="subida/bajada">Subida/Bajada</label><br>
          </td>

          <td>
            <input id="SEDE" name="SEDE" type="hidden" value="';echo($_GET['SEDE']);echo'">
          <input type="submit" value="Ejecutar" class="btn btn-outline-success" style="width:80%;">
          </td>
        </tr>          
      </table>                    
    </form>
  ';
  }
?>
