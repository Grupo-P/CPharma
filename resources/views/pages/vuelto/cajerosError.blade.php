@extends('layouts.contabilidad')

@section('title')
    Cajeros con mas incidencias:
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
		Cajeros con mas incidencias:
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
        <form action="{{route("cajerosTransaccionales2")}}" method="POST">
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
					  <option value="DBs" {{ ( $sede == "DBs") ? 'selected' : '' }}>DBs</option>
                      <option value="FTN" {{ ( $sede == "FTN") ? 'selected' : '' }}>FTN</option>
                      <option value="FAU" {{ ( $sede == "FAU") ? 'selected' : '' }}>FAU</option>
                      <option value="FLL" {{ ( $sede == "FLL") ? 'selected' : '' }}>FLL</option>
                      <option value="FSM" {{ ( $sede == "FSM") ? 'selected' : '' }}>FSM</option>
                      <option value="KDI" {{ ( $sede == "KDI") ? 'selected' : '' }}>KDI</option>
                      <option value="FEC" {{ ( $sede == "FEC") ? 'selected' : '' }}>FEC</option>
                    </select>
                  @endif
                </div>

                <div class="col-12 text-center mt-3">
                    <input class="btn btn-success" type="submit" value="Enviar">
                    <a class="btn btn-info" href="/historicoVueltos" type="button" >Volver</a>
                </div>
            </div>
        </form>
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
		      	<th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Sede</th>                
                <th scope="col" class="CP-sticky">Cajero</th>                
                <th scope="col" class="CP-sticky">Total Factura (Bs)</th>
                <th scope="col" class="CP-sticky">Total Factura ($)</th>
                <th scope="col" class="CP-sticky">Total Pagado (Bs)</th>
                <th scope="col" class="CP-sticky">Total Pagado ($)</th>
                <th scope="col" class="CP-sticky">Cantidad Errores</th>
                
		    </tr>
	  	</thead>
	  	<tbody>       
        
        @if(isset($arreglo))
          @foreach($arreglo as $vuelto)                  
              <tr>                
                    <td class="text-center">{{$vuelto["registro"] }}</td>
                    <td class="text-center">{{$vuelto["Sede"] }}</td>
                    <td class="text-center">
						<form action="{{route("detalleCajerosTransaccionalesError2")}}" method="POST" target="_blank">
							@csrf
							<input name="cajero" type="hidden" value="{{$vuelto['nombreCajeroFactura']}}">
							<input name="fecha_ini" type="hidden" value="{{$fini}}">
							<input name="fecha_fin" type="hidden" value="{{$ffin}}">
							<input name="sede" type="hidden" value="{{$sede}}">
			
							<button class="btn btn-link" type="submit" >{{$vuelto["nombreCajeroFactura"] }}</button>
						</form>                        
                    </td>                      
                    <td class="text-center">{{$vuelto["TotalAcumuladoBs"] }} Bs.</td>                                
                    <td class="text-center">$. {{$vuelto["TotalAcumuladoDolar"] }}</td> 
                    <td class="text-center">{{$vuelto["TotalPagadoBs"] }} Bs.</td> 
                    <td class="text-center">$. {{$vuelto["TotalPagadoDolar"] }}</td>                                    
                    <td class="text-center">{{$vuelto["cantidadPagoMovil"] }}</td>                                      
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