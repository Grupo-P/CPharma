@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {
      box-sizing:border-box;
    }
    .autocomplete {
      position:relative; 
      display:inline-block;
    }
    input {
      border:1px solid transparent;
      background-color:#f1f1f1;
      border-radius:5px;
      padding:10px;
      font-size:16px;
    }
    input[type=text] {
      background-color:#f1f1f1; 
      width:100%;
    }
    .autocomplete-items {
      position:absolute;
      border:1px solid #d4d4d4;
      border-bottom:none;
      border-top:none;
      z-index:99;
      top:100%;
      left:0;
      right:0;
    }
    .autocomplete-items div {
      padding:10px;
      cursor:pointer;
      background-color:#fff; 
      border-bottom:1px solid #d4d4d4; 
    }
    .autocomplete-items div:hover {
      background-color:#e9e9e9;
    }
    .autocomplete-active {
      background-color:DodgerBlue !important; 
      color:#fff;
    }
  </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Detalle de movimientos
  </h1>
  <hr class="row align-items-start col-12">
  
<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $ArtJson = "";
  $CodJson = "";

  if(isset($_GET['SEDE'])) {
    echo '
      <h1 class="h5 text-success"  align="left">
        <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE'])
      .'</h1>
    ';
  }
  echo '<hr class="row align-items-start col-12">';
  
  if(isset($_GET['Id'])) {
    $InicioCarga = new DateTime("now");

    R12_Detalle_Movimientos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['Id']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Detalle de movimientos');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdCB'])) {
    $InicioCarga = new DateTime("now");

    R12_Detalle_Movimientos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['IdCB']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Detalle de movimientos');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else {
    $InicioCarga = new DateTime("now");

    $sql = R12Q_Lista_Articulos();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    $sql1 = R12Q_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

    echo '
      <form id="form" autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td align="center">Fecha Inicio:</td>
            <td>
              <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
            </td>

            <td align="center">Fecha Fin:</td>
            <td align="right">
              <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
              <input id="SEDE" name="SEDE" type="hidden" value="'; print_r($_GET['SEDE']);
              echo'">
            </td>
          </tr>

          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>

          <tr>
            <td colspan="4">
              <div class="autocomplete" style="width:90%;">
                <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
              </div>

              <input id="myId" name="Id" type="hidden">

              <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
            </td>
          </tr>

          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
        </table>
      
        <div class="autocomplete" style="width:90%;">
          <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
          <input id="myIdCB" name="IdCB" type="hidden">
        </div>
        <input id="SEDE" name="SEDE" type="hidden" value="'; 
          print_r($_GET['SEDE']);
          echo'">
        <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
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
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsCB = eval(<?php echo $CodJson ?>);
      autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myIdCB"), ArrJsCB);
    </script> 
  <?php
    }
  ?>  
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R12Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA  
   */
  function R12Q_Lista_Articulos() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY InvArticulo.Descripcion ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_Lista_Articulos_CodBarra() {
    $sql = "
      SELECT
      (SELECT CodigoBarra
      FROM InvCodigoBarra 
      WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
      AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY CodigoBarra ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12_Detalle_Movimientos
    FUNCION: arma la lista del troquel segun el articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA 
  */
  function R12_Detalle_Movimientos($SedeConnection,$FInicial,$FFinal,$IdArticulo) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
   
    $sql = R12Q_seccion1($FInicial,$FFinal,$IdArticulo);
    $result = sqlsrv_query($conn,$sql);

    $sql2 = R12Q_seccion2($FInicial,$FFinal,$IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);  

    $sql3 = R12Q_seccion3($FInicial,$FFinal,$IdArticulo);
    $result3 = sqlsrv_query($conn,$sql3);   

    echo '
      <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
      </div>
      <br/>
    ';

    echo '
      <h6 align="center">Periodo desde el '.date("d-m-Y",strtotime($FInicial)).' al '.date("d-m-Y",strtotime($FFinal)).'</h6>

      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de movimiento</td> 
            <th scope="col" class="CP-sticky">Efecto</th> 
            <th scope="col" class="CP-sticky">Cantidad</th>             
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {      
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';        
        echo '<td align="center"><strong>'.$row['TipoMovimiento'].'</strong></td>';
        echo '<td align="center"><strong>'.$row['Efecto'].'</strong></td>';      
        echo '<td align="center"><strong>'.$row['Cantidad'].'</strong></td>';
        echo '</tr>';
        $contador++;
      }

    echo '
        </tbody>
      </table>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</td>
            <th scope="col" class="CP-sticky">Tipo de movimiento</td>
            <th scope="col" class="CP-sticky">Efecto</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Saldo</th>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;
    $saldo = 0;
    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {      
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';        
        echo '<td align="center"><strong>'.$row2['Fecha']->format('d-m-Y').'</strong></td>';
        echo '<td align="center"><strong>'.$row2['TipoMovimiento'].'</strong></td>';
        echo '<td align="center"><strong>'.$row2['Efecto'].'</strong></td>';      
        echo '<td align="center"><strong>'.$row2['Cantidad'].'</strong></td>';        

        if($row2['Efecto']=="+"){
          $saldo = $saldo + $row2['Cantidad'];
        }else if($row2['Efecto']=="-"){
          $saldo = $saldo + ((-1)*$row2['Cantidad']);
        }

        echo '<td align="center"><strong>'.$saldo.'</strong></td>';
        echo '</tr>';

        $contador++;
      }

    echo '
        </tbody>
      </table>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</td>
            <th scope="col" class="CP-sticky">Tipo de movimiento</td>
            <th scope="col" class="CP-sticky">Efecto</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Saldo</th>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;
    $saldo = 0;
    while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {      
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';        
        echo '<td align="center"><strong>'.$row3['Fecha']->format('d-m-Y').'</strong></td>';
        echo '<td align="center"><strong>'.$row3['TipoMovimiento'].'</strong></td>';
        echo '<td align="center"><strong>'.$row3['Efecto'].'</strong></td>';      
        echo '<td align="center"><strong>'.$row3['Cantidad'].'</strong></td>';        

        if($row3['Efecto']=="+"){
          $saldo = $saldo + $row3['Cantidad'];
        }else if($row3['Efecto']=="-"){
          $saldo = $saldo + ((-1)*$row3['Cantidad']);
        }

        echo '<td align="center"><strong>'.$saldo.'</strong></td>';
        echo '</tr>';

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
    TITULO: R12Q_seccion1
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_seccion1($FInicial,$FFinal,$IdArticulo) {
    $sql = "
      SELECT 
      InvCausa.id as idCausa, 
      InvCausa.Descripcion AS TipoMovimiento,
      (SELECT IIF(InvCausa.EsPositiva = 1, '+', '-')) as Efecto,
      ROUND(CAST(SUM(InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      LEFT JOIN InvCausa ON InvCausa.Id = InvMovimiento.InvCausaId
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND(CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
      AND (
        (InvMovimiento.InvCausaId=1) OR (InvMovimiento.InvCausaId=2) OR (InvMovimiento.InvCausaId=3) OR (InvMovimiento.InvCausaId=4)
        OR (InvMovimiento.InvCausaId=5) OR (InvMovimiento.InvCausaId=6) OR (InvMovimiento.InvCausaId=11) OR (InvMovimiento.InvCausaId=12)
        OR (InvMovimiento.InvCausaId=14)OR (InvMovimiento.InvCausaId=15)
      )
      GROUP BY InvCausa.id,InvCausa.Descripcion,InvCausa.EsPositiva
      ORDER BY InvCausa.id asc
    ";
    return $sql;
  }
/**********************************************************************************/
  /*
    TITULO: R12Q_seccion2
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_seccion2($FInicial,$FFinal,$IdArticulo) {
    $sql = "
      SELECT 
      InvCausa.id as idCausa,
      CONVERT(DATE,InvMovimiento.FechaMovimiento) AS Fecha, 
      InvCausa.Descripcion AS TipoMovimiento,
      (SELECT IIF(InvCausa.EsPositiva = 1, '+', '-')) AS Efecto,
      ROUND(CAST(SUM(InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      LEFT JOIN InvCausa ON InvCausa.Id = InvMovimiento.InvCausaId
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND(CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
      AND (
        (InvMovimiento.InvCausaId=1) OR (InvMovimiento.InvCausaId=2) OR (InvMovimiento.InvCausaId=3) OR (InvMovimiento.InvCausaId=4)
        OR (InvMovimiento.InvCausaId=5) OR (InvMovimiento.InvCausaId=6) OR (InvMovimiento.InvCausaId=11) OR (InvMovimiento.InvCausaId=12)
        OR (InvMovimiento.InvCausaId=14)OR (InvMovimiento.InvCausaId=15)
      )
      GROUP BY InvCausa.id,InvCausa.Descripcion,InvCausa.EsPositiva,CONVERT(DATE,InvMovimiento.FechaMovimiento)
      ORDER BY CONVERT(DATE,InvMovimiento.FechaMovimiento) asc
    ";
    return $sql;
  }
/**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_seccion3($FInicial,$FFinal,$IdArticulo) {
    $sql = "
      SELECT 
      InvCausa.id as idCausa,
      CONVERT(DATE,InvMovimiento.FechaMovimiento) AS Fecha, 
      InvCausa.Descripcion AS TipoMovimiento,
      (SELECT IIF(InvCausa.EsPositiva = 1, '+', '-')) AS Efecto,
      ROUND(CAST((InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      LEFT JOIN InvCausa ON InvCausa.Id = InvMovimiento.InvCausaId
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND(CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
      AND (
        (InvMovimiento.InvCausaId=1) OR (InvMovimiento.InvCausaId=2) OR (InvMovimiento.InvCausaId=3) OR (InvMovimiento.InvCausaId=4)
        OR (InvMovimiento.InvCausaId=5) OR (InvMovimiento.InvCausaId=6) OR (InvMovimiento.InvCausaId=11) OR (InvMovimiento.InvCausaId=12)
        OR (InvMovimiento.InvCausaId=14)OR (InvMovimiento.InvCausaId=15)
      )    
      ORDER BY CONVERT(DATE,InvMovimiento.FechaMovimiento) asc
    ";
    return $sql;
  }
?>