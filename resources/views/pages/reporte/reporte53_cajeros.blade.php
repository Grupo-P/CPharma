@extends('layouts.model')

@section('title')
  Reporte | Ventas concursos
@endsection

@section('scriptsHead')
	<link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" rel="stylesheet">
 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js"></script>
@endsection

@section('scriptsFoot')
	<script>
		$(document).ready(function() {
			$('#tablaCajeros').DataTable({
                order: [[2, 'desc']],
				paging: false,
        		searching: false,

                //Responsive
                responisve: true,
                autoWidth: false,

                //Botones DataTable
                dom: 'Bfrtip',
                lengthMenu: [],
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel" data-toggle="tooltip" data-placement="right" title="Excel"></i>',
                        title: 'Reporte Concurso',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        className : 'flat-button ico-excel mr-1'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv" data-toggle="tooltip" data-placement="right" title="CSV"></i>',
                        title: 'Reporte Concurso',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        className : 'flat-button ico-csv mr-1'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print" data-toggle="tooltip" data-placement="right" title="Imprimir"></i>',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        className : 'flat-button ico-imprimir mr-1'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf" data-toggle="tooltip" data-placement="right" title="PDF"></i>',
                        title: 'Reporte Concurso',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5 ]
                        },
                        className : 'flat-button ico-pdf mr-1'
                    },
                ],
			});

			//Tooltip
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
		});
	</script>
@endsection

@section('content')
	<style>
		.buttons-excel, .buttons-csv {
			background-color: #28a745 !important;
			border-color: #28a745 !important;
		}

		.buttons-print {
			background-color: #007bff !important;
			border-color: #007bff !important;
		}

		.buttons-pdf {
			background-color: #dc3545 !important;
			border-color: #dc3545 !important;
		}

		.cajeroLink {
			cursor: pointer !important;
		}

		.cajeroLink:hover {
			position: relative;
			transform: translateX(4px);
			transition: all ease-in-out .2s;
		}

		.lista {
            list-style: none !important;
        }

        .lista_item > div {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 16px !important;
        }

        .codigoDivisor {
            margin: 0px 8px;
            padding: 0px 8px;
            border-left: 2px solid #444444;
            border-right: 2px solid #444444;
        }
	</style>

	<h1 class="h5 text-info px-4">
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
		<div class="px-4 d-flex align-items-center">
			<a href="javascript:history.go(-1);" class="btn btn-danger mr-3">
				<span><i class="fas fa-arrow-left"></i></span>
				<span>Volver</span>
			</a>

			<h1 class="h5 mb-0"><i class="fas fa-prescription"></i> Concurso del <span class="text-success">{{ $fechaInicio }} al {{ $fechaLimite }}</span></h1>
		</div>

		{{-- Lista --}}
		<div class="mt-4 px-4">
			{{-- Titulo --}}
			<div>
				<h2 class="h2">Lista de cajeros</h2>
			</div>

			{{-- Tabla --}}
			<div class="table-responsive mt-4">
				<table id="tablaCajeros" class="table table-bordered table-hover table-striped">
					<thead class="thead-dark">
						<tr>
							<th>Nro</th>
							<th>Cajero</th>
							<th>Unidades (Total)</th>
							<th>Artículos</th>
							<th>Facturas (Total)</th>
							<th>Monto total facturas Bs. (Sin IVA)</th>
						</tr>
					</thead>
					<tbody>
						@php
							$conteoFilas = 0;
						@endphp
						@foreach ($datos_concurso as $cajero => $informacion)
							@php
								$conteoFilas++;
								$sumasTotales = sumarCantidadesMonto($informacion['articulos']);
							@endphp
							<tr>
								<th>{{ $conteoFilas }}</th>
								<td class="font-weight-bold text-uppercase">
									<form action="{{ route('reporte53.detalle') }}" method="POST">
										@csrf
										<input type="hidden" name="cajero" value="{{ $cajero }}">
										<input type="hidden" name="inicio" value="{{ $fechaInicio }}">
										<input type="hidden" name="limite" value="{{ $fechaLimite }}">
										<input type="hidden" name="codigos" value="{{ $codigos }}">
										<input type="hidden" name="sede" value="{{ $sede }}">
										<button class="btn btn-light text-primary font-weight-bold cajeroLink">{!! utf8_encode($informacion['nombre']) !!} ({{ $cajero }})</button>
									</form>
								</td>
								<td class="font-weight-bold">{{ $sumasTotales['cantidad'] }}</td>
								<td class="font-weight-bold">{{ count($informacion['articulos']) }}</td>
								<td class="font-weight-bold">{{ count($informacion['lista_facturas']) }}</td>
								<td>{{ helper_formatoPrecio($sumasTotales['monto']) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

		{{-- No encontrado --}}
		<div class="mt-4 px-4">
			 {{-- Titulo --}}
			 <div class="mb-4 mt-4 border-bottom border-top border-secondary pt-2 pb-2">
				<h4 class="h4 px-2">Artículos no encontrados <span class="text-danger"><i class="fas fa-times"></i></span></h4>
			</div>
	
			{{-- Lista --}}
			<ul class="lista exitosa px-4">
				@foreach($codigosNotFound as $codigo)
					<li class="lista_item">
						<div>
							{{-- Titulo --}}
							<div class="font-weight-bold">
								<span>Código</span>
							</div>
	
							{{-- Codigo --}}
							<div class="font-weight-bold codigoDivisor">
								<span>{{ $codigo }}</span>
							</div>
	
							{{-- Icono --}}
							<div class="text-danger">
								<span><i class="fas fa-times"></i></span>
							</div>
						</div>
					</li>
				@endforeach
			</ul>
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

		function helper_formatoPrecio($numero)
		{
			return number_format($numero, 2, '.', ',');
		}
    ?>
@endsection

