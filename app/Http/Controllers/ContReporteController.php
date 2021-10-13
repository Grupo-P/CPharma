<?php

namespace compras\Http\Controllers;

use compras\ContBanco;
use compras\ContCuenta;
use compras\ContPagoBancario;
use compras\ContPagoEfectivoFTN;
use compras\ContPagoEfectivoFLL;
use compras\ContPagoEfectivoFAU;
use compras\ContPagoBolivaresFTN;
use compras\ContPagoBolivaresFLL;
use compras\ContPagoBolivaresFAU;
use compras\ContProveedor;
use Datetime;
use DB;
use Illuminate\Http\Request;

class ContReporteController extends Controller
{
    public function index()
    {
        return view('pages.contabilidad.reportes.index');
    }

    public function pagos_por_fecha(Request $request)
    {
        if ($request->get('fechaInicio')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $bancario = ContPagoBancario::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->get();

            $dolaresFTN = ContPagoEfectivoFTN::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $dolaresFAU = ContPagoEfectivoFAU::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $dolaresFLL = ContPagoEfectivoFLL::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $bolivaresFTN = ContPagoBolivaresFTN::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $bolivaresFAU = ContPagoBolivaresFAU::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $bolivaresFLL = ContPagoBolivaresFLL::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $pagos = array_merge($bancario, $dolaresFTN, $dolaresFAU, $dolaresFLL);
            $pagos = collect($pagos)->sortBy('created_at');
        }

        return view('pages.contabilidad.reportes.pagos-por-fecha', compact('fechaInicio', 'fechaFin', 'request', 'pagos'));
    }

    public function movimientos_por_proveedor(Request $request)
    {
        $sqlProveedores = ContProveedor::get();
        $i              = 0;
        $proveedores    = [];

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;

            $i = $i + 1;
        }

