<?php

namespace compras\Http\Controllers;

use compras\TasaVenta;
use compras\Configuracion;
use compras\VueltoVDC as Vuelto;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client as GuzzleHttp;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException as GuzzleException;
use Illuminate\Validation\Rule;

class VueltoVDCController extends Controller
{
    public function validar(Request $request)
    {
        //return 'exito'; -- para testing

        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';
        $numero_factura = $request->numero_factura;
        $RutaUrl = FG_Mi_Ubicacion();
        //$RutaUrl = 'DBs'; //-- para testing
        $SedeConnection = $RutaUrl;
        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $sql = "
            SELECT TOP 1
                f.Id,
                f.NumeroFactura,
                f.Auditoria_Usuario,
                f.FechaDocumento, 
                f.M_MontoTotalFactura as totalFactura,                   
                cl.CodigoCliente,
                CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                g.Telefono
            FROM
                VenFactura f
                LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
            WHERE
                f.NumeroFactura = '$numero_factura'
            ";

        $result = sqlsrv_query($conn, $sql);
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        $id_factura = $row['Id'];
        $cedulaClienteFactura=$row['CodigoCliente'];
        $nombreClienteFactura=mb_convert_encoding($row['nombre'], 'UTF-8', 'UTF-8');
        $nombreCajeroFactura=$row['Auditoria_Usuario'];
        $totalFactura=number_format($row['totalFactura'],2,'.','');
        $tasa = TasaVenta::where('moneda', 'Dolar')->first()->tasa;                    
        $totalFacturaDolar=number_format($totalFactura/$tasa,2,'.','');
        $sede= $SedeConnection;
        $montoPagado=$request->total_pagado;
        /////////////////////////////////////////////////////////////////
        /* Valida que la caja de la venta sea igual a la caja desde la cual se está haciendo el vuelto. */
        /////////////////////////////////////////////////////////////////
        
        $sql = "
            SELECT TOP 1
                VenCaja.EstacionTrabajo
            FROM
                VenFactura
                    LEFT JOIN VenCaja ON VenFactura.VenCajaId = VenCaja.Id
            WHERE
                VenFactura.NumeroFactura = '$numero_factura' AND
                VenCaja.EstacionTrabajo = '$request->caja'
        ";
        $result = sqlsrv_query($conn, $sql);
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        
        $caja = $row['EstacionTrabajo'];

        if ($caja != $request->caja) {
            $descripcion = 'El vuelto de esta factura solo puede hacerse desde la caja desde donde se realizó la venta.';
            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;
        }

        /////////////////////////////////////////////////////////////////
        /* Valida que la factura sea la última registrada en esa caja */
        /////////////////////////////////////////////////////////////////
        $caja = $request->caja;

        $sql = "
            SELECT TOP 1
                    
                f.Id as idFactura,
                f.NumeroFactura,
                f.VE_NumeroControlImp,
                f.VE_SerialImp,
                f.estadoFactura,
                f.FechaDocumento,
                f.M_MontoTotalFactura,
                vc.Id,
                vc.CodigoCaja,
                vc.EstacionTrabajo
            FROM
                VenFactura f
                    LEFT JOIN VenCaja vc ON f.VenCajaId = vc.Id
            WHERE
                vc.EstacionTrabajo = '$caja'
                ORDER BY f.Id DESC;
        ";
        $result = sqlsrv_query($conn, $sql);
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if ($row['NumeroFactura'] != $request->numero_factura) {
            $descripcion = 'Solo puede hacer un vuelto a la última factura registrada en esta caja.';

            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;
        }
        /////////////////////////////////////////////////////////////////
        /* Valida que el cliente no tenga un vuelto ese mismo dia. */
        /////////////////////////////////////////////////////////////////
        
        $id_factura= $row["idFactura"];
        $fecha=date("Y-m-d");
        
        $vuelto = Vuelto::fecha($fecha, $fecha)->where('id_factura', $id_factura)->where('estatus','=','Aprobado')->get();

        
        if ($vuelto->count()>0){
            $descripcion = 'La factura ya tiene un pago movil registrado el día de hoy.';
                        
            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;
            
        }
        
        /////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////
        /* Valida que el cliente no tenga un vuelto ese mismo dia. */
        /////////////////////////////////////////////////////////////////
        
        $cedula= $request->cedula_cliente;
        $fecha=date("Y-m-d");
            
        $vuelto = Vuelto::fecha($fecha, $fecha)->where('cedula_cliente', $cedula)->where('estatus','=','Aprobado')->get();

        
        if ($vuelto->count()>0){
            $descripcion = 'Este cliente ya tiene un pago movil registrado el día de hoy.';
                        
            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;            
        }
        

        /////////////////////////////////////////////////////////////////
        // validar que la factura no este devuelta
        /////////////////////////////////////////////////////////////////
        $sql2="
                SELECT TOP 1
                    vd.Id,
                    vd.VenFacturaId,
                    vd.ConsecutivoDevolucionSistema,
                    vd.Auditoria_FechaCreacion,
                    vca.EstacionTrabajo,
                    vd.Auditoria_Usuario
                FROM VenDevolucion vd
                LEFT JOIN VenCaja vca ON vd.VenCajaId =  vd.VenCajaId
                WHERE
                                vd.VenFacturaId = ".$id_factura."
            ";
        $result2 = sqlsrv_query($conn, $sql2);
        $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

        if($row2['ConsecutivoDevolucionSistema']>0){
            $descripcion = 'La factura fue anulada, proceso detenido.';
                        
            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;            
        }
        /////////////////////////////////////////////////////////////////
        /* Valida que no hayan pasado más de 10 minutos desde registro de factura. */
        /////////////////////////////////////////////////////////////////

        $fecha_factura = $row['FechaDocumento']->format('Y-m-d H:i:s');
        $fecha_actual = date('Y/m/d H:i:s');

        $minutos = (strtotime($fecha_factura) - strtotime($fecha_actual)) / 60;
        $minutos = abs($minutos);
        $minutos = floor($minutos);
        $minutosMaximos=Configuracion::where('variable','=', 'TiempoMaximoUltFac')->first()->valor;

        if ($minutos >= $minutosMaximos) {
            $descripcion = 'Solo puede hacer un vuelto a una factura registrada hace '.$minutosMaximos.' minutos o menos.';
                        
            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;            
        }

        /////////////////////////////////////////////////////////////////
        /* Valida que el monto máximo del pago móvil sea $20. */
        /////////////////////////////////////////////////////////////////
        $tasa = TasaVenta::where('moneda', 'Dolar')->first()->tasa;
        $monto = $request->monto / $tasa;
        //aqui hay que agregarle la interfaz
        $montoMaximo=Configuracion::where('variable','=', 'MontoMaximoPM$')->first()->valor;

        if ($monto > $montoMaximo) {
            $descripcion = 'Solo puede hacer una operación por un monto de $'.$montoMaximo.' o menos.';
                        
            //registro en historico
            Vuelto::create([
                'fecha_hora' => date('Y-m-d H:i:s'),
                'id_factura' => $id_factura,
                'banco_cliente' => $this->obtener_banco($request->banco_destino),
                'cedula_cliente' => $request->cedula_cliente,
                'telefono_cliente' => $request->telefono_cliente,
                'estatus' => 'Error',
                'caja' => $caja,
                'sede' => $sede,
                'motivo_error' => $descripcion,
                'monto' => $request->monto,
                'montoPagado' => $montoPagado,
                'tasaVenta' => $tasa,
                'cedulaClienteFactura' => $cedulaClienteFactura,
                'nombreClienteFactura' => $nombreClienteFactura,
                'nombreCajeroFactura' => $nombreCajeroFactura,
                'totalFacturaBs' => $totalFactura,
                'totalFacturaDolar' => $totalFacturaDolar
            ]);
            return $descripcion;            
        }

        return 'exito';
    }

