@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
    </script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>

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
    .barrido{
      text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
    }
    .barrido:hover{
      text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
      transform: translate(20px,0px);
    }
    </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Catalogo de proveedor
  </h1>
  <hr class="row align-items-start col-12">

  <?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');

  $ArtJson = "";
  
  if (isset($_GET['Id'])){
  //CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR 
    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

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

    R7_Catalogo_Proveedor_C2($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  } 
  else if(isset($_GET['pedido'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UN PEDIDO EN BASE A DIAS Y EL RANGO
    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

    $InicioCarga = new DateTime("now");

    R7_Catalogo_Proveedor_C3($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['pedido']);
    GuardarAuditoria('CONSULTAR','REPORTE','Catalogo de proveedor');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

    $InicioCarga = new DateTime("now");

    $sql = R7Q_Lista_Proveedores();
    $ArtJson = armarJson($sql,$_GET['SEDE']);

    echo '
    <form autocomplete="off" action="" target="_blank">
      <div class="autocomplete" style="width:90%;">
        <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del proveedor " onkeyup="conteo()" required>
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
@endsection

<?php
/*
    TITULO: R7_Catalogo_Proveedor_C2
    PARAMETROS: [$SedeConnection] sede donde se hara la conexion
          [$IdProveedor] ID del proveedor a buscar
          [$NombreProveedor] Nombre del proveedor a buscar
    FUNCION: Armar el reporte catalogo de proveedor
    RETORNO: No aplica
   */
  function R7_Catalogo_Proveedor_C2($SedeConnection,$IdProveedor,$NombreProveedor){

    $conn = ConectarSmartpharma($SedeConnection);

    $sql = R7Q_Catalogo_Proveedor($IdProveedor);
    $result = sqlsrv_query($conn,$sql);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
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
    echo '<td>'.utf8_encode(addslashes($NombreProveedor)).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de Barra</th>
            <th scope="col">Descripcion</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];
      $CodigoArticulo = $row["CodigoArticulo"];
      $CodigoBarra = $row["CodigoBarra"];
      $Descripcion = utf8_encode(addslashes($row["Descripcion"]));

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$CodigoArticulo.'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';
      echo 
      '<td align="left" class="barrido">
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
  }
  /*
    TITULO: R7_Catalogo_Proveedor_C3
    PARAMETROS: [$SedeConnection] sede donde se hara la conexion
          [$IdProveedor] ID del proveedor a buscar
          [$NombreProveedor] Nombre del proveedor a buscar
    FUNCION: Armar el reporte catalogo de proveedor
    RETORNO: No aplica
   */
  function R7_Catalogo_Proveedor_C3($SedeConnection,$IdProveedor,$NombreProveedor,$FInicial,$FFinal,$DiasPedido){

    $conn = ConectarSmartpharma($SedeConnection);

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);

    $sql = R7Q_Catalogo_Proveedor($IdProveedor);
    $result = sqlsrv_query($conn,$sql);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
    </div>
    <br/>
    ';
    echo'<h6 align="center">Pedido en base a: '.$DiasPedido.' dias </h6>';
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
 
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
    echo '<td>'.utf8_encode(addslashes($NombreProveedor)).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de Barra</td>
              <th scope="col">Descripcion</th>              
              <th scope="col">Producto Unico</th>
              <th scope="col">Precio (Con IVA) '.SigVe.'</th>
              <th scope="col">Existencia</th>
              <th scope="col">Unidades vendidas</th>
              <th scope="col">Unidades compradas</th>
              <th scope="col">Venta diaria</th>
              <th scope="col">Dias restantes</th>
              <th scope="col">Ultima Venta (En rango)</th>
              <th scope="col">Ultima Venta</th>
              <th scope="col">Pedir</th>
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];
      $CodigoArticulo = $row["CodigoArticulo"];
      $CodigoBarra = $row["CodigoBarra"];
      $Existencia = $row["Existencia"];
      $Descripcion = utf8_encode(addslashes($row["Descripcion"]));
      $IsIVA = $row["ConceptoImpuesto"];
      $Unico = FG_Producto_Unico($conn,$IdArticulo,$IdProveedor);
      $Precio = FG_Calculo_Precio($conn,$IdArticulo,$IsIVA,$Existencia);

      $sql = QG_CleanTable('CP_R7Q_Unidades_Vendidas');
      sqlsrv_query($conn,$sql);
      $sql = QG_CleanTable('CP_R7Q_Unidades_Devueltas');
      sqlsrv_query($conn,$sql);
      $sql = QG_CleanTable('CP_R7Q_Unidades_Compradas');
      sqlsrv_query($conn,$sql);
      $sql = QG_CleanTable('CP_R7Q_Unidades_Reclamadas');
      sqlsrv_query($conn,$sql);

      $sql1 = R7Q_Unidades_Vendidas($FInicial,$FFinal,$IdArticulo);
      $sql2 = R7Q_Unidades_Devueltas($FInicial,$FFinal,$IdArticulo);
      $sql3 = R7Q_Unidades_Compradas($FInicial,$FFinal,$IdArticulo);
      $sql4 = R7Q_Unidades_Reclamadas($FInicial,$FFinal,$IdArticulo);
      $sql5 = R7Q_Integracion_Catalogo_Proveedor();

      sqlsrv_query($conn,$sql1);
      sqlsrv_query($conn,$sql2);
      sqlsrv_query($conn,$sql3); 
      sqlsrv_query($conn,$sql4);
      $result2 = sqlsrv_query($conn,$sql5);

      $sql = QG_CleanTable('CP_R7Q_Unidades_Vendidas');
      sqlsrv_query($conn,$sql);
      $sql = QG_CleanTable('CP_R7Q_Unidades_Devueltas');
      sqlsrv_query($conn,$sql);
      $sql = QG_CleanTable('CP_R7Q_Unidades_Compradas');
      sqlsrv_query($conn,$sql);
      $sql = QG_CleanTable('CP_R7Q_Unidades_Reclamadas');
      sqlsrv_query($conn,$sql);

      $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
      $UnidadesVendidas = $row["UnidadesVendidas"];
      $UnidadesCompradas = $row["UnidadesCompradas"];
      $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
      $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);
      $CantidadPedido = FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia);

      $sql3 = QG_Ultima_Venta_Rango($IdArticulo,$FInicial,$FFinal);
      $result3 = sqlsrv_query($conn,$sql3);
      $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
      $UltimaVentaRango = $row3["UltimaVenta"];

      $sql4 = QG_UltimaVenta($IdArticulo);
      $result4 = sqlsrv_query($conn,$sql4);
      $row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);
      $UltimaVenta = $row4["UltimaVenta"];

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$CodigoArticulo.'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';
      echo 
      '<td align="left" class="barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';     
      echo '<td align="center">'.$Unico.'</td>';
      echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
      echo '<td align="center">'.intval($Existencia).'</td>';
      echo
      '<td align="center" class="barrido">
      <a href="reporte12?fechaInicio='.$FInicialImp.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesVendidas).
      '</a>
      </td>';
      echo
      '<td align="center" class="barrido">
      <a href="reporte12?fechaInicio='.$FInicialImp.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesCompradas).
      '</a>
      </td>';
      echo '<td align="center">'.round($VentaDiaria,2).'</td>';
      echo '<td align="center">'.round($DiasRestantes,2).'</td>';

      if(($UltimaVentaRango)){
        echo '<td align="center">'.$UltimaVentaRango->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      if(($UltimaVenta)){
        echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }
      echo '<td align="center">'.intval($CantidadPedido).'</td>';
      echo '</tr>';
    $contador++;
      }
      echo '
        </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /*
    TITULO: R7Q_Lista_Proveedores
    PARAMETROS: No aplica
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
   */
  function R7Q_Lista_Proveedores() {
    $sql = "
      SELECT
      GenPersona.Nombre,
      ComProveedor.Id
      FROM ComProveedor
      INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
      INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
      GROUP BY ComProveedor.Id, GenPersona.Nombre
      ORDER BY ComProveedor.Id ASC
    ";
    return $sql;
  }
  /*
    TITULO: R7Q_Catalogo_Proveedor
    PARAMETROS: No aplica
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
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
  /*
    TITULO: R7Q_Unidades_Vendidas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
    RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
   */
  function R7Q_Unidades_Vendidas($FInicial,$FFinal,$IdArticulo) {
    $sql = "
      SELECT 
      VenFacturaDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
      (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas
      INTO CP_R7Q_Unidades_Vendidas
      FROM VenFacturaDetalle
      INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal') AND (VenFacturaDetalle.InvArticuloId = '$IdArticulo')
      GROUP BY VenFacturaDetalle.InvArticuloId
      ORDER BY UnidadesVendidas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R7Q_Unidades_Devueltas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces devuelta a clientes y las unidades devuelta de un producto
    RETORNO: Tabla con los articulos, las veces devuelta y las unidades devuelta
  */
  function R7Q_Unidades_Devueltas($FInicial,$FFinal,$IdArticulo) {  
    $sql = "
      SELECT
      VenDevolucionDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesDevueltas,
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesDevueltas
      INTO CP_R7Q_Unidades_Devueltas
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE
      (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal') AND (VenDevolucionDetalle.InvArticuloId = '$IdArticulo')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ORDER BY UnidadesDevueltas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R7Q_Unidades_Compradas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
    RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
   */
  function R7Q_Unidades_Compradas($FInicial,$FFinal,$IdArticulo) {   
    $sql = "
      SELECT
      ComFacturaDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesCompradas,
      (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0)) as UnidadesCompradas
      INTO CP_R7Q_Unidades_Compradas
      FROM ComFacturaDetalle      
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE
      (ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal') AND (ComFacturaDetalle.InvArticuloId = '$IdArticulo')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ORDER BY UnidadesCompradas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R7Q_Unidades_Reclamadas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
          [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
    RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
   */
  function R7Q_Unidades_Reclamadas($FInicial,$FFinal,$IdArticulo) {   
    $sql = "
      SELECT
      ComReclamoDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesReclamadas,
      (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesReclamadas
      INTO CP_R7Q_Unidades_Reclamadas
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE
      (ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal') AND (ComReclamoDetalle.InvArticuloId = '$IdArticulo')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ORDER BY UnidadesReclamadas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R7Q_Integracion_Catalogo_Proveedor
    PARAMETROS: No aplica
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
   */
  function R7Q_Integracion_Catalogo_Proveedor() {
    $sql = "
      SELECT
      CP_R7Q_Unidades_Vendidas.InvArticuloId,
      ((ISNULL(CP_R7Q_Unidades_Vendidas.VecesVendidas,CAST(0 AS INT))) - 
      (ISNULL(CP_R7Q_Unidades_Devueltas.VecesDevueltas,CAST(0 AS INT))) 
      ) AS VecesVendidas,
      ((ISNULL(CP_R7Q_Unidades_Vendidas.UnidadesVendidas,CAST(0 AS INT))) -
      (ISNULL(CP_R7Q_Unidades_Devueltas.UnidadesDevueltas,CAST(0 AS INT))) 
      ) AS UnidadesVendidas,
      ((ISNULL(CP_R7Q_Unidades_Compradas.VecesCompradas,CAST(0 AS INT))) -
      (ISNULL(CP_R7Q_Unidades_Reclamadas.VecesReclamadas,CAST(0 AS INT))) 
      ) AS VecesCompradas,
      ((ISNULL(CP_R7Q_Unidades_Compradas.UnidadesCompradas,CAST(0 AS INT))) -
      (ISNULL(CP_R7Q_Unidades_Reclamadas.UnidadesReclamadas,CAST(0 AS INT))) 
      ) AS UnidadesCompradas
      FROM CP_R7Q_Unidades_Vendidas
      LEFT JOIN CP_R7Q_Unidades_Devueltas ON CP_R7Q_Unidades_Devueltas.InvArticuloId = CP_R7Q_Unidades_Vendidas.InvArticuloId
      LEFT JOIN CP_R7Q_Unidades_Compradas ON CP_R7Q_Unidades_Compradas.InvArticuloId = CP_R7Q_Unidades_Vendidas.InvArticuloId
      LEFT JOIN CP_R7Q_Unidades_Reclamadas ON CP_R7Q_Unidades_Reclamadas.InvArticuloId = CP_R7Q_Unidades_Vendidas.InvArticuloId
      INNER JOIN InvArticulo ON InvArticulo.Id = CP_R7Q_Unidades_Vendidas.InvArticuloId
      ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }
?>