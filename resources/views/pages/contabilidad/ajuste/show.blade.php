@extends('layouts.contabilidad')

@section('title')
    Registro de ajustes
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de ajuste
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/ajuste" class="btn btn-outline-info btn-sm">
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
                <td>{{$ajuste->proveedor->nombre_proveedor}}</td>
            </tr>

            <tr>
                <th scope="row">RIF/CI del proveedor</th>
                <td>{{$ajuste->proveedor->rif_ci}}</td>
            </tr>

            <tr>
                <th scope="row">Fecha de registro</th>
                <td>{{$ajuste->created_at}}</td>
            </tr>

            <tr>
                <th scope="row">Moneda subtotal</th>
                <td>{{$ajuste->proveedor->moneda}}</td>
            </tr>

            <tr>
                <th scope="row">Monto subtotal (Exento + base)</th>
                <td>{{number_format($ajuste->monto, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Moneda IVA</th>
                <td>{{$ajuste->proveedor->moneda_iva}}</td>
            </tr>

            <tr>
                <th scope="row">Monto IVA</th>
                <td>{{number_format($ajuste->monto_iva, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Comentario</th>
                <td>{{$ajuste->comentario}}</td>
            </tr>
        </tbody>
    </table>
@endsection
