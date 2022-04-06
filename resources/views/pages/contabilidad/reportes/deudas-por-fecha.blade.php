@extends('layouts.contabilidad')


@section('title')
    Reporte
@endsection


@section('scriptsHead')
    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['id_registro', 'fecha_hora', 'tipo', 'proveedor', 'moneda_proveedor', 'monto_subtotal', 'iva', 'sede', 'estado', 'operador'];

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
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice">
        </i>
        Deudas por fecha
    </h1>

    <hr class="row align-items-start col-12">

    @if($request->get('fechaInicio'))
        <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text purple lighten-3" id="basic-text1">
                    <i class="fas fa-search text-white" aria-hidden="true"></i>
                </span>
            </div>

            <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>

        <h6 align="center">Periodo desde el {{ $fechaInicio }} al {{ $fechaFin }}</h6>

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
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'id_registro')" name="id_registro" checked>
                            ID registro
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_hora')" name="fecha_hora" checked>
                            Fecha y hora
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'tipo')" name="tipo" checked>
                            Tipo
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'proveedor')" name="proveedor" checked>
                            Proveedor
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_proveedor')" name="moneda_proveedor" checked>
                            Moneda del proveedor
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_subtotal')" name="monto_subtotal" checked>
                            Monto subtotal
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'iva')" name="iva" checked>
                            IVA
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'sede')" name="sede" checked>
                            Sede
                        </div>

                        <div class="form-group">
                            <input type="checkbox" onclick="mostrar_ocultar(this, 'estado')" name="estado" checked>
                            Estado
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

        <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th nowrap scope="col" class="CP-sticky">#</th>
                    <th nowrap scope="col" class="id_registro CP-sticky">ID registro</th>
                    <th nowrap scope="col" class="fecha_hora CP-sticky">Fecha y hora</th>
                    <th nowrap scope="col" class="tipo CP-sticky">Tipo</th>
                    <th nowrap scope="col" class="proveedor CP-sticky">Proveedor</th>
                    <th nowrap scope="col" class="moneda_proveedor CP-sticky">Moneda del proveedor</th>
                    <th nowrap scope="col" class="monto_subtotal CP-sticky">Monto subtotal</th>
                    <th nowrap scope="col" class="iva CP-sticky">IVA</th>
                    <th nowrap scope="col" class="sede CP-sticky">Sede</th>
                    <th nowrap scope="col" class="estado CP-sticky">Estado</th>
                    <th nowrap scope="col" class="operador CP-sticky">Operador</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $dolares = 0;
                    $bolivares = 0;
                @endphp

                @foreach($items as $item)
                    <tr>
                        <td nowrap class="text-center">{{ $loop->iteration }}</td>
                        <td nowrap class="id_registro text-center">{{ $item->id }}</td>
                        <td nowrap class="fecha_hora text-center">{{ date_format(new DateTime($item->created_at), 'd/m/Y h:i A') }}</td>
                        <td nowrap class="tipo text-center">{{ $item->tipo }}</td>

                        <td align="center" nowrap class="proveedor CP-barrido">
                            @if(isset($item->proveedor))
                                @php
                                    $fechaInicio = date_create();
                                    $fechaInicio = date_modify($fechaInicio, '-30day');
                                    $fechaInicio = $fechaInicio->format('Y-m-d');

                                    $fechaFinal = date('Y-m-d');

                                    $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $item->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;
                                @endphp

                                <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $item->proveedor }}</a>
                            @endif
                        </td>

                        <td nowrap class="text-center moneda_proveedor">{{ $item->moneda_proveedor }}</td>
                        <td nowrap class="text-center monto_subtotal">{{ number_format($item->monto, 2, ',', '.') }}</td>
                        <td nowrap class="text-center iva">{{ number_format($item->monto_iva, 2, ',', '.') }}</td>
                        <td nowrap class="text-center sede">{{ $item->sede }}</td>
                        <td nowrap class="text-center estado">{{ $item->estado }}</td>
                        <td nowrap class="text-center operador">{{ $item->operador }}</td>
                    </tr>

                    @php
                        if ($item->moneda == 'Dólares' && $item->estado == 'Activo') {
                            $dolares = $dolares + $item->monto;
                        }

                        if ($item->moneda == 'Bolívares' && $item->estado == 'Activo') {
                            $bolivares = $bolivares + $item->monto;
                        }
                    @endphp
                @endforeach
            </tbody>
        </table>

        <table class="table table-striped table-bordered col-12 sortable">
            <thead>
                <tr>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border-top: white 1px solid;border-left: white 1px solid;border-bottom: white 1px solid;border"></th>
                    <th class="text-center">Total en bolívares</th>
                    <th class="text-center">{{ number_format($bolivares, 2, ',', '.') }}</th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                </tr>

                <tr>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border-left: white 1px solid;border-bottom: white 1px solid;"></th>
                    <th class="text-center">Total en dólares</th>
                    <th class="text-center">{{ number_format($dolares, 2, ',', '.') }}</th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                </tr>
            </thead>
        </table>


    @else
        <form action="">
            <div class="row mb-5">
                <div class="col"></div>

                <div class="col">
                    Fecha inicio:
                </div>

                <div class="col">
                    <input type="date" required class="form-control" name="fechaInicio">
                </div>

                <div class="col"></div>

                <div class="col">
                    Fecha fin:
                </div>

                <div class="col">
                    <input type="date" required class="form-control" name="fechaFin">
                </div>

                <div class="col">
                    <input type="submit" class="btn btn-outline-success" value="Buscar">
                </div>

                <div class="col"></div>
            </div>
        </form>
    @endif
@endsection
