@extends('layouts.model')

@section('title')
  Reporte
@endsection


@section('scriptsFoot')
    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection


@section('scriptsHead')
  <style>
    * {box-sizing:border-box;}

    [data-toggle=tooltip] {
        cursor: pointer;
    }


    .autocomplete {position:relative; display:inline-block;}

    input {
      border:1px solid transparent;
      background-color:#f1f1f1;
      border-radius:5px;
      padding:10px;
      font-size:16px;
    }

    input[type=text] {background-color:#f1f1f1; width:100%;}

    .autocomplete-items {
      position:absolute;
      border:1px solid #d4d4d4;
      border-bottom:none;
      border-top:none;
      z-index:99;
      top:100%;
      left:0;
      right:0;
    }

    .autocomplete-items div {
      padding:10px;
      cursor:pointer;
      background-color:#fff;
      border-bottom:1px solid #d4d4d4;
    }

    .autocomplete-items div:hover {background-color:#e9e9e9;}
    .autocomplete-active {background-color:DodgerBlue !important; color:#fff;}
  </style>


  <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['codigo_barra', 'descripcion', 'existencia', 'cobeca', 'nena', 'oeste', 'drolanca', 'drocerca'];

        function mostrar_todas(that) {
            if (that.checked) {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).show();
                    $('[name='+campos[i]+']').prop('checked', true);
                }
            } else {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).hide();
                    $('[name='+campos[i]+']').prop('checked', false);
                }
            }
        }
  </script>
@endsection


@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
      Catalogo de droguerias
    </h1>
  <hr class="row align-items-start col-12">

  <?php
    if(isset($_GET['SEDE'])) {
      echo '
        <h1 class="h5 text-success"  align="left">
          <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).
        '</h1>
      ';
    }
    echo '<hr class="row align-items-start col-12">';

    R50_Catalogo_Droguerias($articulos);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Catalogo de droguerias');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  ?>
@endsection

<?php
  /*
    TITULO: R50_Catalogo_Droguerias
    FUNCION: Arma una lista de las distintas droguerías
    RETORNO: No aplica
  */
  function R50_Catalogo_Droguerias($articulos) {
    echo '
        <div class="modal fade" id="ver_campos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mostrar u ocultar columnas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'codigo_barra\')" name="codigo_barra" checked>
                    Código de barra
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion\')" name="descripcion" checked>
                    Descripción
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'cobeca\')" name="cobeca" checked>
                    COBECA
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'nena\')" name="nena" checked>
                    Nena
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'drolanca\')" name="drolanca" checked>
                    Drolanca
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'drocerca\')" name="drocerca" checked>
                    Drocerca
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_todas(this)" name="Marcar todas" checked>
                    Marcar todas
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>';

    echo '
      <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
      </div>
      <br/>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="text-center" colspan="4"></th>
            <th scope="col" class="text-center" colspan="5">Existencia</th>
            <th scope="col" class="text-center" colspan="5">Precio</th>
          </tr>
        </thead>
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky codigo_barra">Código barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripción</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="cobeca CP-sticky">Cobeca</th>
            <th scope="col" class="nena CP-sticky">Nena</th>
            <th scope="col" class="oeste CP-sticky">Oeste</td>
            <th scope="col" class="drolanca CP-sticky">Drolanca</td>
            <th scope="col" class="drocerca CP-sticky">Drocerca</td>
            <th scope="col" class="cobeca CP-sticky">Cobeca</th>
            <th scope="col" class="nena CP-sticky">Nena</th>
            <th scope="col" class="oeste CP-sticky">Oeste</td>
            <th scope="col" class="drolanca CP-sticky">Drolanca</td>
            <th scope="col" class="drocerca CP-sticky">Drocerca</td>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    foreach ($articulos as $articulo) {
        $existencia = isset($articulo['existencia']) ? $articulo['existencia'] : '-';

        $existencia_cobeca = isset($articulo['existencia_cobeca']) ? $articulo['existencia_cobeca'] : '-';
        $existencia_nena = isset($articulo['existencia_nena']) ? $articulo['existencia_nena'] : '-';
        $existencia_oeste = isset($articulo['existencia_oeste']) ? $articulo['existencia_oeste'] : '-';
        $existencia_drolanca = isset($articulo['existencia_drolanca']) ? $articulo['existencia_drolanca'] : '-';
        $existencia_drocerca = isset($articulo['existencia_drocerca']) ? $articulo['existencia_drocerca'] : '-';

        $precio_cobeca = isset($articulo['precio_cobeca']) ? $articulo['precio_cobeca'] : '-';
        $precio_nena = isset($articulo['precio_nena']) ? $articulo['precio_nena'] : '-';
        $precio_oeste = isset($articulo['precio_oeste']) ? $articulo['precio_oeste'] : '-';
        $precio_drolanca = isset($articulo['precio_drolanca']) ? $articulo['precio_drolanca'] : '-';
        $precio_drocerca = isset($articulo['precio_drocerca']) ? $articulo['precio_drocerca'] : '-';


        echo '<tr>';

        echo '<td class="text-center">'.$contador.'</td>';

        echo '<td class="text-center">'.$articulo['codigo_barra'].'</td>';
        echo '<td class="text-center">'.$articulo['descripcion'].'</td>';
        echo '<td class="text-center">'.$existencia.'</td>';

        echo '<td class="text-center">'.$existencia_cobeca.'</td>';
        echo '<td class="text-center">'.$existencia_nena.'</td>';
        echo '<td class="text-center">'.$existencia_oeste.'</td>';
        echo '<td class="text-center">'.$existencia_drolanca.'</td>';
        echo '<td class="text-center">'.$existencia_drocerca.'</td>';

        echo '<td class="text-center">'.$precio_cobeca.'</td>';
        echo '<td class="text-center">'.$precio_nena.'</td>';
        echo '<td class="text-center">'.$precio_oeste.'</td>';
        echo '<td class="text-center">'.$precio_drolanca.'</td>';
        echo '<td class="text-center">'.$precio_drocerca.'</td>';

        echo '</tr>';

        $contador++;
    }

    echo '
        </tbody>
      </table>
    ';
  }
?>
