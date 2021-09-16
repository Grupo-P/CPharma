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

    @php $InicioCarga = new DateTime("now"); @endphp

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
							<option {{ (request('accion') == $accion) ? 'selected' : '' }} value="<?php echo $accion['accion']; ?>"><?php echo strtoupper($accion['accion']); ?></option>
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
							<option {{ (request('tabla') == $tabla) ? 'selected' : '' }} value="<?php echo $tabla['tabla']; ?>"><?php echo strtoupper($tabla['tabla']); ?></option>
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
							<option {{ (request('registro') == $registro) ? 'selected' : '' }} value="<?php echo $registro['registro']; ?>"><?php echo strtoupper($registro['registro']); ?></option>
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
							<option {{ (request('user') == $user['user']) ? 'selected' : '' }} value="<?php echo $user['user']; ?>"><?php echo strtoupper($user['user']); ?></option>
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
							<option {{ (request('departamento') == $departamento->nombre) ? 'selected' : '' }} value="<?php echo $departamento->nombre; ?>"><?php echo strtoupper($departamento->nombre); ?></option>
						<?php
						}
						?>
					</select>
				</td>	

				<th class="text-center">Fecha Desde</th>
				<td>
					<input type="date" name="fechadesde" value="{{ request('fechadesde') }}" class="form-control">
				</td>

				<th class="text-center">Fecha Hasta</th>
				<td>
					<input type="date" name="fechahasta" value="{{ request('fechahasta') }}" class="form-control">
				</td>	
				
				<td></td>
				<td>
                    <input class="btn btn-sm btn-outline-success" type="submit" name="buscar" value="Buscar">
                    <input class="btn btn-sm btn-outline-success" type="submit" name="detalle" value="Ver detallado">
                </td>
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

    @if(request('detalle'))
        @if(request('departamento') != '' && request('fechadesde') != '' && request('fechahasta') != '')
            @php
                include app_path() . '/functions/functions.php';

                $departamento = request('departamento');
                $fechadesde = request('fechadesde');
                $fechahasta = request('fechahasta');

                $modulos = DB::select("
                    SELECT
                        IF(auditorias.tabla != 'REPORTE', auditorias.tabla, auditorias.registro) AS modulo,
                        IF(auditorias.tabla = 'REPORTE', 'Reporte', 'Modulo') AS tipo
                    FROM
                        auditorias
                    WHERE
                        auditorias.user IN (SELECT users.name FROM users WHERE users.departamento = '$departamento') AND
                        DATE(auditorias.created_at) BETWEEN '$fechadesde' AND '$fechahasta'
                    GROUP BY modulo;
                ");

                $fechaInicio = strtotime(request('fechadesde'));
                $fechaFin = strtotime(request('fechahasta'));
            @endphp

            <table class="table table-striped table-bordered col-12 sortable" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="CP-sticky"></th>

                        @foreach($modulos as $modulo)
                            <th nowrap scope="col" class="CP-sticky">{{ strtoupper($modulo->modulo) }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @for($i = $fechaInicio; $i <= $fechaFin; $i += 86400)
                        @php
                            $fecha = date('Y-m-d', $i);
                        @endphp

                        <tr>
                            <td nowrap class="CP-sticky-H text-center">{{ $fecha }}</td>

                            @foreach($modulos as $modulo)
                                <td nowrap class="text-center">
                                    @php
                                        if ($modulo->tipo == 'Reporte') {
                                            $usuarios = DB::select("
                                                SELECT
                                                    auditorias.user
                                                FROM
                                                    auditorias
                                                WHERE
                                                    auditorias.registro = '$modulo->modulo' AND
                                                    DATE(auditorias.created_at) = '$fecha' AND
                                                    user IN (SELECT name FROM users WHERE departamento = '$departamento')
                                                GROUP BY auditorias.user
                                            ");

                                            $texto = '';

                                            foreach ($usuarios as $usuario) {
                                                $texto .= $usuario->user . '<br>';
                                            }

                                            echo $texto;
                                        }

                                        if ($modulo->tipo == 'Modulo') {
                                            $usuarios = DB::select("
                                                SELECT
                                                    auditorias.user
                                                FROM
                                                    auditorias
                                                WHERE
                                                    auditorias.tabla = '$modulo->modulo' AND
                                                    DATE(auditorias.created_at) = '$fecha' AND
                                                    user IN (SELECT name FROM users WHERE departamento = '$departamento')
                                                GROUP BY auditorias.user
                                            ");

                                            $texto = '';

                                            foreach ($usuarios as $usuario) {
                                                $texto .= $usuario->user . '<br>';
                                            }

                                            echo $texto;
                                        }
                                    @endphp
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        @else
            <div class="text-center text-danger mb-5 mt-5 text-uppercase">
                <label><strong>Debe seleccionar un departamento y un rango de fecha para poder ver esta información</strong></label>
            </div>
        @endif
    @else
    	@if(count($auditorias))
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
        				<th class="text-center">{{strtoupper($auditoria->id)}}</th>
        				<td class="text-center">{{strtoupper($auditoria->accion)}}</td>
        				<td class="text-center">{{strtoupper($auditoria->tabla)}}</td>
        				<td class="text-center">{{strtoupper($auditoria->registro)}}</td>
        				<td class="text-center">{{strtoupper($auditoria->user)}}</td>
        				<td class="text-center">{{strtoupper($auditoria->updated_at)}}</td>
    				</tr>
    			@endforeach
    			</tbody>
    		</table>
    	@else
    		<div class="text-center text-danger mb-5 mt-5 text-uppercase">
    			<label><strong>No existen registros para la combinacion de filtros, por favor utilice una diferente</strong></label>
    		</div>
        @endif
    @endif

    @php
        $FinCarga = new DateTime("now");
        $IntervalCarga = $InicioCarga->diff($FinCarga);
        echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    @endphp

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>

@endsection
