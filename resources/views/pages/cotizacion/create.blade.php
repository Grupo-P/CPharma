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
                        <label for="nombre_cliente">Buscar articulo:</label>
                        <input type="text" class="form-control" name="nombre_cliente">
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
@endsection
