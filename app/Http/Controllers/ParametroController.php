<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Parametro;

class ParametroController extends Controller
{
    public function __invoke(){        
        $parametros = Parametro::all();
        return view('parametro.index', [
            'parametros' => $parametros
        ]);
    }   
}
