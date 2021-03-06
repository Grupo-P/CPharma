@extends('layouts.model')

@section('title')
    Usuario
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
                <h4 class="h6">El usuario no fue almacenado, el correo ya esta registrado</h4>
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
                <th scope="row">{!! Form::label('sede', 'Sede') !!}
                </th>
                <td>
                    <select name="sede" class="form-control">
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
                <th scope="row">{!! Form::label('departamento', 'Departamento') !!}
                </th>
                <td>
                    <select name="departamento" class="form-control">
                        <?php
                        foreach($departamentos as $departamento){
                        ?>
                        <option value="<?php echo $departamento; ?>"><?php echo $departamento; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('role', 'Rol') !!}
                </th>
                <td>
                    <select name="role" class="form-control">
                        <?php
                        foreach($roles as $rol){
                        ?>
                        <option value="<?php echo $rol; ?>"><?php echo $rol; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>            
            <tr>
                <th scope="row">{!! Form::label('password', 'Contraseña') !!}</th>
                <td>{!! Form::text('password', null, [ 'class' => 'form-control', 'placeholder' => '******', 'rows' => '2']) !!}</td>
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