@extends('layouts.contabilidad')

@section('title')
    Detalles de operacion
@endsection

@section('content')
    @php
        
    @endphp
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
		        <h4 class="h6">Configuracion almacenada con exito</h4>
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
		        <h4 class="h6">Configuracion modificada con exito</h4>
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
		        <h4 class="h6">Configuracion actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-cogs"></i>
		Detalles de operacion:
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
	        <td style="width:10%;" align="center">				
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
    <div class="col-12">                        
        <form action="{{route("cajerosRecargas2")}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-4">
                    <label for="">Desde:</label>
                        <input class="form-control my-0 py-1" name="fecha_ini" type="date" value="{{$fini}}">
                    </div>
                <div class="col-4 mb-3">
                    <label for="">Hasta:</label>
                    <input class="form-control my-0 py-1" name="fecha_fin" type="date" value="{{$ffin}}">
                </div>
                <div class="col-4 mb-3">
                  @if($sedeUsuario=="GRUPO P, C.A")
                    <label for="">Sede: </label>
                    <select class="form-control my-0 py-1" name="sede" id="sede">
                      <option  >Seleccione una sede</option>					  
                      <option value="FTN" {{ ( $sede == "FTN") ? 'selected' : '' }}>FTN</option>
                      <option value="FAU" {{ ( $sede == "FAU") ? 'selected' : '' }}>FAU</option>
                      <option value="FLL" {{ ( $sede == "FLL") ? 'selected' : '' }}>FLL</option>
                      <option value="FSM" {{ ( $sede == "FSM") ? 'selected' : '' }}>FSM</option>
                      <option value="KDI" {{ ( $sede == "KDI") ? 'selected' : '' }}>KDI</option>
                      <option value="FEC" {{ ( $sede == "FEC") ? 'selected' : '' }}>FEC</option>
					  <option value="KD73" {{ ( $sede == "KD73") ? 'selected' : '' }}>KD73</option>
                    </select>
                  @endif
                </div>

                <div class="col-12 text-center mt-3">
                    <input class="btn btn-success" type="submit" value="Enviar">
                    <a class="btn btn-info" href="/historicoRecargas" type="button" >Volver</a>
                </div>
            </div>
        </form>
    </div>  
    <br>
    <div class="row">    
		<div class=" alert alert-info col-6">			
			<strong><label for="">Total Recibido en divisas: $. {{$operacion->total_divisas}}</label><br>			</strong>
		</div>
		<div class=" alert alert-info col-6"><strong><label for="">Total Recibido en bolivares: Bs.{{$operacion->total_bolivares}}</label></strong>		</div>
      @if(isset($mensaje))
        <div class="col-12">
          <div class="alert alert-danger">{{$mensaje}} </div>
        </div>
      @endif
    </div>
    
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
          <th scope="col" class="CP-sticky">Numero</th>
          <th scope="col" class="CP-sticky">Operacion Nro</th>
          <th scope="col" class="CP-sticky">Cajero</th>
          <th scope="col" class="CP-sticky">Caja</th>
          <th scope="col" class="CP-sticky">Servicio</th>
          <th scope="col" class="CP-sticky">Telefono</th>
          <th scope="col" class="CP-sticky">Contrato</th>
          <th scope="col" class="CP-sticky">Placa</th>
          <th scope="col" class="CP-sticky">Total usado</th>
          <th scope="col" class="CP-sticky">Total restante</th>
          <th scope="col" class="CP-sticky">Fecha</th>                                                
		    </tr>
	  	</thead>
	  	<tbody>               
        @if(isset($vueltos))
          @php        
            $contador=count($vueltos)+1;
          @endphp
          @foreach($vueltos as $vuelto)                  
                @php
                $contador=$contador-1;                  
                @endphp
            <tr>
                    
                    <td>{{$contador}}</td>
                    <td style="text-align:center;">{{$vuelto->id_operacion }}</td>
                    <td>{{$vuelto->cajero->username}}</td>
                    <td>{{$vuelto->caja->nombre}}</td>
                    <td>{{$vuelto->servicio}}</td>                                
                    <td>{{$vuelto->telefono}}</td>                
                    <td>{{$vuelto->contrato}}</td>                
                    <td>{{$vuelto->placa}}</td>         
                    <td>Bs. {{number_format($vuelto->total_usado,2,',','.')}}</td>         
					<td>Bs. {{number_format($vuelto->total_restante,2,',','.')}}</td>         
                    <td>{{$vuelto->fecha}}</td>
            </tr>
          @endforeach
        @endif
		</tbody>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();
		});
		$('#exampleModalCenter').modal('show')
	</script>

@endsection