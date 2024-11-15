@php
    error_reporting(0);

    header('Access-Control-Allow-Origin: *');

    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');
    date_default_timezone_set('America/Caracas');

    try {
        $error = 0;

        $inicio = new DateTime();

        $host = $_SERVER['HTTP_HOST'];

        if ($host == 'cpharmaftn.com') {
            $username = 'pagostierranegra@gmail.com';
            $password = 'ahcl wywv zvcl vcfg';
            $id = 1;
            $remitente = 'pagomovilftn@gmail.com';
        }

        if ($host == 'cpharmafau.com' || $host == 'cpharmade.com' || $host == 'cpharmagpde.com') {
            $username = 'pagosavuniversidad2@gmail.com';
            $password = 'uydr xlow srqa odgv';
            $id = 2;
            $remitente = 'pagomovilgp@gmail.com';
        }

        if ($host == 'cpharmafsm.com') {
            $username = 'pagosmillennium@gmail.com';
            $password = 'ogft pryn rtcu rbgh';
            $remitente = 'pagosfll677@gmail.com';
            $id = 4;
        }

        if ($host == 'cpharmafll.com') {
            $username = 'pagoslalago@gmail.com';
            $password = 'yred gyyu rzzp wcmg';
            $remitente = 'pagosfll677@gmail.com';
            $id = 3;
        }

        if ($host == 'cpharmafec.com') {
            $username = 'pagoselcallejon@gmail.com';
            $password = 'khxx zqax xxzn smgn';
            $remitente = 'pagomovilfec@gmail.com';
            $id = 6;
        }

        if ($host == 'cpharmaflf.com') {
            $username = 'pagoslafusta@gmail.com';
            $password = 'zssu dzzt dhmj xvwf';
            $remitente = 'pagomovilflf@gmail.com';
            $id = 7;
        }

        if ($host == 'cpharmacdd.com') {
            $username = 'pagoslafusta@gmail.com';
            $password = 'zssu dzzt dhmj xvwf';
            $remitente = 'pagomovilcdd@gmail.com';
            $id = 8;
        }

        if ($host == 'cpharmakdi.com' || $host == 'cpharmakd73.com') {
            $username = 'pagoskdi@gmail.com';
            $password = 'ihra yddt ucgk sdrb';
            $remitente = 'pagomovilkd@gmail.com';
            $id = 5;
        }

        $mailbox = '{imap.gmail.com:993/imap/ssl}INBOX';

        $fecha = date_create(request()->fecha);
        $fecha = date_format($fecha, 'd-M-Y');

        try {
            $conn = @imap_open($mailbox, $username, $password);
        } catch (\Throwable $th) {
            echo 'No se pudo conectar @gmail.com:';
        }

        $search = @imap_search($conn, 'SINCE "'.$fecha.'"');

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
            $overview = imap_fetchheader($conn, $email);

            preg_match('/Date: (.*)/', $overview, $matches);
            $fechaReal = isset($matches[1]) ? $matches[1] : null;

            $overview = @imap_fetch_overview($conn, $email);

            $header = @imap_header($conn, $email);

            $fecha = new DateTime($fechaReal);
            $fecha->setTimezone(new DateTimeZone('America/Caracas'));
            $fecha = $fecha->format('d/m/Y h:i A');

            $fechaSinFormato = new DateTime($fechaReal);
            $fechaSinFormato->setTimezone(new DateTimeZone('America/Caracas'));
            $fechaSinFormato = $fechaSinFormato->format('Y-m-d H:i:s');

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

                    $decimales = explode('.', (string) $arrayAsunto[1]);
                    $decimales = $decimales[1];

                    $pagos[$i]['enviado_por'] = html_entity_decode($arrayAsunto[0]);
                    $pagos[$i]['monto'] = $arrayAsunto[1];
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                    $pagos[$i]['comentario'] = $comentario;
                    $pagos[$i]['tipo'] = 'Zelle BOFA';
                    $pagos[$i]['hash'] = rand(100, 999) . substr($arrayAsunto[0], 0, 1) . rand(100, 999) . $decimales;
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

                    $decimales = explode('.', (string) $monto);
                    $decimales = $decimales[1];

                    $pagos[$i]['enviado_por'] = html_entity_decode($arrayAsunto[0]);
                    $pagos[$i]['monto'] = $arrayAsunto[1];
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                    $pagos[$i]['comentario'] = $comentario;
                    $pagos[$i]['tipo'] = 'Zelle BOFA';
                    $pagos[$i]['hash'] = rand(100, 999) . substr($arrayAsunto[0], 0, 1) . rand(100, 999) . $decimales;
                    $pagos[$i]['referencia'] = $i;

                    $i++;
                }

                // Mercantil

                if (strpos($asunto, 'SMS') && $header->fromaddress == $remitente) {
                    try {
                        $body = @imap_fetchbody($conn, $email, 2);
                        $otoFormato = false;

                        if (strpos($body, 'Tpago') && strpos($body, '- 500')) {

                            $body = str_replace(array("\n", "\r", "="), "", $body);

                            $inicioEnviadoPor = (strpos($body, 'celular')) + 7;

                            if($inicioEnviadoPor == 7) {
                                $inicioEnviadoPor = (strpos($body, ' del ')) + 5;
                                $otoFormato = true;
                            }

                            $substr = substr($body, $inicioEnviadoPor);
                            $finEnviadoPor = strpos($substr, '.');

                            if($otoFormato) {
                                $finEnviadoPor = strpos($substr, ' Ref ');
                            }

                            $enviadoPor = substr($substr, 0, $finEnviadoPor);
                            $enviadoPor = str_replace(['=', ' '], ['', ''], $enviadoPor);


                            $inicioMonto = (strpos($body, 'Tpago por')) + 9;

                            if($otoFormato) {
                                $inicioMonto = (strpos($body, 'Tpago recibido')) + 14;
                            }

                            $substr = substr($body, $inicioMonto);
                            $finMonto = strpos($substr, ' desde ');

                            if($otoFormato) {
                                $finMonto = strpos($substr, ' del');
                            }

                            $monto = trim(substr($substr, 0, $finMonto));

                            $inicioReferencia = (strpos($body, 'a:')) + 2;

                            if($otoFormato) {
                                $inicioReferencia = (strpos($body, ' Ref ')) + 5;
                            }

                            $substr = substr($body, $inicioReferencia);
                            $finReferencia = strpos($substr, ',');
                            $referencia = substr($substr, 0, $finReferencia);

                            $comentario = "Referencia: $referencia";

                            $temporalMonto = explode(" ", $monto)[1] ?? $monto;

                            $decimales = explode('.', (string) $temporalMonto);

                            if(!isset($decimales[1])) {
                                $romper = explode(",", (string) $temporalMonto);
                                $decimales = $romper[1] ?? '';
                            } else {
                                $decimales = $decimales[1] ?? '';
                            }

                            $pagos[$i]['tipo'] = 'Pago móvil Mercantil';
                            $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                            $pagos[$i]['monto'] = $monto;
                            $pagos[$i]['fecha'] = $fecha;
                            $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                            $pagos[$i]['comentario'] = $comentario;
                            $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                            $pagos[$i]['referencia'] = $referencia;

                            $i++;
                        }
                    }
                    catch (Throwable  $error) {
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
                        $monto = str_replace(["=","\n","\r"," "], "",substr($substr, 0, $finMonto));

                        $inicioReferencia = (strpos($body, 'ef:')) + 3;
                        $substr = substr($body, $inicioReferencia);

                        $finReferencia = strpos($substr, ' Llamar');
                        $referencia = substr($substr, 0, $finReferencia);

                        $inicioFechaMensaje = strpos($substr, 'Operacion (');
                        $inicioFechaMensaje = str_replace("Operacion (", "", substr($substr, $inicioFechaMensaje));

                        $finFechaMensaje = strpos($inicioFechaMensaje, ' ');
                        $fechaMensaje = substr($inicioFechaMensaje, 0, $finFechaMensaje);
                        $fechaMensaje = DateTime::createFromFormat('j/n/Y', $fechaMensaje);
                        $fechaMensaje = $fechaMensaje->format('d/m/y');
                        $fechaConsulta = date("d/m/Y");

                        $fechaMensaje = DateTime::createFromFormat('d/m/y', $fechaMensaje);
                        $fechaConsulta = DateTime::createFromFormat('d/m/Y', $fechaConsulta);

                        if($fechaMensaje != $fechaConsulta) continue;

                        $comentario = "Referencia: $referencia";

                        $decimales = explode('.', (string) $monto);

                        $decimales = $decimales[1];

                        $pagos[$i]['tipo'] = 'Pago móvil BNC';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                        $pagos[$i]['referencia'] = $referencia;

                        $i++;
                    }
                }

                // Venezuela
                if (strpos($asunto, 'SMS') && $header->fromaddress == $remitente) {
                    $body = @imap_fetchbody($conn, $email, 2);

                    if (strpos($body, 'PagomovilB') && (strpos($body, '- 2661') || strpos($body, '- 2662'))) {
                        $inicioEnviadoPor =(strpos($body, 'del ')) + 4;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);
                        $enviadoPor = str_replace(['=', ' '], ['', ''], $enviadoPor);


                        $inicioMonto = (strpos($body, 'comercio por ')) + 13;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, ' del ');
                        $monto = str_replace(["=","\n","\r"," "], "",substr($substr, 0, $finMonto));

                        $inicioReferencia = (strpos($body, ' Ref: ')) + 6;
                        $substr = substr($body, $inicioReferencia);

                        $finReferencia = strpos($substr, ' comis');
                        $referencia = substr($substr, 0, $finReferencia);

                        $inicioFechaMensaje = strpos($substr, ' fecha: ');
                        $inicioFechaMensaje = str_replace(" fecha: ", "", substr($substr, $inicioFechaMensaje));

                        $finFechaMensaje = strpos($inicioFechaMensaje, ' ');
                        $fechaMensaje = substr($inicioFechaMensaje, 0, $finFechaMensaje);
                        $fechaConsulta = date("d/m/Y");

                        $fechaMensaje = DateTime::createFromFormat('d-m-y', $fechaMensaje);
                        $fechaMensaje = $fechaMensaje->format('d/m/y');

                        $fechaMensaje = DateTime::createFromFormat('d/m/y', $fechaMensaje);
                        $fechaConsulta = DateTime::createFromFormat('d/m/Y', $fechaConsulta);


                        if($fechaMensaje != $fechaConsulta) continue;

                        $comentario = "Referencia: $referencia";

                        $decimales = explode('.', (string) $monto);

                        $decimales = $decimales[1];

                        $pagos[$i]['tipo'] = 'Pago móvil Venezuela';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
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

        // Truist
        try {
            $conn = @imap_open($mailbox, 'pagosgedaca@gmail.com', 'nqzj lehy oupk xdnr');

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = @imap_search($conn, 'SINCE "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = imap_fetchheader($conn, $email);

                preg_match('/Date: (.*)/', $overview, $matches);
                $fechaReal = isset($matches[1]) ? $matches[1] : null;

                $overview = @imap_fetch_overview($conn, $email);

                $header = @imap_header($conn, $email);

                $fecha = new DateTime($fechaReal);
                $fecha->setTimezone(new DateTimeZone('America/Caracas'));
                $fecha = $fecha->format('d/m/Y h:i A');

                $fechaSinFormato = new DateTime($fechaReal);
                $fechaSinFormato->setTimezone(new DateTimeZone('America/Caracas'));
                $fechaSinFormato = $fechaSinFormato->format('Y-m-d H:i:s');

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

                        $enviadoPor = trim($enviadoPor);

                        $inicioMonto = strpos($body, '<td><strong>Fondos recibidos</strong></td>');
                        $monto = substr($body, $inicioMonto+37, 100);
                        $monto = strip_tags($monto);
                        $monto = str_replace('&nbsp;USD', '', $monto);

                        $inicioComentario = strpos($body, 'https://www.paypalobjects.com/digitalassets/c/system-triggered-email/n/layout/images/quote-left.png');
                        $finComentario = strpos($body, 'https://www.paypalobjects.com/digitalassets/c/system-triggered-email/n/layout/images/quote-right.png');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags('<img src="' . $comentario);

                        $decimales = explode('.', (string) $monto);
                        $decimales = $decimales[1];


                        $pagos[$i]['tipo'] = 'Paypal';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor, 0, 1) . rand(100, 999) . $decimales;

                        $i++;

                        continue;
                    }

                    // Binance

                    if (
                        (   strpos($asunto, 'Payment Receive Successful')
                            ||strpos($asunto, 'Pago recibido correctamente')
                        )
                        &&
                        (   $item->from == 'Binance <do-not-reply@ses.binance.com>'
                            || $item->from == 'Binance <do-not-reply@directmail2.binance.com>'
                            || $item->from == 'Binance <donotreply@directmail.binance.com>'
                            || $item->from == 'Binance <do_not_reply@mgdirectmail.binance.com>'
                        )
                    ) {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $substr = substr($substr, $finEnviadoPor + 5);
                        $finMonto = strpos($substr, '. Ve a la [Aplicac');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    if (strpos($asunto, 'Pago recibido correctamente:') && $item->from == 'Binance <do-not-reply@ses.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $substr = substr($substr, $finEnviadoPor + 5);
                        $finMonto = strpos($substr, '. Ve a la [Aplicac');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binances';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    if (strpos($asunto, 'Pago recibido correctamente:') && $item->from == 'Binance <do_not_reply@mgdirectmail.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $substr = substr($substr, $finEnviadoPor + 5);
                        $finMonto = strpos($substr, '. Ve a la [Aplicac');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    if (strpos($asunto, 'Pago recibido correctamente') && $item->from == 'Binance <do-not-reply@post.binance.com>') {

                        $body = @imap_body($conn, $email);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pa')) + 41;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $substr = substr($substr, $finEnviadoPor + 5);
                        $finMonto = strpos($substr, '. Ve a la [Aplicac');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    if (strpos($asunto, 'Payment Receive Successful') && $item->from == 'Binance <do-not-reply@post.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = imap_qprint($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $substr = substr($substr, $finEnviadoPor + 5);
                        $finMonto = strpos($substr, '. Ve a la [Aplicac');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    if (strpos($asunto, 'Payment Receive Successful') && $item->from == 'Binance <do_not_reply@mgdirectmail.binance.com>') {

                        $body = @imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'Recibiste una transferencia de Pay de ')) + 38;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ' por ');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $substr = substr($substr, $finEnviadoPor + 5);
                        $finMonto = strpos($substr, '. Ve a la [Aplicac');
                        $monto = substr($substr, 0, $finMonto);

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    // PNC
                    $fromPNC = trim($item->from);
                    if (strpos($fromPNC, 'PNC Alerts')  !== false && strpos($item->subject, 'sent_you')  !== false)
                    {
                        $body = imap_fetchbody($conn, $email, 1);

                        $subjetPnc = mb_decode_mimeheader($item->subject);
                        $array = explode('_sent_you_', $subjetPnc);
                        $enviadoPor = str_replace('_', ' ', $array[0]);
                        $enviadoPor = strtoupper($enviadoPor);

                        $monto = '';

                        $inicioMonto = strpos($body, 'Amount:');
                        $finMonto = strpos($body, 'Note:');
                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        preg_match('/\d+\.\d+/', $monto, $matches);
                        $monto = $matches[0];

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
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = '$'.$monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    } else if ($item->from == 'PNC Alerts <pncalerts@pnc.com>' && strpos($item->subject, 'sent you'))
                    {
                        $body = imap_fetchbody($conn, $email, 1);

                        $array = explode(' sent you ', $item->subject);
                        $enviadoPor = $array[0];
                        $enviadoPor = strtoupper($array[0]);

                        $monto = $array[1];

                        $inicioMonto = strpos($body, 'Amount:');
                        $finMonto = strpos($body, 'Note:');
                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        preg_match('/\d+\.\d+/', $monto, $matches);
                        $monto = $matches[0];

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
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = '$'.$monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                            $i++;
                    } else if (strpos($asunto, 'sent you a Zelle® payment.') && $item->from == 'PNC Alerts <pncalerts@pnc.com>') {

                        $body = @imap_qprint(@imap_body($conn, $email));


                        $enviadoPor = str_replace('sent you a Zelle® payment.', '', $asunto);
                        $enviadoPor = ucwords(strtolower($enviadoPor));

                        $inicioMonto = strpos($body, 'Amount:');
                        $finMonto = strpos($body, 'Note:');
                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        preg_match('/\d+\.\d+/', $monto, $matches);
                        $monto = $matches[0];

                        $inicioComentario = strpos($body, 'Note:');
                        $finComentario = strpos($body, 'Date:');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);
                        $comentario = str_replace('Note:', '', $comentario);

                        $decimales = explode('.', (string) $monto);
                        $decimales = $decimales[1];

                        $pagos[$i]['tipo'] = 'Zelle PNC';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = '$'.$monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }

                    // Truist
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

                        $finMonto = strpos($body, 'Memo:') === false ? strpos($body, 'The money will') : strpos($body, 'Memo:');

                        if(!$finMonto) {
                            $finMonto = strpos($body, 'Memo:') === false ? strpos($body, 'This was de') : strpos($body, 'Memo:');
                        }

                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        $monto = str_replace(['Amount:', '&nbsp;'], '', $monto);
                        $monto = trim($monto);

                        if (strpos($body, 'Memo:') === false) {
                            $comentario = '';
                        } else {
                            $inicioComentario = strpos($body, 'Memo:');
                            $finComentario = strpos($body, 'The money will');

                            if(!$finComentario) {
                                $finComentario = strpos($body, 'This was de');
                            }

                            $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                            $comentario = strip_tags($comentario);
                            $comentario = str_replace(['Memo:', '&nbsp;'], '', $comentario);
                            $comentario = trim($comentario);
                        }


                        $decimales = explode('.', (string) $monto);
                        $decimales = $decimales[1];

                        $inicioComentario = strpos($body, 'Memo:');
                        $finComentario = strpos($body, 'To learn more:');
                        $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        $comentario = strip_tags($comentario);
                        $comentario = str_replace('Memo:', '', $comentario);

                        $pagos[$i]['tipo'] = 'Zelle Truist';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
                        $pagos[$i]['referencia'] = $i;

                        $i++;

                        continue;
                    }
                }
            }

        } catch (\Throwable $th) {
            echo 'No se pudo conectar pagosgedaca@gmail.com:';
        }

        // Chase
        try {
            $conn = imap_open($mailbox, 'pagosfarmaya@gmail.com', 'ozrz rujm dbqt qnhw');

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = imap_search($conn, 'SINCE "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = imap_fetch_overview($conn, $email);

                preg_match('/Date: (.*)/', $overview, $matches);
                $fechaReal = isset($matches[1]) ? $matches[1] : null;

                $header = @imap_header($conn, $email);

                $fecha = new DateTime($fechaReal);
                $fecha->setTimezone(new DateTimeZone('America/Caracas'));
                $fecha = $fecha->format('d/m/Y h:i A');

                $fechaSinFormato = new DateTime($fechaReal);
                $fechaSinFormato->setTimezone(new DateTimeZone('America/Caracas'));
                $fechaSinFormato = $fechaSinFormato->format('Y-m-d H:i:s');

                $arrayFecha = explode(' ', $fecha);

                if ($arrayFecha[0] != date_format(date_create(request()->fecha), 'd/m/Y')) {
                    continue;
                }

                foreach ($overview as $item) {

                    if ($item->from == 'Chase QuickPay Team <no-reply@alertsp.chase.com>') {
                        $body = imap_fetchbody($conn, $email, 1);

                        $array = explode(' sent you ', $item->subject);
                        $enviadoPor = $array[0];
                        $enviadoPor = strtoupper($array[0]);
                        if(count($array)>1){
                            $monto = $array[1];

                            $inicioComentario = strpos($body, 'Memo:');
                            $finComentario = strpos($body, 'To learn more,');
                            $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                            $comentario = strip_tags($comentario);
                            $comentario = str_replace('Memo:', '', $comentario);

                            $pagos[$i]['tipo'] = 'Zelle Chase';
                            $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                            $pagos[$i]['monto'] = $monto;
                            $pagos[$i]['fecha'] = $fecha;
                            $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                            $pagos[$i]['comentario'] = $comentario;
                            $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                            $pagos[$i]['referencia'] = $i;

                            $i++;
                        }
                    } else if ($item->from == 'Chase <no.reply.alerts@chase.com>') {
                        $body = imap_fetchbody($conn, $email, 1);

                        $array = explode('font-size:16px; font-weight:bold; color:#414042;" class=3D"font14">', $body);

                        $amount = str_replace("=\r\n", '', substr($array[1], 0, strpos($array[1], '</td>')));
                        $referencia =  str_replace("=\r\n", '', substr($array[3], 0, strpos($array[3], '</td>')));
                        $enviadoPor =  explode(' sent you money</td', $body);
                        $enviadoPor = str_replace("=\r\n", '', substr($enviadoPor[0], strrpos($enviadoPor[0], 'class=3D"moPad">')+16));

                        $comentario = "Referencia: ".$referencia;

                        $pagos[$i]['tipo'] = 'Zelle Chase';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $amount;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;
                        $i++;
                    }
                }
            }
        } catch (\Throwable $th) {
            echo 'No se pudo conectar pagosfarmaya@gmail.com:';
        }


        // PNC|BANESCO US|TRUIST

        try {
            $conn = imap_open($mailbox, 'farmayapagos@gmail.com', 'jvxn exjp wlkt hfvr');

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = imap_search($conn, 'SINCE "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = imap_fetch_overview($conn, $email);

                preg_match('/Date: (.*)/', $overview, $matches);
                $fechaReal = new DateTime($overview[0]->date);
                $fechaReal->setTimezone(new DateTimeZone('America/Caracas'));

                $header = @imap_header($conn, $email);

                $fecha = $fechaReal;
                $fecha->setTimezone(new DateTimeZone('America/Caracas'));
                $fecha = $fecha->format('d/m/Y h:i A');

                $fechaSinFormato = $fechaReal;
                $fechaSinFormato->setTimezone(new DateTimeZone('America/Caracas'));
                $fechaSinFormato = $fechaSinFormato->format('Y-m-d H:i:s');

                $arrayFecha = explode(' ', $fecha);

                if ($arrayFecha[0] != date_format(date_create(request()->fecha), 'd/m/Y')) {
                    continue;
                }

                foreach ($overview as $item) {

                    // PNC
                    if ($item->from == 'PNC Alerts <pncalerts@pnc.com>' && strpos($item->subject, 'sent you'))
                    {
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
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = '$'.$monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                            $i++;
                    }
                    // BANESCO USA
                    if ($item->from == 'Banesco USA <customerservice@banescousa.com>' && strpos($item->subject, 'deposited your payment'))
                    {
                        $body = imap_fetchbody($conn, $email, 1);

                        $inicioMonto = strpos($body, 'have successfully deposited the ');
                        $finMonto = strpos($body, ' payment from ');
                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        $monto = str_replace('have successfully deposited the ', '', $monto);

                        $finEnviado = strpos($body, ' (confirmation number ');
                        $enviadoPor = substr($body, $finMonto, $finEnviado - $finMonto);
                        $enviadoPor = str_replace(' payment from ', '', $enviadoPor);

                        //$inicioComentario = strpos($body, 'Note:');
                        //$finComentario = strpos($body, 'Date:');
                        //$comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                        //$comentario = strip_tags($comentario);
                        //$comentario = str_replace('Note:', '', $comentario);

                        $finReferencia = strpos($body, ') into your account ');
                        $referencia = substr($body, $finEnviado, $finReferencia-$finEnviado);
                        $referencia = strip_tags($referencia);
                        $referencia = str_replace(' (confirmation number ', '', $referencia);

                        $pagos[$i]['tipo'] = 'Zelle BANESCO USA';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = 'Referencia: '.$referencia;
                        $pagos[$i]['hash'] = rand(100, 999) . $fechaSinFormato . rand(100, 999) ;
                        $pagos[$i]['referencia'] = $i;

                        $i++;
                    }
                    // TRUIST
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

                        $finMonto = strpos($body, 'Memo:') === false ? strpos($body, 'The money will') : strpos($body, 'Memo:');

                        if(!$finMonto) {
                            $finMonto = strpos($body, 'Memo:') === false ? strpos($body, 'This was de') : strpos($body, 'Memo:');
                        }

                        $monto = substr($body, $inicioMonto, $finMonto-$inicioMonto);
                        $monto = strip_tags($monto);
                        $monto = str_replace(['Amount:', '&nbsp;'], '', $monto);
                        $monto = trim($monto);

                        if (strpos($body, 'Memo:') === false) {
                            $comentario = '';
                        } else {
                            $inicioComentario = strpos($body, 'Memo:');
                            $finComentario = strpos($body, 'The money will');

                            if(!$finComentario) {
                                $finComentario = strpos($body, 'This was de');
                            }

                            $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                            $comentario = strip_tags($comentario);
                            $comentario = str_replace(['Memo:', '&nbsp;'], '', $comentario);
                            $comentario = trim($comentario);
                        }


                        $decimales = explode('.', (string) $monto);
                        $decimales = $decimales[1] ?? $monto;

                        $pagos[$i]['tipo'] = 'Zelle Truist';
                        $pagos[$i]['enviado_por'] = html_entity_decode($enviadoPor);
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

        } catch (\Throwable $th) {
            echo 'No se pudo conectar farmayapagos@gmail.com:';
        }

        $pagos = collect($pagos);
        $pagos = $pagos->sortByDesc('fechaSinFormato');
        $contador = 1;

        $pagos = $pagos->filter(function ($item) {

            $fecha = strtotime($item['fechaSinFormato']);

            $anterior = new DateTime();
            $anterior->setTimezone(new DateTimeZone('America/Caracas'));
            $anterior->modify('-30 minutes');
            //$anterior->modify('-8 hours');
            $anterior = $anterior->format('Y-m-d H:i:s');
            $anterior = strtotime($anterior);

            if ($fecha >= $anterior) {
                return true;
            }
        });

        $contador = 1;
    }

    catch (Exception $excepcion) {
        dd($excepcion);
        $error = 1;
    }

    $RutaUrl = FG_Mi_Ubicacion();
    $SedeConnection = $RutaUrl;
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $estacion_trabajo = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    $query = sqlsrv_query($conn, "SELECT * FROM VenCaja WHERE EstacionTrabajo = '$estacion_trabajo'");

    $caja = '';

    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        $caja = $row['CodigoCaja'];
    }

    $sede = FG_Nombre_Sede(FG_Mi_Ubicacion());
