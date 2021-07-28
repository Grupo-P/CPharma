@extends('layouts.contabilidad')

@section('title')
    Pizarra de deudas
@endsection

@section('content')
    @php
        function fecha_ultimo_pago($id_proveedor)
        {
            $efectivo = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_pagos_efectivo
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $bancario = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_pagos_bancarios
                WHERE
                    id_proveedor = '{$id_proveedor}' AND estatus != 'Reversado'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fecha_efectivo = isset($efectivo[0]->created_at) ? $efectivo[0]->created_at : null;
            $fecha_bancario = isset($bancario[0]->created_at) ? $bancario[0]->created_at : null;

            if ($fecha_efectivo >= $fecha_bancario) {
                if ($fecha_efectivo) {
                    $fecha_efectivo = date_create($fecha_efectivo);
                    $fecha_efectivo = date_format($fecha_efectivo, 'd/m/Y');

                    return $fecha_efectivo;
                }
            } else {
                if ($fecha_bancario) {
                    $fecha_bancario = date_create($fecha_bancario);
                    $fecha_bancario = date_format($fecha_bancario, 'd/m/Y');
                    return $fecha_bancario;
                }
            }
        }

        function dias_ultimo_pago($id_proveedor)
        {
            $efectivo = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_pagos_efectivo
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $bancario = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_pagos_bancarios
                WHERE
                    id_proveedor = '{$id_proveedor}' AND estatus != 'Reversado'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fecha_efectivo = isset($efectivo[0]->created_at) ? $efectivo[0]->created_at : null;
            $fecha_bancario = isset($bancario[0]->created_at) ? $bancario[0]->created_at : null;

            if ($fecha_efectivo >= $fecha_bancario) {
                if ($fecha_efectivo) {
                    $fecha_pago = date_create($fecha_efectivo);
                    $fecha_actual = date_create();

                    $interval = date_diff($fecha_pago, $fecha_actual);

                    return $interval->format('%a');
                }
            } else {
                $fecha_pago = date_create($fecha_bancario);
                $fecha_actual = date_create();

                $interval = date_diff($fecha_pago, $fecha_actual);

                return $interval->format('%a');
            }
        }

        function fecha_ultimo_ingreso($id_proveedor)
        {
            $deuda = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_deudas
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fecha_deuda = isset($deuda[0]->created_at) ? $deuda[0]->created_at : null;

            if ($fecha_deuda) {
                $fecha_deuda = date_create($fecha_deuda);
                $fecha_deuda = date_format($fecha_deuda, 'd/m/Y');
                return $fecha_deuda;
            }
        }

        function dias_ultimo_ingreso($id_proveedor)
        {
            $deuda = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_deudas
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fecha_deuda = isset($deuda[0]->created_at) ? $deuda[0]->created_at : null;

            if ($fecha_deuda) {
                $fecha_deuda = date_create($fecha_deuda);
                $fecha_actual = date_create();

                $interval = date_diff($fecha_deuda, $fecha_actual);

                return $interval->format('%a');
            }
        }

        $total_saldo_positivo = 0;
        $total_saldo_negativo = 0;
    @endphp

    <h1 class="h5 text-info">
        <i class="fas fa-info-circle"></i>
        Pizarra de deudas {{ (!isset($_GET['tipo']) || $_GET['tipo'] == 'dolares') ? 'en dólares' : 'en bolívares' }}
    </h1>

    <div class="text-center">
        <a href="?tipo=bolivares" class="btn btn-outline-info btn-sm">Proveedores en bolívares</a>
        <a href="?tipo=dolares" class="btn btn-outline-success btn-sm">Proveedores en dólares</a>
    </div>

    <hr class="row align-items-start col-12">

    <h6 align="center">Proveedores con saldo positivo</h6>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky">Saldo</th>
                <th scope="col" class="CP-sticky">Fecha último pago</th>
                <th scope="col" class="CP-sticky">Días último pago</th>
                <th scope="col" class="CP-sticky">Fecha último ingreso</th>
                <th scope="col" class="CP-sticky">Días último ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positivos as $positivo)
                @php
                    $fechaInicio = date_create();
                    $fechaInicio = date_modify($fechaInicio, '-30day');
                    $fechaInicio = $fechaInicio->format('Y-m-d');

                    $fechaFinal = date('Y-m-d');

                    $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $positivo->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;

                    $total_saldo_positivo = $total_saldo_positivo + (float) $positivo->saldoNoFormateado;
                @endphp

                <tr>
                    <th align="center">{{ $loop->iteration }}</th>
                    <td align="center" class="CP-barrido">
                        <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $positivo->proveedor }}</a>
                    </td>
                    <td align="center">{{ $positivo->saldo }}</td>
                    <td align="center">{{ fecha_ultimo_pago($positivo->id_proveedor) }}</td>
                    <td align="center">{{ dias_ultimo_pago($positivo->id_proveedor) }}</td>
                    <td align="center">{{ fecha_ultimo_ingreso($positivo->id_proveedor) }}</td>
                    <td align="center">{{ dias_ultimo_ingreso($positivo->id_proveedor) }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td align="center"></td>
                <td align="center"><b>Saldo total</b></td>
                <td align="center"><b>{{ number_format($total_saldo_positivo, 2, ',', '.') }}</b></td>
            </tr>
        </tfoot>
    </table>

    <br>

    <h6 align="center">Proveedores con saldo negativo</h6>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky">Saldo</th>
                <th scope="col" class="CP-sticky">Fecha último pago</th>
                <th scope="col" class="CP-sticky">Días último pago</th>
                <th scope="col" class="CP-sticky">Fecha último ingreso</th>
                <th scope="col" class="CP-sticky">Dísa último ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($negativos as $negativo)
                @php
                    $fechaInicio = date_create();
                    $fechaInicio = date_modify($fechaInicio, '-30day');
                    $fechaInicio = $fechaInicio->format('Y-m-d');

                    $fechaFinal = date('Y-m-d');

                    $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $negativo->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;

                    $total_saldo_negativo = $total_saldo_negativo + (float) $negativo->saldoNoFormateado;
                @endphp

                <tr>
                    <th align="center">{{ $loop->iteration}}</th>
                    <td align="center" class="CP-barrido">
                        <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $negativo->proveedor }}</a>
                    </td>
                    <td align="center">{{ $negativo->saldo }}</td>
                    <td align="center">{{ fecha_ultimo_pago($negativo->id_proveedor) }}</td>
                    <td align="center">{{ dias_ultimo_pago($negativo->id_proveedor) }}</td>
                    <td align="center">{{ fecha_ultimo_ingreso($negativo->id_proveedor) }}</td>
                    <td align="center">{{ dias_ultimo_ingreso($negativo->id_proveedor) }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td align="center"></td>
                <td align="center"><b>Saldo total</b></td>
                <td align="center"><b>{{ number_format($total_saldo_negativo, 2, ',', '.') }}</b></td>
            </tr>
        </tfoot>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show')
    </script>

@endsection
