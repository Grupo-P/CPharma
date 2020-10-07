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
		Monitoreo de Inventarios
	</h1>
	<hr class="row align-items-start col-12">
	
<?php	
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $_GET['SEDE'] = FG_Mi_Ubicacion();

  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if (isset($_GET['fechaInicio'])) {

    $InicioCarga = new DateTime("now");

    R31_Monitoreo_Inventarios($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Monitoreo de Inventarios');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	} 
	else{
		echo '
		<form autocomplete="off" action="" target="_blank">
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
            </td>
            <input id="SEDE" name="SEDE" type="hidden" value="';
            print_r($_GET['SEDE']);
            echo'">
            <td align="right">
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>
		    </table>
	  	</form>
  	';
	} 
?>
@endsection

<?php
  /*********************************************************************************/ 
  /*
    TITULO: R31_Monitoreo_Inventarios
    FUNCION: Arma una lista de productos en falla
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R31_Monitoreo_Inventarios($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql5 = R31Q_Monitoreo_Inventarios($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql5);

    $sql6 = R31Q_Monitoreo_Correcciones($FInicial,$FFinal);    
    $result1 = sqlsrv_query($conn,$sql6);

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
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>  
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
          <th scope="col" class="CP-sticky">Origen de Movimiento</th>
          <th scope="col" class="CP-sticky">Numero de Movimiento</th>
          <th scope="col" class="CP-sticky">Fecha de Movimiento</th>
          <th scope="col" class="CP-sticky">Codigo Interno</th>
          <th scope="col" class="CP-sticky">Codigo de Barra</th>
          <th scope="col" class="CP-sticky">Descripcion</th>
          <th scope="col" class="CP-sticky">Cantidad (CC -)</th>
          <th scope="col" class="CP-sticky">Cantidad (CC +)</th>
          <th scope="col" class="CP-sticky">Numero de Lote (CC -)</th>
          <th scope="col" class="CP-sticky">Numero de Lote (CC +)</th>
          <th scope="col" class="CP-sticky">Almacen (CC -)</td>
          <th scope="col" class="CP-sticky">Almacen (CC +)</td>
          <th scope="col" class="CP-sticky">Costo Uniario (CC -)'.SigVe.'</td>
          <th scope="col" class="CP-sticky">Costo Uniario (CC +)'.SigVe.'</td>          
          <th scope="col" class="CP-sticky">Operador</th>         
        </tr>
      </thead>
      <tbody>
    ';

    $contador = 1;
    while($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
      $TipoMovimiento = FG_Limpiar_Texto($row["TipoMovimiento"]);
      $NumeroMovimiento = $row["NumeroMovimiento"];      
      $CostoUniario = $row["CostoUniario"];
      
      if($TipoMovimiento == 'Corrección costo -'){
        $NumeroMovCCNeg = $NumeroMovimiento;
        $CantidadCCNeg = $row["Cantidad"];
        $LoteCCNeg = $row["Lote"];
        $AlmacenCCNeg = $row["Almacen"];
        $CostoUniarioCCNeg = $CostoUniario;
      }
      else if( ($TipoMovimiento=='Corrección costo +') && ($NumeroMovimiento == $NumeroMovCCNeg) &&($CostoUniarioCCNeg >= $CostoUniario) ) {

        $IdArticulo = $row["IdArticulo"];        
        $OrigenMovimiento = $row["OrigenMovimiento"];
        $OrigenMovimiento = determminar_Origen_Movimiento($OrigenMovimiento);        
        $FechaMovimiento = $row["FechaMovimiento"];
        $CodigoArticulo = $row["CodigoInterno"];
        $CodigoBarra = $row["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
        $Cantidad = $row["Cantidad"];
        $Lote = $row["Lote"];
        $Almacen = $row["Almacen"];        
        $Operador = $row["Operador"];
                        
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="left">'.($TipoMovimiento).'</td>';
        echo '<td align="left">'.FG_Limpiar_Texto($OrigenMovimiento).'</td>';
        echo '<td align="left">'.$NumeroMovimiento.'</td>';
        echo '<td align="center">'.$FechaMovimiento->format('d-m-Y').'</td>';
        echo '<td align="left">'.$CodigoArticulo.'</td>';
        echo '<td align="center">'.$CodigoBarra.'</td>';
        echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';
        echo '<td align="center">'.intval($CantidadCCNeg).'</td>';
        echo '<td align="center">'.intval($Cantidad).'</td>';
        echo '<td align="center">'.$LoteCCNeg.'</td>';
        echo '<td align="center">'.$Lote.'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($AlmacenCCNeg).'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($Almacen).'</td>';
        echo '<td align="center">'.number_format($CostoUniarioCCNeg,2,"," ,"." ).'</td>';        
        echo '<td align="center">'.number_format($CostoUniario,2,"," ,"." ).'</td>'; 
        echo '<td align="center">'.$Operador.'</td>';      
        echo '</tr>';
        $contador++;
      }       
    }
    echo '
      </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>  
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
          <th scope="col" class="CP-sticky">Origen de Movimiento</th>
          <th scope="col" class="CP-sticky">Numero de Movimiento</th>
          <th scope="col" class="CP-sticky">Fecha de Movimiento</th>
          <th scope="col" class="CP-sticky">Codigo Interno</th>
          <th scope="col" class="CP-sticky">Codigo de Barra</th>
          <th scope="col" class="CP-sticky">Descripcion</th>
          <th scope="col" class="CP-sticky">Cantidad</th>
          <th scope="col" class="CP-sticky">Numero de Lote</th>
          <th scope="col" class="CP-sticky">Almacen</td>
          <th scope="col" class="CP-sticky">Costo Uniario '.SigVe.'</td>
          <th scope="col" class="CP-sticky">Comentario</td>
          <th scope="col" class="CP-sticky">Operador</th>          
        </tr>
      </thead>
      <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["IdArticulo"];
      $TipoMovimiento = $row["TipoMovimiento"];
      $OrigenMovimiento = $row["OrigenMovimiento"];
      $OrigenMovimiento = determminar_Origen_Movimiento($OrigenMovimiento);
      $NumeroMovimiento = $row["NumeroMovimiento"];
      $FechaMovimiento = $row["FechaMovimiento"];
      $CodigoArticulo = $row["CodigoInterno"];
      $CodigoBarra = $row["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
      $Cantidad = $row["Cantidad"];
      $Lote = $row["Lote"];
      $Almacen = $row["Almacen"];
      $CostoUniario = $row["CostoUniario"];
      $Operador = $row["Operador"];

      $ComentarioAjuste = $row["ComentarioAjuste"];
      $ComentarioTransferencia = $row["ComentarioTransferencia"];
      
      if(!is_null($ComentarioAjuste)){
        $comentario = $ComentarioAjuste;
      }
      else if(!is_null($ComentarioTransferencia)){
        $comentario = $ComentarioTransferencia;
      }
      else{
        $comentario = "-";
      }
       
      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.FG_Limpiar_Texto($TipoMovimiento).'</td>';
      echo '<td align="left">'.FG_Limpiar_Texto($OrigenMovimiento).'</td>';
      echo '<td align="left">'.$NumeroMovimiento.'</td>';
      echo '<td align="center">'.$FechaMovimiento->format('d-m-Y').'</td>';
      echo '<td align="left">'.$CodigoArticulo.'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';
      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
      echo '<td align="center">'.intval($Cantidad).'</td>';
      echo '<td align="center">'.$Lote.'</td>';
      echo '<td align="center">'.FG_Limpiar_Texto($Almacen).'</td>';
      echo '<td align="center">'.number_format($CostoUniario,2,"," ,"." ).'</td>';
      echo '<td align="center">'.FG_Limpiar_Texto($comentario).'</td>';
      echo '<td align="center">'.$Operador.'</td>';      
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
    TITULO: R31Q_Monitoreo_Inventarios
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R31Q_Monitoreo_Inventarios($FInicial,$FFinal) {
    $sql = "
      SELECT 
    --Tipo Movimiento
    InvCausa.Descripcion as TipoMovimiento,
    -- Origen Movimiento
    InvMovimiento.origenMovimiento as OrigenMovimiento,
    --Numero de Movimiento
    InvMovimiento.DocumentoOrigen as NumeroMovimiento,
    --Id Articulo
    InvArticulo.id as IdArticulo,
    --Codigo Interno
    InvArticulo.CodigoArticulo AS CodigoInterno,
    --Codigo de Barra
    (SELECT CodigoBarra
    FROM InvCodigoBarra 
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    --Descripcion
    InvArticulo.Descripcion,
    --Cantidad
    InvMovimiento.Cantidad as Cantidad,
    --Lote
    InvLote.Numero as Lote,
    --Almacen
    InvAlmacen.Descripcion as Almacen,
    --Costo Unitario 
    InvMovimiento.M_CostoUnitario as CostoUniario,
    --Comentario Ajuste
    (SELECT InvAjuste.Comentario
    FROM InvAjuste 
    WHERE InvAjuste.NumeroAjuste = InvMovimiento.DocumentoOrigen AND (InvMovimiento.InvCausaId = '14' OR InvMovimiento.InvCausaId = '15') ) AS ComentarioAjuste,
    --Comentario Transferecia
    (SELECT InvTransferenciaAlmacen.Observaciones
    FROM InvTransferenciaAlmacen 
    WHERE InvTransferenciaAlmacen.NumeroTransferencia = InvMovimiento.DocumentoOrigen AND (InvMovimiento.InvCausaId = '5' OR InvMovimiento.InvCausaId = '6') ) AS ComentarioTransferencia,
    --Operador
    InvMovimiento.Auditoria_Usuario as Operador,
    --Fecha movimiento
    InvMovimiento.FechaMovimiento  as FechaMovimiento
    FROM InvMovimiento
    INNER JOIN InvCausa ON InvCausa.id = InvMovimiento.InvCausaId
    INNER JOIN InvArticulo ON InvArticulo.id = InvMovimiento.InvArticuloId
    INNER JOIN InvLote ON InvLote.id = InvMovimiento.InvLoteId
    INNER JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
    WHERE (InvMovimiento.InvCausaId = '14' OR InvMovimiento.InvCausaId = '15' 
    OR InvMovimiento.InvCausaId = '5' OR InvMovimiento.InvCausaId = '6')
    AND(InvMovimiento.FechaMovimiento > '$FInicial' AND InvMovimiento.FechaMovimiento < '$FFinal')
    ORDER BY InvMovimiento.FechaMovimiento ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  function determminar_Origen_Movimiento($OrigenMovimiento){
    switch ($OrigenMovimiento) {
      case '1':
       $nombreMovimiento = "Ventas";
      break;
      case '2':
        $nombreMovimiento = "Ventas devolucion";
      break;
      case '3':
        $nombreMovimiento = "Compras";
      break;
      case '4':
        $nombreMovimiento = "Indefinido-4";
      break;
      case '5':
        $nombreMovimiento = "Ajuste de Inventario";
      break;
      case '6':
        $nombreMovimiento = "Indefinido-6";
      break;
      case '7':
        $nombreMovimiento = "Correccion de Costo";
      break;
      case '8':
        $nombreMovimiento = "Indefinido-8";
      break;
      case '9':
        $nombreMovimiento = "Toma de Inventario";
      break;
      case '10':
        $nombreMovimiento = "Transferencia de Almacen";
      break;
      default:
        $nombreMovimiento =  "-";
      break;
    }
    return $nombreMovimiento;
  }
  /**********************************************************************************/
  /*
    TITULO: R31Q_Monitoreo_Inventarios
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R31Q_Monitoreo_Correcciones($FInicial,$FFinal) {
    $sql = "
    SELECT 
    InvArticulo.id as IdArticulo,
    InvArticulo.CodigoArticulo AS CodigoInterno,
    (SELECT CodigoBarra
    FROM InvCodigoBarra 
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    InvArticulo.Descripcion,
    InvMovimiento.origenMovimiento as OrigenMovimiento,
    InvMovimiento.DocumentoOrigen as NumeroMovimiento,
    InvCausa.Descripcion as TipoMovimiento,          
    InvMovimiento.Cantidad as Cantidad,
    InvLote.Numero as Lote,
    InvAlmacen.Descripcion as Almacen,
    InvMovimiento.M_CostoUnitario as CostoUniario,
    InvMovimiento.Auditoria_Usuario as Operador,
    InvMovimiento.FechaMovimiento  as FechaMovimiento
    FROM InvMovimiento
    INNER JOIN InvCausa ON InvCausa.id = InvMovimiento.InvCausaId
    INNER JOIN InvArticulo ON InvArticulo.id = InvMovimiento.InvArticuloId
    INNER JOIN InvLote ON InvLote.id = InvMovimiento.InvLoteId
    INNER JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
    WHERE (InvMovimiento.InvCausaId = '11' or InvMovimiento.InvCausaId = '12')
    AND(InvMovimiento.FechaMovimiento > '$FInicial' AND InvMovimiento.FechaMovimiento < '$FFinal')
    ORDER BY InvMovimiento.FechaMovimiento ASC, InvMovimiento.M_CostoUnitario ASC
    ";
    return $sql;
  }
?>