<?php

namespace compras\Http\Controllers;

use compras\ContPagoBancario;
use compras\ContPagoEfectivo;
use Datetime;
use Illuminate\Http\Request;

class ContReportes extends Controller
{
    public function index()
    {
        return view('pages.contabilidad.reportes.index');
    }

    public function pagos_emitidos(Request $request)
    {
        $fechaInicio = new Datetime($request->get('fechaInicio'));
        $fechaInicio = $fechaInicio->format('d/m/Y');

        $fechaFin = new Datetime($request->get('fechaFin'));
        $fechaFin = $fechaFin->format('d/m/Y');

        $bancario = ContPagoBancario::whereDate('created_at', '>=', $request->get('fechaInicio'))
            ->whereDate('created_at', '<=', $request->get('fechaFin'))
            ->get();

        $efectivo = ContPagoEfectivo::whereDate('created_at', '>=', $request->get('fechaInicio'))
            ->whereDate('created_at', '<=', $request->get('fechaFin'))
            ->get();

        foreach ($bancario as $item) {
            $pagos[] = $item;
        }

        foreach ($efectivo as $item) {
            $pagos[] = $item;
        }

        $pagos = collect($pagos)->sortBy('created_at');

        return view('pages.contabilidad.reportes.pagos-emitidos', compact('fechaInicio', 'fechaFin', 'request', 'pagos'));
    }

    public function movimientos_por_proveedor()
    {
        return view('pages.contabilidad.reportes.movimientos-por-proveedor');
    }

    public function movimientos_bancarios()
    {
        return view('pages.contabilidad.reportes.movimientos-bancarios');
    }

    public function deudas_por_fecha()
    {
        return view('pages.contabilidad.reportes.deudas-por-fecha');
    }

    public function pagos_por_fecha()
    {
        return view('pages.contabilidad.reportes.pagos-por-fecha');
    }
}
