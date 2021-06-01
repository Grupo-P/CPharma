@extends('layouts.model')

@section('title')
    Surtido de gavetas
@endsection

@section('content')

    <style>
    table{
        border-collapse: collapse;
        text-align: center;
    }
    thead{
        border: 1px solid black;
        border-radius: 0px;
        background-color: #e3e3e3;
    }
    tbody{
        border: 1px solid black;
        border-radius: 0px;
    }
    td{
        border: 1px solid black;
        border-radius: 0px;
    }
    th{
        border: 1px solid black;
        border-radius: 0px;padding: 10px;
    }
    .alinear-der{
        text-align: right;
        padding-right: 20px;
    }
    .alinear-izq{
        text-align: left;
        padding-left: 20px;
    }
    .aumento{
        font-size: 1.2em;
        text-transform: uppercase;
    }
</style>

    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Detalle de inventario
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/surtido" class="btn btn-outline-info btn-sm">
        <i class="fa fa-reply"></i> Regresar
    </a>

    <button type="button" class="btn btn-outline-success btn-sm" onclick="window.print()">
        <i class="fa fa-print"></i> Imprimir
    </button>

    <br><br>

    <table>
        <thead>
            <tr>
                <th scope="row" colspan="4" width="20%">
                    <span class="navbar-brand text-info CP-title-NavBar">
                        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                    </span>
                </th>
                <th colspan="9">DATOS GENERALES DEL SURTIDO</th>
            </tr>

            <tr>
                <th scope="row" colspan="3">Código del conteo</th>
                <th scope="row">SEDE</th>
                <th scope="row">Operador</th>
                <th scope="row">Estatus</th>
                <th scope="row">Fecha de generación</th>
                <th scope="row">SKU</th>
                <th scope="row">Unidades</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="3">{{ $surtido->control }}</td>
                <td>{{ $sede }}</td>
                <td>{{ $surtido->operador_generado }}</td>
                <td>{{ $surtido->estatus }}</td>
                <td>{{ date_create($surtido->fecha_generado)->format('d/m/Y H:i A') }}</td>
                <td>{{ $surtido->sku }}</td>
                <td>{{ $surtido->unidades }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <table>
        <thead>
                <tr>
                    <th scope="row" colspan="4" width="20%">
                    <span class="navbar-brand text-info CP-title-NavBar">
                        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                    </span>
                </th>
                    <th colspan="9">DATOS GENERALES DEL SURTIDO</th>
                </tr>
            <tr>
                <th scope="row">#</th>
                <th scope="row" colspan="3">Código interno</th>
                <th scope="row">Código de barra</th>
                <th scope="row">Descripción</th>
                <th scope="row">Unidades</th>
                <th scope="row">Almacen</th>
                <th scope="row">Caja</th>
            </tr>
        </thead>

        <tbody>
            @foreach($detalles as $detalle)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td colspan="3">{{ $detalle->codigo_articulo }}</td>
                    <td>{{ $detalle->codigo_barra }}</td>
                    <td>{{ $detalle->descripcion }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td><input type="checkbox"></td>
                    <td><input type="checkbox"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <table width="1050px">
        <thead>
            <tr>
                <th scope="row">COMENTARIOS</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td height="300px"></td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th colspan="4">Firmas</th>
            </tr>

            <tr>
                <th colspan="2">Por almacen</th>
                <th colspan="2">Por caja</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th width="300px">Nombre</th>
                <td width="300px"></td>

                <th width="300px">Nombre</th>
                <td width="300px"></td>
            </tr>

            <tr>
                <th width="300px">Apellido</th>
                <td width="300px"></td>

                <th width="300px">Apellido</th>
                <td width="300px"></td>
            </tr>

            <tr>
                <th width="300px">Firma</th>
                <td width="300px"></td>

                <th width="300px">Firma</th>
                <td width="300px"></td>
            </tr>
        </tbody>
    </table>

@endsection
