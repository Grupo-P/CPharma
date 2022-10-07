@extends('adminlte::page')

@section('title', 'Ver Conexión')

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
    <h1>Ver Conexión</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.conexiones.index')}}">Conexiónes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ver Conexión</li>
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
                        <td>{{$conexione->id}}</td>
                    </tr>
                    <tr>
                        <td>Nombre</td>
                        <td>{{$conexione->nombre}}</td>
                    </tr>
                    <tr>
                        <td>Nombre para mostrar</td>
                        <td>{{$conexione->nombre_mostrar}}</td>
                    </tr>
                    <tr>
                        <td>Siglas</td>
                        <td>{{$conexione->siglas}}</td>
                    </tr>                    
                    <tr>
                        <td>Dirrección IP</td>
                        <td>{{$conexione->ip_address}}</td>
                    </tr>
                    <tr>
                        <td>Driver de la base de datos</td>
                        <td>{{$conexione->driver_db}}</td>
                    </tr>
                    <tr>
                        <td>Instancia de la base de datos</td>
                        <td>{{$conexione->instancia_db}}</td>
                    </tr>
                    <tr>
                        <td>Usuario de la base de datos</td>
                        <td>{{$conexione->usuario}}</td>
                    </tr>
                    <tr>
                        <td>Clave de la base de datos</td>
                        <td>{{$conexione->clave}}</td>
                    </tr>
                    <tr>
                        <td>Base de datos online</td>
                        <td>{{$conexione->db_online}}</td>
                    </tr> 
                    <tr>
                        <td>Base de datos offline</td>
                        <td>{{$conexione->db_offline}}</td>
                    </tr> 
                    <tr>
                        <td>Es online</td>
                        <td>
                            @if($conexione->online==1)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa"><i class="fas fa-check"></i></span>
                            @elseif($conexione->online==0)
                                <span class="text-warning" data-toggle="tooltip" data-placement="left" title="Inactiva"><i class="fas fa-ban"></i></span>
                            @endif
                        </td>
                    </tr>                     
                    <tr>
                        <td>Estado</td>
                        <td>
                            @if($conexione->activo==1)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa"><i class="fas fa-check"></i></span>
                            @elseif($conexione->activo==0)
                                <span class="text-warning" data-toggle="tooltip" data-placement="left" title="Inactiva"><i class="fas fa-ban"></i></span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            @if($conexione->borrado==0)
                                <span class="text-success" data-toggle="tooltip" data-placement="left" title="OK"><i class="fas fa-check"></i></span>
                            @elseif($conexione->borrado==1)
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
                        <td>@if($creadoPor){{$conexione->created_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Actualizado Por</td>
                        <td>@if($actualizadoPor){{$actualizadoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de actualización</td>
                        <td>@if($actualizadoPor){{$conexione->updated_at}}@endif</td>
                    </tr>
                    <tr>
                        <td>Borrado Por</td>
                        <td>@if($borradoPor){{$borradoPor->name}}@endif</td>
                    </tr>
                    <tr>
                        <td>Fecha de borrado</td>
                        <td>@if($borradoPor){{$conexione->deleted_at}}@endif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop