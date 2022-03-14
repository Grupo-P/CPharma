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

  <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['codigo', 'codigo_barra', 'descripcion', 'existencia', 'clasificacion', 'precio', 'gravado', 'utilidad', 'troquel', 'marca', 'categoria', 'subcategoria', 'dolarizado', 'tasa_actual', 'precio_ds', 'ultima_venta', 'unidad_minima', 'ultimo_conteo', 'ultimo_precio'];

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

        campos2 = ['origen', 'detalle', 'numero_referencia', 'fecha_creacion_lote', 'fecha_vencimiento', 'fecha_entrada', 'numero_lote', 'almacen', 'existencia', 'costo_bruto_total', 'costo_unitario_bruto', 'precio_troquelado_bruto', 'tasa_mercado', 'costo_unitario_bruto_ds', 'responsable', 'lote_fabricante'];

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
  </script>
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Analítico de precios
	</h1>
	<hr class="row align-items-start col-12">
  <?php
  	include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";
    $CodJson = "";

    if (isset($_GET['SEDE'])) {
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

  	if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      R2_Historico_Producto($_GET['SEDE'],$_GET['Id']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Analitico de precios');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	}
  	else {
      $InicioCarga = new DateTime("now");

      $sql = R2Q_Lista_Articulos();
      $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

      $sql1 = R2Q_Lista_Articulos_CodBarra();
      $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

  		echo '
  		<form autocomplete="off" action="" target="_blank">
  	    <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
  	      <input id="myId" name="Id" type="hidden">
  	    </div>
        <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
  	    <input type="submit" value="Buscar" class="btn btn-outline-success">
      </form>
      <br/>
      <form autocomplete="off" action="" target="_blank">
        <div class="autocomplete" style="width:90%;">
          <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
          <input id="myIdCB" name="Id" type="hidden">
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
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsCB = eval(<?php echo $CodJson ?>);
      autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myIdCB"), ArrJsCB);
    </script>
  <?php
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

    $sql = R10Q_Detalle_Articulo($IdArticulo);
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

    $sql2 = R10Q_Analitico_Productos($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);

    $sql3 = R10Q_Analitico_Productos_SE($IdArticulo);
    $result3 = sqlsrv_query($conn,$sql3);

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
    $UltimoPrecio = $row["UltimoPrecio"];
    $Marca = $row["Marca"];

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));
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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'utilidad\')" name="utilidad" checked>
                    Utilidad
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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta\')" name="ultima_venta" checked>
                    Última venta
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
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'origen\')" name="origen" checked>
                    Origen
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'detalle\')" name="detalle" checked>
                    Detalle
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'numero_referencia\')" name="numero_referencia" checked>
                    Número referencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'fecha_creacion_lote\')" name="fecha_creacion_lote" checked>
                    Fecha de creación lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'fecha_vencimiento\')" name="fecha_vencimiento" checked>
                    Fecha de vencimiento
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'numero_lote\')" name="numero_lote" checked>
                    Número lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'almacen\')" name="almacen" checked>
                    Almacen
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_bruto_total\')" name="costo_bruto_total" checked>
                    Costo bruto total
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_unitario_bruto\')" name="costo_unitario_bruto" checked>
                    Costo unitario bruto
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'precio_troquelado_bruto\')" name="precio_troquelado_bruto" checked>
                    Precio troquelado bruto
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'tasa_mercado\')" name="tasa_mercado" checked>
                    Tasa mercado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'costo_unitario_bruto\')" name="costo_unitario_bruto" checked>
                    Costo unitario bruto
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar2(this, \'responsable\')" name="responsable" checked>
                    Responsable
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
          <th class="utilidad" scope="col">Utilidad Configurada</td>
          <th class="troquel" scope="col">Troquel</td>
          <th class="marca" scope="col">Marca</td>
          <th class="categoria" scope="col">Categoria</td>
          <th class="subcategoria" scope="col">Subcategoria</td>
          <th class="dolarizado" scope="col">Dolarizado?</td>
          <th class="tasa_actual" scope="col">Tasa actual '.SigVe.'</td>
          <th class="precio_ds" scope="col">Precio en divisa</br>(Con IVA) '.SigDolar.'</td>
          <th class="ultima_venta" scope="col">Ultima Venta</th>
          <th class="unidad_minima" scope="col">Unidad Minima</th>
          <th class="ultimo_conteo" scope="col">Ultimo Conteo</th>
          <th class="ultimo_precio" scope="col">Ultimo Precio (Sin IVA) '.SigVe.'</th>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td class="codigo">'.$CodigoArticulo.'</td>';
    echo '<td align="center" class="codigo_barra">'.$CodigoBarra.'</td>';

    echo '
      <td align="left" class="descripcion CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
              .$Descripcion
          .'</a>
      </td>
    ';

    echo '<td class="existencia" align="center">'.intval($Existencia).'</td>';
    echo '<td class="clasificacion" align="center"> '.$clasificacion.'</td>';
    echo '<td class="precio" align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
    echo '<td class="gravado" align="center">'.$Gravado.'</td>';
    echo '<td class="utilidad" align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

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

    if($TasaActual!=0){
      echo '<td class="tasa_actual" align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      $PrecioDolar = $Precio/$TasaActual;
      echo '<td class="tasa_actual" align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td class="tasa_actual" align="center">0,00</td>';
      echo '<td class="tasa_actual" align="center">0,00</td>';
    }

    if(!is_null($UltimaVenta)){
      echo '<td class="ultima_venta" align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
    }
    else{
      echo '<td class="ultima_venta" align="center"> - </td>';
    }

    echo '<td class="unidad_minima">'.$unidadminima.'</td>';

    echo '<td class="ultimo_conteo" align="center">'.$ultimoConteo.'</td>';

    if( ($Existencia==0) && (!is_null($UltimaVenta)) ){
      echo '<td class="ultimo_precio" align="center">'.number_format($UltimoPrecio,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td class="ultimo_precio" align="center"> - </td>';
    }

    echo '
      </tr>
      </tbody>
    </table>';

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos_2"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>';

    //Tabla Lotes con exitencia
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="origen CP-sticky">Origen</th>
            <th scope="col" class="detalle CP-sticky">Detalle</th>
            <th scope="col" class="numero_referencia CP-sticky">Numero<br>Referencia</th>
            <th scope="col" class="fecha_creacion_lote CP-sticky">Fecha de Creacion<br>Lote</th>
            <th scope="col" class="fecha_vencimiento CP-sticky">Fecha de<br>Vencimiento</th>
            <th scope="col" class="fecha_entrada CP-sticky">Fecha de<br>Entrada</th>
            <th scope="col" class="numero_lote CP-sticky">Numero Lote</th>
            <th scope="col" class="lote_fabricante CP-sticky">Lote Fabricante</th>
            <th scope="col" class="almacen CP-sticky">Almacen</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="costo_bruto_total CP-sticky">Costo Bruto Total</br>(Sin IVA) '.SigVe.'</th>
            <th scope="col" class="costo_unitario_bruto CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="precio_troquelado_bruto CP-sticky">Precio Troquelado </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="tasa_mercado CP-sticky">Tasa Mercado</th>
            <th scope="col" class="costo_unitario_bruto_ds CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="responsable CP-sticky">Responsable</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    $IdLotePivote = 0;
    $LoteAlmacenIdPivorte = 0;

    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {

      if( ($row2["InvLoteId"]!=$IdLotePivote) ||
          ($row2["InvLoteId"]==$IdLotePivote && $row2["LoteAlmacenId"]!=$LoteAlmacenIdPivorte)
       ){

        $fechaVencimiento = ($row2["FechaVencimiento"]!=NULL)?$row2["FechaVencimiento"]->format('d-m-Y'):'-';
        $fechaEntrada = ($row2["FechaEntrada"]!=NULL)?$row2["FechaEntrada"]->format('d-m-Y'):'-';

        $IdLotePivote = $row2["InvLoteId"];
        $LoteAlmacenIdPivorte = $row2["LoteAlmacenId"];

        echo '
          <td align="center"><strong>'.intval($contador).'</strong></td>
          <td class="origen" align="center">'.FG_Limpiar_Texto($row2["Origen"]).'</td>
        ';

        if($row2["Origen"]=="COMPRA"){

          $sql4 = R10Q_Caso_Compra($row2["InvLoteId"],$IdArticulo,$row2["LoteAlmacenId"]);

          try {
            $result4 = sqlsrv_query($conn,$sql4);
            $row4 = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC);

            echo '
              <td class="detalle" align="center">'.FG_Limpiar_Texto($row4["Proveedor"]).'</td>
              <td class="numero_referencia" align="center">'.($row4["NumeroFactura"]).'</td>
            ';

          } catch (Exception $e) {

            echo '
              <td class="detalle" align="center">'.$row2["InvLoteId"].'|'.$IdArticulo.'|'.$row2["LoteAlmacenId"].'</td>
              <td class="numero_referencia" align="center">*</td>
            ';

          }

        }else{
           echo '
            <td class="detalle" align="center">'.FG_Limpiar_Texto($row2["Causa"]).'</td>
            <td class="numero_referencia" align="center">'.($row2["NumeroReferencia"]).'</td>
          ';
        }

        $TasaFecha = FG_Tasa_Fecha($connCPharma,$row2["FechaCreacionLote"]->format("Y-m-d"));
        if(!isset($TasaFecha)){
           $TasaFecha = 0;
        }

        echo '
          <td class="fecha_creacion_lote" align="center">'.($row2["FechaCreacionLote"]->format('d-m-Y')).'</td>
          <td class="fecha_vencimiento" align="center">'.($fechaVencimiento).'</td>
          <td class="fecha_entrada" align="center">'.($fechaEntrada).'</td>
          <td class="numero_lote" align="center">'.($row2["NumeroLote"]).'</td>
          <td class="lote_fabricante" align="center">'.($row2["LoteFabricante"]).'</td>
          <td class="almacen" align="center">'.($row2["Almacen"]).'</td>
          <td class="existencia" align="center">'.intval($row2["Existencia"]).'</td>
          <td class="costo_bruto_total" align="center">'.number_format($row2["CostoTotal"],2,"," ,"." ).'</td>
          <td class="costo_unitario_bruto" align="center">'.number_format($row2["CostoUnitario"],2,"," ,"." ).'</td>
          <td class="precio_troquelado_bruto" align="center">'.number_format($row2["PrecioTroquelado"],2,"," ,"." ).'</td>
          <td class="tasa_mercado" align="center">'.number_format($TasaFecha,2,"," ,"." ).'</td>
        ';

        if($TasaFecha!=0){
          $CostoDivisa = $row2["CostoUnitario"] / $TasaFecha;
          echo '
            <td class="costo_unitario_bruto_ds" align="center">'.number_format($CostoDivisa,2,"," ,"." ).'</td>
          ';
        }else{
          echo '
            <td class="costo_unitario_bruto_ds" align="center">0.00</td>
          ';
        }

        echo '
            <td class="responsable" align="center">'.FG_Limpiar_Texto($row2["Responsable"]).'</td>
          ';

        echo '</tr>';
        $contador++;
      }
    }
      echo '
      </tbody>
    </table>';
    //Tabla Lotes con exitencia
  /******************************************************************/
    //Tabla Lotes SIN exitencia
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="origen CP-sticky">Origen</th>
            <th scope="col" class="detalle CP-sticky">Detalle</th>
            <th scope="col" class="numero_referencia CP-sticky">Numero<br>Referencia</th>
            <th scope="col" class="fecha_creacion_lote CP-sticky">Fecha de Creacion<br>Lote</th>
            <th scope="col" class="fecha_vencimiento CP-sticky">Fecha de<br>Vencimiento</th>
            <th scope="col" class="fecha_entrada CP-sticky">Fecha de<br>Entrada</th>
            <th scope="col" class="numero_lote CP-sticky">Numero Lote</th>
            <th scope="col" class="lote_fabricante CP-sticky">Lote Fabricante</th>
            <th scope="col" class="almacen CP-sticky">Almacen</th>
            <th scope="col" class="existencia CP-sticky">Existencia</th>
            <th scope="col" class="costo_bruto_total CP-sticky">Costo Bruto Total</br>(Sin IVA) '.SigVe.'</th>
            <th scope="col" class="costo_unitario_bruto CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="precio_troquelado_bruto CP-sticky">Precio Troquelado </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="tasa_mercado CP-sticky">Tasa Mercado</th>
            <th scope="col" class="costo_unitario_bruto_ds CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="responsable CP-sticky">Responsable</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    $IdLotePivote = 0;
    $LoteAlmacenIdPivorte = 0;

    while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {

      if( ($row3["InvLoteId"]!=$IdLotePivote) ||
          ($row3["InvLoteId"]==$IdLotePivote && $row3["LoteAlmacenId"]!=$LoteAlmacenIdPivorte)
       ){

        $fechaVencimiento = ($row3["FechaVencimiento"]!=NULL)?$row3["FechaVencimiento"]->format('d-m-Y'):'-';
        $fechaEntrada = ($row3["FechaEntrada"]!=NULL)?$row3["FechaEntrada"]->format('d-m-Y'):'-';

        $IdLotePivote = $row3["InvLoteId"];
        $LoteAlmacenIdPivorte = $row3["LoteAlmacenId"];

        echo '
          <td align="center"><strong>'.intval($contador).'</strong></td>
          <td class="origen" align="center">'.FG_Limpiar_Texto($row3["Origen"]).'</td>
        ';

        if($row3["Origen"]=="COMPRA"){

          $sql4 = R10Q_Caso_Compra($row3["InvLoteId"],$IdArticulo,$row3["LoteAlmacenId"]);

          try {
            $result4 = sqlsrv_query($conn,$sql4);
            $row4 = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC);

            echo '
              <td class="detalle" align="center">'.FG_Limpiar_Texto($row4["Proveedor"]).'</td>
              <td class="numero_referencia" align="center">'.($row4["NumeroFactura"]).'</td>
            ';

          } catch (Exception $e) {

            echo '
              <td class="detalle" align="center">'.$row3["InvLoteId"].'|'.$IdArticulo.'|'.$row3["LoteAlmacenId"].'</td>
              <td class="numero_referencia" align="center">*</td>
            ';

          }

        }else{
           echo '
            <td class="detalle" align="center">'.FG_Limpiar_Texto($row3["Causa"]).'</td>
            <td class="numero_referencia" align="center">'.($row3["NumeroReferencia"]).'</td>
          ';
        }

        $TasaFecha = FG_Tasa_Fecha($connCPharma,$row3["FechaCreacionLote"]->format("Y-m-d"));
        if(!isset($TasaFecha)){
           $TasaFecha = 0;
        }

        echo '
          <td class="fecha_creacion_lote" align="center">'.($row3["FechaCreacionLote"]->format('d-m-Y')).'</td>
          <td class="fecha_vencimiento" align="center">'.($fechaVencimiento).'</td>
          <td class="fecha_entrada" align="center">'.($fechaEntrada).'</td>
          <td class="numero_lote" align="center">'.($row3["NumeroLote"]).'</td>
          <td class="lote_fabricante" align="center">'.($row3["LoteFabricante"]).'</td>
          <td class="almacen" align="center">'.($row3["Almacen"]).'</td>
          <td class="existencia" align="center">'.intval($row3["Existencia"]).'</td>
          <td class="costo_bruto_total" align="center">'.number_format($row3["CostoTotal"],2,"," ,"." ).'</td>
          <td class="costo_unitario_bruto" align="center">'.number_format($row3["CostoUnitario"],2,"," ,"." ).'</td>
          <td class="precio_troquelado_bruto" align="center">'.number_format($row2["PrecioTroquelado"],2,"," ,"." ).'</td>
          <td class="tasa_mercado" align="center">'.number_format($TasaFecha,2,"," ,"." ).'</td>
        ';

        if($TasaFecha!=0){
          $CostoDivisa = $row3["CostoUnitario"] / $TasaFecha;
          echo '
            <td class="costo_unitario_bruto" align="center">'.number_format($CostoDivisa,2,"," ,"." ).'</td>
          ';
        }else{
          echo '
            <td class="costo_unitario_bruto" align="center">0.00</td>
          ';
        }

        echo '
            <td class="responsable" align="center">'.FG_Limpiar_Texto($row3["Responsable"]).'</td>
          ';

        echo '</tr>';
        $contador++;
      }
    }
      echo '
      </tbody>
    </table>';
    //Tabla Lotes SIN exitencia

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
    TITULO: R10Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Detalle_Articulo($IdArticulo) {
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
    TITULO: R10Q_Analitico_Productos
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Analitico_Productos($IdArticulo) {
    $sql = "
      SELECT
      invlote.Auditoria_FechaCreacion,
      InvLoteAlmacen.id as LoteAlmacenId,
      InvLoteAlmacen.InvLoteId,
      (SELECT IIF(InvMovimiento.InvCausaId = 1, 'COMPRA', 'INVENTARIO')) as Origen,
      InvMovimiento.InvCausaId,
      InvCausa.Descripcion as Causa,
      InvMovimiento.DocumentoOrigen as NumeroReferencia,
      CONVERT(DATE,invlote.Auditoria_FechaCreacion) as FechaCreacionLote,
      CONVERT(DATE,invlote.FechaVencimiento) as FechaVencimiento,
      CONVERT(DATE,invlote.FechaEntrada) as FechaEntrada,
      InvLote.Numero as NumeroLote,
      InvAlmacen.Descripcion as Almacen,
      InvLote.LoteFabricante as LoteFabricante,
      ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) AS Existencia,
      InvLote.M_PrecioCompraBruto as CostoUnitario,
      InvLote.M_PrecioTroquelado as PrecioTroquelado,
      (ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) * InvLote.M_PrecioCompraBruto) as CostoTotal,
      InvLote.Auditoria_Usuario as Responsable
      FROM invlotealmacen
      LEFT JOIN InvLote ON InvLote.id = invlotealmacen.InvLoteId
      LEFT JOIN InvMovimiento ON InvMovimiento.InvLoteId = InvLote.id
      LEFT JOIN InvCausa ON  InvCausa.Id = InvMovimiento.InvCausaId
      LEFT JOIN InvAlmacen ON InvAlmacen.Id = invlotealmacen.InvAlmacenId
      WHERE
      InvLoteAlmacen.InvArticuloId = '$IdArticulo'
      AND InvLoteAlmacen.Existencia > 0
      AND (
          (InvMovimiento.InvCausaId=1)
          OR (InvMovimiento.InvCausaId=5)
          OR (InvMovimiento.InvCausaId=11)
      )
      ORDER BY invlote.Auditoria_FechaCreacion asc
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R10Q_Analitico_Productos
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Analitico_Productos_SE($IdArticulo) {
    $sql = "
      SELECT
      invlote.Auditoria_FechaCreacion,
      InvLoteAlmacen.id as LoteAlmacenId,
      InvLoteAlmacen.InvLoteId,
      (SELECT IIF(InvMovimiento.InvCausaId = 1, 'COMPRA', 'INVENTARIO')) as Origen,
      InvMovimiento.InvCausaId,
      InvCausa.Descripcion as Causa,
      InvMovimiento.DocumentoOrigen as NumeroReferencia,
      CONVERT(DATE,invlote.Auditoria_FechaCreacion) as FechaCreacionLote,
      CONVERT(DATE,invlote.FechaVencimiento) as FechaVencimiento,
      CONVERT(DATE,invlote.FechaEntrada) as FechaEntrada,
      InvLote.Numero as NumeroLote,
      InvAlmacen.Descripcion as Almacen,
      InvLote.LoteFabricante as LoteFabricante,
      ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) AS Existencia,
      InvLote.M_PrecioCompraBruto as CostoUnitario,
      InvLote.M_PrecioTroquelado as PrecioTroquelado,
      (ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) * InvLote.M_PrecioCompraBruto) as CostoTotal,
      InvLote.Auditoria_Usuario as Responsable
      FROM invlotealmacen
      LEFT JOIN InvLote ON InvLote.id = invlotealmacen.InvLoteId
      LEFT JOIN InvMovimiento ON InvMovimiento.InvLoteId = InvLote.id
      LEFT JOIN InvCausa ON  InvCausa.Id = InvMovimiento.InvCausaId
      LEFT JOIN InvAlmacen ON InvAlmacen.Id = invlotealmacen.InvAlmacenId
      WHERE
      InvLoteAlmacen.InvArticuloId = '$IdArticulo'
      AND InvLoteAlmacen.Existencia <= 0
      AND (
          (InvMovimiento.InvCausaId=1)
          OR (InvMovimiento.InvCausaId=5)
          OR (InvMovimiento.InvCausaId=11)
      )
      ORDER BY invlote.Auditoria_FechaCreacion asc
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R10Q_Caso_Compra
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Caso_Compra($InvLoteId,$InvArticuloId,$InvLoteAlmacenId){
    $sql="
    SELECT
    (
      SELECT
      GenPersona.Nombre
      FROM ComFactura
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE ComProveedor.Id = (
        (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = (
          SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
            SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
            )
          )
        )
      )
      AND ComFactura.id = (SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
        SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
        )
      )
    ) AS Proveedor,
    (
      SELECT
      ComFactura.NumeroFactura
      FROM ComFactura
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE ComProveedor.Id = (
        (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = (
          SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
            SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
            )
          )
        )
      )
      AND ComFactura.id = (SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
        SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
        )
      )
    ) AS NumeroFactura
    FROM invlotealmacen
    WHERE
    InvLoteAlmacen.InvLoteId = '$InvLoteId'
    AND InvLoteAlmacen.InvArticuloId = '$InvArticuloId'
    AND InvLoteAlmacen.id = '$InvLoteAlmacenId'
    ";
    return $sql;
  }
?>
