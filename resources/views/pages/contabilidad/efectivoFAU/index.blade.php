@extends('layouts.contabilidad')

@section('title', 'Registro de pagos en efectivo dólares FAU')


@section('scriptsHead')
    <style>
        th, td {text-align: center;}
    </style>


    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['nro_registro', 'tipo', 'concepto', 'ingresos', 'egresos', 'diferidos', 'saldo_anterior', 'saldo_posterior', 'tasa', 'fecha_hora', 'plan_cuentas', 'proveedor_titular', 'usuario', 'autorizado_por'];

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
            <input type="checkbox" onclick="mostrar_ocultar(this, 'nro_registro')" name="nro_registro" checked>
            # de registro
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'tipo')" name="tipo" checked>
            Tipo
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'concepto')" name="concepto" checked>
            Concepto
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'ingresos')" name="ingresos" checked>
            Ingresos
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'egresos')" name="egresos" checked>
            Egresos
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'diferidos')" name="diferidos" checked>
            Diferidos
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'saldo_anterior')" name="saldo_anterior" checked>
            Saldo anterior
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'saldo_posterior')" name="saldo_posterior" checked>
            Saldo posterior
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'tasa')" name="tasa" checked>
            Tasa
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_hora')" name="fecha_hora" checked>
            Fecha y hora
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'plan_cuentas')" name="plan_cuentas" checked>
            Plan de cuentas
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'proveedor_titular')" name="proveedor_titular" checked>
            Proveedor / Titular
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'usuario')" name="usuario" checked>
            Usuario
        </div>

        <div class="form-group">
            <input type="checkbox" onclick="mostrar_ocultar(this, 'autorizado_por')" name="autorizado_por" checked>
            Autorizado por
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
    Registro de pagos en efectivo dólares FAU
  </h1>

  <hr class="row align-items-start col-12">

  <form action="">
    <div class="row">
      <div class="col-4">
        <div class="form-group">
          <label for="fecha_desde">Fecha desde</label>
          <input type="date" name="fecha_desde" value="{{ $request->get('fecha_desde') }}" class="form-control">
        </div>
      </div>

      <div class="col-4">
        <div class="form-group">
          <label for="fecha_hasta">Fecha hasta</label>
          <input type="date" name="fecha_hasta" value="{{ $request->get('fecha_hasta') }}" class="form-control">
        </div>
      </div>

      <div class="col-4">
        <div class="form-group">
          <button class="btn btn-outline-success btn-block" style="margin-top: 10%">Buscar</button>
        </div>
      </div>
    </div>
  </form>



  <hr class="row align-items-start col-12">
  <table style="width:100%;" class="CP-stickyBar">
    <tr>

      @if(auth()->user()->departamento == 'TESORERIA' || auth()->user()->departamento == 'TECNOLOGIA')
        <td style="width:15%;" align="center">
          <a href="/efectivoFAU/create?tipo=movimiento" role="button" class="btn btn-outline-info btn-sm"
          style="display: inline; text-align: left;">
          <i class="fa fa-plus"></i>
            Agregar ingresos / Gastos
          </a>
        </td>

        <td style="width:20%;" align="center">
          <a href="/efectivoFAU/create?tipo=proveedores" role="button" class="btn btn-outline-info btn-sm"
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

  <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

  <table class="table table-striped table-borderless col-12" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="nro_registro CP-sticky"># de registro</th>
        <th scope="col" class="tipo CP-sticky">Tipo</th>
        <th scope="col" class="concepto CP-sticky">Concepto</th>
        <th scope="col" class="ingresos CP-sticky">Ingresos</th>
        <th scope="col" class="egresos CP-sticky">Egresos</th>
        <th scope="col" class="diferidos CP-sticky">Diferidos</th>
        <th scope="col" class="saldo_anterior CP-sticky">Saldo anterior</th>
        <th scope="col" class="saldo_posterior CP-sticky">Saldo posterior</th>
        <th scope="col" class="tasa CP-sticky">Tasa</th>
        <th scope="col" class="fecha_hora CP-sticky">Fecha y hora</th>
        <th scope="col" class="plan_cuentas CP-sticky">Plan de cuentas</th>
        <th scope="col" class="proveedor_titular CP-sticky">Proveedor / Titular</th>
        <th scope="col" class="usuario CP-sticky">Usuario</th>
        <th scope="col" class="autorizado_por CP-sticky">Autorizado por</th>
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
        <th class="nro_registro">{{ str_pad($pago->id, 5, 0, STR_PAD_LEFT) }}</th>
        <td class="tipo">{{ ($pago->proveedor) ? 'Proveedor' : 'Movimiento' }}</td>
        <td class="concepto">
          <span class="d-inline-block " style="max-width: 250px;">
            {!! $pago->concepto !!}
          </span>
        </td>
        <td class="ingresos">
          {{number_format(monto_banco($pago->ingresos, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.')}}
        </td>
        <td class="egresos">
          {{number_format(monto_banco($pago->egresos, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.')}}
        </td>
        <td class="diferidos">
          {{number_format(monto_banco($pago->diferido, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva), 2, ',', '.')}}
        </td>
        <td class="saldo_anterior">{{number_format($pago->saldo_anterior, 2, ',', '.')}}</td>
        <td class="saldo_posterior">{{number_format($pago->saldo_actual, 2, ',', '.')}}</td>
        <td class="tasa">{{ ($pago->tasa) ? number_format($pago->tasa,2,',','.') : '' }}</td>
        <td class="fecha_hora">{{date("d-m-Y h:i:s a", strtotime($pago->created_at))}}</td>
        <td class="plan_cuentas">
            @if($pago->cuenta)
                {{ $pago->cuenta->nombre }}
            @endif

            @if($pago->proveedor)
                {{ $pago->proveedor->plan_cuentas }}
            @endif
        </td>

        <td align="center" class="proveedor_titular CP-barrido">
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

        <td class="usuario">{{$pago->user}}</td>
        <td class="autorizado_por">{{$pago->autorizado_por}}</td>
        <td style="width:140px;">
            @if(!$pago->ingresos)
                @if (!($pago->egresos && strpos($pago->concepto, 'DIFERIDO')))
                    <a target="_blank" href="/efectivoFAU/soporte/{{$pago->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver soporte">
                        <i class="fas fa-eye"></i>
                    </a>
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
