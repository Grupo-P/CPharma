@extends('layouts.model')

@section('title')
  Candidato
@endsection

@section('scriptsHead')
  <style>
    .campoNulo {border-width: 3px !important;}
    .campoNulo::placeholder {color: #dc3545; font-weight: bold;}
  </style>

  <script>
    var activarDangerRequerido = (Input) => {
      Input.addClass('border border-danger campoNulo');
      Input.attr('placeholder', 'Este campo es requerido');
    };
  </script>
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
              El candidato no fue almacenado
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal Cedula duplicada -->
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
              El candidato no fue almacenado, la cédula ya esta registrada
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal Correo duplicado -->
  @if(session('Error2'))
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
              El candidato no fue almacenado, el correo ya esta registrado
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

  {!! Form::open(['route' => 'candidatos.store', 'method' => 'POST', 'id' => 'crear_candidato']) !!}
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
            <td>{!! Form::text('nombres', null, [ 'class' => 'form-control', 'placeholder' => 'Maria Raquel', 'autofocus']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('apellidos', 'Apellidos *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('apellidos', null, [ 'class' => 'form-control', 'placeholder' => 'Herrera Perez']) !!}</td>
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
                    {!! Form::text('cedula', null, [ 'class' => 'form-control', 'placeholder' => '24921001']) !!}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('telefono_celular', 'Teléfono celular') !!}</th>
            <td>{!! Form::text('telefono_celular', null, [ 'class' => 'form-control', 'placeholder' => '0414-1234567']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('telefono_habitacion', 'Teléfono de habitación') !!}</th>
            <td>{!! Form::text('telefono_habitacion', null, [ 'class' => 'form-control', 'placeholder' => '0261-1234567']) !!}</td>
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
            <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Av. 15 Delicias con calle 72', 'rows' => '3']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('observaciones', 'Observaciones') !!}</th>
            <td>{!! Form::textarea('observaciones', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes del candidato', 'rows' => '3']) !!}</td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id' => 'enviar']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();

      var nombres = $('#nombres');
      var apellidos = $('#apellidos');
      var cedula = $('#cedula');
      var telefono_celular = $('#telefono_celular');
      var telefono_habitacion = $('#telefono_habitacion');
      var correo = $('#correo');
      var direccion = $('#direccion');
      var enviar = $('#enviar');
      var crear_candidato = $('#crear_candidato');
      var clasesError = 'border border-danger campoNulo';
      var telefonoNulo = 'Debe indicar al menos un teléfono';

      enviar.click(function(e) {
        e.preventDefault();

        var regExp = /^0[1246]{3}-[0-9]{7}$/;//Numero
        var regExp1 = /^[A-Za-zñÑáéíóúÁÉÍÓÚ]+\s?[A-Za-zñÑáéíóúÁÉÍÓÚ]+$/;//Nombre
        var regExp2 = /^[0-9]{7,}$/;//Cedula
        var regExp3 = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;//Correo

        //En caso de cumplir las validaciones enviar el formulario
        if((regExp1.test(nombres.val())) 
          && (regExp1.test(apellidos.val())) 
          && (regExp2.test(cedula.val()))
          && (direccion.val() != '')
        ) {
          if(
            (telefono_celular.val() != '') 
            && (telefono_habitacion.val() != '') 
            && (correo.val() != '') 
          ) {
            if((regExp.test(telefono_celular.val())) 
              && (regExp.test(telefono_habitacion.val()))
              && (regExp3.test(correo.val()))
            ) {
              crear_candidato.submit();
            }
            else {
              if(!regExp.test(telefono_celular.val())) {
                telefono_celular.val('');
                telefono_celular.addClass(clasesError);
                telefono_celular.attr('placeholder', 'El formato telefónico es: 0xxx-xxxxxxx');
              }

              if(!regExp.test(telefono_habitacion.val())) {
                telefono_habitacion.val('');
                telefono_habitacion.addClass(clasesError);
                telefono_habitacion.attr('placeholder', 'El formato telefónico es: 0xxx-xxxxxxx');
              }

              if(!regExp3.test(correo.val())) {
                correo.val('');
                correo.addClass(clasesError);
                correo.attr('placeholder', 'El formato de correo es: xxxxxxx\@xxxxxx.xxx');
              }
            }
          }//Validacion de telefonos y correo
          else if(
            (telefono_celular.val() == '') 
            && (telefono_habitacion.val() == '')) {

            telefono_celular.addClass(clasesError);
            telefono_celular.attr('placeholder', telefonoNulo);

            telefono_habitacion.addClass(clasesError);
                telefono_habitacion.attr('placeholder', telefonoNulo);
          }//Telefonos vacios
          else if(
            (telefono_celular.val() != '') 
            || (telefono_habitacion.val() != '')) {

            if((regExp.test(telefono_celular.val())) 
              && (regExp.test(telefono_habitacion.val()))) {

              crear_candidato.submit();
            }
            
            if(regExp.test(telefono_celular.val())) {

              crear_candidato.submit();
            }
            else {

              if(regExp.test(telefono_habitacion.val())) {

                crear_candidato.submit();
              }
              else {
                telefono_habitacion.val('');
                telefono_habitacion.addClass(clasesError);
                telefono_habitacion.attr('placeholder', 'El formato telefónico es: 0xxx-xxxxxxx');
              }

              telefono_celular.val('');
              telefono_celular.addClass(clasesError);
              telefono_celular.attr('placeholder', 'El formato telefónico es: 0xxx-xxxxxxx');
            }

            if(regExp.test(telefono_habitacion.val())) {

              crear_candidato.submit();
            }
            else {

              if(regExp.test(telefono_celular.val())) {

                crear_candidato.submit();
              }
              else {
                telefono_celular.val('');
                telefono_celular.addClass(clasesError);
                telefono_celular.attr('placeholder', 'El formato telefónico es: 0xxx-xxxxxxx');
              }

              telefono_habitacion.val('');
              telefono_habitacion.addClass(clasesError);
              telefono_habitacion.attr('placeholder', 'El formato telefónico es: 0xxx-xxxxxxx');
            }
          }
        }//Validacion total
        else if(
          (nombres.val() == '')
          || (apellidos.val() == '')
          || (cedula.val() == '')
          || (direccion.val() == '')
        ) {
          if(nombres.val() == '') {
            activarDangerRequerido(nombres);
          }

          if(apellidos.val() == '') {
            activarDangerRequerido(apellidos);
          }

          if(cedula.val() == '') {
            activarDangerRequerido(cedula);
          }

          if(direccion.val() == '') {
            activarDangerRequerido(direccion);
          }
        }//Campos requeridos

      });

      $('#nombres, #apellidos, #cedula, #telefono_celular, #telefono_habitacion, ' 
      + '#correo, #direccion').on({
        keydown: function(e) {

          if(e.keyCode == 13) e.preventDefault();

          switch(e.target.id) {
            case 'nombres':
              if(e.keyCode == 8) {
                if(nombres.val().length == 1) {

                  activarDangerRequerido(nombres);
                }
              }
              else if(nombres.hasClass('border border-danger campoNulo')) {

                if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {
                  nombres.removeClass('border border-danger campoNulo');
                  nombres.attr('placeholder', 'Maria Raquel');
                }
              }
            break;

            case 'apellidos':
              if(e.keyCode == 8) {
                if(apellidos.val().length == 1) {

                  activarDangerRequerido(apellidos);
                }
              }
              else if(apellidos.hasClass('border border-danger campoNulo')) {

                if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {
                  apellidos.removeClass('border border-danger campoNulo');
                  apellidos.attr('placeholder', 'Herrera Perez');
                }
              }
            break;

            case 'cedula':
              if(e.keyCode == 8) {
                if(cedula.val().length == 1) {

                  activarDangerRequerido(cedula);
                }
              }
              else if(cedula.hasClass('border border-danger campoNulo')) {

                if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {
                  cedula.removeClass('border border-danger campoNulo');
                  cedula.attr('placeholder', '24921001');
                }
              }
            break;

            case 'direccion':
              if(e.keyCode == 8) {
                if(direccion.val().length == 1) {

                  activarDangerRequerido(direccion);
                }
              }
              else if(direccion.hasClass('border border-danger campoNulo')) {

                if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {
                  direccion.removeClass('border border-danger campoNulo');
                  direccion.attr('placeholder', 'Av. 15 Delicias con calle 72');
                }
              }
            break;

            case 'telefono_celular':
              if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {

                if(telefono_celular.attr('placeholder') == telefonoNulo) {

                  telefono_habitacion.removeClass('border border-danger campoNulo');
                  telefono_habitacion.attr('placeholder', '0261-1234567');
                }

                telefono_celular.removeClass('border border-danger campoNulo');
                telefono_celular.attr('placeholder', '0414-1234567');
              }
            break;

            case 'telefono_habitacion':
              if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {

                if(telefono_habitacion.attr('placeholder') == telefonoNulo) {

                  telefono_celular.removeClass('border border-danger campoNulo');
                  telefono_celular.attr('placeholder', '0414-1234567');
                }

                telefono_habitacion.removeClass('border border-danger campoNulo');
                telefono_habitacion.attr('placeholder', '0261-1234567');
              }
            break;

            case 'correo':
              if((e.keyCode != 9) && (e.keyCode != 16) && (e.keyCode != 20)) {
                correo.removeClass('border border-danger campoNulo');
                correo.attr('placeholder', 'mherrera@farmacia72.com');
              }
            break;
          }//switch
        }//keydown
      });

    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection