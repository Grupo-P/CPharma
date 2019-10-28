@extends('layouts.basic_welcome')

@section('title')
    Bienvenido
@endsection

@section('content')
    <main role="main" class="CP-Welcome-content CP-Welcome-flex-center CP-Welcome-position-ref CP-Welcome-full-height">
        <h1 class="CP-Welcome-title CP-Latido m-b-md">
            <b>
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-syringe text-success"></i>                
                    <span class="text-info">CPharma</span>
                </a>
            </b>
        </h1>
    </main>
@endsection