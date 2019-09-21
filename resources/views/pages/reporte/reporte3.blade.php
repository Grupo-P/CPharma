@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('scriptsHead')
  <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
  </script>
  <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>

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
  .barrido{
      text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
    }
    .barrido:hover{
      text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
      transform: translate(20px,0px);
    }
  </style>
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Productos mas vendidos
	</h1>
	<hr class="row align-items-start col-12">

  <?php	
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');
  include(app_path().'\functions\reportes.php');

	if (isset($_GET['top']))
	{
    $InicioCarga = new DateTime("now");

    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

    //ReporteProductosMasVendidos($_GET['SEDE'],$_GET['top'],$_GET['fechaInicio'],$_GET['fechaFin']);
    R3_Productos_MasVendidos($_GET['SEDE'],$_GET['top'],$_GET['fechaInicio'],$_GET['fechaFin']);
    GuardarAuditoria('CONSULTAR','REPORTE','Productos mas vendidos');
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	} 
	else{
    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

		echo '
		<form autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td align="center">
              TOP:
            </td>
            <td align="right" style="width:20%;">
              <select name="top" class="form-control">
                <option>5</option>
                <option>10</option>
                <option>15</option>
                <option>20</option>
                <option>50</option>
                <option>100</option>
                <option>200</option>
                <option>500</option>
                <option>1000</option>
              </select>
            </td>
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
  /*
    TITULO: R3_Productos_MasVendidos
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
          [$Top] Top de articulos a buscar
          [$FInicial] Fecha inicial del rango donde se buscara
          [$FFinal] Fecha final del rango donde se buscara
    FUNCION: Arma una tabla con los productos mas vendidos
    RETORNO: No aplica
  */
  function R3_Productos_MasVendidos($SedeConnection,$Top,$FInicial,$FFinal){
    $conn = ConectarSmartpharma($SedeConnection);

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);

    $sql = QG_CleanTable('CP_R3Q_Unidades_Vendidas');
    sqlsrv_query($conn,$sql);
    $sql = QG_CleanTable('CP_R3Q_Unidades_Devueltas');
    sqlsrv_query($conn,$sql);
    $sql = QG_CleanTable('CP_R3Q_Unidades_Compradas');
    sqlsrv_query($conn,$sql);
    $sql = QG_CleanTable('CP_R3Q_Unidades_Reclamadas');
    sqlsrv_query($conn,$sql);

    $sql = R3Q_Unidades_Vendidas($FInicial,$FFinal);
    sqlsrv_query($conn,$sql);
    $sql = R3Q_Unidades_Devueltas($FInicial,$FFinal);
    sqlsrv_query($conn,$sql);
    $sql = R3Q_Unidades_Compradas($FInicial,$FFinal);
    sqlsrv_query($conn,$sql);
    $sql = R3Q_Unidades_Reclamadas($FInicial,$FFinal);  
    sqlsrv_query($conn,$sql);
    $sql = R3Q_TOP_MasVendidos($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0">
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

    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo</th>
              <th scope="col">Descripcion</th>
              <th scope="col">Existencia</th>
              <th scope="col">Tipo</th>
              <th scope="col">Total de Venta</th>
              <th scope="col">Veces Facturado</th>            
              <th scope="col">Unidades vendidas</th>              
              <th scope="col">Unidades Compradas</th>                     
              <th scope="col">Venta diaria</th>
              <th scope="col">Dias restantes</th>
          </tr>
        </thead>
        <tbody>
    ';

//AQUI QUEDE
  $contador = 1;
  while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

  }

    echo '
        </tbody>
    </table>';

    $sql = QG_CleanTable('CP_R3Q_Unidades_Vendidas');
    sqlsrv_query($conn,$sql);
    $sql = QG_CleanTable('CP_R3Q_Unidades_Devueltas');
    sqlsrv_query($conn,$sql);
    $sql = QG_CleanTable('CP_R3Q_Unidades_Compradas');
    sqlsrv_query($conn,$sql);
    $sql = QG_CleanTable('CP_R3Q_Unidades_Reclamadas');
    sqlsrv_query($conn,$sql);

    sqlsrv_close($conn); 
  }
  /*
    TITULO: R3Q_Unidades_Vendidas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
    RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
   */
  function R3Q_Unidades_Vendidas($FInicial,$FFinal) {   
    $sql = "
      SELECT
      VenFacturaDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
      (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas
      INTO CP_R3Q_Unidades_Vendidas
      FROM VenFacturaDetalle
      INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      GROUP BY VenFacturaDetalle.InvArticuloId
      ORDER BY UnidadesVendidas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R3Q_Unidades_Devueltas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces devuelta a clientes y las unidades devuelta de un producto
    RETORNO: Tabla con los articulos, las veces devuelta y las unidades devuelta
  */
  function R3Q_Unidades_Devueltas($FInicial,$FFinal) {  
    $sql = "
      SELECT
      VenDevolucionDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesDevueltas,
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesDevueltas
      INTO CP_R3Q_Unidades_Devueltas
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE
      (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ORDER BY UnidadesDevueltas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R3Q_Unidades_Compradas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
    RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
   */
  function R3Q_Unidades_Compradas($FInicial,$FFinal) {   
    $sql = "
      SELECT
      ComFacturaDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesCompradas,
      (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0)) as UnidadesCompradas
      INTO CP_R3Q_Unidades_Compradas
      FROM ComFacturaDetalle      
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE
      (ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ORDER BY UnidadesCompradas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R3Q_Unidades_Reclamadas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
          [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
    RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
   */
  function R3Q_Unidades_Reclamadas($FInicial,$FFinal) {   
    $sql = "
      SELECT
      ComReclamoDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesReclamadas,
      (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesReclamadas
      INTO CP_R3Q_Unidades_Reclamadas
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE
      (ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ORDER BY UnidadesReclamadas DESC
    ";          
    return $sql;
  }
  /*
    TITULO: R3Q_TOP_MasVendidos
    PARAMETROS: [$Top] Varaible para indicar el top a buscar
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
   */
  function R3Q_TOP_MasVendidos($Top) {
    $sql = "
      SELECT TOP $Top
      CP_R3Q_Unidades_Vendidas.InvArticuloId,
      ((ISNULL(CP_R3Q_Unidades_Vendidas.VecesVendidas,CAST(0 AS INT))) - 
      (ISNULL(CP_R3Q_Unidades_Devueltas.VecesDevueltas,CAST(0 AS INT))) 
      ) AS VecesVendidas,
      ((ISNULL(CP_R3Q_Unidades_Vendidas.UnidadesVendidas,CAST(0 AS INT))) -
      (ISNULL(CP_R3Q_Unidades_Devueltas.UnidadesDevueltas,CAST(0 AS INT))) 
      ) AS UnidadesVendidas,
      ((ISNULL(CP_R3Q_Unidades_Compradas.VecesCompradas,CAST(0 AS INT))) -
      (ISNULL(CP_R3Q_Unidades_Reclamadas.VecesReclamadas,CAST(0 AS INT))) 
      ) AS VecesCompradas,
      ((ISNULL(CP_R3Q_Unidades_Compradas.UnidadesCompradas,CAST(0 AS INT))) -
      (ISNULL(CP_R3Q_Unidades_Reclamadas.UnidadesReclamadas,CAST(0 AS INT))) 
      ) AS UnidadesCompradas
      FROM CP_R3Q_Unidades_Vendidas
      LEFT JOIN CP_R3Q_Unidades_Devueltas ON CP_R3Q_Unidades_Devueltas.InvArticuloId = CP_R3Q_Unidades_Vendidas.InvArticuloId
      LEFT JOIN CP_R3Q_Unidades_Compradas ON CP_R3Q_Unidades_Compradas.InvArticuloId = CP_R3Q_Unidades_Vendidas.InvArticuloId
      LEFT JOIN CP_R3Q_Unidades_Reclamadas ON CP_R3Q_Unidades_Reclamadas.InvArticuloId = CP_R3Q_Unidades_Vendidas.InvArticuloId
      ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }
  /*
    TITULO: R3Q_Detalle_Articulo
    PARAMETROS: [$IdArticulo] $IdArticulo del articulo a buscar
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
   */
  function R3Q_Detalle_Articulo($IdArticulo) {
    $sql = " 
      SELECT
      InvArticulo.Id,
      InvArticulo.CodigoArticulo,
      (SELECT CodigoBarra
          FROM InvCodigoBarra 
          WHERE InvCodigoBarra.InvArticuloId = '$IdArticulo'
          AND InvCodigoBarra.EsPrincipal = 1) As CodigoBarra,
      InvArticulo.Descripcion,
        (SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
          FROM InvLoteAlmacen
          WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
          AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')) AS Existencia,
      InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
      FROM InvArticulo
      WHERE InvArticulo.Id = '$IdArticulo'
    ";
    return $sql;
  }
?>