<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class AuditoriaController extends Controller
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
        $auditorias = Activity::all();
        return view('core.auditorias.index', compact('auditorias'));
    }    

    /**
     * Display the specified resource.
     *
     * @param  Activity  $auditoria
     * @return \Illuminate\Http\Response
     */
    public function show(Activity  $auditoria)
    {        
        return view('core.auditorias.show', compact('auditoria'));
    }    
}
