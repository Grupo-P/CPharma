@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    table, th, td, tr {
      padding-bottom: 10px;
    }
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
    <i class="fas fa-file-invoice"></i>
    Ventas Cruzadas
  </h1>
  <hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $ArtJson = "";
  $CodJson = "";

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if ((isset($_GET['Descrip']))OR(isset($_GET['CodBar']))){

    $InicioCarga = new DateTime("now");

    if((isset($_GET['Descrip']))&&(!empty($_GET['Descrip']))) {
      R19_Venta_Cruzada($_GET['SEDE'],$_GET['IdD'],$_GET['fechaInicio'],$_GET['fechaFin']);
    }
    else if(isset($_GET['CodBar'])&&(!empty($_GET['CodBar']))){
     R19_Venta_Cruzada($_GET['SEDE'],$_GET['IdCB'],$_GET['fechaInicio'],$_GET['fechaFin']);
    }

    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Ventas Cruzadas');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
    $InicioCarga = new DateTime("now");

    $sql = R19Q_Lista_Articulos();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    $sql1 = R19Q_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

    echo '
    <form id="form" autocomplete="off" action="" target="_blank">
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
              <input id="SEDE" name="SEDE" type="hidden" value="';
              print_r($_GET['SEDE']);
              echo'">
            </td>
          </tr>
          <tr>
            <td colspan="6">
              <div class="autocomplete" style="width:90%;">
              <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
              <input id="myId" name="IdD" type="hidden">
              </div>
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>
          <tr>
            <td colspan="6">
              <div class="autocomplete" style="width:90%;">
              <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
              <input id="myIdCB" name="IdCB" type="hidden">
              </div>
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>
        </table>
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
    TITULO: R19Q_Lista_Articulos
    FUNCION: Armar una lista de articulos
    RETORNO: Lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Lista_Articulos() {
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
    TITULO: R19Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Lista_Articulos_CodBarra() {
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
  /********************************************************************************/
  /*
    TITULO: R19_Venta_Cruzada
    FUNCION: Arma una lista para el pedido de productos
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
   function R19_Venta_Cruzada($SedeConnection,$IdArticulo,$FInicial,$FFinal){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql = R19Q_Lista_Factura($IdArticulo,$FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql);
    $resultLV = sqlsrv_query($conn,$sql);

    $Total_Facturas_Articulo_Unico = 0;
    $Arr_Factura_Detalle = array();
    $Arr_Apariciones = array();

    while($rowLV = sqlsrv_fetch_array($resultLV, SQLSRV_FETCH_ASSOC)) {
      $IdVentaLV = $rowLV['IdVenta'];

      $sqlCF = "SELECT * from VenVenta WHERE VenVenta.Id = '$IdVentaLV'";
      $resultCF = sqlsrv_query($conn,$sqlCF);
      $rowCF = sqlsrv_fetch_array($resultCF, SQLSRV_FETCH_ASSOC);

      $sqlDF = "SELECT * FROM VenVentaDetalle WHERE VenVentaDetalle.VenVentaId = '$IdVentaLV'";
      $resultDF = sqlsrv_query($conn,$sqlDF);

      $sqlCDF = "SELECT COUNT(*) AS Cuenta  FROM VenVentaDetalle WHERE VenVentaDetalle.VenVentaId = '$IdVentaLV'";
      $resultCDF = sqlsrv_query($conn,$sqlCDF);
      $rowCDF = sqlsrv_fetch_array($resultCDF, SQLSRV_FETCH_ASSOC);

      if($rowCDF['Cuenta']==1){
        $Total_Facturas_Articulo_Unico++;
      }

      while($rowDF = sqlsrv_fetch_array($resultDF, SQLSRV_FETCH_ASSOC)) {
        array_push($Arr_Factura_Detalle,$rowDF['InvArticuloId']);
      }
    }

    $Arr_Apariciones = array_count_values($Arr_Factura_Detalle);

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
    echo'
    <table class="table table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</th>
            <th scope="col" class="CP-sticky">Hora</th>
            <th scope="col" class="CP-sticky"># Factura</th>
            <th scope="col" class="CP-sticky">Total Factura</th>
            <th scope="col" class="CP-sticky">Codigo Interno</th>
            <th scope="col" class="CP-sticky">Codido de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Tipo</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Apariciones</th>
            <th scope="col" class="CP-sticky">Precio (En factura)</br>'.SigVe.'</th>
            <th scope="col" class="CP-sticky">Caja</th>
            <th scope="col" class="CP-sticky">Cliente</th>
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;
    $Total_Bs_Factura = 0;
    $Total_Factura = 0;
    $Total_Unidades = 0;
    $Total_Unidades_Articulo_Evaluado = 0;
    $Cuenta_Medicina = 0;
    $Cuenta_Miscelaneo = 0;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdVenta = $row['IdVenta'];

      $sql1 = "SELECT * from VenVenta WHERE VenVenta.Id = '$IdVenta'";
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

      $sql2 = "SELECT * FROM VenVentaDetalle WHERE VenVentaDetalle.VenVentaId = '$IdVenta'";
      $result2 = sqlsrv_query($conn,$sql2);

      while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {

        $sql3 = R19Q_Detalle_Articulo($row2['InvArticuloId']);
        $result3 = sqlsrv_query($conn,$sql3);
        $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

        $CodigoArticulo = $row3["CodigoInterno"];
        $CodigoBarra = $row3["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row3["Descripcion"]);
        $Tipo = FG_Tipo_Producto($row3["Tipo"]);

        if($Tipo=='MEDICINA'){
          $Cuenta_Medicina++;
        }
        else{
          $Cuenta_Miscelaneo++;
        }

        $sql3 = R19Q_Detalle_Articulo($row2['InvArticuloId']);
        $result3 = sqlsrv_query($conn,$sql3);
        $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

        $sql4 = R19Q_Cliente($row1['VenClienteId']);
        $result4 = sqlsrv_query($conn,$sql4);
        $row4 = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC);
        $NombreCliente = ''.$row4['Nombre'].' '.$row4['Apellido'];

        if($row2['InvArticuloId']==$IdArticulo){
          $Total_Unidades_Articulo_Evaluado +=  intval($row2['Cantidad']);
        }

        if(($Total_Factura%2)==0){
          echo '<tr>';
          echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
          echo '<td align="center">'.$row1['FechaDocumentoVenta']->format('d-m-Y').'</td>';
          echo '<td align="center">'.$row1['FechaDocumentoVenta']->format('h:i:s A').'</td>';
          echo '<td align="center">'.$row1['ConsecutivoVentaSistema'].'</td>';
          echo '<td align="center">'.number_format($row1['M_MontoTotalVenta'],2,"," ,"." ).'</td>';
          echo '<td align="center">'.$CodigoArticulo.'</td>';
          echo '<td align="center">'.$CodigoBarra.'</td>';
          echo
          '<td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$row2['InvArticuloId'].'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion.
          '</a>
          </td>';
          echo '<td align="center">'.$Tipo.'</td>';
          echo '<td align="center">'.intval($row2['Cantidad']).'</td>';
          echo '<td align="center">'.intval($Arr_Apariciones[$row2['InvArticuloId']]).'</td>';
          echo '<td align="center">'.number_format($row2['M_PrecioNeto'],2,"," ,"." ).'</td>';
          echo'</td>';
          echo '<td align="left">'.$row1['Auditoria_Usuario'].'</td>';
          echo '<td align="left">'.$NombreCliente.'</td>';
        }
        else{
          echo '<tr>';
          echo '<td align="center" style="background-color: #989b9e;"><strong>'.intval($contador).'</strong></td>';
          echo '<td align="center" style="background-color: #989b9e;">'.$row1['FechaDocumentoVenta']->format('d-m-Y').'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.$row1['FechaDocumentoVenta']->format('h:i:s A').'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.$row1['ConsecutivoVentaSistema'].'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.number_format($row1['M_MontoTotalVenta'],2,"," ,"." ).'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.$CodigoArticulo.'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.$CodigoBarra.'</td>';
          echo
          '<td align="left" class="CP-barrido" style="background-color: #989b9e;">
          <a href="/reporte2?Id='.$row2['InvArticuloId'].'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion.
          '</a>
          </td>';
          echo '<td align="center" style="background-color: #989b9e;">'.$Tipo.'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.intval($row2['Cantidad']).'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.intval($Arr_Apariciones[$row2['InvArticuloId']]).'</td>';
          echo '<td align="center" style="background-color: #989b9e;">'.number_format($row2['M_PrecioNeto'],2,"," ,"." ).'</td>';
          echo'</td>';
          echo '<td align="left" style="background-color: #989b9e;">'.$row1['Auditoria_Usuario'].'</td>';
          echo '<td align="left" style="background-color: #989b9e;">'.$NombreCliente.'</td>';
        }

        $contador++;
        $Total_Unidades += $row2['Cantidad'];
      }
      $Total_Factura++;
      $Total_Bs_Factura += $row1['M_MontoTotalVenta'];
    }
    echo '
      </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Total de facturas</th>
            <th scope="col" class="CP-sticky">Total de facturas</br>(Solo Con Articulo Evaluado)</th>
            <th scope="col" class="CP-sticky">Total en ventas<br>'.SigVe.'</th>
            <th scope="col" class="CP-sticky">Total en ventas<br>Promedio '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio Promedio '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Total de</br>SKU Articulo</th>
            <th scope="col" class="CP-sticky">Total de</br>SKU Articulo (Promedio)</th>
            <th scope="col" class="CP-sticky">Total de unidades</br>facturadas</th>
            <th scope="col" class="CP-sticky">Total de unidades</br>Promedio</th>
            <th scope="col" class="CP-sticky">Total de unidades</br>(Articulo Evaluado)</th>
            <th scope="col" class="CP-sticky">Total de Medicinas</th>
            <th scope="col" class="CP-sticky">Total de Miscelaneos</th>
          </tr>
        </thead>
        <tbody>
    ';
    echo '<tr>';
    echo '<td align="center">'.$Total_Factura.'</td>';
    echo '<td align="center">'.$Total_Facturas_Articulo_Unico.'</td>';
    echo '<td align="center">'.number_format($Total_Bs_Factura,2,"," ,"." ).'</td>';
    echo '<td align="center">'.number_format(($Total_Bs_Factura/$Total_Factura),2,"," ,"." ).'</td>';
    echo '<td align="center">'.number_format(($Total_Bs_Factura/$Total_Unidades),2,"," ,"." ).'</td>';
    echo '<td align="center">'.COUNT($Arr_Apariciones).'</td>';
    echo '<td align="center">'.number_format(($contador/$Total_Factura),2,"," ,"." ).'</td>';
    echo '<td align="center">'.$Total_Unidades.'</td>';
    echo '<td align="center">'.number_format(($Total_Unidades/$Total_Factura),2,"," ,"." ).'</td>';
    echo '<td align="center">'.$Total_Unidades_Articulo_Evaluado.'</td>';
    echo '<td align="center">'.$Cuenta_Medicina.'</td>';
    echo '<td align="center">'.$Cuenta_Miscelaneo.'</td>';
    echo '</tr>';
    echo '
      </tbody>
    </table>';
    echo'</br>';
  }
  /**********************************************************************************/
  /*
    TITULO: R19Q_Lista_Factura
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Lista_Factura($IdArticulo,$FInicial,$FFinal) {
    $sql = "
      SELECT
      VenVenta.id AS IdVenta
      FROM VenVentaDetalle
      INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
      WHERE VenVentaDetalle.InvArticuloId = '$IdArticulo'
      AND (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
      AND (VenVenta.estadoVenta = '2')
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R19Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Detalle_Articulo($IdArticulo) {
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
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
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
    TITULO: R19Q_Cliente
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Cliente($IdCliente) {
    $sql = "
      SELECT
      GenPersona.Nombre,
      GenPersona.Apellido
      FROM VenCliente
      INNER JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
      WHERE VenCliente.Id = '$IdCliente'
    ";
    return $sql;
  }
?>
