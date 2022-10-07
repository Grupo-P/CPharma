@extends('adminlte::page')

@section('title', 'Ver Auditoría')

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
    <h1>Ver Auditoría</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.auditorias.index')}}">Auditorías</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ver Auditoría</li>
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
                        <td>{{ $auditoria[0]['id'] }}</td>
                    </tr>
                    <tr>
                        <td>Modelo</td>
                        <td>{{ $auditoria[0]['subject_type'] }}</td>
                    </tr>
                    <tr>
                        <td>Evento</td>
                        <td style="text-transform: capitalize;">{{ $auditoria[0]['event'] }}</td>
                    </tr>
                    <tr>
                        <td>Desencadenador</td>
                        <td>{{ $auditoria[0]['causer_type'] }}</td>
                    </tr>
                    <tr>
                        <td>Fecha</td>
                        <td>{{ $auditoria[0]['updated_at'] }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-striped mt-5">
                <thead class="table-dark text-center">                    
                    <tr>
                        <th>Antes</th>
                        <th>Ahora</th>
                    </tr>
                </thead>                
                <tbody>                    
                    <tr>
                        <td>
                            @php 
                            if($old != false){
                                foreach ($old as $clave => $valor) {                                                                                                  
                                    if (array_key_exists($clave, $attributes)) {                                    
                                        if($old[$clave]==$attributes[$clave]){                                        
                                            echo "<span><strong>$clave:</strong> $valor</span>";
                                            echo "</br>";
                                        }else{                                        
                                            echo "<span class='text-white bg-danger'><strong>$clave:</strong> $valor</span>";
                                            echo "</br>";
                                        }
                                    }                            
                                }
                            } 
                            else{ 
                                echo "<p class='text-white bg-danger text-center'>No existen valores previos</p>";
                                echo "</br>";
                            }                                                      
                            @endphp
                        </td>

                        <td>
                            @php
                            if($old != false){
                                foreach ($attributes as $clave => $valor) {                                                                                                  
                                if (array_key_exists($clave, $old)) {                                    
                                    if($attributes[$clave]==$old[$clave]){                                        
                                        echo "<span><strong>$clave:</strong> $valor</span>";
                                        echo "</br>";
                                    }else{                                        
                                        echo "<span class='text-white bg-success'><strong>$clave:</strong> $valor</span>";
                                        echo "</br>";
                                    }
                                }                            
                            }
                            }else{
                                foreach ($attributes as $clave => $valor) {                                                                                                                                                                           
                                    echo "<span><strong>$clave:</strong> $valor</span>";                                                                 
                                    echo "</br>";
                                }
                            }                            
                            @endphp
                        </td>                                                
                    </tr>                    
                </tbody>
            </table>
        </div>
    </div>
@stop