@extends('layouts.model')

@section('title')
    Orden de compra
@endsection

<?php
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
?>

@section('content')

	<!-- Modal Guardar -->
	@if (session('Saved'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i> Orden de compra: {{ session('Saved') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Orden de compra generada con exito, codigo: {{ session('Saved') }}</h4>
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
		        <h4 class="h6">Orden de compra actualizada con exito</h4>
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
		        <h4 class="h6">Orden de compra actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal OrdenActiva -->
	@if (session('OrdenActiva'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-info text-danger"></i> Orden de compra</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Ya usted posee una orden de compra activa, codigo: {{ session('OrdenActiva') }}</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal OrdenActiva -->
  @if (session('NoOrdenActiva'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-info text-danger"></i> Orden de compra</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Usted NO posee una orden de compra activa</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

	<h1 class="h5 text-info">
		<i class="far fa-file-alt"></i>
		Orden de compra
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;">
	    <tr>
	    	<?php
	    		if((Auth::user()->departamento == 'TECNOLOGIA')
			 			|| (Auth::user()->departamento == 'GERENCIA')
			 			|| (Auth::user()->departamento == 'COMPRAS')){
	    	?>
        <td style="width:10%;" align="center">	        	
					<a href="{{ url('/ordenCompra/create') }}" role="button" class="btn btn-outline-info btn-sm" 
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Agregar		      		
					</a>
        </td>
        <?php 
      	}
      	?>
	        <td style="width:90%;">
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
	      	<th scope="col" colspan="9" style="text-align: center;">CLASIFICACION</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>
	  	<td style="width:11%;" align="center">
	  		<?php
	  			$tipo = 8;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="8">TODO</button>
				</form>
	    </td>

	  	<td style="width:11%;" align="center">
	  		<?php
	  			$tipo = 0;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="0">EN PROCESO</button>
				</form>
	    </td>

	  	<td style="width:11%;" align="center">
	  		<?php
	  			$tipo = 1;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="1">POR APROBAR</button>
				</form>
	    </td>

	    <td style="width:11%;" align="center">
	      	<?php
	  			$tipo = 2;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-danger btn-sm" value="2">ANULADA</button>
					</form>	
			</td>

	    <td style="width:11%;" align="center">
	      	<?php
	  			$tipo = 3;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-danger btn-sm" value="3">RECHAZADA</button>
				</form>						
	    </td>

	    <td style="width:11%;" align="center">
	      	<?php
	  			$tipo = 7;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="7">POR RECIBIR</button>
				</form>						
	    </td>

	    <td style="width:11%;" align="center">
	      	<?php
	  			$tipo = 4;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="4">RECIBIDA</button>
				</form>						
	    </td>

	    <td style="width:11%;" align="center">
	      	<?php
	  			$tipo = 5;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="5">INGRESADA</button>
				</form>						
	    </td>

	    <td style="width:11%;" align="center">
	      	<?php
	  			$tipo = 6;
	  		?>
				<form action="/ordenCompra?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-dark btn-sm" value="6">CERRADA</button>
				</form>						
	    </td>

	    </tr>
		</tbody>
	</table>
	<br/>

	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Orden compra</th>
		      	<th scope="col" class="CP-sticky">Sede Destino</th>
		      	<th scope="col" class="CP-sticky">Proveedor</th>
		      	<th scope="col" class="CP-sticky">Fecha de la orden</th>
		      	<th scope="col" class="CP-sticky">Fecha estimada de despacho</th>
		      	<th scope="col" class="CP-sticky">Dias transcurridos</th>
		      	<th scope="col" class="CP-sticky">Monto total Bs</th>
		      	<th scope="col" class="CP-sticky">Monto total $</th>
		      	<th scope="col" class="CP-sticky">Unidades</th>
		      	<th scope="col" class="CP-sticky">Condicion crediticia</th>
		      	<th scope="col" class="CP-sticky">Dias de credito</th>
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Estado Actual</th>
		      	<th scope="col" class="CP-sticky">Operador</th>
		      	<th scope="col" class="CP-sticky">Monto real</th>
		      	<th scope="col" class="CP-sticky">Fecha de aprobacion</th>
		      	<th scope="col" class="CP-sticky">Calificacion</th>
		      	<th scope="col" class="CP-sticky">Fecha de recepcion</th>
		      	<th scope="col" class="CP-sticky">Fecha de ingreso</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($OrdenCompra as $ordenCompra)
			<?php 
				$connCPharma = FG_Conectar_CPharma();
				$sql = MySQL_Buscar_Orden_Detalle($ordenCompra->codigo);
				$result = mysqli_query($connCPharma,$sql);

				$total_unidades = 0;
				$costo_total = 0;

				while($row = $result->fetch_assoc()) {
					$total_unidades += floatval($row['total_unidades']);
					$costo_total += floatval($row['costo_total']);
				}
				mysqli_close($connCPharma);

				$costo_total = number_format ($costo_total,2,"," ,"." );

				if($ordenCompra->moneda==SigDolar){
					$costo_total_dolar = $costo_total;
					$costo_total_ve = '0,00';
				}
				else{
					$costo_total_dolar = '0,00';
					$costo_total_ve = $costo_total;
				}

				if($ordenCompra->estado=='CERRADA'){
					$Dias = FG_Rango_Dias($ordenCompra->created_at,$ordenCompra->updated_at);
				}
				else{
					$Dias = FG_Rango_Dias($ordenCompra->created_at,date('Y-m-d H:i:s'));
				}

				$costo_total_real = number_format ($ordenCompra->montoTotalReal,2,"," ,"." );
			?>

		    <tr>
		      <th>{{$ordenCompra->id}}</th>
		      <td>{{$ordenCompra->codigo}}</td>
		       <td>{{$ordenCompra->sede_destino}}</td>
		      <td>{{$ordenCompra->proveedor}}</td>
		      <td>{{$ordenCompra->created_at}}</td>
		      <td>{{$ordenCompra->fecha_estimada_despacho}}</td>
		      <td>{{$Dias}}</td>
		      <td>{{$costo_total_ve}}</td>
		      <td>{{$costo_total_dolar}}</td>
		      <td>{{$total_unidades}}</td>
		      <td>{{$ordenCompra->condicion_crediticia}}</td>
		      <td>{{$ordenCompra->dias_credito}}</td>
		      <td>{{$ordenCompra->estado}}</td>
		      <td>{{$ordenCompra->estatus}}</td>
		      <td>{{$ordenCompra->user}}</td>
		      <td>{{$costo_total_real}}</td>
		      <td>{{$ordenCompra->fecha_aprobacion}}</td>
		      <td>{{$ordenCompra->calificacion}}</td>
		      <td>{{$ordenCompra->fecha_recepcion}}</td>
		      <td>{{$ordenCompra->fecha_ingreso}}</td>

    <!-- Inicio Validacion -->
			<td style="width:300px;">

	    <!--  INICIO Boton generico para impresion de orden de compra -->
				<a href="/ordenCompra/{{$ordenCompra->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir Orden" style="display: inline-block; width: 100%">
    			<i class="fas fa-print"></i>			      		
    		</a>

    		<form action="/DigitalOrdenCompra" method="GET">
				    @csrf					
				    <input type="hidden" name="id" value="{{$ordenCompra->id}}"> 
				    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Consultar" style="display: inline-block; width: 100%"><i class="fa fa-search"></i></button>
					</form>
			<!--  FIN Boton generico para impresion de orden de compra -->

			<!-- INICIO Acciones para el departamento de compra -->
				<?php
				if( ($ordenCompra->estado=='EN PROCESO')
						&&
						( ($ordenCompra->user==Auth::user()->name)
					 		|| (Auth::user()->departamento == 'TECNOLOGIA')
					 		|| (Auth::user()->departamento == 'GERENCIA')
				 		)
					){
				?>

				<?php
					if($ordenCompra->estatus == 'ACTIVO'){
				?>  
	      	<a href="/ordenCompra/{{$ordenCompra->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar Orden" style="display: inline-block; width: 100%">
    				<i class="fas fa-edit"></i>			      		
      		</a>

	      	<form action="/ordenCompraDetalle" method="GET">
				    @csrf					    
				    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar Detalle" style="display: inline-block; width: 100%"><i class="fa fa-edit"></i></button>
					</form>
				 
	      	<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
			    @method('DELETE')
			    @csrf					    
			    	<button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Pausar" style="display: inline-block; width: 100%"><i class="fa fa-pause"></i></button>
					</form>

					<form action="/AnularOrdenCompra" method="PRE">
				    <input type="hidden" name="anular" value="solicitud">
				    <input type="hidden" name="id" value="{{$ordenCompra->id}}">   
				    <button type="submit"role="button" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Anular" style="display: inline-block; width: 100%"><i class="fa fa-ban"></i></button>
					</form>

					<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
			    @method('DELETE')
			    @csrf					    
			    	<button type="submit" name="PorAprobar" value="solicitud" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Por Aprobar" style="display: inline-block; width: 100%"><i class="fas fa-angle-double-right"></i></button>
					</form>

					<?php
					}
					else if($ordenCompra->estatus == 'EN ESPERA'){
					?>  
		      	<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
				    @method('DELETE')
				    @csrf					    
				    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Activar" style="display: inline-block; width: 100%"><i class="fa fa-play"></i></button>
						</form>
				<?php
					}
				?>
				<?php
					}
				?>	
			<!--  FIN Acciones para el departamento de compra -->


			<!-- INICIO Acciones para el departamento de administracion -->
				<?php
				if( ($ordenCompra->estado=='POR APROBAR')
						&&
						( (Auth::user()->departamento == 'ADMINISTRACION')
					 		|| (Auth::user()->departamento == 'TECNOLOGIA')
					 		|| (Auth::user()->departamento == 'GERENCIA')
				 		)
					){
				?>

				<?php
					if($ordenCompra->estatus == 'EN ESPERA'){
				?>  
					<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
			    @method('DELETE')
			    @csrf					    
			    	<button type="submit" name="Aprobar" value="solicitud" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Aprobar" style="display: inline-block; width: 100%"><i class="fas fa-check"></i></button>
					</form>

					<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
			    @method('DELETE')
			    @csrf					    
			    	<button type="submit" name="Devolver" value="solicitud" role="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Devolver" style="display: inline-block; width: 100%"><i class="fas fa-angle-double-left"></i></button>
					</form>

					<form action="/RechazarOrdenCompra" method="PRE">
				    <input type="hidden" name="rechazar" value="solicitud">
				    <input type="hidden" name="id" value="{{$ordenCompra->id}}">   
				    <button type="submit"role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Rechazar" style="display: inline-block; width: 100%"><i class="fa fa-ban"></i></button>
					</form>
					<?php
						}
					?>  
				<?php
					}
				?>

				<?php
				if( ($ordenCompra->estado=='INGRESADA')
						&&
						( (Auth::user()->departamento == 'ADMINISTRACION')
					 		|| (Auth::user()->departamento == 'TECNOLOGIA')
					 		|| (Auth::user()->departamento == 'GERENCIA')
				 		)
					){
				?>

				<?php
					if($ordenCompra->estatus == 'EN ESPERA'){
				?>  
					<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
			    @method('DELETE')
			    @csrf					    
			    	<button type="submit" name="Cerrar" value="solicitud" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Cerrar" style="display: inline-block; width: 100%"><i class="fas fa-check"></i></button>
					</form>
					<?php
						}
					?>  
				<?php
					}
				?>
			<!--  FIN Acciones para el departamento de administracion -->

			<!-- INICIO Acciones para el departamento de recepcion -->
				<?php
				if( ($ordenCompra->estado=='APROBADA')
						&&
						( (Auth::user()->departamento == 'RECEPCION')
					 		|| (Auth::user()->departamento == 'TECNOLOGIA')
					 		|| (Auth::user()->departamento == 'GERENCIA')
				 		)
					){
				?>

				<?php
					if($ordenCompra->estatus == 'EN ESPERA'){
				?>  
					<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST">
			    @method('DELETE')
			    @csrf					    
			    	<button type="submit" name="Recibir" value="solicitud" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Recibir" style="display: inline-block; width: 100%"><i class="fas fa-check"></i></button>
					</form>

					<form action="/RechazarOrdenCompra" method="PRE">
				    <input type="hidden" name="rechazar" value="solicitud">
				    <input type="hidden" name="id" value="{{$ordenCompra->id}}">   
				    <button type="submit"role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Rechazar" style="display: inline-block; width: 100%"><i class="fa fa-ban"></i></button>
					</form>
					<?php
						}
					?>  
				<?php
					}
				?>
			<!--  FIN Acciones para el departamento de recepcion -->

			<!-- INICIO Acciones para el departamento de recepcion -->
				<?php
				if( ($ordenCompra->estado=='RECIBIDA')
						&&
						( (Auth::user()->departamento == 'OPERACIONES')
					 		|| (Auth::user()->departamento == 'TECNOLOGIA')
					 		|| (Auth::user()->departamento == 'GERENCIA')
				 		)
					){
				?>

				<?php
					if($ordenCompra->estatus == 'EN ESPERA'){
				?>  
					<form action="/IngresarOrdenCompra" method="PRE">
				    <input type="hidden" name="Ingresar" value="solicitud">
				    <input type="hidden" name="id" value="{{$ordenCompra->id}}">   
				    <button type="submit"role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Ingresar" style="display: inline-block; width: 100%"><i class="fas fa-check"></i></button>
					</form>
					<?php
						}
					?>  
				<?php
					}
				?>
			<!--  FIN Acciones para el departamento de recepcion -->
   		</td>
    <!-- Fin Validacion -->
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