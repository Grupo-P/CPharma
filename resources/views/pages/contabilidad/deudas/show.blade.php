@extends('layouts.contabilidad')

@section('title')
    Deuda
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de deuda
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/deudas" class="btn btn-outline-info btn-sm">
        <i class="fa fa-reply"></i> Regresar
    </a>

    <br>
    <br>

    <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row"></th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nombre del proveedor</th>
                <td>{{$deuda->proveedor->nombre_proveedor}}</td>
            </tr>

            <tr>
                <th scope="row">RIF/CI del proveedor</th>
                <td>{{$deuda->proveedor->rif_ci}}</td>
            </tr>

            <tr>
                <th scope="row">Fecha de registro</th>
                <td>{{$deuda->created_at}}</td>
            </tr>

            <tr>
                <th scope="row">Moneda</th>
                <td>{{$deuda->proveedor->moneda}}</td>
            </tr>

            <tr>
                <th scope="row">Monto</th>
                <td>{{number_format($deuda->monto, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Documento soporte deuda</th>
                <td>{{$deuda->documento_soporte_deuda}}</td>
            </tr>

            <tr>
                <th scope="row">Numero documento</th>
                <td>{{$deuda->numero_documento}}</td>
            </tr>

            <tr>
                <th scope="row">Creado por</th>
                <td>{{$deuda->usuario_registro}}</td>
            </tr>

            <tr>
                <th scope="row">Sede</th>
                <td>{{$deuda->sede}}</td>
            </tr>
        </tbody>
    </table>
@endsection
