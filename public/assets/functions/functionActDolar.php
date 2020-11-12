<?php 
	include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

	$SedeConnection = FG_Mi_Ubicacion();
  $connCPharma = FG_Conectar_CPharma();
  $TasaVenta = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));
  mysqli_close($connCPharma);
  echo $TasaVenta;
?>