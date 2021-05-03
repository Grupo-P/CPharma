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
    Registro de Reclamos
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
  else if(isset($_GET['IdReclamo'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    R30_Reclamo_Articulo($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdReclamo']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Registro de Reclamos');

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
    <br><br>';

    R38_Proveedor_Reclamo_Top50($_GET['SEDE']);

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
  function R38Q_Lista_Proveedores() {
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
  function R38_Proveedor_Reclamo_Top50($SedeConnection){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Reclamo_Proveedor_Top50();
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
            <th scope="col" class="CP-sticky">Estado reclamo</th>
            <th scope="col" class="CP-sticky">Número del reclamo</th>
            <th scope="col" class="CP-sticky">Factura asociada</th>
            <th scope="col" class="CP-sticky">Fecha factura</th>
            <th scope="col" class="CP-sticky">Registro factura</th>
            <th scope="col" class="CP-sticky">Fecha reclamo</th>
            <th scope="col" class="CP-sticky">Total reclamo (Con IVA)</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $proveedor = FG_Limpiar_Texto($row['proveedor']);
      $id_proveedor = $row['id_proveedor'];
      $estado = $row['estado'];
      $numero_reclamo = $row['numero_reclamo'];
      $factura_asociada = $row['factura_asociada'];
      $fecha_factura = ($row['fecha_factura']) ? $row['fecha_factura']->format('d-m-Y') : '00-00-0000';
      $registro_factura = ($row['registro_factura']) ? $row['registro_factura']->format('d-m-Y') : '00-00-0000';
      $fecha_reclamo = ($row['fecha_reclamo']) ? $row['fecha_reclamo']->format('d-m-Y') : '00-00-0000';
      $total_reclamo = number_format($row['total_reclamo'], 2, '.', ',');
      $operador = $row['operador'];
      $id_factura = $row['id_factura'];
      $background = ($estado == 'En proceso') ? 'bg-warning' : '';
      $id_reclamo = $row['id_reclamo'];

      echo '<tr class="'.$background.'">';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';            
      echo '<td align="left">'.$proveedor.'</td>';
      echo '<td align="center">'.$estado.'</td>';
      echo '<td align="center">'.$numero_reclamo.'</td>';
      echo '<td align="center">'.$factura_asociada.'</td>';
      echo '<td align="center">'.$fecha_factura.'</td>';
      echo '<td align="center">'.$registro_factura.'</td>';
      echo '<td align="center">'.$fecha_reclamo.'</td>';
      echo '<td align="center">'.$total_reclamo.'</td>';
      echo '<td align="center">'.$operador.'</td>';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
             
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              
              <input id="IdReclamo" name="IdReclamo" type="hidden" value="'.$id_reclamo.'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$id_proveedor.'">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.$proveedor.'">
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
    $sql1 = R30Q_Buscar_Reclamos_Proveedor($IdProveedor);
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
            <th scope="col" class="CP-sticky">Estado reclamo</th>
            <th scope="col" class="CP-sticky">Número del reclamo</th>
            <th scope="col" class="CP-sticky">Factura asociada</th>
            <th scope="col" class="CP-sticky">Fecha factura</th>
            <th scope="col" class="CP-sticky">Registro factura</th>
            <th scope="col" class="CP-sticky">Fecha reclamo</th>
            <th scope="col" class="CP-sticky">Total reclamo (Con IVA)</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $proveedor = FG_Limpiar_Texto($row['proveedor']);
      $id_proveedor = $row['id_proveedor'];
      $estado = $row['estado'];
      $numero_reclamo = $row['numero_reclamo'];
      $factura_asociada = $row['factura_asociada'];
      $fecha_factura = ($row['fecha_factura']) ? $row['fecha_factura']->format('d-m-Y') : '00-00-0000';
      $registro_factura = ($row['registro_factura']) ? $row['registro_factura']->format('d-m-Y') : '00-00-0000';
      $fecha_reclamo = ($row['fecha_reclamo']) ? $row['fecha_reclamo']->format('d-m-Y') : '00-00-0000';
      $total_reclamo = number_format($row['total_reclamo'], 2, '.', ',');
      $operador = $row['operador'];
      $id_factura = $row['id_factura'];
      $background = ($estado == 'En proceso') ? 'bg-warning' : '';
      $id_reclamo = $row['id_reclamo'];

      echo '<tr class="'.$background.'">';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$estado.'</td>';
      echo '<td align="center">'.$numero_reclamo.'</td>';
      echo '<td align="center">'.$factura_asociada.'</td>';
      echo '<td align="center">'.$fecha_factura.'</td>';
      echo '<td align="center">'.$registro_factura.'</td>';
      echo '<td align="center">'.$fecha_reclamo.'</td>';
      echo '<td align="center">'.$total_reclamo.'</td>';
      echo '<td align="center">'.$operador.'</td>';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
             
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              
              <input id="IdReclamo" name="IdReclamo" type="hidden" value="'.$id_reclamo.'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$id_proveedor.'">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.$proveedor.'">
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
    TITULO: R30Q_Reclamo_Proveedor_Top50
    FUNCION: Buscar la lista de reclamos donde interviene el proveedor
    RETORNO: lista de reclamos
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R30Q_Reclamo_Proveedor_Top50() {
    $sql = "
      SELECT TOP 50
        ComReclamo.Id As id_reclamo,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = ComReclamo.ComProveedorId)) AS proveedor,
        ComReclamo.ComProveedorId AS id_proveedor,
        IIF (ComReclamo.estado = 1, 'En proceso', 'Procesado') AS estado,
        ComReclamo.NumeroReclamo AS numero_reclamo,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS factura_asociada,
        (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS id_factura,
        (SELECT ComFactura.FechaDocumento FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS fecha_factura,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS registro_factura,
        ComReclamo.FechaRegistro AS fecha_reclamo,
        ComReclamo.M_Total AS total_reclamo,
        ComReclamo.Auditoria_Usuario AS operador
      FROM ComReclamo
      ORDER BY FechaRegistro DESC;
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R30Q_Buscar_Reclamos_Proveedor
    FUNCION: Buscar la lista de reclamos donde interviene el proveedor
    RETORNO: lista de reclamos
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R30Q_Buscar_Reclamos_Proveedor($IdProveedor) {
    $sql = "
      SELECT TOP 50
        ComReclamo.Id As id_reclamo,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = ComReclamo.ComProveedorId)) AS proveedor,
        ComReclamo.ComProveedorId AS id_proveedor,
        IIF (ComReclamo.estado = 1, 'En proceso', 'Procesado') AS estado,
        ComReclamo.NumeroReclamo AS numero_reclamo,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS factura_asociada,
        (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS id_factura,
        (SELECT ComFactura.FechaDocumento FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS fecha_factura,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS registro_factura,
        ComReclamo.FechaRegistro AS fecha_reclamo,
        ComReclamo.M_Total AS total_reclamo,
        ComReclamo.Auditoria_Usuario AS operador
      FROM ComReclamo
      WHERE ComReclamo.ComProveedorId = '$IdProveedor'
      ORDER BY FechaRegistro DESC;
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R30_Reclamo_Articulo
    FUNCION: arma la lista de articulos por reclamo
    RETORNO: no aplica
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R30_Reclamo_Articulo($SedeConnection,$IdProveedor,$NombreProveedor,$IdReclamo){

    $connCPharma = FG_Conectar_CPharma();
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Reclamo_Articulo($IdReclamo);
    $result = sqlsrv_query($conn,$sql1);

    $sqlNReclamo = R38Q_Numero_Reclamo($IdReclamo);
    $resultNReclamo = sqlsrv_query($conn,$sqlNReclamo);
    $rowNReclamo = sqlsrv_fetch_array($resultNReclamo,SQLSRV_FETCH_ASSOC);

    $estado = $rowNReclamo['estado'];
    $operador = $rowNReclamo['operador'];
    $proveedor = $rowNReclamo['proveedor'];
    $id_proveedor = $rowNReclamo['id_proveedor'];
    $numero_factura = $rowNReclamo['numero_factura'];
    $numero_reclamo = $rowNReclamo['numero_reclamo'];
    $fecha_factura = ($rowNReclamo['fecha_factura']) ? $rowNReclamo['fecha_factura']->format('d-m-Y') : '00-00-0000';
    $registro_factura = ($rowNReclamo['registro_factura']) ? $rowNReclamo['registro_factura']->format('d-m-Y') : '00-00-0000';
    $fecha_reclamo = ($rowNReclamo['fecha_reclamo']) ? $rowNReclamo['fecha_reclamo']->format('d-m-Y') : '00-00-0000';

    $TasaActual = FG_Tasa_Fecha($connCPharma,$rowNReclamo['registro_factura']->format('Y-m-d'));
    $TasaActual = ($TasaActual!="" && $TasaActual!=null)?$TasaActual:"0";
    $tasa_mercado = $TasaActual;

    $total_reclamo_bs = $rowNReclamo['total_reclamo'];
    $total_reclamo_ds = $rowNReclamo['total_reclamo'] / $tasa_mercado;

    $fechaDocumento = $rowNReclamo["fecha_factura"];
    $fechaDocumentoMostrar = $fechaDocumento->format('d-m-Y');
    $fechaDocumentoLink = $fechaDocumento->format('Y-m-d');

    $fechaActual = new DateTime('now');
    $fechaActualMostrar = date_format($fechaActual,'d-m-Y');
    $fechaActual = date_format($fechaActual,'Y-m-d');

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
            <th scope="col">Estado reclamo</th>
            <th scope="col">Operador</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Número factura</th>
            <th scope="col">Número reclamo</th>
            <th scope="col">Fecha factura</th>
            <th scope="col">Registro factura</th>
            <th scope="col">Fecha reclamo</th>
            <th scope="col">Tasa Mercado <br> (Fecha de registro de factura) <br>'.SigVe.'</th>
            <th scope="col">Total reclamo <br> (Sin IVA) <br>'.SigVe.'</th>
            <th scope="col">Total reclamo <br> (Sin IVA) <br>'.SigDolar.'</th>
          </tr>          
        </thead>
        <tbody>
        <tr>
      ';
      echo '<td align="center">'.$estado.'</td>';
      echo '<td align="center">'.$operador.'</td>';
      echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte7?Nombre='.$proveedor.'&Id='.$id_proveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .$proveedor.
        '</a>
        </td>';
      echo '<td align="center">'.$numero_factura.'</td>';
      echo '<td align="center">'.$numero_reclamo.'</td>';          
      echo '<td align="center">'.$fecha_factura.'</td>';
      echo '<td align="center">'.$registro_factura.'</td>';
      echo '<td align="center">'.$fecha_reclamo.'</td>';
      echo '<td align="center">'.number_format($tasa_mercado, 2, ',', '.').'</td>';
      echo '<td align="center">'.number_format($total_reclamo_bs, 2, ',', '.').'</td>';
      echo '<td align="center">'.number_format($total_reclamo_ds, 2, ',', '.').'</td>';
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
            <th colspan="11 bg-dark CP-sticky"></th>
            <th colspan="5" scope="col" class="CP-sticky bg-info">Relacion en '.SigVe.'</th>
            <th colspan="5" scope="col" class="CP-sticky bg-success">Relacion en '.SigDolar.'</th>
          </tr>          
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo interno</th>
            <th scope="col">Codigo de barra</th>
            <th scope="col">Descripción</th>            
            <th scope="col">Dolarizado?</td>
            <th scope="col">Gravado?</td>
            <th scope="col">Causa</td>
            <th scope="col">Cantidad</td>
            <th scope="col">Número Lote</td>
            <th scope="col">Fecha de vencimiento</td>
            <th scope="col">Vida util</th>

            <th scope="col" class="CP-sticky bg-info">Costo Bruto </br> (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Costo Neto </br> (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Subtotal </br> (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Impuesto '.SigVe.'</th>
            <th scope="col" class="CP-sticky bg-info">Total '.SigVe.'</th>

            <th scope="col" class="CP-sticky bg-success">Costo Bruto </br> (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Costo Neto </br> (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Subtotal </br> (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky bg-success">Impuesto '.SigDolar.'</th>
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

      $Causa = $row['Causa'];

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
    
      echo '<td align="center">'.$Dolarizado.'</td>';
      echo '<td align="center">'.$Gravado.'</td>';
      echo '<td align="center">'.$Causa.'</td>';
      
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

      if($row["FechaVencimiento"]!=NULL && $rowNReclamo['registro_factura']!=NULL){
        $VidaUtil = FG_Rango_Dias($row["FechaVencimiento"]->format('Y-m-d'),$rowNReclamo['registro_factura']->format('Y-m-d'));  
        echo '<td align="center">'.$VidaUtil.'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      $descuento = ($CostoBrutoGen-$CostoNetoGen)*$CantidadRecibida;
      $subtotal = $CantidadRecibida * $CostoBrutoGen;
      $impuesto = (Impuesto-1)*($subtotal-$descuento);
      $impuesto = ($IsIVA==1)?$impuesto:"0";
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
      echo '<td align="center" class="bg-info">'.number_format($Total,2,"," ,"." ).'</td>';

      //Seccion en dolares
      if($Dolarizado=="SI" && $TasaActual!=0){
        echo '<td align="center" class="bg-success">'.number_format($CostoBrutoGen/$TasaActual,2,"," ,"." ).'</td>';
      echo '<td align="center" class="bg-success">'.number_format($CostoNetoGen/$TasaActual,2,"," ,"." ).'</td>';
        echo '<td align="center" class="bg-success">'.number_format($subtotalD,2,"," ,"." ).'</td>';
        echo '<td align="center" class="bg-success">'.number_format($impuestoD,2,"," ,"." ).'</td>';
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
      echo '</tr>';
    $contador++;
    $SumSKU++;
    }

    echo'
    <tr>
      <td colspan="13" align="right"><strong>Totales</strong></td>
      <td align="center">'.number_format($SumSubTotalBs,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumImpuestoBs,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumTotalBs,2,"," ,"." ).'</td>
      <td colspan="2" align="right"></td>
      <td align="center">'.number_format($SumSubTotalDol,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumImpuestoDol,2,"," ,"." ).'</td>
      <td align="center">'.number_format($SumTotalDol,2,"," ,"." ).'</td>
    </tr>
    <tr>
      <td colspan="13" align="right"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td scope="col" align="center"><input type="checkbox"></td>
      <td scope="col" align="center"><input type="checkbox"></td>  
      <td colspan="2" align="right"></td>
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
          </tr>
        </thead>
        <tbody>
          <tr>
            <td align="center">'.$SumSKU.'</td>
            <td align="center">'.$SumCantidad.'</td>
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
    TITULO: R30Q_Reclamo_Articulo
    FUNCION: busca los articulos que intervienen en una factura
    RETORNO: lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R30Q_Reclamo_Articulo($IdReclamo) {
    $sql = "
      SELECT
        (SELECT ComReclamo.ComFacturaId FROM ComReclamo WHERE ComReclamo.Id = ComReclamoDetalle.ComReclamoId) AS FacturaId,
        ComReclamoDetalle.InvArticuloId AS Id,
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComReclamoDetalle.InvArticuloId) AS CodigoArticulo,
        (SELECT InvArticulo.Descripcion FROM InvArticulo WHERE InvArticulo.Id = ComReclamoDetalle.InvArticuloId) AS Descripcion,
        (SELECT InvArticulo.FinConceptoImptoIdCompra FROM InvArticulo WHERE InvArticulo.Id = ComReclamoDetalle.InvArticuloId) AS ConceptoImpuesto,
        ComReclamoDetalle.Cantidad AS CantidadRecibidaFactura,
        ComReclamoDetalle.M_PrecioCompraBruto,
        ComReclamoDetalle.M_PrecioCompraNeto,
        ComReclamoDetalle.M_PrecioTroquelado,
        ComReclamoDetalle.FechaVencimiento,
        ComReclamoDetalle.NumeroLoteFabricante,
        (SELECT ComCausaReclamo.Descripcion FROM ComCausaReclamo WHERE ComCausaReclamo.Id = ComReclamoDetalle.ComCausaId) AS Causa
      FROM ComReclamoDetalle
      WHERE ComReclamoDetalle.ComReclamoId = '$IdReclamo';
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R38Q_Numero_Reclamo
    FUNCION:
    RETORNO:
    DESAROLLADO POR: SERGIO COVA
  */
  function R38Q_Numero_Reclamo($IdReclamo) {
    $sql = "
      SELECT TOP 50
        IIF (ComReclamo.estado = 1, 'En proceso', 'Procesado') AS estado,
        ComReclamo.Auditoria_Usuario AS operador,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = ComReclamo.ComProveedorId)) AS proveedor,
        ComReclamo.ComProveedorId AS id_proveedor,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS numero_factura,
        ComReclamo.NumeroReclamo AS numero_reclamo,
        (SELECT ComFactura.FechaDocumento FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS fecha_factura,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComReclamo.ComFacturaId) AS registro_factura,
        ComReclamo.FechaRegistro AS fecha_reclamo,
        ComReclamo.M_Total AS total_reclamo
      FROM ComReclamo
      WHERE ComReclamo.Id = '$IdReclamo'
    ";
    return $sql;
  }
?>