@extends('layouts.contabilidad')

@section('title')
  Dashboard
@endsection

@section('content')

<h1 class="h5 text-info">
    <i class="fas fa-columns"></i>
    Dashboard
</h1>

<hr class="row align-items-start col-12">

<!-- CONTACTO -->
<hr class="row align-items-start col-12">
    <div class="card-deck">
        <div class="card border-info" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h2 class="card-title">
                <span class="card-text text-warning">
                    <i class="far fa-lightbulb CP-beep"></i>
                </span>
                <span class="card-text text-white">
                    Tienes una idea.?
                </span>
            </h2>
            <div class="text-center">
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="far fa-keyboard"></i></h3>
                    <h5 class="card-text text-white">Redacta tu idea</h5>
                </div>
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i>
                    </h3>
                </div>
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="far fa-envelope"></i></h3>
                    <h5 class="card-text text-white">Enviala a scova@farmacia72.com.ve</h5>
                </div>
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i></h3>
                </div>
                    <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="far fa-clock"></i></h3>
                    <h5 class="card-text text-white">Espera nuestro contacto</h5>
                </div>
            </div>
            </div>
        </div>
    </div>
<!-- CONTACTO -->
@endsection
