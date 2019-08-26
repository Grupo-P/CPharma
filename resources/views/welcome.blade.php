@extends('layouts.basic_welcome')

<style>
    .full-height {
        height: 82vh;
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
        transform: scale(1);
        transition: width 1s, height 1s, transform 2s;
    }
    .m-b-md {
        margin-bottom: 180px;
    }
    .title:hover{
        transform: scale(1.2);
        transition: width 1s, height 1s, transform 2s;
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