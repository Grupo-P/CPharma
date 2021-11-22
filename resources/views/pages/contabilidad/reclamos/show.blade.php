@extends('layouts.contabilidad')

@section('title')
    Reclamos
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de reclamo
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/reclamos" class="btn btn-outline-info btn-sm">
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
                <td>{{$reclamo->proveedor ? $reclamo->proveedor->nombre_proveedor : ''}}</td>
            </tr>

            <tr>
                <th scope="row">RIF/CI del proveedor</th>
                <td>{{($reclamo->proveedor) ? $reclamo->proveedor->rif_ci : ''}}</td>
            </tr>

            <tr>
                <th scope="row">Fecha de registro</th>
                <td>{{$reclamo->created_at}}</td>
            </tr>

            <tr>
                <th scope="row">Moneda subtotal</th>
                <td>{{($reclamo->proveedor) ? $reclamo->proveedor->moneda : ''}}</td>
            </tr>

            <tr>
                <th scope="row">Monto subtotal (Exento + Base)</th>
                <td>{{number_format($reclamo->monto, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Moneda IVA</th>
                <td>{{($reclamo->proveedor) ? $reclamo->proveedor->moneda_iva : ''}}</td>
            </tr>

            <tr>
                <th scope="row">Monto IVA</th>
                <td>{{number_format($reclamo->monto_iva, 2, ',', '.')}}</td>
            </tr>

            <tr>
                <th scope="row">Documento soporte reclamo</th>
                <td>{{$reclamo->documento_soporte_reclamo}}</td>
            </tr>

            <tr>
                <th scope="row">Numero documento</th>
                <td>{{$reclamo->numero_documento}}</td>
            </tr>

            <tr>
                <th scope="row">Creado por</th>
                <td>{{$reclamo->usuario_registro}}</td>
            </tr>

            <tr>
                <th scope="row">Sede</th>
                <td>{{$reclamo->sede}}</td>
            </tr>

            <tr>
                <th scope="row">Comentario</th>
                <td>{{$reclamo->comentario}}</td>
            </tr>
        </tbody>
    </table>
@endsection
