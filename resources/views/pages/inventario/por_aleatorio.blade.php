@extends('layouts.model')

@section('title')
  Inventario
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
    <i class="fas fa-boxes"></i>
    Inventario aleatorio
  </h1>
  <hr class="row align-items-start col-12">

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $ArtJson = "";
  $_GET['SEDE'] = FG_Mi_Ubicacion();

  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';
  
  if (isset($_GET['cantidad'])){
  //CASO 2: CARGA AL HABER SELECCIONADO UN PROVEEDOR 
    $InicioCarga = new DateTime("now");
    
    echo '
    <form autocomplete="off" action="/inventario/create">
      <table style="width:100%;">
        <tr>          
          <td>
          <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
          </td>
          <td>
          <input id="origen" name="origen" type="hidden" value="Aleatorio">
          </td>
          <td align="right">
            <input type="submit" value="Contar" class="btn btn-outline-success">
          </td>
        </tr>
      </table>
      <input id="motivo" name="motivo" type="hidden" value="'.$_GET['condVent'].'">   
    <br>
    ';
    Iventario_Aleatorio_C2($_GET['SEDE'],$_GET['cantidad'],$_GET['condVent']);
    echo'</form>';
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  } 
  /*
  else if(isset($_GET['CONTEO'])){
  //CASO 3: CARGA AL HABER SELECCIONADO UN PEDIDO EN BASE A DIAS Y EL RANGO
  
    print_r($_GET['articulosContar']);

  }
  */
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
    $InicioCarga = new DateTime("now");

    echo '
    <form autocomplete="off" action="">      
      <table style="width:100%;">
        <tr>          
          <td>
            <span><strong>Cant. a generar</strong></span>
          </td>
          <td>
            <input type="number" name="cantidad" placeholder="Ingrese la cantidad" step="1" required min="1" class"ml-10">
          </td>

          <td>
            <span><strong>Condicion de venta</strong></span>
          </td>
          <td>
            <select name="condVent" class="form-control" required="required" style="width:50%;display:inline;">
              <option value=""></option> 
              <option value="ConVenta">Con Ventas</option>
              <option value="SinVenta">Sin Ventas</option>
            </select>
          </td>
          <td>
            <input id="SEDE" name="SEDE" type="hidden" value="';
            print_r($_GET['SEDE']);
            echo'">
          </td>
          <td>
            <input type="submit" value="Buscar" class="btn btn-outline-success">
          </td>
        </tr>
      </table>
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
    TITULO: R7_Catalogo_Proveedor_C2
    FUNCION: Armar el reporte catalogo de proveedor
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function Iventario_Aleatorio_C2($SedeConnection,$Cantidad,$CondicionVenta){

    $conn = FG_Conectar_Smartpharma($SedeConnection);

    //$FFin= date("Y-m-d");
    //$FInicio = date("Y-m-d",strtotime($FFin."- 20 days"));

    $FFin= date("2018-12-12");
    $FInicio = date("Y-m-d",strtotime($FFin."- 1 days"));

    if($CondicionVenta=="ConVenta"){
      $sql = Inv_ConVenta($FInicio,$FFin,$Cantidad);
      $result = sqlsrv_query($conn,$sql);  
    }
    else if($CondicionVenta=="SinVenta"){
      $sql = Inv_SinVenta($FInicio,$FFin,$Cantidad);
      $result = sqlsrv_query($conn,$sql);
    }

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
            <th scope="col">Condicion de Venta</th>
          </tr>
        </thead>
        <tbody>
        <tr>
      ';
    echo '<td align="center">'.($CondicionVenta).'</td>';
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
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Seleccion <input type="checkbox" onclick="marcarTodos(this)"/></th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];
      $CodigoArticulo = $row["CodigoArticulo"];
      $CodigoBarra = $row["CodigoBarra"];
      $Existencia = $row["Existencia"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$CodigoArticulo.'</td>';
      echo '<td align="center">'.$CodigoBarra.'</td>';
      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
      echo '<td align="center">'.intval($Existencia).'</td>';
      echo'<td align="center"><input type="checkbox" name="articulosContar[]" value="'.$IdArticulo.'"/></td>';
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
    TITULO: R6Q_Descripcion_Like
    FUNCION: Consulta los articulos que conindicen con la palabra buscada
    RETORNO: lista de id de articulos en coincidencia
    DESAROLLADO POR: SERGIO COVA
  */
  function Inv_SinVenta($FInicio,$FFin,$Cantidad) {
    $sql = "
      SELECT TOP $Cantidad
      InvArticulo.Id,
      InvArticulo.CodigoArticulo,
      (SELECT CodigoBarra
          FROM InvCodigoBarra 
          WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
          AND InvCodigoBarra.EsPrincipal = 1) As CodigoBarra,
      InvArticulo.Descripcion,
      (SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
          FROM InvLoteAlmacen
          WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
          AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS Existencia
      FROM VenVenta
      INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
      INNER JOIN InvArticulo ON InvArticulo.Id = VenVentaDetalle.InvArticuloId
      WHERE  VenVenta.FechaDocumentoVenta NOT BETWEEN '$FInicio' AND '$FFin'
      AND (SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) > 0
      GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,InvArticulo.FinConceptoImptoIdCompra
      ORDER BY NEWID()
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R7Q_Catalogo_Proveedor
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function Inv_ConVenta($FInicio,$FFin,$Cantidad) {
    $sql = "
      SELECT TOP $Cantidad
      InvArticulo.Id,
      InvArticulo.CodigoArticulo,
      (SELECT CodigoBarra
          FROM InvCodigoBarra 
          WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
          AND InvCodigoBarra.EsPrincipal = 1) As CodigoBarra,
      InvArticulo.Descripcion,
      (SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
          FROM InvLoteAlmacen
          WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
          AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS Existencia
      FROM VenVenta
      INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
      INNER JOIN InvArticulo ON InvArticulo.Id = VenVentaDetalle.InvArticuloId
      WHERE  VenVenta.FechaDocumentoVenta BETWEEN '$FInicio' AND '$FFin'
      AND (SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) > 0
      GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,InvArticulo.FinConceptoImptoIdCompra
      ORDER BY NEWID()
    ";
    return $sql;
  }
?>

<script>
  function marcarTodos(source) 
  {
    checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
    for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
    {
      if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
      {
        checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
      }
    }
  }
</script>