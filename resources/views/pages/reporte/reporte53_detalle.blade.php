@extends('layouts.model')

@section('title')
  Reporte
@endsection

@php
	$unidades = 0;
	$articulos = count($cajeroInfo['articulos']);
	$facturas = count($cajeroInfo['lista_facturas'] );
	$montos = 0;
	$codigosTotal = count($codigos);

	foreach ($cajeroInfo['articulos'] as $index => $articulo) {
		$unidades += intVal($articulo['cantidad']);
		$montos += floatVal($articulo['monto']);
	}

	$montos = helper_formatoPrecio($montos);
@endphp

@section('scriptsHead')
	<link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.css" rel="stylesheet">
 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/r-2.5.0/datatables.min.js"></script>
@endsection

@section('scriptsFoot')
	<script>
		$(document).ready(function() {
			var unidades = "<?= $unidades ?>";
			var montos = "<?= $montos ?>";
			var articulos = "<?= $articulos ?>";
			var facturas = "<?= $facturas ?>";

			var tablaCajeros = $('#tablaCajeros').DataTable({
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
                            columns: [ 0, 1, 2, 3 ]
                        },
                        className : 'flat-button ico-excel mr-1'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv" data-toggle="tooltip" data-placement="right" title="CSV"></i>',
                        title: 'Reporte Concurso',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3 ]
                        },
                        className : 'flat-button ico-csv mr-1'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print" data-toggle="tooltip" data-placement="right" title="Imprimir"></i>',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3 ]
                        },
                        className : 'flat-button ico-imprimir mr-1'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf" data-toggle="tooltip" data-placement="right" title="PDF"></i>',
                        title: 'Reporte Concurso',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3 ]
                        },
                        className : 'flat-button ico-pdf mr-1'
                    },
                ],
			});

			const rowData = ['Totales', '', unidades, montos];
			tablaCajeros.row.add(rowData).draw().nodes().to$().addClass('filaTotales');

			//Tooltip
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
		});
	</script>
@endsection

@section('content')
	<style>
		tbody .filaTotales {
			background-color: #dc3545 !important;
			color: #fff !important;
			text-transform: uppercase;
			font-weight: bold;
		}

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
				<h3 class="h3 text-uppercase font-weight-bold">Cajero: {!! utf8_encode($cajeroInfo['nombre']) !!} ({{ $cajero }})</h3>
			</div>

			{{-- Informacion --}}
			<div> 
				<div style="font-size: 18px;">
					<span class="font-weight-bold">Total artículos:</span>
					<span>{{ count($cajeroInfo['articulos'] )}}</span>
				</div>
				<div style="font-size: 18px;">
					<span class="font-weight-bold">Total facturas:</span>
					<span>{{ count($cajeroInfo['lista_facturas'] )}}</span>
				</div>
			</div>

			{{-- Tabla --}}
			<div class="table-responsive mt-4 d-flex">
				{{-- Conteo --}}
				<div class="d-flex flex-column justify-content-between">
					@php
					 	$conteoFilas = 0;
					@endphp
					<div style="width: 12px;height: 144px;">
						
					</div>
					@foreach($cajeroInfo['articulos'] as $articulo)
						@php
							$conteoFilas++;
						@endphp
						<div class="text-center font-weight-bold p-2"><span>{{ $conteoFilas }}</span></div>
					@endforeach
					<div style="width: 12px;height: 42px;">
						
					</div>
				</div>
				
				<table id="tablaCajeros" class="table table-bordered table-hover table-striped">
					<thead class="thead-dark">
						<tr>
							<th>Código interno</th>
							<th>Artículo</th>
							<th>Unidades (Total)</th>
							<th>Venta total de Artículos Bs. (Sin IVA)</th>
						</tr>
					</thead>
					<tbody>
						@php
							$listaArticulos  = $cajeroInfo['articulos'];
						@endphp
						@foreach ($listaArticulos as $index => $articulo)
							<tr>
								<td><span class="d-block text-center">{{ $articulo['codigo'] }}</span></td>
								<td class="text-uppercase">{!! utf8_encode($articulo['nombre']) !!}</td>
								<td class="font-weight-bold">{{ $articulo['cantidad'] }}</td>
								<td>{{ helper_formatoPrecio($articulo['monto']) }}</td>
							</tr>
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

		function helper_formatoPrecio($numero)
		{
			return number_format($numero, 2, '.', ',');
		}

    ?>
@endsection

