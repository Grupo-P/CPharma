<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Parametro;
use App\Models\User;

class ParametroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('core.parametro.create');
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
            'variable' => 'required|unique:core_parametros',
            'valor' => 'required',
        ]);

        Parametro::create($request->all());
        session()->flash('message', 'Parámetro creado con éxito');

        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }

    /**
     * Display the specified resource.
     *
     * @param Parametro $parametro
     * @return \Illuminate\Http\Response
     */
    public function show(Parametro $parametro)
    {
        $creadoPor = User::find($parametro->user_created_at);
        $actualizadoPor = User::find($parametro->user_updated_at);
        $borradoPor = User::find($parametro->user_deleted_at);
        return view('core.parametro.show', compact('parametro', 'creadoPor', 'actualizadoPor', 'borradoPor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Parametro $parametro
     * @return \Illuminate\Http\Response
     */
    public function edit(Parametro $parametro)
    {
        return view('core.parametro.edit', compact('parametro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Parametro $parametro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Parametro $parametro)
    {
        $request->validate([
            'variable' => 'required',
            'valor' => 'required',
        ]);

        $parametro->update($request->all());
        session()->flash('message', 'Parámetro actualizado con éxito');

        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Parametro $parametro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parametro $parametro)
    {
        $parametro->borrado = 1;
        $parametro->deleted_at = date('Y-m-d H:i:s');
        $parametro->user_deleted_at = auth()->user()->id;
        $parametro->save();
        session()->flash('message', 'Parámetro borrado con éxito');

        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $parametro = Parametro::find($request->id);
        $parametro->borrado = 0;
        $parametro->deleted_at = NULL;
        $parametro->user_deleted_at = NULL;
        $parametro->save();
        session()->flash('message', 'Parámetro restaurado con éxito');

        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }

    /**
     * Active the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $parametro = Parametro::find($request->id);
        $parametro->activo = 1;
        $parametro->save();
        session()->flash('message', 'Parámetro activado con éxito');

        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }

    /**
     * Inactive the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        $parametro = Parametro::find($request->id);
        $parametro->activo = 0;
        $parametro->save();
        session()->flash('message', 'Parámetro inactivado con éxito');

        $parametros = Parametro::all();
        return view('core.parametro.index', compact('parametros'));
    }
}
