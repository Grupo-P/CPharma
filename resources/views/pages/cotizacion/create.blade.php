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
        <form action="/cotizacion/store" method="POST">
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
                            <label for="direccion_cliente">Dirección del cliente del cliente</label>
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
                                        <th>Componente</th>
                                        <th>Precio {{ SigVe }}</th>
                                        <th>Precio {{ SigDolar }}</th>
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
            autoFocus: true,
            select: function (event, ui) {
                $('[name=id_articulo]').val(ui.item.id_articulo);

                articulo = {
                    codigo_articulo: ui.item.codigo_interno,
                    codigo_barra: ui.item.codigo_barra,
                    descripcion: ui.item.descripcion,
                    componente: ui.item.componente,
                    precio_bs: ui.item.precio_bs,
                    precio_ds: ui.item.precio_ds,
                }
            }
        });

        $('.add').click(function () {
            id_articulo = $('[name=id_articulo]').val();

            if (id_articulo) {
                $('[name=buscar_articulo]').val('');
                $('[name=id_articulo]').val('');

                $('.articulos').append(`
                    <tr>
                        <td>${articulo.codigo_articulo}</td>
                        <td>${articulo.codigo_barra}</td>
                        <td>${articulo.descripcion}</td>
                        <td>${articulo.componente}</td>
                        <td>${articulo.precio_bs}</td>
                        <td>${articulo.precio_ds}</td>
                    </tr>
                `);
            }

            $('[name=buscar_articulo]').focus();
        });
    </script>
@endsection
