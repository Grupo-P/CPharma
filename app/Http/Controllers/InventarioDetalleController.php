<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Inventario;
use compras\InventarioDetalle;
use compras\User;
use compras\Auditoria;

class InventarioDetalleController extends Controller
{
    /**
     * Create a new controller instance with auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Se arma desde el inventario edit
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Se crea desde el inventario create
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Se guarda desde el inventario create
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // No pposeee show no es necesario
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventarioDetalle = InventarioDetalle::find($id);
        return view('pages.inventarioDetalle.edit', compact('inventarioDetalle'));
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
        try{
            $InventarioDetalle = InventarioDetalle::find($id);
            $InventarioDetalle->conteo = $request->input('conteo');
            $InventarioDetalle->re_conteo = $request->input('re_conteo');

            if($request->input('conteo')!=NULL && $InventarioDetalle->operador_conteo==""){
                $InventarioDetalle->operador_conteo = auth()->user()->name;
                $InventarioDetalle->fecha_conteo = date('Y-m-d H:i:m');
            }

            if($request->input('re_conteo')!=NULL && $InventarioDetalle->operador_reconteo==""){
                $InventarioDetalle->operador_reconteo = auth()->user()->name;
                $InventarioDetalle->fecha_reconteo = date('Y-m-d H:i:m');
            }
            
            $InventarioDetalle->save();

            $inventarioDetalle =  InventarioDetalle::where('codigo_conteo',$InventarioDetalle->codigo_conteo)->get();
            return view('pages.inventarioDetalle.index', compact('inventarioDetalle'));
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
    public function destroy($id)
    {
        // No utiliza destroy no es necesario
    }
}
