<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Proveedor
@endsection

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
                <h4 class="h6">El proveedor no fue almacenado, el correo ya esta registrado</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-edit"></i>
        Modificar proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/proveedor/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>
    {!! Form::model($proveedor, ['route' => ['proveedor.update', $proveedor], 'method' => 'PUT']) !!}
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
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Pedro', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('apellido', 'Apellido') !!}</th>
                <td>{!! Form::text('apellido', null, [ 'class' => 'form-control', 'placeholder' => 'Perez']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('telefono', 'Telefono') !!}</th>
                <td>{!! Form::text('telefono', null, [ 'class' => 'form-control', 'placeholder' => '04XX-7988326']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('correo', 'Correo') !!}</th>
                <td>{!! Form::text('correo', null, [ 'class' => 'form-control', 'placeholder' => 'pperez@empresa.com']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('cargo', 'Cargo') !!}</th>
                <td>{!! Form::text('cargo', null, [ 'class' => 'form-control', 'placeholder' => 'Proveedor']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('empresa', 'Empresa') !!}
                </th>
                <td>
                    <select name="empresa" class="form-control">
                        <?php
                        foreach($empresas as $emp){
                        ?>
                        <option value="<?php echo $emp; ?>"><?php echo $emp; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
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