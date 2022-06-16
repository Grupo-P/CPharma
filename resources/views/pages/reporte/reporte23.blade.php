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

    <link rel="stylesheet" href="/assets/jquery/jquery-ui-last.css">
    <script src="/assets/jquery/jquery-ui-last.js"></script>

    <script>
        @php
            include(app_path().'\functions\config.php');
            include(app_path().'\functions\functions.php');
            include(app_path().'\functions\querys_mysql.php');
            include(app_path().'\functions\querys_sqlserver.php');


            $SedeConnection = FG_Mi_Ubicacion();

            $conn = FG_Conectar_Smartpharma($SedeConnection);
            $descripcion = [];
            $codigo = [];
            $i = 0;

            $sql = "
                SELECT
                    InvArticulo.Id AS id,
                    InvArticulo.Descripcion AS descripcion,
                    (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra
                FROM InvArticulo
            ";

            $query = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $descripcion[$i]['id'] = $row['id'];
                $descripcion[$i]['label'] = mb_convert_encoding($row['descripcion'], 'UTF-8', 'UTF-8');
                $descripcion[$i]['value'] = mb_convert_encoding($row['descripcion'], 'UTF-8', 'UTF-8');

                $i++;
            }
        @endphp



        $(document).ready(function () {
            $('.inputDescripcion').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId').val(ui.item.id);
                }
            });
        });
    </script>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Actualizar Fecha de Vencimiento
  </h1>
  <hr class="row align-items-start col-12">

