@extends('layouts.model')

@section('title')
    Cotizaciones
@endsection

@section('scriptsFoot')
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $('[name=nombre_cliente]').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: '/cotizacion/create',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        buscar: request.term,
                        tipo: 'cliente'
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.nombre + ' | ' + item.ci_rif,
                                value: item.nombre,
                                direccion: item.direccion,
                                ci_rif: item.ci_rif
                            }
                        }));
                    }
                });
            },
            select: function (event, ui) {
                $('[name=ci_cliente]').val(ui.item.ci_rif);
                $('[name=direccion_cliente]').val(ui.item.direccion);
            },
            autoFocus: true
        });

        $('[name=ci_cliente]').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: '/cotizacion/create',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        buscar: request.term,
                        tipo: 'cliente'
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.nombre + ' | ' + item.ci_rif,
                                value: item.ci_rif,
                                direccion: item.direccion,
                                nombre: item.nombre
                            }
                        }));
                    }
                });
            },
            select: function (event, ui) {
                $('[name=nombre_cliente]').val(ui.item.nombre);
                $('[name=direccion_cliente]').val(ui.item.direccion);
            },
            autoFocus: true
        });

        $('[name=buscar_articulo]').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: '/cotizacion/create',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        buscar: request.term,
                        tipo: 'articulo'
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.codigo_interno + ' | ' + item.codigo_barra + ' | ' + item.descripcion,
                                value: item.codigo_interno + ' | ' + item.codigo_barra + ' | ' + item.descripcion
                            }
                        }));
                    }
                });
            },
            select: function (event, ui) {

            },
            autoFocus: true
        });
    </script>
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
                            <input type="text" class="form-control" name="nombre_cliente">
                        </div>

                        <div class="col">
                            <label for="ci_cliente">CI/RIF del cliente</label>
                            <input type="text" class="form-control" name="ci_cliente">
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
                            <input type="text" class="form-control" name="buscar_articulo">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-3 btn btn-primary btn-lg bg-dark border-dark">Crear cotización</button>
        </form>
    </div>
@endsection
