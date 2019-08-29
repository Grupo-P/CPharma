<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\TasaVenta;
use compras\User;
use compras\Auditoria;

class TasaVentaController extends Controller {
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
        $tasaVenta = TasaVenta::orderBy('fecha', 'desc')->get();
        return view('pages.tasaVenta.index', compact('tasaVenta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.tasaVenta.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try{
            $tasaVenta = new TasaVenta();
            $tasaVenta->tasa = $request->input('tasa');
            $tasaVenta->moneda = $request->input('moneda');
            $tasaVenta->fecha = $request->input('fecha');
            $tasaVenta->fecha = date('Y-m-d',strtotime($tasaVenta->fecha));
            $tasaVenta->estatus = 'ACTIVO';
            $tasaVenta->user = auth()->user()->name;
            $tasaVenta->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TASA VENTA';
            $Auditoria->registro = $request->input('moneda');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('tasaVenta.index')->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
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
        $tasaVenta = TasaVenta::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'TASA VENTA';
        $Auditoria->registro = $tasaVenta->moneda;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.tasaVenta.show', compact('tasaVenta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $tasaVenta = TasaVenta::find($id);
        return view('pages.tasaVenta.edit', compact('tasaVenta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try{
            $tasaVenta = TasaVenta::find($id);
            $tasaVenta->fill($request->all());
            $tasaVenta->fecha = date('Y-m-d',strtotime($tasaVenta->fecha));
            $tasaVenta->user = auth()->user()->name;
            $tasaVenta->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'TASA VENTA';
            $Auditoria->registro = $tasaVenta->moneda;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('tasaVenta.index')->with('Updated', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $tasaVenta = TasaVenta::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'TASA VENTA';
        $Auditoria->registro = $tasaVenta->moneda;
        $Auditoria->user = auth()->user()->name;       

        if($tasaVenta->estatus == 'ACTIVO'){
            $tasaVenta->estatus = 'INACTIVO';
             $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($tasaVenta->estatus == 'INACTIVO'){
            $tasaVenta->estatus = 'ACTIVO';
             $Auditoria->accion = 'REINCORPORAR';
        }

        $tasaVenta->user = auth()->user()->name;
        $tasaVenta->save();

        $Auditoria->save();

        return redirect()->route('tasaVenta.index')->with('Deleted', ' Informacion');
    }
}
