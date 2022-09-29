<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Request;

class Licencia extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = "core_licencias";    

    protected $fillable = [
        'hash1', 'hash2' ,'hash3', 'hash4' ,'activo' ,'borrado',
        'user_created_at', 'user_updated_at', 'user_deleted_at'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(Licencia::class)->logAll();
    }

    public static function validate_licence(){
        $validate_licence = true;
        $mensaje = 'Licencia valida';

        $licencia = Licencia::find(1);
        $razon_social = Parametro::find(1);
        $rif = Parametro::find(2);
        $fecha_limite = Parametro::find(3);
        $tipo_licencia = Parametro::find(4);

        if(! Hash::check($razon_social->valor,$licencia->hash1) ){
            $validate_licence = false;
            $mensaje = 'La información de la razon social es incorrecta';
        }

        if(! Hash::check($rif->valor,$licencia->hash2) ){
            $validate_licence = false;
            $mensaje = 'La información del RIF es incorrecta';
        }

        if(! Hash::check($fecha_limite->valor,$licencia->hash3) ){
            $validate_licence = false;
            $mensaje = 'La información de la fecha limite es incorrecta';
        }

        if( date('Y-m-d') > $fecha_limite->valor){
            $validate_licence = false;
            $mensaje = 'La fecha limite expiro';
        }

        if(! Hash::check($tipo_licencia->valor,$licencia->hash4) ){
            $validate_licence = false;
            $mensaje = 'La información del tipo de licencia es incorrecta';
        }

        $resultado = array(
            'validate_licence' => $validate_licence,
            'mensaje' => $mensaje,
            'fecha' => $fecha_limite->valor,
        );

        return $resultado;
    }

    public static function validate_route(){
        $rutaActual = Request::route()->getName();

        if( $rutaActual=='dashboard' ||
            $rutaActual=='core.licencias.index' ||
            $rutaActual=='core.licencias.edit' ||
            $rutaActual=='core.parametros.index' ||
            $rutaActual=='core.parametros.edit'
        ){
            return true;
        }
        return false;
    }
}
