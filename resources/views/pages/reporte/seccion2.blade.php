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

    SC2_Devolucion_info($_GET['SEDE'],$_GET['Id'],$_GET['NumeroDevolucion']);
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['troquelesnuevos'])){
  //CASO 4: CARGA AL HABER SELECCIONADO UNA DEVOLUCION
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");
    
    SC2_Actualizar_Troquel($_GET['SEDE'],$_GET['IdDevolucion'],$_GET['IdArticulos'],$_GET['IdLotes'],$_GET['troquelesnuevos'],$_GET['Modalidad'],$_GET['troquelesanteriores']);
    
    FG_Guardar_Auditoria('ACTUALIZAR','TROQUEL DEVOLUCION',$_GET['IdDevolucion']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdDevolucion'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA DEVOLUCION
  //se pasa a mostrar los articulos de la devolucion
    $InicioCarga = new DateTime("now");

    SC2_Devolucion_detalle($_GET['SEDE'],$_GET['IdDevolucion'],true);

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
        <input id="myInput" type="text" name="NumeroDevolucion" placeholder="Ingrese el numero de la devolucion" onkeyup="conteo()" required>
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
              
              <input id="NumeroDevolucion" name="NumeroDevolucion" type="hidden" value="'.$NumeroDevolucion.'">
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
  function SC2_Devolucion_detalle($SedeConnection,$IdDevolucion,$flagTroquelNuevo){

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
    <form class="form-group" autocomplete="off" action="" target="_blank">
    <table class="table table-striped table-bordered col-12 sortable form-group">
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
          <th scope="col">Troquel Actual <br> (CON IVA) '.SigVe.'</td>
          <th scope="col">Precio Devolucion <br> (SIN IVA) '.SigVe.'</td>';
          
          if($flagTroquelNuevo){
            echo '<th scope="col">Troquel Nuevo <br> (CON IVA) '.SigVe.'</td>';
          }                

        echo'</tr>
      </thead>
      <tbody>      
    ';

    $contador = 1;
    $TroquelSugerido = 0;
    while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $idArticulo = $row1["IdArticulo"];
         
        $Gravado = FG_Producto_Gravado($row1["Impuesto"]);
        $Dolarizado = FG_Producto_Dolarizado($row1["Dolarizado"]);
        
        if($Gravado=="SI"){
          $TroquelSugerido = round($row1["PrecioBrutoDevolucion"]*Impuesto,2,PHP_ROUND_HALF_UP);          
        }else{
          $TroquelSugerido = round($row1["PrecioBrutoDevolucion"],2,PHP_ROUND_HALF_UP);
        }

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="center">'.$row1["CodigoInterno"].'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($row1["Descripcion"]).'</td>';
        echo '<td align="center">'.intval($row1["CantidadDevolucion"]).'</td>';
        echo '<td align="center">'.$Gravado.'</td>';
        echo '<td align="center">'.$Dolarizado.'</td>';
        echo '<td align="center">'.$row1["NumeroLote"].'</td>';
        echo '<td align="center">'.intval($row1["ExistenciaActual"]).'</td>';
        echo '<td align="center">'.number_format($row1["PTroquel"],2,"," ,"." ).'</td>';
        echo '<td align="center">'.number_format($row1["PrecioBrutoDevolucion"],2,"," ,"." ).'</td>';
        
        if($flagTroquelNuevo){
          echo '         
            <td align="center">
              <input value="'.$TroquelSugerido.'" class="form-group" type="number" min="0.00" step="any" name="troquelesnuevos[]" style="width:100%;" autofocus="autofocus">
            </td>                       
              <input id="SEDE" name="SEDE" type="hidden" value="'.$SedeConnection.'">
              <input id="IdDevolucion" name="IdDevolucion" type="hidden" value="'.$IdDevolucion.'">
              <input id="IdArticulos" name="IdArticulos[]" type="hidden" value="'.$idArticulo.'">                               
              <input id="IdLotes" name="IdLotes[]" type="hidden" value="'.$row1["IdLote"].'">
              <input id="troquelesanteriores" name="troquelesanteriores[]" type="hidden" value="'.$row1["PTroquel"].'">
          ';
        } 
        
        echo '</tr>';
        $contador++;
    }
    echo '        
      </tbody>
    </table>';

      if($flagTroquelNuevo){        
        echo '<input type="submit" name="Modalidad" value="Revertir" class="btn btn-danger btn-md form-group" style="float:right">';
        echo '<input type="submit" name="Modalidad" value="Troquelar" class="btn btn-success btn-md form-group" style="float:right; margin-right:5px;">';        
        echo '</form>';
      } 
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
        InvLote.Id as IdLote,
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
  /**********************************************************************************/
  /*
    TITULO: SC2_Actualizar_Troquel    
    DESAROLLADO POR: SERGIO COVA
  */
  function SC2_Actualizar_Troquel($SedeConnection,$IdDevolucion,$Arr_Articulos,$Arr_Lotes,$Arra_Troqueles,$Modalidad,$Arra_Troqueles_Anteriores){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    
    $limit = count($Arr_Articulos);
    $msn = "";
    
    for($i = 0 ; $i < $limit; $i++){
      $IdArticulo = $Arr_Articulos[$i];
      $IdLote = $Arr_Lotes[$i];
      $Troquel = $Arra_Troqueles[$i];
      $TroquelAnterior = $Arra_Troqueles_Anteriores[$i];

      if($Modalidad == "Troquelar"){
        if( (floatval($Troquel)>0) && ($Troquel!="NULL") && ($Troquel!="null") && ($Troquel!="") ){
          $sqlTN = SC1Q_Actualizar_Troquel(true,$Troquel,$IdArticulo,$IdLote);          
          sqlsrv_query($conn,$sqlTN);
          $msn = '<h4 class="h5 text-success" align="center">Troqueles actualizados con exito</h4>';
        }
      }
      else if($Modalidad == "Revertir"){
        if( (intval($TroquelAnterior)>0) ){
          $sqlTN = SC1Q_Actualizar_Troquel_Nuleable(true,$TroquelAnterior,$IdArticulo,$IdLote);
          sqlsrv_query($conn,$sqlTN);
          $msn = '<h4 class="h5 text-success" align="center">Troqueles actualizados con exito</h4>';
        }
        else if( ($TroquelAnterior=="NULL") || ($TroquelAnterior=="null") || ($TroquelAnterior=="") ){
          $sqlTN = SC1Q_Actualizar_Troquel_Nuleable(false,$TroquelAnterior,$IdArticulo,$IdLote);
          sqlsrv_query($conn,$sqlTN);
          $msn = '<h4 class="h5 text-warning" align="center">Troqueles actualizados a NULL</h4>';
        }
        else if( (intval($TroquelAnterior)<=0) ){
          $sqlTN = SC1Q_Actualizar_Troquel_Nuleable(false,$TroquelAnterior,$IdArticulo,$IdLote);
          sqlsrv_query($conn,$sqlTN);
          $msn = '<h4 class="h5 text-danger" align="center">Verifique los datos ingresados</h4>';
        }
      }
    }

    echo($msn);
    SC2_Devolucion_detalle($SedeConnection,$IdDevolucion,false);
  }
  /**********************************************************************************/
  /*
    TITULO: SC1Q_Actualizar_Fecha  
    DESAROLLADO POR: SERGIO COVA
  */
  function SC1Q_Actualizar_Troquel($flag,$Troquel,$IdArticulo,$IdLote) {
    
    if($flag == true){
      $sql = "
      UPDATE InvLote 
      SET M_PrecioTroquelado = '$Troquel'
      FROM InvLote, invlotealmacen, InvArticulo 
      WHERE InvLote.id = '$IdLote' 
      AND  InvArticulo.id = InvLote.InvArticuloId 
      AND invlote.id = invlotealmacen.InvLoteId 
      AND InvLoteAlmacen.existencia > 0
      AND InvLoteAlmacen.InvAlmacenId = 1
      AND InvArticulo.id = '$IdArticulo'
      ";
    }    
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SC1Q_Actualizar_Troquel_Nuleable        
    DESAROLLADO POR: SERGIO COVA
  */
  function SC1Q_Actualizar_Troquel_Nuleable($flag,$Troquel,$IdArticulo,$IdLote) {
    
    if($flag == true){
      $sql = "
      UPDATE InvLote 
      SET M_PrecioTroquelado = '$Troquel'
      FROM InvLote, invlotealmacen, InvArticulo 
      WHERE InvLote.id = '$IdLote' 
      AND  InvArticulo.id = InvLote.InvArticuloId 
      AND invlote.id = invlotealmacen.InvLoteId 
      AND InvLoteAlmacen.existencia > 0 
      AND InvLoteAlmacen.InvAlmacenId = 1
      AND InvArticulo.id = '$IdArticulo'
      ";
    }
    else{
      $sql = "
      UPDATE InvLote 
      SET M_PrecioTroquelado = NULL
      FROM InvLote, invlotealmacen, InvArticulo 
      WHERE InvLote.id = '$IdLote' 
      AND  InvArticulo.id = InvLote.InvArticuloId 
      AND invlote.id = invlotealmacen.InvLoteId 
      AND InvLoteAlmacen.existencia > 0
      AND InvLoteAlmacen.InvAlmacenId = 1
      AND InvArticulo.id = '$IdArticulo'
    ";
    }

    return $sql;
  }
?>