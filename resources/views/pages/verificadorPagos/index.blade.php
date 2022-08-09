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

        <form class="m-5 p-5">
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
            }

            if ($sede == 'FAU' || $sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.') {
                $username = 'pagosuniversidad2@hotmail.com';
                $password = 'pagosfarmaciaavenidauniversidad';
            }

            if ($sede == 'FSM' || $sede == 'FARMACIA MILLENNIUM 2000, C.A') {
                $username = 'pagosmillennium@hotmail.com';
                $password = 'Glibenclamida*84';
            }

            if ($sede == 'FLL' || $sede == 'FARMACIA LA LAGO,C.A.') {
                $username = 'pagoslalago@hotmail.com';
                $password = 'Glibenclamida*84';
            }

            if ($sede == 'KDI' || $sede == 'FARMACIAS KD EXPRESS, C.A.') {
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

                        $body = imap_qprint(imap_body($conn, $email));

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

                        $body = imap_qprint(imap_body($conn, $email));


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

                    if (strpos($asunto, 'SMS') && $header->fromaddress == 'SMS forwarder <no-reply-smsforwarder@cofp.ru>') {

                        $body = imap_fetchbody($conn, $email, 2);
                        $body = imap_base64($body);

                        if (strpos($body, 'Tpago')) {

                            $inicioEnviadoPor = (strpos($body, 'celular')) + 7;
                            $substr = substr($body, $inicioEnviadoPor);
                            $finEnviadoPor = strpos($substr, '.');
                            $enviadoPor = substr($substr, 0, $finEnviadoPor);

                            $inicioMonto = (strpos($body, 'Tpago por')) + 9;
                            $substr = substr($body, $inicioMonto);
                            $finMonto = strpos($substr, ' desde ');
                            $monto = substr($substr, 0, $finMonto);

                            $inicioReferencia = (strpos($body, 'referencia:')) + 11;
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
                }
            }

            $conn = imap_open($mailbox, 'pagosgedaca@hotmail.com', 'Cpharma20.') or die (imap_last_error());

            $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

            $search = imap_search($conn, 'SINCE "'.$fecha.'"');
            $search = is_iterable($search) ? $search : [];

            foreach ($search as $email) {
                $overview = imap_fetch_overview($conn, $email);

                $header = imap_header($conn, $email);

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
                        $body = imap_qprint(imap_body($conn, $email));

                        $inicioEnviadoPor = strpos($body, '<p class="ppsans" style="font-size:32px;line-height:40px;color:#2c2e2f;margin:0" dir="ltr"><span>');
                        $finEnviadoPor = strpos($body, ' le ha enviado');
                        $enviadoPor = substr($body, $inicioEnviadoPor, $finEnviadoPor-$inicioEnviadoPor);
                        $enviadoPor = strip_tags($enviadoPor);

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


                    // PNC

                    if (strpos($asunto, 'sent you a Zelle® payment.') && $item->from == 'PNC Alerts <pncalerts@pnc.com>') {

                        $body = imap_qprint(imap_body($conn, $email));


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
