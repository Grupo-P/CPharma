<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class AuditoriaFec extends Model
{
    protected $connection = 'fecRecargas';
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
        return $this->hasOne(OperacionFec::class,'id','id_operacion');
    }
    //obtener los datos del cajero
    public function cajero()
    {
        return $this->hasOne(UserFec::class,'id','id_usuario');
    }

    //obtener los datos de la caja
    public function caja()
    {
        return $this->hasOne(CajaFec::class,'id','id_caja');
    }

    public static function totalDivisas($cajero){
        $operaciones = OperacionFec::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_divisas')>0){
            $resultado=$operaciones->SUM('total_divisas');
        }
        else{
            $resultado=0;
        }
        return $resultado;
    }

    public static function totalBolivares($cajero){
        $operaciones = OperacionFec::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_bolivares')>0){
            $resultado=$operaciones->SUM('total_bolivares');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function totalVueltos($cajero){
        $operaciones = OperacionFec::where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_vuelto')>0){
            $resultado=$operaciones->SUM('total_vuelto');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function servicioNombre($servicio){
        $servicio = ServiciosFec::where('servicio','=',$servicio)->first();
        return $servicio->nombre;
    }

    public static function servicioComision($servicio,$subservicio){
        $servicio = ServiciosFec::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        return $servicio->comision;
    }

    public static function calculoComision($servicio,$subservicio,$monto){
        $servicio = ServiciosFec::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        $total=$monto*($servicio->comision/100);
        return number_format($total,2,',','.');
    }
}
