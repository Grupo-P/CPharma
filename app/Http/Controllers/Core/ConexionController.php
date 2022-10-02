<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Conexion;
use App\Models\User;

class ConexionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:core.conexiones.index')->only('index');
        $this->middleware('can:core.conexiones.show')->only('show');
        $this->middleware('can:core.conexiones.create')->only('create');
        $this->middleware('can:core.conexiones.edit')->only('edit');
        $this->middleware('can:core.conexiones.active')->only('active');
        $this->middleware('can:core.conexiones.inactive')->only('inactive');
        $this->middleware('can:core.conexiones.destroy')->only('destroy');
        $this->middleware('can:core.conexiones.restore')->only('restore');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conexiones = Conexion::all();
        return view('core.conexiones.index', compact('conexiones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('core.conexiones.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'siglas' => 'required',
            'driver_db' => 'required',
            'instancia_db' => 'required',
            'usuario' => 'required',
            'clave' => 'required',
            'db_online' => 'required',
            'online' => 'required',
        ]);

        $conexione = Conexion::create($request->all());
        $conexione->ip_address = $request->ip_address;
        $conexione->save();
        session()->flash('message', 'Conexión creada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Conexion $conexion
     * @return \Illuminate\Http\Response
     */
    public function show(Conexion $conexione)
    {        
        $creadoPor = User::find($conexione->user_created_at);
        $actualizadoPor = User::find($conexione->user_updated_at);
        $borradoPor = User::find($conexione->user_deleted_at);
        return view('core.conexiones.show', compact('conexione', 'creadoPor', 'actualizadoPor', 'borradoPor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Conexion $conexion
     * @return \Illuminate\Http\Response
     */
    public function edit(Conexion $conexione)
    {
        return view('core.conexiones.edit', compact('conexione'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Conexion $conexion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conexion $conexione)
    {    
        $request->validate([
            'nombre' => 'required',
            'siglas' => 'required',
            'driver_db' => 'required',
            'instancia_db' => 'required',
            'usuario' => 'required',
            'clave' => 'required',
            'db_online' => 'required',
            'online' => 'required',
        ]);

        $conexione->update($request->all());
        $conexione->ip_address = $request->ip_address;
        $conexione->save();
        session()->flash('message', 'Conexión actualizada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Conexion $conexion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conexion $conexione)
    {
        $conexione->borrado = 1;
        $conexione->deleted_at = date('Y-m-d H:i:s');
        $conexione->user_deleted_at = auth()->user()->id;
        $conexione->save();
        session()->flash('message', 'Conexión borrada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $conexione = Conexion::find($request->id);
        $conexione->borrado = 0;
        $conexione->deleted_at = NULL;
        $conexione->user_deleted_at = NULL;
        $conexione->updated_at = date('Y-m-d H:i:s');
        $conexione->user_updated_at = auth()->user()->id;
        $conexione->save();
        session()->flash('message', 'Conexión restaurada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }

    /**
     * Active the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $conexione = Conexion::find($request->id);
        $conexione->activo = 1;
        $conexione->updated_at = date('Y-m-d H:i:s');
        $conexione->user_updated_at = auth()->user()->id;
        $conexione->save();
        session()->flash('message', 'Conexión activada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }

    /**
     * Inactive the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        $conexione = Conexion::find($request->id);
        $conexione->activo = 0;
        $conexione->updated_at = date('Y-m-d H:i:s');
        $conexione->user_updated_at = auth()->user()->id;
        $conexione->save();
        session()->flash('message', 'Conexión inactivada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }
}
