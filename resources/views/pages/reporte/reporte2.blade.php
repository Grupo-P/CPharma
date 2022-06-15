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

        campos = ['codigo', 'codigo_barra', 'descripcion', 'existencia', 'clasificacion', 'precio', 'gravado', 'utilidad_configurada', 'troquel', 'marca', 'categoria', 'subcategoria', 'dolarizado', 'tasa_actual', 'precio_ds', 'venta_diaria', 'dias_restantes', 'ultima_venta', 'ultimo_lote', 'unidad_minima', 'ultimo_conteo', 'ultimo_precio', 'transito', 'traslado_transito'];

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


        function mostrar_ocultar2(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos2 = ['proveedor', 'factura', 'fecha_documento', 'fecha_registro', 'dias_llegada', 'fecha_vencimiento', 'lote_fabricante', 'vida_util', 'dias_vencer', 'cantidad_recibida', 'saldo', 'costo_bruto', 'tasa_historico_documento', 'tasa_historico_registro', 'costo_bruto_documento', 'costo_bruto_registro', 'costo_divisa_documento', 'costo_divisa_registro', 'precio_lote', 'precio_lote_historico', 'precio_lote_ds', 'operador'];

        function mostrar_todas2(that) {
            if (that.checked) {
                for (var i = campos2.length - 1; i >= 0; i--) {
                    $('.' + campos2[i]).show();
                    $('[name='+campos2[i]+']').prop('checked', true);
                }
            } else {
                for (var i = campos2.length - 1; i >= 0; i--) {
                    $('.' + campos2[i]).hide();
                    $('[name='+campos2[i]+']').prop('checked', false);
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
            $descripcion = [];
            $codigo = [];
            $i = 0;

            $sql = "
                SELECT
                    InvArticulo.Id AS id,
                    InvArticulo.Descripcion AS descripcion,
                    (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra
                FROM InvArticulo
            ";

            $query = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $descripcion[$i]['id'] = $row['id'];
                $descripcion[$i]['label'] = mb_convert_encoding($row['descripcion'], 'UTF-8', 'UTF-8');
                $descripcion[$i]['value'] = mb_convert_encoding($row['descripcion'], 'UTF-8', 'UTF-8');

                $codigo[$i]['id'] = $row['id'];
                $codigo[$i]['label'] = mb_convert_encoding($row['codigo_barra'], 'UTF-8', 'UTF-8');
                $codigo[$i]['value'] = mb_convert_encoding($row['codigo_barra'], 'UTF-8', 'UTF-8');

                $i++;
            }
        @endphp



        $(document).ready(function () {
            $('#descripcion').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#id_descripcion').val(ui.item.id);
                }
            });

            $('#barras').autocomplete({
                source: {!! json_encode($codigo) !!},
                autoFocus: true,
                minLength: 5,
                select: function (event, ui) {
                    $('#id_barras').val(ui.item.id);
                }
            });
        });
  </script>
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Historico de productos
	</h1>
	<hr class="row align-items-start col-12">
  <?php

    $ArtJson = "";
    $CodJson = "";

    if (isset($_GET['SEDE'])) {
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

  	if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      R2_Historico_Producto($_GET['SEDE'],$_GET['Id']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Historico de productos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	}
  	else {
      $InicioCarga = new DateTime("now");

  		echo '
  		<form autocomplete="off" action="" target="_blank">
  	    <div class="autocomplete" style="width:90%;">
          <input type="text" id="descripcion" name="Descrip" class="form-control" placeholder="Ingrese el nombre del articulo...">
  	      <input name="Id" id="id_descripcion" type="hidden">
  	    </div>
        <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
  	    <input type="submit" value="Buscar" class="btn btn-outline-success">
      </form>
      <br/>
      <form autocomplete="off" action="" target="_blank">
        <div class="autocomplete" style="width:90%;">
          <input type="text" id="barras" name="CodBar" class="form-control" placeholder="Ingrese el codigo de barra del articulo...">
          <input name="Id" id="id_barras" type="hidden">
        </div>
        <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
        <input type="submit" value="Buscar" class="btn btn-outline-success">
    	</form>
    	';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	}
  ?>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R2_Historico_Producto
    FUNCION: Armar una tabla del historico de compra del articulo
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R2_Historico_Producto($SedeConnection,$IdArticulo) {

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = R2Q_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $ResultCPharma = mysqli_query($connCPharma,"SELECT * FROM unidads WHERE id_articulo = '$IdArticulo'");
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $unidadminima = $RowCPharma['divisor']." ".$RowCPharma['unidad_minima'];
    $unidadminima = ($unidadminima)?$unidadminima:"-";

    $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
    $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $clasificacion = $RowCPharma['clasificacion'];
    $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

    $sql2 = R2Q_Historico_Articulo($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);

    $CodigoArticulo = $row["CodigoInterno"];
    $CodigoBarra = $row["CodigoBarra"];
    $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
    $Existencia = $row["Existencia"];
    $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
    $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
    $IsTroquelado = $row["Troquelado"];
    $IsIVA = $row["Impuesto"];
    $UtilidadArticulo = $row["UtilidadArticulo"];
    $UtilidadCategoria = $row["UtilidadCategoria"];
    $TroquelAlmacen1 = $row["TroquelAlmacen1"];
    $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
    $TroquelAlmacen2 = $row["TroquelAlmacen2"];
    $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
    $PrecioCompraBruto = $row["PrecioCompraBruto"];
    $Dolarizado = $row["Dolarizado"];
    $CondicionExistencia = 'CON_EXISTENCIA';
    $UltimaVenta = $row["UltimaVenta"];
    $UltimoLote = $row["UltimoLote"];
    $UltimoPrecio = $row["UltimoPrecio"];
    $Marca = $row["Marca"];

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
    $Utilidad = (1 - $Utilidad)*100;


    $fechaActual = new DateTime('now');
    $fechaActual = date_format($fechaActual,'Y-m-d');

    $sqlUltimoConteo = "
    SELECT fecha_conteo FROM inventario_detalles WHERE id_articulo = '$IdArticulo' ORDER BY fecha_conteo DESC LIMIT 1;
   ";

    $ResultCPharma = mysqli_query($connCPharma,$sqlUltimoConteo);
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $ultimoConteo = $RowCPharma['fecha_conteo'];

    $sqlCategorizacion = "
    SELECT
    categorias.nombre as categoria,
    subcategorias.nombre as subcategoria
    FROM categorizacions
    INNER JOIN categorias ON categorias.codigo = codigo_categoria
    INNER JOIN subcategorias ON subcategorias.codigo = codigo_subcategoria
    WHERE id_articulo = '$IdArticulo';
   ";

    $ResultCategorizacion = mysqli_query($connCPharma,$sqlCategorizacion);
    $RowCategorizacion = mysqli_fetch_assoc($ResultCategorizacion);
    $categoria = $RowCategorizacion['categoria'];
    $subcategoria = $RowCategorizacion['subcategoria'];

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
                <input type="checkbox" onclick="mostrar_ocultar(this, \'clasificacion\')" name="clasificacion" checked>
                Clasificación
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'precio\')" name="precio" checked>
                Precio
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'gravado\')" name="gravado" checked>
                Gravado
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'utilidad_configurada\')" name="utilidad_configurada" checked>
                Utilidad configurada
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'troquel\')" name="troquel" checked>
                Troquel
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'marca\')" name="marca" checked>
                Marca
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'categoria\')" name="categoria" checked>
                Categoría
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'subcategoria\')" name="subcategoria" checked>
                Subcategoría
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'dolarizado\')" name="dolarizado" checked>
                Dolarizado
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'tasa_actual\')" name="tasa_actual" checked>
                Tasa actual
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'precio_ds\')" name="precio_ds" checked>
                Precio $
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'venta_diaria\')" name="venta_diaria" checked>
                Venta diaria
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_restantes\')" name="dias_restantes" checked>
                Días restantes
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
                <input type="checkbox" onclick="mostrar_ocultar(this, \'unidad_minima\')" name="unidad_minima" checked>
                Unidad mínima
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_conteo\')" name="ultimo_conteo" checked>
                Último conteo
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_precio\')" name="ultimo_precio" checked>
                Último precio
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, \'transito\')" name="transito" checked>
                Transito
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
    </div>

    <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th class="codigo" scope="col">Codigo</th>
          <th class="codigo_barra" scope="col">Codigo de barra</td>
          <th class="descripcion" scope="col">Descripcion</td>
          <th class="existencia" scope="col">Existencia</td>
          <th class="clasificacion" scope="col">Clasificacion</td>
          <th class="precio" scope="col">Precio</br>(Con IVA) '.SigVe.'</td>
          <th class="gravado" scope="col">Gravado?</td>
          <th class="utilidad_configurada" scope="col">Utilidad Configurada</td>
          <th class="troquel" scope="col">Troquel</td>
          <th class="marca" scope="col">Marca</td>
          <th class="categoria" scope="col">Categoria</td>
          <th class="subcategoria" scope="col">Subcategoria</td>
          <th class="dolarizado" scope="col">Dolarizado?</td>
          <th class="tasa_actual" scope="col">Tasa actual '.SigVe.'</td>
          <th class="precio_ds" scope="col">Precio en divisa</br>(Con IVA) '.SigDolar.'</td>
          <th scope="col" class="venta_diaria text-white">Venta diaria 15</th>
          <th scope="col" class="dias_restantes text-white">Días restantes 15</th>
          <th class="ultima_venta" scope="col">Ultima Venta</th>
          <th class="ultimo_lote" scope="col" class="text-white">Ultimo Lote</th>
          <th class="unidad_minima" scope="col">Unidad Minima</th>
          <th class="ultimo_conteo" scope="col">Ultimo Conteo</th>
          <th class="ultimo_precio" scope="col">Ultimo Precio (Sin IVA) '.SigVe.'</th>
          <th scope="col" class="transito text-white">Transito</th>
          <th scope="col" class="traslado_transito bg-warning text-white">Traslado en transito</th>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td class="codigo">'.$CodigoArticulo.'</td>';
    echo '<td align="center" class="codigo_barra">'.$CodigoBarra.'</td>';
    echo
      '<td align="left" class="CP-barrido descripcion">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
    echo '<td class="existencia" align="center">'.intval($Existencia).'</td>';
    echo '<td class="clasificacion" align="center"> '.$clasificacion.'</td>';
    echo '<td class="precio" align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
    echo '<td class="gravado" align="center">'.$Gravado.'</td>';
    echo '<td class="utilidad_configurada" align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

    if($TroquelAlmacen1!=NULL){
      echo '<td class="troquel" align="center">'.number_format($TroquelAlmacen1,2,"," ,"." ).'</td>';
    }
    else if($TroquelAlmacen2!=NULL){
      echo '<td class="troquel" align="center">'.number_format($TroquelAlmacen2,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td class="troquel" align="center"> - </td>';
    }

    echo '<td class="marca" align="center">'.$Marca.'</td>';

    echo '<td class="categoria" align="center">'.$categoria.'</td>';

    echo '<td class="subcategoria" align="center">'.$subcategoria.'</td>';

    echo '<td class="dolarizado" align="center">'.$Dolarizado.'</td>';

    /*TASA DOLAR VENTA*/
    $Tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');

    $Tasa = ($Tasa) ? $Tasa : 0;

    if($TasaActual!=0){
      echo '<td class="tasa_actual" align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      $PrecioDolar = $Precio/$Tasa;
      echo '<td class="precio_ds" align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td class="tasa_actual" align="center">0,00</td>';
      echo '<td class="precio_ds" align="center">0,00</td>';
    }

    $fechaInicioDiasCero = date_modify(date_create(), '-15day');
    $fechaInicioDiasCero = date_format($fechaInicioDiasCero, 'Y-m-d');

    $sql3 = R31Q_Total_Venta($IdArticulo, $fechaInicioDiasCero, date_create()->format('Y-m-d'));

    $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$fechaInicioDiasCero,date_create()->format('Y-m-d'));
    $result22 = mysqli_query($connCPharma,$sql2);
    $row2 = $result22->fetch_assoc();
    $RangoDiasQuiebre = $row2['Cuenta'];

    $result3 = sqlsrv_query($conn,$sql3);
    $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

    $VentaDiariaQuiebre = FG_Venta_Diaria($row3['TotalUnidadesVendidas'],$RangoDiasQuiebre);
    $DiasRestantesQuiebre = FG_Dias_Restantes(intval($Existencia),$VentaDiariaQuiebre);

    echo '<td align="center" class="venta_diaria">'.$VentaDiariaQuiebre.'</td>';
    echo '<td align="center" class="dias_restantes">'.$DiasRestantesQuiebre.'</td>';

    if(!is_null($UltimaVenta)){
      echo '<td class="ultima_venta" align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
    }
    else{
      echo '<td class="ultima_venta" align="center"> - </td>';
    }

    if(!is_null($UltimoLote)){
      echo '<td align="center" class="ultimo_lote">'.$UltimoLote->format('d-m-Y').'</td>';
    }
    else{
      echo '<td align="center" class="ultimo_lote"> - </td>';
    }

    echo '<td class="unidad_minima">'.$unidadminima.'</td>';

    echo '<td class="ultimo_conteo" align="center">'.$ultimoConteo.'</td>';

    if( ($Existencia==0) && (!is_null($UltimaVenta)) ){
      echo '<td class="ultimo_precio" align="center">'.number_format($UltimoPrecio,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td class="ultimo_precio" align="center"> - </td>';
    }

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

    if($flag != true){
      echo'
      <td class="transito  text-white" align="center">
          <form action="/ordenCompraDetalle/0" method="PRE" style="display: block; width:100%;" target="_blank">';
          echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
          echo'
            <button type="submit" role="button" class="btn btn-outline-danger btn-sm" style="width:100%;">En Transito</button>
          </form>
      </td>
      ';
    } else {
        echo '<td class="transito  text-white">-</td>';
    }

    $traslado = Traslado_Transito($CodigoBarra);
    $transito = in_array($CodigoBarra, $traslado) ? 'Si' : 'No';
    echo '<td class="text-center traslado_transito bg-warning">'.$transito.'</td>';

    echo '
      </tr>
      </tbody>
    </table>';

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos_2"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>';

    echo '
        <div class="modal fade" id="ver_campos_2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'proveedor\')" name="proveedor" checked>
                    Proveedor
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'factura\')" name="factura" checked>
                    Número factura
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'fecha_documento\')" name="fecha_documento" checked>
                    Fecha documento
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'fecha_registro\')" name="fecha_registro" checked>
                    Fecha registro
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'dias_llegada\')" name="dias_llegada" checked>
                    Días llegada
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'fecha_vencimiento\')" name="fecha_vencimiento" checked>
                    Fecha vencimiento
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'lote_fabricante\')" name="lote_fabricante" checked>
                    Lote fabricante
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'vida_util\')" name="vida_util" checked>
                    Vida útil
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'dias_vencer\')" name="dias_vencer" checked>
                    Días para vencer
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'cantidad_recibida\')" name="cantidad_recibida" checked>
                    Cantidad recibida
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'saldo\')" name="saldo" checked>
                    Saldo
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_bruto\')" name="costo_bruto" checked>
                    Costo bruto (Sin IVA) '.SigVe.'
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'tasa_historico_documento\')" name="tasa_historico_documento" checked>
                    Tasa en histórico '.SigVe.' (fecha documento)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'tasa_historico_registro\')" name="tasa_historico_registro" checked>
                    Tasa en histórico '.SigVe.' (fecha registro)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_bruto_registro\')" name="costo_bruto_registro" checked>
                    Costo bruto HOY (Sin IVA) '.SigVe.' (fecha registro)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_bruto_documento\')" name="costo_bruto_documento" checked>
                    Costo bruto HOY (Sin IVA) '.SigVe.' (fecha documento)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_divisa_registro\')" name="costo_divisa_registro" checked>
                    Costo en divisa (Sin IVA) '.SigDolar.' $ (fecha registro)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_divista_documento\')" name="costo_divista_documento" checked>
                    Costo en divisa (Sin IVA) '.SigDolar.' $ (fecha documento)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'precio_lote\')" name="precio_lote" checked>
                    Precio lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'precio_lote_historico\')" name="precio_lote_historico" checked>
                    Precio lote histórico
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'precio_lote_ds\')" name="precio_lote_ds" checked>
                    Precio lote $
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'operador\')" name="operador" checked>
                    Operador
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_todas2(this)" name="Marcar todas" checked>
                    Marcar todas
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="proveedor CP-sticky">Proveedor</th>
            <th scope="col" class="factura CP-sticky">N° Factura</th>
            <th scope="col" class="fecha_documento CP-sticky">Fecha de documento</th>
            <th scope="col" class="fecha_registro CP-sticky">Fecha de registro</th>
            <th scope="col" class="dias_llegada CP-sticky  text-dark">Días llegada</th>
            <th scope="col" class="fecha_vencimiento CP-sticky">Fecha de vencimiento</th>
            <th scope="col" class="lote_fabricante CP-sticky  text-dark">Lote fabriante</th>
            <th scope="col" class="vida_util CP-sticky">Vida util<br>(Dias)</th>
            <th scope="col" class="dias_vencer CP-sticky">Dias para vencer<br>(Dias)</th>
            <th scope="col" class="cantidad_recibida CP-sticky">Cantidad recibida</th>
            <th scope="col" class="saldo CP-sticky  text-dark">Saldo</th>
            <th scope="col" class="costo_bruto CP-sticky">Costo bruto</br>(Sin IVA) '.SigVe.'</th>
            <th scope="col" class="tasa_historico_documento CP-sticky">Tasa en historico '.SigVe.'</br>(fecha documento)</th>
            <th scope="col" class="tasa_historico_registro CP-sticky">Tasa en historico '.SigVe.'</br>(fecha registro)</th>
            <th scope="col" class="costo_bruto_documento CP-sticky">Costo bruto</br>HOY (Sin IVA) '.SigVe.'</br>(fecha documento)</th>
            <th scope="col" class="costo_bruto_registro CP-sticky">Costo bruto</br>HOY (Sin IVA) '.SigVe.'</br>(fecha registro)</th>
            <th scope="col" class="costo_divisa_documento CP-sticky">Costo en divisa</br>(Sin IVA) '.SigDolar.'</br>(fecha documento)</th>
            <th scope="col" class="costo_divisa_registro CP-sticky bg-danger text-white">Costo en divisa</br>(Sin IVA) '.SigDolar.'</br>(fecha registro)</th>
            <th scope="col" class="precio_lote CP-sticky">Precio Lote en '.SigVe.'</th>
            <th scope="col" class="precio_lote_historico CP-sticky">Precio Lote en '.SigVe.'<br>Historico</th>
            <th scope="col" class="precio_lote_ds CP-sticky">Precio Lote en '.SigDolar.'</th>
            <th scope="col" class="operador CP-sticky">Operador</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    $Saldo = 0;

    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {

        $fechaDocumento = $row2["FechaDocumento"];
        $fechaDocumentoMostrar = $fechaDocumento->format('d-m-Y');
        $fechaDocumentoLink = $fechaDocumento->format('Y-m-d');

        $loteFabricante = $row2['LoteFabricante'];

        $fechaVencimiento = ($row2["FechaVencimiento"]!=NULL)?$row2["FechaVencimiento"]->format('d-m-Y'):'-';
        $vidaUtil = ($row2['VidaUtil']!=NULL)?$row2['VidaUtil']:'-';
        $diasVencer = ($row2['DiasVecer']!=NULL)?$row2['DiasVecer']:'-';

        $diasLlegada = ($row2['FechaRegistro']) ? date_diff($row2['FechaRegistro'], date_create())->format('%a') : '';

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo
        '<td align="left" class="proveedor CP-barrido">
        <a href="/reporte7?Nombre='.FG_Limpiar_Texto($row2["Nombre"]).'&Id='.$row2["Id"].'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .FG_Limpiar_Texto($row2["Nombre"]).
        '</a>
        </td>';

        echo '<td class="factura" align="center">
        <a class="CP-barrido" href="/reporte30?SEDE='.$SedeConnection.'&IdFact='.$row2["idFactura"].'&IdProv='.$row2["Id"].'&NombreProv='.FG_Limpiar_Texto($row2["Nombre"]).'" style="text-decoration: none; color: black" target="_blank">'
          .$row2["numero"].
        '</a>
        </td>';

        echo
        '<td align="center" class="fecha_documento CP-barrido">
        <a href="/reporte12?fechaInicio='.$fechaDocumentoLink.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'&CodBar=&IdCB=&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$fechaDocumentoMostrar.
        '</a>
        </td>';

        echo '<td class="fecha_registro" align="center">'.$row2["FechaRegistro"]->format('d-m-Y').'</td>';
        echo '<td class="dias_llegada" align="center" class="">'.$diasLlegada.'</td>';
        echo '<td class="fecha_vencimiento" align="center">'.$fechaVencimiento.'</td>';
        echo '<td class="lote_fabricante" align="center" class="">'.$loteFabricante.'</td>';
        echo '<td class="vida_util" align="center">'.$vidaUtil.'</td>';

        echo '<td class="dias_vencer" align="center">'.$diasVencer.'</td>';

       echo
        '<td align="center" class="cantidad_recibida CP-barrido">
        <a href="/reporte6?pedido=15&fechaInicio='.$fechaDocumentoLink.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&flag=BsqDescrip&Descrip='.$Descripcion.'&IdD='.$IdArticulo.'&CodBar=&IdCB=" style="text-decoration: none; color: black;" target="_blank">'
          .intval($row2['CantidadRecibidaFactura']).
        '</a>
        </td>';

        $Saldo = $Saldo + $row2['CantidadRecibidaFactura'];

        echo '<td class="saldo" align="center" class="">'.$Saldo.'</td>';

        echo '<td class="costo_bruto" align="center">'.number_format($row2["M_PrecioCompraBruto"],2,"," ,"." ).'</td>';

        $FechaHistDoc = $row2["FechaDocumento"]->format('Y-m-d');
        $TasaDoc = FG_Tasa_Fecha($connCPharma,$FechaHistDoc);

        $FechaHistFR = $row2["FechaRegistro"]->format('Y-m-d');
        $TasaFR = FG_Tasa_Fecha($connCPharma,$FechaHistFR);

        if( ($TasaDoc != 0) && ($TasaFR != 0) ) {

          echo '<td class="tasa_historico_documento" align="center">'.number_format($TasaDoc,2,"," ,"." ).'</td>';
          echo '<td class="tasa_historico_registro" align="center">'.number_format($TasaFR,2,"," ,"." ).'</td>';

          $CostoDivisaDoc = $row2["M_PrecioCompraBruto"]/$TasaDoc;
          $CostoDivisaFR = $row2["M_PrecioCompraBruto"]/$TasaFR;

          if($TasaActual!=0){
            $CostoBrutoHoyDoc = $CostoDivisaDoc*$TasaActual;
            echo '<td class="costo_bruto_documento" align="center">'.number_format($CostoBrutoHoyDoc,2,"," ,"." ).'</td>';

            $CostoBrutoHoyFR = $CostoDivisaFR*$TasaActual;
            echo '<td class="costo_bruto_registro" align="center">'.number_format($CostoBrutoHoyFR,2,"," ,"." ).'</td>';
          }
          else{
            echo '<td class="costo_bruto_documento" align="center">0,00</td>';
            echo '<td class="costo_bruto_registro" align="center">0,00</td>';
          }

          echo '<td class="costo_divisa_documento" align="center">'.number_format($CostoDivisaDoc,2,"," ,"." ).'</td>';
          echo '<td class="costo_divisa_registro bg-danger text-white" align="center">'.number_format($CostoDivisaFR,2,"," ,"." ).'</td>';

          $preciolote = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$row2["M_PrecioCompraBruto"]);
          echo '<td class="precio_lote" align="center">'.number_format($preciolote,2,"," ,"." ).'</td>';

          if($TasaActual!=0){
            $preciolotehist = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$CostoBrutoHoyFR);
            echo '<td class="precio_lote_historico" align="center">'.number_format($preciolotehist,2,"," ,"." ).'</td>';

            $preciolotediv = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$CostoDivisaFR);
            echo '<td class="precio_lote_ds" align="center">'.number_format($preciolotediv,2,"," ,"." ).'</td>';
          }
          else{
            echo '<td class="precio_lote_historico" align="center">0,00</td>';
            echo '<td class="precio_lote_ds" align="center">0,00</td>';
          }

        }
        else{
          echo '<td class="tasa_historico_documento" align="center">0,00</td>';
          echo '<td class="tasa_historico_registro" align="center">0,00</td>';
          echo '<td class="costo_bruto_documento" align="center">0,00</td>';
          echo '<td class="costo_bruto_registro" align="center">0,00</td>';
          echo '<td class="costo_divisa_documento" align="center">0,00</td>';
          echo '<td class="costo_divisa_registro" align="center">0,00</td>';
          echo '<td class="precio_lote" align="center">0,00</td>';
          echo '<td class="precio_lote_historico" align="center">0,00</td>';
          echo '<td class="precio_lote_ds" align="center">0,00</td>';
        }

        echo '<td class="operador" align="center">'.$row2["operador"].'</td>';

        echo '</tr>';
      $contador++;
      }
      echo '
      </tbody>
    </table>';
    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }

  /**********************************************************************************/
  /*
    TITULO: R2Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Lista_Articulos() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY InvArticulo.Descripcion ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R2Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Lista_Articulos_CodBarra() {
    $sql = "
      SELECT
      (SELECT CodigoBarra
      FROM InvCodigoBarra
      WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
      AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY CodigoBarra ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R2Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Detalle_Articulo($IdArticulo) {
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
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
--Tabla principal
    FROM InvArticulo
--Joins
    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE InvArticulo.Id = '$IdArticulo'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R2Q_Historico_Articulo
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Historico_Articulo($IdArticulo) {
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
      ComFactura.Id as idFactura,
      ComFacturaDetalle.NumeroLoteFabricante AS LoteFabricante
      FROM InvArticulo
      INNER JOIN ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE InvArticulo.Id = '$IdArticulo'
      ORDER BY ComFactura.FechaRegistro DESC
    ";
    return $sql;
  }

  function R31Q_Total_Venta($IdArticulo,$FInicial,$FFinal) {
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
      AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta
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
      ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }
?>
