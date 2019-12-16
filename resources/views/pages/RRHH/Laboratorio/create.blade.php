@extends('layouts.model')

@section('title', 'Crear Laboratorios')

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
             El laboratorio no fue almacenado
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if(session('Error1'))
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
             Error, El RIF proporcionado ya está registrado
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
    <i class="fas fa-plus"></i>&nbsp;Agregar Laboratorios
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/laboratorio/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>
  <br/><br/>
{!! Form::open(['route' => 'laboratorio.store', 'method' => 'POST', 'id' => 'crear_laboratorio', 'class' => 'form-group']) !!}
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
            <th scope="row">{!! Form::label('rif', 'RIF *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <table style="width: 100%;">
                <tr style="background-color: transparent;">
                  <td>
                    {!! Form::select('tipo', ['J' => 'J', 'G' => 'G'], null, [ 'class' => 'form-control']) !!}
                  </td>

                  <td>
                    {!! Form::text('rif', null, [ 'class' => 'form-control', 'placeholder' => '40014517-1', 'pattern' => '^[0-9]{7,}-[0-9]{1}$', 'required']) !!}
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          
           <tr>
            <th scope="row">{!! Form::label('nombre', 'Nombre del Laboratorio *', ['title' => 'Este campo es requerido']) !!}</th>

            <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Consultorio Santa Mónica', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('direccion', 'Dirección *', ['title' => 'Este campo es requerido']) !!}</th>

            <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Av. 15 Delicias con calle 74', 'rows' => '3', 'required']) !!}</td>
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
              <label for="telefono_fijo">Teléfono fijo</label>
            </th>
            
            <td>
              <input type="tel" class="form-control" name="telefono_fijo" id="telefono_fijo" placeholder="0261-1234567" pattern="^0[1246]{3}-[0-9]{7}$">
            </td>
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
      var telefono_fijo = document.querySelector('#telefono_fijo');

      //Objetos DOM JQuery
      var enviar = $('#enviar');
      var crear_laboratorio = $('#crear_laboratorio');

      enviar.click(function() {

        if((telefono_celular.value == '') && (telefono_fijo.value == '')) {

          telefono_celular.setCustomValidity('Debe ingresar al menos un Teléfono');
          telefono_fijo.setCustomValidity('Debe ingresar al menos un Teléfono');
        }

      });

      crear_laboratorio.submit(function(e) {

        if((telefono_celular.value == '') && (telefono_fijo.value == '')) {
          e.preventDefault();
        }

      });

      $('#telefono_celular, #telefono_fijo').on({
        
        keydown: function(e) {

          telefono_celular.setCustomValidity('');
          telefono_fijo.setCustomValidity('');
        }

      });
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection