<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Proveedor;
use compras\User;

class ProveedorController extends Controller
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
        $proveedores =  Proveedor::all();
        return view('pages.proveedor.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.proveedor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $proveedor = new Proveedor();
        $proveedor->nombre = $request->input('nombre');
        $proveedor->apellido = $request->input('apellido');
        $proveedor->telefono = $request->input('telefono');
        $proveedor->correo = $request->input('correo');
        $proveedor->cargo = $request->input('cargo');
        $proveedor->empresa = $request->input('empresa');
        $proveedor->Estatus = 'ACTIVO';
        $proveedor->user = auth()->user()->name;
        $proveedor->save();
        return redirect()->route('proveedor.index')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = Proveedor::find($id);        
        return view('pages.proveedor.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proveedor = Proveedor::find($id);        
        return view('pages.proveedor.edit', compact('proveedor'));
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
        $proveedor = Proveedor::find($id);
        $proveedor->fill($request->all());
        $proveedor->user = auth()->user()->name;
        $proveedor->save();
        return redirect()->route('proveedor.index')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);

         if($proveedor->estatus == 'ACTIVO'){
            $proveedor->estatus = 'INACTIVO';
         }
         else if($proveedor->estatus == 'INACTIVO'){
            $proveedor->estatus = 'ACTIVO';
         }

         $proveedor->user = auth()->user()->name;        
         $proveedor->save();
         return redirect()->route('proveedor.index')->with('Deleted', ' Informacion');
    }
}
