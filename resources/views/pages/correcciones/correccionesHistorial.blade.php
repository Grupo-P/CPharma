@php
 session()->put($keyResultado, $resultado);   
@endphp

@extends('layouts.model')

@section('title')
  Correcciones Resultado
@endsection

@section('scriptsHead')
@endsection

@section('content')
    <style>
        .lista {
            list-style: none !important;
        }

        .lista_item > div {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 16px !important;
        }

        .codigoDivisor {
            margin: 0px 8px;
            padding: 0px 8px;
            border-left: 2px solid #444444;
            border-right: 2px solid #444444;
        }
    </style>

    <h1 class="h5 text-info">
        <i class="fas fa-award"></i>
        Modificar Atributos | Ejecutados
    </h1>
    <hr class="row align-items-start col-12">
    
    <?php
        use compras\Configuracion;
        $_GET['SEDE'] = FG_Mi_Ubicacion();
    ?>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
        $('#exampleModalCenter').modal('show')
    </script>

    {{-- Lista de resultados exitosos --}}
    <div class="px-4 mt-5">
        {{-- Titulo --}}
        <div class="mb-4 border-bottom border-secondary pb-2">
            <h4 class="h4">Correcciones Exitosas <span class="text-success"><i class="fas fa-check"></i></span></h4>
        </div>

        {{-- Lista --}}
        <ul class="lista exitosa px-4">
            @foreach($resultado['exitoso'] as $item)
                <li class="lista_item">
                    <div>
                        {{-- Nombre --}}
                        <div class="text-success font-weight-bold">
                            <span>{{ mb_convert_encoding($item['descripcion'] , 'UTF-8', 'UTF-8') }}</span>
                        </div>

                        {{-- Codigo --}}
                        <div class="font-weight-bold codigoDivisor">
                            <span>{{ $item['codigo_barra'] }}</span>
                        </div>

                        {{-- Icono --}}
                        <div class="text-success">
                            <span><i class="fas fa-check"></i></span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Lista de resultados fallidos --}}
    <div class="px-4 mt-5">
        {{-- Titulo --}}
        <div class="mb-4 border-bottom border-secondary pb-2">
            <h4 class="h4">Correcciones Fallidas <span class="text-danger"><i class="fas fa-times"></i></span></h4>
        </div>

        {{-- Lista --}}
        <ul class="lista exitosa px-4">
            @foreach($resultado['fallido'] as $item)
                <li class="lista_item">
                    <div>
                        {{-- Titulo --}}
                        <div class="font-weight-bold">
                            <span>Código</span>
                        </div>

                        {{-- Codigo --}}
                        <div class="font-weight-bold codigoDivisor">
                            <span>{{ $item['barra'] ?? $item['interno'] }}</span>
                        </div>

                        {{-- Icono --}}
                        <div class="text-danger">
                            <span><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-2 px-4">
        <a class="btn btn-danger" href="{{ route('atributos.masivos') }}">Volver</a>
    </div>
@endsection