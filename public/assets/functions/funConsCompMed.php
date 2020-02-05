<?php 
  include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $Descripcion = $_POST["Descripcion"];
 	//$Descripcion = 'ATAMEL';
 	if ($Descripcion!='') {
 		$respuesta = consultaMedicamento($Descripcion);
	    if( (is_array($respuesta)) && (!empty($respuesta)) ){
	      echo json_encode($respuesta);
	    }
	    else{
	      echo json_encode('UNICO');
	    }
 	}
?>
<?php
	function consultaMedicamento($Descripcion){ 
		$SedeConnection = 'ARG';//FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
  	$arrayConsultaMed = array();

  	$sql = Descripcion_Like($Descripcion);
    $result = sqlsrv_query($conn,$sql);

    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
    	$IdArticulo = $row['IdArticulo'];
    	$sql1 =	SQG_Detalle_Articulo($IdArticulo);
    	$result1 = sqlsrv_query($conn,$sql1);

    	while($row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)) {
    		$arrayConsultaMed[] = FG_Limpiar_Texto($row1['Descripcion']);
    	}
    }
    return $arrayConsultaMed;
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