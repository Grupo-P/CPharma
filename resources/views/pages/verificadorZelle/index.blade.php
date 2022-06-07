@extends('layouts.model')

@section('title')
  Verificador Zelle
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-check"></i>
        Verificador Zelle
    </h1>

    @if(!request()->fecha)

        <form class="m-5 p-5">
            <div class="row text-center">
                <div class="col">
                    <label for="fecha">Fecha</label>
                </div>

                <div class="col-3">
                    <input min="2022-05-30" type="date" name="fecha" required class="form-control">
                </div>

                <div class="col">
                    <button type="submit" class="btn btn-outline-success">Buscar</button>
                </div>
            </div>
        </form>


    @else

        @php
            date_default_timezone_set('America/Caracas');

            include app_path() . '\functions\functions.php';

            $inicio = new DateTime();

            $sede = isset($_GET['sede']) ? $_GET['sede'] : FG_Mi_Ubicacion();

            if ($sede == 'FTN') {
                $username = 'pagostierranegra@hotmail.com';
                $password = 'GGlibenclamida*84';
            }

            if ($sede == 'FAU' || $sede == 'DBs') {
                $username = 'pagosuniversidad2@hotmail.com';
                $password = 'pagosfarmaciaavenidauniversidad';
            }

            if ($sede == 'FSM') {
                $username = 'pagosmillennium@hotmail.com';
                $password = 'Glibenclamida*84';
            }

            if ($sede == 'FLL') {
                $username = 'pagoslalago@hotmail.com';
                $password = 'Glibenclamida*84';
            }

            if ($sede == 'KDI') {
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
                        $pagos[$i]['comentario'] = $comentario;

                        $i++;
                    }
                }
            }

            $pagos = array_reverse($pagos);
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