    public function procesar(Request $request)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        
        $RutaUrl = FG_Mi_Ubicacion();
        //$RutaUrl = 'DBs';
        
        $SedeConnection = $RutaUrl;
        $sede= $SedeConnection;
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $request->validate([
            "monto" => 'required|regex:/^[0-9.,]+$/u',
            "telefono_cliente" => ['digits_between:11,11','regex:/^[0-9]+$/u','required'],
            "banco_destino" =>'required|numeric|regex:/^[0-9]+$/u',
            "tipo_cliente" => 'required',
            "cedula_cliente" => 'required',
        ]);
        $montoPagado=$request->total_pagado;
        /*$request->monto = 0.01;
        $request->tipo_cliente = 'J';
        $request->telefono_cliente = '04146803196';
        $request->banco_destino = '0105';
        $request->cedula_cliente = '4118013131';*/

            $caja = gethostbyaddr($_SERVER['REMOTE_ADDR']);

            $key = '6d0fa27ce7dbddfc';
            $vi = '260196cb0c40b50b';

            $json['hs'] = 'GFRyhmuM4waaJZlGSeg2NA==';

            $telefono_cliente = substr($request->telefono_cliente, 1);
            $telefono_cliente = '58' . $telefono_cliente;

            $json['dt']['titular'] = $request->cliente;
            $json['dt']['cedula'] = $request->cedula_cliente;
            $json['dt']['nacionalidad'] = $request->tipo_cliente;
            $json['dt']['motivo'] = 'Vuelto de factura';
            $json['dt']['telefono'] = $telefono_cliente;
            $json['dt']['email'] = '';
            $json['dt']['monto'] = $request->monto;
            $json['dt']['banco'] = $request->banco_destino;

