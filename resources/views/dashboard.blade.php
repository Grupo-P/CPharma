@extends('adminlte::page')

@section('title', 'Dashboard')

@section('footer')
    <span><a href="https://www.linkedin.com/in/covacode/" target="_blank" style="text-decoration: none; color:#869099;"> PBase v.1.0.0. Copyright © <?php echo''.date('Y'); ?> .All rights reserved.</span></a>
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

    <div class="card">
        <div class="card-header">
            Welcome to this beautiful Dashboard.
        </div>
        <div class="card-body">
            A nice little card body.
        </div>
    </div>
    
@stop