@extends('layouts.model')

@section('title')
    Traslado
@endsection

<?php
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $totalUnidades = 0;

  $abiertas = 0;
  $tres = 0;
  $cuatro = 0;
  $cinco = 0;
  $siete = 0;

  $embaladosAbiertas = 0;
  $embaladosTres = 0;
  $embaladosCuatro = 0;
  $embaladosCinco = 0;
  $embaladosSiete = 0;

  $promedioEmbalados = 0;
  $promedioEntregados15 = 0;
  $promedioProcesados = 0;

  $sql = "
    SELECT
        SUM(DATEDIFF(DATE(NOW()), traslados.fecha_traslado)) / COUNT(1) AS promedio_embalados
    FROM
        traslados
    WHERE
        traslados.estatus = 'EMBALADO'
    ORDER BY
        traslados.id DESC;
  ";

  $sql = DB::select($sql);

  $promedioEmbalados = $sql[0]->promedio_embalados;

  $sql = "
    SELECT
        SUM(DATEDIFF(DATE(NOW()), traslados.fecha_traslado)) / COUNT(1) AS promedioEntregados15
    FROM
        traslados
    WHERE
        traslados.estatus = 'ENTREGADO'
        AND fecha_traslado >= DATE_SUB(DATE(NOW()), INTERVAL 15 DAY)
    ORDER BY
        traslados.id DESC;
  ";

  $sql = DB::select($sql);

  $promedioEntregados15 = $sql[0]->promedioEntregados15;

  $sql = "
    SELECT
        FORMAT(SUM(DATEDIFF(DATE(NOW()), traslados.fecha_traslado)) / COUNT(1), 2) AS promedioProcesados
    FROM
        traslados
    WHERE
        traslados.estatus = 'PROCESADO';
  ";

  $sql = DB::select($sql);

  $promedioProcesados = $sql[0]->promedioProcesados;

  $sql = "
    SELECT
        *
    FROM
        traslados
    WHERE
        estatus = 'ENTREGADO CON RECLAMO'
        AND fecha_traslado >= DATE_SUB(DATE(NOW()), INTERVAL 30 DAY)
  ";

  $sql = DB::select($sql);

  $entregadosReclamos30 = count($sql);
?>


