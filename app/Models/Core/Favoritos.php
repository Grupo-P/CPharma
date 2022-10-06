<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Favoritos extends Model
{
    use HasFactory;

    protected $table = "core_favoritos";

    protected $fillable = [
        'nombre', 'ruta' ,'user_favoritos', 'activo' ,'borrado',
        'user_created_at', 'user_updated_at', 'user_deleted_at'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }    

    public static function validar_favorito($ruta = null, $user_favoritos = null)
    {
        if($ruta!==null && $user_favoritos!==null){
            $favorito = json_decode(DB::table('core_favoritos')->where('ruta', $ruta)->where('user_favoritos', $user_favoritos)->get(),true);            
            if(!empty($favorito)){
                return $favorito;
            }
        }
        return false;
    }
}
