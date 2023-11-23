@extends('layouts.contabilidad')

@section('title')
    Pizarra de deudas
@endsection

@section('content')
    @php
        include app_path() . '/functions/functions_contabilidad.php';

        function fecha_ultimo_pago($id_proveedor, $prepagado = '')
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

            $fsm = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FM
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fsm = isset($fsm[0]->created_at) ? $fsm[0]->created_at : null;

            if ($pago <= $fsm) {
                $pago = $fsm;
            }

            $fec = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FEC
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fec = isset($fec[0]->created_at) ? $fec[0]->created_at : null;

            if ($pago <= $fec) {
                $pago = $fec;
            }

            // FLF
            $flf = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FLF
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $flf = isset($flf[0]->created_at) ? $flf[0]->created_at : null;

            if ($pago <= $flf) {
                $pago = $flf;
            }

            // PAG
            $pag = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_PAG
                WHERE
                    id_proveedor = '{$id_proveedor}' AND deleted_at IS NULL
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $pag = isset($pag[0]->created_at) ? $pag[0]->created_at : null;

            if ($pago <= $pag) {
                $pago = $pag;
            }

            // Bancario
            $bancario = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_pagos_bancarios
                WHERE
                    id_proveedor = '{$id_proveedor}' AND estatus != 'Reversado' AND estatus != 'Prepagado'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            if ($prepagado) {
                $bancario = DB::select("
                    SELECT
                        DATE(created_at) AS created_at
                    FROM
                        cont_prepagados
                    WHERE
                        id_proveedor = '{$id_proveedor}' AND status = 'Pendiente'
                    ORDER BY
                        created_at DESC
                    LIMIT 1
                ");
            }

            $bancario = isset($bancario[0]->created_at) ? $bancario[0]->created_at : null;

            if ($pago <= $bancario) {
                $pago = $bancario;
            }

            if ($pago) {
                return date_create($pago)->format('Y-m-d');
            }
        }

        function dias_ultimo_pago($id_proveedor, $prepagado = '')
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

            $fll = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FLL
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fll = isset($fll[0]->created_at) ? $fll[0]->created_at : null;

            if ($pago <= $fll) {
                $pago = $fll;
            }

            $fsm = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FM
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fsm = isset($fsm[0]->created_at) ? $fsm[0]->created_at : null;

            if ($pago <= $fsm) {
                $pago = $fsm;
            }

            $fec = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FEC
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fec = isset($fec[0]->created_at) ? $fec[0]->created_at : null;

            if ($pago <= $fec) {
                $pago = $fec;
            }

            // FLF
            $flf = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_FLF
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $flf = isset($flf[0]->created_at) ? $flf[0]->created_at : null;

            if ($pago <= $flf) {
                $pago = $flf;
            }

            // PAG
            $pag = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    {$tabla}_PAG
                WHERE
                    id_proveedor = '{$id_proveedor}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $pag = isset($pag[0]->created_at) ? $pag[0]->created_at : null;

            if ($pago <= $pag) {
                $pago = $pag;
            }

            // Bancario
            $bancario = DB::select("
                SELECT
                    DATE(created_at) AS created_at
                FROM
                    cont_pagos_bancarios
                WHERE
                    id_proveedor = '{$id_proveedor}' AND estatus != 'Reversado' AND estatus != 'Prepagado'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            if ($prepagado) {
                $bancario = DB::select("
                    SELECT
                        DATE(created_at) AS created_at
                    FROM
                        cont_prepagados
                    WHERE
                        id_proveedor = '{$id_proveedor}' AND status = 'Pendiente'
                    ORDER BY
                        created_at DESC
                    LIMIT 1
                ");
            }

            $bancario = isset($bancario[0]->created_at) ? $bancario[0]->created_at : null;

            if ($pago <= $bancario) {
                $pago = $bancario;
            }

            if ($pago) {
                $fecha_pago = date_create($pago);
                $fecha_actual = date_create();

                $interval = date_diff($fecha_pago, $fecha_actual);

                return $interval->format('%a');
            }
        }

        function fecha_ultimo_ingreso($id_proveedor, $prepagado = '')
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

        function dias_ultimo_ingreso($id_proveedor, $prepagado = '')
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

        $total_positivos_bolivares = 0;
        $total_positivos_bolivares_iva = 0;

        $total_positivos_dolares = 0;
        $total_positivos_dolares_iva = 0;

        $total_negativos_bolivares = 0;
        $total_negativos_bolivares_iva = 0;

        $total_negativos_dolares = 0;
        $total_negativos_dolares_iva = 0;

        $total_prepagado_bolivares = 0;
        $total_prepagado_bolivares_iva = 0;

        $total_prepagado_dolares = 0;
        $total_prepagado_dolares_iva = 0;
    @endphp

    <h1 class="h5 text-info">
        <i class="fas fa-info-circle"></i>
        Pizarra de deudas
    </h1>

    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            <td style="width:100%;">
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

    <table class="table table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" colspan="2">Leyenda de colores segun los días de ingreso</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td scope="col" class="bg-white text-dark">
              <ul>
                <li>
                    <span>Las columnas en color blanco o gris representan el rango entre 0 y 9 dias trascurridos (o sin ingreso)</span>
                </li>
              </ul>
              <ul class="bg-success text-white">
                <li>
                    <span>Las columnas en color verde representan el rango entre 10 y 19 dias trascurridos</span>
                </li>
              </ul>
            </td>
            <td>
                <ul class="bg-warning text-white">
                <li>
                    <span>Las columnas en color amarillo representan el rango entre 20 y 29 dias trascurridos</span>
                </li>
              </ul>
              <ul class="bg-danger text-white">
                <li>
                    <span>Las columnas en color rojo representan el rango con mas de 30 dias trascurridos</span>
                </li>
              </ul>
            </td>
          </tr>
        </tbody>
    </table>


    <table class="table table-striped table-borderless col-12" style="margin-bottom: -10px;">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">Proveedores con saldo positivo</th>
            </tr>
        </thead>
    </table>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky" colspan="2">Saldo</th>
                <th scope="col" class="CP-sticky" colspan="2">Saldo IVA</th>
                <th scope="col" class="CP-sticky">Tasa</th>
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

                    if (dias_ultimo_ingreso($positivo->id_proveedor) >= 10 && dias_ultimo_ingreso($positivo->id_proveedor) <= 19) {
                        $fondo = 'bg-success';
                    }
                    elseif (dias_ultimo_ingreso($positivo->id_proveedor) >= 20 && dias_ultimo_ingreso($positivo->id_proveedor) <= 29) {
                        $fondo = 'bg-warning';
                    }
                    elseif (dias_ultimo_ingreso($positivo->id_proveedor) > 30) {
                        $fondo = 'bg-danger';
                    }
                    else {
                        $fondo = '';
                    }

                    if ($positivo->moneda == 'Dólares') {
                        $total_positivos_dolares = (float) $total_positivos_dolares + (float) $positivo->saldoNoFormateado;
                    }

                    if ($positivo->moneda == 'Bolívares') {
                        $total_positivos_bolivares = (float) $total_positivos_bolivares + (float) $positivo->saldoNoFormateado;
                    }

                    if ($positivo->moneda_iva == 'Dólares') {
                        $total_positivos_dolares_iva = (float) $total_positivos_dolares_iva + (float) $positivo->saldoIvaNoFormateado;
                    }

                    if ($positivo->moneda_iva == 'Bolívares') {
                        $total_positivos_bolivares_iva = (float) $total_positivos_bolivares_iva + (float) $positivo->saldoIvaNoFormateado;
                    }
                @endphp

                <tr class="{{ $fondo }}">
                    <th align="center">{{ $loop->iteration }}</th>
                    <td align="center" class="CP-barrido">
                        <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $positivo->proveedor }}</a>
                    </td>
                    <td align="center">{{ simbolo($positivo->moneda) }}</td>
                    <td align="center">{{ $positivo->saldo }}</td>
                    <td align="center">{{ simbolo($positivo->moneda_iva) }}</td>
                    <td align="center">{{ $positivo->saldo_iva }}</td>
                    <td align="center">{{ $positivo->tasa }}</td>
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
                <td align="center"><b>Total bolívares</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_positivos_bolivares, 2, ',', '.') }}</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_positivos_bolivares_iva, 2, ',', '.') }}</b></td>
            </tr>

            <tr>
                <td align="center"></td>
                <td align="center"><b>Total dólares</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_positivos_dolares, 2, ',', '.') }}</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_positivos_dolares_iva, 2, ',', '.') }}</b></td>
        </tfoot>
    </table>

    <br>

    <table class="table table-striped table-borderless col-12" style="margin-bottom: -10px;">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">Proveedores con saldo negativo</th>
            </tr>
        </thead>
    </table>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable2">
        <thead class="thead-dark">
            <tr class="{{ $fondo }}">
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky" colspan="2">Saldo</th>
                <th scope="col" class="CP-sticky" colspan="2">Saldo IVA</th>
                <th scope="col" class="CP-sticky">Tasa</th>
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

                    if (dias_ultimo_ingreso($negativo->id_proveedor) > 0 && dias_ultimo_ingreso($negativo->id_proveedor) <= 10) {
                        $fondo = 'bg-success';
                    }
                    elseif (dias_ultimo_ingreso($negativo->id_proveedor) > 15 && dias_ultimo_ingreso($negativo->id_proveedor) <= 30) {
                        $fondo = 'bg-warning';
                    }
                    elseif (dias_ultimo_ingreso($negativo->id_proveedor) > 30) {
                        $fondo = 'bg-danger';
                    }
                    else {
                        $fondo = '';
                    }

                    if ($negativo->moneda == 'Dólares') {
                        $total_negativos_dolares = (float) $total_negativos_dolares + (float) $negativo->saldoNoFormateado;
                    }

                    if ($negativo->moneda == 'Bolívares') {
                        $total_negativos_bolivares = (float) $total_negativos_bolivares + (float) $negativo->saldoNoFormateado;
                    }

                    if ($negativo->moneda_iva == 'Dólares') {
                        $total_negativos_dolares_iva = (float) $total_negativos_dolares_iva + (float) $negativo->saldoIvaNoFormateado;
                    }

                    if ($negativo->moneda_iva == 'Bolívares') {
                        $total_negativos_bolivares_iva = (float) $total_negativos_bolivares_iva + (float) $negativo->saldoIvaNoFormateado;
                    }
                @endphp

                <tr class="{{ $fondo }}">
                    <th align="center">{{ $loop->iteration}}</th>
                    <td align="center" class="CP-barrido">
                        <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $negativo->proveedor }}</a>
                    </td>
                    <td align="center">{{ simbolo($negativo->moneda) }}</td>
                    <td align="center">{{ $negativo->saldo }}</td>
                    <td align="center">{{ simbolo($negativo->moneda_iva) }}</td>
                    <td align="center">{{ $negativo->saldo_iva }}</td>
                    <td align="center">{{ $negativo->tasa }}</td>
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
                <td align="center"><b>Total bolívares</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_negativos_bolivares, 2, ',', '.') }}</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_negativos_bolivares_iva, 2, ',', '.') }}</b></td>
            </tr>

            <tr>
                <td align="center"></td>
                <td align="center"><b>Total dólares</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_negativos_dolares, 2, ',', '.') }}</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_negativos_dolares_iva, 2, ',', '.') }}</b></td>
        </tfoot>
    </table>

    <br>

    <table class="table table-striped table-borderless col-12" style="margin-bottom: -10px;">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">Prepagados</th>
            </tr>
        </thead>
    </table>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable2">
        <thead class="thead-dark">
            <tr class="{{ $fondo }}">
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky" colspan="2">Monto</th>
                <th scope="col" class="CP-sticky" colspan="2">Monto IVA</th>
                <th scope="col" class="CP-sticky">Fecha último pago</th>
                <th scope="col" class="CP-sticky">Días último pago</th>
                <th scope="col" class="CP-sticky">Fecha último ingreso</th>
                <th scope="col" class="CP-sticky">Días último ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prepagados as $prepagado)
                @php
                    $fechaInicio = date_create();
                    $fechaInicio = date_modify($fechaInicio, '-30day');
                    $fechaInicio = $fechaInicio->format('Y-m-d');

                    $fechaFinal = date('Y-m-d');

                    $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $prepagado->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;

                    if (dias_ultimo_pago($prepagado->id_proveedor) > 0 && dias_ultimo_pago($prepagado->id_proveedor) <= 10) {
                        $fondo = 'bg-success';
                    }
                    elseif (dias_ultimo_pago($prepagado->id_proveedor) > 15 && dias_ultimo_pago($prepagado->id_proveedor) <= 30) {
                        $fondo = 'bg-warning';
                    }
                    elseif (dias_ultimo_pago($prepagado->id_proveedor) > 30) {
                        $fondo = 'bg-danger';
                    }
                    else {
                        $fondo = '';
                    }

                    if ($prepagado->moneda == 'Dólares') {
                        $total_prepagado_dolares = (float) $total_prepagado_dolares + (float) $prepagado->montoNoFormateado;
                    }

                    if ($prepagado->moneda == 'Bolívares') {
                        $total_prepagado_bolivares = (float) $total_prepagado_bolivares + (float) $prepagado->montoNoFormateado;
                    }

                    if ($prepagado->moneda_iva == 'Dólares') {
                        $total_prepagado_dolares_iva = (float) $total_prepagado_dolares_iva + (float) $prepagado->montoIvaNoFormateado;
                    }

                    if ($prepagado->moneda_iva == 'Bolívares') {
                        $total_prepagado_bolivares_iva = (float) $total_prepagado_bolivares_iva + (float) $prepagado->montoIvaNoFormateado;
                    }
                @endphp

                <tr class="{{ $fondo }}">
                    <th align="center">{{ $loop->iteration}}</th>
                    <td align="center" class="CP-barrido">
                        <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $prepagado->proveedor }}</a>
                    </td>
                    <td align="center">{{ simbolo($prepagado->moneda) }}</td>
                    <td align="center">{{ $prepagado->monto }}</td>
                    <td align="center">{{ simbolo($prepagado->moneda_iva) }}</td>
                    <td align="center">{{ $prepagado->monto_iva }}</td>
                    <td align="center">{{ fecha_ultimo_pago($prepagado->id_proveedor, 'prepagado') }}</td>
                    <td align="center">{{ dias_ultimo_pago($prepagado->id_proveedor, 'prepagado') }}</td>
                    <td align="center">{{ fecha_ultimo_ingreso($prepagado->id_proveedor, 'prepagado') }}</td>
                    <td align="center">{{ dias_ultimo_ingreso($prepagado->id_proveedor, 'prepagado') }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td align="center"></td>
                <td align="center"><b>Total bolívares</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_prepagado_bolivares, 2, ',', '.') }}</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_prepagado_bolivares_iva, 2, ',', '.') }}</b></td>
            </tr>

            <tr>
                <td align="center"></td>
                <td align="center"><b>Total dólares</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_prepagado_dolares, 2, ',', '.') }}</b></td>
                <td align="center"></td>
                <td align="center"><b>{{ number_format($total_prepagado_dolares_iva, 2, ',', '.') }}</b></td>
        </tfoot>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show')
    </script>

@endsection
