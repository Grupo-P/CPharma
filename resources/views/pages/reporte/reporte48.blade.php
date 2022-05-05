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

        campos = ['codigo', 'codigo_barra', 'descripcion', 'precio_bs', 'precio_ds', 'gravado', 'utilidad', 'existencia', 'costo', 'ultimo_lote', 'ultima_compra', 'ultima_venta', 'ultimo_proveedor', 'drolanca', 'drooeste', 'dronena', 'drocerca', 'cobeca'];

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

        $(document).ready(function () {
            $('.bajadas').hide();

            $('.subidas-btn').click(function () {
                $('.bajadas').hide();
                $('.subidas').show();
            });

            $('.bajadas-btn').click(function () {
                $('.subidas').hide();
                $('.bajadas').show();
            });
          });
  </script>
@endsection


@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
      Cambio de precios
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

    if(isset($_GET['opcion'])) {
      $InicioCarga = new DateTime("now");
      R48_Cambio_Precios($_GET['SEDE'],$_GET['opcion']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Cambio de precios');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      echo '
        <form autocomplete="off" action="" target="_blank">
          <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <tr>
              <td colspan="2" align="center">
                <label for="opcion">Seleccione una opcion:</label>
              </td>

              <td colspan="2" align="center">
                <select class="form-control" id="opcion" name="opcion" required style="width:100%;">
                  <option value""></option>
                  <option value"Articulos dolarizados">Articulos dolarizados</option>
                  <option value"Articulos no dolarizados">Articulos no dolarizados</option>
                </select>
              </td>

              <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($_GET['SEDE']);
              echo'">

              <td colspan="2" align="center">
                <input type="submit" value="Buscar" class="btn btn-outline-success">
              </td>
            </tr>

            <tr>
              <td align="center">
                <label>Descuento facturación Drolanca</label>
                <input type="number" min="0.01" step="0.01" max="0.99" name="descuentoFacturacionDrolanca" class="form-control">
              </td>

              <td align="center">
                <label>Descuento facturación Drooeste</label>
                <input type="number" min="0.01" step="0.01" max="0.99" name="descuentoFacturacionDrooeste" class="form-control">
              </td>

              <td align="center">
                <label>Descuento facturación Dronena</label>
                <input type="number" min="0.01" step="0.01" max="0.99" name="descuentoFacturacionDronena" class="form-control">
              </td>

              <td align="center">
                <label>Descuento facturación Drocerca</label>
                <input type="number" min="0.01" step="0.01" max="0.99" name="descuentoFacturacionDrocerca" class="form-control">
              </td>

              <td align="center">
                <label>Descuento facturación Cobeca</label>
                <input type="number" min="0.01" step="0.01" max="0.99" name="descuentoFacturacionCobeca" class="form-control">
              </td>
            </tr>

            <tr>
              <td colspan="5" align="center">
                <label>El descuento para cada droguería debe expresar en un número decimal mayor a 0.00 y menor a 1.00. Ejemplo: 5% igual a 0.95</label>
              </td>
            </tr>
          </table>
        </form>
      ';
    }
  ?>
@endsection

<?php
  /*
    TITULO: R48_Cambio_Precios
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
                [$opcion] Lista cambio de precios de articulos dolarizados o no
    FUNCION: Arma una lista de productos con su precio en distintas droguerías
    RETORNO: No aplica
  */
  function R48_Cambio_Precios($SedeConnection,$opcion) {

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $Hoy = new DateTime('now');
    $Hoy = $Hoy->format('Y-m-d');

    // Drolanca

    try {
        $ftp_connect = ftp_connect('ftp.drolanca.com');
        ftp_login($ftp_connect, '17900', '17900');
        ftp_pasv($ftp_connect, true);

        $fopen = fopen('drolanca.csv', 'w+');

        ftp_fget($ftp_connect, $fopen, '/inventario/Inventario.txt', FTP_ASCII, 0);
        ftp_close($ftp_connect);

        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('drolanca.csv');
        $drolanca = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        fclose($fopen);

        $drolancaFechaActualizacion = date('d/m/Y h:i A', ftp_mdtm($fopen, '/inventario/Inventario.txt'));


    } catch (Exception $exception) {
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('drolanca.csv');
        $drolanca = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $drolancaFechaActualizacion = date('d/m/Y h:i A', filemtime('drolanca.csv'));
    }

    // Drooeste

    try {
        $ftp_connect = ftp_connect('03bb052.netsolhost.com');
        ftp_login($ftp_connect, 'clientes_electronico', 'Horizonte777');
        ftp_pasv($ftp_connect, true);

        $fopen = fopen('drooeste.csv', 'w+');

        ftp_fget($ftp_connect, $fopen, '/Inventario/I_BTIERR/BTIERR.txt', FTP_ASCII, 0);
        ftp_close($ftp_connect);

        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('drooeste.csv');
        $drooeste = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        fclose($fopen);

        $drooesteFechaActualizacion = date('d/m/Y h:i A', ftp_mdtm($fopen, '/Inventario/I_BTIERR/BTIERR.txt'));
    } catch (Exception $exception) {
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('drooeste.csv');
        $drooeste = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $drooesteFechaActualizacion = date('d/m/Y h:i A', filemtime('drooeste.csv'));
    }



    // Dronena

    try {
        $ftp_connect = ftp_connect('ftp.dronena.com');
        ftp_login($ftp_connect, '9431-foraneo', '5svjk431');

        $fopen = fopen('dronena.txt', 'w+');

        ftp_pasv($ftp_connect, true);
        ftp_fget($ftp_connect, $fopen, '/Clientes/9431/Inventario.txt', FTP_ASCII, 0);
        ftp_close($ftp_connect);

        fclose($fopen);

        $content = file_get_contents('dronena.txt');
        $lines = explode("\r\n", $content);

        $dronena = [];
        $i = 0;

        foreach ($lines as $field) {
          $dronena[$i]['CODBARRAS'] = trim(substr($field, 130, 17));
          $dronena[$i]['PRECIO'] = trim(substr($field, 49, 15));
          $dronena[$i]['DCTO_UFI'] = trim(substr($field, 97, 13));
          $dronena[$i]['DCTO_EMPQUE'] = trim(substr($field, 118, 12));
          $dronena[$i]['CANTIDAD'] = trim(substr($field, 64, 12));

          $i = $i+1;
        }

        $dronenaFechaActualizacion = date('d/m/Y h:i A', ftp_mdtm($fopen, '/Clientes/9431/Inventario.txt'));


    } catch (Exception $exception) {
        $content = file_get_contents('dronena.txt');
        $lines = explode("\r\n", $content);

        $dronena = [];
        $i = 0;

        foreach ($lines as $field) {
          $dronena[$i]['CODBARRAS'] = trim(substr($field, 130, 17));
          $dronena[$i]['PRECIO'] = trim(substr($field, 49, 15));
          $dronena[$i]['DCTO_UFI'] = trim(substr($field, 97, 13));
          $dronena[$i]['DCTO_EMPQUE'] = trim(substr($field, 118, 12));
          $dronena[$i]['CANTIDAD'] = trim(substr($field, 64, 12));

          $i = $i+1;
        }

        $dronenaFechaActualizacion = date('d/m/Y h:i A', filemtime('dronena.txt'));
    }

    // Drocerca

    try {
        $ftp_connect = ftp_connect('Drocerca.proteoerp.org');
        ftp_login($ftp_connect, 'C00660', 'TIKpjKjZfD');

        $fopen = fopen('drocerca.csv', 'w+');

        ftp_pasv($ftp_connect, true);
        ftp_fget($ftp_connect, $fopen, '/inventario.txt', FTP_ASCII, 0);
        ftp_close($ftp_connect);

        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('drocerca.csv');
        $drocerca = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        fclose($fopen);

        $drocercaFechaActualizacion = date('d/m/Y h:i A', ftp_mdtm($fopen, '/inventario.txt'));
    } catch (Exception $exception) {
        $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load('drocerca.csv');
        $drocerca = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $drocercaFechaActualizacion = date('d/m/Y h:i A', filemtime('drocerca.csv'));
    }



    // COBECA

    try {
        $client = new GuzzleHttp\Client();

        $request = $client->request('POST', 'http://www.cobeca.com:8080/pedidofl/api/Login', [
            'json' => [
                'Usuario' => 'F27336',
                'Clave' => 'FTN2016+'
            ]
        ]);

        $response = json_decode($request->getBody(), true);

        $token = $response['Token'];

        $request = $client->request('GET', 'http://www.cobeca.com:8080/pedidofl/api/Articulos', [
            'headers' => [
                'Autorizacion' => $token,
            ],
            'form_params' => [
                'cod_drogueria' => '7',
            ]
        ]);

        $cobeca = json_decode($request->getBody(), true);

        $cobecaFechaActualizacion = date('d/m/Y h:i A');

        $fopen = fopen('cobeca.json', 'w+');
        fwrite($fopen, $request->getBody());
        fclose($fopen);
    } catch (Exception $exception) {
        $cobeca = file_get_contents('cobeca.json');
        $cobeca = json_decode($cobeca, true);

        $cobecaFechaActualizacion = date('d/m/Y h:i A', filemtime('cobeca.json'));
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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'codigo\')" name="codigo" checked>
                    Código
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'codigo_barra\')" name="codigo_barra" checked>
                    Código de barra
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion\')" name="descripcion" checked>
                    Descripción
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio_bs\')" name="precio_bs" checked>
                    Precio Bs.
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio_ds\')" name="precio_ds" checked>
                    Precio $
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'gravado\')" name="gravado" checked>
                    Gravado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'utilidad\')" name="utilidad" checked>
                    Utilidad
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'costo\')" name="costo" checked>
                    Costo
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'drolanca\')" name="drolanca" checked>
                    Drolanca
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'drooeste\')" name="drooeste" checked>
                    Droguería del Oeste
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dronena\')" name="dronena" checked>
                    Dronena
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'drocerca\')" name="drocerca" checked>
                    Drocerca
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'cobeca\')" name="cobeca" checked>
                    COBECA
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_lote\')" name="ultimo_lote" checked>
                    Último lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_compra\')" name="ultima_compra" checked>
                    Última compra
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta\')" name="ultima_venta" checked>
                    Última venta
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_proveedor\')" name="ultimo_proveedor" checked>
                    Último proveedor
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
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTables()">
      </div>
      <br/>
    ';

    $tasa = compras\TasaVenta::where('moneda', 'Dolar')->first()->tasa;

    if ($_GET['opcion'] == 'Articulos dolarizados') {
        echo '<div class="text-center">Los costos de las droguerías están en dólares, calculados a la tasa de <b>'.$tasa.'</b>.</div>';
    }

    echo '
        <div class="text-center">
            <button class="btn btn-outline-danger subidas-btn"> <i class="fa fa-arrow-up"></i> Subidas</button>
            <button class="btn btn-outline-success bajadas-btn"> <i class="fa fa-arrow-down"></i> Bajadas</button>
        </div>
    ';

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>';

    echo'<h6 align="center">'.$opcion.'</h6>';

    $contador = 1;

    $sql2 = R48_Q_CambioPrecios($opcion);
    $result2 = sqlsrv_query($conn,$sql2);

    $subidas = [];
    $bajadas = [];

    $y = 0;
    $k = 0;

    while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {

        $costo_drolanca = '-';
        $costo_drooeste = '-';
        $costo_dronena = '-';
        $costo_drocerca = '-';
        $costo_cobeca = '-';

        $existencia_drolanca = 0;
        $existencia_drooeste = 0;
        $existencia_dronena = 0;
        $existencia_drocerca = 0;
        $existencia_cobeca = 0;

        $descuento1 = 0;
        $descuento2 = 0;
        $descuento3 = 0;

        $mejor_precio = '';
        $drogueria_mejor_precio = '';


        $id_articulo = $row2['IdArticulo'];
        $codigo_articulo = $row2['CodigoInterno'];
        $codigo_barra = $row2['CodigoBarra'];
        $descripcion = FG_Limpiar_Texto($row2['Descripcion']);
        $existencia = intval($row2['Existencia']);

        $Existencia = $row2["Existencia"];
        $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
        $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
        $IsTroquelado = $row2["Troquelado"];
        $IsIVA = $row2["Impuesto"];
        $UtilidadArticulo = $row2["UtilidadArticulo"];
        $UtilidadCategoria = $row2["UtilidadCategoria"];
        $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
        $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
        $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
        $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
        $PrecioCompraBruto = $row2["PrecioCompraBruto"];
        $CondicionExistencia = 'CON_EXISTENCIA';

        $precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
        $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
        $precio_sin_formato = $precio;
        $precio = number_format($precio,2,"." ,"," );

        $gravado = FG_Producto_Gravado($IsIVA);

        $utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
        $utilidad = (1 - $utilidad)*100 . '%';

        $ultimo_lote = $row2['UltimoLote']->format('d/m/Y');

        $nombre_ultimo_proveedor = FG_Limpiar_Texto($row2['UltimoProveedorNombre']);

        $precio_ds = number_format($precio_sin_formato / $tasa, 2);

        $ultima_venta = ($row2['UltimaVenta']) ? $row2['UltimaVenta']->format('d/m/Y') : '00/00/0000';
        $ultima_compra = ($row2['ultima_compra']) ? $row2['ultima_compra']->format('d/m/Y') : '';

        if ($_GET['opcion'] == 'Articulos no dolarizados') {
          $precio_compra_bruto = number_format($row2['PrecioCompraBruto'], 2, '.', ',');
        }

        if ($_GET['opcion'] == 'Articulos dolarizados') {
          $sql3 = R36Q_Historico_Articulo($id_articulo);
          $result3 = sqlsrv_query($conn,$sql3);

          $CostoMayorD = 0;
          $j = '';

          while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {

            $TasaMercado = FG_Tasa_Fecha($connCPharma,$row3["Auditoria_FechaCreacion"]->format('Y-m-d'));

            if($TasaMercado!=0){

              $CostoDolar = ($row3["M_PrecioCompraBruto"]/$TasaMercado);
              $CostoDolar = round($CostoDolar,2);

              if($CostoDolar>$CostoMayorD){
                $CostoMayorD = $CostoDolar;
                $j = 'Costo bruto: ' . $row3["M_PrecioCompraBruto"] . '<br>Tasa mercado: ' . $TasaMercado . '<br>Fecha documento: ' . $row3["Auditoria_FechaCreacion"]->format('Y-m-d');
              }
            }
          }
        }

        $indexDrolanca = array_search($codigo_barra, array_column($drolanca, 'B'));

        if ($indexDrolanca && $drolanca[$indexDrolanca+1]['J'] > 0) {
          $costo_drolanca = $drolanca[$indexDrolanca+1]['I'];
          $costo_drolanca = str_replace(',', '.', $costo_drolanca);

          $descuento1 = $costo_drolanca * ((int) $drolanca[$indexDrolanca+1]['L'] / 100);
          $descuento2 = $costo_drolanca * ((int) $drolanca[$indexDrolanca+1]['M'] / 100);
          $descuento3 = $costo_drolanca * ((int) $drolanca[$indexDrolanca+1]['N'] / 100);

          $costo_drolanca = $costo_drolanca - $descuento1 - $descuento2 - $descuento3;
          $costo_drolanca = ($_GET['descuentoFacturacionDrolanca'] != '') ? $costo_drolanca * $_GET['descuentoFacturacionDrolanca'] : $costo_drolanca;

          $costo_drolanca = $_GET['opcion'] == 'Articulos dolarizados' ? number_format($costo_drolanca / $tasa, 2) : number_format($costo_drolanca, 2);

          $existencia_drolanca = $drolanca[$indexDrolanca+1]['J'];

          $drogueria_mejor_precio = 'drolanca';
          $mejor_precio = $costo_drolanca;
        }


        $indexDrooeste = array_search($codigo_barra, array_column($drooeste, 'A'));

        if ($indexDrooeste && $drooeste[$indexDrooeste+1]['D'] > 0) {
          $costo_drooeste = $drooeste[$indexDrooeste+1]['E'];
          $costo_drooeste = str_replace(',', '.', $costo_drooeste);

          $descuento1 = $costo_drooeste * ((int) $drooeste[$indexDrooeste+1]['G'] / 100);
          $descuento2 = $costo_drooeste * ((int) $drooeste[$indexDrooeste+1]['H'] / 100);
          $descuento3 = $costo_drooeste * ((int) $drooeste[$indexDrooeste+1]['I'] / 100);

          $costo_drooeste = $costo_drooeste - $descuento1 - $descuento2 - $descuento3;
          $costo_drooeste = ($_GET['descuentoFacturacionDrooeste'] != '') ? $costo_drooeste * $_GET['descuentoFacturacionDrooeste'] : $costo_drooeste;

          $costo_drooeste = $_GET['opcion'] == 'Articulos dolarizados' ? @number_format($costo_drooeste / $tasa, 2) : number_format($costo_drooeste, 2);

          $existencia_drooeste = $drooeste[$indexDrooeste+1]['D'];

          if ($drogueria_mejor_precio != '') {
            if ($mejor_precio > $costo_drooeste) {
                $mejor_precio = $costo_drooeste;
                $drogueria_mejor_precio = 'drooeste';
            }
          } else {
            $drogueria_mejor_precio = 'drooeste';
            $mejor_precio = $costo_drooeste;
          }
        }

        $indexDronena = array_search($codigo_barra, array_column($dronena, 'CODBARRAS'));

        if ($indexDronena && $dronena[$indexDronena]['CANTIDAD'] > 0) {
          $costo_dronena = $dronena[$indexDronena]['PRECIO'];
          $costo_dronena = str_replace(',', '.', $costo_dronena);

          $descuento1 = $costo_dronena * ((int) $dronena[$indexDronena]['DCTO_UFI'] / 100);
          $descuento2 = $costo_dronena * ((int) $dronena[$indexDronena]['DCTO_EMPQUE'] / 100);

          $costo_dronena = $costo_dronena - $descuento1 - $descuento2;
          $costo_dronena = ($_GET['descuentoFacturacionDronena'] != '') ? $costo_dronena * $_GET['descuentoFacturacionDronena'] : $costo_dronena;

          $costo_dronena = $_GET['opcion'] == 'Articulos dolarizados' ? @number_format($costo_dronena / $tasa, 2) : number_format($costo_dronena, 2);

          $existencia_dronena = $dronena[$indexDronena]['CANTIDAD'];

          if ($drogueria_mejor_precio != '') {
            if ($mejor_precio > $costo_dronena) {
                $mejor_precio = $costo_dronena;
                $drogueria_mejor_precio = 'dronena';
            }
          } else {
            $drogueria_mejor_precio = 'dronena';
            $mejor_precio = $costo_dronena;
          }
        }

        $indexDrocerca = array_search($codigo_barra, array_column($drocerca, 'B'));

        if ($indexDrocerca && ($drocerca[$indexDrocerca+1]['D']+$drocerca[$indexDrocerca+1]['E']) > 0) {
          $costo_drocerca = $drocerca[$indexDrocerca+1]['H'];
          $costo_drocerca = str_replace(',', '.', $costo_drocerca);

          $costo_drocerca = $costo_drocerca;
          $costo_drocerca = ($_GET['descuentoFacturacionDrocerca'] != '') ? $costo_drocerca * $_GET['descuentoFacturacionDrocerca'] : $costo_drocerca;

          $costo_drocerca = $_GET['opcion'] == 'Articulos dolarizados' ? @number_format($costo_drocerca / $tasa, 2) : number_format($costo_drocerca, 2);

          $existencia_drocerca = $drocerca[$indexDrocerca+1]['D']+$drocerca[$indexDrocerca+1]['E'];

          if ($drogueria_mejor_precio != '') {
            if ($mejor_precio > $costo_drocerca) {
                $mejor_precio = $costo_drocerca;
                $drogueria_mejor_precio = 'drocerca';
            }
          } else {
            $drogueria_mejor_precio = 'drocerca';
            $mejor_precio = $costo_drocerca;
          }
        }

        $indexCobeca = array_search($codigo_barra, array_column($cobeca, 'cod_barra'));

        if ($indexCobeca && $cobeca[$indexCobeca]['existencia'] > 0) {
          $costo_cobeca = $cobeca[$indexCobeca]['monto_final'];
          $costo_cobeca = str_replace(',', '.', $costo_cobeca);

          $costo_cobeca = $costo_cobeca;
          $costo_cobeca = ($_GET['descuentoFacturacionCobeca'] != '') ? $costo_cobeca * $_GET['descuentoFacturacionCobeca'] : $costo_cobeca;

          $costo_cobeca = $_GET['opcion'] == 'Articulos dolarizados' ? @number_format($costo_cobeca / $tasa, 2) : number_format($costo_cobeca, 2);

          $existencia_cobeca = $cobeca[$indexCobeca]['existencia'];

          if ($drogueria_mejor_precio != '') {
            if ($mejor_precio > $costo_cobeca) {
                $mejor_precio = $costo_cobeca;
                $drogueria_mejor_precio = 'cobeca';
            }
          } else {
            $drogueria_mejor_precio = 'cobeca';
            $mejor_precio = $costo_cobeca;
          }
        }

        $costos = [$costo_drolanca, $costo_drooeste, $costo_dronena, $costo_drocerca, $costo_cobeca];

        $costos = array_filter($costos, function ($value) {
            if ($value != '-') {
                return $value;
            }
        });

        $min = @min($costos);

        $costo = ($_GET['opcion'] == 'Articulos no dolarizados') ? $precio_compra_bruto : $CostoMayorD;

        if ($costo > $min && !empty($costos)) {
            $bajadas[$y]['codigo_articulo'] = $codigo_articulo;
            $bajadas[$y]['codigo_barra'] = $codigo_barra;
            $bajadas[$y]['descripcion'] = $descripcion;
            $bajadas[$y]['id_articulo'] = $id_articulo;
            $bajadas[$y]['precio'] = $precio;
            $bajadas[$y]['precio_ds'] = $precio_ds;
            $bajadas[$y]['gravado'] = $gravado;
            $bajadas[$y]['utilidad'] = $utilidad;
            $bajadas[$y]['existencia'] = $existencia;
            $bajadas[$y]['costo'] = $costo;
            $bajadas[$y]['costo_drolanca'] = $costo_drolanca;
            $bajadas[$y]['existencia_drolanca'] = $existencia_drolanca;
            $bajadas[$y]['costo_drooeste'] = $costo_drooeste;
            $bajadas[$y]['existencia_drooeste'] = $existencia_drooeste;
            $bajadas[$y]['costo_dronena'] = $costo_dronena;
            $bajadas[$y]['existencia_dronena'] = $existencia_dronena;
            $bajadas[$y]['costo_drocerca'] = $costo_drocerca;
            $bajadas[$y]['existencia_drocerca'] = $existencia_drocerca;
            $bajadas[$y]['costo_cobeca'] = $costo_cobeca;
            $bajadas[$y]['existencia_cobeca'] = $existencia_cobeca;
            $bajadas[$y]['ultimo_lote'] = $ultimo_lote;
            $bajadas[$y]['ultima_compra'] = $ultima_compra;
            $bajadas[$y]['ultima_venta'] = $ultima_venta;
            $bajadas[$y]['nombre_ultimo_proveedor'] = $nombre_ultimo_proveedor;
            $bajadas[$y]['mejor_precio'] = $drogueria_mejor_precio;

            $y++;
        }

        if ($costo < $min && !empty($costos)) {
            $subidas[$k]['codigo_articulo'] = $codigo_articulo;
            $subidas[$k]['codigo_barra'] = $codigo_barra;
            $subidas[$k]['descripcion'] = $descripcion;
            $subidas[$k]['id_articulo'] = $id_articulo;
            $subidas[$k]['precio'] = $precio;
            $subidas[$k]['precio_ds'] = $precio_ds;
            $subidas[$k]['gravado'] = $gravado;
            $subidas[$k]['utilidad'] = $utilidad;
            $subidas[$k]['existencia'] = $existencia;
            $subidas[$k]['costo'] = $costo;
            $subidas[$k]['costo_drolanca'] = $costo_drolanca;
            $subidas[$k]['existencia_drolanca'] = $existencia_drolanca;
            $subidas[$k]['costo_drooeste'] = $costo_drooeste;
            $subidas[$k]['existencia_drooeste'] = $existencia_drooeste;
            $subidas[$k]['costo_dronena'] = $costo_dronena;
            $subidas[$k]['existencia_dronena'] = $existencia_dronena;
            $subidas[$k]['costo_drocerca'] = $costo_drocerca;
            $subidas[$k]['existencia_drocerca'] = $existencia_drocerca;
            $subidas[$k]['costo_cobeca'] = $costo_cobeca;
            $subidas[$k]['existencia_cobeca'] = $existencia_cobeca;
            $subidas[$k]['ultimo_lote'] = $ultimo_lote;
            $subidas[$k]['ultima_compra'] = $ultima_compra;
            $subidas[$k]['ultima_venta'] = $ultima_venta;
            $subidas[$k]['nombre_ultimo_proveedor'] = $nombre_ultimo_proveedor;
            $subidas[$k]['mejor_precio'] = $drogueria_mejor_precio;

            $k++;
        }

        $contador++;
    }

    mysqli_close($connCPharma);
    sqlsrv_close($conn);

    echo '
      <div class="subidas">
      <table class="table table-striped table-bordered col-12">
        <thead class="thead-dark">
           <tr>
            <th scope="col" class="text-center" colspan="19">Subidas</th>
          </tr>
        </thead>
      </table>

      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="codigo CP-sticky">Codigo Articulo</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo de Barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripcion</th>
            <th scope="col" class="precio_bs CP-sticky">Precio Bs.</th>
            <th scope="col" class="precio_ds CP-sticky">Precio $</th>
            <th scope="col" class="gravado CP-sticky">Gravado</th>
            <th scope="col" class="utilidad CP-sticky">Utilidad</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="costo CP-sticky">Costo</th>
            <th scope="col" class="bg-warning drolanca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'">Drolanca</th>
            <th scope="col" class="bg-warning drolanca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'">Existencia Drolanca</th>
            <th scope="col" class="bg-warning drooeste CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'">Drooeste</th>
            <th scope="col" class="bg-warning drooeste CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'">Existencia Drooeste</th>
            <th scope="col" class="bg-warning dronena CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'">Dronena</th>
            <th scope="col" class="bg-warning dronena CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'">Existencia Dronena</th>
            <th scope="col" class="bg-warning drocerca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'">Drocerca</th>
            <th scope="col" class="bg-warning drocerca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'">Existencia Drocerca</th>
            <th scope="col" class="bg-warning cobeca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'">COBECA</th>
            <th scope="col" class="bg-warning cobeca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'">Existencia COBECA</th>
            <th scope="col" class="ultimo_lote CP-sticky">Ultimo lote</th>
            <th scope="col" class="ultima_compra CP-sticky">Ultima compra</th>
            <th scope="col" class="ultima_venta CP-sticky">Ultima venta</th>
            <th scope="col" class="ultimo_proveedor CP-sticky">Ultimo Proveedor</th>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    foreach ($subidas as $subida) {

        $resaltado_drolanca = ($subida['mejor_precio'] == 'drolanca') ? 'bg-success' : 'bg-warning';
        $resaltado_drooeste = ($subida['mejor_precio'] == 'drooeste') ? 'bg-success' : 'bg-warning';
        $resaltado_dronena = ($subida['mejor_precio'] == 'dronena') ? 'bg-success' : 'bg-warning';
        $resaltado_drocerca = ($subida['mejor_precio'] == 'drocerca') ? 'bg-success' : 'bg-warning';
        $resaltado_cobeca = ($subida['mejor_precio'] == 'cobeca') ? 'bg-success' : 'bg-warning';


        echo '<tr>';

        echo '<td align="center"><b>'.$contador.'</b></td>';
        echo '<td class="codigo" align="center">'.$subida['codigo_articulo'].'</td>';
        echo '<td class="codigo_barra" align="center">'.$subida['codigo_barra'].'</td>';
        echo '<td align="center" class="descripcion CP-barrido"><a href="/reporte2?SEDE='.$_GET['SEDE'].'&Id='.$subida['id_articulo'].'" style="text-decoration: none; color: black;" target="_blank">'.$subida['descripcion'].'</a></td>';
        echo '<td class="precio_bs" align="center">'.$subida['precio'].'</td>';
        echo '<td class="precio_ds" align="center">'.$subida['precio_ds'].'</td>';
        echo '<td class="gravado" align="center">'.$subida['gravado'].'</td>';
        echo '<td class="utilidad" align="center">'.$subida['utilidad'].'</td>';
        echo '<td class="existencia" align="center">'.$subida['existencia'].'</td>';
        echo '<td class="costo" align="center">'.$subida['costo'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'" class="drolanca '.$resaltado_drolanca.'" align="center">'.$subida['costo_drolanca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'" class="drolanca bg-warning" align="center">'.$subida['existencia_drolanca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'" class="drooeste '.$resaltado_drooeste.'" align="center">'.$subida['costo_drooeste'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'" class="drooeste bg-warning" align="center">'.$subida['existencia_drooeste'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'" class="dronena '.$resaltado_dronena.'" align="center">'.$subida['costo_dronena'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'" class="dronena bg-warning" align="center">'.$subida['existencia_dronena'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'" class="drocerca '.$resaltado_drocerca.'" align="center">'.$subida['costo_drocerca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'" class="drocerca bg-warning" align="center">'.$subida['existencia_drocerca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'" class="cobeca '.$resaltado_cobeca.'" align="center">'.$subida['costo_cobeca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'" class="cobeca bg-warning" align="center">'.$subida['existencia_cobeca'].'</td>';

        echo '<td class="ultimo_lote" align="center">'.$subida['ultimo_lote'].'</td>';
        echo '<td class="ultima_compra" align="center">'.$subida['ultima_compra'].'</td>';
        echo '<td class="ultima_venta" align="center">'.$subida['ultima_venta'].'</td>';
        echo '<td class="ultimo_proveedor" align="center">'.$subida['nombre_ultimo_proveedor'].'</td>';
        echo '</tr>';

        $contador++;
    }

    echo '
        </tbody>
      </table>
      </div>
    ';

    echo '
      <div class="bajadas">
      <table class="table table-striped table-bordered col-12">
        <thead class="thead-dark">
           <tr>
            <th scope="col" class="text-center" colspan="19">Bajadas</th>
          </tr>
        </thead>
      </table>

      <table class="table table-striped table-bordered col-12 sortable" id="myTable2">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="codigo CP-sticky">Codigo Articulo</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo de Barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripcion</th>
            <th scope="col" class="precio_bs CP-sticky">Precio Bs.</th>
            <th scope="col" class="precio_ds CP-sticky">Precio $</th>
            <th scope="col" class="gravado CP-sticky">Gravado</th>
            <th scope="col" class="utilidad CP-sticky">Utilidad</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="costo CP-sticky">Costo</th>
            <th scope="col" class="bg-warning drolanca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'">Drolanca</th>
            <th scope="col" class="bg-warning drolanca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'">Existencia Drolanca</th>
            <th scope="col" class="bg-warning drooeste CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'">Drooeste</th>
            <th scope="col" class="bg-warning drooeste CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'">Existencia Drooeste</th>
            <th scope="col" class="bg-warning dronena CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'">Dronena</th>
            <th scope="col" class="bg-warning dronena CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'">Existencia Dronena</th>
            <th scope="col" class="bg-warning drocerca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'">Drocerca</th>
            <th scope="col" class="bg-warning drocerca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'">Existencia Drocerca</th>
            <th scope="col" class="bg-warning cobeca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'">COBECA</th>
            <th scope="col" class="bg-warning cobeca CP-sticky" data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'">Existencia COBECA</th>
            <th scope="col" class="ultimo_lote CP-sticky">Ultimo lote</th>
            <th scope="col" class="ultima_compra CP-sticky">Ultima compra</th>
            <th scope="col" class="ultima_venta CP-sticky">Ultima venta</th>
            <th scope="col" class="ultimo_proveedor CP-sticky">Ultimo Proveedor</th>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    foreach ($bajadas as $bajada) {
        $resaltado_drolanca = ($bajada['mejor_precio'] == 'drolanca') ? 'bg-success' : 'bg-warning';
        $resaltado_drooeste = ($bajada['mejor_precio'] == 'drooeste') ? 'bg-success' : 'bg-warning';
        $resaltado_dronena = ($bajada['mejor_precio'] == 'dronena') ? 'bg-success' : 'bg-warning';
        $resaltado_drocerca = ($bajada['mejor_precio'] == 'drocerca') ? 'bg-success' : 'bg-warning';
        $resaltado_cobeca = ($bajada['mejor_precio'] == 'cobeca') ? 'bg-success' : 'bg-warning';



        echo '<tr>';

        echo '<td align="center"><b>'.$contador.'</b></td>';
        echo '<td class="codigo" align="center">'.$bajada['codigo_articulo'].'</td>';
        echo '<td class="codigo_barra" align="center">'.$bajada['codigo_barra'].'</td>';
        echo '<td align="center" class="descripcion CP-barrido"><a href="/reporte2?SEDE='.$_GET['SEDE'].'&Id='.$bajada['id_articulo'].'" style="text-decoration: none; color: black;" target="_blank">'.$bajada['descripcion'].'</a></td>';
        echo '<td class="precio_bs" align="center">'.$bajada['precio'].'</td>';
        echo '<td class="precio_ds" align="center">'.$bajada['precio_ds'].'</td>';
        echo '<td class="gravado" align="center">'.$bajada['gravado'].'</td>';
        echo '<td class="utilidad" align="center">'.$bajada['utilidad'].'</td>';
        echo '<td class="existencia" align="center">'.$bajada['existencia'].'</td>';
        echo '<td class="costo" align="center">'.$bajada['costo'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'" class="drolanca '.$resaltado_drolanca.'" align="center">'.$bajada['costo_drolanca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drolancaFechaActualizacion.'" class="drolanca bg-warning" align="center">'.$bajada['existencia_drolanca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'" class="drooeste '.$resaltado_drooeste.'" align="center">'.$bajada['costo_drooeste'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drooesteFechaActualizacion.'" class="drooeste bg-warning" align="center">'.$bajada['existencia_drooeste'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'" class="dronena '.$resaltado_dronena.'" align="center">'.$bajada['costo_dronena'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$dronenaFechaActualizacion.'" class="dronena bg-warning" align="center">'.$bajada['existencia_dronena'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'" class="drocerca '.$resaltado_drocerca.'" align="center">'.$bajada['costo_drocerca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$drocercaFechaActualizacion.'" class="drocerca bg-warning" align="center">'.$bajada['existencia_drocerca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'" class="cobeca '.$resaltado_cobeca.'" align="center">'.$bajada['costo_cobeca'].'</td>';
        echo '<td data-toggle="tooltip" data-placement="bottom" title="Fecha de actualización: '.$cobecaFechaActualizacion.'" class="cobeca bg-warning" align="center">'.$bajada['existencia_cobeca'].'</td>';

        echo '<td class="ultimo_lote" align="center">'.$bajada['ultimo_lote'].'</td>';
        echo '<td class="ultima_compra" align="center">'.$bajada['ultima_compra'].'</td>';
        echo '<td class="ultima_venta" align="center">'.$bajada['ultima_venta'].'</td>';
        echo '<td class="ultimo_proveedor" align="center">'.$bajada['nombre_ultimo_proveedor'].'</td>';
        echo '</tr>';

        $contador++;
    }

    echo '
        </tbody>
      </table>
      </div>
    ';
  }


  /*
  TITULO: R48_Q_CambioPrecios
  PARAMETROS: [$opcion] Articulos dolarizados o no dolarizados
  RETORNO: Un String con la query pertinente
   */
  function R48_Q_CambioPrecios($opcion) {
    if ($opcion == 'Articulos no dolarizados') {
      $sql = "
          SELECT TOP 15
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Categoria Articulo
  InvArticulo.InvCategoriaId ,
--Codigo Interno
    InvArticulo.CodigoArticulo AS CodigoInterno,
--Codigo de Barra
    (SELECT CodigoBarra
    FROM InvCodigoBarra
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
--Descripcion
    InvArticulo.Descripcion,
--Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
    (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
--Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Troquelados'
    OR  InvAtributo.Descripcion = 'troquelados')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
--UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
        FROM VenCondicionVenta
        WHERE VenCondicionVenta.Id = (
        SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
        FROM VenCondicionVenta_VenCondicionVentaArticulo
        WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
--UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
  FROM VenCondicionVenta
  WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
--Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
--Precio Compra Bruto Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
  AND (InvLoteAlmacen.InvAlmacenId = '1')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
--Precio Troquel Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
--Precio Compra Bruto Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
  AND (InvLoteAlmacen.InvAlmacenId = '2')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
--Precio Compra Bruto
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
--Existencia (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
--ExistenciaAlmacen1 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
--ExistenciaAlmacen2 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
--Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Dolarizados'
    OR  InvAtributo.Descripcion = 'Giordany'
    OR  InvAtributo.Descripcion = 'giordany')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
--Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Medicina')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
--Articulo Estrella (0 NO es Articulo Estrella , Id SI es Articulo Estrella)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Articulo Estrella')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS ArticuloEstrella,
--Marca
    InvMarca.Nombre as Marca,
-- Ultima Venta (Fecha)
    (SELECT TOP 1
    CONVERT(DATE,VenFactura.FechaDocumento)
    FROM VenFactura
    INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
    WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY FechaDocumento DESC) AS UltimaVenta,
--Tiempo sin Venta (En dias)
    (SELECT TOP 1
    DATEDIFF(DAY,CONVERT(DATE,VenFactura.FechaDocumento),GETDATE())
    FROM VenFactura
    INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
    WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY FechaDocumento DESC) AS TiempoSinVenta,
--Ultimo Lote (Fecha)
    (SELECT TOP 1
    CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
    FROM InvLote
    WHERE InvLote.InvArticuloId  = InvArticulo.Id
    ORDER BY UltimoLote DESC) AS UltimoLote,
--Tiempo Tienda (En dias)
    (SELECT TOP 1
    DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
    FROM InvLoteAlmacen
    INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
    WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
    ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
-- Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
-- Ultimo Proveedor (Nombre Proveedor)
    (SELECT TOP 1
    GenPersona.Nombre
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
-- Ultima compra
    (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = (SELECT TOP 1 ComFacturaDetalle.ComFacturaId FROM ComFacturaDetalle WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id ORDER BY ComFacturaDetalle.ComFacturaId DESC)) AS ultima_compra
--Tabla principal
    FROM InvArticulo
--Joins
    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE Existencia > 0 AND (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Dolarizados'
    OR  InvAtributo.Descripcion = 'Giordany'
    OR  InvAtributo.Descripcion = 'giordany')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) = 0 AND
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
      ";
    }

    if ($opcion == 'Articulos dolarizados') {
      $sql = "
        SELECT TOP 15
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Categoria Articulo
  InvArticulo.InvCategoriaId ,
--Codigo Interno
    InvArticulo.CodigoArticulo AS CodigoInterno,
--Codigo de Barra
    (SELECT CodigoBarra
    FROM InvCodigoBarra
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
--Descripcion
    InvArticulo.Descripcion,
--Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
    (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
--Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Troquelados'
    OR  InvAtributo.Descripcion = 'troquelados')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
--UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
        FROM VenCondicionVenta
        WHERE VenCondicionVenta.Id = (
        SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
        FROM VenCondicionVenta_VenCondicionVentaArticulo
        WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
--UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
  FROM VenCondicionVenta
  WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
--Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
--Precio Compra Bruto Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
  AND (InvLoteAlmacen.InvAlmacenId = '1')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
--Precio Troquel Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
--Precio Compra Bruto Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
  AND (InvLoteAlmacen.InvAlmacenId = '2')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
--Precio Compra Bruto
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
--Existencia (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
--ExistenciaAlmacen1 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
--ExistenciaAlmacen2 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
--Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Dolarizados'
    OR  InvAtributo.Descripcion = 'Giordany'
    OR  InvAtributo.Descripcion = 'giordany')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
--Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Medicina')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
--Articulo Estrella (0 NO es Articulo Estrella , Id SI es Articulo Estrella)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Articulo Estrella')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS ArticuloEstrella,
--Marca
    InvMarca.Nombre as Marca,
-- Ultima Venta (Fecha)
    (SELECT TOP 1
    CONVERT(DATE,VenFactura.FechaDocumento)
    FROM VenFactura
    INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
    WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY FechaDocumento DESC) AS UltimaVenta,
--Tiempo sin Venta (En dias)
    (SELECT TOP 1
    DATEDIFF(DAY,CONVERT(DATE,VenFactura.FechaDocumento),GETDATE())
    FROM VenFactura
    INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
    WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY FechaDocumento DESC) AS TiempoSinVenta,
--Ultimo Lote (Fecha)
    (SELECT TOP 1
    CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
    FROM InvLote
    WHERE InvLote.InvArticuloId  = InvArticulo.Id
    ORDER BY UltimoLote DESC) AS UltimoLote,
--Tiempo Tienda (En dias)
    (SELECT TOP 1
    DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
    FROM InvLoteAlmacen
    INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
    WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
    ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
-- Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
-- Ultimo Proveedor (Nombre Proveedor)
    (SELECT TOP 1
    GenPersona.Nombre
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
-- Ultima compra
  (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = (SELECT TOP 1 ComFacturaDetalle.ComFacturaId FROM ComFacturaDetalle WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id ORDER BY ComFacturaDetalle.ComFacturaId DESC)) AS ultima_compra
--Tabla principal
    FROM InvArticulo
--Joins
    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE Existencia > 0 AND (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Dolarizados'
    OR  InvAtributo.Descripcion = 'Giordany'
    OR  InvAtributo.Descripcion = 'giordany')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) != 0 AND
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
      ";
    }

    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R2Q_Historico_Articulo
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R36Q_Historico_Articulo($IdArticulo) {
    $sql = "
      SELECT
      ComProveedor.Id,
      GenPersona.Nombre,
      CONVERT(DATE,ComFactura.FechaRegistro) As FechaRegistro,
      CONVERT(DATE,ComFactura.FechaDocumento) As FechaDocumento,
      CONVERT(DATE,ComFacturaDetalle.FechaVencimiento) As FechaVencimiento,
      DATEDIFF(DAY,CONVERT(DATE,ComFactura.FechaRegistro),CONVERT(DATE,ComFacturaDetalle.FechaVencimiento)) as VidaUtil,
      DATEDIFF(DAY,CONVERT(DATE,GETDATE()),CONVERT(DATE,ComFacturaDetalle.FechaVencimiento)) as DiasVecer,
      ComFacturaDetalle.CantidadRecibidaFactura,
      ComFacturaDetalle.M_PrecioCompraBruto,
      ComFactura.auditoria_usuario as operador,
      ComFactura.NumeroFactura as numero,
      ComFactura.Id as idFactura
      FROM InvArticulo
      INNER JOIN ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE InvArticulo.Id = '$IdArticulo'
      ORDER BY ComFactura.FechaDocumento DESC
    ";

    $sql = "
      SELECT
          InvLote.M_PrecioCompraBruto, InvLote.Auditoria_FechaCreacion
      FROM InvLoteALmacen LEFT JOIN InvLote ON InvLoteAlmacen.InvLoteId = InvLote.Id
      WHERE
        InvLoteAlmacen.InvArticuloId = '$IdArticulo' AND
        InvLoteAlmacen.Existencia > 0 AND
        InvLoteAlmacen.InvAlmacenId IN (1, 2)
    ";
    return $sql;
  }
?>
