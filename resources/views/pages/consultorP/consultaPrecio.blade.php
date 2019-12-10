@extends('layouts.modelUser')

@section('title')
  Dosificaciones
@endsection

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
?>

@section('content')
	<span>Consulta de precio</span>
@endsection