@extends('layouts.contabilidad')

@section('title')
    Bancos
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de banco
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/bancos" class="btn btn-outline-info btn-sm">
        <i class="fa fa-reply"></i> Regresar
    </a>

    <br>
    <br>

    <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nombre del banco</th>
                <td>{{$banco->nombre_banco}}</td>
            </tr>

            <tr>
                <th scope="row">Nombre del titular</th>
                <td>{{$banco->nombre_titular}}</td>
            </tr>

            <tr>
                <th scope="row">Alias de la cuenta</th>
                <td>{{$banco->alias_cuenta}}</td>
            </tr>
        </tbody>
    </table>
@endsection
