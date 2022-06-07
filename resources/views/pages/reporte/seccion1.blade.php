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
            $('#myInput').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
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
    Actualizar Troquel (Cliente)
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

    SC1_Articulo_Lote($_GET['SEDE'],$_GET['Id'],$_GET['Nombre']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['troquelactual'])){
  //CASO 4: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    $registro = "Se actualizo el troquel del articulo: ".$_GET['Descripcion']." del troquel: ".$_GET['troquelanterior']." al troquel: ".$_GET['troquelactual']." por el motivo: ".$_GET['Motivo'];

    SC1_Actualizar_Fecha($_GET['SEDE'],$_GET['IdLote'],$_GET['troquelactual'],$_GET['IdArti'],$_GET['Descripcion']);
    FG_Guardar_Auditoria('ACTUALIZAR','TROQUEL CLIENTE',$registro);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdLote'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UNA FACTURA
  //se pasa a la seleccion del articulo
    $InicioCarga = new DateTime("now");

    SC1_Actualizar_Lote($_GET['SEDE'],$_GET['IdLote'],$_GET['IdArti'],$_GET['Nombre']);

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
        <input id="myInput" type="text" name="Nombre" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()" required>
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
    TITULO: SC1Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function SC1Q_Lista_Articulos() {
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
    TITULO: SC1Q_Lista_Lotes_Artiulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function SC1Q_Lista_Lotes_Artiulos($IdArticulo) {
    $sql = "
    SELECT InvLote.Id,
    InvLote.M_PrecioCompraBruto,
    InvLote.M_PrecioTroquelado,
    InvLoteAlmacen.Existencia,
    InvLote.Numero,
    InvLote.FechaVencimiento,
    InvLote.FechaEntrada,
    InvLote.Numero,
    InvLote.LoteFabricante
    FROM InvLote, invlotealmacen, InvArticulo
    WHERE InvArticulo.id = InvLote.InvArticuloId
    AND invlote.id = invlotealmacen.InvLoteId
    AND InvLoteAlmacen.existencia > 0
    AND InvLoteAlmacen.InvAlmacenId = 1
    AND InvArticulo.id = '$IdArticulo'
    ORDER BY InvLote.Numero ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SC1Q_Lista_Lotes_IdLote
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function SC1Q_Lista_Lotes_Lote($IdLote,$IdArticulo) {
    $sql = "
    SELECT InvLote.Id,
    InvLote.M_PrecioCompraBruto,
    InvLote.M_PrecioTroquelado,
    InvLoteAlmacen.Existencia,
    InvLote.Numero,
    InvLote.FechaVencimiento,
    InvLote.FechaEntrada,
    InvLote.Numero,
    InvLote.LoteFabricante
    FROM InvLote, invlotealmacen, InvArticulo
    WHERE InvArticulo.id = InvLote.InvArticuloId
    AND invlote.id = '$IdLote'
    AND InvLoteAlmacen.existencia > 0
    AND InvLoteAlmacen.InvAlmacenId = 1
    AND InvArticulo.id = '$IdArticulo'
    ORDER BY InvLote.Numero ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SC1Q_Actualizar_Fecha
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
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
    TITULO: R22_Articulo_Lote
    FUNCION:  Arma la lista de los lotes del articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function SC1_Articulo_Lote($SedeConnection,$IdArticulo,$Descripcion){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = SC1Q_Lista_Lotes_Artiulos($IdArticulo);
    $result = sqlsrv_query($conn,$sql1);

    $sql2 = SQG_Detalle_Articulo($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);
    $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);


    $CodigoArticulo = $row2["CodigoInterno"];
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

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
    $Utilidad = (1 - $Utilidad)*100;

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
          <th scope="col">Codigo</th>
          <th scope="col">Codigo de barra</td>
          <th scope="col">Descripcion</td>
          <th scope="col">Existencia</td>
          <th scope="col">Precio</br>(Con IVA) '.SigVe.'</td>
          <th scope="col">Gravado?</td>
          <th scope="col">Utilidad Configurada</td>
          <th scope="col">Troquel '.SigVe.'</td>
          <th scope="col">Dolarizado?</td>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td>'.$CodigoArticulo.'</td>';
    echo '<td align="center">'.$CodigoBarra.'</td>';
    echo
      '<td align="left" class="CP-barrido">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
    echo '<td align="center">'.intval($Existencia).'</td>';
    echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
    echo '<td align="center">'.$Gravado.'</td>';
    echo '<td align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

    if($TroquelAlmacen1!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen1,2,"," ,"." ).'</td>';
    }
    else if($TroquelAlmacen2!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen2,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '<td align="center">'.$Dolarizado.'</td>';

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
            <th scope="col" class="CP-sticky">Fecha de Entrada</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col">Existencia</td>
            <th scope="col">Costo Bruto '.SigVe.'</td>
            <th scope="col">Troquel '.SigVe.'</td>
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

      echo '<td align="center">'.intval($row['Existencia']).'</td>';

      $costoBruto = $row["M_PrecioCompraBruto"];
      if($costoBruto!=NULL){
        echo '<td align="center">'.number_format($costoBruto,2,"," ,"." ).'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      $costoTroquel = $row["M_PrecioTroquelado"];
      if($costoTroquel!=NULL){
        echo '<td align="center">'.number_format($costoTroquel,2,"," ,"." ).'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
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
  function SC1_Actualizar_Lote($SedeConnection,$IdLote,$IdArticulo,$Descripcion){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = SC1Q_Lista_Lotes_Lote($IdLote,$IdArticulo);
    $result = sqlsrv_query($conn,$sql1);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    $sql2 = SQG_Detalle_Articulo($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);
    $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);


    $CodigoArticulo = $row2["CodigoInterno"];
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

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
    $Utilidad = (1 - $Utilidad)*100;

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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Codigo</th>
          <th scope="col">Codigo de barra</td>
          <th scope="col">Descripcion</td>
          <th scope="col">Existencia</td>
          <th scope="col">Precio</br>(Con IVA) '.SigVe.'</td>
          <th scope="col">Gravado?</td>
          <th scope="col">Utilidad Configurada</td>
          <th scope="col">Troquel '.SigVe.'</td>
          <th scope="col">Dolarizado?</td>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td>'.$CodigoArticulo.'</td>';
    echo '<td align="center">'.$CodigoBarra.'</td>';
    echo
      '<td align="left" class="CP-barrido">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
    echo '<td align="center">'.intval($Existencia).'</td>';
    echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
    echo '<td align="center">'.$Gravado.'</td>';
    echo '<td align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

    if($TroquelAlmacen1!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen1,2,"," ,"." ).'</td>';
    }
    else if($TroquelAlmacen2!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen2,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '<td align="center">'.$Dolarizado.'</td>';

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
            <th scope="col" class="CP-sticky">Precio Compra Bruto</th>
            <th scope="col" class="CP-sticky">Precio Troquelado</th>
            <th scope="col" class="CP-sticky">Precio Troquelado<br>(Nuevo)</th>
            <th scope="col" class="CP-sticky">Motivo</th>
            <th scope="col" class="CP-sticky">Seleccion</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$row["Numero"].'</td>';
      echo '<td align="center">'.$row["LoteFabricante"].'</td>';

      if($row["FechaVencimiento"]!=NULL){
         echo '<td align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center">-</td>';
      }

      echo '<td align="center">'.$row["M_PrecioCompraBruto"].'</td>';
      echo '<td align="center">'.$row["M_PrecioTroquelado"].'</td>';

      echo '
        <form autocomplete="off" action="">
          <td align="center">
            <input id="fechaInicio" type="number" min="0.00" step="any" name="troquelactual" style="width:100%;" autofocus="autofocus">
          </td>
          <td><input id="Motivo" name="Motivo" type="text"></td>
          <td align="center">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">
            <input type="submit" value="Selecionar" class="btn btn-outline-success">
            <input id="IdLote" name="IdLote" type="hidden" value="'.intval($row["Id"]).'">
            <input id="IdArti" name="IdArti" type="hidden" value="'.$IdArticulo.'">
            <input id="Descripcion" name="Descripcion" type="hidden" value="'.$Descripcion.'">
            <input id="troquelanterior" name="troquelanterior" type="hidden" value="'.$row["M_PrecioTroquelado"].'">
          </td>
          </form>
          <br>
        ';
      echo '</tr>';

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
  function SC1_Actualizar_Fecha($SedeConnection,$IdLote,$Troquel,$IdArticulo,$Descripcion){
    $msn = "";
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    if( (($Troquel)>0) ){
      $sql2 = SC1Q_Actualizar_Troquel(true,$Troquel,$IdArticulo,$IdLote);
      $msn = '<h4 class="h5 text-success" align="center">Troquel actualizado con exito</h4>';
    }
    else if( ($Troquel=="NULL") || ($Troquel=="null") || ($Troquel=="") ){
      $sql2 = SC1Q_Actualizar_Troquel(false,$Troquel,$IdArticulo,$IdLote);
      $msn = '<h4 class="h5 text-warning" align="center">Troquel actualizado a NULL</h4>';
    }
    else if( (($Troquel)<=0) ){
      $sql2 = SC1Q_Actualizar_Troquel(false,$Troquel,$IdArticulo,$IdLote);
      $msn = '<h4 class="h5 text-danger" align="center">Verifique los datos</h4>';
    }

    sqlsrv_query($conn,$sql2);

    $sql1 = SC1Q_Lista_Lotes_Lote($IdLote,$IdArticulo);
    $result = sqlsrv_query($conn,$sql1);
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

    $sql2 = SQG_Detalle_Articulo($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);
    $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

    $CodigoArticulo = $row2["CodigoInterno"];
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

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
    $Utilidad = (1 - $Utilidad)*100;

    echo ($msn);
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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Codigo</th>
          <th scope="col">Codigo de barra</td>
          <th scope="col">Descripcion</td>
          <th scope="col">Existencia</td>
          <th scope="col">Precio</br>(Con IVA) '.SigVe.'</td>
          <th scope="col">Gravado?</td>
          <th scope="col">Utilidad Configurada</td>
          <th scope="col">Troquel</td>
          <th scope="col">Dolarizado?</td>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td>'.$CodigoArticulo.'</td>';
    echo '<td align="center">'.$CodigoBarra.'</td>';
    echo
      '<td align="left" class="CP-barrido">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
    echo '<td align="center">'.intval($Existencia).'</td>';
    echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
    echo '<td align="center">'.$Gravado.'</td>';
    echo '<td align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

    if($TroquelAlmacen1!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen1,2,"," ,"." ).'</td>';
    }
    else if($TroquelAlmacen2!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen2,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '<td align="center">'.$Dolarizado.'</td>';

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
            <th scope="col" class="CP-sticky">Precio Compra Bruto</th>
            <th scope="col" class="CP-sticky">Precio Troquelado</th>
          </tr>
        </thead>
        <tbody>
     ';

    $contador = 1;

    echo '<tr>';
    echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
    echo '<td align="center">'.$row["Numero"].'</td>';
    echo '<td align="center">'.$row["LoteFabricante"].'</td>';

    if($row["FechaVencimiento"]!=NULL){
       echo '<td align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';
    }
    else{
      echo '<td align="center">-</td>';
    }

    echo '<td align="center">'.$row["M_PrecioCompraBruto"].'</td>';
    echo '<td align="center">'.$row["M_PrecioTroquelado"].'</td>';
    echo '</tr>';
    echo '
      </tbody>
  </table>';
  sqlsrv_close($conn);
  }
?>
