@extends('layouts.default')

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
		display: inline;
		padding: 0.2cm;	
	}
	thead{
		border: 1px solid black;
		border-radius: 0px;
	}
	tbody{
		border: 1px solid black;
		border-radius: 0px;
	}
	td{
		width:4cm;
	}
	tr{
		height: 0.9cm;
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
	 	
		<table>
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
						Arena para Gatos asdasdasdadas adfsdf 
					</td>
				</tr>
				<tr>
					<td class="izquierda titulo">
						PMVP Bs.
					</td>
					<td class="derecha precio">
						82.500,00
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
	
	
		<table>
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
						Arena para Gatos Cat 
					</td>
				</tr>
				<tr>
					<td class="izquierda titulo">
						PMVP Bs.
					</td>
					<td class="derecha precio">
						82.500,00
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

		<table>
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
						Arena para Gatos Cat 
					</td>
				</tr>
				<tr>
					<td class="izquierda titulo">
						PMVP Bs.
					</td>
					<td class="derecha precio">
						82.500,00
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

