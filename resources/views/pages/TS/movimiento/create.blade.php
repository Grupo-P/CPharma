@extends('layouts.model')

@section('title', 'Crear movimiento')

@section('content')
  <!-- Modal Guardar -->
  @if(session('Error'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
              <i class="fas fa-exclamation-triangle text-danger"></i>
              {{ session('Error') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">
              El movimiento no fue almacenado
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>&nbsp;Agregar movimiento
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/movimientos/" method="GET" style="display: inline;">  
    <input type="hidden" name="tasa_ventas_id" value="{{$_GET["tasa_ventas_id"]}}">
    <button type="submit" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'movimientos.store', 'method' => 'POST', 'id' => 'crear_movimientos', 'class' => 'form-group']) !!}
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
            <th scope="row">{!! Form::label('nombres', 'Nombres *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('nombres', null, [ 'class' => 'form-control', 'placeholder' => 'Maria Raquel', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\']+$', 'autofocus', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('apellidos', 'Apellidos *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('apellidos', null, [ 'class' => 'form-control', 'placeholder' => 'Herrera Perez', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\']+$', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('cedula', 'Cédula *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <table style="width: 100%;">
                <tr style="background-color: transparent;">
                  <td>
                    {!! Form::select('tipo', ['V' => 'V', 'E' => 'E'], null, [ 'class' => 'form-control']) !!}
                  </td>

                  <td>
                    {!! Form::text('cedula', null, [ 'class' => 'form-control', 'placeholder' => '24921001', 'pattern' => '^[0-9]{7,}$', 'required']) !!}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <th scope="row">
              <label for="telefono_celular">Teléfono celular</label>
            </th>
            
            <td>
              <input type="tel" class="form-control" name="telefono_celular" id="telefono_celular" placeholder="0414-1234567" pattern="^0[1246]{3}-[0-9]{7}$">
            </td>
          </tr>

          <tr>
            <th scope="row">
              <label for="telefono_habitacion">Teléfono de habitación</label>
            </th>
            
            <td>
              <input type="tel" class="form-control" name="telefono_habitacion" id="telefono_habitacion" placeholder="0261-1234567" pattern="^0[1246]{3}-[0-9]{7}$">
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('correo', 'Correo') !!}</th>
            <td>{!! Form::email('correo', null, [ 'class' => 'form-control', 'placeholder' => 'mherrera@farmacia72.com']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('tipo_relacion', 'Tipo de relación *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              {!! Form::select('tipo_relacion', [
                '' => 'Seleccione una opción',
                'Ince' => 'Ince', 
                'Pasante' => 'Pasante',
                'Trabajador regular' => 'Trabajador regular',
              ], null, ['class' => 'form-control', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('relaciones_laborales', 'Relaciones con trabajadores *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              {!! Form::select('relaciones_laborales', [
                '' => 'Seleccione una opción',
                'Si' => 'Si', 
                'No' => 'No',
              ], null, ['class' => 'form-control', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('como_nos_contacto', 'Como nos contactó *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              {!! Form::select('como_nos_contacto', [
                '' => 'Seleccione una opción',
                'Computrabajo' => 'Computrabajo', 
                'Bumeran' => 'Bumeran',
                'Redes sociales' => 'Redes sociales',
                'Instagram' => 'Instagram',
                'Radio' => 'Radio',
                'Recomendado' => 'Recomendado',
              ], null, ['class' => 'form-control', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('practica', 'Práctica pre-empleto *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="practica1" name="practica" value="Si" required>
                <label class="custom-control-label" for="practica1">Si</label>
              </div>

              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="practica2" name="practica" value="No">
                <label class="custom-control-label" for="practica2">No</label>
              </div>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('experiencia_laboral', 'Experiencia laboral') !!}</th>
            <td>{!! Form::textarea('experiencia_laboral', null, [ 'class' => 'form-control', 'placeholder' => 'Experiencia laboral previa del movimientos', 'rows' => '3']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('direccion', 'Dirección *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Av. 15 Delicias con calle 72', 'rows' => '3', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('observaciones', 'Observaciones') !!}</th>
            <td>{!! Form::textarea('observaciones', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes del movimientos', 'rows' => '3']) !!}</td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id' => 'enviar']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();

      //Objetos DOM JavaScript
      var telefono_celular = document.querySelector('#telefono_celular');
      var telefono_habitacion = document.querySelector('#telefono_habitacion');

      //Objetos DOM JQuery
      var enviar = $('#enviar');
      var crear_movimientos = $('#crear_movimientos');

      enviar.click(function() {

        if((telefono_celular.value == '') && (telefono_habitacion.value == '')) {

          telefono_celular.setCustomValidity('Debe ingresar al menos un Teléfono');
          telefono_habitacion.setCustomValidity('Debe ingresar al menos un Teléfono');
        }

      });

      crear_movimientos.submit(function(e) {

        if((telefono_celular.value == '') && (telefono_habitacion.value == '')) {
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