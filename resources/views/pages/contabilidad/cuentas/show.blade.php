@extends('layouts.contabilidad')

@section('title')
    Plan de cuentas
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle del plan de cuentas
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/cuentas" class="btn btn-outline-info btn-sm">
        <i class="fa fa-reply"></i> Regresar
    </a>

    <br>
    <br>

    <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row">{{ $cuenta->nombre }}</th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nombre</th>
                <td>{{ $cuenta->nombre }}</td>
            </tr>

            <tr>
                <th scope="row">Pertenece a</th>
                <td>{{ $pertenece_a }}</td>
            </tr>
        </tbody>
    </table>
@endsection
