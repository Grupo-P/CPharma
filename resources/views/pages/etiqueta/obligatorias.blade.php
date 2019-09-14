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
		width: 4cm;
	}
	.titulo{
		background-color: green;
		height: 1cm;
		font-size: 1em;
	}
	.descripcion{
		background-color: blue;
		height: 1cm;
		font-size: 0.8em;
	}
	.rowDer{
		background-color: red;
		height: 1cm;
	}
	.rowIzq{
		background-color: pink;
		height: 1cm;
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
</style>
	 	
		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
						Codigo Interno
					</td>
				</tr>	
			</thead>
			<tbody>
				<tr rowspan="2">
					<td class="centrado descripcion" colspan="2">
						Arena para  
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>

<br><br>

		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
						12/09/2019
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<thead>
				<tr>
					<td class="centrado titulo" colspan="2">
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
					<td class="izquierda rowDer">
						PMVP Bs.
					</td>
					<td class="derecha rowIzq">
						82.500,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						IVA 16% Bs.
					</td>
					<td class="derecha rowIzq">
						123.113.200,00
					</td>
				</tr>
				<tr>
					<td class="izquierda rowDer">
						<strong>Total a Pagar Bs.</strong>
					</td>
					<td class="derecha rowIzq">
						<strong>13.200,00</strong>
					</td>
				</tr>
				<tr>
					<td class="izquierda dolarizado rowDer">
						<strong>*</strong>
					</td>
					<td class="derecha rowIzq">
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

