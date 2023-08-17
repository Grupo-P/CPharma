<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class AuditoriaFsm extends Model
{
    protected $connection = 'fmRecargas';
    protected $table = 'black_boxes';
    protected $fillable = [
        "id_operacion",
        "id_caja",
        "id_usuario",
        "servicio",
        "monto",
        "telefono",
        "contrato",
        "placa",
        "total_usado",
        "total_restante",
        "fecha",
    ];

    //obtener los datos de la operacion
    public function operacion()
    {
        return $this->hasOne(OperacionFsm::class,'id','id_operacion');
    }
    //obtener los datos del cajero
    public function cajero()
    {
        return $this->hasOne(UserFsm::class,'id','id_usuario');
    }

    //obtener los datos de la caja
    public function caja()
    {
        return $this->hasOne(CajaFsm::class,'id','id_caja');
    }
    public static function totalDivisas($cajero){
        $operaciones = OperacionFsm::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_divisas')>0){
            $resultado=$operaciones->SUM('total_divisas');
        }
        else{
            $resultado=0;
        }
        return $resultado;
    }

    public static function totalBolivares($cajero){
        $operaciones = OperacionFsm::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_bolivares')>0){
            $resultado=$operaciones->SUM('total_bolivares');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function totalVueltos($cajero){
        $operaciones = OperacionFsm::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_vuelto')>0){
            $resultado=$operaciones->SUM('total_vuelto');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function servicioNombre($servicio){
        $servicio = ServiciosFsm::where('servicio','=',$servicio)->first();
        return $servicio->nombre;
    }

}
