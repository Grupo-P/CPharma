@extends('layouts.basic')

@section('title')
    Login 
@endsection

@section('content')
<div class="container p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header border border-info bg-info text-white"><b>{{ __('Inicio de Sesión') }}</b>
        </div>
          <div class="card-body border border-info">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group row">
                  <i class="fas fa-envelope col-sm-4 col-form-label text-md-right text-info"></i>
                  <div class="col-md-6">
                      <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                      @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('email') }}</strong>
                        </span>
                      @endif
                    </div>
                </div>
                <div class="form-group row">
                  <i class="fas fa-key col-sm-4 col-form-label text-md-right text-info"></i>
                  <div class="col-md-6">
                      <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                      @if ($errors->has('password'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
                <div class="form-group row mb-2">
                  <div class="col-md-8 offset-md-5">
                    <button type="submit" class="btn btn-info">
                      {{ __('Entrar') }}
                    </button>
                  </div>
                </div>
            </form>
          </div>
      </div>
    </div>
  </div>
</div>
@endsection