<?php 
  $_GET['SEDE'] = FG_Mi_Ubicacion();


  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';
  
  if (isset($_GET['Id'])){
  //CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR
  //se pasa a la carga de los lotes del articulo seleccionado 
    $InicioCarga = new DateTime("now");

    R23_Articulo_Lote($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['ActualizaFecha'])){
  //CASO 4: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    $registro = "Se actualizo el troquel del articulo: ".$_GET['IdArti'];

    R23_Actualizar_Fecha($_GET['SEDE'],$_GET['IdLote'],$_GET['fechaVenActualizada'],$_GET['IdArti'],$_GET['Nombre'],$_GET['ActualizaFecha']);
    FG_Guardar_Auditoria('ACTUALIZAR','FECHA DE VENCIMIENTO','Actualizar Fecha de Vencimiento');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdLote'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    R23_Actualizar_Lote($_GET['SEDE'],$_GET['IdLote'],$_GET['IdArti'],$_GET['Nombre']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
  //Se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");


    echo '
    <form autocomplete="off" action="">
      <div class="autocomplete" style="width:90%;">
        <input id="myInput" class="inputDescripcion" type="text" name="Nombre" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()" required>
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

<?php
  /**********************************************************************************/
  /*
    TITULO: R23Q_Lista_Lotes_Artiulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R23Q_Lista_Lotes_Artiulos($IdArticulo) {
    $sql = "
    SELECT
    InvLote.Id,
    InvLote.Numero,
    InvLote.LoteFabricante,
    InvLote.FechaVencimiento,
    InvLote.M_PrecioCompraBruto,
    InvLote.M_PrecioTroquelado,
    InvLote.FechaEntrada,
    (SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo') AND (InvLoteAlmacen.InvLoteId = InvLote.Id)) as Existencia
    FROM InvLote
    INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvLote.InvArticuloId
    INNER JOIN InvArticulo ON InvArticulo.Id = InvLote.InvArticuloId
    WHERE 
    (InvLote.InvArticuloId = '$IdArticulo')
    AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.Existencia > 0)
    GROUP BY InvLote.Id,InvLote.Numero,InvLote.LoteFabricante,InvLote.FechaVencimiento,InvLote.M_PrecioCompraBruto,InvLote.M_PrecioTroquelado,InvLote.FechaEntrada
    ORDER BY InvLote.Numero DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R23Q_Lista_Lotes_IdLote
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R23Q_Lista_Lotes_Lote($IdLote) {
    $sql = "
    SELECT
    InvLote.Id,
    InvLote.Numero,
    InvLote.LoteFabricante,
    InvLote.FechaVencimiento,
    InvLote.M_PrecioCompraBruto,
    InvLote.M_PrecioTroquelado,
    InvLote.FechaEntrada,
    (SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvLoteId = InvLote.Id)) as Existencia
    FROM InvLote
    INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvLote.InvArticuloId
    INNER JOIN InvArticulo ON InvArticulo.Id = InvLote.InvArticuloId
    WHERE 
    (InvLote.Id = '$IdLote')
    AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.Existencia > 0)
    GROUP BY InvLote.Id,InvLote.Numero,InvLote.LoteFabricante,InvLote.FechaVencimiento,InvLote.M_PrecioCompraBruto,InvLote.M_PrecioTroquelado,InvLote.FechaEntrada
    ORDER BY InvLote.FechaVencimiento DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R23Q_Actualizar_Fecha
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R23Q_Actualizar_Fecha($FechaVencimiento,$IdArticulo,$IdLote) {
    $sql = "
    UPDATE InvLote 
    SET FechaVencimiento = '$FechaVencimiento' 
    FROM InvLote
    WHERE 
    (InvArticuloId = '$IdArticulo') 
    AND (id = '$IdLote')
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R22_Articulo_Lote
    FUNCION:  Arma la lista de los lotes del articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R23_Articulo_Lote($SedeConnection,$IdArticulo,$Descripcion){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R23Q_Lista_Lotes_Artiulos($IdArticulo);
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
            <th scope="col">Articulo</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
    echo '<td>'.FG_Limpiar_Texto($Descripcion).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Numero de Lote</th>            
            <th scope="col" class="CP-sticky">Lote de Fabricante</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Precio Compra Bruto '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio Troquelado '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Fecha de Entrada</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["Numero"].'</td>';
      echo '<td align="center">'.$row["LoteFabricante"].'</td>';
      echo '<td align="center">'.intval($row["Existencia"]).'</td>';

      echo '<td align="center">'.number_format($row["M_PrecioCompraBruto"],2,"," ,"." ).'</td>';
      echo '<td align="center">'.number_format($row["M_PrecioTroquelado"],2,"," ,"." ).'</td>';
      if($row["FechaEntrada"]!=NULL){
         echo '<td align="center">'.$row["FechaEntrada"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      if($row["FechaVencimiento"]!=NULL){
         echo '<td align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }
     
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
             
              <input type="submit" value="Selecionar" class="btn btn-outline-success">
              
              <input id="IdLote" name="IdLote" type="hidden" value="'.intval($row["Id"]).'">

              <input id="IdArti" name="IdArti" type="hidden" value="'.$IdArticulo.'">

              <input id="Nombre" name="Nombre" type="hidden" value="'.$Descripcion.'">
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
    TITULO: R22_Articulo_Lote
    FUNCION:  Arma la lista de los lotes del articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R23_Actualizar_Lote($SedeConnection,$IdLote,$IdArticulo,$Descripcion){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R23Q_Lista_Lotes_Lote($IdLote);
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
            <th scope="col">Articulo</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
    echo '<td>'.FG_Limpiar_Texto($Descripcion).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Numero de Lote</th>
            <th scope="col" class="CP-sticky">Lote de Fabricante</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Precio Compra Bruto '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio Troquelado '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Fecha de Entrada</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</br>(Nueva)</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["Numero"].'</td>';
      echo '<td align="center">'.$row["LoteFabricante"].'</td>';
      echo '<td align="center">'.intval($row["Existencia"]).'</td>';

      echo '<td align="center">'.number_format($row["M_PrecioCompraBruto"],2,"," ,"." ).'</td>';
      echo '<td align="center">'.number_format($row["M_PrecioTroquelado"],2,"," ,"." ).'</td>';
      if($row["FechaEntrada"]!=NULL){
         echo '<td align="center">'.$row["FechaEntrada"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      if($row["FechaVencimiento"]!=NULL){
         echo '<td align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }
     
      echo '
        <form autocomplete="off" action=""> 
          <td align="center">
            <input id="fechaInicio" type="date" name="fechaVenActualizada" style="width:100%;">
          </td>
          <td align="center">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
            <input type="submit" value="Actualizar" name="ActualizaFecha" class="btn btn-outline-success">
            <input type="submit" value="No vence" name="ActualizaFecha" class="btn btn-outline-danger">
            <input id="IdLote" name="IdLote" type="hidden" value="'.intval($row["Id"]).'">
            <input id="IdArti" name="IdArti" type="hidden" value="'.$IdArticulo.'">
            <input id="Nombre" name="Nombre" type="hidden" value="'.$Descripcion.'">
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
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R22_Articulo_Lote
    FUNCION:  Arma la lista de los lotes del articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R23_Actualizar_Fecha($SedeConnection,$IdLote,$FechaVencimiento,$IdArticulo,$Descripcion,$flagActualizacion){
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R23Q_Lista_Lotes_Lote($IdLote);
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
            <th scope="col">Articulo</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
    echo '<td>'.FG_Limpiar_Texto($Descripcion).'</td>';
      echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Numero de Lote</th>
            <th scope="col" class="CP-sticky">Lote de Fabricante</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    $FechaVencimiento = new DateTime($FechaVencimiento);
    $FechaVenActualizar = $FechaVencimiento->format('Y-m-d 00:00:00.0000000');
    $FechaVenMostrar = $FechaVencimiento->format('d-m-Y');

    if($flagActualizacion=="Actualizar"){
      $sql2 = R23Q_Actualizar_Fecha($FechaVenActualizar,$IdArticulo,$IdLote);
    }
    else if($flagActualizacion=="No vence"){
      $sql2 = R23Q_Actualizar_Fecha("9999-12-31 00:00:00.0000000",$IdArticulo,$IdLote);
    }
    
    sqlsrv_query($conn,$sql2);
    
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["Numero"].'</td>';
      echo '<td align="center">'.$row["LoteFabricante"].'</td>';

      if($flagActualizacion=="Actualizar"){
        echo '<td align="center">'.$FechaVencimiento->format('d-m-Y').'</td>';
      }
      else if($flagActualizacion=="No vence"){
        echo '<td align="center">No vence</td>';
      }

      echo '</tr>';
    $contador++;
    }
      echo '
        </tbody>
    </table>';
    sqlsrv_close($conn);
  }
?>
