<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\OrdenCompra;
use compras\OrdenCompraDetalle; 
use compras\User;
use compras\Auditoria;

class OrdenCompraDetalleController extends Controller
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
        $usuario = auth()->user()->name;
        $OrdenActiva = 
        OrdenCompra::where('user',$usuario)
        ->where('estatus','ACTIVO')
        ->get();

        if(!empty($OrdenActiva[0]->codigo)) {
           $ordenCompraDetalles =  OrdenCompraDetalle::all()
           ->where('codigo_orden',$OrdenActiva[0]->codigo);
             return view('pages.ordenCompraDetalle.index', compact('ordenCompraDetalles'));
        }
        else if(empty($OrdenActiva[0]->codigo)) {
             return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.ordenCompraDetalle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try{
        $ordenCompraDetalles = new OrdenCompraDetalle();
        $ordenCompraDetalles->codigo_orden = $request->input('codigoOrden');
        $ordenCompraDetalles->id_articulo = $request->input('id_articulo');
        $ordenCompraDetalles->codigo_articulo = $request->input('codigo_articulo');
        $ordenCompraDetalles->codigo_barra = $request->input('codigo_barra');
        $ordenCompraDetalles->descripcion = $request->input('descripcion');

        $ordenCompraDetalles->save();

      return redirect()->route('ordenCompraDetalle.index')->with('Saved', ' Informacion');
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
