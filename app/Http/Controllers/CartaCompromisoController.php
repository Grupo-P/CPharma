<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\CartaCompromiso;
use compras\User;

class CartaCompromisoController extends Controller {
    /**
     * Create a new controller instance with auth.
     *
     * 
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('pages.cartaCompromiso.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.cartaCompromiso.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $cartaCompromiso = new CartaCompromiso();
            $cartaCompromiso->articulo = $request->input('articulo');
            $cartaCompromiso->lote = $request->input('lote');
            $cartaCompromiso->fecha_vencimiento = $request->input('fecha_vencimiento');
            $cartaCompromiso->fecha_vencimiento = date('Y-m-d',strtotime($cartaCompromiso->fecha_vencimiento));
            $cartaCompromiso->proveedor = $request->input('proveedor');
            $cartaCompromiso->fecha_recepcion = $request->input('fecha_recepcion');
            $cartaCompromiso->fecha_recepcion = date('Y-m-d',strtotime($cartaCompromiso->fecha_recepcion));
            $cartaCompromiso->fecha_tope = $request->input('fecha_tope');
            $cartaCompromiso->fecha_tope = date('Y-m-d',strtotime($cartaCompromiso->fecha_tope));
            $cartaCompromiso->causa = $request->input('causa');
            $cartaCompromiso->nota = $request->input('nota');
            $cartaCompromiso->estatus = $request->input('estatus');
            $cartaCompromiso->user = auth()->user()->name;
            $cartaCompromiso->save();
            return redirect()->route('cartaCompromiso.index')->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
