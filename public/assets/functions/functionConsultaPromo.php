<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

	$resultado = array();
  $respuesta = FG_Articulos_Promocion();
    if( (is_array($respuesta)) && (!empty($respuesta)) ){
      $resultado = FG_Ordenar_Array($respuesta);
      echo json_encode($resultado);
    }
    else{
      echo json_encode('UNICO');
    }
?>
<?php
	/**********************************************************************************/
  /*
    TITULO: SQL_Atributo_Promocion
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
 	function SQL_Atributo_Promocion(){
    $sql = " 
  	SELECT
    InvAtributo.Id,
    InvAtributo.Descripcion
    FROM 
    InvAtributo
    WHERE InvAtributo.Descripcion =  'PromocionConsultor'
    ";
  return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: SQL_Articulos_EnPromocion
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
 	function SQL_Articulos_EnPromocion($IdAtributo){
    $sql = " 
  	SELECT * 
    FROM InvArticuloAtributo 
    WHERE InvArticuloAtributo.InvAtributoId = '$IdAtributo' 
    ";
  return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: FG_Articulos_Promocion
    FUNCION: arma la lista de atributos del articulo
    DESAROLLADO POR: SERGIO COVA
  */
 	function FG_Articulos_Promocion(){
    $SedeConnection = FG_Mi_Ubicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
    $ExistenciaMinima = 20;
    $arraySugeridos = array();
    $arrayIteracion = array();

  	$sql = SQL_Atributo_Promocion();
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
    $IdAtributo = $row['Id'];

    $sql1 = SQL_Articulos_EnPromocion($IdAtributo);
    $result1 = sqlsrv_query($conn,$sql1);
    
    while($row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)){
      $IdArticulo = $row1['InvArticuloId'];

    	$sql2 = SQG_Lite_Detalle_Articulo($IdArticulo);
      $result2 = sqlsrv_query($conn,$sql2);
      $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
      $Descripcion = FG_Limpiar_Texto($row2['Descripcion']);
      $CodigoBarra = $row2['CodigoBarra'];
      $Existencia = $row2['Existencia'];

      if($Existencia>$ExistenciaMinima){
        $Precio = FG_Precio_Consultor($IdArticulo);

        $arrayIteracion["CodigoBarra"]=$CodigoBarra;
        $arrayIteracion["Descripcion"]=$Descripcion;
        $arrayIteracion["Precio"]=floatval($Precio);

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