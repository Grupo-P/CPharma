<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Parametro;

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

        $parametro = Parametro::create($request->all());
        return redirect()->route('core.parametros.edit', compact('parametro'));
    }

    /**
     * Display the specified resource.
     *
     * @param Parametro $parametro
     * @return \Illuminate\Http\Response
     */
    public function show(Parametro $parametro)
    {
        return view('core.parametro.show', compact('parametro'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Parametro $parametro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Parametro $parametro)
    {
        echo "Aqui hago el destroy<br>";
        echo ('<pre>');
        print_r($parametro);
        echo ('</pre>');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $parametro = Parametro::findorfail($request->id);
        echo "Aqui hago el restore<br>";
        echo ('<pre>');
        print_r($parametro);
        echo ('</pre>');
    }

    /**
     * Active the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $parametro = Parametro::findorfail($request->id);
        echo "Aqui hago el active<br>";
        echo ('<pre>');
        print_r($parametro);
        echo ('</pre>');
    }

    /**
     * Inactive the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        $parametro = Parametro::findorfail($request->id);
        echo "Aqui hago el inactive<br>";
        echo ('<pre>');
        print_r($parametro);
        echo ('</pre>');
    }
}
