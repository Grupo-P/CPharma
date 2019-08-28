<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Conexion;
use compras\User;
use compras\Sede;

class ConexionController extends Controller
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
        $conexiones =  Conexion::all();
        return view('pages.conexion.index', compact('conexiones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sedes = Sede::pluck('siglas','id');
        return view('pages.conexion.create', compact('sedes'));
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
            $conexiones = new Conexion();
            $conexiones->siglas = $request->input('siglas');
            $conexiones->instancia = $request->input('instancia');
            $conexiones->base_datos = $request->input('base_datos');   
            $conexiones->usuario = $request->input('usuario'); 
            $conexiones->credencial = $request->input('credencial');       
            $conexiones->user = auth()->user()->name;
            $conexiones->estatus = 'ACTIVO';
            $conexiones->save();
            return redirect()->route('conexion.index')->with('Saved', ' Informacion');
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
        $conexiones = Conexion::find($id); 
        return view('pages.conexion.show', compact('conexiones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $conexiones = Conexion::find($id);
        $sedes = Sede::pluck('siglas','id'); 
        return view('pages.conexion.edit', compact('conexiones','sedes'));
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
            $conexiones = Conexion::find($id);
            $conexiones->fill($request->all());
            $conexiones->user = auth()->user()->name;
            $conexiones->estatus = 'ACTIVO';
            $conexiones->save();
            return redirect()->route('conexion.index')->with('Updated', ' Informacion');
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
        $conexiones = Conexion::find($id);

         if($conexiones->estatus == 'ACTIVO'){
            $conexiones->estatus = 'INACTIVO';
         }
         else if($conexiones->estatus == 'INACTIVO'){
            $conexiones->estatus = 'ACTIVO';
         }

         $conexiones->user = auth()->user()->name;      
         $conexiones->save();
         return redirect()->route('conexion.index')->with('Deleted', ' Informacion');
    }
}
