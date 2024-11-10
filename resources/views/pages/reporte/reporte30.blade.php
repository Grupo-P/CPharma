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

    @page {size: landscape}

    @media print{
      .saltoDePagina{
        display:block;
        page-break-before:always;
      }
    </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Registro de Compras
  </h1>
  <hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
  $_GET['SEDE'] = FG_Mi_Ubicacion();

  $ArtJson = "";

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if (isset($_GET['Id'])){
  //CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR
  //se pasa a la carga de las facturas del proveedor
    $InicioCarga = new DateTime("now");

    R30_Proveedor_Factura($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['tipo'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    R30_Factura_Articulo_Traslado($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Registro de Compras');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdFact'])){
  //CASO 4: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    R30_Factura_Articulo($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Registro de Compras');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
  //Se pasa a la seleccion del proveedor
    $InicioCarga = new DateTime("now");

    $sql = R30Q_Lista_Proveedores();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    echo '
    <form autocomplete="off" action="">
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
    <br>';

    $cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
    $fechaInicioUrl = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
    $fechaFinUrl = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

    $selected50 = ($cantidad == '50') ? 'selected' : '';
    $selected100 = ($cantidad == '100') ? 'selected' : '';
    $selected200 = ($cantidad == '200') ? 'selected' : '';
    $selected500 = ($cantidad == '500') ? 'selected' : '';
    $selected1000 = ($cantidad == '1000') ? 'selected' : '';
    $selectedTodos = ($cantidad == 'Todos') ? 'selected' : '';

    echo '
    <form autocomplete="off" action="">
        <div class="col"><input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'"></div>

        <div class="row">
            <div class="col">Cantidad de registros</div>
            <div class="col">
                <select class="form-control form-control-sm" name="cantidad">
                    <option '.$selected50.' value="50">50</option>
                    <option '.$selected100.' value="100">100</option>
                    <option '.$selected200.' value="200">200</option>
                    <option '.$selected500.' value="500">500</option>
                    <option '.$selected1000.' value="1000">1000</option>
                    <option '.$selectedTodos.' value="Todos">Todos</option>
                </select>
            </div>

            <div class="col">Fecha inicio</div>
            <div class="col"><input type="date" value="'.$fechaInicioUrl.'" class="form-control form-control-sm" name="fechaInicio"></div>

            <div class="col">Fecha final</div>
            <div class="col"><input type="date" value="'.$fechaFinUrl.'" class="form-control form-control-sm" name="fechaFin"></div>

            <div class="col"><input type="submit" value="Buscar" class="btn btn-sm btn-block btn-outline-success"></div>
        </div>
    </form>
    <br>';

    R30_Proveedor_Factura_Top50($_GET['SEDE'],$cantidad,$fechaInicioUrl,$fechaFinUrl);

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
  /**********************************************************************************/
  /*
    TITULO: R30Q_Lista_Proveedores
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function R30Q_Lista_Proveedores() {
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
  function R30_Proveedor_Factura_Top50($SedeConnection,$cantidad,$inicio,$fin){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Factura_Proveedor_Top50($cantidad,$inicio,$fin);
    $result = sqlsrv_query($conn,$sql1);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myFilter" onkeyup="FilterAllTableConflicto()">
    </div>
    <br/>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Estatus Factura</th>
            <th scope="col" class="CP-sticky">Numero de Control</th>
            <th scope="col" class="CP-sticky">Numero de Factura</th>
            <th scope="col" class="CP-sticky">Fecha Documento</th>
            <th scope="col" class="CP-sticky">Fecha Registro</th>
            <th scope="col" class="CP-sticky">Total (Con IVA)</th>
            <th scope="col" class="CP-sticky">Total SKU</th>
            <th scope="col" class="CP-sticky">Total Unidades</th>
            <th scope="col" class="CP-sticky">Usuario Auditor</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
            <th scope="col" class="CP-sticky">Traslado</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $Estado = $row["Estado"];

      switch ($Estado) {
      case '1':
        $Estado = "En proceso";
      break;
      case '2':
        $Estado = "Procesada";
      break;
      default:
        $Estado = "Desconocido: ".$Estado;
      break;
    }

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.FG_Limpiar_Texto($row["nombreProveedor"]).'</td>';
      echo '<td align="center">'.$Estado.'</td>';
      echo '<td align="center">'.$row["NumeroControl"].'</td>';
      echo '<td align="center">'.$row["NumeroFactura"].'</td>';
      echo '<td align="center">'.$row["FechaDocumento"]->format('d-m-Y').'</td>';
      echo '<td align="center">'.$row["FechaRegistro"]->format('d-m-Y').'</td>';
      echo '<td align="center">'.number_format($row["Total"],2,"," ,"." ).'</td>';
      echo '<td align="center">'.$row["TotalSKU"].'</td>';
      echo '<td align="center">'.intval($row["TotalCantidad"]).'</td>';
      echo '<td align="center">'.$row["Auditoria_Usuario"].'</td>';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">

              <input type="submit" value="Seleccionar" class="btn btn-outline-success">

              <input id="IdFact" name="IdFact" type="hidden" value="'.intval($row["FacturaId"]).'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$row["IdProveedor"].'">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.FG_Limpiar_Texto($row["nombreProveedor"]).'">
          </form>
          <br>
        ';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">

            <input type="submit" value="Ver traslado" class="btn btn-outline-info">
            <input id="IdFact" name="IdFact" type="hidden" value="'.intval($row["FacturaId"]).'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$row["IdProveedor"].'">
            <input id="tipo" name="tipo" type="hidden" value="Traslado">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.FG_Limpiar_Texto($row["nombreProveedor"]).'">
          </form>
          <br>
        ';
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
    TITULO: R30_Proveedor_Factura
    FUNCION:  Arma la lista de factura por proveedores
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R30_Proveedor_Factura($SedeConnection,$IdProveedor,$NombreProveedor){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Factura_Proveedor_Toquel($IdProveedor);
    $result = sqlsrv_query($conn,$sql1);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
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
    echo '<td>'.FG_Limpiar_Texto($NombreProveedor).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Numero de Control</th>
            <th scope="col" class="CP-sticky">Numero de Factura</th>
            <th scope="col" class="CP-sticky">Fecha Documento</th>
            <th scope="col" class="CP-sticky">Fecha Registro</th>
            <th scope="col" class="CP-sticky">Total (Con IVA)</th>
            <th scope="col" class="CP-sticky">Total SKU</th>
            <th scope="col" class="CP-sticky">Total Unidades</th>
            <th scope="col" class="CP-sticky">Usuario Auditor</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
            <th scope="col" class="CP-sticky">Traslado</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["NumeroControl"].'</td>';
      echo '<td align="center">'.$row["NumeroFactura"].'</td>';
      echo '<td align="center">'.$row["FechaDocumento"]->format('d-m-Y').'</td>';
      echo '<td align="center">'.$row["FechaRegistro"]->format('d-m-Y').'</td>';
      echo '<td align="center">'.number_format($row["Total"],2,"," ,"." ).'</td>';
      echo '<td align="center">'.$row["TotalSKU"].'</td>';
      echo '<td align="center">'.intval($row["TotalCantidad"]).'</td>';
      echo '<td align="center">'.$row["Auditoria_Usuario"].'</td>';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">

              <input type="submit" value="Selecionar" class="btn btn-outline-success">

              <input id="IdFact" name="IdFact" type="hidden" value="'.intval($row["FacturaId"]).'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.FG_Limpiar_Texto($NombreProveedor).'">
          </form>
          <br>
        ';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">

            <input type="submit" value="Ver traslado" class="btn btn-outline-info">
            <input id="IdFact" name="IdFact" type="hidden" value="'.intval($row["FacturaId"]).'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$row["IdProveedor"].'">
            <input id="tipo" name="tipo" type="hidden" value="Traslado">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.FG_Limpiar_Texto($row["nombreProveedor"]).'">
          </form>
          <br>
        ';
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
    TITULO: R30Q_Factura_Proveedor_Top50
    FUNCION: Buscar la lista de facturas donde interviene el proveedor
    RETORNO: lista de facturas
    DESAROLLADO POR: SERGIO COVA
  */
  function R30Q_Factura_Proveedor_Top50($cantidad,$inicio,$fin) {

    if ($cantidad == '') {
        $cantidad = "TOP 50";
    }

    if ($cantidad == 'Todos') {
        $cantidad = '';
    }

    if (is_numeric($cantidad)) {
        $cantidad = "TOP $cantidad";
    }

    if ($inicio != '') {
        $where = " WHERE CONVERT(DATE,ComFactura.FechaRegistro) >= '$inicio' ";
    }

    if ($fin != '') {
        $where = " WHERE CONVERT(DATE,ComFactura.FechaRegistro) <= '$fin' ";
    }

    if ($inicio != '' && $fin != '') {
        $where = " WHERE CONVERT(DATE,ComFactura.FechaRegistro) >= '$inicio' AND CONVERT(DATE,ComFactura.FechaRegistro) <= '$fin' ";
    }

    if ($inicio == '' && $fin == '') {
        $where = "";
    }

    $sql = "
    SELECT $cantidad
    ComFactura.Id AS FacturaId,
    ComFactura.NumeroFactura,
    ComFactura.NumeroControl,
    ComFactura.Estado as Estado,
    CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
    CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRegistro,
    ComFactura.M_MontoTotalNeto AS Total,
    (SELECT COUNT(1) FROM ComFacturaDetalle WHERE ComFacturaDetalle.ComFacturaId = ComFactura.Id) AS TotalSKU,
    (SELECT SUM(ComFacturaDetalle.CantidadFacturada) FROM ComFacturaDetalle WHERE ComFacturaDetalle.ComFacturaId = ComFactura.Id) AS TotalCantidad,
    ComFactura.Auditoria_Usuario,
    GenPersona.Nombre as nombreProveedor,
    ComProveedor.Id as IdProveedor
    FROM ComFactura
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON ComProveedor.GenPersonaId = GenPersona.Id
    $where
    ORDER BY CONVERT(DATE,ComFactura.FechaRegistro)DESC , CONVERT(DATE,ComFactura.FechaDocumento) DESC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R30Q_Factura_Proveedor_Toquel
    FUNCION: Buscar la lista de facturas donde interviene el proveedor
    RETORNO: lista de facturas
    DESAROLLADO POR: SERGIO COVA
  */
  function R30Q_Factura_Proveedor_Toquel($IdProveedor) {
    $sql = "
    SELECT
    ComFactura.ComProveedorId AS IdProveedor,
    (SELECT GenPersona.Nombre FROM GenPersona WHERE Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = $IdProveedor)) AS nombreProveedor,
    ComFactura.Id AS FacturaId,
    ComFactura.NumeroFactura,
    ComFactura.NumeroControl,
    CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
    CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRegistro,
    ComFactura.M_MontoTotalNeto AS Total,
    (SELECT COUNT(1) FROM ComFacturaDetalle WHERE ComFacturaDetalle.ComFacturaId = ComFactura.Id) AS TotalSKU,
    (SELECT SUM(ComFacturaDetalle.CantidadFacturada) FROM ComFacturaDetalle WHERE ComFacturaDetalle.ComFacturaId = ComFactura.Id) AS TotalCantidad,
    ComFactura.Auditoria_Usuario
    FROM ComFactura
    WHERE ComFactura.ComProveedorId = '$IdProveedor'
    ORDER BY FechaRegistro DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R30_Factura_Articulo
    FUNCION: arma la lista de articulos por factura
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R30_Factura_Articulo($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura){

    $connCPharma = FG_Conectar_CPharma();

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Factura_Articulo($IdFatura);
    $result = sqlsrv_query($conn,$sql1);

    $sqlNFact = R30Q_Numero_Factura($IdFatura);
    $resultNFact = sqlsrv_query($conn,$sqlNFact);
    $rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
    $NumeroFactura = ($rowNFact["NumeroFactura"])?$rowNFact["NumeroFactura"]:"-";
    $Operador = ($rowNFact["Operador"])?$rowNFact["Operador"]:"-";
    $NumeroControl = $rowNFact["NumeroControl"];
    $Estado = $rowNFact["Estado"];

    $FechaRegistro = $rowNFact["FechaRegistro"];

    $TasaActual = FG_Tasa_Fecha($connCPharma,$FechaRegistro->format('Y-m-d'));
    $TasaActual = ($TasaActual!="" && $TasaActual!=null)?$TasaActual:"0";

    switch ($Estado) {
      case '1':
        $Estado = "En proceso";
      break;
      case '2':
        $Estado = "Procesada";
      break;
      default:
        $Estado = "Desconocido: ".$Estado;
      break;
    }

    $fechaDocumento = $rowNFact["FechaDocumento"];
    $fechaDocumentoMostrar = $fechaDocumento->format('d-m-Y');
    $fechaDocumentoLink = $fechaDocumento->format('Y-m-d');

    $fechaActual = new DateTime('now');
    $fechaActualMostrar = date_format($fechaActual,'d-m-Y');
    $fechaActual = date_format($fechaActual,'Y-m-d');

    $NombreProveedor = FG_Limpiar_Texto($NombreProveedor);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar" style="width:95%;">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTableDoubleHeader()">
      <input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">
    </div>
    <br/>
    ';

    echo '
    <table class="table table-striped table-bordered col-12" style="width:70%;">
      <thead class="thead-dark">
          <tr>
            <th scope="col">Estatus Factura</th>
            <th scope="col">Operador</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Numero de Factura</th>
            <th scope="col">Numero de Control</th>
            <th scope="col">Tasa Mercado <br> (Fecha de Registro de Factura) <br>'.SigVe.'</th>
            <th scope="col">Fecha Factura</th>
            <th scope="col">Fecha de Registro</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
      echo '<td align="center">'.$Estado.'</td>';
      echo '<td align="center">'.$Operador.'</td>';
      echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .$NombreProveedor.
        '</a>
        </td>';
      echo '<td align="center">'.$NumeroFactura.'</td>';
      echo '<td align="center">'.$NumeroControl.'</td>';
      echo '<td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      echo '<td align="center">'.$fechaDocumentoMostrar.'</td>';
      echo '<td align="center">'.$FechaRegistro->format('d-m-Y').'</td>';
      echo '
        </tr>
        <tr>
          <td scope="col" align="center"><input type="checkbox"></td>
          <td></td>
          <td scope="col" align="center"><input type="checkbox"></td>
          <td scope="col" align="center"><input type="checkbox"></td>
          <td colspan="4"></td>
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th colspan="16 bg-dark CP-sticky"></th>
            <th colspan="6" scope="col" class="CP-sticky bg-info">Relacion en '.SigVe.'</th>
            <th colspan="6" scope="col" class="CP-sticky bg-success">Relacion en '.SigDolar.'</th>
          </tr>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de Barra</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Existencia</th>
            <th scope="col">Dolarizado?</td>
            <th scope="col">Gravado?</td>
            <th scope="col">Marca</td>
            <th scope="col">Nuevo?</td>
            <th scope="col">Cantidad Recibida</th>
            <th scope="col">Lote del fabricante</th>
            <th scope="col">Fecha de vencimiento</th>
            <th scope="col">Vida Util</th>
            <th scope="col">Utilidad</th>
            <th scope="col">Precio </br> (Con IVA) '.SigVe.'</th>
            <th scope="col">Troquel </br> (Con IVA) '.SigVe.'</th>

            <th scope="col" class="CP-sticky bg-info">Costo Bruto </br> (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Costo Neto </br> (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Subtotal </br> (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Impuesto '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Descuento Especifico <br> y Global '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Total '.SigVe.'</th>

            <th scope="col" class="CP-sticky bg-success">Costo Bruto </br> (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Costo Neto </br> (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Subtotal </br> (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Impuesto '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Descuento Especifico <br> y Global '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Total '.SigDolar.'</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    $SumImpuestoBs = $SumDescBs = $SumTotalBs = $SumImpuestoDol = $SumDescDol = $SumTotalDol = $SumCantidad = $SumSKU = $SumSubTotalBs = $SumSubTotalDol = 0;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

      $IdArticulo = $row["Id"];
      $TroqueladoGen = ($row["M_PrecioTroquelado"])?$row["M_PrecioTroquelado"]:"0";
      $CostoBrutoGen = ($row["M_PrecioCompraBruto"])?$row["M_PrecioCompraBruto"]:"0";
      $CostoNetoGen = ($row["M_PrecioCompraNeto"])?$row["M_PrecioCompraNeto"]:"0";
      $CantidadRecibida = intval($row['CantidadRecibidaFactura']);
      $PorcentajeDescuentoGlobal = ($row["PorcentajeDescuentoGlobal"])?$row["PorcentajeDescuentoGlobal"]:"0";

      $SumCantidad += $CantidadRecibida;

      $sql2 = SQG_Detalle_Articulo($IdArticulo);
      $result2 = sqlsrv_query($conn,$sql2);
      $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

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
      $UltimaVenta = $row2["UltimaVenta"];
      $Marca = ($row2["Marca"])?FG_Limpiar_Texto($row2["Marca"]):"-";
      $FechaCreacion = ($row2["FechaCreacion"])?$row2["FechaCreacion"]:"-";

      $nuevo = FG_Rango_Dias($FechaCreacion->format('Y-m-d'),date('Y-m-d'));
      $nuevo = ($nuevo==0)?"SI":"NO";

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
      $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
      $Utilidad = (1 - $Utilidad)*100;

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';

      echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';

      echo '<td align="center">'.$Existencia.'</td>';
      echo '<td align="center">'.$Dolarizado.'</td>';
      echo '<td align="center">'.$Gravado.'</td>';
      echo '<td align="center">'.$Marca.'</td>';
      echo '<td align="center">'.$nuevo.'</td>';

      echo
        '<td align="center" class="CP-barrido">
        <a href="/reporte6?pedido=15&fechaInicio='.$fechaDocumentoLink.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&flag=BsqDescrip&Descrip='.$Descripcion.'&IdD='.$IdArticulo.'&CodBar=&IdCB=" style="text-decoration: none; color: black;" target="_blank">'
          .$CantidadRecibida.
        '</a>
        </td>';

      echo '<td align="center">'.$row["NumeroLoteFabricante"].'</td>';

      if($row["FechaVencimiento"]!=NULL){
        echo '<td align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      if($row["FechaVencimiento"]!=NULL && $FechaRegistro!=NULL){
        $VidaUtil = FG_Rango_Dias_Negativo($FechaRegistro->format('Y-m-d'), $row["FechaVencimiento"]->format('Y-m-d'));
        echo '<td align="center">'.$VidaUtil.'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      echo '<td align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

      echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';

      echo '<td align="center">'.number_format($TroqueladoGen,2,"," ,"." ).'</td>';

      $descuento = ($CostoBrutoGen-$CostoNetoGen)*$CantidadRecibida;
      $subtotal = $CantidadRecibida * $CostoBrutoGen;
      $impuesto = (Impuesto-1)*($subtotal-$descuento);
      $impuesto = ($IsIVA==FG_ID_Impuesto_Sede())?$impuesto:"0";
      $Total = ($subtotal+$impuesto)-$descuento;

      if($Dolarizado=="SI" && $TasaActual!=0){
        $descuentoD = $descuento/$TasaActual;
        $subtotalD = $subtotal/$TasaActual;
        $impuestoD = $impuesto/$TasaActual;
        $TotalD = $Total/$TasaActual;

        $SumSubTotalDol += $subtotalD;
        $SumImpuestoDol += $impuestoD;
        $SumDescDol += $descuentoD;
        $SumTotalDol += $TotalD;
      }

      $SumSubTotalBs += $subtotal;
      $SumImpuestoBs += $impuesto;
      $SumDescBs += $descuento;
      $SumTotalBs += $Total;

      //Seccion en bolivares
      echo '<td align="center" class="bg-info">'.number_format($CostoBrutoGen,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-info">'.number_format($CostoNetoGen,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-info">'.number_format($subtotal,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-info">'.number_format($impuesto,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-info">'.number_format($descuento,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-info">'.number_format($Total,2,"," ,"." ).'</td>';

      //Seccion en dolares
      if($Dolarizado=="SI" && $TasaActual!=0){
        echo '<td align="center" class="bg-success">'.number_format($CostoBrutoGen/$TasaActual,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-success">'.number_format($CostoNetoGen/$TasaActual,2,"," ,"." ).'</td>';
        echo '<td align="center" class="bg-success">'.number_format($subtotalD,2,"," ,"." ).'</td>';
        echo '<td align="center" class="bg-success">'.number_format($impuestoD,2,"," ,"." ).'</td>';
        echo '<td align="center" class="bg-success">'.number_format($descuentoD,2,"," ,"." ).'</td>';
        echo '<td align="center" class="bg-success">'.number_format($TotalD,2,"," ,"." ).'</td>';
      }
      else{
        echo '<td align="center" class="bg-success">-</td>';
        echo '<td align="center" class="bg-success">-</td>';
        echo '<td align="center" class="bg-success">-</td>';
        echo '<td align="center" class="bg-success">-</td>';
        echo '<td align="center" class="bg-success">-</td>';
        echo '<td align="center" class="bg-success">-</td>';
      }

      echo'<td scope="col" align="center"><input type="checkbox"></td>';
      echo '</tr>';
    $contador++;
    $SumSKU++;
    }

    echo'
    <tr>
      <td colspan="16" align="right"><strong>Totales</strong></td>
      <td align="center">'.number_format($SumSubTotalBs,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumImpuestoBs,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumDescBs,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumTotalBs,2,"," ,"." ).'</td>
      <td colspan="2" align="right"></td>
      <td align="center">'.number_format($SumSubTotalDol,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumImpuestoDol,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumDescDol,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumTotalDol,2,"," ,"." ).'</td>
    </tr>
    <tr>
      <td colspan="17" align="right"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td colspan="3" align="right"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
    </tr>
    ';

    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);

    echo "<strong>Fecha de Impresion: ".date('d/m/Y')." a las ".date('h:i a')."</strong><br><br>";

    echo'
      <table class="table table-striped table-bordered col-12" style="width:40%;">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Total SKU</th>
            <th scope="col">Total Unidades</th>
            <th scope="col">Descuento Global</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td align="center">'.$SumSKU.'</td>
            <td align="center">'.$SumCantidad.'</td>
            <td align="center">'.($PorcentajeDescuentoGlobal*100).' %</td>
          </tr>
        </tbody>
      </table>
    ';

    echo'
      <table class="table table-striped table-bordered col-12" style="width:40%;">
        <thead class="thead-dark">
          <tr>
            <th scope="col" colspan="2">Departamento de almacen</th>
            <th scope="col" colspan="2">Departamento de administracion</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td align="center">Nombre</td>
            <td align="center">__________________</td>
            <td align="center">Nombre</td>
            <td align="center">__________________</td>
          </tr>
          <tr>
            <td align="center">Apellido</td>
            <td align="center">__________________</td>
            <td align="center">Apellido</td>
            <td align="center">__________________</td>
          </tr>
          <tr>
            <td align="center">Fecha</td>
            <td align="center">__________________</td>
            <td align="center">Fecha</td>
            <td align="center">__________________</td>
          </tr>
          <tr>
            <td align="center">Hora</td>
            <td align="center">__________________</td>
            <td align="center">Hora</td>
            <td align="center">__________________</td>
          </tr>
          <tr>
            <td align="center">Firma</td>
            <td align="center">__________________</td>
            <td align="center">Firma</td>
            <td align="center">__________________</td>
          </tr>
        </tbody>
      </table>
    ';
  }


  /**********************************************************************************/
  /*
    TITULO: R30_Factura_Articulo_Traslado
    FUNCION: arma la lista de articulos y su existencia en otras sedes por factura
    RETORNO: no aplica
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R30_Factura_Articulo_Traslado($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura){

    $connCPharma = FG_Conectar_CPharma();

    $conectividad_ftn = FG_Validar_Conectividad('FTN');
    $conectividad_fau = FG_Validar_Conectividad('FAU');
    $conectividad_fll = FG_Validar_Conectividad('FLL');
    $conectividad_fsm = FG_Validar_Conectividad('FSM');

    $connFAU = FG_Conectar_Smartpharma('FAU');
    $connFTN = FG_Conectar_Smartpharma('FTN');
    $connFLL = FG_Conectar_Smartpharma('FLL');
    $connFSM = FG_Conectar_Smartpharma('FSM');

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Factura_Articulo($IdFatura);
    $result = sqlsrv_query($conn,$sql1);

    $sqlNFact = R30Q_Numero_Factura($IdFatura);
    $resultNFact = sqlsrv_query($conn,$sqlNFact);
    $rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
    $NumeroFactura = ($rowNFact["NumeroFactura"])?$rowNFact["NumeroFactura"]:"-";
    $Operador = ($rowNFact["Operador"])?$rowNFact["Operador"]:"-";
    $NumeroControl = $rowNFact["NumeroControl"];
    $Estado = $rowNFact["Estado"];

    $FechaRegistro = $rowNFact["FechaRegistro"];

    $TasaActual = FG_Tasa_Fecha($connCPharma,$FechaRegistro->format('Y-m-d'));
    $TasaActual = ($TasaActual!="" && $TasaActual!=null)?$TasaActual:"0";

    switch ($Estado) {
      case '1':
        $Estado = "En proceso";
      break;
      case '2':
        $Estado = "Procesada";
      break;
      default:
        $Estado = "Desconocido: ".$Estado;
      break;
    }

    $fechaDocumento = $rowNFact["FechaDocumento"];
    $fechaDocumentoMostrar = $fechaDocumento->format('d-m-Y');
    $fechaDocumentoLink = $fechaDocumento->format('Y-m-d');

    $fechaActual = new DateTime('now');
    $fechaActualMostrar = date_format($fechaActual,'d-m-Y');
    $fechaActual = date_format($fechaActual,'Y-m-d');

    $NombreProveedor = FG_Limpiar_Texto($NombreProveedor);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar" style="width:95%;">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
      <input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">
    </div>
    <br/>
    ';

    echo '
    <table class="table table-striped table-bordered col-12" style="width:70%;">
      <thead class="thead-dark">
          <tr>
            <th scope="col">Estatus Factura</th>
            <th scope="col">Operador</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Numero de Factura</th>
            <th scope="col">Numero de Control</th>
            <th scope="col">Tasa Mercado <br> (Fecha de Registro de Factura) <br>'.SigVe.'</th>
            <th scope="col">Fecha Factura</th>
            <th scope="col">Fecha de Registro</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
      echo '<td align="center">'.$Estado.'</td>';
      echo '<td align="center">'.$Operador.'</td>';
      echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .$NombreProveedor.
        '</a>
        </td>';
      echo '<td align="center">'.$NumeroFactura.'</td>';
      echo '<td align="center">'.$NumeroControl.'</td>';
      echo '<td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      echo '<td align="center">'.$fechaDocumentoMostrar.'</td>';
      echo '<td align="center">'.$FechaRegistro->format('d-m-Y').'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th class="CP-sticky" scope="col">#</th>
            <th class="CP-sticky" scope="col">Codigo</th>
            <th class="CP-sticky" scope="col">Codigo de Barra</th>
            <th class="CP-sticky" scope="col">Descripcion</th>
            <th class="CP-sticky" scope="col">Existencia actual</th>
            <th class="CP-sticky" scope="col">Cantidad Recibida</th>';

    if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
        echo '
            <th scope="col" class="CP-sticky">Existencia FTN</td>
            <th scope="col" class="CP-sticky">Existencia FLL</td>
            <th scope="col" class="CP-sticky">Existencia FSM</td>
        ';
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
        echo '
            <th scope="col" class="CP-sticky">Existencia FAU</td>
            <th scope="col" class="CP-sticky">Existencia FLL</td>
            <th scope="col" class="CP-sticky">Existencia FSM</td>
        ';
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
        echo '
            <th scope="col" class="CP-sticky">Existencia FTN</td>
            <th scope="col" class="CP-sticky">Existencia FAU</td>
            <th scope="col" class="CP-sticky">Existencia FSM</td>
        ';
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
        echo '
            <th scope="col" class="CP-sticky">Existencia FTN</td>
            <th scope="col" class="CP-sticky">Existencia FAU</td>
            <th scope="col" class="CP-sticky">Existencia FLL</td>
        ';
      }

    echo '
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    $SumImpuestoBs = $SumDescBs = $SumTotalBs = $SumImpuestoDol = $SumDescDol = $SumTotalDol = $SumCantidad = $SumSKU = $SumSubTotalBs = $SumSubTotalDol = 0;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

      $IdArticulo = $row["Id"];
      $TroqueladoGen = ($row["M_PrecioTroquelado"])?$row["M_PrecioTroquelado"]:"0";
      $CostoBrutoGen = ($row["M_PrecioCompraBruto"])?$row["M_PrecioCompraBruto"]:"0";
      $CostoNetoGen = ($row["M_PrecioCompraNeto"])?$row["M_PrecioCompraNeto"]:"0";
      $CantidadRecibida = intval($row['CantidadRecibidaFactura']);
      $PorcentajeDescuentoGlobal = ($row["PorcentajeDescuentoGlobal"])?$row["PorcentajeDescuentoGlobal"]:"0";

      $SumCantidad += $CantidadRecibida;

      $sql2 = SQG_Detalle_Articulo($IdArticulo);
      $result2 = sqlsrv_query($conn,$sql2);
      $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

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
      $UltimaVenta = $row2["UltimaVenta"];
      $Marca = ($row2["Marca"])?$row2["Marca"]:"-";
      $FechaCreacion = ($row2["FechaCreacion"])?$row2["FechaCreacion"]:"-";

      $nuevo = FG_Rango_Dias($FechaCreacion->format('Y-m-d'),date('Y-m-d'));
      $nuevo = ($nuevo==0)?"SI":"NO";

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
      $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';

      echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';

      echo '<td align="center">'.$Existencia.'</td>';

      echo
        '<td align="center" class="CP-barrido">
        <a href="/reporte6?pedido=15&fechaInicio='.$fechaDocumentoLink.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&flag=BsqDescrip&Descrip='.$Descripcion.'&IdD='.$IdArticulo.'&CodBar=&IdCB=" style="text-decoration: none; color: black;" target="_blank">'
          .$CantidadRecibida.
        '</a>
        </td>';

      if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
        if ($conectividad_ftn == 1) {
          $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFTN,$sql3);

          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $existencia_ftn = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
        }

        if ($conectividad_fll == 1) {
          $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFLL,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $existencia_fll = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
        }

        if ($conectividad_fsm == 1) {
          $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFSM,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $existencia_fsm = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
        }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
        if ($conectividad_fau == 1) {
            $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $existencia_fau = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
          }

          if ($conectividad_fll == 1) {
            $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $existencia_fll = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
          }

          if ($conectividad_fsm == 1) {
              $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
              $result3 = sqlsrv_query($connFSM,$sql3);
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $existencia_fsm = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
            }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
         if ($conectividad_ftn == 1) {
            $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $existencia_ftn = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
          }

          if ($conectividad_fau == 1) {
            $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $existencia_fau = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
          }

          if ($conectividad_fsm == 1) {
              $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
              $result3 = sqlsrv_query($connFSM,$sql3);
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $existencia_fsm = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
            }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
         if ($conectividad_ftn == 1) {
            $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $existencia_ftn = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
          }

          if ($conectividad_fau == 1) {
            $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $existencia_fau = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
          }

          if ($conectividad_fll == 1) {
              $sql3 = R30_Q_Descripcion_Existencia_Articulo($CodigoBarra);
              $result3 = sqlsrv_query($connFLL,$sql3);
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $existencia_fll = ($row3['existencia'] || $row3['existencia'] == 0) ? intval($row3['existencia']) : 'No existe';
            }
      }

      if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1) {
                echo '<td align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1) {
                echo '<td align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1) {
                echo '<td align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }

            if ($conectividad_fau == 1) {
                echo ' <td align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td align="center">Sin conexión</td>';
            }
        }

      echo '</tr>';
    $contador++;
    $SumSKU++;
    }

    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);
  }


  /**********************************************************************************/
  /*
    TITULO: R30Q_Factura_Articulo
    FUNCION: busca los articulos que intervienen en una factura
    RETORNO: lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R30Q_Factura_Articulo($IdFatura) {
    $sql = "
    SELECT
    ComFacturaDetalle.ComFacturaId AS FacturaId,
    InvArticulo.Id,
    InvArticulo.CodigoArticulo,
    InvArticulo.Descripcion,
    InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto,
    (ROUND(CAST((ComFacturaDetalle.CantidadRecibidaFactura) AS DECIMAL(38,0)),2,0))  AS CantidadRecibidaFactura,
    (ROUND(CAST((ComFacturaDetalle.M_PrecioCompraBruto) AS DECIMAL(38,2)),2,0))  AS M_PrecioCompraBruto,
    (ROUND(CAST((ComFacturaDetalle.M_PrecioCompraNeto) AS DECIMAL(38,2)),2,0))  AS M_PrecioCompraNeto,
    (ROUND(CAST((ComFacturaDetalle.M_PrecioTroquelado) AS DECIMAL(38,2)),2,0))  AS M_PrecioTroquelado,
    ComFacturaDetalle.PorcentajeDescuentoGlobal as PorcentajeDescuentoGlobal,
    ComFacturaDetalle.NumeroLoteFabricante AS NumeroLoteFabricante,
    CONVERT(DATE,ComFacturaDetalle.FechaVencimiento) AS FechaVencimiento
    FROM ComFacturaDetalle
    INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
    WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura'
    ORDER BY ComFacturaDetalle.Secuencia ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R30Q_Numero_Factura
    FUNCION:
    RETORNO:
    DESAROLLADO POR: SERGIO COVA
  */
  function R30Q_Numero_Factura($IdFactura) {
    $sql = "
      SELECT ComFactura.NumeroFactura AS NumeroFactura,
      ComFactura.Estado as Estado,
      ComFactura.Auditoria_Usuario as Operador,
      ComFactura.NumeroControl as NumeroControl,
      CONVERT(DATE,ComFactura.FechaRegistro) as FechaRegistro,
      CONVERT(DATE,ComFactura.FechaDocumento) As FechaDocumento
      FROM ComFactura
      WHERE ComFactura.Id = '$IdFactura'
    ";
    return $sql;
  }

  /*
    TITULO: R30_Q_Descripcion_Existencia_Articulo
    FUNCION: Query que genera descripcion y existencia de articulo
    RETORNO: Descripcion/existencia de articulo
    DESAROLLADO POR: NISA DELGADO
  */
  function R30_Q_Descripcion_Existencia_Articulo($CodigoBarra)
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
?>
