<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

	$resultado = array();
	$categoria = $_POST["categoria"];	
		
  $respuesta = FG_buscar_subcategoria($categoria);

    if( (is_array($respuesta)) && (!empty($respuesta)) ){      
      echo json_encode($respuesta);
    }
    else{
    	echo("entre aca");
      echo json_encode('SIN SUBCATEGORIA');
    }
?>
<?php	
  /**********************************************************************************/
  /*
    TITULO: FG_buscar_subcategoria    
    DESAROLLADO POR: SERGIO COVA
  */
 	function FG_buscar_subcategoria($categoria){
    $SedeConnection = FG_Mi_Ubicacion();  	
  	$connCPharma = FG_Conectar_CPharma();
       
    $arraySubcategorias = array();
    $arrayIteracion = array();

  	$result = $connCPharma->query("SELECT codigo,nombre FROM subcategorias WHERE codigo_categoria = '$categoria'");  
    
    while($row = $result->fetch_assoc()){
      
      $arrayIteracion["codigo"]=$row['codigo'];
      $arrayIteracion["nombre"]=$row['nombre'];    

      $arraySubcategorias[]=$arrayIteracion;
  	}    

   	mysqli_close($connCPharma);
    return $arraySubcategorias;
  }  
?>