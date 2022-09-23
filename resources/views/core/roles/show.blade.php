@extends('adminlte::page')

@section('title', 'Ver Rol')

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
    <h1>Ver Rol</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.roles.index')}}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ver Rol</li>
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
                        <td>Nro</td>
                        <td>{{$role->id}}</td>
                    </tr>
                    <tr>
                        <td>Variable</td>
                        <td>{{$role->name}}</td>
                    </tr>                    
                    <tr>
                        <td>Estado</td>
                        <td>
                            @if($role->activo==1)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa"><i class="fas fa-check"></i></span>
                            @elseif($role->activo==0)
                                <span class="text-warning" data-toggle="tooltip" data-placement="left" title="Inactiva"><i class="fas fa-ban"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($role->borrado==0)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="OK"><i class="fas fa-check"></i></span>
                            @elseif($role->borrado==1)
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
                        <td>@if($creadoPor){{$role->created_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Actualizado Por</td>
                        <td>@if($actualizadoPor){{$actualizadoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de actualización</td>
                        <td>@if($actualizadoPor){{$role->updated_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Borrado Por</td>
                        <td>@if($borradoPor){{$borradoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de borrado</td>
                        <td>@if($borradoPor){{$role->deleted_at}}@endif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop