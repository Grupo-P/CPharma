@extends('layouts.model')

@section('title')
    Auditoria
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
		        <h4 class="h6">Auditoria almacenada con exito</h4>
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
		        <h4 class="h6">Auditoria modificada con exito</h4>
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
		        <h4 class="h6">Auditoria actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-search"></i>
		Auditoria
	</h1>

	<hr class="row align-items-start col-12">

	{!! Form::open(['route' => 'auditoria.store', 'method' => 'POST']) !!}
    <fieldset>
		<table style="width:100%;" class="CP-stickyBar">
			<tr>
				<th class="text-center" style="width:10%;">Accion</th>
					<td scope="col" style="width:15%;">
						<select name="accion" class="form-control">							
						<option value="TODOS">TODOS</option>
						<?php
						foreach($acciones as $accion){											
						?>
							<option value="<?php echo $accion['accion']; ?>"><?php echo strtoupper($accion['accion']); ?></option>
						<?php
						}
						?>
					</select>
				</td>

				<th class="text-center" style="width:10%;">Reporte/Modulo</th>	
				<td scope="col" style="width:15%;">
					<select name="tabla" class="form-control">
						<option value="TODOS">TODOS</option>
						<?php
						foreach($tablas as $tabla){															
						?>
							<option value="<?php echo $tabla['tabla']; ?>"><?php echo strtoupper($tabla['tabla']); ?></option>
						<?php
						}
						?>
					</select>
				</td>

				<th class="text-center"style="width:10%;">Registro</th>
				<td scope="col" style="width:15%;">
					<select name="registro" class="form-control">
						<option value="TODOS">TODOS</option>
						<?php
						foreach($registros as $registro){
							if(!intval($registro['registro'])&&!floatval($registro['registro'])){										
						?>
							<option value="<?php echo $registro['registro']; ?>"><?php echo strtoupper($registro['registro']); ?></option>
						<?php
							}
						}
						?>
					</select>
				</td>

				<th class="text-center" style="width:10%;">Usuario</th>
				<td scope="col" style="width:15%;">
					<select name="user" class="form-control">
						<option value="TODOS">TODOS</option>
						<?php
						foreach($users as $user){											
						?>
							<option value="<?php echo $user['user']; ?>"><?php echo strtoupper($user['user']); ?></option>
						<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th class="text-center">Departamento</th>
				<td>
					<select name="departamento" class="form-control">
						<option value="TODOS">TODOS</option>
						<?php
						foreach($departamentos as $departamento){
						?>
							<option value="<?php echo $departamento['nombre']; ?>"><?php echo strtoupper($departamento['nombre']); ?></option>
						<?php
						}
						?>
					</select>
				</td>	

				<th class="text-center">Fecha Desde</th>
				<td>
					<input type="date" name="fechadesde" class="form-control">
				</td>

				<th class="text-center">Fecha Hasta</th>
				<td>
					<input type="date" name="fechahasta" class="form-control">
				</td>	
				
				<td></td>
				<td><input class="btn btn-sm btn-outline-success" type="submit" name="buscar" value="Buscar"></td>
			</tr>
		</table>
	</fieldset>
    {!! Form::close()!!} 
	
	<hr class="row align-items-start col-12">
	
	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
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
	
	<?php
		if(count($auditorias)>0){			
	?>	
		<table class="table table-striped table-borderless col-12 sortable" id="myTable">
			<thead class="thead-dark">
				<tr>
					<th scope="col" class="CP-sticky">#</th>
					<th scope="col" class="CP-sticky">Accion</th>
					<th scope="col" class="CP-sticky">Reporte/Modulo</th>	
					<th scope="col" class="CP-sticky">Registro</th>
					<th scope="col" class="CP-sticky">Usuario</th>
					<th scope="col" class="CP-sticky">Fecha Actualizacion</th>
				</tr>
			</thead>
			<tbody>
			@foreach($auditorias as $auditoria)
				<tr>
				<th>{{strtoupper($auditoria->id)}}</th>
				<td>{{strtoupper($auditoria->accion)}}</td>
				<td>{{strtoupper($auditoria->tabla)}}</td>
				<td>{{strtoupper($auditoria->registro)}}</td>
				<td>{{strtoupper($auditoria->user)}}</td>
				<td>{{strtoupper($auditoria->updated_at)}}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	<?php 
		}else{
	?>
		<div class="text-center text-danger mb-5 mt-5 text-uppercase">
			<label><strong>No existen registros para la conbinacion de filtros, por favor utilice una diferente</strong></label>
		</div>
	<?php
		}
	?>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>

@endsection