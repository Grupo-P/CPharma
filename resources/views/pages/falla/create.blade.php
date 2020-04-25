@extends('layouts.modelUser')

@section('title')
    Registro de Fallas
@endsection

@section('content')
<!-- Modal Guardar -->
    @if (session('Error'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-exclamation-triangle text-danger"></i>{{ session('Error') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">La falla no fue almacenada</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    @if (session('Saved'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Saved') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Falla almacenada con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <h1 class="h5 text-info">
        <i class="fas fa-cart-arrow-down"></i>
        Registro de Fallas
    </h1>

    <hr class="row align-items-start col-12">
    
    {!! Form::open(['route' => 'falla.store', 'method' => 'POST']) !!}
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
                <th scope="row">{!! Form::label('usuario', 'Nombre de cajero') !!}</th>
                <td>{!! Form::text('usuario', null, [ 'class' => 'form-control', 'placeholder' => 'Sergio Cova', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('falla', 'Falla') !!}</th>
                <td>{!! Form::textarea('falla', null, [ 'class' => 'form-control', 'placeholder' => 'ACETAMINOFEN JARABE 120ML', 'rows' => '2', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('cliente', 'Nombre de cliente') !!}</th>
                <td>{!! Form::text('cliente', null, [ 'class' => 'form-control', 'placeholder' => 'Pedro Perez', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('telefono', 'Telefono de cliente') !!}</th>
                <td>{!! Form::text('telefono', null, [ 'class' => 'form-control', 'placeholder' => '04XX-XXXXXXX', 'autofocus']) !!}</td>
            </tr>
            <input type="hidden" name="estacion" value="<?php echo(gethostbyaddr($_SERVER['REMOTE_ADDR']));?>">
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!} 
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
        $('#exampleModalCenter').modal('show')
    </script>
@endsection