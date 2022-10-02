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
        ]);

        Conexion::create($request->all());
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
    public function show(Conexion $conexion)
    {
        $creadoPor = User::find($conexion->user_created_at);
        $actualizadoPor = User::find($conexion->user_updated_at);
        $borradoPor = User::find($conexion->user_deleted_at);
        return view('core.conexiones.show', compact('conexion', 'creadoPor', 'actualizadoPor', 'borradoPor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Conexion $conexion
     * @return \Illuminate\Http\Response
     */
    public function edit(Conexion $conexion)
    {
        return view('core.conexiones.edit', compact('conexion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Conexion $conexion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conexion $conexion)
    {
        $request->validate([
            'nombre' => 'required',
            'siglas' => 'required',
            'driver_db' => 'required',
            'instancia_db' => 'required',
            'usuario' => 'required',
            'clave' => 'required',
            'db_online' => 'required',
        ]);

        $conexion->update($request->all());
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
    public function destroy(Conexion $conexion)
    {
        $conexion->borrado = 1;
        $conexion->deleted_at = date('Y-m-d H:i:s');
        $conexion->user_deleted_at = auth()->user()->id;
        $conexion->save();
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
        $conexion = Conexion::find($request->id);
        $conexion->borrado = 0;
        $conexion->deleted_at = NULL;
        $conexion->user_deleted_at = NULL;
        $conexion->save();
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
        $conexion = Conexion::find($request->id);
        $conexion->activo = 1;
        $conexion->save();
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
        $conexion = Conexion::find($request->id);
        $conexion->activo = 0;
        $conexion->save();
        session()->flash('message', 'Conexión inactivada con éxito');

        $conexiones = Conexion::all();
        return redirect()->route('core.conexiones.index', compact('conexiones'));
    }
}
