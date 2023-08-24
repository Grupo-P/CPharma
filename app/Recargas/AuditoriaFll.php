<?php

namespace compras\Recargas;

use Illuminate\Database\Eloquent\Model;

class AuditoriaFll extends Model
{
    protected $connection = 'fllRecargas';
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
        return $this->hasOne(OperacionFll::class,'id','id_operacion');
    }
    //obtener los datos del cajero
    public function cajero()
    {
        return $this->hasOne(UserFll::class,'id','id_usuario');
    }

    //obtener los datos de la caja
    public function caja()
    {
        return $this->hasOne(CajaFll::class,'id','id_caja');
    }

    public static function totalDivisas($cajero,$fini,$ffin){
        $operaciones = OperacionFll::whereBetween('fecha',[ $fini,$ffin])->where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_divisas')>0){
            $resultado=$operaciones->SUM('total_divisas');
        }
        else{
            $resultado=0;
        }
        return $resultado;
    }

    public static function totalBolivares($cajero,$fini,$ffin){
        $operaciones = OperacionFll::whereBetween('fecha',[ $fini,$ffin])->where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_bolivares')>0){
            $resultado=$operaciones->SUM('total_bolivares');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function totalVueltos($cajero,$fini,$ffin){
        $operaciones = OperacionFll::whereBetween('fecha',[ $fini,$ffin])->where('id_user','=',$cajero)->get();
        if($operaciones->SUM('total_vuelto')>0){
            $resultado=$operaciones->SUM('total_vuelto');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function totalDivisasCaja($caja,$fini,$ffin){
        $operaciones = OperacionFll::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->get();
        if($operaciones->SUM('total_divisas')>0){
            $resultado=$operaciones->SUM('total_divisas');
        }
        else{
            $resultado=0;
        }
        return $resultado;
    }

    public static function totalBolivaresCaja($caja,$fini,$ffin){
        $operaciones = OperacionFll::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->get();
        if($operaciones->SUM('total_bolivares')>0){
            $resultado=$operaciones->SUM('total_bolivares');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function totalVueltosCaja($caja,$fini,$ffin){
        $operaciones = OperacionFll::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->get();
        if($operaciones->SUM('total_vuelto')>0){
            $resultado=$operaciones->SUM('total_vuelto');
        }
        else{
            $resultado=0;
        }
        return  $resultado;
    }

    public static function servicioNombre($servicio,$subservicio){
        $servicio = ServiciosFll::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        return $servicio->nombre;
    }
    public static function servicioComision($servicio,$subservicio){
        $servicio = ServiciosFll::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        return $servicio->comision;
    }

    public static function calculoComision($servicio,$subservicio,$monto){
        $servicio = ServiciosFll::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        $total=$monto*($servicio->comision/100);
        return floatval($total);
    }

    public static function cantidadRecargasCajero($fini,$ffin,$cajero){        
        $cantidad = AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->where('id_usuario','=',$cajero)->count();        
        return $cantidad;
    }
    
    public static function cantidadRecargasCaja($fini,$ffin,$caja){        
        $cantidad = AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->count();        
        return $cantidad;
    }
}
