<?php

namespace compras\Http\Controllers;

use compras\ContPagoBancario;
use compras\ContPagoEfectivoFTN;
use compras\ContPagoEfectivoFAU;
use compras\ContPagoEfectivoFLL;
use compras\ContPagoBolivaresFTN;
use compras\ContPagoBolivaresFAU;
use compras\ContPagoBolivaresFLL;
use Illuminate\Http\Request;

class ContConciliacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        include app_path() . '/functions/functions.php';

        $pagos = [];
        $i     = 0;

        $bancarios = ContPagoBancario::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($bancarios as $bancario) {
            $pagos[$i]['id']               = $bancario->id;
            $pagos[$i]['tipo']             = 'Bancario';
            $pagos[$i]['emisor']           = $bancario->banco->nombre_banco;
            $pagos[$i]['nombre_proveedor'] = $bancario->proveedor->nombre_proveedor;
            $pagos[$i]['ci_proveedor']     = $bancario->proveedor->rif_ci;
            $pagos[$i]['monto']            = number_format($bancario->monto, 2, ',', '.');
            $pagos[$i]['operador']         = $bancario->operador;
            $pagos[$i]['fecha']            = date_format(date_create($bancario->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($bancario->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $bancario->comentario;
            $pagos[$i]['clase']            = get_class($bancario);
            $i                             = $i + 1;
        }

        $dolaresFTN = ContPagoEfectivoFTN::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($dolaresFTN as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo dolares FTN';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($efectivo->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $pagos[$i]['clase']            = get_class($efectivo);
            $i                             = $i + 1;
        }

        $dolaresFAU = ContPagoEfectivoFAU::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($dolaresFAU as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo dolares FAU';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($efectivo->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $pagos[$i]['clase']            = get_class($efectivo);
            $i                             = $i + 1;
        }

        $dolaresFLL = ContPagoEfectivoFLL::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($dolaresFLL as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo dolares FAU';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($efectivo->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $pagos[$i]['clase']            = get_class($efectivo);
            $i                             = $i + 1;
        }

        $bolivaresFTN = ContPagoBolivaresFTN::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($bolivaresFTN as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo dolares FTN';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($efectivo->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $pagos[$i]['clase']            = get_class($efectivo);
            $i                             = $i + 1;
        }

        $bolivaresFAU = ContPagoBolivaresFAU::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($bolivaresFAU as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo dolares FAU';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($efectivo->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $pagos[$i]['clase']            = get_class($efectivo);
            $i                             = $i + 1;
        }

        $bolivaresFLL = ContPagoBolivaresFLL::where('fecha_conciliado', '')
            ->orWhereNull('fecha_conciliado')
            ->get();

        foreach ($bolivaresFLL as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo dolares FAU';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = ($efectivo->deleted_at) ? 'Reversado' : 'Pagado';
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $pagos[$i]['clase']            = get_class($efectivo);
            $i                             = $i + 1;
        }

        $pagos = FG_Ordenar_Arreglo($pagos, 'fecha', SORT_ASC);

        return view('pages.contabilidad.conciliaciones.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->pagos as $pago) {
            $model = $pago['clase'];
            $id = $pago['id'];

            $pago = $model::find($id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
