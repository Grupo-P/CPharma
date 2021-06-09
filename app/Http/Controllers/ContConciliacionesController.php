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

        $bancarios = ContPagoBancario::whereNull('estatus')->get();

        foreach ($bancarios as $bancario) {
            $pagos[$i]['id']           = $bancario->id;
            $pagos[$i]['tipo']         = 'Banco';
            $pagos[$i]['id_proveedor'] = $bancario->id_proveedor;
            $pagos[$i]['proveedor']    = $bancario->proveedor;
            $pagos[$i]['id_banco']     = $bancario->id_banco;
            $pagos[$i]['banco']        = $bancario->banco;
            $pagos[$i]['monto']        = $bancario->monto;
            $pagos[$i]['comentario']   = $bancario->comentario;
            $pagos[$i]['operador']     = $bancario->operador;
            $pagos[$i]['estatus']      = $bancario->estatus;
            $pagos[$i]['deleted_at']   = $bancario->deleted_at;
            $pagos[$i]['created_at']   = $bancario->created_at;
            $pagos[$i]['updated_at']   = $bancario->updated_at;
            $i                         = $i + 1;
        }

        $efectivos = ContPagoEfectivo::whereNull('estatus_conciliaciones')->get();

        foreach ($efectivos as $efectivo) {
            $pagos[$i]['id']                     = $efectivo->id;
            $pagos[$i]['tipo']                   = 'Efectivo';
            $pagos[$i]['id_proveedor']           = $efectivo->id_proveedor;
            $pagos[$i]['proveedor']              = $efectivo->proveedor;
            $pagos[$i]['id_cuenta']              = $efectivo->id_cuenta;
            $pagos[$i]['ingresos']               = $efectivo->ingresos;
            $pagos[$i]['egresos']                = $efectivo->egresos;
            $pagos[$i]['diferido']               = $efectivo->diferido;
            $pagos[$i]['saldo_anterior']         = $efectivo->saldo_anterior;
            $pagos[$i]['saldo_actual']           = $efectivo->saldo_actual;
            $pagos[$i]['diferido_anterior']      = $efectivo->diferido_anterior;
            $pagos[$i]['diferido_actual']        = $efectivo->diferido_actual;
            $pagos[$i]['concepto']               = $efectivo->concepto;
            $pagos[$i]['user']                   = $efectivo->user;
            $pagos[$i]['estatus_conciliaciones'] = $efectivo->estatus_conciliaciones;
            $pagos[$i]['user_up']                = $efectivo->user_up;
            $pagos[$i]['estatus']                = $efectivo->estatus;
            $pagos[$i]['sede']                   = $efectivo->sede;
            $pagos[$i]['deleted_at']             = $efectivo->deleted_at;
            $pagos[$i]['created_at']             = $efectivo->created_at;
            $pagos[$i]['updated_at']             = $efectivo->updated_at;
            $i                                   = $i + 1;
        }

        $pagos = FG_Ordenar_Arreglo($pagos, 'created_at', SORT_DESC);

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
