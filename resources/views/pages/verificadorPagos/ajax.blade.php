@php
    try {
        $error = 0;

        $inicio = new DateTime();

        $host = $_SERVER['HTTP_HOST'];

        if ($host == 'cpharmaftn.com') {
            $username = 'pagostierranegra2@hotmail.com';
            $password = 'Glibenclamida*84';
        }

        if ($host == 'cpharmafau.com' || $host == 'cpharmade.com') {
            $username = 'pagosuniversidad2@hotmail.com';
            $password = 'pagosfarmaciaavenidauniversidad';
        }

        if ($host == 'cpharmafsm.com') {
            $username = 'pagosmillennium@hotmail.com';
            $password = 'Glibenclamida*84';
        }

        if ($host == 'cpharmafll.com') {
            $username = 'pagoslalago@hotmail.com';
            $password = 'Glibenclamida*84';
        }

        if ($host == 'cpharmakdi.com') {
            $username = 'pagoskdi@hotmail.com';
            $password = 'GJpc2017.';
        }

        $mailbox = '{outlook.office365.com:993/imap/ssl}';
        $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

        $conn = imap_open($mailbox, $username, $password) or die (imap_last_error());

        $search = imap_search($conn, 'SINCE "'.$fecha.'"');

        $search = is_iterable($search) ? $search : [];

        function fix_text_subject($str)
        {
            $asunto = '';
            $array = imap_mime_header_decode($str);

            foreach ($array as $object) {
                $asunto .= utf8_encode(rtrim($object->text, 't'));
            }

            return utf8_decode($asunto);
        }

        $pagos = [];
        $i = 0;

        foreach ($search as $email) {
            $overview = imap_fetch_overview($conn, $email);

            $header = imap_header($conn, $email);

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
                if (isset($item->subject)) {
                    $asunto = fix_text_subject($item->subject);
                }

                if (strpos($asunto, ' sent you ') && $header->fromaddress == 'Bank of America <customerservice@ealerts.bankofamerica.com>') {
                    $arrayAsunto = explode(' sent you ', $asunto);

                    $body = imap_qprint(imap_body($conn, $email));

                    $inicioComentario = strpos($body, '<!-- Zone2 - Begins-->');
                    $finComentario = strpos($body, '<!-- Zone2 - Ends-->');
                    $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                    $comentario = strip_tags($comentario);

                    $pagos[$i]['enviado_por'] = $arrayAsunto[0];
                    $pagos[$i]['monto'] = $arrayAsunto[1];
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                    $pagos[$i]['comentario'] = $comentario;

                    $i++;
                }

                if (strpos($asunto, ' le ha enviado ') && $header->fromaddress == 'Bank of America <customerservice@ealerts.bankofamerica.com>') {
                    $arrayAsunto = explode(' sent you ', $asunto);

                    $body = imap_qprint(imap_body($conn, $email));

                    $inicioComentario = strpos($body, '<!-- Zone2 - Begins-->');
                    $finComentario = strpos($body, '<!-- Zone2 - Ends-->');
                    $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                    $comentario = strip_tags($comentario);

                    $pagos[$i]['enviado_por'] = $arrayAsunto[0];
                    $pagos[$i]['monto'] = $arrayAsunto[1];
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                    $pagos[$i]['comentario'] = $comentario;

                    $i++;
                }
            }
        }

        $conn = imap_open($mailbox, 'pagosgedaca@hotmail.com', 'Cpharma20.') or die (imap_last_error());

        $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

        $search = imap_search($conn, 'SINCE "'.$fecha.'"');
        $search = is_iterable($search) ? $search : [];

        $i = 0;

        foreach ($search as $email) {
            $overview = imap_fetch_overview($conn, $email);

            $header = imap_header($conn, $email);

            $fecha = new DateTime($header->date);
            $fecha->modify('-4hour');
            $fecha = $fecha->format('d/m/Y h:i A');

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
                    $body = imap_qprint(imap_body($conn, $email));

                    $inicioEnviadoPor = strpos($body, '<p class="ppsans" style="font-size:32px;line-height:40px;color:#2c2e2f;margin:0" dir="ltr"><span>');
                    $finEnviadoPor = strpos($body, ' le ha enviado');
                    $enviadoPor = substr($body, $inicioEnviadoPor, $finEnviadoPor-$inicioEnviadoPor);
                    $enviadoPor = strip_tags($enviadoPor);

                    $inicioMonto = strpos($body, '<td><strong>Fondos recibidos</strong></td>');
                    $monto = substr($body, $inicioMonto+37, 100);
                    $monto = strip_tags($monto);
                    $monto = str_replace('&nbsp;USD', '', $monto);

                    $inicioComentario = strpos($body, ':</span></p>');
                    $finComentario = strpos($body, 'Detalles de la transacción');
                    $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                    $comentario = strip_tags($comentario);
                    $comentario = str_replace(':', '', $comentario);

                    $pagos[$i]['tipo'] = 'Paypal';
                    $pagos[$i]['enviado_por'] = $enviadoPor;
                    $pagos[$i]['monto'] = $monto;
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['comentario'] = $comentario;

                    $i++;
                }
            }
        }

        $pagos = collect($pagos)->sortByDesc('fechaSinFormato');
        $contador = 1;

        $pagos = array_filter($pagos, function ($item) {

            $fecha = strtotime($item['fechaSinFormato']);

            $anterior = new DateTime();
            $anterior->modify('-30minutes');
            $anterior = $anterior->format('Y-m-d H:i:s');
            $anterior = strtotime($anterior);

            if ($fecha >= $anterior) {
                return true;
            }
        });
    }

    catch (Exception $excepcion) {
        $error = 1;
    }
    @endphp

    <table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th colspan="5" class="text-center">VERIFICADOR PAGOS <small>(pagos en los últimos 30 minutos)</small></th>
        </tr>

        @if($error == 0)
            <tr>
                <th class="text-center">#</th>
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
                    <tr>
                        <td class="text-center">{{ $contador++ }}</td>
                        <td class="text-center">{{ $pago['enviado_por'] }}</td>
                        <td class="text-center">{{ $pago['monto'] }}</td>
                        <td class="text-center">{{ $pago['comentario'] }}</td>
                        <td class="text-center">{{ $pago['fecha'] }}</td>
                    </tr>
                @endforeach

            @else
                <tr>
                    <td colspan="5" class="text-center">No hay pagos recientes</td>
                </tr>
            @endif

        @else
            <tr>
                <td colspan="5" class="text-center">No hay conexión</td>
            </tr>
        @endif
    </tbody>
    </table>
