@php
    namespace App\Models;
    use App\Models\Core\Favoritos;
@endphp

@extends('adminlte::page')

@section('title', 'Sandbox 2')

@section('footer')
    <!-- Footer theme -->
@stop

@section('css')
@stop

@section('js')
    
    <!-- Sweet Alert -->
    <script type="text/javascript">
        Swal.fire(
            'Good job!',
            'You clicked the button!',
            'success'
        );
    </script>
    <!-- Sweet Alert -->

    <!-- myChart -->
    <script>        
        const data = {
            labels: [
                'Red',
                'Blue',
                'Yellow'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,            
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
    <!-- myChart -->       
@stop

@section('content_header')
    <!-- Seccion de favoritos -->
        @include('favoritos')
    <!-- Seccion de favoritos -->

    <!-- Icono de favorito en titulo-->
    @php
        $id_favorito = null;
        $icono_favorito = 'far fa-star';
        $favorito = Favoritos::validar_favorito('core.demo.sandbox2',auth()->user()->id);
        if($favorito){
            $id_favorito = $favorito[0]['id'];
            $icono_favorito = 'fas fa-star';
        }
    @endphp
    <h1 class="mt-2">
        <form action="{{route('core.favoritos.gestionar')}}" method="POST">
            <input type="hidden" name="id" value="{{$id_favorito}}">
            <input type="hidden" name="nombre" value="Sandbox 2">
            <input type="hidden" name="ruta" value="core.demo.sandbox2">
            <input type="hidden" name="user_favoritos" value="{{auth()->user()->id}}">
            @csrf
            <button type="submit" style="display:inline-block; border:0px; background-color: transparent;">
                <i class="{{$icono_favorito}} text-warning"></i>
            </button>
            Sandbox 2
        </form>
    </h1>    
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sandbox 2</li>
        </ol>
    </nav>    
@stop

@section('content')
            
    <div class="card col-6">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            A nice little card body.
        </div>
    </div>
        
    <div class="card col-6">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">        
            <canvas id="myChart"></canvas>            
        </div>
    </div>
@stop