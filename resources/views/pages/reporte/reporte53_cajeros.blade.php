@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Ventas de concursos
	</h1>
	<hr class="row align-items-start col-12">

	<?php
		// include(app_path().'\functions\config.php');
		// include(app_path().'\functions\functions.php');
		// include(app_path().'\functions\querys_mysql.php');
		// include(app_path().'\functions\querys_sqlserver.php');

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Ventas de concursos');
	?>

    <div>
        {{-- Titulo --}}
		<div>
			<h1 class="h5"><i class="fas fa-prescription"></i> Concurso del <span class="text-success">{{ $fechaInicio }} al {{ $fechaLimite }}</span></h1>
		</div>

		{{-- Lista --}}
		<div class="mt-4">
			{{-- Titulo --}}
			<div>
				<h2 class="h2">Lista de cajeros</h2>
			</div>

			{{-- Tabla --}}
			<div class="table-responsive mt-4">
				<table class="table table-bordered table-hover table-striped">
					<thead class="thead-dark">
						<tr>
							<th>Nro</th>
							<th>Cajero</th>
							<th>Cantidad</th>
							<th>Monto (Bsf)</th>
						</tr>
					</thead>
					<tbody>
						@php
							$conteoFilas = 0;
						@endphp
						@foreach ($datos_concurso as $cajero => $articulos)
							@php
								$conteoFilas++;
								$sumasTotales = sumarCantidadesMonto($articulos);
							@endphp
							<tr>
								<th>{{ $conteoFilas }}</th>
								<td class="font-weight-bold text-uppercase">{{ $cajero }}</td>
								<td class="font-weight-bold">{{ $sumasTotales['cantidad'] }}</td>
								<td>{{ $sumasTotales['monto'] }}</td>							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
    </div>

    <?php 
        $FinCarga = new DateTime("now");
        $IntervalCarga = $InicioCarga->diff($FinCarga);
        echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");

		function sumarCantidadesMonto($lista_articulos)
		{
			$cantidades = 0;
			$monto = 0;

			foreach ($lista_articulos as $index => $articulo) {
				$cantidades+= intVal($articulo['cantidad']);
				$monto+= floatVal($articulo['monto']);
			}
			
			return [
				'cantidad' => $cantidades,
				'monto' => $monto
			];
		}

    ?>
@endsection

