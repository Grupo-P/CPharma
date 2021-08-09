@extends('layouts.contabilidad')

@section('title')
    Conciliaciones
@endsection

@section('content')

<!-- Modal Guardar -->
@if (session('Saved'))
<div aria-hidden="true" aria-labelledby="exampleModalCenterTitle" class="modal fade" id="exampleModalCenter" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle">
                    <i class="fas fa-info text-info">
                    </i>
                    {{ session('Saved') }}
                </h5>

                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>

            <div class="modal-body">
                <h4 class="h6">
                    Pago conciciliado exitosamente
                </h4>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-success" data-dismiss="modal" type="button">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<h1 class="h5 text-info">
    <i class="fas fa-file-invoice-dollar">
    </i>
    Conciliaciones
</h1>

<hr class="row align-items-start col-12">
    <table class="CP-stickyBar" style="width:100%;">
        <tr>
            <td style="width:100%;">
                <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                    <div class="input-group-prepend">
                        <span class="input-group-text purple lighten-3" id="basic-text1">
                            <i aria-hidden="true" class="fas fa-search text-white">
                            </i>
                        </span>
                    </div>
                    <input aria-label="Search" autofocus="autofocus" class="form-control my-0 py-1 CP-stickyBar" id="myInput" onkeyup="FilterAllTable()" placeholder="Buscar..." type="text">
                    </input>
                </div>
            </td>
        </tr>
    </table>

    <br/>
    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th class="CP-sticky" scope="col">
                    #
                </th>
                <th class="CP-sticky" scope="col">
                    Tipo
                </th>
                <th class="CP-sticky" scope="col">
                    Emisor
                </th>
                <th class="CP-sticky" scope="col">
                    Proveedor / Concepto
                </th>
                <th class="CP-sticky" scope="col">
                    Monto
                </th>
                <th class="CP-sticky" scope="col">
                    Operador
                </th>
                <th class="CP-sticky" scope="col">
                    Fecha
                </th>
                <th class="CP-sticky" scope="col">
                    Estado
                </th>
                <th class="CP-sticky" scope="col">
                    Acciones
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach($pagos as $pago)
            <tr>
                <td>
                    {{$pago['id']}}
                </td>
                <td>
                    {{($pago['tipo'])}}
                </td>
                <td>
                    {{$pago['emisor']}}
                </td>
                <td>
                    @if($pago['nombre_proveedor'])
                        {{ $pago['nombre_proveedor'] . ' | ' . $pago['ci_proveedor'] }}
                    @else
                        {!! $pago['concepto'] !!}
                    @endif
                </td>
                <td>
                    {{$pago['monto']}}
                </td>
                <td>
                    {{$pago['operador']}}
                </td>
                <td>
                    {{$pago['fecha']}}
                </td>
                <td>
                    {{$pago['estado']}}
                </td>
                <td class="text-center">
                    <input data-id="{{$pago['id']}}" data-clase="{{$pago['clase']}}" type="checkbox">
                </td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <button type="button" class="conciliar btn btn-outline-success btn-sm">Conciliar<br>seleccionados</button>
                </td>
            </tr>
        </tfoot>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');

        $('.conciliar').click(function () {
            checkeds = $('[type=checkbox]:checked');
            pagos = [];

            for (var i = 0; i < checkeds.length; i++) {
                id = $(checkeds[i]).attr('data-id');
                clase = $(checkeds[i]).attr('data-clase');

                pagos.push({ clase: clase, id: id });
            }

            $.ajax({
                url: '/conciliaciones',
                type: 'POST',
                data: {
                    pagos: pagos,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            })
        });
    </script>
    @endsection
</hr>

