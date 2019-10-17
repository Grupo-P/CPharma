@extends('layouts.etiquetas')

@section('title')
    Traslado
@endsection

@section('content')
<style>
	table{
		border-collapse: collapse;
		text-align: center;
	}
	thead{
		border: 1px solid black;
		border-radius: 0px;
		background-color: #e3e3e3;
	}
	tbody{
		border: 1px solid black;
		border-radius: 0px;
	}
	td{
		border: 1px solid black;
		border-radius: 0px;
	}
	th{
		border: 1px solid black;
		border-radius: 0px;
	}
	.alinear-der{
		text-align: right;
		padding-right: 20px;
	}
	.alinear-izq{
		text-align: left;
		padding-left: 20px;
	}
	.aumento{
		font-size: 1.2em;
		text-transform: uppercase;
	}
</style>

 	<h1 class="h5 text-info" style="display: inline;">
		<i class="fas fa-people-carry"></i>
		Traslado
	</h1>
	<form action="/traslado/" method="POST" style="display: inline; margin-left: 50px;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>
	<input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">

	<hr class="row align-items-start col-12">
	<table>
		<thead>
		    <tr>
		    		<th scope="row" colspan="4">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
  						</span>
		    		</th>
		    		<th scope="row" colspan="8" class="aumento">Soporte de Traslado interno</th>
		    </tr>
  	</thead>
	  	<tbody>
		    <tr>
	      	<td colspan="4" class="alinear-der"># de Ajuste:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->numero_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha de Ajuste:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->fecha_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha de Traslado:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->fecha_traslado}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Sede Emisora:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->sede_emisora}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Sede Destino:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->sede_destino}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Operador emisor del ajuste:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->operador_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Operador emisor del traslado:</td>
	      	<td colspan="8" class="alinear-izq">{{$traslado->operador_traslado}}</td>
		    </tr>
		    <thead>
		    <tr>
		    		<th scope="row">Codigo Interno</th>
		    		<th scope="row">Codigo de Barra</th>
		    		<th scope="row">Descripcion</th>
		    		<th scope="row">Gravado?</th>
		    		<th scope="row">Dolarizado?</th>
		    		<th scope="row">Costo Unit Bs. S/IVA</th>
		    		<th scope="row">Costo Unit $. S/IVA</th>
		    		<th scope="row">Cantidad</th>
		    		<th scope="row">Total Impuesto Bs.</th>
		    		<th scope="row">Total Impuesto $.</th>
		    		<th scope="row">Total Bs.</th>
		    		<th scope="row">Total $.</th>
		    </tr>
		    </thead>


	  	</tbody>
	</table>
@endsection

<?php
	

?>