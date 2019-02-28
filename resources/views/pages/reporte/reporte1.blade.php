@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Activacion de proveedores
	</h1>
	<hr class="row align-items-start col-12">

  <?php	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\Functions.php');
	ReporteActivacionProveedores();
?>
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection