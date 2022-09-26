<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Licencia;
use App\Models\User;

class LicenciaController extends Controller
{    
    public function __construct()
    {
        $this->middleware('can:core.parametros.index')->only('index');        
        $this->middleware('can:core.parametros.edit')->only('edit');        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $licencias = Licencia::all();
        return view('core.licencias.index', compact('licencias'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Licencia $licencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Licencia $licencia)
    {
        return view('core.licencias.edit', compact('licencia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Licencia $licencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Licencia $licencia)
    {
        $request->validate([            
            'hash1' => 'required',
            'hash2' => 'required',
            'hash3' => 'required',
            'hash4' => 'required',
        ]);

        $licencia->update($request->all());
        session()->flash('message', 'Licencia actualizada con éxito');

        $licencias = Licencia::all();
        return redirect()->route('core.licencias.index', compact('licencias'));
    }   
}
