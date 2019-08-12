@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
@endsection

@section('content')
	<!-- Modal Registro Almacenado Con Exito -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		        <div class="modal-header">
			        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info text-info"></i> Informaci&oacute;n
			          </h5>
			          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
			          </button>
		        </div>

		        <div class="modal-body">
		        	<h4 class="h6">Los registros fueron almacenados exitosamente</h4>
		        </div>

		        <div class="modal-footer">
		        	<button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		        </div>
		    </div>
	    </div>
	</div>

	<h1 class="h5 text-info">
		<i class="fas fa-balance-scale"></i>
		Dias en cero
	</h1>
	<hr class="row align-items-start col-12">

	<?php
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\querys.php');
		include(app_path().'\functions\funciones.php');
		include(app_path().'\functions\reportes.php');

		$InicioCarga = new DateTime("now");

		$_GET['SEDE'] = "FTN";//Borrar esta sentencia

		if(isset($_GET['SEDE'])) {
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		ReporteDiasEnCero($_GET['SEDE']);

		?> 
          <script>
            $('#exampleModalCenter').modal('show');
          </script> 
        <?php
	    
		$FinCarga = new DateTime("now");
	    $IntervalCarga = $InicioCarga->diff($FinCarga);
	    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>

	<script>
	    $(document).ready(function(){
	      $('[data-toggle="tooltip"]').tooltip(); 
	    });
	</script>
@endsection