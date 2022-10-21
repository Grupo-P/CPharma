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

  <link rel="stylesheet" href="/assets/jquery/jquery-ui-last.css">
  <script src="/assets/jquery/jquery-ui-last.js"></script>

  <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['codigo', 'codigo_barra', 'descripcion', 'producto_unico', 'dolarizado', 'gravado', 'clasificacion', 'precio', 'ultimo_precio', 'existencia', 'unidades_vendidas', 'unidades_compradas', 'venta_diaria', 'venta_diaria_real', 'dias_restantes', 'dias_restantes_real', 'ultima_venta_rango', 'ultima_venta', 'ultimo_lote', 'ultima_compra', 'ultimo_proveedor', 'pedir', 'pedir_real', 'traslado_transito'];

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

        @php
            include(app_path().'\functions\config.php');
            include(app_path().'\functions\functions.php');
            include(app_path().'\functions\querys_mysql.php');
            include(app_path().'\functions\querys_sqlserver.php');


            $SedeConnection = FG_Mi_Ubicacion();

            $conn = FG_Conectar_Smartpharma($SedeConnection);
            $proveedores = [];
            $codigo = [];
            $i = 0;

            $sql = R7Q_Lista_Proveedores();

            $query = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $proveedores[$i]['id'] = $row['id'];
                $proveedores[$i]['label'] = mb_convert_encoding($row['nombre'], 'UTF-8', 'UTF-8');
                $proveedores[$i]['value'] = mb_convert_encoding($row['nombre'], 'UTF-8', 'UTF-8');

                $i++;
            }
        @endphp



        $(document).ready(function () {
            $('.inputProveedor').autocomplete({
                source: {!! json_encode($proveedores) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId').val(ui.item.id);
                }
            });

            $('.agregarOrdenCompra').on('click', function (event) {
              event.preventDefault();

              data = $(this).serialize();
              url = $(this).attr('action');

              $.ajax({
                url: url,
                data: data,
                success: function (response) {
                  if (response == 'ordenNoActiva') {
                    alert('Usted NO posee una orden de compra activa');
                    window.open('/ordenCompra/create', '_blank');
                    return false;
                  }
                  
                  $('#myModal').find('.modal-body').html(response);
                  $('#myModal').modal('show');
                },
                error: function (error) {
                  $('body').html(error.responseText);
                }
              });
            });
        });
  </script>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Catalogo de proveedor
  </h1>
  <hr class="row align-items-start col-12">