@endphp

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th colspan="6" class="text-center">VERIFICADOR PAGOS <small>(pagos en los últimos 30 minutos)</small></th>
        </tr>

        @if($error == 0)
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Enviado por</th>
                <th class="text-center">Monto</th>
                <th class="text-center">Comentario</th>
                <th class="text-center">Fecha y hora</th>
            </tr>
        @endif
    </thead>

    <tbody>
        @if($error == 0)
            @if(count($pagos))
                @foreach($pagos as $pago)
                    <tr class="tr-item" onclick="mostrar_modal('{{ trim($pago['tipo']) }}', '{{ trim($pago['enviado_por']) }}', '{{ trim($pago['monto']) }}', `{{ strval(trim($pago['comentario'])) }}`, '{{ trim($pago['fecha']) }}', '{{ trim($pago['hash']) }}', '{{ trim($caja) }}', '{{ trim($sede) }}')">
                        <td class="text-center">{{ $contador++ }}</td>
                        <td class="text-center">{{ trim($pago['tipo']) }}</td>
                        <td class="text-center">{{ trim($pago['enviado_por']) }}</td>
                        <td class="text-center">{{ trim($pago['monto']) }}</td>
                        <td class="text-center">{{ trim($pago['comentario']) }}</td>
                        <td class="text-center">{{ trim($pago['fecha']) }}</td>
                    </tr>
                @endforeach

            @else
                <tr>
                    <td colspan="6" class="text-center">No hay pagos recientes</td>
                </tr>
            @endif

        @else
            <tr>
                <td colspan="6" class="text-center">No hay conexión</td>
            </tr>
        @endif
    </tbody>
