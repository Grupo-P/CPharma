<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Core\Licencia;
use Illuminate\Support\Facades\DB;
use DateTime;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:dashboard')->only('dashboard');
    }

    public function dashboard() {        
        $licencia = $this->card_licence();
        $paramtros = $this->card_parametro();

        $cards = array();
        array_push($cards, $licencia);
        array_push($cards, $paramtros);
        return view('dashboard', compact('cards'));
    }

    private function card_licence(){
        $validate_licence = Licencia::validate_licence();
        $datetime1 = new DateTime(date('Y-m-d'));
        $datetime2 = new DateTime($validate_licence['fecha']);
        $interval = $datetime1->diff($datetime2);
        $diff_dias = $interval->format('%a');
        $interval = ($validate_licence['validate_licence'])? $interval->format('%R%a') : 'Licencia';
        $color = ($validate_licence['validate_licence'])?( ($diff_dias > 5)?'success':'danger' ): 'danger';
        $icono = ($validate_licence['validate_licence'])? 'unlock-alt' : 'lock';

        $licencia = [
            'clases' => 'bg-'.$color,
            'icono' => 'fas fa-'.$icono,
            'contador' => $interval,
            'mensaje' => $validate_licence['mensaje'],
            'ruta' => 'core.licencias.index',
        ];    
        return $licencia;
    } 
    
    private function card_parametro(){
        $consulta = DB::table('core_parametros')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $parametro = [
            'clases' => 'bg-info',
            'icono' => 'fas fa-cogs',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Parametros',
            'ruta' => 'core.parametros.index',
        ];    
        return $parametro;
    }
}
