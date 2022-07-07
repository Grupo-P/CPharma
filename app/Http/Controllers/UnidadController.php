<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Unidad;
use compras\Auditoria;

class UnidadController extends Controller
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
        $unidades =  Unidad::all();
        return view('pages.unidad.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $CodBar = $request->input('CodBar');
        $id_articulo = $request->input('Id');

        if($CodBar!="" || $id_articulo!=""){
        
            if($id_articulo!=""){
                $result = Unidad::where('id_articulo',$id_articulo)->get();                   
                if(isset($result[0])){
                    $id = $result[0]->id;
                    $unidad = Unidad::find($id);            
                    return view('pages.unidad.edit', compact('unidad'));
                }
            }
            else if($CodBar!=""){               
                $result = Unidad::where('codigo_barra',$CodBar)->get();
                if(isset($result[0])){
                    $id = $result[0]->id;
                    $unidad = Unidad::find($id);
                    return view('pages.unidad.edit', compact('unidad'));
                }
            }
            
            return view('pages.unidad.create');

        }else{
            return view('pages.unidad.articuloUnidad');
        }        
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
            $unidad = new Unidad();
            $unidad->id_articulo = $request->input('id_articulo');
            $unidad->codigo_interno= $request->input('codigo_interno');
            $unidad->codigo_barra = $request->input('codigo_barra');
            $unidad->divisor = $request->input('divisor');
            $unidad->unidad_minima = $request->input('unidad_minima');
            $unidad->articulo = $request->input('articulo');
            $unidad->estatus = 'ACTIVO';
            $unidad->user = auth()->user()->name;
            
            if($unidad->unidad_minima!="Seleccione..."){
                $unidad->save();
            }else{
                return back()->with('Error Unidad', ' Error Unidad');
            }            

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'UNIDAD MINIMA';
            $Auditoria->registro = $request->input('articulo');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('articuloUnidad')
                ->with('Saved', ' Informacion')
                ->with('unidad', $unidad);
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
       $unidad = Unidad::find($id); 

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'UNIDAD MINIMA';
        $Auditoria->registro = $unidad->articulo;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.unidad.show', compact('unidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unidad = Unidad::find($id);
        return view('pages.unidad.edit', compact('unidad'));
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
            $unidad = Unidad::find($id);
            $unidad->fill($request->all());
            $unidad->user = auth()->user()->name;
            $unidad->save();

            if($unidad->unidad_minima!="Seleccione..."){
                $unidad->save();
            }else{
                return back()->with('Error Unidad', ' Error Unidad');
            }  

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'UNIDAD MINIMA';
            $Auditoria->registro = $unidad->articulo;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('unidad.index')->with('Updated', ' Informacion');
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
       $unidad = Unidad::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'UNIDAD MINIMA';
        $Auditoria->registro = $unidad->articulo;
        $Auditoria->user = auth()->user()->name;

        if($unidad->estatus == 'ACTIVO'){
            $unidad->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($unidad->estatus == 'INACTIVO'){
            $unidad->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $unidad->user = auth()->user()->name;        
        $unidad->save();

        $Auditoria->save();

        return redirect()->route('unidad.index')->with('Deleted', ' Informacion');
    }
}
