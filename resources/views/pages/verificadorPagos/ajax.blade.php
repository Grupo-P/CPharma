@php
    header('Access-Control-Allow-Origin: *');

    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    try {
        $error = 0;

        $inicio = new DateTime();

        $host = $_SERVER['HTTP_HOST'];

        if ($host == 'cpharmaftn.com') {
            $username = 'pagostierranegra2@hotmail.com';
            $password = 'Glibenclamida*84';
            $id = 1;
            $remitente = 'pagomovilftn@gmail.com';
        }

        if ($host == 'cpharmafau.com' || $host == 'cpharmade.com' || $host == 'cpharmagpde.com') {
            $username = 'pagosuniversidad2@hotmail.com';
            $password = 'pagosfarmaciaavenidauniversidad';
            $id = 2;
            $remitente = 'pagomovilgp@gmail.com';
        }

        if ($host == 'cpharmafsm.com') {
            $username = 'pagosmillennium@hotmail.com';
            $password = 'Glibenclamida*84';
            $remitente = 'pagosfll677@gmail.com';
            $id = 4;
        }

        if ($host == 'cpharmafll.com') {
            $username = 'pagoslalago@hotmail.com';
            $password = 'Glibenclamida*84';
            $remitente = 'pagosfll677@gmail.com';
            $id = 3;
        }

        if ($host == 'cpharmakdi.com') {
            $username = 'pagoskdi@hotmail.com';
            $password = 'GJpc2017.';
            $remitente = '';
            $id = 5;
        }

        $mailbox = '{outlook.office365.com:993/imap/ssl}';

        $fecha = date_create(request()->fecha);
        $fecha = date_modify($fecha, '-1day');
        $fecha = date_format($fecha, 'd-M-Y');

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

                // Bank of America

                if (strpos($asunto, ' sent you ') && $header->fromaddress == 'Bank of America <customerservice@ealerts.bankofamerica.com>') {
                    $arrayAsunto = explode(' sent you ', $asunto);

                    $body = imap_qprint(imap_body($conn, $email));

                    $inicioComentario = strpos($body, '<!-- Zone2 - Begins-->');
                    $finComentario = strpos($body, '<!-- Zone2 - Ends-->');
                    $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                    $comentario = strip_tags($comentario);

                    $decimales = explode('.', (string) $arrayAsunto[1]);
                    $decimales = $decimales[1];

                    $pagos[$i]['enviado_por'] = $arrayAsunto[0];
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

                    $body = imap_qprint(imap_body($conn, $email));

                    $inicioComentario = strpos($body, '<!-- Zone2 - Begins-->');
                    $finComentario = strpos($body, '<!-- Zone2 - Ends-->');
                    $comentario = substr($body, $inicioComentario, $finComentario-$inicioComentario);
                    $comentario = strip_tags($comentario);

                    $decimales = explode('.', (string) $monto);
                    $decimales = $decimales[1];

                    $pagos[$i]['enviado_por'] = $arrayAsunto[0];
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

                    $body = imap_fetchbody($conn, $email, 2);

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

                        $decimales = explode('.', (string) $monto);
                        $decimales = $decimales[1];

                        $pagos[$i]['tipo'] = 'Pago móvil Mercantil';
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

        $conn = imap_open($mailbox, 'pagosgedaca@hotmail.com', 'Cpharma20.') or die (imap_last_error());

        $fecha = date_format(date_create(request()->fecha), 'd-M-Y');

        $search = imap_search($conn, 'SINCE "'.$fecha.'"');
        $search = is_iterable($search) ? $search : [];

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
                // Paypal

                if (isset($item->subject)) {
                    $asunto = fix_text_subject($item->subject);
                }

                if ($asunto == 'Ha recibido un pago' && $item->from == '"service@paypal.com" <service@paypal.com>') {
                    $body = imap_qprint(imap_body($conn, $email));

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

                    $decimales = explode('.', (string) $monto);
                    $decimales = $decimales[1];


                    $pagos[$i]['tipo'] = 'Paypal';
                    $pagos[$i]['enviado_por'] = $enviadoPor;
                    $pagos[$i]['monto'] = $monto;
                    $pagos[$i]['fecha'] = $fecha;
                    $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                    $pagos[$i]['comentario'] = $comentario;
                    $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor, 0, 1) . rand(100, 999) . $decimales;

                    $i++;
                }

                // Binance

                    if (strpos($asunto, 'Payment Receive Successful') && $item->from == 'Binance <do-not-reply@ses.binance.com>') {

                        $body = imap_body($conn, $email);
                        $body = base64_decode($body);

                        $inicioEnviadoPor = (strpos($body, 'You have received an incoming Pay transfer from ')) + 48;
                        $substr = substr($body, $inicioEnviadoPor);
                        $finEnviadoPor = strpos($substr, ',');
                        $enviadoPor = substr($substr, 0, $finEnviadoPor);

                        $inicioMonto = (strpos($body, 'amount of ')) + 10;
                        $substr = substr($body, $inicioMonto);
                        $finMonto = strpos($substr, '.&nbsp;</span>');
                        $monto = substr($substr, 0, $finMonto);
                        $monto = str_replace('USDT', '', $monto);
                        $monto = '$'.$monto;

                        $comentario = '';

                        $decimales = explode('.', (string) $monto);
                        $decimales = $decimales[1];

                        $pagos[$i]['tipo'] = 'Binance';
                        $pagos[$i]['enviado_por'] = $enviadoPor;
                        $pagos[$i]['monto'] = $monto;
                        $pagos[$i]['fecha'] = $fecha;
                        $pagos[$i]['fechaSinFormato'] = $fechaSinFormato;
                        $pagos[$i]['comentario'] = $comentario;
                        $pagos[$i]['hash'] = rand(100, 999) . substr($enviadoPor[0], 0, 1) . rand(100, 999) . $decimales;
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

                    $decimales = explode('.', (string) $monto);
                    $decimales = $decimales[1];

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
        $pagos = $pagos->sortByDesc('fechaSinFormato');
        $contador = 1;

        $pagos = $pagos->filter(function ($item) {

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
                    <tr class="tr-item" onclick="mostrar_modal('{{ trim($pago['tipo']) }}', '{{ trim($pago['enviado_por']) }}', '{{ trim($pago['monto']) }}', '{{ trim($pago['comentario']) }}', '{{ trim($pago['fecha']) }}', '{{ trim($pago['hash']) }}', '{{ trim($caja) }}', '{{ trim($sede) }}')">
                        <td class="text-center">{{ $contador++ }}</td>
                        <td class="text-center">{{ $pago['tipo'] }}</td>
                        <td class="text-center">{{ $pago['enviado_por'] }}</td>
                        <td class="text-center">{{ $pago['monto'] }}</td>
                        <td class="text-center">{{ $pago['comentario'] }}</td>
                        <td class="text-center">{{ $pago['fecha'] }}</td>
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
        width: 50%;
        margin-left: 25%;
        height: auto;
    }
</style>

<script>
    function mostrar_modal(tipo, enviado_por, monto, comentario, fecha, hash, caja, sede) {
        html = '<img src="/assets/img/icono.png">';
        html = html + '<p>Tipo: '+tipo+'</p>';
        html = html + '<p>Enviado por: '+enviado_por+'</p>';
        html = html + '<p>Monto: '+monto+'</p>';
        html = html + '<p>Comentario: '+comentario+'</p>';
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
