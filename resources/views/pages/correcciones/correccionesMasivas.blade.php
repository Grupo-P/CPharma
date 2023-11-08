@extends('layouts.model')

@section('title')
    Modificar Atributos
@endsection

@section('scriptsHead')
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-award"></i>
        Modificar Atributos
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

    @include('pages.correcciones.form.formulario')
@endsection