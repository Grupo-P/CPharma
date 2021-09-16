@extends('layouts.contabilidad')

@section('title')
    Registro de pagos bancarios
@endsection

@section('content')

    <!-- Modal Guardar -->
    @if (session('Send'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Send') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Notificación enviada satisfactoriamente</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

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
                <h4 class="h6">Pago bancario almacenado con exito</h4>
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
                <h4 class="h6">Pago bancario modificado con exito</h4>
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
                <h4 class="h6">Pago bancario reversado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <h1 class="h5 text-info">
        <i class="fas fa-info-circle"></i>
        Registro de pagos bancarios
    </h1>

    <hr class="row align-items-start col-12">
    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'ADMINISTRACION')
                <td style="width:15%;" align="center">
                    <a href="{{ url('/bancarios/create') }}" role="button" class="btn btn-outline-info btn-sm"
                    style="display: inline; text-align: left;">
                    <i class="fa fa-plus"></i>
                        Cargar pago bancario a proveedor
                    </a>
                </td>
            @endif

            <td style="width:60%;">
                <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                    <div class="input-group-prepend">
                        <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                    aria-hidden="true"></i></span>
                    </div>
                <input class="form-control my-0 py-1 CP-stickyBar" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
                </div>
            </td>
        </tr>
    </table>

    <br/>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky" nowrap>#</th>
                <th scope="col" class="CP-sticky" nowrap>Nombre del proveedor</th>
                <th scope="col" class="CP-sticky" nowrap>RIF/CI del proveedor</th>
                <th scope="col" class="CP-sticky" nowrap>Fecha de registro</th>
                <th scope="col" class="CP-sticky" nowrap>Monto del banco</th>
                <th scope="col" class="CP-sticky" nowrap>Monto del proveedor</th>
                <th scope="col" class="CP-sticky" nowrap>Tasa</th>
                <th scope="col" class="CP-sticky" nowrap>Estado</th>
                <th scope="col" class="CP-sticky" nowrap>Comentario</th>
                <th scope="col" class="CP-sticky" nowrap>Alias bancario</th>
                <th scope="col" class="CP-sticky" nowrap>Operador</th>
                <th scope="col" class="CP-sticky" nowrap>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pagos as $pago)
            @php
                $fechaInicio = date_create();
                $fechaInicio = date_modify($fechaInicio, '-30day');
                $fechaInicio = $fechaInicio->format('Y-m-d');

                $fechaFinal = date('Y-m-d');

                $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $pago->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;
            @endphp

            <tr class="{{ ($pago->estatus == 'Reversado') ? 'bg-warning' : '' }}">
              <th class="text-center" nowrap>{{$pago->id}}</th>
              <td class="text-center" nowrap align="center" class="CP-barrido">
                  <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $pago->proveedor->nombre_proveedor }}</a>
              </td>
              <td class="text-center" nowrap>{{$pago->proveedor->rif_ci}}</td>
              <td class="text-center" nowrap>{{$pago->created_at}}</td>
              <td class="text-center" nowrap>{{ ($pago->estatus != 'Prepagado') ? number_format($pago->monto, 2, ',', '.') : ''}}</td>

              @php
                if ($pago->tasa) {
                    if ($pago->banco->moneda != $pago->proveedor->moneda) {
                        if ($pago->banco->moneda == 'Dólares' && $pago->proveedor->moneda == 'Bolívares') {
                            $monto_proveedor = $pago->monto * $pago->tasa;
                        }

                        if ($pago->banco->moneda == 'Dólares' && $pago->proveedor->moneda == 'Pesos') {
                            $monto_proveedor = $pago->monto * $pago->tasa;
                        }

                        if ($pago->banco->moneda == 'Bolívares' && $pago->proveedor->moneda == 'Dólares') {
                            $monto_proveedor = $pago->monto / $pago->tasa;
                        }

                        if ($pago->banco->moneda == 'Bolívares' && $pago->proveedor->moneda == 'Pesos') {
                            $monto_proveedor = $pago->monto * $pago->tasa;
                        }

                        if ($pago->banco->moneda == 'Pesos' && $pago->proveedor->moneda == 'Bolívares') {
                            $monto_proveedor = $pago->monto / $pago->tasa;
                        }

                        if ($pago->banco->moneda == 'Pesos' && $pago->proveedor->moneda == 'Dólares') {
                            $monto_proveedor = $pago->monto / $pago->tasa;
                        }
                    } else {
                        $monto_proveedor = $pago->monto;
                    }
                }
                else {
                    $monto_proveedor = $pago->monto;
                }
              @endphp

              <td nowrap class="text-center">{{number_format($monto_proveedor, 2, ',', '.')}}</td>
              <td nowrap class="text-center">{{($pago->tasa) ? number_format($pago->tasa, 2, ',', '.') : ''}}</td>
              <td nowrap class="text-center">{{$pago->estatus}}</td>
              <td nowrap class="text-center">{{$pago->comentario}}</td>
              <td nowrap class="text-center">{{isset($pago->banco->alias_cuenta) ? $pago->banco->alias_cuenta : ''}}</td>
              <td nowrap class="text-center">{{$pago->operador}}</td>
              <td nowrap class="text-center">
                @if($pago->estatus == 'Prepagado')
                    <a href="{{ route('bancarios.edit', $pago) }}" target="_blank" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
                        <i class="fa fa-edit"></i>
                    </a>
                @endif

                <a target="_blank" href="/bancarios/soporte/{{$pago->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver soporte">
                    <i class="fa fa-file"></i>
                </a>

                <a href="/bancarios/{{$pago->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
                    <i class="far fa-eye"></i>
                </a>

                @if($pago->estatus != 'Reversado' && $pago->estatus != 'Prepagado')
                    <a href="/bancarios/notificar/{{$pago->id}}" role="button" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Notificar">
                        <i class="fa fa-bell"></i>
                    </a>
                @endif

                @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
                    @if($pago->estatus != 'Reversado')
                        <form action="/bancarios/{{$pago->id}}" method="POST" style="display: inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reverso"><i class="fa fa-reply"></i></button>
                        </form>
                    @endif
                @endif
              </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');

        $('.notificar').click(function (event) {
            event.preventDefault();
            alert('En desarrollo...');
        });
    </script>

@endsection