            $json['dt'] = json_encode($json['dt']);

            $json['dt'] = openssl_encrypt($json['dt'], 'AES-128-CBC', $key, 0, $vi);

            $numero_factura = $request->numero_factura;

            $sql = "
            SELECT TOP 1
                f.Id,
                f.NumeroFactura,
                f.Auditoria_Usuario,
                f.FechaDocumento, 
                f.M_MontoTotalFactura as totalFactura,                   
                cl.CodigoCliente,
                CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                g.Telefono
            FROM
                VenFactura f
                LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
            WHERE
                f.NumeroFactura = '$numero_factura'
            ";

            $result = sqlsrv_query($conn, $sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

            $id_factura = $row['Id'];
            $cedulaClienteFactura=$row['CodigoCliente'];
            $nombreClienteFactura=mb_convert_encoding($row['nombre'], 'UTF-8', 'UTF-8');
            $nombreCajeroFactura=$row['Auditoria_Usuario'];
            $totalFactura=number_format($row['totalFactura'],2,'.','');

            $cedula_cliente = $request->tipo_cliente . $request->cedula_cliente;
            
           
                try {
                    $client = new GuzzleHttp;
                    $response = $client->request(
                        'POST',
                        'https://cb.venezolano.com/rs/c2x',
                        ['timeout'=>40,'json' => $json]
                    );
                    $tasa = TasaVenta::where('moneda', 'Dolar')->first()->tasa;                    
                    $totalFacturaDolar=number_format($totalFactura/$tasa,2,'.','');
                    //obtener la respuesta del servidor
                    $response = json_decode($response->getBody()->getContents())->response;
                    $response = openssl_decrypt($response, 'AES-128-CBC', $key, 0, $vi);
                    $response = json_decode($response);

                    $confirmacion_banco = $response->referenciaMovimiento;

                    //Registro en caja negra
                    Vuelto::create([
                        'fecha_hora' => date('Y-m-d H:i:s'),
                        'id_factura' => $id_factura,
                        'banco_cliente' => $this->obtener_banco($request->banco_destino),
                        'cedula_cliente' => $cedula_cliente,
                        'telefono_cliente' => $telefono_cliente,
                        'estatus' => 'Aprobado',
                        'confirmacion_banco' => $confirmacion_banco,
                        'caja' => $caja,
                        'sede' => $sede,
                        'monto' => $request->monto,
                        'montoPagado' => $montoPagado,
                        'tasaVenta' => $tasa,
                        'cedulaClienteFactura' => $cedulaClienteFactura,
                        'nombreClienteFactura' => $nombreClienteFactura,
                        'nombreCajeroFactura' => $nombreCajeroFactura,
                        'totalFacturaBs' => $totalFactura,
                        'totalFacturaDolar' => $totalFacturaDolar
                        
                    ]);
                    
                    return json_encode(['resultado' => 'exito', 'referencia' => $confirmacion_banco]);
                }
                catch (GuzzleException $exception) {
                    $response = $exception->getResponse()->getBody()->getContents();
                    $response = json_decode($response);
                    $response = openssl_decrypt($response->response, 'AES-128-CBC', $key, 0, $vi);
                    $response = json_decode($response);

                    
                    //Obtener la respuesta del servidor
                    $descripcion = $response->descripcion ?? $response->mensajeError;

                    //Codigos de error del banco
                    $tasa = TasaVenta::where('moneda', 'Dolar')->first()->tasa;
                    $totalFacturaDolar=number_format($totalFactura/$tasa,2,'.','');

                    //registro en historico
                    Vuelto::create([
                        'fecha_hora' => date('Y-m-d H:i:s'),
                        'id_factura' => $id_factura,
                        'banco_cliente' => $this->obtener_banco($request->banco_destino),
                        'cedula_cliente' => $cedula_cliente,
                        'telefono_cliente' => $telefono_cliente,
                        'estatus' => 'Error',
                        'caja' => $caja,
                        'sede' => $sede,
                        'motivo_error' => $descripcion,
                        'monto' => $request->monto,
                        'tasaVenta' => $tasa,
                        'cedulaClienteFactura' => $cedulaClienteFactura,
                        'nombreClienteFactura' => $nombreClienteFactura,
                        'nombreCajeroFactura' => $nombreCajeroFactura,
                        'totalFacturaBs' => $totalFactura,
                        'totalFacturaDolar' => $totalFacturaDolar
                    ]);

                    return json_encode(['resultado' => 'error', 'error' => $descripcion]);
                }
            
    }

