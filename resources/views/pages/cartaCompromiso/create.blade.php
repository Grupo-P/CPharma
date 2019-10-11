@extends('layouts.model')

@section('title')
  Compromisos
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
  <!-- Modal Error De Fecha Recepcion Mayor A Fecha Tope -->
  <div class="modal fade" id="errorModalCenter1" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle1">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha tope (Compromiso)</b> no puede ser menor que <b>la fecha de recepcion (Articulo)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error De Fecha Tope Menor A Fecha De Recepcion -->
  <div class="modal fade" id="errorModalCenter2" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle2">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha de vencimiento (Articulo)</b> no puede ser mayor que <b>la fecha tope (Compromiso)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error De Fecha De Recepcion Mayor A Fecha De Vencimiento -->
  <div class="modal fade" id="errorModalCenter3" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle3">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha de recepcion (Articulo)</b> no puede ser mayor que <b>la fecha de vencimiento (Articulo)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error De Fecha De Recepcion Mayor A Fecha De Vencimiento -->
  <div class="modal fade" id="errorModalCenter4" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle4">
            <i class="fas fa-exclamation-circle"></i>&nbsp;Error
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">
            <b>La fecha de factura</b> debe ser mayor que <b>la fecha de recepcion (Articulo)</b>
          </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>
    Agregar Compromisos
  </h1>
  <hr class="row align-items-start col-12">

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";

    if(isset($_GET['SEDE'])) {
      echo '
        <h1 class="h5 text-success" align="left">
          <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).
        '</h1>
      ';
    }
    echo '<hr class="row align-items-start col-12">';
    
    if(isset($_GET['Id'])) {
      /*CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR
                Se pasa a la carga de las facturas del proveedor*/
      $InicioCarga = new DateTime("now");

      R11_Proveedor_Factura($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);

      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['lote'])) {
      /*CASO 5: CARGA AL HABER COMPLETADO LA Compromiso
                Se pasa a la solicitud de fechas*/
      $InicioCarga = new DateTime("now");

      if($_GET['fecha_vencimiento'] == '') {
        $diferencia1_numero = ValidarFechas($_GET['fecha_recepcion'],$_GET['fecha_tope']);
        $diferencia4_numero = ValidarFechas($_GET['fecha_documento'],$_GET['fecha_recepcion']);

        if(($diferencia1_numero > 0) && ($diferencia4_numero >= 0)) {
        ?>

          <form action="{{url('/cartaCompromiso')}}" method="POST" id="redireccionado">
            @csrf
            <input id="proveedor" name="proveedor" type="hidden" value="<?php print_r($_GET['proveedor']); ?>">
            <input id="articulo" name="articulo" type="hidden" value="<?php print_r($_GET['articulo']); ?>">
            <input id="lote" name="lote" type="hidden" value="<?php print_r($_GET['lote']); ?>">
            <input id="fecha_documento" name="fecha_documento" type="hidden" value="<?php print_r($_GET['fecha_documento']); ?>">
            <input id="fecha_recepcion" name="fecha_recepcion" type="hidden" value="<?php print_r($_GET['fecha_recepcion']); ?>">
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="0000-00-00">
            <input id="fecha_tope" name="fecha_tope" type="hidden" value="<?php print_r($_GET['fecha_tope']); ?>">
            <input id="causa" name="causa" type="hidden" value="<?php print_r(trim($_GET['causa'])); ?>">
            <input id="nota" name="nota" type="hidden" value="<?php print_r(trim($_GET['nota'])); ?>">
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r(trim($_GET['SEDE'])); ?>">
          </form>

          <script>
            document.getElementById('redireccionado').submit();
          </script>
          <?php
        }
        else {
          if($diferencia1_numero <= 0) {
            ?>
              <script>
                $('#errorModalCenter1').modal('show');
              </script>
            <?php
          }

          if($diferencia4_numero < 0) {
            ?>
              <script>
                $('#errorModalCenter4').modal('show');
              </script>
            <?php
          }

          CartaDeCompromiso($_GET['SEDE'],$_GET['IdProv'],$_GET['proveedor'],$_GET['IdFact'],$_GET['IdArt']);
        }
      }
      else {
        $diferencia1_numero = ValidarFechas($_GET['fecha_recepcion'],$_GET['fecha_tope']);
        $diferencia2_numero = ValidarFechas($_GET['fecha_vencimiento'],$_GET['fecha_tope']);
        $diferencia3_numero = ValidarFechas($_GET['fecha_recepcion'],$_GET['fecha_vencimiento']);
        $diferencia4_numero = ValidarFechas($_GET['fecha_documento'],$_GET['fecha_recepcion']);

        if(($diferencia1_numero > 0) 
          && ($diferencia2_numero <= 0) 
          && ($diferencia3_numero > 0) 
          && ($diferencia4_numero >= 0)) {
          ?>
          <form action="{{url('/cartaCompromiso')}}" method="POST" id="redireccionado">
            @csrf
            <input id="proveedor" name="proveedor" type="hidden" value="<?php print_r($_GET['proveedor']); ?>">
            <input id="articulo" name="articulo" type="hidden" value="<?php print_r($_GET['articulo']); ?>">
            <input id="lote" name="lote" type="hidden" value="<?php print_r($_GET['lote']); ?>">
            <input id="fecha_documento" name="fecha_documento" type="hidden" value="<?php print_r($_GET['fecha_documento']); ?>">
            <input id="fecha_recepcion" name="fecha_recepcion" type="hidden" value="<?php print_r($_GET['fecha_recepcion']); ?>">
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="<?php print_r($_GET['fecha_vencimiento']); ?>">
            <input id="fecha_tope" name="fecha_tope" type="hidden" value="<?php print_r($_GET['fecha_tope']); ?>">
            <input id="causa" name="causa" type="hidden" value="<?php print_r(trim($_GET['causa'])); ?>">
            <input id="nota" name="nota" type="hidden" value="<?php print_r(trim($_GET['nota'])); ?>">
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r(trim($_GET['SEDE'])); ?>">
          </form>

          <script>
            document.getElementById('redireccionado').submit();
          </script>
          <?php
        }
        else {
          if($diferencia1_numero <= 0) {
            ?>
              <script>
                $('#errorModalCenter1').modal('show');
              </script>
            <?php
          }

          if($diferencia2_numero > 0) {
            ?>
              <script>
                $('#errorModalCenter2').modal('show');
              </script>
            <?php
          }

          if($diferencia3_numero <= 0) {
            ?>
              <script>
                $('#errorModalCenter3').modal('show');
              </script>
            <?php
          }

          if($diferencia4_numero < 0) {
            ?>
              <script>
                $('#errorModalCenter4').modal('show');
              </script>
            <?php
          }

          CartaDeCompromiso($_GET['SEDE'],$_GET['IdProv'],$_GET['proveedor'],$_GET['IdFact'],$_GET['IdArt']);
        }
      }

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['IdArt'])) {
      /*CASO 4: CARGA AL HABER SELECCIONADO UNA ARTICULO
                Se pasa a la solicitud de fechas*/
      $InicioCarga = new DateTime("now");

      R11_Carta_Compromiso($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact'],$_GET['IdArt']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else if(isset($_GET['IdFact'])) {
      /*CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA 
              Se pasa a la seleccion del articulo*/
      $InicioCarga = new DateTime("now");

      R11_Factura_Articulo($_GET['SEDE'],$_GET['IdProv'],$_GET['NombreProv'],$_GET['IdFact']);

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      /*CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
                Se pasa a la seleccion del proveedor*/
      $InicioCarga = new DateTime("now");

      $sql = R11Q_Lista_Proveedores();
      $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

      echo '
        <form autocomplete="off" action="">
          <div class="autocomplete" style="width:90%;">
            <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del proveedor " onkeyup="conteo()" required>
            <input id="myId" name="Id" type="hidden">
            <input id="SEDE" name="SEDE" type="hidden" value="'; print_r($_GET['SEDE']); echo'">
          </div>
          <input type="submit" value="Buscar" class="btn btn-outline-success">
        </form>
      ';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    } 
  ?>

  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip(); 
    });
  </script>
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
    TITULO: R11Q_Lista_Proveedores
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: MANUEL HENRIQUEZ
  */
  function R11Q_Lista_Proveedores() {
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
    TITULO: R11_Proveedor_Factura
    FUNCION:  Arma la lista de factura por proveedores
    RETORNO: no aplica
    DESAROLLADO POR: MANUEL HENRIQUEZ
  */
  function R11_Proveedor_Factura($SedeConnection,$IdProveedor,$NombreProveedor) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R11Q_Factura_Proveedor_Toquel($IdProveedor);
    $result = sqlsrv_query($conn,$sql1);

    echo '
      <div class="input-group md-form form-sm form-1 pl-0">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
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
            <td>'.FG_Limpiar_Texto($NombreProveedor).'</td>
          </tr>
        </tbody>
      </table>
    ';

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
      echo '
        <tr>
          <td align="left"><strong>'.intval($contador).'</strong></td>
          <td align="center">'.$row["NumeroFactura"].'</td>
          <td align="center">'.$row["FechaDocumento"]->format('d-m-Y').'</td>
          <td align="center">'.$row["Auditoria_Usuario"].'</td>
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
          </td>
        </tr>
      ';

      $contador++;
    }

    echo '
        </tbody>
      </table>
    ';

    sqlsrv_close($conn);
  }

  /**********************************************************************************/
  /*
    TITULO: R11Q_Factura_Proveedor_Toquel
    FUNCION: Buscar la lista de facturas donde interviene el proveedor
    RETORNO: lista de facturas
    DESAROLLADO POR: MANUEL HENRIQUEZ
  */
  function R11Q_Factura_Proveedor_Toquel($IdProveedor) {
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
    TITULO: R11_Factura_Articulo
    FUNCION: arma la lista de articulos por factura
    RETORNO: no aplica
    DESAROLLADO POR: MANUEL HENRIQUEZ
  */
  function R11_Factura_Articulo($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R11Q_Factura_Articulo($IdFatura);
    $result = sqlsrv_query($conn,$sql1);

    $sqlNFact = R11Q_Numero_Factura($IdFatura);
    $resultNFact = sqlsrv_query($conn,$sqlNFact);
    $rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
    $NumeroFactura = $rowNFact["NumeroFactura"];

    echo '
      <div class="input-group md-form form-sm form-1 pl-0">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
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
            <td>'.$NumeroFactura.'</td>
            <td>'.FG_Limpiar_Texto($NombreProveedor).'</td>
          </tr>
        </tbody>
      </table>
    ';

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
      echo '
        <tr>
          <td align="center"><strong>'.intval($contador).'</strong></td>
          <td align="center">'.$row["CodigoArticulo"].'</td>
          <td align="center">'.FG_Limpiar_Texto($row["Descripcion"]).'</td>
          <td align="center">
            <form autocomplete="off" action="">
              <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              <input id="IdArt" name="IdArt" type="hidden" value="'.$row["Id"].'">
              <input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
              <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
              <input id="NombreProv" name="NombreProv" type="hidden" value="'
                .FG_Limpiar_Texto($NombreProveedor)
              .'">
            </form>
            <br>
          </td>
        </tr>
      ';
      $contador++;
    }

    echo '
        </tbody>
      </table>
    ';

    sqlsrv_close($conn);
  }

  /**********************************************************************************/
  /*
    TITULO: R11Q_Factura_Articulo
    FUNCION: busca los articulos que intervienen en una factura
    RETORNO: lista de articulos
    DESAROLLADO POR: MANUEL HENRIQUEZ
  */
  function R11Q_Factura_Articulo($IdFatura) {
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
    TITULO: R11Q_Numero_Factura
    FUNCION:
    RETORNO:
    DESAROLLADO POR: MANUEL HENRIQUEZ
  */
  function R11Q_Numero_Factura($IdFactura) {
    $sql = "
      SELECT ComFactura.NumeroFactura AS NumeroFactura
      FROM ComFactura 
      WHERE ComFactura.Id = '$IdFactura'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R11_Carta_Compromiso
    PARAMETROS: [$SedeConnection] sede donde se hara la conexion
          [$IdProveedor] ID del proveedor a buscar
          [$NombreProveedor] Nombre del proveedor a buscar
          [$IdFatura] id de la factura
          [$IdArticulo] ide del articulo
    FUNCION: arma la lista del troquel segun el articulo
    RETORNO: no aplica
   */
  function R11_Carta_Compromiso($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura,$IdArticulo) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql = R11_QCarta_Compromiso($IdProveedor,$IdFatura,$IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $result1 = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    $NumeroFactura = $row["NumeroFactura"];
    $CodigoArticulo = $row["CodigoArticulo"];
    $Articulo = $row["Descripcion"];
    $FechaDocumento = $row["FechaDocumento"];
    $FechaRecepcion = $row["FechaRecepcion"];
    $FechaVencimiento = $row["FechaVencimiento"];
    $Lote = $row["NumeroLote"];
    $LoteFabricante = $row["LoteFabricante"];
    $LoteFabricanteAnterior = "";

    $flag = array();
    $flag2 = array();//Array LoteFabricante
    $i = 0;

    while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
      $Lote1 = $row1["NumeroLote"];
      $LoteFabricante1 = $row1["LoteFabricante"];
      $FechaVencimiento1 = $row1["FechaVencimiento"];

      $flag[$i] = utf8_encode($Lote1);
      $flag2[$i] = utf8_encode($LoteFabricante1);

      if(is_null($FechaVencimiento1)) {
        $flag[$i + 1] = utf8_encode('');
        $flag2[$i + 1] = utf8_encode('');
      }
      else {
        $flag[$i + 1] = utf8_encode($FechaVencimiento1->format('Y-m-d'));
        $flag2[$i + 1] = utf8_encode($FechaVencimiento1->format('Y-m-d'));
      }
      
      $i += 2;
    }

    $ArrFinal = json_encode($flag);
    $ArrFinal2 = json_encode($flag2); 
  ?>

    <script>
      function capturarLote(e,loteNulo) {
        var FechaVencimientoJs = '';

        if(isNaN(e.value) || loteNulo == '1') {
          var ArrJs2 = eval(<?php echo $ArrFinal2 ?>);
          var indice = ArrJs2.indexOf(e.value);
          var indiceFecha = indice+1;
          FechaVencimientoJs = ArrJs2[indiceFecha];
        }
        else {
          var ArrJs = eval(<?php echo $ArrFinal ?>);
          var indice = ArrJs.indexOf(e.value);
          var indiceFecha = indice+1;
          FechaVencimientoJs = ArrJs[indiceFecha];
        }

        if(FechaVencimientoJs == '') {
          document.getElementById('input_fechaV').value = '00/00/0000';
        }
        else {
          document.getElementById('input_fechaV').value = formato(FechaVencimientoJs);
        }

        document.getElementById('fecha_vencimiento').value = FechaVencimientoJs;
      }

      /**
       * Convierte un texto de la forma 2017-01-10 a la forma
       * 10/01/2017
       *
       * @param {string} texto Texto de la forma 2017-01-10
       * @return {string} texto de la forma 10/01/2017
       *
       */
      function formato(texto){
        return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
      }
    </script>

  <?php
    echo '
      <form autocomplete="off" action="">
        <table class="table table-striped table-bordered col-12 sortable">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Numero de Factura</th>
              <th scope="col">Proveedor</th>
              <th scope="col">Lote</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>'.$NumeroFactura.'</td>
              <td>'.$NombreProveedor.'</td>
              <td>
    ';

    if($LoteFabricante == null) { 
      echo '
        <select name="lote" id="lote" style="width:100%;" onchange="capturarLote(this,1);">
          <option value="'.$Lote.'">'.$Lote.'</option>
      ';
    }
    else { 
      echo '
        <select name="lote" id="lote" style="width:100%;" onchange="capturarLote(this,2);">
          <option value="'.$LoteFabricante.'">'.$LoteFabricante.'</option>
      ';

      $LoteFabricanteAnterior = $LoteFabricante;
    }

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $Lote = $row["NumeroLote"];
      $LoteFabricante = $row["LoteFabricante"];

      if($LoteFabricante == null) { 
        echo '
          <option value="'.$Lote.'">'.$Lote.'</option>
        ';
      }
      else if($LoteFabricanteAnterior != $LoteFabricante) { 
        echo '
          <option value="'.$LoteFabricante.'">'.$LoteFabricante.'</option>
        ';
      }
    }
           echo '
           </select>
            </td>
          </tr>
        </tbody>
      </table>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Descripcion</th>
          </tr>
        </thead>

        <tbody>
        <tr> 
          <td align="center">'.$CodigoArticulo.'</td>
          <td align="center">'.$Articulo.'</td>
        </tr>
        </tbody>
    </table>';

    echo '
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Fecha de factura</th>
            <th scope="col">Fecha de recepcion (Articulo)</th>
            <th scope="col">Fecha de vencimiento (Articulo)</th>
            <th scope="col">Fecha tope (Carta compromiso)</th>
          </tr>
        </thead>

        <tbody>
        <tr>
          <td align="center">
            <input type="text" value="'.$FechaDocumento->format('d/m/Y').'" disabled>
          </td>
          <td align="center">
            <input type="text" value="'.$FechaRecepcion->format('d/m/Y').'" disabled>
          </td>';
          if(!is_null($FechaVencimiento)) {
            echo '
          <td align="center">
            <input type="text" id="input_fechaV" value="'.$FechaVencimiento->format('d/m/Y').'" disabled>
          </td>';
          }
          else {
            echo '
          <td align="center">
            <input type="text" id="input_fechaV" value="00/00/0000" disabled>
          </td>';
          }
          echo '
          <td align="center">
            <input id="fecha_tope" name="fecha_tope" type="date" required>
          </td>
        </tr>
        </tbody>
    </table>';

    echo '
      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
            <tr>
              <th scope="col">Causa</th>
              <th scope="col">Nota</th>
            </tr>
          </thead>
        
          <tbody>
            <tr>
            <td>
              <textarea name="causa" id="causa" class="form-control" rows="4" required></textarea>
            </td>

              <td>
                <textarea name="nota" id="nota" class="form-control" rows="4" required></textarea>
              </td>
            </tr>
          </tbody>
      </table>

      <div class="text-center">
        <input id="articulo" name="articulo" type="hidden" value="'.$Articulo.'">
        <input id="proveedor" name="proveedor" type="hidden" value="'.$NombreProveedor.'">
        <input id="SEDE" name="SEDE" type="hidden" value="'.$SedeConnection.'">
        <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
        <input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
        <input id="IdArt" name="IdArt" type="hidden" value="'.$IdArticulo.'">
        <input id="fecha_documento" name="fecha_documento" type="hidden" value="'.$FechaDocumento->format('Y-m-d').'">
        <input id="fecha_recepcion" name="fecha_recepcion" type="hidden" value="'.$FechaRecepcion->format('Y-m-d').'">';

          if(!is_null($FechaVencimiento)) {
            echo '
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="'.$FechaVencimiento->format('Y-m-d').'">';
          }
          else {
            echo '
            <input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="'.$FechaVencimiento.'">';
          }
          echo '
        <input type="submit" value="Guardar" class="btn btn-outline-success">
      </div>
    </form>';

    sqlsrv_close($conn);
  }

  /*
    TITULO: R11_QCarta_Compromiso
    FUNCION: Construir las columnas correspondientes al reporte
    RETORNO: Un String con la query
    DESARROLLADO POR: MANUEL HENRIQUEZ
   */
  function R11_QCarta_Compromiso($IdProveedor,$IdFatura,$IdArticulo) {
    $sql = "
      SELECT ComFactura.ComProveedorId,
      InvLote.Numero AS NumeroLote,
      InvLote.LoteFabricante,
      ComFactura.NumeroFactura, 
      CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
      CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRecepcion,
      CONVERT(DATE,InvLote.FechaVencimiento) AS FechaVencimiento,
      InvArticulo.CodigoArticulo,
      InvArticulo.Descripcion
      FROM ComFactura
      INNER JOIN ComFacturaDetalle ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN InvArticulo ON ComFacturaDetalle.InvArticuloId = InvArticulo.Id
      INNER JOIN InvLote ON InvLote.InvArticuloId = InvArticulo.Id
      WHERE ComFactura.ComProveedorId='$IdProveedor' 
      AND ComFactura.Id='$IdFatura'
      AND InvArticulo.Id='$IdArticulo'
      AND CONVERT(DATE,ComFactura.FechaRegistro) = CONVERT(DATE,InvLote.FechaEntrada)
      GROUP BY ComFactura.ComProveedorId, InvLote.Numero, InvLote.LoteFabricante, ComFactura.NumeroFactura, CONVERT(DATE,ComFactura.FechaDocumento), CONVERT(DATE,ComFactura.FechaRegistro), CONVERT(DATE,InvLote.FechaVencimiento), InvArticulo.CodigoArticulo, InvArticulo.Descripcion
      ORDER BY InvLote.Numero ASC
    ";
    return $sql;
  }
?>