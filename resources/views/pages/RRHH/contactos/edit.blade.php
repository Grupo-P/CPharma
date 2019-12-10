@extends('layouts.model')

@section('title', 'Modificar contacto')

@section('content')
  <!-- Modal Guardar -->
  @if(session('Error'))
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
            <h4 class="h6">El contacto no fue almacenado</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-edit"></i>&nbsp;Modificar contacto
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/contactos/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm" data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::model($contactos, ['route' => ['contactos.update', $contactos], 'method' => 'PUT', 'id' => 'crear_contacto', 'class' => 'form-group']) !!}
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
            <th scope="row">{!! Form::label('nombre', 'Nombres *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Maria Raquel', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\']+$', 'autofocus', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('apellido', 'Apellidos *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('apellido', null, [ 'class' => 'form-control', 'placeholder' => 'Herrera Perez', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\']+$', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('telefono', 'Teléfono') !!}
            </th>
            
            <td>
              {!! Form::tel('telefono', null, [ 'class' => 'form-control', 'placeholder' => '0414-1234567']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('correo', 'Correo') !!}</th>
            <td>{!! Form::email('correo', null, [ 'class' => 'form-control', 'placeholder' => 'mherrera@farmacia72.com']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('cargo', 'Cargo *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('cargo', null, [ 'class' => 'form-control', 'placeholder' => 'Gerente de RRHH', 'required']) !!}</td>
          </tr>
        </tbody>
      </table>
      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id' => 'enviar']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

      //Objetos DOM JavaScript
      var telefono_celular = document.querySelector('#telefono_celular');
      var telefono_habitacion = document.querySelector('#telefono_habitacion');

      //Objetos DOM JQuery
      var enviar = $('#enviar');
      var crear_contacto = $('#crear_contacto');
      var cedula = $('#cedula');

      //Expresiones regulares
      var regExp = /^0[1246]{3}-[0-9]{7}$/;

      cedula.val(cedula.val().substring(2));

      enviar.click(function() {

        if((telefono_celular.value == '') && (telefono_habitacion.value == '')) {

          telefono_celular.setCustomValidity('Debe ingresar al menos un Teléfono');
          telefono_habitacion.setCustomValidity('Debe ingresar al menos un Teléfono');
        }
        else if((telefono_celular.value != '') && (!regExp.test(telefono_celular.value))) {

          telefono_celular.setCustomValidity('Ingrese un teléfono con el patrón especificado 0xxx-xxxxxxx');
        }
        else if((telefono_habitacion.value != '') && (!regExp.test(telefono_habitacion.value))) {

          telefono_habitacion.setCustomValidity('Ingrese un teléfono con el patrón especificado 0xxx-xxxxxxx');
        }

      });

      crear_contacto.submit(function(e) {

        if((telefono_celular.value == '') && (telefono_habitacion.value == '')) {
          
          e.preventDefault();
        }
        else if((telefono_celular.value != '') && (!regExp.test(telefono_celular.value))) {
          
          e.preventDefault();
        }
        else if((telefono_habitacion.value != '') && (!regExp.test(telefono_habitacion.value))) {
          
          e.preventDefault();
        }

      });

      $('#telefono_celular, #telefono_habitacion').on({
        
        keydown: function(e) {

          telefono_celular.setCustomValidity('');
          telefono_habitacion.setCustomValidity('');
        }

      });
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection