@extends('layouts.contabilidad')


@section('title')
    Reporte
@endsection


@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice">
        </i>
        Deudas por fecha
    </h1>

    <hr class="row align-items-start col-12">

    @if($request->get('fechaInicio'))
        <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text purple lighten-3" id="basic-text1">
                    <i class="fas fa-search text-white" aria-hidden="true"></i>
                </span>
            </div>

            <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>

        <h6 align="center">Periodo desde el {{ $fechaInicio }} al {{ $fechaFin }}</h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Fecha y hora</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">Proveedor</th>
                    <th scope="col" class="CP-sticky">Registros</th>
                    <th scope="col" class="CP-sticky">Monto</th>
                    <th scope="col" class="CP-sticky">Sede</th>
                    <th scope="col" class="CP-sticky">Operador</th>
                </tr>
            </thead>

            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ date_format(new DateTime($item->created_at), 'd/m/Y h:i A') }}</td>
                        <td class="text-center">{{ (get_class($item) == 'compras\ContReclamo') ? 'Reclamo' : 'Deuda' }}</td>
                        <td class="text-center">{{ $item->proveedor }}</td>
                        <td class="text-center">{{ $item->registros }}</td>
                        <td class="text-center">{{ number_format($item->monto, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $item->sede }}</td>
                        <td class="text-center">{{ $item->operador }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    @else
        <form action="">
            <div class="row mb-5">
                <div class="col"></div>

                <div class="col">
                    Fecha inicio:
                </div>

                <div class="col">
                    <input type="date" required class="form-control" name="fechaInicio">
                </div>

                <div class="col"></div>

                <div class="col">
                    Fecha fin:
                </div>

                <div class="col">
                    <input type="date" required class="form-control" name="fechaFin">
                </div>

                <div class="col">
                    <input type="submit" class="btn btn-outline-success" value="Buscar">
                </div>

                <div class="col"></div>
            </div>
        </form>
    @endif
@endsection
