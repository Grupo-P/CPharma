<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Imagen extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = "core_imagenes";
    protected $modelName = 'Imagenes';

    protected $fillable = [
        'url', 'imageable_id' ,'imageable_type', 'activo' ,'borrado',
        'user_created_at', 'user_updated_at', 'user_deleted_at'
    ];

    public function imageable(){
        return $this->morphTo();
    }
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName($this->modelName)->logAll();
    }    
}
