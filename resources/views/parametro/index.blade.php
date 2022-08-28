@extends('adminlte::page')

@section('title', 'Parametros')

@section('footer')
    <!-- Footer theme -->
@stop

@section('css') 
@stop

@section('js')   
    <script>
        $(document).ready(function(){
            $('#myTable').DataTable({
                "language":{
                    "search":"Buscar:"
                }
            });
        });
    </script>
@stop

@section('content_header')
    <h1>Parametros</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Parametros</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Variable</th>
                        <th>Valor</th>
                        <th>Descripción</th>                
                        <th>Estado</th>
                        <th>Status</th>                        
                    </tr>
                </thead>
                <tbody>            
                    @foreach($parametros as $parametro)
                        <tr>
                            <td>{{ $parametro->id }}</td>
                            <td>{{ $parametro->variable }}</td>
                            <td>{{ $parametro->valor }}</td>
                            <td>{{ $parametro->descripcion }}</td>                            

                            <td>
                                @if($parametro->activa==1)
                                    <span>Activa</span>
                                @else
                                    <span>Inactiva</span>
                                @endif
                            </td>

                            <td class="w-1/6 text-left py-3 px-4">
                                @if($parametro->deleted_at=='')
                                    <span>Ok</span>
                                @else
                                    <span>Borrada</span>
                                @endif
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>       
@stop