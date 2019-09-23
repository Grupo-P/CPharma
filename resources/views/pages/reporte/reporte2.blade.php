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
		Historico de productos
	</h1>
	<hr class="row align-items-start col-12">
  <?php	
  	include(app_path().'\functions\config.php');
    include(app_path().'\functions\querys.php');
    include(app_path().'\functions\funciones.php');

    $ArtJson = "";
  	
  	if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");
      
      if (isset($_GET['SEDE'])) {     
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      R2_Historico_Producto($_GET['SEDE'],$_GET['Id']);
      GuardarAuditoria('CONSULTAR','REPORTE','Historico de productos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	} 
  	else {
      $InicioCarga = new DateTime("now");

      if (isset($_GET['SEDE'])){      
        echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';
      
      $sql = R2Q_Lista_Articulos();
      $ArtJson = armarJson($sql,$_GET['SEDE']);

  		echo '
  		<form autocomplete="off" action="" target="_blank">
  	    <div class="autocomplete" style="width:90%;">
  	      <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()" required>
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
  /*
    TITULO: R2_Historico_Producto
    PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
                 [$IdArticuloQ] Id del articulo a buscar
    FUNCION: Armar una tabla del historico de compra del articulo
    RETORNO: No aplica
  */
  function R2_Historico_Producto($SedeConnection,$IdArticulo) {
    
    $conn = ConectarSmartpharma($SedeConnection);
    $connCPharma = ConectarXampp();

    $sql = R2Q_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $sql2 = R2Q_Historico_Articulo($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);

    $CodigoArticulo = $row["CodigoArticulo"];
    $CodigoBarra = $row["CodigoBarra"];
    $Descripcion = utf8_encode(addslashes($row["Descripcion"]));
    $Existencia = $row["Existencia"];
    $IsIVA = $row["ConceptoImpuesto"];
    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($conn,$IdArticulo);
    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
    $Precio = FG_Calculo_Precio($conn,$IdArticulo,$IsIVA,$Existencia);

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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de barra</td>
              <th scope="col">Descripcion</td>
              <th scope="col">Existencia</td>
              <th scope="col">Precio</br>(Con IVA) '.SigVe.'</td>         
              <th scope="col">Gravado?</td>
              <th scope="col">Dolarizado?</td>
              <th scope="col">Tasa actual '.SigVe.'</td>
              <th scope="col">Precio en divisa</br>(Con IVA) '.SigDolar.'</td>
          </tr>
        </thead>
        <tbody>
      ';
    echo '<tr>';
    echo '<td>'.$CodigoArticulo.'</td>';
    echo '<td align="center">'.$CodigoBarra.'</td>';

    echo 
      '<td align="left" class="barrido">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';

    echo '<td align="center">'.intval($Existencia).'</td>';
    echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>'; 
    echo '<td align="center">'.$Gravado.'</td>';
    echo '<td align="center">'.$Dolarizado.'</td>';

    if($TasaActual!=0){
      echo '<td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      $PrecioDolar = $Precio/$TasaActual;
      echo '<td align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center">0,00</td>';
      echo '<td align="center">0,00</td>';
    }
    echo '
        </tr>
        </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Proveedor</th>
              <th scope="col">Fecha de documento</th>
              <th scope="col">Cantidad recibida</th>
              <th scope="col">Costo bruto</br>(Sin IVA) '.SigVe.'</th>
              <th scope="col">Tasa en historico '.SigVe.'</th>
              <th scope="col">Costo bruto</br>HOY (Sin IVA) '.SigVe.'</th>
          <th scope="col">Costo en divisa</br>(Sin IVA) '.SigDolar.'</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td>'.utf8_encode(addslashes($row2["Nombre"])).'</td>';
        echo '<td align="center">'.$row2["FechaDocumento"]->format('d-m-Y').'</td>';
        echo '<td align="center">'.intval($row2['CantidadRecibidaFactura']).'</td>';
        echo '<td align="center">'.number_format($row2["M_PrecioCompraBruto"],2,"," ,"." ).'</td>';

        $FechaHist = $row2["FechaDocumento"]->format('Y-m-d');
        $Tasa = FG_Tasa_Fecha($connCPharma,$FechaHist);
         
        if($Tasa != 0) {
          $CostoDivisa = $row2["M_PrecioCompraBruto"]/$Tasa;
          echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';

          if($TasaActual!=0){
            $CostoBrutoHoy = $CostoDivisa*$TasaActual;
            echo '<td align="center">'.number_format($CostoBrutoHoy,2,"," ,"." ).'</td>';
          }
          else{
            echo '<td align="center">0,00</td>';
          }

          echo '<td align="center">'.number_format($CostoDivisa,2,"," ,"." ).'</td>';
        }
        else{
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
        }
        echo '</tr>';
      $contador++;
      }
      echo '
        </tbody>
    </table>';
    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }
  /*
    TITULO: R2Q_Lista_Articulos
    PARAMETROS: No aplica
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
   */
  function R2Q_Lista_Articulos() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY InvArticulo.Descripcion ASC
    ";
    return $sql;
  }
  /*
    TITULO: R2Q_Detalle_Articulo
    PARAMETROS: [$IdArticulo] $IdArticulo del articulo a buscar
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
   */
  function R2Q_Detalle_Articulo($IdArticulo) {
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
  /*
    TITULO: R2Q_Historico_Articulo
    PARAMETROS: [$IdArticuloQ] Id del articulo
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
   */
  function R2Q_Historico_Articulo($IdArticulo) {
    $sql = "
      SELECT
      GenPersona.Nombre,
      CONVERT(DATE,ComFactura.FechaRegistro) As FechaRegistro,
      CONVERT(DATE,ComFactura.FechaDocumento) As FechaDocumento,
      ComFacturaDetalle.CantidadRecibidaFactura,
      ComFacturaDetalle.M_PrecioCompraBruto
      FROM InvArticulo
      INNER JOIN ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE InvArticulo.Id = '$IdArticulo'
      ORDER BY ComFactura.FechaDocumento DESC
    ";
    return $sql;
  }
?>