<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Proveedor;
use compras\User;
use compras\Empresa;
use compras\Auditoria;

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
        $empresa = Empresa::pluck('nombre','id');
        return view('pages.proveedor.create', compact('empresa'));
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
            $proveedor = new Proveedor();
            $proveedor->nombre = $request->input('nombre');
            $proveedor->apellido = $request->input('apellido');
            $proveedor->telefono = $request->input('telefono');
            $proveedor->correo = $request->input('correo');
            $proveedor->cargo = $request->input('cargo');
            $proveedor->empresa = $request->input('empresa');
            $proveedor->Estatus = 'ACTIVO';
            $proveedor->observacion = $request->input('observacion');
            $proveedor->user = auth()->user()->name;
            $proveedor->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'PROVEEDOR';
            $Auditoria->registro = $request->input('nombre').$request->input('apellido');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('proveedor.index')->with('Saved', ' Informacion');
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
        $proveedor = Proveedor::find($id); 

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'PROVEEDOR';
        $Auditoria->registro = $proveedor->nombre.$proveedor->apellido;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

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
        $empresas = Empresa::pluck('nombre','id');      
        return view('pages.proveedor.edit', compact('proveedor','empresas'));
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
            $proveedor = Proveedor::find($id);
            $proveedor->fill($request->all());
            $proveedor->user = auth()->user()->name;
            $proveedor->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'PROVEEDOR';
            $Auditoria->registro = $proveedor->nombre.$proveedor->apellido;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('proveedor.index')->with('Updated', ' Informacion');
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
        $proveedor = Proveedor::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'PROVEEDOR';
        $Auditoria->registro = $proveedor->nombre.$proveedor->apellido;
        $Auditoria->user = auth()->user()->name;

         if($proveedor->estatus == 'ACTIVO'){
            $proveedor->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($proveedor->estatus == 'INACTIVO'){
            $proveedor->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }

         $proveedor->user = auth()->user()->name;        
         $proveedor->save();

         $Auditoria->save();

         return redirect()->route('proveedor.index')->with('Deleted', ' Informacion');
    }
}
