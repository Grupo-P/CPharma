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
    if (!request()->pasoUno) {

        echo '<form>';
        echo '<table class="table table-bordered">';

        echo '<input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'">';
        echo '<input type="hidden" name="_token" value="'.$_GET['_token'].'">';
        echo '<input type="hidden" name="pasoUno" value="1">';

        echo '<tr>';

        echo '<td class="text-center">';
        echo '<label for="descuentoCobeca">Descuento Cobeca</label>';
        echo '<input min="0.01" step="0.01" max="0.99" type="number" name="descuentoCobeca" class="form-control">';
        echo '</td>';

        echo '<td class="text-center">';
        echo '<label for="descuentoNena">Descuento Nena</label>';
        echo '<input min="0.01" step="0.01" max="0.99" type="number" name="descuentoNena" class="form-control">';
        echo '</td>';

        echo '<td class="text-center">';
        echo '<label for="descuentoDrocerca">Descuento Drocerca</label>';
        echo '<input min="0.01" step="0.01" max="0.99" type="number" name="descuentoDrocerca" class="form-control">';
        echo '</td>';

        echo '<td class="text-center">';
        echo '<label for="descuentoDrolanca">Descuento Drolanca</label>';
        echo '<input min="0.01" step="0.01" max="0.99" type="number" name="descuentoDrolanca" class="form-control">';
        echo '</td>';

        echo '<td class="text-center">';
        echo '<label for="descuentoOeste">Descuento Oeste</label>';
        echo '<input min="0.01" step="0.01" max="0.99" type="number" name="descuentoOeste" class="form-control">';
        echo '</td>';

        echo '<td class="text-center">';
        echo '<label for="descuentoOeste">Descuento Oeste</label>';
        echo '<input min="0.01" step="0.01" max="0.99" type="number" name="descuentoOeste" class="form-control">';
        echo '</td>';

        echo '</tr>';

        echo '<tr>';

        echo '<td class="text-center" colspan="5">El descuento para cada droguería debe expresar en un número decimal mayor a 0.00 y menor a 1.00. Ejemplo: 5% igual a 0.95</td>';

        echo '<td class="text-center">';
        echo '<button class="btn btn-outline-success">Ir</button>';
        echo '</td>';

        echo '</tr>';

        echo '</table>';
        echo '</form>';

        return;
    }

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
        <input class="form-control my-0 py-1" type="text" placeholder="Esta casilla filtra dentro de esta pagina específicamente..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
      </div>
      <br/>
    ';

    $current = $_GET['page'] ?? 1;

    $buscar = $_GET['buscar'] ?? '';

    echo '
      <form>
          <input type="hidden" name="_token" value="'.$_GET['_token'].'">
          <input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'">
          <input type="hidden" name="page" value="'.$current.'">

          <div class="input-group md-form form-sm form-1 pl-0">
            <input class="form-control my-0 py-1" type="text" placeholder="Esta casilla filtra entre todo el catalogo por código de barra o descripción..." value="'.$buscar.'" name="buscar" aria-label="Search">
            <div class="input-group-append">
              <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search text-white" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          </form>
      <br/>
    ';

    if ($buscar) {
        echo '<div class="text-center"><a href="/reporte50?_token='.$_GET['_token'].'&SEDE='.$_GET['SEDE'].'"><i class="fa fa-reply"></i> Volver a mostrar todo<a></div>';
    }

    $textoDescuentos = [];

    if ($_GET['descuentoDrolanca'] != '') {
        $textoDescuentos[] = 'Drolanca: '.$_GET['descuentoDrolanca'];
    }

    if ($_GET['descuentoOeste'] != '') {
        $textoDescuentos[] = 'Oeste: '.$_GET['descuentoOeste'];
    }

    if ($_GET['descuentoNena'] != '') {
        $textoDescuentos[] = 'Nena: '.$_GET['descuentoNena'];
    }

    if ($_GET['descuentoDrocerca'] != '') {
        $textoDescuentos[] = 'Drocerca: '.$_GET['descuentoDrocerca'];
    }

    if ($_GET['descuentoCobeca'] != '') {
        $textoDescuentos[] = 'Cobeca: '.$_GET['descuentoCobeca'];
    }

    $textoDescuentos = implode(', ', $textoDescuentos);

    if ($textoDescuentos == '') {
        $textoDescuentos = 'No hay ninguna droguería configurada';
    }

    echo '
        <table class="table table-striped table-bordered col-12">
            <tr>
                <td>
                    <ul>
                        <li>La casilla de existencia presentará un guion cuando no lo tenemos codificado y 0 cuando lo tenemos codificados pero no hay existencia.</li>
                        <li>La existencia de Drocerca incluye la sede de Mérida mas la sede de Caracas.</li>
                        <li>El color amarillo indica el mejor precio entre las droguerías.</li>
                        <li>El color verde indica la mayor existencia entre las droguerías.</li>
                        <li>Se configuraron los siguientes descuentos en factura por droguería: '.$textoDescuentos.'.</li>
                    </ul>
                </td>
            </tr>
        </table>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky codigo_barra">Código barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripción</th>
            <th scope="col" class="componente CP-sticky">Componente</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="cobeca CP-sticky">Existencia Cobeca</th>
            <th scope="col" class="nena CP-sticky">Existencia Nena</th>
            <th scope="col" class="oeste CP-sticky">Existencia Oeste</td>
            <th scope="col" class="drolanca CP-sticky">Existencia Drolanca</td>
            <th scope="col" class="drocerca CP-sticky">Existencia Drocerca</td>
            <th scope="col" class="cobeca CP-sticky">Precio Cobeca</th>
            <th scope="col" class="nena CP-sticky">Precio Nena</th>
            <th scope="col" class="oeste CP-sticky">Precio Oeste</td>
            <th scope="col" class="drolanca CP-sticky">Precio Drolanca</td>
            <th scope="col" class="drocerca CP-sticky">Precio Drocerca</td>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    foreach ($articulos as $articulo) {
        $descripcion = FG_Limpiar_Texto($articulo['descripcion']);
        $existencia = isset($articulo['existencia']) ? $articulo['existencia'] : '-';
        $componente = isset($articulo['componente']) ? FG_Limpiar_Texto($articulo['componente']) : '-';

        $existencia_cobeca = isset($articulo['existencia_cobeca']) ? $articulo['existencia_cobeca'] : '-';
        $existencia_nena = isset($articulo['existencia_nena']) ? $articulo['existencia_nena'] : '-';
        $existencia_oeste = isset($articulo['existencia_oeste']) ? $articulo['existencia_oeste'] : '-';
        $existencia_drolanca = isset($articulo['existencia_drolanca']) ? $articulo['existencia_drolanca'] : '-';
        $existencia_drocerca = isset($articulo['existencia_drocerca']) ? $articulo['existencia_drocerca'] : '-';

        $precio_cobeca = isset($articulo['precio_cobeca']) ? number_format($articulo['precio_cobeca'], 2) : '-';
        $precio_nena = isset($articulo['precio_nena']) ? number_format($articulo['precio_nena'], 2) : '-';
        $precio_oeste = isset($articulo['precio_oeste']) ? number_format($articulo['precio_oeste'], 2) : '-';
        $precio_drolanca = isset($articulo['precio_drolanca']) ? number_format($articulo['precio_drolanca'], 2) : '-';
        $precio_drocerca = isset($articulo['precio_drocerca']) ? number_format($articulo['precio_drocerca'], 2) : '-';

        if (isset($articulo['id_articulo'])) {
            $link = '/reporte2?Descrip='.$descripcion.'&Id=' . $articulo['id_articulo'] . '&SEDE=' . FG_Mi_Ubicacion();
            $link = '<td class="text-center CP-barrido"><a target="_blank" style="text-decoration: none; color: black" href="'.$link.'">'.$descripcion.'</a></td>';
        } else {
            $link = '<td class="text-center">'.$descripcion.'</td>';
        }

        $resaltado_precio_cobeca = ($articulo['menor_precio'] == 'cobeca') ? 'bg-warning' : '';
        $resaltado_precio_nena = ($articulo['menor_precio'] == 'nena') ? 'bg-warning' : '';
        $resaltado_precio_oeste = ($articulo['menor_precio'] == 'oeste') ? 'bg-warning' : '';
        $resaltado_precio_drolanca = ($articulo['menor_precio'] == 'drolanca') ? 'bg-warning' : '';
        $resaltado_precio_drocerca = ($articulo['menor_precio'] == 'drocerca') ? 'bg-warning' : '';

        $resaltado_existencia_cobeca = ($articulo['mayor_existencia'] == 'cobeca') ? 'bg-success' : '';
        $resaltado_existencia_nena = ($articulo['mayor_existencia'] == 'nena') ? 'bg-success' : '';
        $resaltado_existencia_oeste = ($articulo['mayor_existencia'] == 'oeste') ? 'bg-success' : '';
        $resaltado_existencia_drolanca = ($articulo['mayor_existencia'] == 'drolanca') ? 'bg-success' : '';
        $resaltado_existencia_drocerca = ($articulo['mayor_existencia'] == 'drocerca') ? 'bg-success' : '';


        echo '<tr>';

        echo '<td class="text-center">'.$contador.'</td>';

        echo '<td class="text-center">'.$articulo['codigo_barra'].'</td>';
        echo $link;
        echo '<td class="text-center">'.$componente.'</td>';
        echo '<td class="text-center">'.$existencia.'</td>';

        echo '<td class="'.$resaltado_existencia_cobeca.' text-center">'.$existencia_cobeca.'</td>';
        echo '<td class="'.$resaltado_existencia_nena.' text-center">'.$existencia_nena.'</td>';
        echo '<td class="'.$resaltado_existencia_oeste.' text-center">'.$existencia_oeste.'</td>';
        echo '<td class="'.$resaltado_existencia_drolanca.' text-center">'.$existencia_drolanca.'</td>';
        echo '<td class="'.$resaltado_existencia_drocerca.' text-center">'.$existencia_drocerca.'</td>';

        echo '<td class="'.$resaltado_precio_cobeca.' text-center">'.$precio_cobeca.'</td>';
        echo '<td class="'.$resaltado_precio_nena.' text-center">'.$precio_nena.'</td>';
        echo '<td class="'.$resaltado_precio_oeste.' text-center">'.$precio_oeste.'</td>';
        echo '<td class="'.$resaltado_precio_drolanca.' text-center">'.$precio_drolanca.'</td>';
        echo '<td class="'.$resaltado_precio_drocerca.' text-center">'.$precio_drocerca.'</td>';

        echo '</tr>';

        $contador++;
    }

    echo '
        </tbody>
      </table>
    ';

    echo $articulos->links();
  }
?>
