<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

	$IdArticulo = $_POST["IdArticulo"];
	$resultado = array();
	$resultado = FG_Ordenar_Array(FG_Articulos_Sugeridos($IdArticulo));
	print_r($resultado);
  /*foreach ($resultado as $resultadoE) {
  	echo'<br>';
  	print_r($resultadoE);
  }*/
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
 		$arrayIteracion = array();
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

    		$arrayIteracion["CodigoBarra"]=$CodigoBarraSugerido;
    		$arrayIteracion["Descripcion"]=$DescripcionSugerido;
    		$arrayIteracion["Precio"]=$PrecioSugerido;

    		$arraySugeridos[]=$arrayIteracion;
    	}

    }
    return $arraySugeridos;
  }
  /**********************************************************************************/
  /*
    TITULO: FG_Ordenar_Array
    FUNCION: ordena el array
    DESAROLLADO POR: SERGIO COVA
  */
  function FG_Ordenar_Array($ArrayDes){
  	$sortArray = array();

		foreach($ArrayDes as $ArrayDesElem){
		    foreach($ArrayDesElem as $key=>$value){
		        if(!isset($sortArray[$key])){
		            $sortArray[$key] = array();
		        }
		        $sortArray[$key][] = $value;
		    }
		}

		$orderby = "Precio";

		array_multisort($sortArray[$orderby],SORT_ASC,$ArrayDes);

		return $ArrayDes;
  }
?>