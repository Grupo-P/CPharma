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

    public static function totalDivisas($cajero,$fini,$ffin){
        $operaciones = OperacionKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_user','=',$cajero)->get();

        $resultado = 0;
        foreach($operaciones as $operacion){
            $auditoria = AuditoriaKd73::where('id_operacion','=',$operacion->id)->first();
            if($auditoria != null){
                if($operacion->total_divisas > 0){
                    $resultado= $resultado + $operacion->total_divisas;
                }
            }
        }        
        return $resultado;
    }

    public static function totalBolivares($cajero,$fini,$ffin){
        $operaciones = OperacionKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_user','=',$cajero)->get();

        $resultado = 0;
        foreach($operaciones as $operacion){
            $auditoria = AuditoriaKd73::where('id_operacion','=',$operacion->id)->first();
            if($auditoria != null){
                if($operacion->total_bolivares > 0){
                    $resultado= $resultado + $operacion->total_bolivares;
                }
            }
        }
        return  $resultado;
    }

    public static function totalVueltos($cajero,$fini,$ffin){
        $operaciones = OperacionKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_user','=',$cajero)->get();

        $resultado = 0;
        foreach($operaciones as $operacion){
            $auditoria = AuditoriaKd73::where('id_operacion','=',$operacion->id)->first();
            if($auditoria != null){
                if($operacion->total_vuelto > 0){
                    $resultado= $resultado + $operacion->total_vuelto;
                }
            }
        }
        return  $resultado;
    }

    public static function totalDivisasCaja($caja,$fini,$ffin){
        $operaciones = OperacionKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->get();
        $resultado = 0;
        foreach($operaciones as $operacion){
            $auditoria = AuditoriaKd73::where('id_operacion','=',$operacion->id)->first();
            if($auditoria != null){
                if($operacion->total_divisas > 0){
                    $resultado= $resultado + $operacion->total_divisas;
                }
            }
        }
        return $resultado;
    }

    public static function totalBolivaresCaja($caja,$fini,$ffin){
        $operaciones = OperacionKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->get();

        $resultado = 0;
        foreach($operaciones as $operacion){
            $auditoria = AuditoriaKd73::where('id_operacion','=',$operacion->id)->first();
            if($auditoria != null){
                if($operacion->total_bolivares > 0){
                    $resultado= $resultado + $operacion->total_bolivares;
                }
            }
        }
        return  $resultado;
    }

    public static function totalVueltosCaja($caja,$fini,$ffin){
        $operaciones = OperacionKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->get();

        $resultado = 0;
        foreach($operaciones as $operacion){
            $auditoria = AuditoriaKd73::where('id_operacion','=',$operacion->id)->first();
            if($auditoria != null){
                if($operacion->total_vuelto > 0){
                    $resultado= $resultado + $operacion->total_vuelto;
                }
            }
        }
        return  $resultado;
    }

    public static function servicioNombre($servicio,$subservicio){
        $servicio = ServiciosKd73::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        return $servicio->nombre;
    }
    public static function servicioComision($servicio,$subservicio){
        $servicio = ServiciosKd73::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        return $servicio->comision;
    }

    public static function calculoComision($servicio,$subservicio,$monto){
        $servicio = ServiciosKd73::where('servicio','=',$servicio)->where("subservicio","=",$subservicio)->first();
        $total=$monto*($servicio->comision/100);
        return floatval($total);
    }

    public static function cantidadRecargasCajero($fini,$ffin,$cajero){        
        $cantidad = AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_usuario','=',$cajero)->count();        
        return $cantidad;
    }
    
    public static function cantidadRecargasCaja($fini,$ffin,$caja){        
        $cantidad = AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->where('id_caja','=',$caja)->count();        
        return $cantidad;
    }
}
