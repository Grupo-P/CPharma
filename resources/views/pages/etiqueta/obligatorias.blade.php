@extends('layouts.modelUser')

@section('title')
    Etiqueta
@endsection

@section('content')

	<h1 class="h5 text-info">
		<i class="fas fa-tag"></i>
		Etiquetables
	</h1>
	<hr class="row align-items-start col-12">

<?php	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\querys.php');
	include(app_path().'\functions\funciones.php');
	include(app_path().'\functions\reportes.php');
?>

<style>
	table{
		border-radius: 0px;
		height: 6.5cm;
		width: 5cm;
		display: inline;
	}
	thead{
		border: 1px solid black;
	}
	tbody{
		border: 1px solid black;
	}
	.centrado{
		text-align: center;
		text-transform: uppercase;
	}
	.derecha{
		text-align: right;
		text-transform: uppercase;
	}
	.izquierda{
		text-align: left;
		text-transform: uppercase;
	}
	.descripcion{
		padding: 2% 3%;
	}
	.titulo{
		padding: 1.5% 3%;
	}
	.precio{
		padding: 1.5% 5% 1.5% 1.5%;
	}
</style>
	  	
	<table class="etiqueta">
		<thead>
			<tr>
				<td class="centrado" colspan="2">
					Codigo Interno
				</td>
			</tr>	
		</thead>
		<tbody>
			<tr rowspan="2">
				<td class="centrado descripcion" colspan="2">
					Arena para Gatos Cat Clean x 4 kg Imagroca
				</td>
			</tr>
			<tr>
				<td class="izquierda titulo">
					PMVP Bs.
				</td>
				<td class="derecha precio">
					77878782.500,00
				</td>
			</tr>
			<tr>
				<td class="izquierda titulo">
					IVA 16% Bs.
				</td>
				<td class="derecha precio">
					13.200,00
				</td>
			</tr>
			<tr>
				<td class="izquierda titulo">
					<strong>Total a Pagar Bs.</strong>
				</td>
				<td class="derecha precio">
					<strong>13.200,00</strong>
				</td>
			</tr>
			<tr>
				<td class="izquierda dolarizado titulo">
					<strong>*</strong>
				</td>
				<td class="derecha precio">
					12/09/2019
				</td>
			</tr>
		</tbody>
	</table>	
		

				
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection

