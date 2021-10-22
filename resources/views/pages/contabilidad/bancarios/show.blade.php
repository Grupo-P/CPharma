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
                <th scope="row">Alias bancario</th>
                <td>{{$pago->banco->alias_cuenta}}</td>
            </tr>

            <tr>
                <th scope="row">Fecha de registro</th>
                <td>{{$pago->created_at}}</td>
            </tr>

            <tr>
                <th scope="row">Monto al banco</th>
                <td>{{ number_format(monto_banco($pago->monto, $pago->iva), 2, ',', '.') }}</td>
            </tr>

            <tr>
                <th scope="row">Monto proveedor base</th>
                <td>{{ number_format($pago->monto, 2, ',', '.') }}</td>
            </tr>

            <tr>
                <th scope="row">Monto proveedor IVA</th>
                <td>{{ ($pago->estatus != 'Prepagado') ? number_format($pago->iva, 2, ',', '.') : '' }}</td>
            </tr>

            @if($pago->tasa)
                <tr>
                    <th scope="row">Tasa</th>
                    <td>{{number_format($pago->tasa, 2, ',', '.')}}</td>
                </tr>
            @endif

            <tr>
                <th scope="row">Operador</th>
                <td>{{$pago->operador}}</td>
            </tr>

            <tr>
                <th scope="row">Estado</th>
                <td>{{$pago->estatus}}</td>
            </tr>

            <tr>
                <th scope="row">Comentario</th>
                <td>{{$pago->comentario}}</td>
            </tr>
        </tbody>
    </table>
@endsection
