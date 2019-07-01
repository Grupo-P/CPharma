@extends('layouts.default')

<style>
    
    .full-height {
        height: 90vh;
    }
    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }
    .position-ref {
        position: relative;
    }
    .content {
        text-align: center;
    }
    .title {
        font-size: 84px;
    }
    .m-b-md {
        margin-top: 35%;
    }

    .fondopantalla{
        background-image:url(/assets/img/Logo.jpg);
        background-size: 20%;
    }

    .frameS{
        z-index: 100;
        background-color:rgba(255,255,255,0.7);
        width: 100%;
        height: 100%;
    }

    .a{
        text-decoration: none;
    }
</style>

@section('title')
    Bienvenido
@endsection

@section('content')
    <main role="main" class="content flex-center position-ref full-height fondopantalla">
        <div class="frameS">
            <a href="{{ url('/') }}">
                <h1 class="title m-b-md text-info">PROXIMAMENTE</h1>
            </a>
        </div>
    </main>
@endsection