        if ($request->get('id_proveedor')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $dolaresFTN = DB::select("
                SELECT
                    cont_pagos_efectivo_ftn.created_at AS fecha,
                    'Pago efectivo dolares FTN' AS tipo,
                    LPAD(cont_pagos_efectivo_ftn.id, 5, '0') AS nro_movimiento,
                    cont_pagos_efectivo_ftn.egresos AS monto,
                    cont_pagos_efectivo_ftn.concepto AS comentario,
                    IF(cont_pagos_efectivo_ftn.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_efectivo_ftn.user AS operador,
                    IF(cont_pagos_efectivo_ftn.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_efectivo_ftn.id_proveedor) AS moneda_proveedor,
                    cont_pagos_efectivo_ftn.tasa AS tasa
                FROM
                    cont_pagos_efectivo_ftn
                WHERE
                    cont_pagos_efectivo_ftn.egresos IS NOT NULL AND
                    cont_pagos_efectivo_ftn.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_efectivo_ftn.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_efectivo_ftn.created_at) <= '{$request->fechaFin}';
            ");

            $dolaresFAU = DB::select("
                SELECT
                    cont_pagos_efectivo_fau.created_at AS fecha,
                    'Pago efectivo dolares FAU' AS tipo,
                    LPAD(cont_pagos_efectivo_fau.id, 5, '0') AS nro_movimiento,
                    cont_pagos_efectivo_fau.egresos AS monto,
                    cont_pagos_efectivo_fau.concepto AS comentario,
                    IF(cont_pagos_efectivo_fau.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_efectivo_fau.user AS operador,
                    IF(cont_pagos_efectivo_fau.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_efectivo_fau.id_proveedor) AS moneda_proveedor,
                    cont_pagos_efectivo_fau.tasa AS tasa
                FROM
                    cont_pagos_efectivo_fau
                WHERE
                    cont_pagos_efectivo_fau.egresos IS NOT NULL AND
                    cont_pagos_efectivo_fau.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_efectivo_fau.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_efectivo_fau.created_at) <= '{$request->fechaFin}';
            ");

            $dolaresFLL = DB::select("
                SELECT
                    cont_pagos_efectivo_fll.created_at AS fecha,
                    'Pago efectivo dolares FAU' AS tipo,
                    LPAD(cont_pagos_efectivo_fll.id, 5, '0') AS nro_movimiento,
                    cont_pagos_efectivo_fll.egresos AS monto,
                    cont_pagos_efectivo_fll.concepto AS comentario,
                    IF(cont_pagos_efectivo_fll.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_efectivo_fll.user AS operador,
                    IF(cont_pagos_efectivo_fll.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_efectivo_fll.id_proveedor) AS moneda_proveedor,
                    cont_pagos_efectivo_fll.tasa AS tasa
                FROM
                    cont_pagos_efectivo_fll
                WHERE
                    cont_pagos_efectivo_fll.egresos IS NOT NULL AND
                    cont_pagos_efectivo_fll.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_efectivo_fll.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_efectivo_fll.created_at) <= '{$request->fechaFin}';
            ");

            $bolivaresFTN = DB::select("
                SELECT
                    cont_pagos_bolivares_ftn.created_at AS fecha,
                    'Pago efectivo bolivares FTN' AS tipo,
                    LPAD(cont_pagos_bolivares_ftn.id, 5, '0') AS nro_movimiento,
                    cont_pagos_bolivares_ftn.egresos AS monto,
                    cont_pagos_bolivares_ftn.concepto AS comentario,
                    IF(cont_pagos_bolivares_ftn.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_bolivares_ftn.user AS operador,
                    IF(cont_pagos_bolivares_ftn.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bolivares_ftn.id_proveedor) AS moneda_proveedor,
                    cont_pagos_bolivares_ftn.tasa AS tasa
                FROM
                    cont_pagos_bolivares_ftn
                WHERE
                    cont_pagos_bolivares_ftn.egresos IS NOT NULL AND
                    cont_pagos_bolivares_ftn.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_bolivares_ftn.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_bolivares_ftn.created_at) <= '{$request->fechaFin}';
            ");

            $bolivaresFAU = DB::select("
                SELECT
                    cont_pagos_bolivares_fau.created_at AS fecha,
                    'Pago efectivo bolivares FAU' AS tipo,
                    LPAD(cont_pagos_bolivares_fau.id, 5, '0') AS nro_movimiento,
                    cont_pagos_bolivares_fau.egresos AS monto,
                    cont_pagos_bolivares_fau.concepto AS comentario,
                    IF(cont_pagos_bolivares_fau.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_bolivares_fau.user AS operador,
                    IF(cont_pagos_bolivares_fau.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bolivares_fau.id_proveedor) AS moneda_proveedor,
                    cont_pagos_bolivares_fau.tasa AS tasa
                FROM
                    cont_pagos_bolivares_fau
                WHERE
                    cont_pagos_bolivares_fau.egresos IS NOT NULL AND
                    cont_pagos_bolivares_fau.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_bolivares_fau.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_bolivares_fau.created_at) <= '{$request->fechaFin}';
            ");

            $bolivaresFLL = DB::select("
                SELECT
                    cont_pagos_bolivares_fll.created_at AS fecha,
                    'Pago efectivo bolivares FAU' AS tipo,
                    LPAD(cont_pagos_bolivares_fll.id, 5, '0') AS nro_movimiento,
                    cont_pagos_bolivares_fll.egresos AS monto,
                    cont_pagos_bolivares_fll.concepto AS comentario,
                    IF(cont_pagos_bolivares_fll.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_bolivares_fll.user AS operador,
                    IF(cont_pagos_bolivares_fll.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bolivares_fll.id_proveedor) AS moneda_proveedor,
                    cont_pagos_bolivares_fll.tasa AS tasa
                FROM
                    cont_pagos_bolivares_fll
                WHERE
                    cont_pagos_bolivares_fll.egresos IS NOT NULL AND
                    cont_pagos_bolivares_fll.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_bolivares_fll.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_bolivares_fll.created_at) <= '{$request->fechaFin}';
            ");

            $bancarios = DB::select("
                SELECT
                    cont_pagos_bancarios.created_at AS fecha,
                    CONCAT('Pago bancario por ', (SELECT cont_bancos.alias_cuenta FROM cont_bancos WHERE cont_bancos.id = cont_pagos_bancarios.id_banco)) AS tipo,
                    '-' AS nro_movimiento,
                    cont_pagos_bancarios.monto,
                    IF(cont_pagos_bancarios.iva != 0, CONCAT('IVA: ', FORMAT(cont_pagos_bancarios.iva, 2, 'de_DE'), '<br>', cont_pagos_bancarios.comentario), cont_pagos_bancarios.comentario) AS comentario,
                    IF(cont_pagos_bancarios.fecha_conciliado, 'Si', 'No') AS conciliacion,
                    cont_pagos_bancarios.operador AS operador,
                    IF(cont_pagos_bancarios.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_bancos.moneda FROM cont_bancos WHERE cont_bancos.id = cont_pagos_bancarios.id_banco) AS moneda_banco,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bancarios.id_proveedor) AS moneda_proveedor,
                    cont_pagos_bancarios.tasa AS tasa
                FROM
                    cont_pagos_bancarios
                WHERE
                    cont_pagos_bancarios.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_bancarios.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_bancarios.created_at) <= '{$request->fechaFin}';
            ");

            $deudas = DB::select("
                SELECT
                    cont_deudas.created_at AS fecha,
                    'Deudas' AS tipo,
                    cont_deudas.numero_documento AS nro_movimiento,
                    cont_deudas.monto,
                    IF(cont_deudas.monto_iva != 0, CONCAT('IVA: ', FORMAT(cont_deudas.monto_iva, 2, 'de_DE')), '') AS comentario,
                    '' AS conciliacion,
                    cont_deudas.usuario_registro AS operador,
                    IF(cont_deudas.deleted_at, 'Desincorporado', 'Activo') AS estado
                FROM
                    cont_deudas
                WHERE
                    cont_deudas.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_deudas.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_deudas.created_at) <= '{$request->fechaFin}';
            ");

            $reclamos = DB::select("
                SELECT
                    cont_reclamos.created_at AS fecha,
                    'Reclamo' AS tipo,
                    cont_reclamos.numero_documento AS nro_movimiento,
                    cont_reclamos.monto,
                    IF(cont_reclamos.monto_iva != 0, CONCAT('IVA: ', FORMAT(cont_reclamos.monto_iva, 2, 'de_DE'), '<br>', cont_reclamos.comentario), cont_reclamos.comentario) AS comentario,
                    '-' AS conciliacion,
                    cont_reclamos.usuario_registro AS operador,
                    IF(cont_reclamos.deleted_at, 'Desincorporado', 'Activo') AS estado

                FROM
                    cont_reclamos
                WHERE
                    cont_reclamos.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_reclamos.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_reclamos.created_at) <= '{$request->fechaFin}';
            ");

            $ajustes = DB::select("
               SELECT
                    cont_ajustes.created_at AS fecha,
                    'Ajuste' AS tipo,
                    '-' AS nro_movimiento,
                    cont_ajustes.monto,
                    cont_ajustes.monto_iva,
                    IF(cont_ajustes.monto_iva != 0, CONCAT('IVA: ', FORMAT(cont_ajustes.monto_iva, 2, 'de_DE'), '<br>', cont_ajustes.comentario), cont_ajustes.comentario) AS comentario,
                    '-' AS conciliacion,
                    cont_ajustes.usuario_registro AS operador,
                    IF(cont_ajustes.deleted_at, 'Desincorporado', 'Activo') AS estado
                FROM
                    cont_ajustes
                WHERE
                    cont_ajustes.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_ajustes.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_ajustes.created_at) <= '{$request->fechaFin}';
            ");

            $movimientos = array_merge($bancarios, $deudas, $reclamos, $ajustes, $bolivaresFLL, $bolivaresFAU, $bolivaresFTN, $dolaresFLL, $dolaresFAU, $dolaresFTN);
            $movimientos = collect($movimientos)->sortByDesc('fecha');

            $proveedor = ContProveedor::find($request->get('id_proveedor'));
        }

        return view('pages.contabilidad.reportes.movimientos-por-proveedor', compact('movimientos', 'fechaInicio', 'fechaFin', 'request', 'proveedores', 'proveedor'));
    }

    public function movimientos_bancarios(Request $request)
    {
        $bancos = ContBanco::orderBy('alias_cuenta', 'ASC')->get();

        if ($request->get('id_banco')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $banco = ContBanco::find($request->id_banco);

            $pagos = ContPagoBancario::where('id_banco', $request->get('id_banco'))
                ->whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->orderByDesc('id')
                ->get();
        }

        return view('pages.contabilidad.reportes.movimientos-bancarios', compact('pagos', 'fechaInicio', 'fechaFin', 'banco', 'bancos', 'request'));
    }

    public function deudas_por_fecha(Request $request)
    {
        if ($request->get('fechaInicio')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $deuda = DB::select("
                SELECT
                    cont_deudas.id,
                    cont_deudas.created_at,
                    'Deuda' AS tipo,
                    (SELECT cont_proveedores.id FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS id_proveedor,
                    (SELECT cont_proveedores.nombre_proveedor FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS moneda_proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS moneda,
                    cont_deudas.monto,
                    cont_deudas.sede,
                    cont_deudas.usuario_registro AS operador,
                    IF(cont_deudas.deleted_at, 'Desincorporado', 'Activo') AS estado
                FROM cont_deudas
                WHERE
                    DATE(cont_deudas.created_at) >= '{$request->get('fechaInicio')}' AND DATE(cont_deudas.created_at) <= '{$request->get('fechaFin')}';
            ");

            $reclamo = DB::select("
                SELECT
                    cont_reclamos.id,
                    cont_reclamos.created_at,
                    'Reclamo' AS tipo,
                    (SELECT cont_proveedores.id FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS id_proveedor,
                    (SELECT cont_proveedores.nombre_proveedor FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS moneda_proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS moneda,
                    cont_reclamos.monto,
                    cont_reclamos.sede,
                    cont_reclamos.usuario_registro AS operador,
                    IF(cont_reclamos.deleted_at, 'Desincorporado', 'Activo') AS estado
                FROM cont_reclamos
                WHERE
                    DATE(cont_reclamos.created_at) >= '{$request->get('fechaInicio')}' AND DATE(cont_reclamos.created_at) <= '{$request->get('fechaFin')}';
            ");

            $items = array_merge($reclamo, $deuda);
            $items = collect($items)->sortBy('created_at');
        }

        return view('pages.contabilidad.reportes.deudas-por-fecha', compact('request', 'fechaInicio', 'fechaFin', 'items'));
    }

    public function reporte_por_cuentas(Request $request)
    {
        error_reporting(0);

        $sqlCuentas = ContCuenta::get();
        $i          = 0;
        $cuentas    = [];

        foreach ($sqlCuentas as $cuenta) {
            $cuentas[$i]['label']  = $cuenta->nombre;
            $cuentas[$i]['value']  = $cuenta->nombre;
            $cuentas[$i]['id']     = $cuenta->id;
            $cuentas[$i]['nombre'] = $cuenta->nombre;

            $i = $i + 1;
        }

        if ($request->get('fechaInicio')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            if ($request->get('id_cuenta')) {
                $efectivo = DB::select("
                    SELECT
                        cont_pagos_efectivo.created_at AS fecha,
                        CONCAT('Pago en efectivo en ', (SELECT sedes.siglas FROM sedes WHERE sedes.razon_social = cont_pagos_efectivo.sede)) AS tipo,
                        IF (cont_pagos_efectivo.egresos, cont_pagos_efectivo.egresos, cont_pagos_efectivo.diferido) AS monto,
                        cont_pagos_efectivo.user AS operador
                    FROM
                        cont_pagos_efectivo
                    WHERE
                        cont_pagos_efectivo.id_proveedor IS NOT NULL AND
                        (SELECT cont_proveedores.plan_cuentas FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_efectivo.id_proveedor) = '{$request->cuenta}' AND
                        DATE(cont_pagos_efectivo.created_at) >= '{$request->fechaInicio}' AND
                        DATE(cont_pagos_efectivo.created_at) <= '{$request->fechaFin}';
                ");

                $bancario = DB::select("
                    SELECT
                        cont_pagos_bancarios.created_at AS fecha,
                        CONCAT('Pago bancario por ', (SELECT cont_bancos.alias_cuenta FROM cont_bancos WHERE cont_bancos.id = cont_pagos_bancarios.id_banco)) AS tipo,
                        cont_pagos_bancarios.monto,
                        cont_pagos_bancarios.operador AS operador
                    FROM
                        cont_pagos_bancarios
                    WHERE
                        (SELECT cont_proveedores.plan_cuentas FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bancarios.id_proveedor) = '{$request->cuenta}' AND
                        DATE(cont_pagos_bancarios.created_at) >= '{$request->fechaInicio}' AND
                        DATE(cont_pagos_bancarios.created_at) <= '{$request->fechaFin}';
                ");
            }

            $items = array_merge($efectivo, $bancario, $efectivo);
            $items = collect($items)->sortBy('created_at');
        }

        return view('pages.contabilidad.reportes.reporte-por-cuentas', compact('items', 'cuentas', 'fechaInicio', 'fechaFin', 'request'));
    }
}
