@extends('layouts.contabilidad')

@section('title')
    Pizarra de deudas
@endsection

@section('content')

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
                <th>{{$loop->iteration}}</th>
                <td align="left" class="CP-barrido">
                    <a href="" style="text-decoration: none; color: black;" target="_blank">{{$positivo->proveedor}}</a>
                </td>
                <td>{{$positivo->saldo}}</td>
                <td>{{$positivo->fecha_ultimo_pago}}</td>
                <td>{{$positivo->dias_ultimo_pago}}</td>
                <td>{{$positivo->fecha_ultimo_ingreso}}</td>
                <td>{{$positivo->dias_ultimo_ingreso}}</td>
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
                <th>{{$loop->iteration}}</th>
                <td align="left" class="CP-barrido">
                    <a href="" style="text-decoration: none; color: black;" target="_blank">{{$negativos->proveedor}}</a>
                </td>
                <td>{{$negativos->saldo}}</td>
                <td>{{$negativos->fecha_ultimo_pago}}</td>
                <td>{{$negativos->dias_ultimo_pago}}</td>
                <td>{{$negativos->fecha_ultimo_ingreso}}</td>
                <td>{{$negativos->dias_ultimo_ingreso}}</td>
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
