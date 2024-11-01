@extends('layouts.contabilidad')

@section('title')
    Detalles de errores del cajero
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
		Detalles de errores del cajero
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

        <form action="{{route("detalleCajerosTransaccionales2")}}" method="POST">
            @csrf
            <input type="hidden" name="cajero" value="{{$cajero}}">
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
                      <option value="FLF" {{ ( $sede == "FLF") ? 'selected' : '' }}>FLF</option>
                      <option value="CDD" {{ ( $sede == "CDD") ? 'selected' : '' }}>CDD</option>
                    </select>
                  @endif
                </div>

                <div class="col-12 text-center mt-3">
                    <input class="btn btn-success" type="submit" value="Enviar">
                    <a class="btn btn-info" href="/historicoCajerosTransaccionales" type="button" >Volver</a>
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
		      	<th scope="col" class="CP-sticky">Codigo</th>
                <th scope="col" class="CP-sticky">Sede</th>
                <th scope="col" class="CP-sticky">Fecha / Hora Factura</th>
		      	    <th scope="col" class="CP-sticky">Nro Factura</th>
                <th scope="col" class="CP-sticky">Cédula Factura</th>
                <th scope="col" class="CP-sticky">Cliente Factura</th>
                <th scope="col" class="CP-sticky">Teléfono Factura</th>
                <th scope="col" class="CP-sticky">Total Factura (Bs)</th>
                <th scope="col" class="CP-sticky">Total Factura ($)</th>
                <th scope="col" class="CP-sticky">Monto Pagado Factura (Bs)</th>
                <th scope="col" class="CP-sticky">Monto Pagado Factura ($)</th>
                <th scope="col" class="CP-sticky">Caja Venta</th>
                <th scope="col" class="CP-sticky">Cajero Venta</th>
                <th scope="col" class="CP-sticky">Fecha / Hora Pago Movil</th>
                <th scope="col" class="CP-sticky">Banco Destino</th>
                <th scope="col" class="CP-sticky">Monto Pago movil (Bs)</th>
                <th scope="col" class="CP-sticky">Monto Pago movil ($)</th>
                <th scope="col" class="CP-sticky">Cedula Pago movil</th>
                <th scope="col" class="CP-sticky">Telefono Pago movil</th>
                <th scope="col" class="CP-sticky">Status Banco</th>
                <th scope="col" class="CP-sticky">Nro Confirmacion</th>
                <th scope="col" class="CP-sticky">Mensaje del banco</th>
                <th scope="col" class="CP-sticky">Numero devolución</th>
                <th scope="col" class="CP-sticky">Fecha / Hora devolución</th>
                <th scope="col" class="CP-sticky">Caja devolución</th>
                <th scope="col" class="CP-sticky">Cajero devolucion</th>
		    </tr>
	  	</thead>
	  	<tbody>

        @if(isset($historialvueltos))
          @foreach($historialvueltos as $vuelto)
                  @php
                  if($vuelto->get("nro_devolucion")>0){
                      $color='background-color:#f5c6cb;';
                      $font='color:#721c24;font-weight:bold;';
                  }
                  else{
                      if($vuelto->get("estatus")=="Error"){
                          $color='background-color:#fff3cd;';
                          $font='color:red;font-weight:bold;';
                      }
                      else{
                          $color='background-color:white';
                          $font='color:darkgreen;font-weight:bold;';
                      }
                  }
                  @endphp
              <tr style="{{$color}}">

                      <td>{{$vuelto->get("id")}}</td>
                      <td>{{$vuelto->get("sede")}}</td>
                      <td>{{$vuelto->get("fecha_hora_factura")}}</td>
                      <td>{{$vuelto->get("numero_factura")}}</td>
                      <td>{{$vuelto->get("cedula_cliente_factura")}}</td>
                      <td>{{$vuelto->get("nombre_cliente")}}</td>
                      <td>{{$vuelto->get("telefono_cliente_factura")}}</td>
                      <td>{{number_format($vuelto->get("total_factura"), 2, ',', '.')}}</td>
                      <td>{{number_format($vuelto->get("total_factura_dolar"), 2, ',', '.')}}</td>
                      <td>{{number_format($vuelto->get("monto_pagado_factura"), 2, ',', '.')}}</td>
                      <td>{{number_format($vuelto->get("monto_pagado_factura_dolar"), 2, ',', '.')}}</td>
                      <td>{{$vuelto->get("caja")}}</td>
                      <td>{{$vuelto->get("cajero_venta")}}</td>
                      <td>{{$vuelto->get("fecha_hora")}}</td>
                      <td>{{$vuelto->get("banco_cliente")}}</td>
                      <td>{{number_format($vuelto->get("monto"), 2, ',', '.')}}</td>
                      <td>{{number_format($vuelto->get("monto_dolar"), 2, ',', '.')}}</td>
                      <td>{{$vuelto->get("cedula_cliente")}}</td>
                      <td>{{$vuelto->get("telefono_pago_movil")}}</td>
                      <td style="{{$font}}">{{$vuelto->get("estatus")}}</td>
                      <td>
                          @if($vuelto->get("estatus")=="Error")
                              N/A
                          @else
                              {{$vuelto->get("confirmacion_banco")}}
                          @endif

                      </td>
                      <td style="{{$font}}">
                          @if($vuelto->get("estatus")=="Error")
                              {{$vuelto->get("motivo_error")}}
                          @else
                              Aprobado
                          @endif
                      </td>
                      <td>{{$vuelto->get("nro_devolucion")}}</td>
                      <td>{{$vuelto->get("fecha_devolucion")}}</td>
                      <td>{{$vuelto->get("caja_devolucion")}}</td>
                      <td>{{$vuelto->get("cajero_devolucion")}}</td>
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
