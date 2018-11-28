@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tablero</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}layout
                        </div>
                    @endif

                    Has iniciado sesión en app prueba de layout!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
