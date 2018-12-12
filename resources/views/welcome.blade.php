<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.default')

<style>
    .full-height {
        height: 100vh;
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
        margin-bottom: 30px;
    }
</style>

@section('title')
    Bienvenido
@endsection

@section('content')
    <main role="main" class="content flex-center position-ref full-height">
        <h1 class="title m-b-md">
            <b>
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-syringe text-success"></i>                
                    <span class="text-info">CPharma</span>
                </a>
            </b>
        </h1>
    </main>
@endsection