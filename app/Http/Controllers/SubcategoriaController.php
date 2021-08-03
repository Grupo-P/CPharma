<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Subcategoria;
use compras\Categoria;
use compras\User;
use compras\Auditoria;

class SubcategoriaController extends Controller
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
        $subcategorias =  Subcategoria::all();
        return view('pages.subcategoria.index', compact('subcategorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::pluck('codigo','nombre');
        return view('pages.subcategoria.create', compact('categorias'));
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
            $subcategoria = new Subcategoria();
            $subcategoria->codigo_categoria = $request->input('codigo_categoria');
            $subcategoria->codigo = $request->input('codigo');
            $subcategoria->codigo_app = $request->input('codigo_app');
            $subcategoria->nombre = $request->input('nombre');
            $subcategoria->estatus = 'ACTIVO';
            $subcategoria->user = auth()->user()->name;
            $subcategoria->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'SUBCATEGORIA';
            $Auditoria->registro = $request->input('nombre');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('subcategoria.index')->with('Saved', ' Informacion');
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
        $subcategoria = Subcategoria::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'SUBCATEGORIA';
        $Auditoria->registro = $subcategoria->nombre;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.subcategoria.show', compact('subcategoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subcategoria = Subcategoria::find($id);
        $categorias = Categoria::pluck('codigo','nombre');
        return view('pages.subcategoria.edit', compact('subcategoria','categorias'));
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
            $subcategoria = Subcategoria::find($id);
            $subcategoria->fill($request->all());
            $subcategoria->user = auth()->user()->name;
            $subcategoria->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'SUBCATEGORIA';
            $Auditoria->registro = $subcategoria->nombre;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('subcategoria.index')->with('Updated', ' Informacion');
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
        $subcategoria = Subcategoria::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'SUBCATEGORIA';
        $Auditoria->registro = $subcategoria->nombre;
        $Auditoria->user = auth()->user()->name;

        if($subcategoria->estatus == 'ACTIVO'){
            $subcategoria->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($subcategoria->estatus == 'INACTIVO'){
            $subcategoria->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $subcategoria->user = auth()->user()->name;
        $subcategoria->save();

        $Auditoria->save();

        return redirect()->route('subcategoria.index')->with('Deleted', ' Informacion');
    }
}
