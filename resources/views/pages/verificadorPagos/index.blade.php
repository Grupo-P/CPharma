@extends('layouts.contabilidad')

@section('title')
  Verificador de pagos
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-check"></i>
        Verificador de pagos
    </h1>

    @if(!request()->fecha)

        <form class="m-5 p-5" target="_blank">
            <div class="row text-center">
                <div class="col">
                    <label for="fecha">Fecha</label>
                </div>

                <div class="col">
                    <input min="2022-05-30" type="date" name="fecha" required class="form-control">
                </div>

                <div class="col">
                    <label for="sede">Sede</label>
                </div>

                @if(Auth::user()->sede == 'GRUPO P, C.A')
                    <div class="col">
                        <select name="sede" class="form-control">
                            <option value=""></option>
                            <option value="FTN">FTN</option>
                            <option value="FAU">FAU</option>
                            <option value="FLL">FLL</option>
                            <option value="KDI">KDI</option>
                            <option value="FSM">FSM</option>
                            <option value="FEC">FEC</option>
                            <option value="KD73">KD73</option>
                        </select>
                    </div>
                @endif

                <div class="col">
                    <button type="submit" class="btn btn-outline-success">Buscar</button>
                </div>
            </div>
        </form>


    @else

        @php

            include app_path() . '\functions\functions.php';

            $inicio = new DateTime();

            $sede = isset($_GET['sede']) ? $_GET['sede'] : Auth::user()->sede;

            if ($sede == 'FTN' || $sede == 'FARMACIA TIERRA NEGRA, C.A.') {
                $username = 'pagostierranegra2@hotmail.com';
                $password = 'Glibenclamida*84';
                $remitente = 'pagomovilftn@gmail.com';
            }

            if ($sede == 'FAU' || $sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.') {
                $username = 'pagosuniversidad2@hotmail.com';
                $password = 'pagosfarmaciaavenidauniversidad';
                $remitente = 'pagomovilgp@gmail.com';
            }

            if ($sede == 'FSM' || $sede == 'FARMACIA MILLENNIUM 2000, C.A') {
                $username = 'pagosmillennium@hotmail.com';
                $password = 'Glibenclamida*84';
                $remitente = 'pagosfll677@gmail.com';
            }

            if ($sede == 'FLL' || $sede == 'FARMACIA LA LAGO,C.A.') {
                $username = 'pagoslalago@hotmail.com';
                $password = 'Glibenclamida*84';
                $remitente = 'pagosfll677@gmail.com';
            }

            if ($sede == 'KDI' || $sede == 'KD73' || $sede == 'FARMACIAS KD EXPRESS, C.A.') {
                $username = 'pagoskdi@hotmail.com';
                $password = 'GJpc2017.';
                $remitente = 'pagomovilkd@gmail.com';
                $id = 5;
            }

            if ($sede == 'FEC' || $sede == 'FARMACIA EL CALLEJON, C.A.') {
                $username = 'pagoselcallejon@hotmail.com';
                $password = 'atorvastati@.YA';
                $remitente = 'pagomovilfec@gmail.com';
            }

            $mailbox = '{outlook.office365.com:993/imap/ssl}';
            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $conn = @imap_open($mailbox, $username, $password) or die (@imap_last_error());

            $search = @imap_search($conn, 'ON "'.$fecha.'"');

            $search = is_iterable($search) ? $search : [];

            function fix_text_subject($str)
            {
                $asunto = '';
                $array = @imap_mime_header_decode($str);

                foreach ($array as $object) {
                    $asunto .= utf8_encode(rtrim($object->text, 't'));
                }

                return utf8_decode($asunto);
            }

            $pagos = [];
            $i = 0;

            foreach ($search as $email) {
                $overview = @imap_fetch_overview($conn, $email);

                $header = @imap_header($conn, $email);

                $fechaInstancia = new DateTime($header->date);
                $fechaInstancia->modify('-4hour');
                $fecha = $fechaInstancia->format('d/m/Y h:i A');
                $fechaSinFormato = $fechaInstancia->format('Y-m-d H:i:s');

                $arrayFecha = explode(' ', $fecha);

                if ($arrayFecha[0] != date_format(date_create(request()->fecha), 'd/m/Y')) {
                    continue;
                }

                foreach ($overview as $item) {
                    if (isset($item->subject)) {
                        $asunto = fix_text_subject($item->subject);
                    }

                    // Bank of America

                    if (strpos($asunto, ' sent you ') && $header->fromaddress == 'Bank of America <customerservice@ealerts.bankofamerica.com>') {
                        $arrayAsunto = explode(' sent you ', $asunto);

                        $body = @imap_qprint(@imap_body($conn, $email));

                        $inicioComentario = strpos($body, '<!-- Zone2 - Begins-->');
                        $finComentario = strpos($body, '<!-- Zone2 - Ends-->');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);

                        $pagos[$i]['tipo'] = 'Zelle BOFA';
                        $pagos[$i]['enviado_por'] = $arrayAsunto[0];
                        $pagos[$i]['monto'] = $arrayAsunto[1];
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if (strpos($asunto, ' le ha enviado ') && $header->fromaddress == 'Bank of America <customerservice@ealerts.bankofamerica.com>') {
                        $arrayAsunto = explode(' sent you ', $asunto);

                        $body = @imap_qprint(@imap_body($conn, $email));


                        $inicioComentario = strpos($body, '<!-- Zone2 - Begins-->');
                        $finComentario = strpos($body, '<!-- Zone2 - Ends-->');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);

                        $pagos[$i]['tipo'] = 'Zelle BOFA';
                        $pagos[$i]['enviado_por'] = $arrayAsunto[0];
                        $pagos[$i]['monto'] = $arrayAsunto[1];
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    // Mercantil

                    if (strpos($asunto, 'SMS') && $header->fromaddress == $remitente) {

                        $body = @imap_fetchbody($conn, $email, 2);

                        if (strpos($body, 'Tpago') && strpos($body, '- 500')) {

                            $inicioEnviadoPor = (strpos($body, 'celular')) + 7;
                            $substr = substr($body, $inicioEnviadoPor);
                            $finEnviadoPor = strpos($substr, '.');
                            $enviadoPor = substr($substr, 0, $finEnviadoPor);
                            $enviadoPor = str_replace(['=', ' '], ['', ''], $enviadoPor);

                            $inicioMonto = (strpos($body, 'Tpago por')) + 9;
                            $substr = substr($body, $inicioMonto);
                            $finMonto = strpos($substr, ' desde ');
                            $monto = substr($substr, 0, $finMonto);

                            $inicioReferencia = (strpos($body, 'a:')) + 2;
                            $substr = substr($body, $inicioReferencia);
                            $finReferencia = strpos($substr, ',');
                            $referencia = substr($substr, 0, $finReferencia);

                            $comentario = "Referencia: $referencia";

                            $pagos[$i]['tipo'] = 'Pago móvil Mercantil';
                            $pagos[$i]['enviado_por'] = $enviadoPor;
                            $pagos[$i]['monto'] = $monto;
                            $pagos[$i]['fecha'] = $fecha;
                            $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                            $pagos[$i]['comentario'] = $comentario;
                            $pagos[$i]['referencia'] = $referencia;

                            $i++;
                        }

                    }
                    // BNC

                    if (strpos($asunto, 'SMS') && $header->fromaddress == $remitente) {

                        $body = @imap_fetchbody($conn, $email, 2);

                        if (strpos($body, 'Pago Movil') && strpos($body, '- 2620')) {

                            $inicioEnviadoPor =(strpos($body, 'Telf.')) + 5;                
                            $substr = substr($body, $inicioEnviadoPor);                
                            $finEnviadoPor = strpos($substr, ' ');                
                            $enviadoPor = substr($substr, 0, $finEnviadoPor);                
                            $enviadoPor = str_replace(['=', ' '], ['', ''], $enviadoPor);
                            

                            $inicioMonto = (strpos($body, 'Pago Movil Recibido')) + 20;                
                            $substr = substr($body, $inicioMonto);                
                            $finMonto = strpos($substr, ' Telf.');
                            $monto = substr($substr, 0, $finMonto);
                        
                            $inicioReferencia = (strpos($body, 'ef:')) + 3;                
                            $substr = substr($body, $inicioReferencia);
                            
                            $finReferencia = strpos($substr, ' Llamar');
                            $referencia = substr($substr, 0, $finReferencia);                

                            $comentario = "Referencia: $referencia";

                            $decimales = explode('.', (string) $monto);
                            
                            $decimales = $decimales[1];
                        
                            $pagos[$i]['tipo'] = 'Pago móvil BNC';
                            $pagos[$i]['enviado_por'] = $enviadoPor;
                            $pagos[$i]['monto'] = $monto;
                            $pagos[$i]['fecha'] = $fecha;
                            $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                            $pagos[$i]['comentario'] = $comentario;
                            $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                            $pagos[$i]['referencia'] = $referencia;

                            $i++;
                        }

                    }
                }
            }

            $conn = @imap_open($mailbox, 'pagosgedaca@hotmail.com', 'Cpharma20.') or die (@imap_last_error());

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = @imap_search($conn, 'ON "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = @imap_fetch_overview($conn, $email);

                $header = @imap_header($conn, $email);

                $fechaInstancia = new DateTime($header->date);
                $fechaInstancia->modify('-4hour');
                $fecha = $fechaInstancia->format('d/m/Y h:i A');
                $fechaSinFormato = $fechaInstancia->format('Y-m-d H:i:s');

                $arrayFecha = explode(' ', $fecha);

                if ($arrayFecha[0] != date_format(date_create(request()->fecha), 'd/m/Y')) {
                    continue;
                }

                foreach ($overview as $item) {
                    // Paypal

                    if (isset($item->subject)) {
                        $asunto = fix_text_subject($item->subject);
                    }

                    if ($asunto == 'Ha recibido un pago' && $item->from == '"service@paypal.com" <service@paypal.com>') {
                        $body = @imap_qprint(@imap_body($conn, $email));

                        $inicioEnviadoPor = strpos($body, 'Gedaca holding corp:');
                        $finEnviadoPor = strpos($body, ' le ha enviado');
                        $enviadoPor = substr($body, $inicioEnviadoPor, $finEnviadoPor-$inicioEnviadoPor);
                        $enviadoPor = strip_tags($enviadoPor);
                        $enviadoPor = str_replace('Gedaca holding corp:', '', $enviadoPor);

                        $inicioMonto = strpos($body, '<td><strong>Fondos recibidos</strong></td>');
                        $monto = substr($body, $inicioMonto+37, 100);
                        $monto = strip_tags($monto);
                        $monto = str_replace('&nbsp;USD', '', $monto);

                        $inicioComentario = strpos($body, 'https://www.paypalobjects.com/digitalassets/c/system-triggered-email/n/layout/images/quote-left.png');
                        $finComentario = strpos($body, 'https://www.paypalobjects.com/digitalassets/c/system-triggered-email/n/layout/images/quote-right.png');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags('<img src="' . $comentario);


                        $pagos[$i]['tipo'] = 'Paypal';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }


                    // Binance

                    if (strpos($asunto, 'Payment Receive Successful') && $item->from == 'Binance <do-not-reply@ses.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, ' por ')) + 5;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '. Ve a la [Aplicación d');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if (strpos($asunto, 'Pago recibido correctamente') && $item->from == 'Binance <do-not-reply@ses.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, ' por ')) + 5;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '. Ve a la [Aplicación d');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if (strpos($asunto, 'Payment Receive Successful') && $item->from == 'Binance <do-not-reply@post.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = imap_qprint($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, ' por ')) + 5;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '. Ve a la [Aplicación d');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if (strpos($asunto, 'Payment Receive Successful') && $item->from == 'Binance <do_not_reply@mgdirectmail.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, ' por ')) + 5;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '. Ve a la [Aplicación d');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if (strpos($asunto, 'Pago recibido correctamente') && $item->from == 'Binance <do-not-reply@post.binance.com>') {

                        $body = @imap_body($conn, $email);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pa')) + 41;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, ' por ')) + 5;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '. Ve a la [Aplicaci');
                        $monto = substr($substr, 0, $finMonto);/*

                        dd($body);

                        dd($inicioMonto, $finMonto);*/

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if (strpos($asunto, 'Pago recibido correctamente:') && $item->from == 'Binance <do_not_reply@mgdirectmail.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, ' por ')) + 5;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '. Ve a la [Aplicación d');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;                        
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    // PNC

                    if (strpos($asunto, 'sent you a Zelle® payment.') && $item->from == 'PNC Alerts <pncalerts@pnc.com>') {

                        $body = @imap_qprint(@imap_body($conn, $email));


                        $enviadoPor = str_replace('sent you a Zelle® payment.', '', $asunto);
                        $enviadoPor = ucwords(strtolower($enviadoPor));

                        $inicioMonto = strpos($body, 'Amount:');
                        $finMonto = strpos($body, 'Note:');
                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        $monto = str_replace('Amount:', '', $monto);

                        $inicioComentario = strpos($body, 'Note:');
                        $finComentario = strpos($body, 'Date:');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);
                        $comentario = str_replace('Note:', '', $comentario);


                        $pagos[$i]['tipo'] = 'Zelle PNC';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }

                    if ($item->subject == 'Money was sent to you with Zelle' && $item->from == 'Truist Alerts <alertnotifications@message.truist.com>') {

                    $body = imap_fetchbody($conn, $email, 1);
                    $body = imap_base64($body);

                    $inicioEnviadoPor = strpos($body, 'Sent by:');
                    $finEnviadoPor = strpos($body, 'Amount');
                    $enviadoPor = substr($body, $inicioEnviadoPor, $finEnviadoPor-$inicioEnviadoPor);
                    $enviadoPor = strip_tags($enviadoPor);
                    $enviadoPor = str_replace(['Sent by:', '&nbsp;'], '', $enviadoPor);
                    $enviadoPor = trim($enviadoPor);

                    $inicioMonto = strpos($body, 'Amount:');

                    $finMonto = strpos($body, 'Memo:') === false ? strpos($body, 'This was de') : strpos($body, 'Memo:');

                    $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                    $monto = strip_tags($monto);
                    $monto = str_replace(['Amount:', '&nbsp;'], '', $monto);
                    $monto = trim($monto);

                    if (strpos($body, 'Memo:') === false) {
                        $comentario = '';
                    } else {
                        $inicioComentario = strpos($body, 'Memo:');
                        $finComentario = strpos($body, 'This was de');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);
                        $comentario = str_replace(['Memo:', '&nbsp;'], '', $comentario);
                        $comentario = trim($comentario);
                    }


                    $decimales = explode('.', (string) $monto);
                    $decimales = $decimales[1];

                    $pagos[$i]['tipo'] = 'Zelle Truist';
                    $pagos[$i]['enviado_por'] = $enviadoPor;
                    $pagos[$i]['monto'] = $monto;
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                    $pagos[$i]['comentario'] = $comentario;
                    $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                    $pagos[$i]['referencia'] = $i;

                    $i++;
                }
                }
            }

            // Chase

            $conn = imap_open($mailbox, 'pagosfarmaya@hotmail.com', 'Laravel23.') or die (imap_last_error());

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = imap_search($conn, 'ON "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = imap_fetch_overview($conn, $email);

                $header = @imap_header($conn, $email);

                $fecha = new DateTime($header->date);
                $fecha->modify('-4hour');
                $fecha = $fecha->format('d/m/Y h:i A');

                $fechaSinFormato = new DateTime($header->date);
                $fechaSinFormato->modify('-4hour');
                $fechaSinFormato = $fechaSinFormato->format('Y-m-d H:i:s');

                $arrayFecha = explode(' ', $fecha);

                /*if ($arrayFecha[0] != date_format(date_create(request()->fecha), 'd/m/Y')) {
                    continue;
                }*/

                foreach ($overview as $item) {

                    if ($item->from == 'Chase QuickPay Team <no-reply@alertsp.chase.com>') {
                        $body = imap_fetchbody($conn, $email, 1);

                        $array = explode(' sent you ', $item->subject);
                        $enviadoPor = $array[0];
                        $enviadoPor = strtoupper($array[0]);

                        $monto = $array[1];

                        $inicioComentario = strpos($body, 'Memo:');
                        $finComentario = strpos($body, 'To learn more,');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);
                        $comentario = str_replace('Memo:', '', $comentario);

                        $pagos[$i]['tipo'] = 'Zelle Chase';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }
                }
            }

            // PNC

            $conn = imap_open($mailbox, 'farmayapagos@hotmail.com', 'Laravel23.') or die (imap_last_error());

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = imap_search($conn, 'ON "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = imap_fetch_overview($conn, $email);

                $header = @imap_header($conn, $email);

                $fecha = new DateTime($header->date);
                $fecha->modify('-4hour');
                $fecha = $fecha->format('d/m/Y h:i A');

                $fechaSinFormato = new DateTime($header->date);
                $fechaSinFormato->modify('-4hour');
                $fechaSinFormato = $fechaSinFormato->format('Y-m-d H:i:s');

                $arrayFecha = explode(' ', $fecha);

                if ($arrayFecha[0] != date_format(date_create(request()->fecha), 'd/m/Y')) {
                    continue;
                }

                foreach ($overview as $item) {

                    if ($item->from == 'PNC Alerts <pncalerts@pnc.com>' && strpos($item->subject, 'sent you')) {
                        $body = imap_fetchbody($conn, $email, 1);

                        $array = explode(' sent you ', $item->subject);
                        $enviadoPor = $array[0];
                        $enviadoPor = strtoupper($array[0]);

                        $monto = $array[1];

                        $inicioMonto = strpos($body, 'Amount:');
                        $finMonto = strpos($body, 'Note:');
                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        $monto = str_replace('Amount:', '', $monto);

                        $inicioComentario = strpos($body, 'Note:');
                        $finComentario = strpos($body, 'Date:');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);
                        $comentario = str_replace('Note:', '', $comentario);

                        $inicioReferencia = strpos($body, 'Transaction ID:');
                        $finReferencia = strpos($body, 'The money will ');
                        $referencia = substr($body, $inicioReferencia, $finReferencia-$inicioReferencia);
                        $referencia = strip_tags($referencia);
                        $referencia = str_replace('Transaction ID:', '', $referencia);
                        $comentario = $comentario . ' Referencia: ' . $referencia;

                        $pagos[$i]['tipo'] = 'Zelle PNC';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }
                }
            }

            $pagos = collect($pagos);
            $pagos = $pagos->unique('referencia');
            $pagos = $pagos->sortByDesc('fechaSinFormato');
            $contador = 1;
        @endphp

        <hr class="row align-items-start col-12">

        <div class="input-group md-form form-sm form-1 pl-0">
            <div class="input-group-prepend">
                <span class="input-group-text purple lighten-3" id="basic-text1">
                    <i class="fas fa-search text-white" aria-hidden="true"></i>
                </span>
            </div>

            <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>

        <table class="table table-striped table-bordered mt-3 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th class="CP-sticky">#</th>
                    <th class="CP-sticky">Tipo</th>
                    <th class="CP-sticky">Enviado por</th>
                    <th class="CP-sticky">Monto</th>
                    <th class="CP-sticky">Comentario</th>
                    <th class="CP-sticky">Fecha</th>
                </tr>
            </thead>

            <tbody>
                @foreach($pagos as $pago)
                    <tr>
                        <td class="text-center">{{ $contador++ }}</td>
                        <td class="text-center">{{ $pago['tipo'] }}</td>
                        <td class="text-center">{{ $pago['enviado_por'] }}</td>
                        <td class="text-center">{{ $pago['monto'] }}</td>
                        <td class="text-center">{{ $pago['comentario'] }}</td>
                        <td class="text-center">{{ $pago['fecha'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $fin = new DateTime();
            $intervalo = $inicio->diff($fin);
            echo 'Tiempo de carga: ' . $intervalo->format("%Y-%M-%D %H:%I:%S");
        @endphp
    @endif
@endsection
