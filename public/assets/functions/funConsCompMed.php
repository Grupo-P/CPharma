<?php 
  include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $Descripcion = $_POST["Descripcion"];
  $precio = FG_Precio_Consultor($IdArticulo);
  FG_Registro_Datos($IdArticulo,$precio);
  echo''.$precio;
?>