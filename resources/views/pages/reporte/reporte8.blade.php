@extends('layouts.model')

@section('title')
  Actualizar Troquel
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
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Actualizar Troquel (Proveedor)
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
    
    R8_Proveedor_Factura($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['TroquelN'])){
  //CASO 5: ACTUALIZAR EL TROQUEL
  //se pasa a mostrar el resultado de actualizar el troquel
    $InicioCarga = new DateTime("now");

    $registro = "Se actualizo el troquel del articulo: ".$_GET['IdArt'];

    R8_Troquel($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact'],$_GET['IdArt'],$_GET['TroquelN']);
    FG_Guardar_Auditoria('ACTUALIZAR','TROQUEL PROVEEDOR',$registro);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdArt'])){
  //CASO 4: CARGA AL HABER SELECCIONADO UNA ARTICULO
  //se pasa a la actualizacion del troquel
    $InicioCarga = new DateTime("now");

    R8_Articulo_Troquel($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact'],$_GET['IdArt']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdFact'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    R8_Factura_Articulo($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
  //Se pasa a la seleccion del proveedor
    $InicioCarga = new DateTime("now");

    $sql = R8Q_Lista_Proveedores();
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
  /**********************************************************************************/
  /*
    TITULO: R8Q_Lista_Proveedores
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Lista_Proveedores() {
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
  /**********************************************************************************/
  /*
    TITULO: R8_Proveedor_Factura
    FUNCION:  Arma la lista de factura por proveedores
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R8_Proveedor_Factura($SedeConnection,$IdProveedor,$NombreProveedor){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R8Q_Factura_Proveedor_Toquel($IdProveedor);
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
            <th scope="col" class="CP-sticky">Numero de Factura</th>
            <th scope="col" class="CP-sticky">Fecha Documento</th>
            <th scope="col" class="CP-sticky">Usuario Auditor</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["NumeroFactura"].'</td>';
      echo '<td align="center">'.$row["FechaDocumento"]->format('d-m-Y').'</td>';
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
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.($NombreProveedor).'">
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
    TITULO: R8Q_Factura_Proveedor_Toquel
    FUNCION: Buscar la lista de facturas donde interviene el proveedor
    RETORNO: lista de facturas
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Factura_Proveedor_Toquel($IdProveedor) {
    $sql = "
    SELECT
    ComFactura.Id AS FacturaId,
    ComFactura.NumeroFactura,
    CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
    ComFactura.Auditoria_Usuario
    FROM ComFactura
    WHERE ComFactura.ComProveedorId = '$IdProveedor'
    ORDER BY FacturaId ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R8_Factura_Articulo
    FUNCION: arma la lista de articulos por factura
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R8_Factura_Articulo($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R8Q_Factura_Articulo($IdFatura);
    $result = sqlsrv_query($conn,$sql1);

    $sqlNFact = R8Q_Numero_Factura($IdFatura);
    $resultNFact = sqlsrv_query($conn,$sqlNFact);
    $rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
    $NumeroFactura = $rowNFact["NumeroFactura"];

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
            <th scope="col">Numero de Factura</th>
            <th scope="col">Proveedor</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
      echo '<td>'.$NumeroFactura.'</td>';
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
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
      echo '<td align="center">'.($row["Descripcion"]).'</td>';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
             
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              
              <input id="IdArt" name="IdArt" type="hidden" value="'.$row["Id"].'">
              <input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.($NombreProveedor).'">
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
    TITULO: R8Q_Factura_Articulo
    FUNCION: busca los articulos que intervienen en una factura
    RETORNO: lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Factura_Articulo($IdFatura) {
    $sql = "
    SELECT
    ComFacturaDetalle.ComFacturaId AS FacturaId,
    InvArticulo.Id,
    InvArticulo.CodigoArticulo,
    InvArticulo.Descripcion,
    InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
    FROM ComFacturaDetalle
    INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
    WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura'
    ORDER BY FacturaId,InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R8Q_Numero_Factura
    FUNCION:
    RETORNO:
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Numero_Factura($IdFactura) {
    $sql = "
      SELECT ComFactura.NumeroFactura AS NumeroFactura
      FROM ComFactura 
      WHERE ComFactura.Id = '$IdFactura'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R8_Articulo_Troquel
    FUNCION: arma la lista del troquel segun el articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R8_Articulo_Troquel($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura,$IdArticulo){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql1 = R8Q_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql1);

    $sql2 = R8Q_Troquel_Articulo_Factura($IdFatura,$IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);
    $row2 = sqlsrv_fetch_array( $result2, SQLSRV_FETCH_ASSOC);

    $sqlNFact = R8Q_Numero_Factura($IdFatura);
    $resultNFact = sqlsrv_query($conn,$sqlNFact);
    $rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
    $NumeroFactura = $rowNFact["NumeroFactura"];

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
            <th scope="col">Numero de Factura</th>
            <th scope="col">Proveedor</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
      echo '<td>'.$NumeroFactura.'</td>';
    echo '<td>'.($NombreProveedor).'</td>';
    echo '
      </tr>
      </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Precio Troquelado Actual '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio Troquelado Nuevo</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';

    while($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
      
      echo '<tr>';
      echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
      echo '<td align="center">'.($row["Descripcion"]).'</td>';
      echo '<td align="center">'.number_format($row2["M_PrecioTroquelado"],2,"," ,"." ).'</td>';


      echo '
        <form autocomplete="off" action="">
        <td align="center">
          <input id="TroquelN" type="text" name="TroquelN" placeholder="Ingrese el nuevo precio troquelado" required autofocus>
        </td>';

      echo '
      <td align="center">
            <input id="SEDE" name="SEDE" type="hidden" value="';
              print_r($SedeConnection);
              echo'">
             
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              
              <input id="IdArt" name="IdArt" type="hidden" value="'.$row["Id"].'">
              <input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
            <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
            <input id="NombreProv" name="NombreProv" type="hidden" value="'.($NombreProveedor).'">
        </td>
          </form>
          <br>
        ';
      echo '</tr>';
    }
    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R8Q_Articulo
    FUNCION: Buscar informacion del articulo
    RETORNO: Datos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Articulo($IdArticulo) {
    $sql = "
      SELECT
      InvArticulo.Id,
      InvArticulo.CodigoArticulo,
      InvArticulo.Descripcion,
      InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
      FROM InvArticulo
      WHERE InvArticulo.Id = '$IdArticulo'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R8Q_Troquel_Articulo_Factura
    FUNCION: busca el troquel del articulo de la factura
    RETORNO: retora en valor del troquel del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Troquel_Articulo_Factura($IdFatura,$IdArticulo) {
    $sql = "
    SELECT
    ComFacturaDetalle.ComFacturaId,
    ComFacturaDetalle.InvArticuloId,
    ISNULL(ComFacturaDetalle.M_PrecioTroquelado,CAST(0 AS INT)) AS M_PrecioTroquelado
    FROM ComFacturaDetalle
    WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura' AND ComFacturaDetalle.InvArticuloId = '$IdArticulo'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R8_Troquel
    FUNCION: arma la lista definitiva con el troquel actualizado
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R8_Troquel($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura,$IdArticulo,$PrecioTroquel){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql2 = R8Q_Actualizar_Troquel($IdFatura,$IdArticulo,$PrecioTroquel);
    sqlsrv_query($conn,$sql2);
    
    $sql1 = R8Q_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql1);

    $sql3 = R8Q_Troquel_Articulo_Factura($IdFatura,$IdArticulo);
    $result3 = sqlsrv_query($conn,$sql3);
    $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

    $sqlNFact = R8Q_Numero_Factura($IdFatura);
    $resultNFact = sqlsrv_query($conn,$sqlNFact);
    $rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
    $NumeroFactura = $rowNFact["NumeroFactura"];

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
            aria-hidden="true"></i></span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
    </div>
    <br/>
    ';

    echo '
    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
          <tr>
            <th scope="col">Numero de Factura</th>
            <th scope="col">Proveedor</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
      echo '<td>'.$NumeroFactura.'</td>';
    echo '<td>'.($NombreProveedor).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Precio Troquelado '.SigVe.'</th>
          </tr>
        </thead>
        <tbody>
     ';

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="center" class="CP-sticky">'.$row["CodigoArticulo"].'</td>';
      echo '<td align="center" class="CP-sticky">'.$row["Descripcion"].'</td>';
      echo '<td align="center" class="CP-sticky">'.number_format($row3["M_PrecioTroquelado"],2,"," ,"." ).'</td>';
      echo '</tr>';  
    }
      echo '
        </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R8Q_Actualizar_Troquel
    FUNCION: actualiza el precio del troquel del articulo
    RETORNO: precio actualizado del troquel
    DESAROLLADO POR: SERGIO COVA
  */
  function R8Q_Actualizar_Troquel($IdFatura,$IdArticulo,$PrecioTroquel) {
    $sql = "
    UPDATE ComFacturaDetalle
    SET M_PrecioTroquelado = '$PrecioTroquel'
    FROM ComFacturaDetalle
    WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura'
    AND ComFacturaDetalle.InvArticuloId = '$IdArticulo'
    ";
    return $sql;
  }
?>