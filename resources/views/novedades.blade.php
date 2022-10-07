@php
    namespace App\Models;
    use App\Models\Core\Favoritos;
@endphp

@extends('adminlte::page')

@section('title', 'Noveades')

@section('footer')
    <!-- Footer theme -->
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"></script>    
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
@stop

@section('content_header')
    <!-- Seccion de favoritos -->
        @include('favoritos')
    <!-- Seccion de favoritos -->

    <!-- Icono de favorito en titulo-->
    @php
        $id_favorito = null;
        $icono_favorito = 'far fa-star';
        $favorito = Favoritos::validar_favorito('novedades',auth()->user()->id);
        if($favorito){
            $id_favorito = $favorito[0]['id'];
            $icono_favorito = 'fas fa-star';
        }
    @endphp
    <h1>
        <form action="{{route('core.favoritos.gestionar')}}" method="POST">
            <input type="hidden" name="id" value="{{$id_favorito}}">
            <input type="hidden" name="nombre" value="Novedades">
            <input type="hidden" name="ruta" value="novedades">
            <input type="hidden" name="user_favoritos" value="{{auth()->user()->id}}">
            @csrf
            <button type="submit" style="display:inline-block; border:0px; background-color: transparent;">
                <i class="{{$icono_favorito}} text-warning"></i>
            </button>
            Novedades
        </form>
    </h1>
    <!-- Icono de favorito en titulo-->
@stop

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-light alert-dismissible fade show text-dark shadow" role="alert">            
            <strong><i class="fa fa-exclamation"></i>&nbsp;&nbsp;{{session('message')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="card col-12">
            <div class="card-header">
                Bienvenido <strong>{{auth()->user()->name}}</strong>, estas usando <strong>{{application_name()}} {{last_version()}}</strong> Acá están las novedades que tenemos para ti.
            </div>
            <div class="card-body">
                {{release_changes()}}
            </div>
        </div>
    </div>
@stop