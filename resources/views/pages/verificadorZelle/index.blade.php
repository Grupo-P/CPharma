@extends('layouts.model')

@section('title')
  Verificador Zelle
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-check"></i>
        Verificador Zelle
    </h1>

    <hr class="row align-items-start col-12">

    <div class="input-group md-form form-sm form-1 pl-0">
        <div class="input-group-prepend">
            <span class="input-group-text purple lighten-3" id="basic-text1">
                <i class="fas fa-search text-white" aria-hidden="true"></i>
            </span>
        </div>

        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
    </div>

    @php
        include app_path() . '\functions\functions.php';

        $sede = FG_Mi_Ubicacion();

        if ($sede == 'FTN') {
            $username = 'pagoskdi@gmail.com';
            $password = 'GJpc2017.';
        }

        if ($sede == 'FAU' || $sede == 'DBs') {
            $username = 'pagoskdi@gmail.com';
            $password = 'GJpc2017.';
        }

        if ($sede == 'FSM') {
            $username = 'pagoskdi@gmail.com';
            $password = 'GJpc2017.';
        }

        if ($sede == 'FLL') {
            $username = 'pagoskdi@gmail.com';
            $password = 'GJpc2017.';
        }

        if ($sede == 'KDI') {
            $username = 'pagoskdi@gmail.com';
            $password = 'GJpc2017.';
        }

        $mailbox = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
        $fecha = '25-MAY-2021';

        $conn = imap_open($mailbox, $username, $password) or die (imap_last_error());

        $search = imap_search($conn, 'SINCE "'.$fecha.'"');

        function fix_text_subject($str)
        {
            $subject = '';
            $array = imap_mime_header_decode($str);

            foreach ($array as $object) {
                $subject .= utf8_encode(rtrim($object->text, 't'));
            }

            return utf8_decode($subject);
        }

        $pagos = [];
        $i = 0;

        foreach ($search as $email) {
            $overview = imap_fetch_overview($conn, $email);
            $header = imap_header($conn, $email);
            $sender = $header->sender[0]->mailbox . '@' . $header->sender[0]->host;
            $fecha = date_format(date_create($header->date), 'd/m/Y');

            foreach ($overview as $item) {
                if (isset($item->subject)) {
                    $subject = fix_text_subject($item->subject);
                }

                if (strpos($subject, ' sent you ') && $sender == 'customerservice@ealerts.bankofamerica.com') {
                    $array = explode(' sent you ', $subject);

                    $pagos[$i]['enviado_por'] = $array[0];
                    $pagos[$i]['monto'] = $array[1];
                    $pagos[$i]['fecha'] = $fecha;

                    $i++;
                }

                if (strpos($subject, ' le ha enviado ') && $sender == 'customerservice@ealerts.bankofamerica.com') {
                    $array = explode(' le ha enviado ', $subject);

                    $pagos[$i]['enviado_por'] = $array[0];
                    $pagos[$i]['monto'] = $array[1];
                    $pagos[$i]['fecha'] = $fecha;

                    $i++;
                }
            }
        }

        $pagos = array_reverse($pagos);
        $contador = 1;
    @endphp

    <table class="table table-striped table-bordered mt-3 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th class="CP-sticky">#</th>
                <th class="CP-sticky">Enviado por</th>
                <th class="CP-sticky">Monto</th>
                <th class="CP-sticky">Fecha</th>
            </tr>
        </thead>

        <tbody>
            @foreach($pagos as $pago)
                <tr>
                    <td class="text-center">{{ $contador++ }}</td>
                    <td class="text-center">{{ $pago['enviado_por'] }}</td>
                    <td class="text-center">{{ $pago['monto'] }}</td>
                    <td class="text-center">{{ $pago['fecha'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