    public function obtener_banco($id)
    {
        $bancos = [
            '0102' => 'Banco de Venezuela',
            '0104' => 'Banco Venezolano de Crédito',
            '0105' => 'Mercantil',
            '0108' => 'Provincial',
            '0114' => 'Bancaribe',
            '0115' => 'Banco Exterior',
            '0116' => 'Banco Occidental de Descuento',
            '0128' => 'Banco Caroní',
            '0134' => 'Banesco',
            '0138' => 'Banco Plaza',
            '0151' => 'Banco Fondo Común',
            '0156' => '100% Banco',
            '0157' => 'Banco del Sur',
            '0163' => 'Banco del Tesoro',
            '0166' => 'Banco Agrícola de Venezuela',
            '0168' => 'Bancrecer',
            '0169' => 'Mi Banco',
            '0171' => 'Banco Activo',
            '0172' => 'Bancamiga',
            '0174' => 'Banplus',
            '0175' => 'Banco Bicentario',
            '0177' => 'Banfanb',
            '0191' => 'Banco Nacional de Crédito'
        ];

        return $bancos[$id];
    }

    public function info()
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

         $RutaUrl = FG_Mi_Ubicacion();
         //$RutaUrl = 'DBs';
         $SedeConnection = $RutaUrl;
        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $caja = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $sql = "
            SELECT
                vv.NumeroFactura AS numero_factura,
                gp.IdentificacionFiscal AS cedula_cliente,
                gp.Telefono AS telefono_cliente,
                (vvp.MontoRecibido - vvp.MontoPagado) AS monto
            FROM
                VenFactura vf
                    LEFT JOIN VenCaja vca ON vf.VenCajaId =  vca.Id
                    LEFT JOIN VenCliente vcl ON vcl.Id = vf.VenClienteId
                    LEFT JOIN GenPersona gp ON vcl.GenPersonaId = gp.Id
                    LEFT JOIN VenVenta vv ON vf.NumeroFactura = vv.NumeroFactura
                    LEFT JOIN VenVentaPago vvp ON vvp.VenVentaId = vv.Id
            WHERE
                vca.EstacionTrabajo = '$caja'
            ORDER BY vf.Id DESC
        ";

        $result = sqlsrv_query($conn, $sql);
        /*dd(sqlsrv_errors());*/
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        return [
            'numero_factura' => $row['numero_factura'],
            'cedula_cliente' => $row['cedula_cliente'],
            'telefono_cliente' => $row['telefono_cliente'],
            'monto' => $row['monto'],
        ];
    }
    
    public function actualizar(Request $request)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        $RutaUrl = FG_Mi_Ubicacion();
        //$RutaUrl = 'DBs';
        $SedeConnection = $RutaUrl;
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $caja = $request->caja; 

        $sql = "
            SELECT
                TOP 1 vvp.VenVentaId,
                f.NumeroFactura,
                ca.EstacionTrabajo,
                cl.CodigoCliente,
                CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                g.Telefono,
                f.M_MontoTotalFactura as totalFactura,
                vvp.MontoPagado,
                vvp.MontoRecibido
            FROM
                VenFactura f
                LEFT JOIN VenCaja vca ON f.VenCajaId =  vca.Id
                LEFT JOIN VenCaja ca ON f.VenCajaId = ca.Id
                LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
                LEFT JOIN VenVentaPago vvp ON vvp.VenVentaId = f.VenVentaId
            WHERE
                vca.EstacionTrabajo = '$caja'
            ORDER BY
                f.Id DESC
        ";

        $result = sqlsrv_query($conn, $sql);
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
           
        $numero_factura = $row['NumeroFactura'];
        $caja = $row['EstacionTrabajo'];        
        $array = explode('-', $row['CodigoCliente']);
        $tipo_cliente = $array[0];
        unset($array[0]);
        $cedula_cliente = implode($array, '');
        $telefono = $row['Telefono'];
        $monto = $row['MontoRecibido'] - $row['MontoPagado'];
        $monto = number_format($monto, 2,'.','');        

        $cliente = mb_convert_encoding($row['nombre'], 'UTF-8', 'UTF-8');
        $total_factura = number_format($row['totalFactura'], 2,'.','');
        $total_factura_pagado = number_format($row['MontoRecibido'], 2,'.','');
        return [
            'numero_factura' => $numero_factura,
            'caja' => $caja,
            'tipo_cliente' => $tipo_cliente,
            'cedula_cliente' => $cedula_cliente,
            'telefono' => $telefono,
            'monto' =>  $monto,
            'cliente' => $cliente,
            'total_factura' => $total_factura,
            'total_factura_pagado' => $total_factura_pagado,
        ];
        
    }
}
