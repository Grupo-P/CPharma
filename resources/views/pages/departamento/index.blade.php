@extends('layouts.model')

@section('title')
    Departamento
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
		        <h4 class="h6">Departamento almacenado con exito</h4>
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
		        <h4 class="h6">Departamento modificado con exito</h4>
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
		        <h4 class="h6">Departamento actualizado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fab fa-buffer"></i>
		Departamento
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
	        <td style="width:10%;" align="center">	        	
				<a href="{{ url('/departamento/create') }}" role="button" class="btn btn-outline-info btn-sm" 
				style="display: inline; text-align: left;">
				<i class="fas fa-plus"></i>
					Agregar		      		
				</a>
	        </td>
	        <td style="width:90%;">
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
		      	<th scope="col" class="CP-sticky">Nombre</th>
		      	<th scope="col" class="CP-sticky">Reportes</th>		      	
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($departamentos as $departamento)
		    <tr>
		      <th>{{$departamento->id}}</th>
		      <td>{{$departamento->nombre}}</td>
		      <td>{{reportesPorDepartamento($departamento->descripcion)}}</td>
		      <td>{{$departamento->estatus}}</td>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">
				
				<?php
				if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
				?>

					<?php
					if($departamento->estatus == 'ACTIVO'){
					?>
						<a href="/departamento/{{$departamento->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
			      		</a>

			      		<a href="/departamento/{{$departamento->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>			      		
				      	</a>
				 					  
				      	<form action="/departamento/{{$departamento->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf					    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
						</form>
					<?php
					}
					else if($departamento->estatus == 'INACTIVO'){
					?>		
			      	<form action="/departamento/{{$departamento->id}}" method="POST" style="display: inline;">
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
					<a href="/departamento/{{$departamento->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>			      		
		      		</a>

		      		<a href="/departamento/{{$departamento->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
		      			<i class="fas fa-edit"></i>
	      			</a>
				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/departamento/{{$departamento->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
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

<?php
	function reportesPorDepartamento($arrayNumerosReportes){
		include_once(app_path().'\functions\functions.php');
		$NumerosReportes = explode(",", $arrayNumerosReportes);
		$cont = 0;
		$flagTr = true;
		echo"
			<table>
				<tbody>
					<tr>
		";
		for ($i=0; $i<count($NumerosReportes); $i++){

			if($cont==5){
				$flagTr = false;
				echo"</tr>";
				$cont=0;
			}
			
			if($flagTr == true){
				echo"<td style='background-color:#FFF; border: 1px solid black;'>".FG_Nombre_Reporte($NumerosReportes[$i])."</td>";
			}
			else if($flagTr == false){
				$flagTr = true;
				echo"<tr>";
				echo"<td style='background-color:#FFF; border: 1px solid black;'>".FG_Nombre_Reporte($NumerosReportes[$i])."</td>";
			}
			
			$cont++;
		}

		if($flagTr == true){
			echo"</tr>";
		} 

		echo "
				<tbody>
			</table
		";
	}
?>