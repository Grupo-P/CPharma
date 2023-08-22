<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class AuditoriaKd73 extends Model
{
    protected $connection = 'kd73Recargas';
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
        return $this->hasOne(OperacionKd73::class,'id','id_operacion');
    }
    //obtener los datos del cajero
    public function cajero()
    {
        return $this->hasOne(UserKd73::class,'id','id_usuario');
    }

    //obtener los datos de la caja
    public function caja()
    {
        return $this->hasOne(CajaKd73::class,'id','id_caja');
    }
    public static function totalDivisas($cajero){
        $operaciones = OperacionKd73::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_divisas')>0){
            $resultado=$operaciones->SUM('total_divisas');
        }
        else{
            $resultado=0;
        }
        return $resultado;
    }

    public static function totalBolivares($cajero){
        $operaciones = OperacionKd73::where('id_user','=',$cajero)->get();
        
        if($operaciones->SUM('total_bolivares')>0){
            $resultado=$operaciones->SUM('total_bolivares');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function totalVueltos($cajero){
        $operaciones = OperacionKd73::where('id_user','=',$cajero)->get();
        
        if($operaciones->SUM('total_vuelto')>0){
            $resultado=$operaciones->SUM('total_vuelto');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function servicioNombre($servicio){
        $servicio = ServiciosKd73::where('servicio','=',$servicio)->first();
        return $servicio->nombre;
    }

    public static function servicioComision($servicio,$subservicio){
        $servicio = ServiciosKd73::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        return $servicio->comision;
    }

    public static function calculoComision($servicio,$subservicio,$monto){
        $servicio = ServiciosKd73::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        $total=$monto*($servicio->comision/100);
        return number_format($total,2,',','.');
    }
}
