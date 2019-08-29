@extends('layouts.model')

@section('title')
    Conexion
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
                <h4 class="h6">La conexion no pudo ser almacenada</h4>
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
        Agregar conexion
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/conexion/" method="POST" style="display: inline;">
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'conexion.store', 'method' => 'POST']) !!}
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
                <th scope="row">{!! Form::label('siglas', 'Siglas') !!}
                </th>
                <td>
                    <select name="siglas" class="form-control">
                        <?php
                        foreach($sedes as $sede){
                        ?>
                        <option value="<?php echo $sede; ?>"><?php echo $sede; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>            
            <tr>
                <th scope="row">{!! Form::label('instancia', 'Instancia') !!}</th>
                <td>{!! Form::text('instancia', null, [ 'class' => 'form-control', 'placeholder' => '11.25.0.41\SMARTPHARMA', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('base_datos', 'Base de Datos') !!}</th>
                <td>{!! Form::text('base_datos', null, [ 'class' => 'form-control', 'placeholder' => 'cpharmaftn', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('usuario', 'Usuario') !!}</th>
                <td>{!! Form::text('usuario', null, [ 'class' => 'form-control', 'placeholder' => 'admin', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('credencial', 'Clave') !!}</th>
                <td>{!! Form::text('credencial', null, [ 'class' => 'form-control', 'placeholder' => 'tierranegra19*', 'autofocus', 'required']) !!}</td>
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