@extends('layouts.etiquetas')

@section('title')
    Orden de compra
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
		<i class="fas fa-print"></i>
		Soporte de orden de compra
	</h1>
	<form action="/ordenCompra/" method="POST" style="display: inline; margin-left: 50px;">  
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
		    		<th scope="row" colspan="9" class="aumento">Soporte de orden de compra</th>
		    </tr>
  	</thead>
	  	<tbody>
		    <tr>
	      	<td colspan="4" class="alinear-der">Codigo:</td>
	      	<td colspan="9" class="alinear-izq">{{$OrdenCompra->codigo}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha de orden:</td>
	      	<td colspan="9" class="alinear-izq">{{$OrdenCompra->created_at}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Destino:</td>
	      	<td colspan="9" class="alinear-izq">{{$OrdenCompra->sede_origen}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Proveedor:</td>
	      	<td colspan="9" class="alinear-izq">{{$OrdenCompra->proveedor}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Operador:</td>
	      	<td colspan="9" class="alinear-izq">{{$OrdenCompra->user}}</td>
		    </tr>
	  	</tbody>
	</table>
	<br/>
	<span>Nota: La informacion aqui contenida es propiedad exclusiva de {{$OrdenCompra->sede_origen}}
	<br/>
	Para cualquier aclaratoria llamar al XXXX-XXXXXXX o escribir al correo XXXXXXX@XXXXXX.com. 
	<br/>
	Para cualquier informacion de pagos contactar al XXXX-XXXXXX</span>
@endsection