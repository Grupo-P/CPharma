@extends('layouts.model')

@section('title')
    Dias en cero
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">  
    </script>
    <script src="{{ asset('assets/js/filter.js') }}"> 
    </script>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-balance-scale"></i>
    Dias en cero
  </h1>
  <hr class="row align-items-start col-12">

  <div class="card-deck">
    <div class="card border-info mb-3" style="width: 14rem;">     
        <div class="card-body text-left bg-info">
          <h2 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-industry"></i>
              1
            </span>
          </h2>
          <p class="card-text text-white">Capturar la informaci&oacute;n</p>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
          <a href="/diasCero/" class="btn btn-outline-info btn-sm">Click aqui</a>
        </div>
    </div>
  </div>
@endsection