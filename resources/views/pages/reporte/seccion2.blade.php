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
          <th scope="col">Cohigo Interno</th>
          <th scope="col">hescripcion</th>
          <th scope="col">Cantihah</th>   
          <th scope="col">Precio</br>(Con IVA) '.SigVe.'</th>       
          <th scope="col">Gravaho</th>
          <th scope="col">holarizaho</th>
          <th scope="col">Numero Lote</th>
          <th scope="col">Existencia</th> 
          <th scope="col">Troquel Actual <br> (CON IVA) '.SigVe.'</th>
          <th scope="col">Precio Devolucion <br> (SIN IVA) '.SigVe.'</th>';
          
          if($flagTroquelNuevo){
            echo '<th scope="col">Troquel Nuevo <br> (CON IVA) '.SigVe.'</th>';
          }                

        echo'</tr>
      </thead>
      <tbody>      
    ';

    $contador = 1;
    $TroquelSugerido = 0;
    while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $idArticulo = $row1["IdArticulo"];

        $sql = R2Q_Detalle_Articulo($idArticulo);
        $result = sqlsrv_query($conn,$sql);
        $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
        
        $Existencia = $row["Existencia"];
        $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
        $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
        $IsTroquelado = $row["Troquelado"];
        $IsIVA = $row["Impuesto"];
        $UtilidadArticulo = $row["UtilidadArticulo"];
        $UtilidadCategoria = $row["UtilidadCategoria"];
        $TroquelAlmacen1 = $row["TroquelAlmacen1"];
        $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
        $TroquelAlmacen2 = $row["TroquelAlmacen2"];
        $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
        $PrecioCompraBruto = $row["PrecioCompraBruto"];
        $Dolarizado = $row["Dolarizado"];
        $CondicionExistencia = 'CON_EXISTENCIA';        
       
        $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
        $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
         
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
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte10?Descrip='.FG_Limpiar_Texto($row1["Descripcion"]).'&Id='.$idArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .FG_Limpiar_Texto($row1["Descripcion"]).
        '</a>
        </td>';        
        echo '<td align="center">'.intval($row1["CantidadDevolucion"]).'</td>';
        echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
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
  /**********************************************************************************/
  /*
    TITULO: R2Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Detalle_Articulo($IdArticulo) {
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
        WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
--UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
  FROM VenCondicionVenta
  WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
--Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
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
--Marca
    InvMarca.Nombre as Marca,
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
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
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
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE InvArticulo.Id = '$IdArticulo'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
?>