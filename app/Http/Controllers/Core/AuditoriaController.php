<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
        $resultados_auditorias = DB::table('activity_log')->orderBy('id', 'desc')->limit(100)->get();
        $auditorias = $this->getAuditorias($resultados_auditorias);
        return view('core.auditorias.index', compact('auditorias'));
    }    

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $resultado_auditoria = DB::table('activity_log')->where('id',$id)->get();
        $auditoria = $this->getAuditorias($resultado_auditoria);
        $attributes = $this->getAttributesArray($resultado_auditoria);
        $old = $this->getOldArray($resultado_auditoria);
        return view('core.auditorias.show', compact('auditoria', 'attributes', 'old'));
    }
    
    private function getAuditorias($resultados_auditorias){
        
        $auditorias = array();
        
        foreach($resultados_auditorias as $result_auditoria){            

            $subject_type_array = explode('\\', $result_auditoria->subject_type);
            $subject_type = array_pop($subject_type_array);

            $causer_type_array = explode('\\', $result_auditoria->causer_type);
            $causer_type = array_pop($causer_type_array);
            
            switch ($result_auditoria->event){
                case 'created';
                    $evento = 'Creado';
                break;
                case 'updated':
                    $evento = 'Actualizado';
                break;
                default:
                    $evento = $result_auditoria->event;
                break;
            }

            $auditoria = [
                'id' => $result_auditoria->id,
                'subject_type' => $subject_type,
                'event' => $evento,
                'causer_type' => ($causer_type != '') ? $causer_type : 'System',
                'updated_at' => $result_auditoria->updated_at,
            ];

            array_push($auditorias, $auditoria);
        }

        return $auditorias;
    }

    private function getAttributesArray($resultado_auditoria){
        $propiedades = json_decode($resultado_auditoria[0]->properties, true);
        if(isset($propiedades['attributes'])){
            return $propiedades['attributes'];
        }
        return false;
    }

    private function getOldArray($resultado_auditoria){
        $propiedades = json_decode($resultado_auditoria[0]->properties, true);
        if(isset($propiedades['old'])){
            return $propiedades['old'];
        }
        return false;
    }
}
