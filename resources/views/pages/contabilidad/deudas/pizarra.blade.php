@extends('layouts.contabilidad')

@section('title')
    Pizarra de deudas
@endsection

@section('content')
    @php
        function fecha_ultimo_pago($id_proveedor)
        {
            $tabla = (isset($_GET['tipo']) && $_GET['tipo'] == 'dolares') ? 'cont_pagos_efectivo' : 'cont_pagos_bolivares';

            $pago = null;

            $ftn = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FTN
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $ftn = isset($ftn[0]->created_at) ? $ftn[0]->created_at : null;

            if ($pago <= $ftn) {
                $pago = $ftn;
            }

            $fau = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FAU
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fau = isset($fau[0]->created_at) ? $fau[0]->created_at : null;

            if ($pago <= $fau) {
                $pago = $fau;
            }

            $fll = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FLL
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fll = isset($fll[0]->created_at) ? $fll[0]->created_at : null;

            if ($pago <= $fll) {
                $pago = $fll;
            }

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

            $bancario = isset($bancario[0]->created_at) ? $bancario[0]->created_at : null;

            if ($pago <= $bancario) {
                $pago = $bancario;
            }

            if ($pago) {
                return date_create($pago)->format('Y-m-d');
            }
        }

        function dias_ultimo_pago($id_proveedor)
        {
            $tabla = (isset($_GET['tipo']) && $_GET['tipo'] == 'dolares') ? 'cont_pagos_efectivo' : 'cont_pagos_bolivares';

            $pago = null;

            $ftn = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FTN
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $ftn = isset($ftn[0]->created_at) ? $ftn[0]->created_at : null;

            if ($pago <= $ftn) {
                $pago = $ftn;
            }

            $fau = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FAU
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fau = isset($fau[0]->created_at) ? $fau[0]->created_at : null;

            if ($pago <= $fau) {
                $pago = $fau;
            }

            $dolaresFLL = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_fll
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $dolaresFLL = isset($dolaresFLL[0]->created_at) ? $dolaresFLL[0]->created_at : null;

            if ($pago <= $dolaresFLL) {
                $pago = $dolaresFLL;
            }

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

            if ($pago) {
                $fecha_pago = date_create($pago);
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
                $fecha_deuda = date_format($fecha_deuda, 'Y-m-d');
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

    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            <td style="width:30%;" align="center">
                <a href="?tipo=bolivares" class="btn btn-outline-info btn-sm">Proveedores en bolívares</a>
                <a href="?tipo=dolares" class="btn btn-outline-success btn-sm">Proveedores en dólares</a>
            </td>
            <td style="width:70%;">
                <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                    <div class="input-group-prepend">
                        <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                    aria-hidden="true"></i></span>
                    </div>
                <input class="form-control my-0 py-1 CP-stickyBar" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTables()" autofocus="autofocus">
                </div>
            </td>
        </tr>
    </table>

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

    <table class="table table-striped table-borderless col-12 sortable" id="myTable2">
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
