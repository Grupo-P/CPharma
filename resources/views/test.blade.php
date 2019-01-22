@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Historico de Articulos TEST
	</h1>
	<hr class="row align-items-start col-12">

  <?php	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\Functions.php');
	
	if (isset($_GET['Historico'])  )
	{
	  echo '<br>El IdArticulo es: '.$_GET['IdArticulo'];
	  echo '<br>El CodigoArticulo es: '.$_GET['CodigoArticulo'];
	  echo '<br>La DescripcionArticulo es: '.$_GET['DescripcionArticulo'];
	} 
	else{
		ReporteHistoricoArticulos();
	} 
?>
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection