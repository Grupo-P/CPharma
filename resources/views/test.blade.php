@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Historico de Articulos
	</h1>
	<hr class="row align-items-start col-12">

  <?php	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\Functions.php');
	
	if (isset($_GET['Historico'])  )
	{
	  TableHistoricoArticulos(
	  	$_GET['IdArticulo'],
	  	$_GET['CodigoArticulo'],
	  	$_GET['DescripcionArticulo']
	  );
	} 
	else{
		TableArticulos();
	} 
?>
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection