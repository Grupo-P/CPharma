@php    
    use App\Models\Core\Licencia;
    $validate_licence = Licencia::validate_licence();
    $datetime1 = new DateTime(date('Y-m-d'));
    $datetime2 = new DateTime($validate_licence['fecha']);
    $interval = $datetime1->diff($datetime2);
    $diff_dias = $interval->format('%a');
    $interval = ($validate_licence['validate_licence'])? $interval->format('%R%a') : 'Licencia';
    $color = ($validate_licence['validate_licence'])?( ($diff_dias > 5)?'success':'danger' ): 'danger';
    $icono = ($validate_licence['validate_licence'])? 'unlock-alt' : 'lock';
@endphp

@extends('adminlte::page')

@section('title', 'Dashboard')

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
    <h1>Dashboard</h1>
@stop

@section('content')

    @if(auth()->user()->cambio_clave == 1)
        <div class="alert alert-danger alert-dismissible fade show text-white shadow" role="alert">            
            <strong><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Es necesario que cambie su contraseña</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12 col-md-3 col-lg-2">
            <div class="shadow small-box bg-{{$color}}">
                <div class="inner">
                    <h3>{{$interval}}</h3>
                    <p>{{$validate_licence['mensaje'];}}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-{{$icono}}"></i>
                </div>
            </div>
        </div>
    </div>
@stop