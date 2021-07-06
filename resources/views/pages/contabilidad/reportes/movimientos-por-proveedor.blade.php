@extends('layouts.contabilidad')


@section('title')
    Reporte
@endsection


@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice">
        </i>
        Movimientos por proveedor
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

        <h6 align="center">Periodo desde el <b>{{ $fechaInicio }}</b> al <b>{{ $fechaFin }}</b> para el proveedor <b>{{ $proveedor->nombre_proveedor }}</b></h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Fecha y hora</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">Nro. movimiento</th>
                    <th scope="col" class="CP-sticky">Monto</th>
                    <th scope="col" class="CP-sticky">Comentario</th>
                    <th scope="col" class="CP-sticky">Conciliado</th>
                    <th scope="col" class="CP-sticky">Operador</th>
                </tr>
            </thead>

            <tbody>
                @foreach($movimientos as $movimiento)
                    @php $fecha = date_create($movimiento->fecha); @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ date_format($fecha, 'd/m/Y h:i A') }}</td>
                        <td class="text-center">{{ $movimiento->tipo }}</td>
                        <td class="text-center">{{ $movimiento->nro_movimiento }}</td>
                        <td class="text-center">{{ $movimiento->monto }}</td>
                        <td class="text-center">{{ $movimiento->comentario }}</td>
                        <td class="text-center">{{ $movimiento->conciliacion }}</td>
                        <td class="text-center">{{ $movimiento->operador }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    @else
        <form action="">
            <div class="row mt-5 mb-5">
                <div class="col"></div>

                <div class="col">
                    Proveedor:
                </div>

                <div class="col">
                    <input type="text" required class="form-control" name="proveedor">
                    <input type="hidden" required name="id_proveedor">
                </div>

                <div class="col"></div>
            </div>

            <div class="row mb-5 mt-5">
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



@section('scriptsHead')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {
            var proveedoresJson = {!! json_encode($proveedores) !!};

            $('[name=proveedor]').autocomplete({
                source: proveedoresJson,
                autoFocus: true,
                select: function (event, ui) {
                    $('[name=id_proveedor]').val(ui.item.id);
                }
            });

            $('form').submit(function (event) {
                resultado = proveedoresJson.find(elemento => elemento.label == $('[name=proveedor]').val());

                if (!resultado) {
                    event.preventDefault();
                    alert('Debe seleccionar un proveedor válido');
                    bancario = false;
                }
            });
        });
    </script>
@endsection
