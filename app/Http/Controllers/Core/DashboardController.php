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

    private function card_data($clases, $style, $icono, $contador, $mensaje, $ruta){
        $array = [
            'clases' => $clases,
            'style' => $style,
            'icono' => $icono,
            'contador' => $contador,
            'mensaje' => $mensaje,
            'ruta' => $ruta,
        ];
        return $array;
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
        return $this->card_data('bg-'.$color, '', 'fas fa-'.$icono, $interval, $validate_licence['mensaje'], 'core.licencias.index');
    } 
    
    private function card_conexiones(){
        $consulta = DB::table('core_conexiones')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-info', '', 'fas fa-network-wired', $result[0]['cuenta'], 'Conexiones', 'core.conexiones.index');
    }

    private function card_parametros(){
        $consulta = DB::table('core_parametros')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-warning', 'color:white !important', 'fas fa-cogs', $result[0]['cuenta'], 'Parametros', 'core.parametros.index');
    }

    private function card_permisos(){
        $consulta = DB::table('permissions')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-danger', '', 'fas fa-user-lock', $result[0]['cuenta'], 'Permisos', 'core.permisos.index');
    }

    private function card_roles(){
        $consulta = DB::table('roles')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-secondary', '', 'fas fa-users-cog', $result[0]['cuenta'], 'Roles', 'core.roles.index');
    }

    private function card_usuarios(){
        $consulta = DB::table('users')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-dark', '', 'fas fa-users', $result[0]['cuenta'], 'Usuarios', 'core.usuarios.index');
    }

    private function card_health(){
        return $this->card_data('bg-success', '', 'fas fa-heartbeat', 'Ver', 'Estado del servidor', 'core.health');
    }

    private function card_healthHistory(){
        $consulta = DB::table('health_check_result_history_items')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-info', '', 'fas fa-file-medical-alt', $result[0]['cuenta'], 'Histórico de estados', 'core.healthHistory');
    }

    private function card_auditorias(){
        $consulta = DB::table('activity_log')->select(DB::raw('count(*) as cuenta '))->get();
        $result = json_decode($consulta,true);
        return $this->card_data('bg-warning', 'color:white !important', 'fas fa-search', $result[0]['cuenta'], 'Auditoría', 'core.auditorias.index');
    }

    private function card_sandbox1(){
        return $this->card_data('bg-danger', '', 'fas fa-gamepad', '1', 'Sandbox', 'core.demo.sandbox1');
    }

    private function card_sandbox2(){
        return $this->card_data('bg-secondary', '', 'fas fa-gamepad', '2', 'Sandbox', 'core.demo.sandbox2');
    }
}
