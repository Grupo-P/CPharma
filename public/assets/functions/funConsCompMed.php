<?php 
  include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $Descripcion = $_POST["Descripcion"];
 	//$Descripcion = 'ATAMEL';
 	if ($Descripcion!='') {
 		$respuesta = consultaMedicamento($Descripcion);
	  echo $respuesta;
 	}
?>
<?php
	function consultaMedicamento($Descripcion){ 
		$SedeConnection = 'ARG';//FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);

  	$sql = Descripcion_Like($Descripcion);
    $result = sqlsrv_query($conn,$sql);

    $tableResponse = '
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-stickyBar">Codigo interno</th>
            <th scope="col" class="CP-stickyBar">Codigo de barra</th>             
            <th scope="col" class="CP-stickyBar">Descripcion</th>
            <th scope="col" class="CP-stickyBar">Existencia</th>
          </tr>
        </thead>
        <tbody>
    ';

    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
    	$IdArticulo = $row['IdArticulo'];
    	$sql1 =	SQG_Detalle_Articulo($IdArticulo);
    	$result1 = sqlsrv_query($conn,$sql1);

    	while($row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)) {
    		$CodigoArticulo = $row1["CodigoInterno"];
      	$CodigoBarra = $row1["CodigoBarra"];
      	$Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      	$Existencia = $row1["Existencia"];

      	$tableResponse = $tableResponse. '<tr>';
	      $tableResponse = $tableResponse. '<td>'.$CodigoArticulo.'</td>';
	      $tableResponse = $tableResponse. '<td>'.$CodigoBarra.'</td>';
	      $tableResponse = $tableResponse.
	      '<td align="left" class="CP-barrido">
	      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
	        .$Descripcion.
	      '</a>
	      </td>';
	      $tableResponse = $tableResponse. '<td align="center">'.intval($Existencia).'</td>';
	      $tableResponse = $tableResponse. '</tr>';
    	}
    }
    $tableResponse = $tableResponse. '
      </tbody>
    </table>';

    sqlsrv_close($conn);

    return $tableResponse;
	}
	/**********************************************************************/
	function Descripcion_Like($DescripLike) {   
    $sql = "
      SELECT
      InvArticulo.Id AS IdArticulo
      FROM InvArticulo
      WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
    ";          
    return $sql;
  }
?>