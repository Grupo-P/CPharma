@extends('layouts.contabilidad')

@section('title')
    Registro de pagos bancarios
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
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky">RIF/CI del proveedor</th>
                <th scope="col" class="CP-sticky">Fecha de registro</th>
                <th scope="col" class="CP-sticky">Monto del banco</th>
                <th scope="col" class="CP-sticky">Monto del proveedor</th>
                <th scope="col" class="CP-sticky">Tasa</th>
                <th scope="col" class="CP-sticky">Estado</th>
                <th scope="col" class="CP-sticky">Comentario</th>
                <th scope="col" class="CP-sticky">Alias bancario</th>
                <th scope="col" class="CP-sticky">Operador</th>
                <th scope="col" class="CP-sticky">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pagos as $deuda)
            <tr class="{{ ($deuda->estatus == 'Reversado') ? 'bg-warning' : '' }}">
              <th>{{$deuda->id}}</th>
              <td>{{$deuda->proveedor->nombre_proveedor}}</td>
              <td>{{$deuda->proveedor->rif_ci}}</td>
              <td>{{$deuda->created_at}}</td>
              <td>{{number_format($deuda->monto, 2, ',', '.')}}</td>

              @php
                if ($deuda->banco->moneda != $deuda->proveedor->moneda) {
                    if ($deuda->banco->moneda == 'Dólares' && $deuda->proveedor->moneda == 'Bolívares') {
                        $monto_proveedor = $deuda->monto * $deuda->tasa;
                    }

                    if ($deuda->banco->moneda == 'Dólares' && $deuda->proveedor->moneda == 'Pesos') {
                        $monto_proveedor = $deuda->monto * $deuda->tasa;
                    }

                    if ($deuda->banco->moneda == 'Bolívares' && $deuda->proveedor->moneda == 'Dólares') {
                        $monto_proveedor = $deuda->monto / $deuda->tasa;
                    }

                    if ($deuda->banco->moneda == 'Bolívares' && $deuda->proveedor->moneda == 'Pesos') {
                        $monto_proveedor = $deuda->monto * $deuda->tasa;
                    }

                    if ($deuda->banco->moneda == 'Pesos' && $deuda->proveedor->moneda == 'Bolívares') {
                        $monto_proveedor = $deuda->monto / $deuda->tasa;
                    }

                    if ($deuda->banco->moneda == 'Pesos' && $deuda->proveedor->moneda == 'Dólares') {
                        $monto_proveedor = $deuda->monto / $deuda->tasa;
                    }
                } else {
                    $monto_proveedor = $deuda->monto;
                }
              @endphp

              <td>{{number_format($monto_proveedor, 2, ',', '.')}}</td>
              <td>{{($deuda->tasa) ? number_format($deuda->tasa, 2, ',', '.') : ''}}</td>
              <td>{{$deuda->estatus}}</td>
              <td>{{$deuda->comentario}}</td>
              <td>{{isset($deuda->banco->alias_cuenta) ? $deuda->banco->alias_cuenta : ''}}</td>
              <td>{{$deuda->operador}}</td>
              <td style="width:200px;">
                <a target="_blank" href="/bancarios/soporte/{{$deuda->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver soporte">
                    <i class="fa fa-file"></i>
                </a>

                <a href="/bancarios/{{$deuda->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
                    <i class="far fa-eye"></i>
                </a>

                @if(!$deuda->estatus == 'Reversado')
                    <a href="/bancarios/notificar/{{$deuda->id}}" role="button" class="btn btn-outline-dark notificar btn-sm" data-toggle="tooltip" data-placement="top" title="Notificar">
                        <i class="fa fa-bell"></i>
                    </a>
                @endif

                @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
                    @if($deuda->estatus != 'Reversado')
                        <form action="/bancarios/{{$deuda->id}}" method="POST" style="display: inline;">
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
