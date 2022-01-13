@extends('layouts.contabilidad')

@section('title', 'Registro de pagos en efectivo dólares FM')

@section('scriptsHead')
  <style>
    th, td {text-align: center;}
  </style>
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
            <h4 class="h6">Pago en efectivo almacenado con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-money-bill-alt"></i>
    Registro de pagos en efectivo dólares FM
  </h1>

  <hr class="row align-items-start col-12">

  <form action="">
    <div class="row">
      <div class="col-3">
        <div class="form-group">
            <label for="cantidad">Cantidad de registros</label>
            <select class="form-control" name="cantidad">
                <option {{ $selected50 }} value="50">50</option>
                <option {{ $selected100 }} value="100">100</option>
                <option {{ $selected200 }} value="200">200</option>
                <option {{ $selected500 }} value="500">500</option>
                <option {{ $selected1000 }} value="1000">1000</option>
                <option {{ $selectedTodos }} value="Todos">Todos</option>
            </select>
        </div>
      </div>

      <div class="col-3">
        <div class="form-group">
          <label for="fecha_desde">Fecha desde</label>
          <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control">
        </div>
      </div>

      <div class="col-3">
        <div class="form-group">
          <label for="fecha_hasta">Fecha hasta</label>
          <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control">
        </div>
      </div>

      <div class="col-3">
        <div class="form-group">
          <button style="margin-top: 7.5%" class="btn btn-outline-success btn-block">Buscar</button>
        </div>
      </div>
    </div>
  </form>



  <hr class="row align-items-start col-12">
  <table style="width:100%;" class="CP-stickyBar">
    <tr>

      @if(auth()->user()->departamento == 'TESORERIA' || auth()->user()->departamento == 'TECNOLOGIA')
        <td style="width:15%;" align="center">
          <a href="/efectivoFM/create?tipo=movimiento" role="button" class="btn btn-outline-info btn-sm"
          style="display: inline; text-align: left;">
          <i class="fa fa-plus"></i>
            Agregar ingresos / Gastos
          </a>
        </td>

        <td style="width:20%;" align="center">
          <a href="/efectivoFM/create?tipo=proveedores" role="button" class="btn btn-outline-info btn-sm"
          style="display: inline; text-align: left;">
          <i class="fa fa-plus"></i>
            Agregar pago a proveedores
          </a>
        </td>
      @endif

      <td style="width:90%;">
        <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
          <div class="input-group-prepend">
            <span class="input-group-text purple lighten-3" id="basic-text1">
              <i class="fas fa-search text-white" aria-hidden="true"></i>
            </span>
          </div>
          <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>
      </td>
    </tr>
  </table>
  <br/>

  <table class="table table-striped table-borderless col-12" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="CP-sticky"># de registro</th>
        <th scope="col" class="CP-sticky">Tipo</th>
        <th scope="col" class="CP-sticky">Concepto</th>
        <th scope="col" class="CP-sticky">Ingresos</th>
        <th scope="col" class="CP-sticky">Egresos</th>
        <th scope="col" class="CP-sticky">Diferidos</th>
        <th scope="col" class="CP-sticky">Saldo anterior</th>
        <th scope="col" class="CP-sticky">Saldo posterior</th>
        <th scope="col" class="CP-sticky">Tasa</th>
        <th scope="col" class="CP-sticky">Fecha y hora</th>
        <th scope="col" class="CP-sticky">Plan de cuentas</th>
        <th scope="col" class="CP-sticky">Proveedor / Titular</th>
        <th scope="col" class="CP-sticky">Usuario</th>
        <th scope="col" class="CP-sticky">Autorizado por</th>
        <th scope="col" class="CP-sticky">Acciones</th>
      </tr>
    </thead>

    @php
      include(app_path().'\functions\functions.php');
      include(app_path().'\functions\functions_contabilidad.php');
      $cont = 0;
    @endphp

    <tbody>
    @foreach($pagos as $pago)
      <tr>
        <th>{{intval(++$cont)}}</th>
        <th>{{ str_pad($pago->id, 5, 0, STR_PAD_LEFT) }}</th>
        <td>{{ ($pago->proveedor) ? 'Proveedor' : 'Movimiento' }}</td>
        <td>
          <span class="d-inline-block " style="max-width: 250px;">
            {!! $pago->concepto !!}
          </span>
        </td>
        <td>
          {{number_format(monto_banco($pago->ingresos, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.')}}
        </td>
        <td>
          {{number_format(monto_banco($pago->egresos, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.')}}
        </td>
        <td>
          {{number_format(monto_banco($pago->diferido, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.')}}
        </td>
        <td>{{number_format($pago->saldo_anterior, 2, ',', '.')}}</td>
        <td>{{number_format($pago->saldo_actual, 2, ',', '.')}}</td>
        <td>{{ ($pago->tasa) ? number_format($pago->tasa,2,',','.') : '' }}</td>
        <td>{{date("d-m-Y h:i:s a", strtotime($pago->created_at))}}</td>
        <td>
            @if($pago->cuenta)
                {{ $pago->cuenta->nombre }}
            @endif

            @if($pago->proveedor)
                {{ $pago->proveedor->plan_cuentas }}
            @endif
        </td>

        <td align="center" class="CP-barrido">
            @if(isset($pago->proveedor))
                @php
                    $fechaInicio = date_create();
                    $fechaInicio = date_modify($fechaInicio, '-30day');
                    $fechaInicio = $fechaInicio->format('Y-m-d');

                    $fechaFinal = date('Y-m-d');

                    $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $pago->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;
                @endphp

                <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $pago->proveedor->nombre_proveedor }}</a>
            @endif

            @if($pago->titular_pago)
                {{ $pago->titular_pago }}
            @endif
        </td>
        <td>{{$pago->user}}</td>
        <td>{{$pago->autorizado_por}}</td>
        <td style="width:140px;">
            @if(!$pago->ingresos)
                @if (!($pago->egresos && strpos($pago->concepto, 'DIFERIDO')))
                    <a target="_blank" href="/efectivoFM/soporte/{{$pago->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver soporte">
                        <i class="fas fa-eye"></i>
                    </a>
                @endif
            @endif
          </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ ($pagos instanceof Illuminate\Pagination\LengthAwarePaginator) ? $pagos->links() : '' }}

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
  </script>
@endsection
