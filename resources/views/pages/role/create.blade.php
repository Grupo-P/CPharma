@extends('layouts.model')

@section('title')
    Rol
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
                <h4 class="h6">El rol no pudo ser almaceno</h4>
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
        Agregar rol
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/rol/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'rol.store', 'method' => 'POST']) !!}
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
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'USUARIO', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('descripcion', 'Descripcion') !!}</th>
                <td>{!! Form::textarea('descripcion', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes del rol', 'rows' => '2', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('read', '¿Lee Informacion?') !!}</th>
                <td>
                    <select name="read" class="form-control" required="required">
                        <option selected="selected">Seleccione...</option>
                        <option>SI</option>
                        <option>NO</option>                        
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('create', '¿Agrega Informacion?') !!}</th>
                <td>
                    <select name="create" class="form-control" required="required">
                        <option selected="selected">Seleccione...</option>
                        <option>SI</option>
                        <option>NO</option>                        
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('update', '¿Modifica Informacion?') !!}</th>
                <td>
                    <select name="update" class="form-control" required="required">
                        <option selected="selected">Seleccione...</option>
                        <option>SI</option>
                        <option>NO</option>                        
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('delete', '¿Elimina Informacion?') !!}</th>
                <td>
                    <select name="delete" class="form-control" required="required">
                        <option selected="selected">Seleccione...</option>
                        <option>SI</option>
                        <option>NO</option>                        
                    </select>
                </td>
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