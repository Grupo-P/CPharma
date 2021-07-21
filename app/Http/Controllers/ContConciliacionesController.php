<?php

namespace compras\Http\Controllers;

use compras\ContPagoBancario;
use compras\ContPagoEfectivo;
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

        $bancarios = ContPagoBancario::get();

        foreach ($bancarios as $bancario) {
            $pagos[$i]['id']               = $bancario->id;
            $pagos[$i]['tipo']             = 'Bancario';
            $pagos[$i]['emisor']           = $bancario->banco->nombre_banco;
            $pagos[$i]['nombre_proveedor'] = $bancario->proveedor->nombre_proveedor;
            $pagos[$i]['ci_proveedor']     = $bancario->proveedor->rif_ci;
            $pagos[$i]['monto']            = number_format($bancario->monto, 2, ',', '.');
            $pagos[$i]['operador']         = $bancario->operador;
            $pagos[$i]['fecha']            = date_format(date_create($bancario->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = strtoupper($bancario->estatus);
            $pagos[$i]['concepto']         = $bancario->comentario;
            $i                             = $i + 1;
        }

        $efectivos = ContPagoEfectivo::get();

        foreach ($efectivos as $efectivo) {
            $pagos[$i]['id']               = $efectivo->id;
            $pagos[$i]['tipo']             = 'Efectivo';
            $pagos[$i]['emisor']           = $efectivo->sede;
            $pagos[$i]['nombre_proveedor'] = ($efectivo->proveedor) ? $efectivo->proveedor->nombre_proveedor : '';
            $pagos[$i]['ci_proveedor']     = ($efectivo->proveedor) ? $efectivo->proveedor->rif_ci : '';
            $pagos[$i]['monto']            = ($efectivo->egresos) ? number_format($efectivo->egresos, 2, ',', '.') : number_format($efectivo->diferido, 2, ',', '.');
            $pagos[$i]['operador']         = $efectivo->user_up;
            $pagos[$i]['fecha']            = date_format(date_create($efectivo->created_at), 'd/m/Y h:ia');
            $pagos[$i]['estado']           = strtoupper($efectivo->estatus);
            $pagos[$i]['concepto']         = $efectivo->concepto;
            $i                             = $i + 1;
        }

        $pagos = FG_Ordenar_Arreglo($pagos, 'fecha', SORT_DESC);

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
        //
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
