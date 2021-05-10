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
    Inventario por codigo de barra
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

  if (isset($_GET['Codigos'])){
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
          <input id="origen" name="origen" type="hidden" value="Codigo de Barra">
          </td>
          <td align="right">
            <input type="submit" value="Contar" class="btn btn-outline-success">
          </td>
        </tr>
      </table>
      <input id="motivo" name="motivo" type="hidden" value="Codigos de Barra">
    <br>
    ';
    Iventario_CodBar_C2($_GET['SEDE'],$_GET['Id'],$_GET['Codigos']);
    echo'</form>';

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
  //CASO 1: AL CARGAR EL REPORTE DESDE EL MENU
    $InicioCarga = new DateTime("now");

    $sql = Inv_Lista_Articulos_CodBar();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    echo '
    <form autocomplete="off" action="">
      <div class="autocomplete" style="width:90%;">
        <input id="myInput" type="text" name="Codigos" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteo()" required>
        <input id="myId" name="Id" type="hidden">
        <input id="SEDE" name="SEDE" type="hidden" value="';
        print_r($_GET['SEDE']);
        echo'">
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
    TITULO: R7_Catalogo_Proveedor_C2
    FUNCION: Armar el reporte catalogo de proveedor
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function Iventario_CodBar_C2($SedeConnection,$IdArticulo,$CodigosBarra){

    $conn = FG_Conectar_Smartpharma($SedeConnection);

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
    <br/>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Todo <input name="control" type="radio" onclick="marcarTodo(this)"></th>
            <th scope="col" class="CP-sticky">Con existencia <input name="control" onclick="marcarConExistencia(this)" type="radio"></th>
            <th scope="col" class="CP-sticky">Existencia cero <input name="control" onclick="marcarConCero(this)" type="radio"></th>
          </tr>
        </thead>
        <tbody>
    ';

    if($IdArticulo!=""){
      $sql = Inv_Descripcion($IdArticulo);
      $result = sqlsrv_query($conn,$sql);

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

        $class = (intval($Existencia) > 0) ? 'check existencia' : 'check cero';

        echo'<td align="center" colspan="3"><input type="checkbox" class="'.$class.'" name="articulosContar[]" value="'.$IdArticulo.'"/></td>';

        echo '</tr>';
      $contador++;
        }
        echo '
          </tbody>
      </table>';

    }
    else{
      $codigosBarra = explode(",", $CodigosBarra);

      $contador = 1;
      $i=0;

      while ( ($i<count($codigosBarra)) && ($codigosBarra[$i]!='') ){
        $CodigoBarra = $codigosBarra[$i];

        $sql3 = Inv_CodigoBarra($codigosBarra[$i]);
        $result3 =  sqlsrv_query($conn,$sql3);
        $row = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

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

        $class = (intval($Existencia) > 0) ? 'check existencia' : 'check cero';

        echo'<td align="center" colspan="3"><input type="checkbox" class="'.$class.'" name="articulosContar[]" value="'.$IdArticulo.'"/></td>';

        echo '</tr>';
        $i++;
        $contador++;
      }
        echo '
          </tbody>
      </table>';
    }

    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R6Q_Lista_Articulos
    FUNCION: Armar una lista de articulos
    RETORNO: Lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function Inv_Lista_Articulos_CodBar() {
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
    TITULO: R7Q_Catalogo_Proveedor
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function Inv_Descripcion($IdArticulo) {
    $sql = "
      SELECT
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
      FROM InvArticulo
      WHERE InvArticulo.Id = '$IdArticulo'
      GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,InvArticulo.FinConceptoImptoIdCompra
      ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }

  function Inv_CodigoBarra($codigoBarra) {
    $sql = "
      SELECT
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
      FROM InvArticulo
      WHERE InvArticulo.Id = (
      SELECT InvCodigoBarra.InvArticuloId FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$codigoBarra'
      )
      GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,InvArticulo.FinConceptoImptoIdCompra
      ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
?>

<script>
  function marcarConExistencia(that)
  {
    checked = $(that).prop('checked');

    $('.check').attr('checked', false);

    if (checked) {
        $('.existencia').attr('checked', true);
    }
  }

  function marcarConCero(that)
  {
    checked = $(that).prop('checked');

    $('.check').attr('checked', false);

    if (checked) {
        $('.cero').attr('checked', true);
    }
  }

  function marcarTodo(that)
  {
    checked = $(that).prop('checked');

    $('.check').attr('checked', false);

    if (checked) {
        $('.check').attr('checked', true);
    }
  }
</script>
