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
    Actualizar Troquel (Devolucion)
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
  //CASO 2: CARGA AL HABER SELECCIONADO UNA DEVOLUCION
  //se pasa a mostrar la informacion de la devolucion
    $InicioCarga = new DateTime("now");

    SC2_Devolucion_info($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['troquelactual'])){
  //CASO 4: CARGA AL HABER SELECCIONADO UNA DEVOLUCION
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    $registro = "Se actualizo el troquel del articulo: ".$_GET['Descripcion']." del troquel: ".$_GET['troquelanterior']." al troquel: ".$_GET['troquelactual']." por el motivo: ".$_GET['Motivo']; 

    SC2_Actualizar_Fecha($_GET['SEDE'],$_GET['IdLote'],$_GET['troquelactual'],$_GET['IdArti'],$_GET['Descripcion']);
    FG_Guardar_Auditoria('ACTUALIZAR','TROQUEL CLIENTE',$registro);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdDevolucion'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA DEVOLUCION
  //se pasa a mostrar los articulos de la devolucion
    $InicioCarga = new DateTime("now");

    SC2_Devolucion_detalle($_GET['SEDE'],$_GET['IdDevolucion'],$_GET['Nombre']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
  //Se pasa a la seleccion de la devolucion
    $InicioCarga = new DateTime("now");

    $sql = SC2Q_Lista_Devolucion();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    echo '
    <form autocomplete="off" action="">
      <div class="autocomplete" style="width:90%;">
        <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el numero de la devolucion" onkeyup="conteo()" required>
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
    TITULO: SC2Q_Lista_Devolucion
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function SC2Q_Lista_Devolucion() {
    $sql = "
        SELECT
        VenDevolucion.ConsecutivoDevolucionSistema,
        VenDevolucion.Id
        FROM VenDevolucion
        ORDER BY VenDevolucion.ConsecutivoDevolucionSistema ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SC2_Devolucion_info    
    DESAROLLADO POR: SERGIO COVA
  */
  function SC2_Devolucion_info($SedeConnection,$IdDevolucion,$NumeroDevolucion){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql = SC2Q_Devolucion_info($IdDevolucion);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Numero Devolucion</th>
          <th scope="col">Cliente</td>
          <th scope="col">Fecha</td>
          <th scope="col">Impresora</td>          
          <th scope="col">Caja</td>
          <th scope="col">Monto '.SigVe.'</td>
          <th scope="col">Numero Factura</td>         
          <th scope="col">Seleccion</td>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td align="center">'.$row["NumeroDevolucion"].'</td>';
    echo '<td align="center">'.$row["Cliente"].'</td>';
    echo '<td align="center">'.$row["Fecha"]->format('d-m-Y').'</td>';
    echo '<td align="center">'.$row["Impresora"].'</td>';
    echo '<td align="center">'.$row["Caja"].'</td>';
    echo '<td align="center">'.number_format($row["Monto"],2,"," ,"." ).'</td>';
    echo '<td align="center">'.$row["NumeroFactura"].'</td>';

    echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
             
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              
              <input id="IdDevolucion" name="IdDevolucion" type="hidden" value="'.$IdDevolucion.'">
              
              <input id="Nombre" name="Nombre" type="hidden" value="'.$NumeroDevolucion.'">
          </form>        
        ';

    echo '
      </tr>
      </tbody>
    </table>';    
  }
  /**********************************************************************************/
  /*
    TITULO: SC2Q_Devolucion_info
    DESAROLLADO POR: SERGIO COVA
  */
  function SC2Q_Devolucion_info($IdDevolucion) {
    $sql = "
        SELECT 
        VenDevolucion.ConsecutivoDevolucionSistema as NumeroDevolucion,
        CONCAT(GenPersona.Nombre,' ', GenPersona.Apellido) as Cliente,
        VenDevolucion.FechaDocumento as Fecha,
        VenDevolucion.VE_SerialImp as Impresora,
        VenCaja.EstacionTrabajo as  Caja,
        VenDevolucion.M_MontoTotalDevolucion as Monto,
        VenFactura.NumeroFactura as NumeroFactura
        from VenDevolucion
        left join VenFactura on VenDevolucion.VenFacturaId = VenFactura.Id
        left join VenCliente on VenFactura.VenClienteId = VenCliente.Id
        left join GenPersona on VenCliente.GenPersonaId =  GenPersona.Id
        left join VenCaja on VenDevolucion.VenCajaId = VenCaja.Id
        where VenDevolucion.Id = '$IdDevolucion'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SC2_Devolucion_detalle   
    DESAROLLADO POR: SERGIO COVA
  */
  function SC2_Devolucion_detalle($SedeConnection,$IdDevolucion,$NumeroDevolucion){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql = SC2Q_Devolucion_info($IdDevolucion);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);    

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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Numero Devolucion</th>
          <th scope="col">Cliente</td>
          <th scope="col">Fecha</td>
          <th scope="col">Impresora</td>          
          <th scope="col">Caja</td>
          <th scope="col">Monto '.SigVe.'</td>
          <th scope="col">Numero Factura</td>         
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td align="center">'.$row["NumeroDevolucion"].'</td>';
    echo '<td align="center">'.$row["Cliente"].'</td>';
    echo '<td align="center">'.$row["Fecha"]->format('d-m-Y').'</td>';
    echo '<td align="center">'.$row["Impresora"].'</td>';
    echo '<td align="center">'.$row["Caja"].'</td>';
    echo '<td align="center">'.number_format($row["Monto"],2,"," ,"." ).'</td>';
    echo '<td align="center">'.$row["NumeroFactura"].'</td>';
    echo '
      </tr>
      </tbody>
    </table>';    

    
    $sql1 = SC2Q_Devolucion_detalle($IdDevolucion);
    $result1 = sqlsrv_query($conn,$sql1);

    echo '
    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Codigo Interno</td>
          <th scope="col">Descripcion</td>
          <th scope="col">Cantidad</td>          
          <th scope="col">Gravado</td>
          <th scope="col">Dolarizado</td>
          <th scope="col">Numero Lote</td>
          <th scope="col">Existencia</td> 
          <th scope="col">Toquel Actual '.SigVe.'</td>
          <th scope="col">Precio Devolucion '.SigVe.'</td>
          <th scope="col">Toquel Nuevo '.SigVe.'</td>
          <th scope="col">Seleccion</td>
        </tr>
      </thead>
      <tbody>
    ';

    $contador = 1;
    while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
         
        $Gravado = FG_Producto_Gravado($row1["Impuesto"]);
        $Dolarizado = FG_Producto_Dolarizado($row1["Dolarizado"]);

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="center">'.$row1["CodigoInterno"].'</td>';
        echo '<td align="left">'.FG_Limpiar_Texto($row1["Descripcion"]).'</td>';
        echo '<td align="center">'.intval($row1["CantidadDevolucion"]).'</td>';
        echo '<td align="left">'.$Gravado.'</td>';
        echo '<td align="left">'.$Dolarizado.'</td>';
        echo '<td align="left">'.$row1["NumeroLote"].'</td>';
        echo '<td align="center">'.intval($row1["ExistenciaActual"]).'</td>';
        echo '<td align="center">'.number_format($row1["PTroquel"],2,"," ,"." ).'</td>';
        echo '<td align="center">'.number_format($row1["PrecioBrutoDevolucion"],2,"," ,"." ).'</td>';
        echo '
        <form autocomplete="off" action=""> 
          <td align="center">
            <input type="number" min="0.00" step="any" name="troquelnuevo[]" style="width:100%;" autofocus="autofocus">
          </td>          
          <td align="center">            
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
            <input type="submit" value="Selecionar" class="btn btn-outline-success">
            <input id="IdLote" name="IdDevolucion" type="hidden" value="'.$IdDevolucion.'">          
            <input id="IdArticulo" name="IdArticulo[]" type="hidden" value="'.$row1["IdArticulo"].'">            
          </td>       
          </form>
          <br>
        ';
        echo '</tr>';
        $contador++;
    }
    echo '
      </tbody>
    </table>'; 


  }
  /**********************************************************************************/
  /*
    TITULO: SC2Q_Devolucion_detalle
    DESAROLLADO POR: SERGIO COVA
  */
  function SC2Q_Devolucion_detalle($IdDevolucion) {
    $sql = "
        select 
        InvArticulo.Id as IdArticulo,
        InvArticulo.CodigoArticulo as CodigoInterno,
        InvArticulo.Descripcion,
        VenDevolucionDetalle.Cantidad as CantidadDevolucion,
        --Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
            (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
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
        InvLote.Numero as NumeroLote,
        InvLoteAlmacen.Existencia as ExistenciaActual,
        InvLote.M_PrecioTroquelado as PTroquel,
        VenDevolucionDetalle.PrecioBruto as PrecioBrutoDevolucion
        from VenDevolucion
        left join VenDevolucionDetalle on VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        left join InvArticulo on VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
        left join InvLoteAlmacen on VenDevolucionDetalle.InvLoteAlmacenId = InvLoteAlmacen.Id
        left join InvLote on InvLoteAlmacen.InvLoteId =  InvLote.Id
        where VenDevolucion.Id = '$IdDevolucion'
    ";
    return $sql;
  }
?>