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
        $cards = array();
        array_push($cards, $this->card_licence());
        array_push($cards, $this->card_conexiones());
        array_push($cards, $this->card_parametros());
        array_push($cards, $this->card_permisos());
        array_push($cards, $this->card_roles());
        array_push($cards, $this->card_usuarios());
        array_push($cards, $this->card_health());
        array_push($cards, $this->card_healthHistory());
        array_push($cards, $this->card_auditorias());
        array_push($cards, $this->card_sandbox1());
        array_push($cards, $this->card_sandbox2());
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
            'style' => '',
            'icono' => 'fas fa-'.$icono,
            'contador' => $interval,
            'mensaje' => $validate_licence['mensaje'],
            'ruta' => 'core.licencias.index',
        ];    
        return $licencia;
    } 
    
    private function card_conexiones(){
        $consulta = DB::table('core_conexiones')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-info',
            'style' => '',
            'icono' => 'fas fa-network-wired',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Conexiones',
            'ruta' => 'core.conexiones.index',
        ];    
        return $array;
    }

    private function card_parametros(){
        $consulta = DB::table('core_parametros')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-warning',
            'style' => 'color:white !important',
            'icono' => 'fas fa-cogs',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Parametros',
            'ruta' => 'core.parametros.index',
        ];    
        return $array;
    }

    private function card_permisos(){
        $consulta = DB::table('permissions')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-danger',
            'style' => '',
            'icono' => 'fas fa-user-lock',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Permisos',
            'ruta' => 'core.permisos.index',
        ];    
        return $array;
    }

    private function card_roles(){
        $consulta = DB::table('roles')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-secondary',
            'style' => '',
            'icono' => 'fas fa-users-cog',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Roles',
            'ruta' => 'core.roles.index',
        ];    
        return $array;
    }

    private function card_usuarios(){
        $consulta = DB::table('users')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-dark',
            'style' => '',
            'icono' => 'fas fa-users',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Usuarios',
            'ruta' => 'core.usuarios.index',
        ];    
        return $array;
    }

    private function card_health(){        
        $array = [
            'clases' => 'bg-success',
            'style' => '',
            'icono' => 'fas fa-heartbeat',
            'contador' => 'Ver',
            'mensaje' => 'Estado del servidor',
            'ruta' => 'core.health',
        ];    
        return $array;
    }

    private function card_healthHistory(){
        $consulta = DB::table('health_check_result_history_items')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-info',
            'style' => '',
            'icono' => 'fas fa-file-medical-alt',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Histórico de estados',
            'ruta' => 'core.healthHistory',
        ];    
        return $array;
    }

    private function card_auditorias(){
        $consulta = DB::table('activity_log')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        
        $array = [
            'clases' => 'bg-warning',
            'style' => 'color:white !important',
            'icono' => 'fas fa-search',
            'contador' => $result[0]['cuenta'],
            'mensaje' => 'Auditoría',
            'ruta' => 'core.auditorias.index',
        ];    
        return $array;
    }

    private function card_sandbox1(){        
        $array = [
            'clases' => 'bg-danger',
            'style' => '',
            'icono' => 'fas fa-gamepad',
            'contador' => '1',
            'mensaje' => 'Sandbox',
            'ruta' => 'core.demo.sandbox1',
        ];    
        return $array;
    }

    private function card_sandbox2(){        
        $array = [
            'clases' => 'bg-secondary',
            'style' => '',
            'icono' => 'fas fa-gamepad',
            'contador' => '2',
            'mensaje' => 'Sandbox',
            'ruta' => 'core.demo.sandbox2',
        ];    
        return $array;
    }
}
