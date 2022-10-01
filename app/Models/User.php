<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Core\Conexion;
use App\Models\Core\Imagen;
use App\Models\Core\Licencia;
use App\Models\Core\Parametro;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'documento','cambio_clave','activo' ,'borrado','user_created_at', 'user_updated_at', 'user_deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function adminlte_image()
    {        
        return ($this->imagenes) ? Storage::url($this->imagenes->url) : '/storage/default.jpg';
    }

    public function adminlte_profile_url()
    {
        return 'user/profile';
    }

    public function adminlte_desc()
    {
        $label = "";
        $roles = $this->getRoleNames();
        foreach ($roles as $role){
            $label .= $role.', ';
        }
        $label = substr($label, 0, -2);
        return $label;
    }        
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName(User::class)->logAll();
    }

    public function imagenes()
    {
        return $this->morphOne(Imagen::class, 'imageable');
    }

    public function parametros()
    {        
        return $this->hasMany(Parametro::class);
    }

    public function licencias()
    {        
        return $this->hasMany(Licencia::class);
    }

    public function conexiones()
    {        
        return $this->hasMany(Conexion::class);
    }
}
