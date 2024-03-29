@extends('layouts.contabilidad')

@section('title')
    Registro de pagos bancarios
@endsection


@section('scriptsHead')
    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['nombre_proveedor', 'rif_proveedor', 'fecha_registro', 'monto_banco', 'monto_proveedor_base', 'monto_proveedor_iva', 'moneda_base', 'moneda_iva', 'tasa', 'estado', 'comentario', 'alias_bancario', 'operador'];

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
                <input type="checkbox" onclick="mostrar_ocultar(this, 'nombre_proveedor')" name="nombre_proveedor" checked>
                Nombre del proveedor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'rif_proveedor')" name="rif_proveedor" checked>
                RIF/CI del proveedor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_registro')" name="fecha_registro" checked>
                Fecha de registro
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_banco')" name="monto_banco" checked>
                Monto banco
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_proveedor_base')" name="monto_proveedor_base" checked>
                Monto proveedor base
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_proveedor_iva')" name="monto_proveedor_iva" checked>
                Monto proveedor IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_base')" name="moneda_base" checked>
                Moneda base
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_iva')" name="moneda_iva" checked>
                Moneda IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'tasa')" name="tasa" checked>
                Tasa
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'estado')" name="estado" checked>
                Estado
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'comentario')" name="comentario" checked>
                Comentario
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'alias_bancario')" name="alias_bancario" checked>
                Alias bancario
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'operador')" name="operador" checked>
                Operador
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


    <h1 class="h5 text-info">
        <i class="fas fa-info-circle"></i>
        Registro de pagos bancarios
    </h1>

    <hr class="row align-items-start col-12">

    <form autocomplete="off" action="" class="mb-3">
        <div class="row">
            <div class="col-2">Cantidad de registros</div>
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

            <div class="col">Fecha inicio</div>
            <div class="col"><input type="date" value="{{ $fechaInicioUrl }}" class="form-control form-control-sm" name="fechaInicio"></div>

            <div class="col">Fecha final</div>
            <div class="col"><input type="date" value="{{ $fechaFinUrl }}" class="form-control form-control-sm" name="fechaFin"></div>

            <div class="col-2">Número registro</div>
            <div class="col"><input type="number" value="{{ $numeroRegistro }}" class="form-control form-control-sm" name="numeroRegistro"></div>

            <div class="col"><input type="submit" value="Buscar" class="btn btn-sm btn-block btn-outline-success"></div>
        </div>
    </form>

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

    <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky" nowrap>#</th>
                <th scope="col" class="nombre_proveedor CP-sticky" nowrap>Nombre del proveedor</th>
                <th scope="col" class="rif_proveedor CP-sticky" nowrap>RIF/CI del proveedor</th>
                <th scope="col" class="fecha_registro CP-sticky" nowrap>Fecha de registro</th>
                <th scope="col" class="monto_banco CP-sticky" nowrap>Monto banco</th>
                <th scope="col" class="monto_proveedor_base CP-sticky" nowrap>Monto proveedor base</th>
                <th scope="col" class="monto_proveedor_iva CP-sticky" nowrap>Monto proveedor IVA</th>
                <th scope="col" class="moneda_base CP-sticky" nowrap>Moneda base</th>
                <th scope="col" class="moneda_iva CP-sticky" nowrap>Moneda IVA</th>
                <th scope="col" class="tasa CP-sticky" nowrap>Tasa</th>
                <th scope="col" class="estado CP-sticky" nowrap>Estado</th>
                <th scope="col" class="comentario CP-sticky" nowrap>Comentario</th>
                <th scope="col" class="alias_bancario CP-sticky" nowrap>Alias bancario</th>
                <th scope="col" class="operador CP-sticky" nowrap>Operador</th>
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
              <td class="nombre_proveedor text-center" nowrap align="center" class="CP-barrido">
                  <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $pago->proveedor->nombre_proveedor }}</a>
              </td>
              <td class="rif_proveedor text-center" nowrap>{{$pago->proveedor->rif_ci}}</td>
              <td class="fecha_registro text-center" nowrap>{{$pago->created_at}}</td>
              <td class="monto_banco text-center" nowrap>{{ ($pago->estatus != 'Prepagado') ? number_format(monto_banco($pago->monto, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.') : '' }}</td>
              <td nowrap class="monto_proveedor_base text-center">{{ number_format($pago->monto, 2, ',', '.') }}</td>
              <td nowrap class="monto_proveedor_iva text-center">{{ number_format($pago->iva, 2, ',', '.') }}</td>
              <td nowrap class="moneda_base text-center">{{ $pago->proveedor->moneda }}</td>
              <td nowrap class="moneda_iva text-center">{{ $pago->proveedor->moneda_iva }}</td>
              <td nowrap class="tasa text-center">{{($pago->tasa) ? number_format($pago->tasa, 2, ',', '.') : ''}}</td>
              <td nowrap class="estado text-center">{{$pago->estatus}}</td>
              <td nowrap class="comentario text-center">{{$pago->comentario}}</td>
              <td nowrap class="alias_bancario text-center">{{isset($pago->banco->alias_cuenta) ? $pago->banco->alias_cuenta : ''}}</td>
              <td nowrap class="operador text-center">{{$pago->operador}}</td>
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

                @if($pago->estatus != 'Prepagado' && (Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA'))
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

    <div>
        {{ ($pagos instanceof Illuminate\Pagination\LengthAwarePaginator) ? $pagos->appends($_GET)->links() : '' }}
    </div>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();

            $('tbody').find('tr').click(function () {
                background = $(this).css('background-color');

                console.log(background);

                if (background == 'rgb(100, 149, 237)') {
                    $(this).css('background-color', '');
                } else {
                    $(this).css('background-color', 'cornflowerblue');
                }
            });
        });

        $('#exampleModalCenter').modal('show');

        $('.notificar').click(function (event) {
            event.preventDefault();
            alert('En desarrollo...');
        });

    </script>

@endsection
