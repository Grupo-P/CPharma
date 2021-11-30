<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class TrasladoRecibirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        return view('pages.trasladoRecibir.index');
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
        session()->push("traslado.$request->sede", ['codigo_barra' => $request->codigo_barra, 'cantidad' => $request->cantidad]);
        return session()->get('traslado');
    }

    public function pdf($sede)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        $pdf = PDF::loadView('pages.trasladoRecibir.pdf', compact('sede'));
        return $pdf->download($sede . '.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($codigo_barra, $sede)
    {
        $traslados = [];

        foreach (session()->get('traslado')[$sede] as $item) {
            if ($item['codigo_barra'] != $codigo_barra) {
                $traslados[] = $item;
            }
        }

        session()->put("traslado.$sede", $traslados);

        if (count(session()->get("traslado.$sede")) == 0) {
            session()->forget("traslado.$sede");
        }

        return redirect('/trasladoRecibir');
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
    public function destroy()
    {
        session()->forget("traslado");
    }
}
