<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Categoria;
use compras\User;
use compras\Auditoria;

class CategoriaController extends Controller
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
        $categorias =  Categoria::all();
        return view('pages.categoria.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.categoria.create');
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
            $categoria = new categoria();
            $categoria->codigo = $request->input('codigo'); 
            $categoria->nombre = $request->input('nombre');
            $categoria->estatus = 'ACTIVO';            
            $categoria->user = auth()->user()->name;
            $categoria->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'CATEGORIA';
            $Auditoria->registro = $request->input('nombre');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('categoria.index')->with('Saved', ' Informacion');
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
        $categoria = Categoria::find($id);
        return view('pages.categoria.edit', compact('categoria'));
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
            $categoria = Categoria::find($id);
            $categoria->fill($request->all());
            $categoria->user = auth()->user()->name;            
            $categoria->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'CATEGORIA';
            $Auditoria->registro = $categoria->nombre;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('categoria.index')->with('Updated', ' Informacion');
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
        $categoria = Categoria::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'CATEGORIA';
        $Auditoria->registro = $categoria->nombre;
        $Auditoria->user = auth()->user()->name;

        if($categoria->estatus == 'ACTIVO'){
            $categoria->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($categoria->estatus == 'INACTIVO'){
            $categoria->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $categoria->user = auth()->user()->name;        
        $categoria->save();

        $Auditoria->save();

        return redirect()->route('categoria.index')->with('Deleted', ' Informacion');
    }
}
