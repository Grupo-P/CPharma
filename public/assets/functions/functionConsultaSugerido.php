<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

	//$IdArticulo = $_POST["IdArticulo"];
	$IdArticulo = 542;
  print_r(json_encode(FG_Articulos_Sugeridos($IdArticulo)));
?>

<?php
	/**********************************************************************************/
  /*
    TITULO: SQL_Atributo_Articulo
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
 	function SQL_Atributo_Articulo($IdArticulo){
    $sql = " 
  	SELECT
		InvAtributo.Id,
		InvAtributo.Descripcion
		FROM 
		InvArticuloAtributo
		INNER JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
		WHERE InvArticuloAtributo.InvArticuloId =  '$IdArticulo'
    ";
  return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SQL_Articulos_Sugeridos
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
 	function SQL_Articulos_Sugeridos($IdArticulo,$IdAtributo){
    $sql = " 
  	SELECT * 
		FROM InvArticuloAtributo 
		WHERE InvArticuloAtributo.InvAtributoId = '$IdAtributo' 
		and InvArticuloAtributo.InvArticuloId <> '$IdArticulo'
    ";
  return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SQL_Articulos_Sugeridos
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
 	function FG_Articulos_Sugeridos($IdArticulo){
 		$arraySugeridos = array();
    $arrayAtributo = array("Arroz","JabonEnPolvo","Azucar");
    $AtributoSugerido = '';
    $AtributoSugeridoId = '';

    $SedeConnection = 'FTN';//FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);

  	$sql = SQL_Atributo_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    
    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
    	$Atributo = $row['Descripcion'];
    	$IdAtributo = $row['Id'];

    	if(in_array($Atributo,$arrayAtributo)){
    		$indice = array_search($Atributo,$arrayAtributo);
  		 	$AtributoSugerido = $arrayAtributo[$indice];
  		 	$AtributoSugeridoId = $IdAtributo;
    	}
    }

    if($AtributoSugerido!=''){
    	echo'El atributo sugerido es: '.$AtributoSugerido.', y su Id es: '.$AtributoSugeridoId;

    	$sql1 = SQL_Articulos_Sugeridos($IdArticulo,$AtributoSugeridoId);
    	$result1 = sqlsrv_query($conn,$sql1);

    	while($row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)){
    		
    		$IdArticuloSugerido = $row1['InvArticuloId'];

    		$sql2 = SQG_Lite_Detalle_Articulo($IdArticuloSugerido);
    		$result2 = sqlsrv_query($conn,$sql2);
    		$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
    		$DescripcionSugerido = FG_Limpiar_Texto($row2['Descripcion']);
    		$CodigoBarraSugerido = $row2['CodigoBarra'];

    		$PrecioSugerido = FG_Precio_Consultor($IdArticuloSugerido);

    		array_push($arraySugeridos,$CodigoBarraSugerido);
    		array_push($arraySugeridos,$DescripcionSugerido);
    		array_push($arraySugeridos,$PrecioSugerido);
    	}

    }
    return $arraySugeridos;
  }
?>