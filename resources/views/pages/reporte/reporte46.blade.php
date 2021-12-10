@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {box-sizing:border-box;}

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
@endsection


@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
      Compras por archivo
    </h1>
  <hr class="row align-items-start col-12">

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    if(isset($_GET['SEDE'])) {
      echo '
        <h1 class="h5 text-success"  align="left">
          <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).
        '</h1>
      ';
    }
    echo '<hr class="row align-items-start col-12">';

    if (request()->hasFile('archivo') && (request()->file('archivo')->extension() != 'xlsx' && request()->file('archivo')->extension() != 'xls')) {
        echo '
            <form autocomplete="off" enctype="multipart/form-data" action="" method="POST" target="_blank">
              <div class="row">
                <div class="col"></div>

                <div class="col-5">
                  <div class="alert alert-danger text-center">Solo se admiten archivos Excel.</div>

                  <div class="alert alert-info">
                    <ul>
                        <li>La primera línea en archivo se reserva para el encabezado.</li>
                        <li>Los calumnas del Excel deben estar en el siguiente orden:<br>Código de barra, Descripción, Precio, Existencia.</li>
                    </ul>
                  </div>
                </div>

                <div class="col">
                </div>
              </div>

              <div class="row">
                <div class="col"></div>

                <div class="col">
                  <input style="background-color: white" required accept=".xls,.xlsx" type="file" name="archivo">
                </div>

                <div class="col form-inline">
                  <label for="opcion">Moneda:</label>

                  <select style="width: 70%" class="ml-3 form-control" id="opcion" name="opcion" required>
                    <option value""></option>
                    <option value"Dólares">Dólares</option>
                    <option value"Bolívares">Bolívares</option>
                  </select>
                </div>

                <div class="col">
                  <input id="SEDE" name="SEDE" type="hidden" value="';
                    print_r($_GET['SEDE']);
                  echo'">

                  <input type="submit" value="Buscar" class="mt-1 btn btn-outline-success">
                </div>
              </div>
            </form>
          ';
    }

    elseif(isset($_POST['opcion'])) {
      $InicioCarga = new DateTime("now");
      R46_Compras_Archivo($_POST['SEDE'],$_POST['opcion']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Compras por archivo');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }

    else {
      echo '
        <form autocomplete="off" enctype="multipart/form-data" action="" method="POST" target="_blank">
          <div class="row">
            <div class="col"></div>

            <div class="col-5">
              <div class="alert alert-info">
                <ul>
                    <li>La primera línea en archivo se reserva para el encabezado.</li>
                    <li>Los calumnas del Excel deben estar en el siguiente orden:<br>Código de barra, Descripción, Precio, Existencia.</li>
                </ul>
              </div>
            </div>

            <div class="col">
            </div>
          </div>

          <div class="row">
            <div class="col"></div>

            <div class="col">
              <input style="background-color: white" required accept=".xls,.xlsx" type="file" name="archivo">
            </div>

            <div class="col form-inline">
              <label for="opcion">Moneda:</label>

              <select style="width: 70%" class="ml-3 form-control" id="opcion" name="opcion" required>
                <option value""></option>
                <option value"Dólares">Dólares</option>
                <option value"Bolívares">Bolívares</option>
              </select>
            </div>

            <div class="col">
              <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($_GET['SEDE']);
              echo'">

              <input type="submit" value="Buscar" class="mt-1 btn btn-outline-success">
            </div>
          </div>
        </form>
      ';
    }
  ?>
@endsection

<?php
  /*
    TITULO: R46_Compras_Archivo
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
                [$opcion] Lista de precios de articulos dolarizados o no
    FUNCION: Arma una lista de productos con su precio
    RETORNO: No aplica
  */
  function R46_Compras_Archivo($SedeConnection,$opcion) {

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $Hoy = new DateTime('now');
    $Hoy = $Hoy->format('Y-m-d');

    $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['archivo']['tmp_name']);
    $data        = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $i = 0;

    $procesados = '';
    $noProcesados = '';

    $contadorProcesados = 1;
    $contadorNoProcesados = 1;

    foreach ($data as $item) {
      if ($i != 0) {
        if ($item['A'] == '' && $item['B'] == '' && $item['C'] == '' && $item['D'] == '') {
            continue;
        }


        if ($item['A'] != '' && $item['C'] != '') {
          $sql = SQG_Detalle_Articulo_CodigoBarra($item['A']);
          $result = sqlsrv_query($conn, $sql);
          $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

          if ($row) {

            $precio = FG_Calculo_Precio_Alfa($row['Existencia'], $row['ExistenciaAlmacen1'], $row['ExistenciaAlmacen2'], $row['Troquelado'], $row['UtilidadArticulo'], $row['UtilidadCategoria'], $row['TroquelAlmacen1'], $row['PrecioCompraBrutoAlmacen1'], $row['TroquelAlmacen2'], $row['PrecioCompraBrutoAlmacen2'], $row['PrecioCompraBruto'], $row['Impuesto'], 'CON_EXISTENCIA');

            $tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');
            $tasa = ($tasa) ? $tasa : 0;

            $precioBs = ($precio) ? number_format($precio, 2, ',', '.') : '';
            $precioDs = ($precio and $tasa) ? number_format($precio * $tasa, 2, ',', '.') : '';
            $ultimaVenta = ($row['UltimaVenta']) ? $row['UltimaVenta']->format('d/m/Y') : '';

            $procesados .= '
            <tr>
              <td class="text-center">'.$contadorProcesados.'</td>
              <td class="text-center">'.$item['A'].'</td>
              <td class="text-center">'.$item['B'].'</td>
              <td class="text-center CP-barrido"><a href="/reporte2?Id='.$row['IdArticulo'].'&SEDE='.$_GET['SEDE'].'" style="text-decoration: none; color: black;" target="_blank">'.$row['Descripcion'].'</a></td>
              <td class="text-center">'.intval($item['D']).'</td>
              <td class="text-center">'.intval($row['Existencia']).'</td>
              <td class="text-center">'.number_format($item['C'], 2, ',', '.').'</td>
              <td class="text-center">'.$precioBs.'</td>
              <td class="text-center">'.$precioDs.'</td>
              <td class="text-center">'.$ultimaVenta.'</td>
              <td class="text-center CP-barrido"><a href="/reporte7?Nombre='.$row['UltimoProveedorNombre'].'&Id='.$row['UltimoProveedorID'].'&SEDE='.$_GET['SEDE'].'" style="text-decoration: none; color: black;" target="_blank">'.$row['UltimoProveedorNombre'].'</a></td>
            </tr>
            ';

            $contadorProcesados++;

          } else {
              $noProcesados .= '
                <tr>
                  <td class="text-center">'.$contadorNoProcesados.'</td>
                  <td class="text-center">'.$item['A'].'</td>
                  <td class="text-center">'.$item['B'].'</td>
                  <td class="text-center">'.number_format($item['C'], 2, ',', '.').'</td>
                  <td class="text-center">'.$item['D'].'</td>
                  <td class="text-center">Artículo no codificado</td>
                </tr>
              ';

              $contadorNoProcesados++;
          }
        }

        else {
          $noProcesados .= '
            <tr>
              <td class="text-center">'.$contadorNoProcesados.'</td>
              <td class="text-center">'.$item['A'].'</td>
              <td class="text-center">'.$item['B'].'</td>
              <td class="text-center">'.number_format($item['C'], 2, ',', '.').'</td>
              <td class="text-center">'.$item['D'].'</td>
              <td class="text-center">Artículo incompleto</td>
            </tr>
          ';

          $contadorNoProcesados++;
        }
      }

      $i = $i + 1;
    }

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
      <table class="table table-striped table-bordered col-12 mt-5">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">No procesados</th>
          </tr>
        </thead>
      </table>

      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="CP-sticky">Codigo Excel</th>
            <th scope="col" class="CP-sticky">Descripcion Excel</th>
            <th scope="col" class="CP-sticky">Descripcion Farmacia</th>
            <th scope="col" class="CP-sticky">Existencia Excel</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Costo Excel</th>
            <th scope="col" class="CP-sticky">Precio '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio '.SigDolar.'</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
            <th scope="col" class="CP-sticky">Ultimo proveedor</th>
          </tr>
        </thead>

        <tbody>
          '.$procesados.'
        </tbody>
      </table>
    ';



    echo '
      <table class="table table-striped table-bordered col-12 mt-5">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">No procesados</th>
          </tr>
        </thead>
      </table>

      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="CP-sticky">Codigo Excel</th>
            <th scope="col" class="CP-sticky">Descripcion Excel</th>
            <th scope="col" class="CP-sticky">Costo Excel</td>
            <th scope="col" class="CP-sticky">Existencia Excel</td>
            <th scope="col" class="CP-sticky">Causa</td>
          </tr>
        </thead>
        <tbody>
            '.$noProcesados.'
        </tbody>
      </table>
    ';
  }
?>
