@extends('layouts.model')

@section('title')
  Detalle de orden de compra
@endsection

@section('content')

	<!-- Modal Guardar -->
	@if (session('Saved'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Saved') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Articulo agregado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Editar -->
	@if (session('Updated'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Updated') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Articulo modificado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Eliminar -->
	@if (session('Deleted'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Deleted') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Articulo actualizado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

<div class="modal" tabindex="-1" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar a orden</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

	<h1 class="h5 text-info">
		<i class="far fa-file-alt"></i>
		Detalle de orden de compra
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;" class="CP-stickyBar">
		<tr>
				<td style="width:7%;">
					<form action="/ordenCompra/" method="POST">
			        <button type="submit" role="button" class="btn btn-outline-success btn-sm"data-placement="top" style="display: inline;"><i class="fa fa-reply">&nbsp;Regresar</i></button>
			    </form>
				</td>
        <td style="width:7%;">
						<form action="{{ url('/ordenCompraDetalle/create') }}" class="agregarOrdenCompra" method="PRE">
			        <button type="submit" role="button" class="btn btn-outline-info btn-sm"data-placement="top" name="Reporte" value="NO" style="display: inline;"><i class="fas fa-plus">&nbsp;Agregar</i></button>
			        <input type="hidden" name="Reporte" value="NO">
				    </form>
	        </td>
	        <td style="width:86%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>

	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Codigo Orden</th>
		      	<th scope="col" class="CP-sticky">Codigo Interno</th>
		      	<th scope="col" class="CP-sticky">Codigo Barra</th>
		      	<th scope="col" class="CP-sticky">Descripcion</th>
		      	<th scope="col" class="CP-sticky">Cantidad FTN</th>
		      	<th scope="col" class="CP-sticky">Cantidad FLL</th>
		      	<th scope="col" class="CP-sticky">Cantidad FAU</th>
		      	<th scope="col" class="CP-sticky">Cantidad FM</th>
		      	<th scope="col" class="CP-sticky">Cantidad FEC</th>
                <th scope="col" class="CP-sticky">Cantidad PAG</th>
                <th scope="col" class="CP-sticky">Cantidad FLF</th>
                <th scope="col" class="CP-sticky">Cantidad CDD</th>
		      	<th scope="col" class="CP-sticky">Total Unidades</th>
		      	<th scope="col" class="CP-sticky">Costo Unitario</th>
		      	<th scope="col" class="CP-sticky">Costo Total</th>
		      	<th scope="col" class="CP-sticky">Existencia (Origen)</th>
		      	<th scope="col" class="CP-sticky">Dias Restantes (Origen)</th>
		      	<th scope="col" class="CP-sticky">Reporte (Origen)</th>
		      	<th scope="col" class="CP-sticky">Rango (Origen)</th>
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($ordenCompraDetalles as $ordenCompraDetalle)
		    <tr>
		      <th>{{$ordenCompraDetalle->id}}</th>
		      <td>{{$ordenCompraDetalle->codigo_orden}}</td>
		      <td>{{$ordenCompraDetalle->codigo_articulo}}</td>
		      <td>{{$ordenCompraDetalle->codigo_barra}}</td>
		      <td>{{$ordenCompraDetalle->descripcion}}</td>
		      <td>{{$ordenCompraDetalle->sede1}}</td>
		      <td>{{$ordenCompraDetalle->sede2}}</td>
		      <td>{{$ordenCompraDetalle->sede3}}</td>
		      <td>{{$ordenCompraDetalle->sede4}}</td>
		      <td>{{$ordenCompraDetalle->sede5}}</td>
              <td>{{$ordenCompraDetalle->sede6}}</td>
              <td>{{$ordenCompraDetalle->sede7}}</td>
              <td>{{$ordenCompraDetalle->sede8}}</td>
		      <td>{{$ordenCompraDetalle->total_unidades}}</td>
		      <td>{{number_format($ordenCompraDetalle->costo_unitario,2,"," ,"." )}}</td>
		      <td>{{number_format($ordenCompraDetalle->costo_total,2,"," ,"." )}}</td>
		      <td>{{$ordenCompraDetalle->existencia_rpt}}</td>
		      <td>{{$ordenCompraDetalle->dias_restantes_rpt}}</td>
		      <td>{{$ordenCompraDetalle->origen_rpt}}</td>
		      <td>{{$ordenCompraDetalle->rango_rpt}}</td>
		      <td>{{$ordenCompraDetalle->estatus}}</td>

		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">

				<?php
				if(Auth::user()->role == 'MASTER'
					|| Auth::user()->role == 'DEVELOPER'
					|| Auth::user()->departamento == 'COMPRAS'){
				?>

					<?php
					if($ordenCompraDetalle->estatus == 'ACTIVO'){
					?>
						<a href="/ordenCompraDetalle/0?id_articulo={{$ordenCompraDetalle->id_articulo}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
	      			<i class="far fa-eye"></i>
		      		</a>

	      		<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
	      			<i class="fas fa-edit"></i>
		      	</a>

		      	<form action="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" method="POST" style="display: inline;">
				    @method('DELETE')
				    @csrf
				    	<button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
						</form>
					<?php
					}
					else if($ordenCompraDetalle->estatus == 'INACTIVO'){
					?>
		      	<form action="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar"><i class="fa fa-share"></i></button>
					</form>
					<?php
					}
					?>
				<?php
				} else if(Auth::user()->role == 'SUPERVISOR' || Auth::user()->role == 'ADMINISTRADOR' || Auth::user()->role == 'SUPERVISOR CAJA'){
				?>
					<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>
		      		</a>

		      		<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
		      			<i class="fas fa-edit"></i>
	      			</a>
				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>
		      		</a>
				<?php
				}
				?>

		      </td>
		    <!-- Fin Validacion de ROLES -->

		    </tr>
		@endforeach
		</tbody>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();

		    $('.agregarOrdenCompra').on('submit', function (event) {
          event.preventDefault();

          data = $(this).serialize();
          url = $(this).attr('action');

          $.ajax({
            url: url,
            data: data,
            success: function (response) {
              if (response == 'ordenNoActiva') {
                alert('Usted NO posee una orden de compra activa');
                window.open('/ordenCompra/create', '_blank');
                return false;
              }

              $('#myModal').find('.modal-body').html(response);
              $('#myModal').modal('show');
            },
            error: function (error) {
              $('body').html(error.responseText);
            }
          });
        });
		});

		$('#exampleModalCenter').modal('show');
	</script>

@endsection