<?php

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

  if (isset($_GET['Id'])){
  //CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR
    $InicioCarga = new DateTime("now");

    echo '
    <form autocomplete="off" action="" target="_blank">
      <table style="width:100%;">
        <tr>
          <td align="center">
            Pedido cant. dias
          </td>
          <td align="right" style="width:20%;">
            <input id="pedido" type="text" name="pedido" required style="width:100%;" autofocus>
          </td>
          <td align="center">
            Fecha Inicio:
          </td>
          <td>
            <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
          </td>
          <td align="center">
            Fecha Fin:
          </td>
          <td align="right">
            <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
          </td>
          <td>
          <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
          </td>
          <td align="right">
            <input type="submit" value="Buscar" class="btn btn-outline-success">
          </td>
        </tr>
      </table>
      <input id="IdProv" name="IdProv" type="hidden" value="'.$_GET['Id'].'">
      <input id="NombreProv" name="NombreProv" type="hidden" value="'.$_GET['Nombre'].'">
    </form>
    <br>
    ';

    $cadenaCodigosBarra = R7_Catalogo_Proveedor_C2($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);

    echo '<button onclick="copiar_codigo_barras(\'' . $cadenaCodigosBarra . '\')" class="btn btn-outline-success" type="button">Copiar códigos de barra</button></br>';
    //echo '<pre><strong>Codigos de Barra<br>'.$cadenaCodigosBarra.'<br></strong></pre>';
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['pedido'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UN PEDIDO EN BASE A DIAS Y EL RANGO

    $InicioCarga = new DateTime("now");

    R7_Catalogo_Proveedor_C3($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['pedido']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Catalogo de proveedor');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
    $InicioCarga = new DateTime("now");

    echo '
    <form autocomplete="off" action="" target="_blank">
      <div class="autocomplete" style="width:90%;">
        <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del proveedor " class="inputProveedor" onkeyup="conteo()" required>
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
@endsection

@section('scriptsFoot')

<script>
  function copiar_codigo_barras(text) {
       // Crea un campo de texto "oculto"
        var aux = document.createElement("input");

        // Asigna el contenido del elemento especificado al valor del campo
        aux.setAttribute("value", text);

        // Añade el campo a la página
        document.body.appendChild(aux);

        // Selecciona el contenido del campo
        aux.select();

        // Copia el texto seleccionado
        document.execCommand("copy");

        // Elimina el campo de la página
        document.body.removeChild(aux);
    }
</script>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R7_Catalogo_Proveedor_C2
    FUNCION: Armar el reporte catalogo de proveedor
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R7_Catalogo_Proveedor_C2($SedeConnection,$IdProveedor,$NombreProveedor){

    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql = R7Q_Catalogo_Proveedor($IdProveedor);
    $result = sqlsrv_query($conn,$sql);

    $cadenaCodigosBarra = "";

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
    </div>
    <br/>

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
          <tr>
            <th scope="col">Proveedor</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
    echo '<td>'.($NombreProveedor).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = $conteo = 1;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];
      $CodigoArticulo = $row["CodigoArticulo"];

      $CodigoBarra = $row["CodigoBarra"];

      if($conteo==15){
        $cadenaCodigosBarra = $cadenaCodigosBarra.$CodigoBarra.",";
        $conteo = 1;
      }
      else{
        $cadenaCodigosBarra = $cadenaCodigosBarra.$CodigoBarra.",";
        $conteo++;
      }

      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$CodigoArticulo.'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';
      echo
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';

      echo '</tr>';
    $contador++;
      }
      echo '
        </tbody>
    </table>';

    sqlsrv_close($conn);

    return $cadenaCodigosBarra;
  }
  /**********************************************************************************/
  /*
    TITULO: R7_Catalogo_Proveedor_C3
    FUNCION: Armar el reporte catalogo de proveedor
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
   */
  function R7_Catalogo_Proveedor_C3($SedeConnection,$IdProveedor,$NombreProveedor,$FInicial,$FFinal,$DiasPedido){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

  /*INCIO PARA CALCULOS CON DIAS EN CERO*/
    $sqlDC = MySQL_Rango_Dias_Cero();
    $resultDC = mysqli_query($connCPharma,$sqlDC);
    $rowDC = $resultDC->fetch_assoc();
    $DC_FInicialImp = date("d-m-Y", strtotime($rowDC['Inicio']));
    $DC_FFinalImp = date("d-m-Y", strtotime($rowDC['Fin']));
 /*FIN PARA CALCULOS CON DIAS EN CERO*/

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);

    $sql = R7Q_Catalogo_Proveedor($IdProveedor);
    $result = sqlsrv_query($conn,$sql);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
    </div>
    <br/>
    ';
    echo'<h6 align="center">Pedido en base a: '.$DiasPedido.' dias </h6>';
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
    echo'<h6 align="center">La data recolectada para el calculo <span style="color:red;">(Real)</span> va desde el <span style="color:red;">'.$DC_FInicialImp.'</span> al <span style="color:red;">'.$DC_FFinalImp.'</span> </h6>';

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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'producto_unico\')" name="producto_unico" checked>
                    Producto único
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dolarizado\')" name="dolarizado" checked>
                    Dolarizado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'gravado\')" name="gravado" checked>
                    Gravado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'clasificacion\')" name="clasificacion" checked>
                    Clasificacion
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio\')" name="precio" checked>
                    Precio
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_precio\')" name="ultimo_precio" checked>
                    Último precio
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'unidades_vendidas\')" name="unidades_vendidas" checked>
                    Unidades vendidas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'unidades_compradas\')" name="unidades_compradas" checked>
                    Unidades compradas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'venta_diaria\')" name="venta_diaria" checked>
                    Venta diaria
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'venta_diaria_real\')" name="venta_diaria_real" checked>
                    Venta diaria real
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_restantes\')" name="dias_restantes" checked>
                    Días restantes
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_restantes_real\')" name="dias_restantes_real" checked>
                    Días restantes real
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta_rango\')" name="ultima_venta_rango" checked>
                    Última venta en rango
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta\')" name="ultima_venta" checked>
                    Última venta
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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_proveedor\')" name="ultimo_proveedor" checked>
                    Último proveedor
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'pedir\')" name="pedir" checked>
                    Pedir
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'pedir_real\')" name="pedir_real" checked>
                    Pedir real
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'traslado_transito\')" name="traslado_transito" checked>
                    Traslado en transito
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
      <div class="modal" tabindex="-1" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Agregar a orden</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>
    ';




    echo '

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
          <tr>
            <th scope="col">Proveedor</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
    echo '<td>'.($NombreProveedor).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="codigo CP-sticky">Codigo</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo de Barra</td>
            <th scope="col" class="descripcion CP-sticky">Descripcion</th>
            <th scope="col" class="producto_unico CP-sticky">Producto Unico</th>
            <th scope="col" class="dolarizado CP-sticky">Dolarizado?</td>
            <th scope="col" class="gravado CP-sticky">Gravado?</td>
            <th scope="col" class="clasificacion CP-sticky">Clasificacion</td>
            <th scope="col" class="precio CP-sticky">Precio (Con IVA) '.SigVe.'</th>
            <th scope="col" class="ultimo_precio CP-sticky">Ultimo Precio (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="unidades_vendidas CP-sticky">Unidades vendidas</th>
            <th scope="col" class="unidades_compradas CP-sticky">Unidades compradas</th>
            <th scope="col" class="venta_diaria CP-sticky">Venta diaria</th>
            <th scope="col" class="venta_diaria_real CP-sticky bg-danger text-white">Venta diaria (Real)</th>
            <th scope="col" class="dias_restantes CP-sticky">Dias restantes</th>
            <th scope="col" class="dias_restantes_real CP-sticky bg-danger text-white">Dias restantes (Real)</th>
            <th scope="col" class="ultima_venta_rango CP-sticky">Ultima Venta (En rango)</th>
            <th scope="col" class="ultima_venta CP-sticky">Ultima Venta</th>
            <th scope="col" class="ultimo_lote CP-sticky">Ultimo Lote</th>
            <th scope="col" class="ultima_compra CP-sticky">Ultima Compra</th>
            <th scope="col" class="ultimo_proveedor CP-sticky">Ultimo Proveedor</th>
            <th scope="col" class="traslado_transito CP-sticky bg-warning">Traslado en transito</th>
            <th scope="col" class="pedir CP-sticky">Pedir</th>
            <th scope="col" class="pedir_real CP-sticky bg-danger text-white">Pedir (Real)</th>
            <th scope="col" class="CP-sticky">Acciones</th>
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;

    $traslado = Traslado_Transito();

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];

      $sql3 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$FInicial,$FFinal);
      $result3 = mysqli_query($connCPharma,$sql3);
      $row3 = $result3->fetch_assoc();
      $RangoDiasQuiebre = $row3['Cuenta'];

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      $sql2 = R7Q_Detalle_Articulo($IdArticulo);
      $result2 = sqlsrv_query($conn,$sql2);
      $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row2["CodigoInterno"];
      $CodigoBarra = $row2["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
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
      $Dolarizado = $row2["Dolarizado"];
      $CondicionExistencia = 'CON_EXISTENCIA';
      $UltimoLote = $row2["UltimoLote"];
      $UltimoPrecio = $row2["UltimoPrecio"];
      $UltimaVenta = $row2["UltimaVenta"];
      $Unico = FG_Producto_Unico($conn,$IdArticulo,$IdProveedor);
      $UltimoProveedorNombre = $row2["UltimoProveedorNombre"];
      $UltimaCompra = $row2["UltimaCompra"];

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $sql1 = R7Q_Integracion_Catalogo($IdArticulo,$FInicial,$FFinal);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

      $UnidadesVendidas = $row1["TotalUnidadesVendidas"];
      $UnidadesCompradas = $row1["TotalUnidadesCompradas"];
      $UltimaVentaRango = $row1["UltimaVentaRango"];
      $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
      $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);
      $CantidadPedido = FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia);
      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $VentaDiariaQuiebre = FG_Venta_Diaria($UnidadesVendidas,$RangoDiasQuiebre);
      $DiasRestantesQuiebre = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre);
      $CantidadPedidoQuiebre = FG_Cantidad_Pedido($VentaDiariaQuiebre,$DiasPedido,$Existencia);

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left" class="codigo">'.$CodigoArticulo.'</td>';
      echo '<td align="center" class="codigo_barra">'.$CodigoBarra.'</td>';
      echo
      '<td align="left" class="descripcion CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
      echo '<td class="producto_unico" align="center">'.$Unico.'</td>';

      echo '<td class="dolarizado" align="center">'.$Dolarizado.'</td>';
      echo '<td class="gravado" align="center">'.$Gravado.'</td>';
      echo '<td class="clasificacion" align="center">'.$clasificacion.'</td>';

      echo '<td class="precio" align="center">'.number_format($Precio,2,"," ,"." ).'</td>';

      if( ($Existencia==0) && (!is_null($UltimaVenta)) ){
          echo '<td class="ultimo_precio" align="center">'.number_format($UltimoPrecio,2,"," ,"." ).'</td>';
        }
        else{
          echo '<td class="ultimo_precio" align="center"> - </td>';
        }

      echo '<td class="existencia" align="center">'.intval($Existencia).'</td>';
      echo
      '<td align="center" class="unidades_vendidas CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinal.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesVendidas).
      '</a>
      </td>';
      echo
      '<td align="center" class="unidades_compradas CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinal.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesCompradas).
      '</a>
      </td>';
      echo '<td class="venta_diaria" align="center">'.round($VentaDiaria,2).'</td>';
      echo '<td align="center" class="venta_diaria_real bg-danger text-white">'.round($VentaDiariaQuiebre,2).'</td>';
      echo '<td class="dias_restantes" align="center">'.round($DiasRestantes,2).'</td>';
      echo '<td align="center" class="dias_restantes_real" bg-danger text-white">'.round($DiasRestantesQuiebre,2).'</td>';

      if(!is_null($UltimaVentaRango)){
        echo '<td class="ultima_venta_rango" align="center">'.$UltimaVentaRango->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultima_venta_rango" align="center"> - </td>';
      }

      if(!is_null($UltimaVenta)){
        echo '<td class="ultima_venta" align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultima_venta" align="center"> - </td>';
      }

      if(($UltimoLote)){
        echo '<td class="ultimo_lote" align="center">'.$UltimoLote->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultimo_lote" align="center"> - </td>';
      }

      if(!is_null($UltimaCompra)){
        echo '<td align="center" class="ultima_compra">'.$UltimaCompra->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center" class="ultima_compra"> - </td>';
      }

      if(!is_null($UltimoProveedorNombre)){
        echo '<td align="center" class="ultimo_proveedor">'.$UltimoProveedorNombre.'</td>';
      }
      else{
        echo '<td align="center" class="ultimo_proveedor"> - </td>';
      }

      $transito = in_array($CodigoBarra, $traslado) ? 'Si' : 'No';

      echo '<td class="text-center traslado_transito bg-warning">'.$transito.'</td>';



      echo '<td align="center" class="pedir">'.intval($CantidadPedido).'</td>';
      echo '<td align="center" class="pedir_real bg-danger text-white">'.round($CantidadPedidoQuiebre,2).'</td>';

      /*BOTON PARA AGREGAR A LA ORDEN DE COMPRA*/

      $sqlCuentaOrden = "SELECT COUNT(*) AS Cuenta FROM orden_compra_detalles WHERE id_articulo = '$IdArticulo' AND estatus = 'ACTIVO'";
      $resultCuentaOrden = mysqli_query($connCPharma,$sqlCuentaOrden);
      $rowCuentaOrden = $resultCuentaOrden->fetch_assoc();

      $sqlDetalleOrden = "SELECT codigo_orden FROM orden_compra_detalles WHERE id_articulo = '$IdArticulo' AND estatus = 'ACTIVO'";
      $resultDetalleOrden = mysqli_query($connCPharma,$sqlDetalleOrden);

      $flag = false;
      if($rowCuentaOrden['Cuenta']==0){
        $flag = true;
      }

      while($rowDetalleOrden = $resultDetalleOrden->fetch_assoc()){
        $codigo_orden = $rowDetalleOrden['codigo_orden'];
        $sqlOrden = "SELECT estado FROM orden_compras WHERE codigo = '$codigo_orden'";
        $resultOrden = mysqli_query($connCPharma,$sqlOrden);
        $rowOrden = $resultOrden->fetch_assoc();

        if( ($rowOrden['estado']=='INGRESADA')
          || ($rowOrden['estado']=='CERRADA')
          || ($rowOrden['estado']=='RECHAZADA')
          || ($rowOrden['estado']=='ANULADA')
        ){
          $flag = true;
        }
        else{
          $flag = false;
        }
      }

      echo'
      <td style="width:140px;">
        <form class="agregarOrdenCompra" action="/ordenCompraDetalle/create" method="PRE" style="display: block; width:100%;" target="_blank">
      ';
      echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
      echo'<input type="hidden" name="codigo_articulo" value="'.$CodigoArticulo.'">';
      echo'<input type="hidden" name="codigo_barra" value="'.$CodigoBarra.'">';
      echo'<input type="hidden" name="descripcion" value="'.$Descripcion.'">';
      echo'<input type="hidden" name="existencia_rpt" value="'.$Existencia.'">';
      echo'<input type="hidden" name="dias_restantes_rpt" value="'.$DiasRestantesQuiebre.'">';
      echo'<input type="hidden" name="origen_rpt" value="Catalogo de proveedor">';
      echo'<input type="hidden" name="rango_rpt" value="Del: '.$FInicialImp.' Al: '.$FFinalImp.'">';
      echo'<input type="hidden" name="unidades_vendidas" value="'.intval($UnidadesVendidas).'">';
      echo'<input type="hidden" name="venta_diaria_real" value="'.round($VentaDiariaQuiebre,2).'">';
      echo'<input type="hidden" name="pedir_real" value="'.round($CantidadPedidoQuiebre,2).'">';
      echo'<input type="hidden" name="dias_pedir" value="'.$DiasPedido.'">';
      echo '<input type="hidden" name="Reporte" value="SI">';
      echo'
          <button type="submit" role="button" class="btn btn-outline-success btn-sm" style="width:100%;">Agregar</button>
        </form>
        ';

        if($flag != true){
          echo'
          <br/>
          <form action="/ordenCompraDetalle/0" method="PRE" style="display: block; width:100%;" target="_blank">';
          echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
          echo'
            <button type="submit" role="button" class="btn btn-outline-danger btn-sm" style="width:100%;">En Transito</button>
          </form>
          ';
        }
      echo'
      </td>
      ';
      /*BOTON PARA AGREGAR A LA ORDEN DE COMPRA*/

      echo '</tr>';
      $contador++;
    }
    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R7Q_Lista_Proveedores
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function R7Q_Lista_Proveedores() {
    $sql = "
      SELECT
      GenPersona.Nombre AS nombre,
      ComProveedor.Id AS id
      FROM ComProveedor
      INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
      INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
      GROUP BY ComProveedor.Id, GenPersona.Nombre
      ORDER BY ComProveedor.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R7Q_Catalogo_Proveedor
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function R7Q_Catalogo_Proveedor($IdProveedor) {
    $sql = "
      SELECT
      InvArticulo.Id,
      InvArticulo.CodigoArticulo,
      (SELECT CodigoBarra
          FROM InvCodigoBarra
          WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
          AND InvCodigoBarra.EsPrincipal = 1) As CodigoBarra,
      InvArticulo.Descripcion,
      (SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
          FROM InvLoteAlmacen
          WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
          AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS Existencia,
      InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
      WHERE ComFactura.ComProveedorId = '$IdProveedor'
      GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,InvArticulo.FinConceptoImptoIdCompra
      ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R7Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R7Q_Detalle_Articulo($IdArticulo) {
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
        WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
--UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
  FROM VenCondicionVenta
  WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
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
--Ultimo Precio Sin Iva
      (SELECT TOP 1
      (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
      FROM VenVenta
      INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
      WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
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
  -- Ultima Compra (Fecha de ultima compra)
    (SELECT TOP 1
    CONVERT(DATE,ComFactura.FechaRegistro)
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaRegistro DESC) AS  UltimaCompra
--Tabla principal
    FROM InvArticulo
--Joins
    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
--Condicionales
    WHERE InvArticulo.Id = '$IdArticulo'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R7Q_Integracion_Catalogo
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R7Q_Integracion_Catalogo($IdArticulo,$FInicial,$FFinal) {
    $sql = "
      SELECT
    -- Id Articulo
      VenFacturaDetalle.InvArticuloId,
    --Veces Vendidas (En Rango)
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
    --Unidades Vendidas (En Rango)
      (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas,
    --Veces Devueltas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesDevueltas,
    --Unidades Devueltas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesDevueltas,
    --Total Veces Vendidas (En Rango)
      ((ISNULL(COUNT(*),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalVecesVendidas,
    --Total Unidades Vendidas (En Rango)
      (((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalUnidadesVendidas,
    --Veces Conpradas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesCompradas,
    --Unidades Conpradas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesCompradas,
    --Veces Reclamadas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesReclamadas,
    --Unidades Reclamadas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesReclamadas,
    --Total Veces Compradas (En Rango)
      ((ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalVecesCompradas,
    --Total de Unidades Compradas (En Rango)
      ((ISNULL((SELECT
      (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalUnidadesCompradas,
    -- SubTotal Venta (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
      FROM VenVentaDetalle
      INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
      WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
      AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) AS SubTotalVenta,
    --SubTotal Devolucion (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) as SubTotalDevolucion,
    --TotalVenta (En Rango)
      ((ISNULL((SELECT
      (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
      FROM VenVentaDetalle
      INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
      WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
      AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta,
    -- Ultima Venta (Rango)
      (SELECT TOP 1
      CONVERT(DATE,VenFactura.FechaDocumento)
      FROM VenFactura
      INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
      WHERE (VenFacturaDetalle.InvArticuloId = '$IdArticulo')
      AND (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      ORDER BY FechaDocumento DESC) AS UltimaVentaRango
    --Tabla Principal
      FROM VenFacturaDetalle
    --Joins
      INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
    --Condicionales
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      AND VenFacturaDetalle.InvArticuloId = '$IdArticulo'
    --Agrupamientos
      GROUP BY VenFacturaDetalle.InvArticuloId
    --Ordenamientos
      ORDER BY TotalUnidadesVendidas DESC
    ";
    return $sql;
  }
?>
