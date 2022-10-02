<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Conexion extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = "core_conexiones";

    protected $fillable = ['nombre', 'nombre_mostrar', 'siglas', 'ip_address',
    'driver_db', 'instancia_db', 'usuario', 'clave', 'db_online', 'db_offline','online' ,
    'activo' ,'borrado', 'user_created_at', 'user_updated_at', 'user_deleted_at'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(Conexion::class)->logAll();
    }
}
