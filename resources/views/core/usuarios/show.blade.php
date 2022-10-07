@extends('adminlte::page')

@section('title', 'Ver Usuario')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    
@stop

@section('js')
    <script>
        //Tooltip
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@stop

@section('content_header')
    <!-- Seccion de favoritos -->
        @include('favoritos')
    <!-- Seccion de favoritos -->
    <h1>Ver Usuario</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.usuarios.index')}}">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ver Usuario</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead class="table-dark"><tr><th colspan="2"></th></tr></thead>
                <tbody>
                    <tr>
                        <td>Foto de perfil</td>
                        <td>
                            <img src="{{$url_imagen}}" alt="Foto de perfil" class="img-circle elevation-2" style="display:block;" width="80" height="80"/>                        
                        </td>                        
                    </tr>
                    <tr>
                        <td>Nro</td>
                        <td>{{$usuario->id}}</td>
                    </tr>
                    <tr>
                        <td>Nombre</td>
                        <td>{{$usuario->name}}</td>
                    </tr>
                    <tr>
                        <td>Cedula</td>
                        <td>{{$usuario->documento}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{$usuario->email}}</td>
                    </tr>
                    <tr>
                        <td>Estado</td>
                        <td>
                            @if($usuario->activo==1)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa"><i class="fas fa-check"></i></span>
                            @elseif($usuario->activo==0)
                                <span class="text-warning" data-toggle="tooltip" data-placement="left" title="Inactiva"><i class="fas fa-ban"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($usuario->borrado==0)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="OK"><i class="fas fa-check"></i></span>
                            @elseif($usuario->borrado==1)
                                <span class="text-danger" data-toggle="tooltip" data-placement="left" title="Borrada"><i class="fas fa-trash-alt"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Creado Por</td>
                        <td>@if($creadoPor){{$creadoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de creación</td>
                        <td>@if($creadoPor){{$usuario->created_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Actualizado Por</td>
                        <td>@if($actualizadoPor){{$actualizadoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de actualización</td>
                        <td>@if($actualizadoPor){{$usuario->updated_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Borrado Por</td>
                        <td>@if($borradoPor){{$borradoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de borrado</td>
                        <td>@if($borradoPor){{$usuario->deleted_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Roles</td>
                        <td>{{$usuario->adminlte_desc()}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop