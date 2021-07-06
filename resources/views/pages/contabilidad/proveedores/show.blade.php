@extends('layouts.contabilidad')

@section('title')
    Proveedor
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/proveedores" class="btn btn-outline-info btn-sm">
        <i class="fa fa-reply"></i> Regresar
    </a>

    <br>
    <br>

    <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row">{{$proveedor->nombre_proveedor}}</th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nombre del representante</th>
                <td>{{$proveedor->nombre_representante}}</td>
            </tr>

            <tr>
                <th scope="row">RIF/Cédula</th>
                <td>{{$proveedor->rif_ci}}</td>
            </tr>

            <tr>
                <th scope="row">Dirección</th>
                <td>{{$proveedor->direccion}}</td>
            </tr>

            <tr>
                <th scope="row">Tasa</th>
                <td>{{$proveedor->tasa}}</td>
            </tr>

            <tr>
                <th scope="row">Plan de cuentas</th>
                <td>{{$proveedor->plan_cuentas}}</td>
            </tr>

            <tr>
                <th scope="row">Moneda</th>
                <td>{{$proveedor->moneda}}</td>
            </tr>

            <tr>
                <th scope="row">Saldo</th>
                <td>{{number_format($proveedor->saldo, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Creado por</th>
                <td>{{$proveedor->usuario_creado}}</td>
            </tr>
        </tbody>
    </table>
@endsection
