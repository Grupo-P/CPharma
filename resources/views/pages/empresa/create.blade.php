<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Empresa
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
                <h4 class="h6">La empresa no fue almacenada, el RIF ya esta registrado</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    <h1 class="h5 text-info">
        <i class="fas fa-plus"></i>
        Agregar empresa
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/empresa/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'empresa.store', 'method' => 'POST']) !!}
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
                <th scope="row">{!! Form::label('nombre', 'Nombre') !!}</th>
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Farmacia Tierra Negra C.A.', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('rif', 'RIF') !!}</th>
                <td>{!! Form::text('rif', null, [ 'class' => 'form-control', 'placeholder' => 'J-400145717' , 'required', 'pattern' => '^[A-Za-z]-\d{9}$']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('telefono', 'Telefono') !!}</th>
                <td>{!! Form::text('telefono', null, [ 'class' => 'form-control', 'placeholder' => '0261-9876543,0212-1234567', 'required', 'pattern' => '[0-9_,-]*']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('direccion', 'Direccion') !!}</th>
                <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Calle 72 esquina av. 14A, local nro. 13a-99 sector tierra negra Maracaibo Zulia zona postal 4002', 'rows' => '2', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('observacion', 'Observaciones') !!}</th>
                <td>{!! Form::textarea('observacion', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes de la empresa', 'rows' => '3']) !!}</td>
            </tr>
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