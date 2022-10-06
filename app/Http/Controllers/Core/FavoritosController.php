<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Favoritos;
use Illuminate\Http\Request;

class FavoritosController extends Controller
{        
    public function gestionar(Request $request)
    {        
        if($request->id != null){
            $favoritos = Favoritos::find($request->id);
            $favoritos->delete();
            session()->flash('message', 'Favorito eliminado con éxito');
        }else{
            $favoritos = Favoritos::create($request->all());
            session()->flash('message', 'Favorito agregado con éxito');
        }                
        
        return redirect()->back();
    }
}
