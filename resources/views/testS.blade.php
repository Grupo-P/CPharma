@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

@section('content')
  <?php 
	$NombreCliente = gethostname();
	$IpCliente = gethostbyname(gethostname());
	echo "Su direccion IP es : ".$IpCliente;

	$Octeto = explode(".", $IpCliente);
	echo"<br/>Segmento de red: ".$Octeto[2];

	switch ($Octeto[2]) {
		case '1':
			echo'<br/>Estoy en FTN';
		break;

		case '10':
			echo'<br/>Estoy en FTN';
		break;

		case '7':
			echo'<br/>Estoy en FLL';
		break;

		case '12':
			echo'<br/>Estoy en FAU';
		break;
		
		default:
			echo '<br/>No estoy en la empresa';
		break;
	}



  ?>
@endsection