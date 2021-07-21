@extends('layouts.contabilidad')

@section('title')
    Pizarra de deudas
@endsection

@section('content')
    @php
        function fecha_ultimo_pago($nombre_proveedor)
        {
            $proveedor = DB::select("
                SELECT
                    *
                FROM
                    cont_proveedores
                WHERE
                    saldo > 0 AND nombre_proveedor = '$nombre_proveedor'
            ");

            $efectivo = DB::select("
                SELECT
                    created_at
                FROM
                    cont_pagos_efectivo
                WHERE
                    id_proveedor = '{$proveedor[0]->id}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $bancario = DB::select("
                SELECT
                    created_at
                FROM
                    cont_pagos_bancarios
                WHERE
                    id_proveedor = '{$proveedor[0]->id}'
                ORDER BY
                    created_at DESC
                LIMIT 1
            ");

            $fecha_efectivo = isset($efectivo[0]->created_at) ? $efectivo[0]->created_at : null;
            $fecha_bancario = isset($bancario[0]->created_at) ? $bancario[0]->created_at : null;

            if ($fecha_efectivo >= $fecha_bancario) {
                return $fecha_efectivo;
            } else {
                return $fecha_bancario;
            }
        }
    @endphp

    <h1 class="h5 text-info">
        <i class="fas fa-info-circle"></i>
        Pizarra de deudas
    </h1>

    <hr class="row align-items-start col-12">

    <h6 align="center">Proveedores con saldo positivo</h6>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky">Saldo</th>
                <th scope="col" class="CP-sticky">Fecha último pago</th>
                <th scope="col" class="CP-sticky">Días último pago</th>
                <th scope="col" class="CP-sticky">Fecha último ingreso</th>
                <th scope="col" class="CP-sticky">Días último ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positivos as $positivo)
                <tr>
                    <th align="center">{{$loop->iteration}}</th>
                    <td align="center" class="CP-barrido">
                        <a href="" style="text-decoration: none; color: black;" target="_blank">{{$positivo->proveedor}}</a>
                    </td>
                    <td align="center">{{$positivo->saldo}}</td>
                    <td align="center">{{fecha_ultimo_pago($positivo->proveedor)}}</td>
                    <td align="center">{{$positivo->dias_ultimo_pago}}</td>
                    <td align="center">{{$positivo->fecha_ultimo_ingreso}}</td>
                    <td align="center">{{$positivo->dias_ultimo_ingreso}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <h6 align="center">Proveedores con saldo negativo</h6>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre del proveedor</th>
                <th scope="col" class="CP-sticky">Saldo</th>
                <th scope="col" class="CP-sticky">Fecha último pago</th>
                <th scope="col" class="CP-sticky">Días último pago</th>
                <th scope="col" class="CP-sticky">Fecha último ingreso</th>
                <th scope="col" class="CP-sticky">Días último ingreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($negativos as $negativos)
                <tr>
                    <th align="center">{{$loop->iteration}}</th>
                    <td align="center" class="CP-barrido">
                        <a href="" style="text-decoration: none; color: black;" target="_blank">{{$negativos->proveedor}}</a>
                    </td>
                    <td align="center">{{$negativos->saldo}}</td>
                    <td align="center">{{$negativos->fecha_ultimo_pago}}</td>
                    <td align="center">{{$negativos->dias_ultimo_pago}}</td>
                    <td align="center">{{$negativos->fecha_ultimo_ingreso}}</td>
                    <td align="center">{{$negativos->dias_ultimo_ingreso}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show')
    </script>

@endsection
