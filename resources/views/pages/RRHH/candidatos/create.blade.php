@extends('layouts.model')

@section('title')
  Candidato
@endsection

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
              El candidato no fue almacenado, el correo o la cédula ya esta registrado
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info"><i class="fas fa-plus"></i>&nbsp;Agregar candidato</h1>
  <hr class="row align-items-start col-12">

  <form action="/candidatos/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'candidatos.store', 'method' => 'POST']) !!}
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
            <td>{!! Form::text('nombres', null, [ 'class' => 'form-control', 'placeholder' => 'Maria Raquel', 'pattern' => '^[A-Za-zñÑáéíóúÁÉÍÓÚ]+\s?[A-Za-zñÑáéíóúÁÉÍÓÚ]+$', 'title' => 'Debe utilizar el patrón especificado [A-Z]', 'autofocus', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('apellidos', 'Apellidos *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('apellidos', null, [ 'class' => 'form-control', 'placeholder' => 'Herrera Perez', 'pattern' => '^[A-Za-zñÑáéíóúÁÉÍÓÚ]+\s?[A-Za-zñÑáéíóúÁÉÍÓÚ]+$', 'title' => 'Debe utilizar el patrón especificado [A-Z]', 'required']) !!}</td>
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
                    {!! Form::text('cedula', null, [ 'class' => 'form-control', 'placeholder' => '24921001', 'pattern' => '^[0-9]{7,}$', 'title' => 'Debe utilizar el patrón especificado [0-9]', 'required']) !!}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('telefono_celular', 'Teléfono celular') !!}</th>
            <td>{!! Form::text('telefono_celular', null, [ 'class' => 'form-control', 'placeholder' => '0414-1234567', 'pattern' => '^0[1246]{3}-[0-9]{7}$', 'title' => 'Debe utilizar el patrón especificado [xxxx-xxxxxxx]']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('telefono_habitacion', 'Teléfono de habitación') !!}</th>
            <td>{!! Form::text('telefono_habitacion', null, [ 'class' => 'form-control', 'placeholder' => '0261-1234567', 'pattern' => '^0[1246]{3}-[0-9]{7}$', 'title' => 'Debe utilizar el patrón especificado [xxxx-xxxxxxx]']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('correo', 'Correo') !!}</th>
            <td>{!! Form::email('correo', null, [ 'class' => 'form-control', 'placeholder' => 'mherrera@farmacia72.com']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('tipo_relacion', 'Tipo de relación') !!}</th>
            <td>
              {!! Form::select('tipo_relacion', [
                'Ince' => 'Ince', 
                'Pasante' => 'Pasante',
                'Trabajador regular' => 'Trabajador regular',
              ], null, ['class' => 'form-control']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('relaciones_laborales', 'Relaciones con trabajadores') !!}</th>
            <td>
              {!! Form::select('relaciones_laborales', [
                'Si' => 'Si', 
                'No' => 'No',
              ], 'No', ['class' => 'form-control']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('como_nos_contacto', 'Como nos contactó') !!}</th>
            <td>
              {!! Form::select('como_nos_contacto', [
                'Computrabajo' => 'Computrabajo', 
                'Bumeran' => 'Bumeran',
                'Redes sociales' => 'Redes sociales',
                'Instagram' => 'Instagram',
                'Radio' => 'Radio',
                'Recomendado' => 'Recomendado',
              ], null, ['class' => 'form-control']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('experiencia_laboral', 'Experiencia laboral') !!}</th>
            <td>{!! Form::textarea('experiencia_laboral', null, [ 'class' => 'form-control', 'placeholder' => 'Experiencia laboral previa del candidato', 'rows' => '3']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('direccion', 'Dirección *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Av. 15 Delicias con calle 72', 'rows' => '3', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('observaciones', 'Observaciones') !!}</th>
            <td>{!! Form::textarea('observaciones', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes del candidato', 'rows' => '3']) !!}</td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection