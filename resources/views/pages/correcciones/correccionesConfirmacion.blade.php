@extends('layouts.model')

@section('title')
  Correcciones Confirmacion
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
        Modificar Atributos | Confirmar
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

    {{-- Si los datos no son validos redirigimos --}}
    @if(!isset($resultado) && $formularioConfirmado)
        <div class="alert alert-danger mb-2">
            <small>Información no valida</small>
        </div>
        <a class="btn btn-danger" href="{{ route('atributos.masivos') }}">Atrás</a>
    @else
        {{-- Lista de resultados exitosos --}}
        <div class="px-4 mt-5">
            {{-- Titulo --}}
            <div class="mb-4 border-bottom border-secondary pb-2">
                <h4 class="h4">Articulos encontrados <span class="text-success"><i class="fas fa-check"></i></span></h4>
            </div>

            {{-- Lista --}}
            <ul class="lista exitosa px-4">
                @if(is_array($resultado))
                    @foreach($resultado['exitoso'] as $index => $item)
                        <li class="lista_item d-flex align-items-center">
                            {{-- Index --}}
                            <div class="mr-2">
                                <span class="badge badge-dark">{{ $index + 1 }}</span>
                            </div>

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
                @endif
            </ul>
        </div>

        {{-- Lista de resultados fallidos --}}
        <div class="px-4 mt-5">
            {{-- Titulo --}}
            <div class="mb-4 border-bottom border-secondary pb-2">
                <h4 class="h4">Códigos no encontrados <span class="text-danger"><i class="fas fa-times"></i></span></h4>
            </div>

            {{-- Lista --}}
            <ul class="lista exitosa px-4">
                @if(is_array($resultado))
                    @foreach($resultado['fallido'] as $index => $item)
                        <li class="lista_item d-flex align-items-center">
                            {{-- Index --}}
                            <div class="mr-2">
                                <span class="badge badge-dark">{{ $index + 1 }}</span>
                            </div>

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
                @endif
            </ul>
        </div>

        <div class="px-4 mt-2">
            @include('pages.correcciones.form.formulario')
        </div>
    @endif
@endsection