@section('scriptsFoot')
    <script>
        $(document).ready(function () {
            $('[name=cantidad]').on('change', function () {
                $('[name=cantidad]').parent().submit();
            });
        });
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

	<br/>

	<table style="width:100%;" class="CP-stickyBar">
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
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
				</div>
	        </td>
	    </tr>
	</table>

    <br/>

    <hr class="row align-items-start col-12">

    @if(request()->Tipo != 0 && request()->Tipo != 1)
        <div class="d-flex justify-content-center col-md-12 text-center form-inline">
            <form action="">
                Cantidad de registros a mostrar

                <select name="cantidad" class="ml-5 form-control">
                    <option {{ request()->cantidad == 50 ? 'selected' : '' }} value="50">50</option>
                    <option {{ request()->cantidad == 100 ? 'selected' : '' }} value="100">100</option>
                    <option {{ request()->cantidad == 200 ? 'selected' : '' }} value="200">200</option>
                    <option {{ request()->cantidad == 500 ? 'selected' : '' }} value="500">500</option>
                    <option {{ request()->cantidad == 1000 ? 'selected' : '' }} value="1000">1000</option>
                    <option {{ request()->cantidad == 'Todos' ? 'selected' : '' }} value="Todos">Todos</option>
                </select>

                <input type="hidden" name="Tipo" value="{{ request()->Tipo ?? 3 }}">
            </form>
        </div>
    @endif

	<br/>

	<table class="table table-striped table-borderless col-12">
  	<thead class="thead-dark">
        <tr>
            <th scope="col" colspan="5" style="text-align: center;">
                @switch ($Tipo)
                    @case(0)
                        TRASLADOS PROCESADOS
                    @break

                    @case(1)
                        TRASLADOS EMBALADOS
                    @break

                    @case(2)
                        TRASLADOS ENTREGADOS
                    @break

                    @default
                        TODOS LOS TRASLADOS
                @endswitch
            </th>
        </tr>
	    <tr>
	      	<th scope="col" colspan="5" style="text-align: center;">CLASIFICACION</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>
	  	<td style="width:20%;" align="center">
	  		<?php
	  			$tipo = 3;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="3">TODO</button>
				</form>
	    </td>

	  	<td style="width:20%;" align="center">
	  		<?php
	  			$tipo = 0;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-dark btn-sm" value="0">PROCESADO</button>
				</form>
	    </td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$tipo = 1;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-danger btn-sm" value="1">EMBALADO</button>
					</form>	
			</td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$tipo = 2;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="2">ENTREGADO</button>
				</form>						
	    </td>
	    </tr>
		</tbody>
	</table>

    @if(isset($_GET['Tipo']) && $_GET['Tipo'] == 0)
        <table class="table table-striped table-borderless col-12 sortable">
            <tbody>
            <tr>
                <td align="center">
                    <a href="?Tipo=0" class="btn btn-outline-info btn-sm">Órdenes abiertas: <span class="abiertas">{{$abiertas}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=0&dias=3" class="btn btn-outline-secondary btn-sm">Órdenes con menos de 3 días: <span class="tres">{{$tres}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=0&dias=4" class="btn btn-outline-success btn-sm">Órdenes con más de 3 días: <span class="cuatro">{{$cuatro}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=0&dias=5" class="btn btn-outline-warning btn-sm">Órdenes con más de 5 días: <span class="cinco">{{$cinco}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=0&dias=7" class="btn btn-outline-danger btn-sm">Órdenes con más de 7 días: <span class="siete">{{$siete}}</span> </a>
                </td>
            </tr>
            </tbody>
        </table>
    @endif

    @if(isset($_GET['Tipo']) && $_GET['Tipo'] == 1)
        <table class="table table-striped table-borderless col-12 sortable">
            <tbody>
            <tr>
                <td align="center">
                    <a href="?Tipo=1" class="btn btn-outline-info btn-sm">Órdenes abiertas: <span class="abiertas">{{$embaladosAbiertas}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=1&dias=3" class="btn btn-outline-secondary btn-sm">Órdenes con menos de 3 días: <span class="tres">{{$embaladosTres}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=1&dias=4" class="btn btn-outline-success btn-sm">Órdenes con más de 3 días: <span class="cuatro">{{$embaladosCuatro}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=1&dias=5" class="btn btn-outline-warning btn-sm">Órdenes con más de 5 días: <span class="cinco">{{$embaladosCinco}}</span> </a>
                </td>

                <td align="center">
                    <a href="?Tipo=1&dias=7" class="btn btn-outline-danger btn-sm">Órdenes con más de 7 días: <span class="siete">{{$embaladosSiete}}</span> </a>
                </td>
            </tr>
            </tbody>
        </table>
    @endif

    <br>

    <table class="table table-bordered col-12">
        <thead class="thead-dark">
          <tr>
            <th scope="col" colspan="3">LEYENDA DE COLORES SEGUN LOS DIAS EN TRASLADO</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td scope="col" class="bg-white text-dark">
              <ul>
                <li>
                    <span>Las columnas en color blanco o gris representan el rango entre 0 y menor/igual a 3 dias trascurridos</span>
                </li>
              </ul>
              <ul class="bg-success text-white">
                <li>
                    <span>Las columnas en color verde representan el rango mayor a 3 y menor/igual a 5 dias trascurridos</span>
                </li>
              </ul>
            </td>
            <td colspan="2">
              <ul class="bg-warning text-white">
                <li>
                    <span>Las columnas en color amarillo representan el rango mayor a 5 y 7 menor/igual dias trascurridos</span>
                </li>
              </ul>
              <ul class="bg-danger text-white">
                <li>
                    <span>Las columnas en color rojo representan el rango con mas de 7 dias trascurridos</span>
                </li>
              </ul>
            </td>
          </tr>
          <tr>
            <td scope="col" class="bg-white text-dark">
                <ul class="text-danger text-center CP-Latir">
                    El promedio de días de todos los traslados embalados es de: {{ number_format($promedioEmbalados, 2) }}</br>
                    El promedio de días de los últimos 15 días de traslados entregados es de: {{ number_format($promedioEntregados15, 2) }}</br>
                    El promedio de días de todos los traslados procesados es de: {{ number_format($promedioProcesados, 2) }}</br>
                    Cantidad de traslados con reclamos en los últimos 30 días: {{ $entregadosReclamos30 }}
                </ul>
            </td>

            <td scope="col" class="bg-white text-dark">
                <ul class="text-info text-center">
                    Traslados procesados hoy: {{ $procesadosHoy }}</br>
                    Traslados embalados hoy: {{ $embaladosHoy }}</br>
                    Diferencial hoy: {{ $procesadosHoy-$embaladosHoy }}</br>
                    Entregados hoy: {{ $entregadosHoy }}
                </ul>
            </td>

            <td scope="col" class="bg-white text-dark">
                <ul class="text-info text-center">
                    Traslados procesados ayer: {{ $procesadosAyer }}</br>
                    Traslados embalados ayer: {{ $embaladosAyer }}</br>
                    Diferencial ayer: {{ $procesadosAyer-$embaladosAyer }}</br>
                    Entregados ayer: {{ $entregadosAyer }}
                </ul>
            </td>
          </tr>
        </tbody>
    </table>
	<br/>
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Ajuste</th>
		      	<th scope="col" class="CP-sticky">Fecha Ajuste</th>
		      	<th scope="col" class="CP-sticky">Fecha Traslado</th>	
		      	<th scope="col" class="CP-sticky">Sede Destino</th>
		      	<th scope="col" class="CP-sticky">Unidades</th>
		      	<th scope="col" class="CP-sticky">Bultos</th>
                <th scope="col" class="CP-sticky">SKU</th>
		      	<th scope="col" class="CP-sticky">Total Bs.S</th>
		      	<th scope="col" class="CP-sticky">Total $</th>
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Dias en traslado</th>
                <th scope="col" class="CP-sticky">Primer artículo</th>
                <th scope="col" class="CP-sticky">Último artículo</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($traslados as $traslado)

			<?php
                $fondo = '';
                $verde = 'btn-outline-success';
                $rojo = 'btn-outline-danger';
                $amarillo = 'btn-outline-warning';

                if($traslado->estatus=='ENTREGADO'){
                    $Dias = FG_Rango_Dias($traslado->fecha_traslado,date_format($traslado->updated_at, 'Y-m-d'));
                }
                else{
                    $Dias = FG_Rango_Dias($traslado->fecha_traslado,date('Y-m-d'));
                }

                if ($traslado->estatus == 'PROCESADO') {
                    $abiertas = $abiertas + 1;
                }

                if ($traslado->estatus == 'EMBALADO') {
                    $embaladosAbiertas = $embaladosAbiertas + 1;
                }

                if (($traslado->estatus == 'EMBALADO' || $traslado->estatus == 'PROCESADO') && $Dias <= 3) {
                    $tres = $tres + 1;
                    $embaladosTres = $embaladosTres + 1;

                    if (isset($_GET['dias']) && $_GET['dias'] != 3) {
                        continue;
                    }
                }

                if (($traslado->estatus == 'EMBALADO' || $traslado->estatus == 'PROCESADO') && ($Dias > 3 && $Dias <= 5)) {
                    $fondo = 'bg-success';
                    $verde = 'btn-outline-light';
                    $cuatro = $cuatro + 1;
                    $embaladosCuatro = $embaladosCuatro + 1;

                    if (isset($_GET['dias']) && $_GET['dias'] != 4) {
                        continue;
                    }
                }

                if (($traslado->estatus == 'EMBALADO' || $traslado->estatus == 'PROCESADO') && ($Dias > 5 && $Dias <= 7)) {
                    $fondo = 'bg-warning';
                    $amarillo = 'btn-outline-light';
                    $cinco = $cinco + 1;
                    $embaladosCinco = $embaladosCinco + 1;

                    if (isset($_GET['dias']) && $_GET['dias'] != 5) {
                        continue;
                    }
                }

                if (($traslado->estatus == 'EMBALADO' || $traslado->estatus == 'PROCESADO') && ($Dias > 7)) {
                    $fondo = 'bg-danger';
                    $rojo = 'btn-outline-light';
                    $siete = $siete + 1;
                    $embaladosSiete = $embaladosSiete + 1;

                    if (isset($_GET['dias']) && $_GET['dias'] != 7) {
                        continue;
                    }
                }

				$connCPharma = FG_Conectar_CPharma();
				$sql = MySQL_Buscar_Traslado_Detalle($traslado->numero_ajuste);
				$result = mysqli_query($connCPharma,$sql);

				$Total_Cantidad = 0;
				$Total_Impuesto_Bs = 0;
				$Total_Impuesto_Usd = 0;
				$Total_Bs = 0;
				$Total_Usd = 0;

				while($row = $result->fetch_assoc()) {
					$Total_Cantidad += floatval($row['cantidad']);
					$Total_Impuesto_Bs += floatval($row['total_imp_bs']);
					$Total_Impuesto_Usd += floatval($row['total_imp_usd']);
					$Total_Bs += floatval($row['total_bs']);
					$Total_Usd += floatval($row['total_usd']);
				}
				mysqli_close($connCPharma);

				$Total_Bs = number_format ($Total_Bs,2,"," ,"." );
				$Total_Usd = number_format ($Total_Usd,2,"," ,"." );

                $totalUnidades = $totalUnidades + $Total_Cantidad;

                $primero = $traslado->detalle->count() ? $traslado->detalle[0]->descripcion : '';
                $ultimo = $traslado->detalle->count() ? $traslado->detalle[count($traslado->detalle)-1]->descripcion : '';
			?>
		    <tr class="{{ $fondo }}">
		      <th>{{$traslado->id}}</th>
		      <td>{{$traslado->numero_ajuste}}</td>
		      <td>{{$traslado->fecha_ajuste}}</td>
		      <td>{{$traslado->fecha_traslado}}</td>
		      <td>{{$traslado->sede_destino}}</td>
		      <td>{{$Total_Cantidad}}</td>
		      <td>{{$traslado->bultos+$traslado->bultos_refrigerados+$traslado->bultos_fragiles}}</td>
              <td>{{$traslado->detalle->count()}}</td>
		      <td>{{$Total_Bs}}</td>
		      <td>{{$Total_Usd}}</td>
		      <td>{{$traslado->estatus}}</td>
		      <td>{{$Dias}}</td>
              <td>{{ $primero }}</td>
              <td>{{ $ultimo }}</td>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:170px;">
					<?php
					if(($traslado->estatus=='PROCESADO'||$traslado->estatus=='EMBALADO'||$traslado->estatus=='ENTREGADO'||$traslado->estatus=='ENTREGADO CON RECLAMO') &&
						(Auth::user()->departamento == 'OPERACIONES'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA' 
						|| Auth::user()->departamento == 'INVENTARIO'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA'
                    || Auth::user()->departamento == 'CONTRASEDE' || Auth::user()->departamento == 'ALMACEN')
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
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/traslado/{{$traslado->id}}/edit" role="button" class="btn {{ $verde }} btn-sm" data-toggle="tooltip" data-placement="top" title="Embalar" style="width: auto">
	      			<i class="fas fa-box-open"></i>      		
	      		</a>
					<?php
					}
					?>						

		      <?php
					if(($traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ALMACEN'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/GuiaEnvio?Ajuste={{$traslado->numero_ajuste}}" role="button" class="btn {{ $amarillo }} btn-sm" data-toggle="tooltip" data-placement="top" title="Guia de envio y etiquetas" style="width: auto">
	      			<i class="fas fa-tag"></i>     		
	      		</a>
					<?php
					}
					?>

					<?php
					if(($traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ADMINISTRACION'
				    || Auth::user()->departamento == 'GERENCIA'
                    || Auth::user()->departamento == 'CONTRASEDE'
				    || Auth::user()->departamento == 'TECNOLOGIA'
                    || Auth::user()->departamento == 'DEVOLUCIONES'
				    || (Auth::user()->departamento == 'AUDITORIA' && Auth::user()->role == 'SUPERVISOR')
				  )
						){
					?>
						<form action="/traslado/{{$traslado->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn {{ $rojo }} btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fa fa-check"></i></button>
						</form>

                        @if(strpos($traslado->numero_ajuste, 'R') === false)
                            <form action="/traslado/finalizarConReclamo" style="display: inline;">
                                <input type="hidden" name="traslado" value="{{ $traslado->id }}">
                                <button type="submit" class="btn {{ $rojo }} btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar con reclamo"><i class="m-1 fa fa-info"></i></button>
                            </form>
                        @endif
					<?php
					}
					?>						
		      </td>
		    <!-- Fin Validacion de ROLES -->

		    </tr>
		@endforeach
		</tbody>

        <tfoot>
            <tr>
                <th colspan="5">Totales:</th>
                <th>{{ $totalUnidades }}</th>
                <th colspan="6"></th>
            </tr>
        </tfoot>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   

            @if(request()->Tipo == 0)
                $('.abiertas').text({{ $abiertas }});
                $('.tres').text({{ $tres }});
                $('.cuatro').text({{ $cuatro }});
                $('.cinco').text({{ $cinco }});
                $('.siete').text({{ $siete }});
            @endif

            @if(request()->Tipo == 1)
                $('.abiertas').text({{ $embaladosAbiertas }});
                $('.tres').text({{ $embaladosTres }});
                $('.cuatro').text({{ $embaladosCuatro }});
                $('.cinco').text({{ $embaladosCinco }});
                $('.siete').text({{ $embaladosSiete }});
            @endif
		});

		$('#exampleModalCenter').modal('show')
	</script>

@endsection
