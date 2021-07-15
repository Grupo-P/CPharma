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
                    <th scope="col" class="CP-sticky">ID registro</th>
                    <th scope="col" class="CP-sticky">Fecha y hora</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">Proveedor</th>
                    <th scope="col" class="CP-sticky">Moneda del proveedor</th>
                    <th scope="col" class="CP-sticky">Monto</th>
                    <th scope="col" class="CP-sticky">Sede</th>
                    <th scope="col" class="CP-sticky">Estado</th>
                    <th scope="col" class="CP-sticky">Operador</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $dolares = 0;
                    $bolivares = 0;
                @endphp

                @foreach($items as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->id }}</td>
                        <td class="text-center">{{ date_format(new DateTime($item->created_at), 'd/m/Y h:i A') }}</td>
                        <td class="text-center">{{ $item->tipo }}</td>
                        <td class="text-center">{{ $item->proveedor }}</td>
                        <td class="text-center">{{ $item->moneda_proveedor }}</td>
                        <td class="text-center">{{ number_format($item->monto, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $item->sede }}</td>
                        <td class="text-center">{{ $item->estado }}</td>
                        <td class="text-center">{{ $item->operador }}</td>
                    </tr>

                    @php
                        if ($item->moneda == 'Dólares' && $item->estado == 'Activo') {
                            $dolares = $dolares + $item->monto;
                        }

                        if ($item->moneda == 'Bolívares' && $item->estado == 'Activo') {
                            $bolivares = $bolivares + $item->monto;
                        }
                    @endphp
                @endforeach
            </tbody>
        </table>

        <table class="table table-striped table-bordered col-12 sortable">
            <thead>
                <tr>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border-top: white 1px solid;border-left: white 1px solid;border-bottom: white 1px solid;border"></th>
                    <th class="text-center">Total en bolívares</th>
                    <th class="text-center">{{ number_format($bolivares, 2, ',', '.') }}</th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                </tr>

                <tr>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border-left: white 1px solid;border-bottom: white 1px solid;"></th>
                    <th class="text-center">Total en dólares</th>
                    <th class="text-center">{{ number_format($dolares, 2, ',', '.') }}</th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                </tr>
            </thead>
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
