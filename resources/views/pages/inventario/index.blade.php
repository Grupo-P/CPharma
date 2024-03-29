<?php
	use compras\InventarioDetalle;
	$totalSKU = $totalUnidades = $totalResultado = $totalDiferencia = 0;
?>
@extends('layouts.model')

@section('title')
    Inventario
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
		        <h4 class="h6">Inventario almacenada con exito</h4>
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
		        <h4 class="h6">Inventario modificada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	@if (session('Contar'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Contar') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Debe cargar la informacion del conteo</h4>
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
		        <h4 class="h6">Inventario actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-boxes"></i>
		Inventario
	</h1>

	<hr class="row align-items-start col-12">

    <form autocomplete="off" action="">
        @php
            $cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
            $clasificacion = isset($_GET['clasificacion']) ? $_GET['clasificacion'] : '';
            $fechaInicioUrl = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
            $fechaFinUrl = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

            $selected50 = ($cantidad == '50') ? 'selected' : '';
            $selected100 = ($cantidad == '100') ? 'selected' : '';
            $selected200 = ($cantidad == '200') ? 'selected' : '';
            $selected500 = ($cantidad == '500') ? 'selected' : '';
            $selected1000 = ($cantidad == '1000') ? 'selected' : '';
            $selectedTodos = ($cantidad == 'Todos') ? 'selected' : '';
        @endphp

        <div class="row">
            <div class="col">Cantidad de registros</div>
            <div class="col">
                <select class="form-control form-control-sm" name="cantidad">
                    <option {{ $selected50 }} value="50">50</option>
                    <option {{ $selected100 }} value="100">100</option>
                    <option {{ $selected200 }} value="200">200</option>
                    <option {{ $selected500 }} value="500">500</option>
                    <option {{ $selected1000 }} value="1000">1000</option>
                    <option {{ $selectedTodos }} value="Todos">Todos</option>
                </select>
            </div>

            <div class="col">Clasificación</div>
            <div class="col">
                <select name="clasificacion" class="form-control form-control-sm">
                    <option {{ ($clasificacion == 'Todos') ? 'selected' : '' }} value="Todos">Todos</option>
                    <option {{ ($clasificacion == 'Generado') ? 'selected' : '' }} value="Generado">Generado</option>
                    <option {{ ($clasificacion == 'Revisado') ? 'selected' : '' }} value="Revisado">Revisado</option>
                    <option {{ ($clasificacion == 'Anulado') ? 'selected' : '' }} value="Anulado">Anulado</option>
                </select>
            </div>

            <div class="col">Fecha inicio</div>
            <div class="col"><input type="date" value="{{ $fechaInicioUrl }}" class="form-control form-control-sm" name="fechaInicio"></div>

            <div class="col">Fecha final</div>
            <div class="col"><input type="date" value="{{$fechaFinUrl }}" class="form-control form-control-sm" name="fechaFin"></div>

            <div class="col"><input type="submit" value="Buscar" class="btn btn-sm btn-block btn-outline-success"></div>
        </div>
    </form>

	<!--<table class="table table-striped table-borderless col-12 sortable">
  	<thead class="thead-dark">
	    <tr>
	      	<th scope="col" colspan="4" style="text-align: center;">CLASIFICACION</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>

  		<td style="width:11%;" align="center">
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-dark btn-sm" value="3">TODOS</button>
				</form>
	    </td>

	  	<td style="width:11%;" align="center">
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="0">GENERADO</button>
				</form>
	    </td>

	  	<td style="width:11%;" align="center">
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="1">REVISADO</button>
				</form>
	    </td>

	    <td style="width:11%;" align="center">
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-danger btn-sm" value="2">ANULADO</button>
					</form>
			</td>

	    </tr>
		</tbody>
	</table>
	<br>-->

    <hr>

	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
        <td style="width:8%;" align="center">
					<a href="{{ url('/inventarioProveedor') }}" role="button" class="btn btn-outline-success btn-sm"
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Por Proveedor
					</a>
        </td>
        <td style="width:8%;" align="center">
					<a href="{{ url('/inventarioDescripcion') }}" role="button" class="btn btn-outline-info btn-sm"
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Por Descripcion
					</a>
        </td>
        <td style="width:8%;" align="center">
					<a href="{{ url('/inventarioCodigoBarra') }}" role="button" class="btn btn-outline-dark btn-sm"
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Por Codigo de Barra
					</a>
        </td>
        <td style="width:8%;" align="center">
					<a href="{{ url('/inventarioMarca') }}" role="button" class="btn btn-outline-danger btn-sm"
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Por Marca
					</a>
        </td>
        <td style="width:6%;" align="center">
					<a href="{{ url('/inventarioAleatorio') }}" role="button" class="btn btn-outline-warning btn-sm"
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Aleatorio
					</a>
        </td>
	        <td style="width:27%;">
	        	<div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
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
		      	<th scope="col" class="CP-sticky">Codigo</th>
		      	<th scope="col" class="CP-sticky">Fecha</th>
		      	<th scope="col" class="CP-sticky">Hora</th>
		      	<th scope="col" class="CP-sticky">Origen</th>
		      	<th scope="col" class="CP-sticky">Motivo</th>
		      	<th scope="col" class="CP-sticky">Cantidad SKU</th>
		      	<th scope="col" class="CP-sticky">Unidades</th>
		      	<th scope="col" class="CP-sticky">Resultado</th>
		      	<th scope="col" class="CP-sticky">Diferencia Resultado</th>
		      	<th scope="col" class="CP-sticky">Operador</th>
		      	<th scope="col" class="CP-sticky">Estatus</th>
                <th scope="col" class="CP-sticky">Ajuste</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($inventarios as $inventario)
				<?php
					$fecha = new DateTime($inventario->fecha_generado);
					$fecha = $fecha->format('d-m-Y');

					$hora = new DateTime($inventario->fecha_generado);
					$hora = $hora->format('h:i A');
				?>
		    <tr>
		      <th>{{$inventario->id}}</th>
		      <td>{{$inventario->codigo}}</td>
		      <td>{{$fecha}}</td>
		      <td>{{$hora}}</td>
		      <td>{{$inventario->origen_conteo}}</td>
		      <td>{{$inventario->motivo_conteo}}</td>
		      <td>{{$inventario->cantidades_conteo}}</td>
		      <td>{{$inventario->unidades_conteo}}</td>

		      <?php
		            $totalUnidades += $inventario->unidades_conteo;
		            $totalSKU += $inventario->cantidades_conteo;

					if($inventario->estatus == "REVISADO"){

							$resultado = $difResultado = 0;

							$inventarioDetalles =
							InventarioDetalle::where('codigo_conteo',$inventario->codigo)->get();

							foreach ($inventarioDetalles as $inventarioDetalle) {

								if($inventarioDetalle->conteo != "" && $inventarioDetalle->re_conteo == ""){
									$resultado += $inventarioDetalle->conteo;
								}
								else if($inventarioDetalle->re_conteo != ""){
									$resultado += $inventarioDetalle->re_conteo;
								}

							}

						echo "<td>".$resultado."</td>";
						echo "<td>".($resultado - $inventario->unidades_conteo)."</td>";

						$totalResultado += $resultado;
						$totalDiferencia += ($resultado - $inventario->unidades_conteo);

					}else{
						echo "<td>-</td>";
						echo "<td>-</td>";
					}
					?>

		      <td>{{$inventario->operador_generado}}</td>
		      <td>{{$inventario->estatus}}</td>

              <td>
                    <div class="input-group">
                        <form target="_blank" method="POST" action="{{ route('inventario.update', $inventario->id) }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <input type="hidden" name="id" value="{{ $inventario->id }}">

                            <input type="text" class="form-control form-control-sm" name="numero_ajuste" value="{{ ($inventario->numero_ajuste) ? $inventario->numero_ajuste : '' }}">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-info btn-sm"><i class="fa fa-save"></i> Guardar ajuste</button>

                                <button type="submit" class="btn btn-outline-success btn-sm" name="verificar" value="1"><i class="fa fa-user-check"></i> Verificar ajuste</button>
                            </div>
                        </form>
                    </div>
              </td>

		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:200px;">

				<?php
				if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
				?>

					<?php
					if($inventario->estatus == 'GENERADO'){
					?>
						<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>
			      		</a>

			      		<a href="/inventario/{{$inventario->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>
				      	</a>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Revisar">
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Aprobar"><i class="fa fa-check"></i></button>
				 			</form>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Anular">
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-ban"></i></button>
							</form>
					<?php
					}
					else if($inventario->estatus == 'ANULADO' || $inventario->estatus == 'REVISADO'){
					?>
			      	<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>
		      		</a>
					</form>
					<?php
					}
					?>

				<?php
				} else if(Auth::user()->role == 'SUPERVISOR' || Auth::user()->role == 'ADMINISTRADOR' || Auth::user()->role == 'SUPERVISOR CAJA'){
				?>

					<?php
					if($inventario->estatus == 'GENERADO'){
					?>
						<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>
			      		</a>

			      		<a href="/inventario/{{$inventario->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>
				      	</a>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Revisar">
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Aprobar"><i class="fa fa-check"></i></button>
				 			</form>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Anular">
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-ban"></i></button>
							</form>
					<?php
					}
					else if($inventario->estatus == 'ANULADO' || $inventario->estatus == 'REVISADO'){
					?>
			      	<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>
		      		</a>
					</form>
					<?php
					}
					?>

				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>
		      		</a>
				<?php
				}
				?>

		      </td>
		    <!-- Fin Validacion de ROLES -->
		    </tr>
		@endforeach

		<tr>
		 <th colspan="6" class="text-right">Totales</th>
		 <th><?php echo($totalSKU) ?></th>
		 <th><?php echo($totalUnidades) ?></th>
		 <th><?php echo($totalResultado) ?></th>
		 <th><?php echo($totalDiferencia) ?></th>
		 <th colspan="3"></th>
		</tr>
		</tbody>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();
		});
		$('#exampleModalCenter').modal('show')
	</script>
@endsection
