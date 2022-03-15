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

        campos = ['codigo', 'codigo_barra', 'descripcion', 'precio', 'dolarizado', 'clasificacion', 'existencia', 'veces_escaneadas', 'veces_facturadas', 'contraste', 'unidades_facturadas', 'promedio_factura', 'ultimo_proveedor'];

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
        Consultor de Precios
    </h1>
    <hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if (isset($_GET['fechaInicio'])) {

    $InicioCarga = new DateTime("now");

    R21_Consultor_Precio($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Consultor de Precios');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else{
        echo '
        <form autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
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
  /**********************************************************************************/
  /*
    TITULO: R21_Consultor_Precio
    FUNCION: Armar el reporte del consultor de precios
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
 function R21_Consultor_Precio($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql = Q21_Data_Consultor($FInicial,$FFinal);
    $result = mysqli_query($connCPharma,$sql);

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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio\')" name="precio" checked>
                    Precio
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dolarizado\')" name="dolarizado" checked>
                    Dolarizado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'veces_escaneadas\')" name="veces_escaneadas" checked>
                    Veces escaneadas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'veces_facturadas\')" name="veces_facturadas" checked>
                    Veces facturadas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'contraste\')" name="contraste" checked>
                    Contraste
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'unidades_facturadas\')" name="unidades_facturadas" checked>
                    Unidades facturadas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'promedio_factura\')" name="promedio_factura" checked>
                    Promedio por factura
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_proveedor\')" name="ultimo_proveedor" checked>
                    Ultimo proveedor
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
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
    </div>
    <br/>
    ';
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>';

    echo '
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="codigo CP-sticky">Codigo Interno</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo de Barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripcion</td>
            <th scope="col" class="precio CP-sticky">Precio</br>(Con IVA) '.SigVe.'</td>
            <th scope="col" class="dolarizado CP-sticky">Dolarizado</td>
            <th scope="col" class="clasificacion CP-sticky">Clasificacion</td>
            <th scope="col" class="existencia CP-sticky">Existencia</td>
            <th scope="col" class="veces_escaneadas CP-sticky">Veces Escaneadas</td>
            <th scope="col" class="veces_facturadas CP-sticky">Veces Facturadas</td>
            <th scope="col" class="contraste CP-sticky">Contraste</td>
            <th scope="col" class="unidades_facturadas CP-sticky">Unidades Facturadas</td>
            <th scope="col" class="promedio_factura CP-sticky">Promedio por Factura</td>
            <th scope="col" class="ultimo_proveedor CP-sticky">Ultimo Proveedor</th>
          </tr>
        </thead>
        <tbody>
      ';

    $contador = 1;
    while($row = mysqli_fetch_assoc($result)){
      $IdArticulo = $row['id_articulo'];
      $codigo_interno = $row['codigo_interno'];
      $codigo_barra = $row['codigo_barra'];
      $Descripcion = FG_Limpiar_Texto($row["descripcion"]);
      $VecesEscaneadas = $row['VecesEscaneadas'];

      $sql1 = SQG_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      $Existencia = $row1["Existencia"];
      $ExistenciaAlmacen1 = $row1["ExistenciaAlmacen1"];
      $ExistenciaAlmacen2 = $row1["ExistenciaAlmacen2"];
      $IsTroquelado = $row1["Troquelado"];
      $IsIVA = $row1["Impuesto"];
      $UtilidadArticulo = $row1["UtilidadArticulo"];
      $UtilidadCategoria = $row1["UtilidadCategoria"];
      $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
      $PrecioCompraBrutoAlmacen1 = $row1["PrecioCompraBrutoAlmacen1"];
      $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
      $PrecioCompraBrutoAlmacen2 = $row1["PrecioCompraBrutoAlmacen2"];
      $PrecioCompraBruto = $row1["PrecioCompraBruto"];
      $Dolarizado = $row1["Dolarizado"];
      $CondicionExistencia = 'CON_EXISTENCIA';
      $UltimoProveedorId = $row1['UltimoProveedorID'];
      $UltimoProveedor = FG_Limpiar_Texto($row1['UltimoProveedorNombre']);

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $sql2 = SQG_Detalle_Venta($IdArticulo,$FInicial,$FFinal);
      $result2 = sqlsrv_query($conn,$sql2);
      $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

      $TotalVecesVendidas = $row2['TotalVecesVendidas'];
      $TotalUnidadesVendidas = $row2['TotalUnidadesVendidas'];

      $Contraste = intval($TotalVecesVendidas)-intval($VecesEscaneadas);

      if($TotalVecesVendidas!=0){
        $PromedioFactura = intval($TotalUnidadesVendidas)/intval($TotalVecesVendidas);
      }
      else{
        $PromedioFactura = 0;
      }

      if($TotalUnidadesVendidas==''){
        $TotalUnidadesVendidas = 0;
      }

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td class="codigo" align="center">'.$codigo_interno.'</td>';
      echo '<td class="codigo_barra" align="center">'.$codigo_barra.'</td>';
      echo
      '<td align="left" class="descripcion CP-barrido">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
       echo '<td class="precio" align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
       echo '<td class="dolarizado" align="center">'.$Dolarizado.'</td>';
       echo '<td class="clasificacion" align="center">'.$clasificacion.'</td>';
       echo '<td class="existencia" align="center">'.intval($Existencia).'</td>';
       echo '<td class="veces_escaneadas" align="center">'.intval($VecesEscaneadas).'</td>';
       echo '<td class="veces_facturadas" align="center">'.intval($TotalVecesVendidas).'</td>';
       echo '<td class="contraste" align="center">'.intval($Contraste).'</td>';
       echo '<td class="unidades_facturadas" align="center">'.$TotalUnidadesVendidas.'</td>';
       echo '<td class="promedio_factura" align="center">'.$PromedioFactura.'</td>';
       echo
        '<td align="left" class="ultimo_proveedor CP-barrido">
        <a href="/reporte7?Nombre='.$UltimoProveedor.'&Id='.$UltimoProveedorId.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .$UltimoProveedor.
        '</a>
        </td>';
        echo '</tr>';
      $contador++;
    }
    echo '
        </tbody>
    </table>';
    mysqli_close($connCPharma);
    sqlsrv_close($conn);
 }
 /************************************************************/
 /*
    TITULO: Q21_Data_Consultor
    FUNCION: Armar el reporte del consultor de precios
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
 function Q21_Data_Consultor($FInicial,$FFinal){
  $sql = "
    SELECT
    id_articulo,
    codigo_interno,
    codigo_barra,
    descripcion,
    COUNT(*) AS VecesEscaneadas
    FROM consultor
    WHERE (fecha_captura >= '$FInicial') AND (fecha_captura < '$FFinal')
    GROUP BY id_articulo,codigo_interno,codigo_barra,descripcion
    ORDER BY VecesEscaneadas DESC
    LIMIT 100
  ";
  return $sql;
 }
?>
