@extends('layouts.model')

@section('title')
    Etiqueta
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
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
		        <h4 class="h6">Etiqueta almacenada con exito</h4>
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
		        <h4 class="h6">Etiqueta modificada con exito</h4>
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
		        <h4 class="h6">Etiqueta actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-tag"></i>
		Etiqueta
	</h1>
	
	<?php
		use compras\Etiqueta;
		$etiqueta = new Etiqueta();
	?>

	<hr class="row align-items-start col-12">
	<table style="width:100%;">
		<tr>
      <td style="width:100%;">
        	<div class="input-group md-form form-sm form-1 pl-0">
			  <div class="input-group-prepend">
			    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
			        aria-hidden="true"></i></span>
			  </div>
			  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
				</div>
      </td>
    </tr>
  </table>
  <br/>
  <table class="table table-striped table-borderless col-12 sortable">
  	<thead class="thead-dark">
	    <tr>
	      	<th scope="col" colspan="5" style="text-align: center;">CLASIFICACION</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>
	  	<td style="width:20%;" align="center">
	  		<?php
	  			$etiqueta->id = 0;
	  		?>
				<form action="/etiqueta/{{$etiqueta->id}}" method="POST" style="display: inline;">
			    @method('PUT')
			    @csrf					    
			    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-dark btn-sm">Pendientes</button>
				</form>
	    </td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$etiqueta->id = 1;
	  		?>
				<form action="/etiqueta/{{$etiqueta->id}}" method="POST" style="display: inline;">
			    @method('PUT')
			    @csrf					    
			    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm">No Etiquetables</button>
					</form>	
			</td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$etiqueta->id = 2;
	  		?>
				<form action="/etiqueta/{{$etiqueta->id}}" method="POST" style="display: inline;">
			    @method('PUT')
			    @csrf					    
			    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-info btn-sm">Etiquetables</button>
				</form>						
	    </td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$etiqueta->id = 3;
	  		?>
				<form action="/etiqueta/{{$etiqueta->id}}" method="POST" style="display: inline;">
			    @method('PUT')
			    @csrf					    
			    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-success btn-sm">Obligatorias Etiquetar</button>
				</form>			
	    </td>

	    <td style="width:20%;" align="center">	
				<a href="{{ url('/etiqueta/create') }}" role="button" class="btn btn-outline-warning btn-sm" 
				style="display: inline;">
					Validar Etiquetas	      		
				</a>
	    </td>
	    </tr>
		</tbody>
	</table>
	<table class="table table-striped table-borderless col-12 sortable">
  	<thead class="thead-dark">
	    <tr>
	      	<th scope="col" colspan="6" style="text-align: center;">GENERAR ETIQUETAS</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>
	        <td style="width:16%;" align="center">	
				<a href="{{ url('/EtqObliatoria') }}" role="button" class="btn btn-outline-success btn-sm" 
				style="display: inline;" target="_blank">
					Obliagtorias (Todo)		      		
				</a>
	        </td>

	        <td style="width:16%;" align="center">	
				<a href="{{ url('/EtqObliatoria') }}" role="button" class="btn btn-outline-success btn-sm" 
				style="display: inline;" target="_blank">
					Obliagtorias ($)		      		
				</a>
	        </td>

	        <td style="width:16%;" align="center">	
				<a href="{{ url('/EtqObliatoria') }}" role="button" class="btn btn-outline-success btn-sm" 
				style="display: inline;" target="_blank">
					Obliagtorias (NO $)		      		
				</a>
	        </td>

	        <td style="width:16%;" align="center">	
				<a href="{{ url('/EtqEtiquetable') }}" role="button" class="btn btn-outline-info btn-sm" 
				style="display: inline;" target="_blank">
					Etiquetables (Todo)	      		
				</a>
	        </td>

	        <td style="width:16%;" align="center">	
				<a href="{{ url('/EtqEtiquetable') }}" role="button" class="btn btn-outline-info btn-sm" 
				style="display: inline;" target="_blank">
					Etiquetables ($)	      		
				</a>
	        </td>

	        <td style="width:16%;" align="center">	
				<a href="{{ url('/EtqEtiquetable') }}" role="button" class="btn btn-outline-info btn-sm" 
				style="display: inline;" target="_blank">
					Etiquetables (NO $)	      		
				</a>
	        </td>
			</tr>
		</tbody>
	</table>
	<br/>
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">#</th>
		      	<th scope="col">Codigo</th>
		      	<th scope="col">Descripcion </th>
		      	<th scope="col">Condicion</th>
		      	<th scope="col">Clasificacion</th>		      	
		      	<th scope="col">Estatus</th>
		      	<th scope="col">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($etiquetas as $etiqueta)
		    <tr>
		      <th>{{$etiqueta->id}}</th>
		      <td>{{$etiqueta->codigo_articulo}}</td>
		      <td>{{$etiqueta->descripcion}}</td>
		      <td>{{$etiqueta->condicion}}</td>
		      <td>{{$etiqueta->clasificacion}}</td>
		      <td>{{$etiqueta->estatus}}</td>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">
				
				<?php
				if(Auth::user()->role == 'MASTER'
					|| Auth::user()->role == 'DEVELOPER'
					|| Auth::user()->role == 'SUPERVISOR'
					|| Auth::user()->role == 'USUARIO'){
				?>

					<?php
					if($etiqueta->estatus == 'ACTIVO'){
					?>
						<a href="/etiqueta/{{$etiqueta->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Obligatorio Etiquetar">
			      			<i class="fas fa-check-double"></i>			      		
			      		</a>

			      		<a href="/etiqueta/{{$etiqueta->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Etiquetable">
			      			<i class="fas fa-check"></i>			      		
				      	</a>
				 					  
				      	<form action="/etiqueta/{{$etiqueta->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf					    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="No Etiquetable"><i class="fas fa-ban"></i></button>
						</form>
					<?php
					}
					else if($etiqueta->estatus == 'INACTIVO'){
					?>		
			      	<form action="/etiqueta/{{$etiqueta->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar"><i class="fa fa-share"></i></button>
					</form>
					<?php
					}					
					?>
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