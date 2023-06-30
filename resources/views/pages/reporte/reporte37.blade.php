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


@section('scriptsFoot')
  <script src="https://raw.githubusercontent.com/SamWM/jQuery-Plugins/master/numeric/jquery.numeric.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>
    $(document).ready(function () {
       $('#existenciaUsuario').change(function () {
          value = $('#existenciaUsuario').val();

          if (value < 0) {
            alert('Debe ingresar un numero positivo');
            $('#existenciaUsuario').val('');
          }
        });
    });

        function agregarTraslado(codigo_barra, sede, existencia, bandera = '') {
            cantidad = prompt('Ingrese la cantidad' + bandera + ': ');

            if (cantidad) {
                if (!parseInt(cantidad)) {
                    agregarTraslado(codigo_barra, sede, existencia, ' (sólo números enteros)');
                    return false;
                }

                if (parseInt(cantidad) <= 0) {
                    agregarTraslado(codigo_barra, sede, existencia, ' (número mayor a cero)');
                    return false;
                }

                if (parseInt(cantidad) > parseInt(existencia)) {
                    agregarTraslado(codigo_barra, sede, existencia, ' (número menor a existencia)');
                    return false;
                }

                cantidad = parseInt(cantidad);

                $.ajax({
                    type: 'POST',
                    url: '/trasladoRecibir',
                    data: {
                        codigo_barra: codigo_barra,
                        sede: sede,
                        cantidad: cantidad,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        toastr.success('Articulo agregado a traslado');
                    }
                })
            }
        }

        function confirmarEliminacion(url) {
            respuesta = confirm('¿Está seguro que desea eliminar este artículo?');

            if (respuesta) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        console.log(response);
                        window.location.href = window.location.href.replace('#', '');
                    }
                })
            }
        }

        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['codigo', 'codigo_barra', 'descripcion', 'existencia', 'ultima_venta', 'precio', 'sede1', 'sede2', 'sede3', 'sede4', 'existencias_foraneas'];

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
      Traslados entre tiendas
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

    if(isset($_GET['existenciaUsuario'])) {
      $InicioCarga = new DateTime("now");
      R37_Traslados_Entre_Tiendas($_GET['SEDE'],$_GET['existenciaUsuario']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Traslados entre tiendas');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      echo '
        <form autocomplete="off" action="" target="_blank">
          <table style="width: 100%">
            <tr>
              <td align="justify" colspan="3">
                <div class="alert alert-info">
                    <li>En el resultado no se mostraran articulos que en la sede origen tengan existencia mayor a la cantidad indicada. Ejemplo: si se marca 30 unidades, se mostraran todos los articulos con existencia en la sede origen menor a 30 y con existencia mayor a cero en las sedes destino, los articulos con existencia mayor a 30 no se analizaran en este reporte.</li>

                    <li>Una existencia alta generara mas resultados que una solicitud con una cantidad baja. La idea es hacerle entender al sistema que esa existencia indicada es una cantidad que necesita ser repuesta con mercancia de otras tiendas via traslado.</li>
                </div>
              </td>
            </tr>

            <tr>
              <td align="center">
                <label for="existenciaUsuario">Artículos con existencia menor o igual a:</label>
              </td>

              <td>
                <input id="existenciaUsuario" type="number" name="existenciaUsuario" required style="width:100%;">
              </td>

              <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($_GET['SEDE']);
              echo'">

              <td align="right">
                <input type="submit" value="Buscar" class="btn btn-outline-success">
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
    TITULO: R37_Traslados_Entre_Tiendas
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
                [$existenciaUsuario] Existencia ingresada por el usuario
    FUNCION: Arma una lista de productos con existencia menor a la indicada por el usuario
    RETORNO: No aplica
  */
  function R37_Traslados_Entre_Tiendas($SedeConnection,$existenciaUsuario) {
    session()->forget('traslado');

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $Hoy = new DateTime('now');
    $Hoy = $Hoy->format('Y-m-d');

    echo '
      <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTableWithoutFooter()">
      </div>
      <br/>
    ';

    echo'<h6 align="center">'.$existenciaUsuario.' o menor existencia en '.FG_Nombre_Sede($_GET['SEDE']).'</h6>';

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Ocultar u mostrar columnas<a></h6>';

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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta\')" name="ultima_venta" checked>
                    Última venta
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio\')" name="precio" checked>
                    Precio
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede1\')" name="sede1" checked>
                    Sede 1
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede2\')" name="sede2" checked>
                    Sede 2
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede3\')" name="sede3" checked>
                    Sede 3
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede4\')" name="sede4" checked>
                    Sede 4
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencias_foraneas\')" name="existencias_foraneas" checked>
                    Existencias foráneas
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
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="codigo CP-sticky">Codigo Interno</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo de Barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripcion</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="ultima_venta CP-sticky">Ultima Venta</td>
            <th scope="col" class="precio CP-sticky">Precio</td>
    ';

    if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
    echo '<th scope="col" class="sede1 CP-sticky">Descripción FTN</th>
      <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
      <th scope="col" class="sede2 CP-sticky">Descripción FLL</th>
      <th scope="col" class="sede2 CP-sticky">Existencia FLL</td>
      <th scope="col" class="sede3 CP-sticky">Descripción FSM</th>
      <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
      <th scope="col" class="sede4 CP-sticky">Descripción FEC</th>
      <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
    ';
    }

    if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
      echo '<th scope="col" class="CP-sticky sede1">Descripción FAU</th>
      <th scope="col" class="CP-sticky sede1">Existencia FAU</td>
      <th scope="col" class="CP-sticky sede2">Descripción FLL</th>
      <th scope="col" class="CP-sticky sede2">Existencia FLL</td>
      <th scope="col" class="CP-sticky sede3">Descripción FSM</th>
      <th scope="col" class="CP-sticky sede3">Existencia FSM</td>
      <th scope="col" class="sede4 CP-sticky">Descripción FEC</th>
      <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
    ';
    }

    if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
      echo '<th scope="col" class="CP-sticky sede1">Descripción FTN</th>
      <th scope="col" class="CP-sticky sede1">Existencia FTN</td>
      <th scope="col" class="CP-sticky sede2">Descripción FAU</th>
      <th scope="col" class="CP-sticky sede2">Existencia FAU</td>
      <th scope="col" class="CP-sticky sede3">Descripción FSM</th>
      <th scope="col" class="CP-sticky sede3">Existencia FSM</td>
      <th scope="col" class="sede4 CP-sticky">Descripción FEC</th>
      <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
    ';
    }

    if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
      echo '<th scope="col" class="CP-sticky sede1">Descripción FTN</th>
      <th scope="col" class="CP-sticky sede1">Existencia FTN</td>
      <th scope="col" class="CP-sticky sede2">Descripción FAU</th>
      <th scope="col" class="CP-sticky sede2">Existencia FAU</td>
      <th scope="col" class="CP-sticky sede3">Descripción FLL</th>
      <th scope="col" class="CP-sticky sede3">Existencia FLL</td>
      <th scope="col" class="sede4 CP-sticky">Descripción FEC</th>
      <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
    ';
    }

    if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FEC') {
      echo '<th scope="col" class="CP-sticky sede1">Descripción FTN</th>
      <th scope="col" class="CP-sticky sede1">Existencia FTN</td>
      <th scope="col" class="CP-sticky sede2">Descripción FAU</th>
      <th scope="col" class="CP-sticky sede2">Existencia FAU</td>
      <th scope="col" class="CP-sticky sede3">Descripción FLL</th>
      <th scope="col" class="CP-sticky sede3">Existencia FLL</td>
      <th scope="col" class="sede4 CP-sticky">Descripción FSM</th>
      <th scope="col" class="sede4 CP-sticky">Existencia FSM</td>
    ';
    }

    echo '<th scope="col" class="CP-sticky existencias_foraneas">Existencias foraneas totales</td>';
    echo '<th scope="col" class="CP-sticky" colspan="4">Acciones traslado</th>
      </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    $sql2 = R37_Q_Articulos_Con_Existencia_Menor($existenciaUsuario);
    $result2 = sqlsrv_query($conn,$sql2);

    $conectividad_ftn = FG_Validar_Conectividad('FTN');
    $conectividad_fau = FG_Validar_Conectividad('FAU');
    $conectividad_fll = FG_Validar_Conectividad('FLL');
    $conectividad_fsm = FG_Validar_Conectividad('FSM');
    $conectividad_fec = FG_Validar_Conectividad('FEC');

    $connFAU = FG_Conectar_Smartpharma('FAU');
    $connFTN = FG_Conectar_Smartpharma('FTN');
    $connFLL = FG_Conectar_Smartpharma('FLL');
    $connFSM = FG_Conectar_Smartpharma('FSM');
    $connFEC = FG_Conectar_Smartpharma('FEC');

    while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {
      $codigo_articulo = $row2['CodigoInterno'];
      $codigo_barra = $row2['CodigoBarra'];

      $existencia = intval($row2['Existencia']);
      $descripcion = FG_Limpiar_Texto($row2['Descripcion']);
      $ultima_venta = ($row2['UltimaVenta']) ? $row2['UltimaVenta']->format('d/m/Y') : '00/00/0000';

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

      $existenciaForanea = 0;

      if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
        if ($conectividad_ftn == 1) {
          $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
          $result3 = sqlsrv_query($connFTN,$sql3);
          if(!$result3 === false) {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
            $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
          }
        }

        if ($conectividad_fll == 1) {
          $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
          $result3 = sqlsrv_query($connFLL,$sql3);
          if(!$result3 === false) {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
            $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
          }
        }

        if ($conectividad_fsm == 1) {
          $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
          $result3 = sqlsrv_query($connFSM,$sql3);
          if(!$result3 === false) {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
            $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
          }
        }

        if ($conectividad_fec == 1) {
          $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
          $result3 = sqlsrv_query($connFEC,$sql3);
          if(!$result3 === false) {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
            $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
          }
        }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
        if ($conectividad_fau == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fll == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFSM,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fec == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFEC,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
         if ($conectividad_ftn == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fau == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFSM,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fec == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFEC,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
         if ($conectividad_ftn == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fau == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fll == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fec == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFEC,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FEC') {
         if ($conectividad_ftn == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fau == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fll == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R37_Q_Descripcion_Existencia_Articulo($codigo_barra);
            $result3 = sqlsrv_query($connFSM,$sql3);
            if(!$result3 === false) {
                $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
                $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                $existenciaForanea = $existenciaForanea + (($row3['existencia']) ? $row3['existencia'] : 0);
            }
          }
      }

      if ($existenciaForanea > 0) {
        $precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
        $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
        $precio_sin_formato = $precio;
        $precio = number_format($precio,2,"." ,"," );

        echo '<tr>';

        echo '<td align="center"><b>'.$contador.'</b></td>';
        echo '<td class="codigo" align="center">'.$codigo_articulo.'</td>';
        echo '<td class="codigo_barra" align="center">'.$codigo_barra.'</td>';
        echo '<td class="descripcion" align="center">'.$descripcion.'</td>';
        echo '<td class="existencia" align="center">'.$existencia.'</td>';
        echo '<td class="ultima_venta" align="center">'.$ultima_venta.'</td>';
        echo '<td class="precio" align="center">'.$precio.'</td>';

        if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1) {
                echo '<td  class="sede1" align="center">'.$descripcion_ftn.'</td>
                      <td  class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1" >-</td>';
                echo '<td class="sede1" >-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_fau.'</td>
                      <td class="sede1" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
            if ($conectividad_ftn == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fll.'</td>
                      <td class="sede3" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FEC') {
            if ($conectividad_ftn == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fll.'</td>
                      <td class="sede3" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede4" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede4" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        echo '<td align="center" class="existencias_foraneas">'.$existenciaForanea.'</td>';

        if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FEC\', \'' . $existencia_fec . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FEC\', \'' . $existencia_fec . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FEC\', \'' . $existencia_fec . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FEC\', \'' . $existencia_fec . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FEC') {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $codigo_barra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        echo '</tr>';

        $contador++;
      }

    }

    echo '</tbody>';

    echo '<tfoot>';

    echo '<tr>';
    echo '<td colspan="14"></td>';
    echo '<td colspan="3"><a href="/trasladoRecibir" target="_blank" class="btn btn-outline-info btn-sm">Ver soporte</a>';
    echo '<button type="button" onclick="confirmarEliminacion(\'/trasladoRecibir/limpiar\')" class="btn btn-outline-danger btn-sm">Limpiar todo</button></td>';
    echo '</tr>';

    echo '</tfoot>';


    echo '</table>';

  sqlsrv_close($conn);
  }

  function R37_Q_Descripcion_Existencia_Articulo($CodigoBarra)
  {

    $CodigoBarra = str_replace("'", "''", $CodigoBarra);

    $sql = "
      SELECT
        InvArticulo.DescripcionLarga AS descripcion,
        (SELECT SUM(Existencia) FROM InvLoteAlmacen WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AS existencia
      FROM
      InvArticulo
      WHERE InvArticulo.Id = (SELECT InvCodigoBarra.InvArticuloId FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$CodigoBarra' AND InvCodigoBarra.EsPrincipal = 1)
    ";

    return $sql;
  }

  /*
  TITULO: R37_Q_Articulos_Con_Existencia_Menor
  PARAMETROS: [$Sede] Sede de conexión
          [$existencia] Existencia de productos
  RETORNO: Un String con la query pertinente
   */
  function R37_Q_Articulos_Con_Existencia_Menor($existencia) {
    $sql = "
    SELECT
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
    WHERE (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) <= '$existencia'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY Existencia ASC
    ";
    return $sql;
  }
?>
