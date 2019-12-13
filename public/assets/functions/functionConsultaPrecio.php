<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $IdArticulo = $_POST["IdArticulo"];
  $precio = FG_Precio_Consultor($IdArticulo);
  echo''.$precio;
?>
<?php
	/**********************************************************************************/
  /*
    TITULO: FG_Registro_Datos
    FUNCION: registra el articulo escaneado en la base de datos
    DESAROLLADO POR: SERGIO COVA
  */
 	function FG_Registro_Datos(){
 		//Aqui voy a desarrollar el registro de los datos escaneados
 	}
?>