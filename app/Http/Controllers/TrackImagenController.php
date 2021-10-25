<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\TrackImagen;
use compras\User;
use compras\Auditoria;

class TrackImagenController extends Controller
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
        $trackimagenes =  TrackImagen::all();
        return view('pages.trackimagen.index', compact('trackimagenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.trackimagen.create');
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
            $trackimagen = new TrackImagen();
            $trackimagen->codigo_barra = $request->input('codigo_barra');
            $trackimagen->url_app = $request->input('url_app');
            $trackimagen->estatus = 'ACTIVO';
            $trackimagen->user = auth()->user()->name;
            $trackimagen->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TRACKIMAGEN';
            $Auditoria->registro = $request->input('codigo_barra');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('trackimagen.index')->with('Saved', ' Informacion');
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
        $trackimagen = TrackImagen::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'TRACKIMAGEN';
        $Auditoria->registro = $trackimagen->codigo_barra;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.trackimagen.show', compact('trackimagen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trackimagen = TrackImagen::find($id);
        return view('pages.trackimagen.edit', compact('trackimagen'));
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
            $trackimagen = TrackImagen::find($id);
            $trackimagen->fill($request->all());
            $trackimagen->user = auth()->user()->name;
            $trackimagen->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'TRACKIMAGEN';
            $Auditoria->registro = $trackimagen->codigo_barra;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('trackimagen.index')->with('Updated', ' Informacion');
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
        $trackimagen = TrackImagen::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'TRACKIMAGEN';
        $Auditoria->registro = $trackimagen->codigo_barra;
        $Auditoria->user = auth()->user()->name;

        if($trackimagen->estatus == 'ACTIVO'){
            $trackimagen->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($trackimagen->estatus == 'INACTIVO'){
            $trackimagen->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $trackimagen->user = auth()->user()->name;
        $trackimagen->save();

        $Auditoria->save();

        return redirect()->route('trackimagen.index')->with('Deleted', ' Informacion');
    }
}