</table>

<style>
    .tr-item {
        cursor: pointer;
    }

    .modal-body-pago {
        position: relative;
    }

    .modal-body-pago img {
        position: absolute;
        opacity: 0.6;
        width: 30%;
        margin-left: 25%;
        height: auto;
    }
</style>

<script>
    function mostrar_modal(tipo, enviado_por, monto, comentario, fecha, hash, caja, sede) {
        html = '<img src="/assets/img/icono.png" style="object-fit: contain;">';
        html = html + '<p>Tipo: '+tipo+'</p>';
        html = html + '<p>Enviado por: '+enviado_por+'</p>';
        html = html + '<p>Monto: '+monto+'</p>';
        html = html + '<p>Comentario: '+comentario +'</p>';
        html = html + '<p>Fecha: '+fecha+'</p>';
        html = html + '<p>Hash: '+hash+'</p>';
        html = html + '<p>Caja: '+caja+'</p>';
        html = html + '<p>Sede: '+sede+'</p>';
        html = html + '<p><b>NOTA:</b> El hash mostrado es un código de seguridad del CPharma y no corresponde al código de confirmación de la transferencia bancaria.</p>';

        $('#ver-pago-modal').find('.modal-body').html(html);
        $('#ver-pago-modal').modal('show');
    }
</script>


<div class="modal" id="ver-pago-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-body-pago">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
