@extends('layouts.model')

@section('title')
    Orden de compra
@endsection

<?php
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $pie_unidades = 0;
  $pie_dolares = 0;
  $pie_ve = 0;
?>


@section('scriptsHead')
    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['orden_compra', 'sede_destino', 'proveedor', 'fecha_orden', 'fecha_estimada_despacho', 'dias_transcurridos', 'monto_total_bs', 'monto_total_ds', 'unidades', 'condicion_crediticia', 'dias_credito', 'estatus', 'estado_actual', 'operador', 'monto_real', 'calificacion'];

        function mostrar_todas(that) {
            if (that.checked) {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).show();
                    $('[name='+campos[i]+']').prop('checked', true);
                }
            } else {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).hide();
                    $('[name='+campos[i]+']').prop('checked', false);
                }
            }
        }
    </script>
@endsection


@section('content')

    <div class="modal fade" id="ver_campos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Mostrar u ocultar columnas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'orden_compra')" name="orden_compra" checked>
                Orden compra
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'sede_destino')" name="sede_destino" checked>
                Sede destino
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'proveedor')" name="proveedor" checked>
                Proveedor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_orden')" name="fecha_orden" checked>
                Fecha de la orden
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_estimada_despacho')" name="fecha_estimada_despacho" checked>
                Fecha estimada de despacho
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'dias_transcurridos')" name="dias_transcurridos" checked>
                Días transcurridos
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_total_bs')" name="monto_total_bs" checked>
                Monto total Bs.
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_total_ds')" name="monto_total_ds" checked>
                Monto total $
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'unidades')" name="unidades" checked>
                Unidades
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'condicion_crediticia')" name="condicion_crediticia" checked>
                Condición crediticia
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'dias_credito')" name="dias_credito" checked>
                Días de crédito
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'estatus')" name="estatus" checked>
                Estatus
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'estado_actual')" name="estado_actual" checked>
                Estado actual
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'operador')" name="operador" checked>
                Operador
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_real')" name="monto_real" checked>
                Monto real
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'calificacion')" name="calificacion" checked>
                Calificación
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_todas(this)" name="Marcar todas" checked>
                Marcar todas
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

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
	<table style="width:100%;" class="CP-stickyBar">
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
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
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

  <table class="table table-bordered col-12 sortable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" colspan="2">LEYENDA DE COLORES SEGUN LOS DIAS EN TRANSITO</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td scope="col" class="bg-white text-dark">
          <ul>
            <li>
            	<span>Las columnas en color blanco o gris representan el rango entre 0 y 5 dias trascurridos</span>
          	</li>
          </ul>
          <ul class="bg-success text-white">
            <li>
            	<span>Las columnas en color verde representan el rango entre 6 y 10 dias trascurridos</span>
          	</li>
          </ul>
        </td>
        <td>
        	<ul class="bg-warning text-white">
            <li>
            	<span>Las columnas en color amarillo representan el rango entre 11 y 15 dias trascurridos</span>
          	</li>
          </ul>
          <ul class="bg-danger text-white">
            <li>
            	<span>Las columnas en color rojo representan el rango con mas de 15 dias trascurridos</span>
          	</li>
          </ul>
        </td>
      </tr>
    </tbody>
 	</table>

    <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

	<table class="table table-striped col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="orden_compra CP-sticky">Orden compra</th>
		      	<th scope="col" class="sede_destino CP-sticky">Sede Destino</th>
		      	<th scope="col" class="proveedor CP-sticky">Proveedor</th>
		      	<th scope="col" class="fecha_orden CP-sticky">Fecha de la orden</th>
		      	<th scope="col" class="fecha_estimada_despacho CP-sticky">Fecha estimada de despacho</th>
		      	<th scope="col" class="dias_transcurridos CP-sticky">Dias transcurridos</th>
		      	<th scope="col" class="monto_total_bs CP-sticky">Monto total Bs</th>
		      	<th scope="col" class="monto_total_ds CP-sticky">Monto total $</th>
		      	<th scope="col" class="unidades CP-sticky">Unidades</th>
		      	<th scope="col" class="condicion_crediticia CP-sticky">Condicion crediticia</th>
		      	<th scope="col" class="dias_credito CP-sticky">Dias de credito</th>
		      	<th scope="col" class="estatus CP-sticky">Estatus</th>
		      	<th scope="col" class="estado_actual CP-sticky">Estado Actual</th>
		      	<th scope="col" class="operador CP-sticky">Operador</th>
		      	<th scope="col" class="monto_real CP-sticky">Monto real</th>
		      	<!-- <th scope="col" class="CP-sticky">Fecha de aprobacion</th> -->
		      	<th scope="col" class="calificacion CP-sticky">Calificacion</th>
		      	<!-- <th scope="col" class="CP-sticky">Fecha de recepcion</th>
		      	<th scope="col" class="CP-sticky">Fecha de ingreso</th> -->
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

				$costo_interno = 0;
				$costo_interno_dolar = 0;
				$costo_interno_ve = 0;

				while($row = $result->fetch_assoc()) {
					$total_unidades += floatval($row['total_unidades']);
					$costo_total += floatval($row['costo_total']);
				}
				mysqli_close($connCPharma);

				$costo_interno = $costo_total;
				$unidades_internas = $total_unidades;

				$costo_total = number_format ($costo_total,2,"," ,"." );
				$total_unidades = number_format ($total_unidades,0,"," ,"." );

				if($ordenCompra->moneda==SigDolar){
					$costo_interno_dolar = $costo_interno;
					$costo_total_dolar = $costo_total;
					$costo_total_ve = '0,00';
				}
				else{
					$costo_interno_ve = $costo_interno;
					$costo_total_dolar = '0,00';
					$costo_total_ve = $costo_total;
				}

				$pie_unidades += $unidades_internas;
				$pie_dolares += $costo_interno_dolar;
				$pie_ve += $costo_interno_ve;

				if($ordenCompra->estado=='CERRADA'){
					$Dias = FG_Rango_Dias($ordenCompra->created_at,$ordenCompra->updated_at);
				}
				else{
					$Dias = FG_Rango_Dias($ordenCompra->created_at,date('Y-m-d H:i:s'));
				}

				$costo_total_real = number_format ($ordenCompra->montoTotalReal,2,"," ,"." );
			?>

			<?php
			if( ($ordenCompra->estado=='EN PROCESO')
				|| ($ordenCompra->estado=='POR APROBAR')
				|| ($ordenCompra->estado=='APROBADA')
			){
				if($Dias>=0 && $Dias<=5){
					echo'<tr style="border: 1px solid #fff">';
				}
				else if($Dias>=6 && $Dias<=10){
					echo'<tr class="bg-success text-white" style="border: 1px solid #fff">';
				}
				else if($Dias>=11 && $Dias<=15){
					echo'<tr class="bg-warning text-white" style="border: 1px solid #fff">';
				}
				else if($Dias>15){
					echo'<tr class="bg-danger text-white" style="border: 1px solid #fff">';
				}
			}
			else{
				echo'<tr style="border: 1px solid #fff">';
			}
			?>
	      <th>{{$ordenCompra->id}}</th>
	      <td class="orden_compra">{{$ordenCompra->codigo}}</td>
       	  <td class="sede_destino">{{$ordenCompra->sede_destino}}</td>
	      <td class="proveedor">{{$ordenCompra->proveedor}}</td>
	      <td class="fecha_orden">{{$ordenCompra->created_at}}</td>
	      <td class="fecha_estimada_despacho">{{$ordenCompra->fecha_estimada_despacho}}</td>
	      <td class="dias_transcurridos">{{$Dias}}</td>
	      <td class="monto_total_bs">{{$costo_total_ve}}</td>
	      <td class="monto_total_ds">{{$costo_total_dolar}}</td>
	      <td class="unidades">{{$total_unidades}}</td>
	      <td class="condicion_crediticia">{{$ordenCompra->condicion_crediticia}}</td>
	      <td class="dias_credito">{{$ordenCompra->dias_credito}}</td>
	      <td class="estatus">{{$ordenCompra->estado}}</td>
	      <td class="estado_actual">{{$ordenCompra->estatus}}</td>
	      <td class="operador">{{$ordenCompra->user}}</td>
	      <td class="monto_real">{{$costo_total_real}}</td>
	      <!-- <td>{{$ordenCompra->fecha_aprobacion}}</td> -->
	      <td class="calificacion">{{$ordenCompra->calificacion}}</td>
	      <!-- <td>{{$ordenCompra->fecha_recepcion}}</td>
	      <td>{{$ordenCompra->fecha_ingreso}}</td> -->

    <!-- Inicio Validacion -->
			<td style="width:300px;" class="bg-white">

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


			<!-- INICIO Acciones para el departamento de COMPRAS -->
				<?php
				if( ($ordenCompra->estado=='POR APROBAR')
						&&
						( (Auth::user()->departamento == 'COMPRAS')
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
						( (Auth::user()->departamento == 'COMPRAS')
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
			<!--  FIN Acciones para el departamento de COMPRAS -->

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

		<tr>
		 <th colspan="7" class="text-right">Totales</th>
		 <th><?php echo(number_format ($pie_ve,2,"," ,"." )) ?></th>
		 <th><?php echo(number_format($pie_dolares,2,"," ,"." )) ?></th>
		 <th><?php echo(number_format ($pie_unidades,0,"," ,"." )) ?></th>
		 <th colspan="11"></th>
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
