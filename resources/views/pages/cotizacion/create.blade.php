@extends('layouts.model')

@section('title')
    Cotizaciones
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file"></i>
        Crear cotización
    </h1>

    <hr class="row align-items-start col-12">

    <div>
        <form action="/cotizacion" method="POST">
            @csrf

            <div class="card">
                <div class="card-header bg-dark text-white font-weight-bold">Cliente</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="nombre_cliente">Nombre del cliente</label>
                            <input type="text" autofocus class="form-control" name="nombre_cliente" required>
                        </div>

                        <div class="col">
                            <label for="ci_cliente">CI/RIF del cliente</label>
                            <input type="text" class="form-control" name="ci_cliente" required>
                        </div>

                        <div class="col">
                            <label for="direccion_cliente">Dirección del cliente</label>
                            <input type="text" class="form-control" name="direccion_cliente">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-dark text-white font-weight-bold">Artículos</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="buscar_articulo">Buscar articulo:</label>

                            <div class="input-group">
                                <input type="text" class="form-control" name="buscar_articulo">

                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info add">
                                        <i class="fa fa-plus"></i>
                                        Agregar
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="id_articulo">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Código artículo</th>
                                        <th>Código barra</th>
                                        <th>Descripción</th>
                                        <th>Precio {{ SigVe }}</th>
                                        <th>Precio {{ SigDolar }}</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody class="articulos"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col">
                    <button type="submit" class="btn btn-primary bg-dark border-dark">
                        <i class="fa fa-check"></i>
                        Crear cotización
                    </button>

                    <a href="/home" class="btn btn-danger">
                        <i class="fa fa-reply"></i>
                        Atrás
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scriptsFoot')
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        clientes = {!! $clientes !!};

        i = 0;

        $('[name=nombre_cliente]').autocomplete({
            source: clientes,
            autoFocus: true,
            select: function (event, ui) {
                $('[name=ci_cliente]').val(ui.item.ci_rif);
                $('[name=direccion_cliente]').val(ui.item.direccion);
            }
        });

        articulos = {!! $articulos !!};

        $('[name=buscar_articulo]').autocomplete({
            source: articulos,
            select: function (event, ui) {
                $('[name=id_articulo]').val(ui.item.id_articulo);
            }
        });

        $('[name=buscar_articulo]').keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                $('.add').click();
            }
        });

        $('.add').click(function () {
            id_articulo = $('[name=id_articulo]').val();

            if (id_articulo == '') {
                return false;
            }

            $.ajax({
                type: 'GET',
                data: {
                    id_articulo: id_articulo
                },
                success: function (response) {
                    codigo_interno = response.codigo_interno;
                    codigo_barra = response.codigo_barra;
                    descripcion = response.descripcion;
                    precio_bs = response.precio_bs;
                    precio_ds = response.precio_ds;

                    $('.articulos').append(`
                        <tr>
                            <td>${codigo_interno}</td>
                            <td>${codigo_barra}</td>
                            <td>${descripcion}</td>
                            <td>${precio_bs}</td>
                            <td>${precio_ds}</td>
                            <td>
                                <button type="button" onclick="remove(this)" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                    Eliminar
                                </button>
                            </td>

                            <input type="hidden" name="articulos[${i}][codigo_interno]" value="${codigo_interno}">
                            <input type="hidden" name="articulos[${i}][codigo_barra]" value="${codigo_barra}">
                            <input type="hidden" name="articulos[${i}][descripcion]" value="${descripcion}">
                            <input type="hidden" name="articulos[${i}][precio_bs]" value="${precio_bs}">
                            <input type="hidden" name="articulos[${i}][precio_ds]" value="${precio_ds}">
                        </tr>
                    `);

                    $('[name=id_articulo]').val('');
                    $('[name=buscar_articulo]').val('');
                    $('[name=buscar_articulo]').focus();

                    i = i + 1;
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            });

            $('[name=buscar_articulo]').focus();
        });

        function remove(that) {
            $(that).parent().parent().remove();
        }
    </script>
@endsection
