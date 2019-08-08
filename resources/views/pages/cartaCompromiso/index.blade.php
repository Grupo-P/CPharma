@extends('layouts.model')

@section('title')
    Carta de compromiso
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection

@section('content')
	<!-- Modal Guardar -->
	@if(session('Saved'))
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
		        <h4 class="h6">Compromiso almacenado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif
	
	<!-- Modal Editar -->
	@if(session('Updated'))
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
		        <h4 class="h6">Compromiso modificado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Eliminar -->
	@if(session('Deleted'))
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
		        <h4 class="h6">Compromiso actualizado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="far fa-address-card"></i>
		Carta de compromiso
	</h1>
	
	<hr class="row align-items-start col-12">
	<table style="width:100%;">
	    <tr>
	        <td style="width:10%;" align="center">	
				<form action="/cartaCompromiso/create" style="display:inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"><i class="fa fa-plus"></i>&nbsp;Agregar</button>
				</form>
	        </td>
	        
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
				</div>
	        </td>
	    </tr>
	</table>

	<br/>
	
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">#</th>
		      	<th scope="col">Proveedor</th>
		      	<th scope="col">Art&iacute;culo</th>
		      	<th scope="col">Lote</th>
		      	<th scope="col">Fecha de vencimiento (Art&iacute;culo)</th>
		      	<th scope="col">Fecha tope (Carta compromiso)</th>
		      	<th scope="col">Estatus</th>
		      	<th scope="col">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($cartaCompromiso as $cartaC)
		    <tr>
		      <th>{{$cartaC->id}}</th>
		      <td>{{$cartaC->proveedor}}</td>
		      <td>{{$cartaC->articulo}}</td>
		      <td>{{$cartaC->lote}}</td>
		      <td>{{date('d-m-Y',strtotime($cartaC->fecha_vencimiento))}}</td>
		      <td>{{date('d-m-Y',strtotime($cartaC->fecha_tope))}}</td>
		      <td>
		      	<?php
					if($cartaC->estatus == 'ACTIVO') {
						echo 'ABIERTO';
					}
					else {
						echo 'CERRADO';
					}
				?>
		      </td>
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">
				
				<?php
				if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
				?>

					<?php
					if($cartaC->estatus == 'ACTIVO'){
					?>
						<a href="/cartaCompromiso/{{$cartaC->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
			      		</a>

			      		<a href="/cartaCompromiso/{{$cartaC->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>			      		
				      	</a>
				 					  
				      	<form action="/cartaCompromiso/{{$cartaC->id}}" method="POST" style="display:inline;">
						    @method('DELETE')
						    @csrf					    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
						</form>
					<?php
					}
					else if($cartaC->estatus == 'INACTIVO'){
					?>		
			      	<form action="/cartaCompromiso/{{$cartaC->id}}" method="POST" style="display:inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar"><i class="fa fa-share"></i></button>
					</form>
					<?php
					}					
					?>
				<?php	
				} else if(Auth::user()->role == 'SUPERVISOR' || Auth::user()->role == 'ADMINISTRADOR'){
				?>
					<a href="/cartaCompromiso/{{$cartaC->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>			      		
		      		</a>

		      		<a href="/cartaCompromiso/{{$cartaC->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
		      			<i class="fas fa-edit"></i>
	      			</a>
				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/cartaCompromiso/{{$cartaC->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
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
		});
		$('#exampleModalCenter').modal('show')
	</script>

@endsection