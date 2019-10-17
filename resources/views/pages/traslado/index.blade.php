@extends('layouts.model')

@section('title')
    Traslado
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
		        <h4 class="h6">Traslado almacenado con exito</h4>
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
		        <h4 class="h6">Traslado modificado con exito</h4>
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
		        <h4 class="h6">Traslado actualizado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-people-carry"></i>
		Traslado
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;">
	    <tr>
	        <td style="width:10%;" align="center">        	
				<a href="{{ url('/SearchAjuste') }}" role="button" class="btn btn-outline-info btn-sm" 
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
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>
	
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="stickyCP">#</th>
		      	<th scope="col" class="stickyCP">Ajuste</th>
		      	<th scope="col" class="stickyCP">Sede Emisora</th>	
		      	<th scope="col" class="stickyCP">Sede Destino</th>
		      	<th scope="col" class="stickyCP">Fecha</th>	
		      	<th scope="col" class="stickyCP">Estatus</th>
		      	<th scope="col" class="stickyCP">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($traslados as $traslado)
		    <tr>
		    	<th>{{$traslado->id}}</th>
		      <th>{{$traslado->numero_ajuste}}</th>
		      <td>{{$traslado->sede_emisora}}</td>
		      <td>{{$traslado->sede_destino}}</td>
		      <td>{{$traslado->fecha_traslado}}</td>
		      <td>{{$traslado->estatus}}</td>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">
					<?php
					if(($traslado->estatus=='PROCESADO'||$traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'OPERACIONES' 
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/traslado/{{$traslado->id}}" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Soporte Traslado" style="width: auto">
	      			<i class="fas fa-print"></i>		      		
	      		</a>
					<?php
					}
					?>						
		     
					<?php
					if(($traslado->estatus=='PROCESADO'||$traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ALMACEN'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/traslado/{{$traslado->id}}/edit" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Embalar" style="width: auto">
	      			<i class="fas fa-box-open"></i>      		
	      		</a>
					<?php
					}
					?>						

		      <?php
					if(($traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ALMACEN'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/GuiaEnvio?Ajuste={{$traslado->numero_ajuste}}" role="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Guia de envio y etiquetas" style="width: auto">
	      			<i class="fas fa-tag"></i>     		
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