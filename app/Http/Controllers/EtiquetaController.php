<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Etiqueta;
use compras\User;
use compras\Auditoria;

class EtiquetaController extends Controller
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
        $etiquetas =  
        Etiqueta::orderBy('condicion', 'desc')->
        where('clasificacion', 'PENDIENTE')->get();

        return view('pages.etiqueta.index', compact('etiquetas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\Querys.php');
        include(app_path().'\functions\funciones.php');
        include(app_path().'\functions\reportes.php');

        ValidarEtiquetas();

        return redirect()->action('EtiquetaController@index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // AQUI QUEDE
    {   
        $clasificacion = $request->input('clasificacion');

        if($etiqueta->clasificacion != 'OBLIGATORIO ETIQUETAR'){
            
            $etiqueta->clasificacion = 'OBLIGATORIO ETIQUETAR';
        }

        $etiquetas =  
        Etiqueta::orderBy('condicion', 'desc')->
        where('clasificacion', 'PENDIENTE')->get();
        
        return view('pages.etiqueta.index', compact('etiquetas'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $etiqueta = Etiqueta::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'EDITAR';
        $Auditoria->tabla = 'ETIQUETA';
        $Auditoria->registro = $etiqueta->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        if($etiqueta->clasificacion != 'OBLIGATORIO ETIQUETAR'){
            $etiqueta->condicion = 'CLASIFICADO';
            $etiqueta->clasificacion = 'OBLIGATORIO ETIQUETAR';
        }
        
        $etiqueta->user = auth()->user()->name;        
        $etiqueta->save();

        $Auditoria->save();

        return redirect()->route('etiqueta.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $etiqueta = Etiqueta::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'EDITAR';
        $Auditoria->tabla = 'ETIQUETA';
        $Auditoria->registro = $etiqueta->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        if($etiqueta->clasificacion != 'ETIQUETABLE'){
            $etiqueta->condicion = 'CLASIFICADO';
            $etiqueta->clasificacion = 'ETIQUETABLE';
        }
        
        $etiqueta->user = auth()->user()->name;        
        $etiqueta->save();

        $Auditoria->save();

        return redirect()->route('etiqueta.index');
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
        return redirect()->action('EtiquetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $etiqueta = Etiqueta::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'EDITAR';
        $Auditoria->tabla = 'ETIQUETA';
        $Auditoria->registro = $etiqueta->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        if($etiqueta->clasificacion != 'NO ETIQUETABLE'){
            $etiqueta->condicion = 'CLASIFICADO';
            $etiqueta->clasificacion = 'NO ETIQUETABLE';
        }
        
        $etiqueta->user = auth()->user()->name;        
        $etiqueta->save();

        $Auditoria->save();

        return redirect()->route('etiqueta.index');
    }
}
