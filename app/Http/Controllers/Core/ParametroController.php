<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Parametro;

class ParametroController extends Controller
{
    public function __invoke(){        
        $parametros = Parametro::all();
        return view('parametro.index', [
            'parametros' => $parametros
        ]);
    }   
}
