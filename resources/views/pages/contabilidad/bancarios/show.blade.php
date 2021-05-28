@extends('layouts.contabilidad')

@section('title')
    Registro de pagos bancarios
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de pago bancario
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/bancarios" class="btn btn-outline-info btn-sm">
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
                <td>{{$pago->proveedor->nombre_proveedor}}</td>
            </tr>

            <tr>
                <th scope="row">RIF/CI del proveedor</th>
                <td>{{$pago->proveedor->rif_ci}}</td>
            </tr>

            <tr>
                <th scope="row">Banco</th>
                <td>{{$pago->banco->nombre_banco}}</td>
            </tr>

            <tr>
                <th scope="row">Fecha de registro</th>
                <td>{{$pago->created_at}}</td>
            </tr>

            <tr>
                <th scope="row">Monto</th>
                <td>{{number_format($pago->monto, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Operador</th>
                <td>{{$pago->operador}}</td>
            </tr>

            <tr>
                <th scope="row">Comentario</th>
                <td>{{$pago->comentario}}</td>
            </tr>
        </tbody>
    </table>
@endsection
