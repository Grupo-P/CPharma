<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Usuario
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-plus"></i>
        Agregar usuario
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/usuario/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'usuario.store', 'method' => 'POST']) !!}
    <fieldset>

        <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row"></th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">{!! Form::label('name', 'Nombre') !!}</th>
                <td>{!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Pedro Perez', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('email', 'Correo') !!}</th>
                <td>{!! Form::text('email', null, [ 'class' => 'form-control', 'placeholder' => 'pperez@empresa.com']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('role', 'Rol') !!}</th>
                <td>{!! Form::text('role', null, [ 'class' => 'form-control', 'placeholder' => 'ROL']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('password', 'Contraseña') !!}</th>
                <td>{!! Form::text('password', null, [ 'class' => 'form-control', 'placeholder' => '******', 'rows' => '2']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('passwordC', 'Confirmar Contraseña') !!}</th>
                <td>{!! Form::text('passwordC', null, [ 'class' => 'form-control', 'placeholder' => '******', 'rows' => '2']) !!}</td>
            </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!} 

    {{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Rol') }}</label>

                            <div class="col-md-6">
                                <input id="role" type="text" class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role" value="{{ old('role') }}" required autofocus>

                                @if ($errors->has('role'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('role') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div> --}}
@endsection