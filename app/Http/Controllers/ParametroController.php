<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Parametro;

class ParametroController extends Controller
{
    public function __invoke(){        
        $parametros = Parametro::where('activa', '1')->get();
        return view('parametro.index', [
            'parametros' => $parametros
        ]);
    }   
}
