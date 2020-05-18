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
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Consulta compras
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
    
    R18_Proveedor_Factura($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdFact'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    R18_Factura_Articulo($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Consulta Compras');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
  //Se pasa a la seleccion del proveedor
    $InicioCarga = new DateTime("now");

    $sql = R18Q_Lista_Proveedores();
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
    TITULO: R18Q_Lista_Proveedores
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function R18Q_Lista_Proveedores() {
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
    TITULO: R18_Proveedor_Factura
    FUNCION:  Arma la lista de factura por proveedores
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R18_Proveedor_Factura($SedeConnection,$IdProveedor,$NombreProveedor){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R18Q_Factura_Proveedor_Toquel($IdProveedor);
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
      echo '<td align="center">'.$row["NumeroControl"].'</td>';
      echo '<td align="center">'.$row["NumeroFactura"].'</td>';
      echo '<td align="center">'.$row["FechaDocumento"]->format('d-m-Y').'</td>';
      echo '<td align="center">'.$row["FechaRegistro"]->format('d-m-Y').'</td>';
      echo '<td align="center">'.number_format($row["Total"],2,"," ,"." ).'</td>';
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
    TITULO: R18Q_Factura_Proveedor_Toquel
    FUNCION: Buscar la lista de facturas donde interviene el proveedor
    RETORNO: lista de facturas
    DESAROLLADO POR: SERGIO COVA
  */
  function R18Q_Factura_Proveedor_Toquel($IdProveedor) {
    $sql = "
    SELECT
    ComFactura.Id AS FacturaId,
    ComFactura.NumeroFactura,
    ComFactura.NumeroControl,
    CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
    CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRegistro,
    ComFactura.M_MontoTotalNeto AS Total,
    ComFactura.Auditoria_Usuario
    FROM ComFactura
    WHERE ComFactura.ComProveedorId = '$IdProveedor'
    ORDER BY FechaDocumento DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R18_Factura_Articulo
    FUNCION: arma la lista de articulos por factura
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R18_Factura_Articulo($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura){

    $connCPharma = FG_Conectar_CPharma();
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R18Q_Factura_Articulo($IdFatura);
    $result = sqlsrv_query($conn,$sql1);

    $sqlNFact = R18Q_Numero_Factura($IdFatura);
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
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Existencia (Actual)</th>
            <th scope="col" class="CP-sticky">Dolarizado?</td>
            <th scope="col" class="CP-sticky">Gravado?</td>
            <th scope="col" class="CP-sticky">Clasificacion</td>
            <th scope="col" class="CP-sticky">Precio de venta '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Costo Bruto (Sin IVA)</th>
            <th scope="col" class="CP-sticky">Lote del fabricante</th>
            <th scope="col" class="CP-sticky">Fecha de vencimiento</th>
            <th scope="col" class="CP-sticky">Ultimo Lote</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
            <th scope="col" class="CP-sticky">Ultimo Proveedor</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];

      $sql2 = SQG_Detalle_Articulo($IdArticulo);
      $result2 = sqlsrv_query($conn,$sql2);
      $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);
      
      $CodigoBarra = $row2["CodigoBarra"];
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
      $UltimoProveedorNombre = $row2["UltimoProveedorNombre"];
      $UltimoProveedorId = $row2["UltimoProveedorID"];
      
      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
      $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';
      echo '<td align="center">'.FG_Limpiar_Texto($row["Descripcion"]).'</td>';
      echo '<td align="center">'.intval($Existencia).'</td>';
      echo '<td align="center">'.$Dolarizado.'</td>';
      echo '<td align="center">'.$Gravado.'</td>';
      echo '<td align="center">'.$clasificacion.'</td>';
      echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
      echo '<td align="center">'.intval($row["CantidadRecibidaFactura"]).'</td>';
      echo '<td align="center">'.number_format($row["M_PrecioCompraBruto"],2,"," ,"." ).'</td>';
      echo '<td align="center">'.$row["NumeroLoteFabricante"].'</td>';

      if($row["FechaVencimiento"]!=NULL){
        echo '<td align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      if(($UltimoLote)){
        echo '<td align="center">'.$UltimoLote->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      if(!is_null($UltimaVenta)){
        echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      if(!is_null($UltimoProveedorNombre)){
          echo '<td align="center">'.$UltimoProveedorNombre.'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }
      
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
    TITULO: R18Q_Factura_Articulo
    FUNCION: busca los articulos que intervienen en una factura
    RETORNO: lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R18Q_Factura_Articulo($IdFatura) {
    $sql = "
    SELECT
    ComFacturaDetalle.ComFacturaId AS FacturaId,
    InvArticulo.Id,
    InvArticulo.CodigoArticulo,
    InvArticulo.Descripcion,
    InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto,
    (ROUND(CAST((ComFacturaDetalle.CantidadRecibidaFactura) AS DECIMAL(38,0)),2,0))  AS CantidadRecibidaFactura,
    (ROUND(CAST((ComFacturaDetalle.M_PrecioCompraBruto) AS DECIMAL(38,2)),2,0))  AS M_PrecioCompraBruto,
    ComFacturaDetalle.NumeroLoteFabricante AS NumeroLoteFabricante,
    CONVERT(DATE,ComFacturaDetalle.FechaVencimiento) AS FechaVencimiento
    FROM ComFacturaDetalle
    INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
    WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura'
    ORDER BY FacturaId,InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R18Q_Numero_Factura
    FUNCION:
    RETORNO:
    DESAROLLADO POR: SERGIO COVA
  */
  function R18Q_Numero_Factura($IdFactura) {
    $sql = "
      SELECT ComFactura.NumeroFactura AS NumeroFactura
      FROM ComFactura 
      WHERE ComFactura.Id = '$IdFactura'
    ";
    return $sql;
  }
?>