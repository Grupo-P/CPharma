@extends('layouts.contabilidad')

@section('title')
    Histórico de recargas
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
		Histórico de recargas
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
        @if($fini>0)
            <div class="alert alert-info" style="font-weight:bold;">
                <div class="row">
                    <div class="col-6 text-center">
                        Desde: {{date("d-m-Y",strtotime($fini))}} 
                    </div>
                    <div class="col-6 text-center">
                        Hasta: {{date("d-m-Y",strtotime($ffin))}}
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center" style="font-weight:bold;">
               Día: {{date("d-m-Y")}}
            </div>
        @endif
                        
        <form action="{{route("recargas2")}}" method="POST">
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
                    <a class="btn btn-info" href="/historicoRecargas" type="button" >Limpiar</a>
                </div>
            </div>
        </form>
        <div class="col-12 text-center mt-3">    
          <div class="row">
            <div class="col-4">
              <form action="{{route("cajasRecargas2")}}" method="POST" target="_blank">
                @csrf
                <input name="fecha_ini" type="hidden" value="{{$fini}}">
                <input name="fecha_fin" type="hidden" value="{{$ffin}}">
                <input name="sede" type="hidden" value="{{$sede}}">

                <button class="btn btn-info" type="submit" >Cajas Transaccionales</button>
              </form>     
            </div>           
            <div class="col-4">
              <form action="{{route("cajerosRecargas2")}}" method="POST" target="_blank">
                @csrf
                <input name="fecha_ini" type="hidden" value="{{$fini}}">
                <input name="fecha_fin" type="hidden" value="{{$ffin}}">
                <input name="sede" type="hidden" value="{{$sede}}">

                <button class="btn btn-info" type="submit" >Cajeros Transaccionales</button>
              </form> 
            </div>
            <div class="col-4">
              <form action="{{route("serviciosRecargas2")}}" method="POST" target="_blank">
                @csrf
                <input name="fecha_ini" type="hidden" value="{{$fini}}">
                <input name="fecha_fin" type="hidden" value="{{$ffin}}">
                <input name="sede" type="hidden" value="{{$sede}}">

                <button class="btn btn-info" type="submit" >Servicios</button>
              </form> 
            </div>
          </div>
        </div>        
    </div>  
    <br>
    <div class="row">    
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
                <th scope="col" class="CP-sticky">Tarjeta</th>
                <th scope="col" class="CP-sticky">Monto Recarga</th>                
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
                      
                      <td style="text-align:center;">{{$contador}}</td>
                      <td style="text-align:center;">
                        <form action="{{route("detalleOperacionRecarga2")}}" method="POST" target="_blank">
                          @csrf
                          <input name="operacion" type="hidden" value="{{$vuelto->id_operacion}}"> 
                          <input name="fecha_ini" type="hidden" value="{{$fini}}">
            							<input name="fecha_fin" type="hidden" value="{{$ffin}}">                        
                          <input name="sede" type="hidden" value="{{$sede}}">
                  
                          <button class="btn btn-link" type="submit" >{{$vuelto->id_operacion }}</button>
                        </form>  
                        
                      </td>
                      <td style="text-align:center;">{{$vuelto->cajero->username}}</td>
                      <td style="text-align:center;">{{$vuelto->caja->nombre}}</td>
                      <td style="text-align:center;">{{$vuelto->servicioNombre($vuelto->servicio,$vuelto->subservicio)}}</td>                                
                      <td style="text-align:center;">{{$vuelto->telefono}}</td>                
                      <td style="text-align:center;">{{$vuelto->contrato}}</td>                
                      <td style="text-align:center;">{{$vuelto->placa}}</td>         
                      <td style="text-align:center;">Bs. {{number_format($vuelto->monto,2,',','.')}}</td>                             
                      <td style="text-align:center;">{{date("d/m/Y g:i a",strtotime($vuelto->created_at))}}</td>
              </tr>
          @endforeach
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3" style="text-aling:right;font-weight:bold;">Totales:</td>            
            <td style="text-align:center;font-weight:bold">Bs. {{number_format($vueltos->SUM('monto'),2,',','.')}}</td>                             
            <td></td>
          </tr>
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