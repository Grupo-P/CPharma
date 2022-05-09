@extends('layouts.model')

@section('title')
    Traslado
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
                <h4 class="h6">La configuracion no pudo ser almacenada</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    <h1 class="h5 text-info">
        <i class="fas fa-box-open"></i>
        Embalar
    </h1>

    <hr class="row align-items-start col-12">

    <a class="btn btn-outline-info btn-sm" data-placement="top" href="/traslado"><i class="fa fa-reply"></i> Regresar</a>
    <br/><br/>

    <?php
      $FechaActual = date('Y-m-d');
      $FechaActualImp = date('d-m-Y');
      $Operador = auth()->user()->name;
    ?>

    {!! Form::model($traslado, ['route' => ['traslado.update', $traslado], 'method' => 'PUT']) !!}
    <fieldset>
  
        <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row"></th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
          {!! Form::hidden('fecha_embalaje',$FechaActual) !!}
          {!! Form::hidden('fecha_envio',$FechaActual) !!}
          <tr>
            <th scope="row">{!! Form::label('numero_ajuste', 'Numero de Ajuste') !!}</th>
            <td><label><?php echo($traslado->numero_ajuste); ?></label></td>
          </tr> 
          <tr>
            <th scope="row">{!! Form::label('FechaActualImp', 'Fecha de Embalaje') !!}</th>
            <td><label><?php echo($FechaActualImp); ?></label></td>   
          </tr> 
          <tr>
            <th scope="row">{!! Form::label('operador_embalaje', 'Operador de Embalaje') !!}</th>
            <td><label><?php echo($Operador); ?></label></td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('FechaActualImp', 'Fecha de Envio') !!}</th>
            <td><label><?php echo($FechaActualImp); ?></label></td>   
          </tr> 
          <tr>
            <th scope="row">{!! Form::label('operador_envio', 'Operador de Envio') !!}</th>
            <td><label><?php echo($Operador); ?></label></td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('sede_destino', 'Sede destino') !!}</th>
            <td><label><?php echo($traslado->sede_destino); ?></label></td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('bultos', 'Cantidad de Bultos (Normales)') !!}</th>
            <td>{!! Form::number('bultos', 0, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la cantidad de bultos embalados', 'autofocus', 'required', 'min' => '0'] ) !!}</td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('bultos_refrigerados', 'Cantidad de Bultos (Refrigerados)') !!}</th>
            <td>{!! Form::number('bultos_refrigerados', 0, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la cantidad de bultos embalados', 'autofocus', 'required', 'min' => '0'] ) !!}</td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('bultos_fragiles', 'Cantidad de Bultos (Frágiles)') !!}</th>
            <td>{!! Form::number('bultos_fragiles', 0, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la cantidad de bultos embalados', 'autofocus', 'required', 'min' => '0'] ) !!}</td>
          </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md validacion']) !!}
    </fieldset>
    {!! Form::close()!!} 
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });

        $('#exampleModalCenter').modal('show');

        $('form').submit(function (event) {
            normales = $('[name=bultos]').val();
            bultos_refrigerados = $('[name=bultos_refrigerados]').val();
            bultos_fragiles = $('[name=bultos_fragiles]').val();

            if ((normales == 0 || normales == '') && (bultos_refrigerados == 0 || bultos_refrigerados == '') && (bultos_fragiles == 0 || bultos_fragiles == '')) {
                alert('Debe especificar al menos un tipo de bulto');
                event.preventDefault();
            }
        });
    </script>
@endsection
