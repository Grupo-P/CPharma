@extends('layouts.model')

@section('title')
    Unidad Minima
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
                <h4 class="h6">La unidad minima no fue almacenada</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    @if (session('Error Unidad'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-exclamation-triangle text-danger"></i>{{ session('Error Unidad') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Debe seleccionar una unidad</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    <h1 class="h5 text-info">
        <i class="fas fa-less-than-equal"></i>
        Unidad minima de expresion
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/unidad/" method="POST" style="display: inline;">
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::model($unidad, ['route' => ['unidad.update', $unidad], 'method' => 'PUT']) !!}

    <fieldset>

        <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row" colspan="2">{{$unidad->articulo}}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">{!! Form::label('divisor', 'Divisor') !!}</th>
                <td>{!! Form::text('divisor', null, [ 'class' => 'form-control', 'placeholder' => '500', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('unidad_minima', 'Unidad') !!}</th>
                <td>
                    <select name="unidad_minima" class="form-control" required="required">
                        <option selected="selected">Seleccione...</option>
                        <option>METROS (M)</option>
                        <option>MILILITROS (ML)</option>
                        <option>MILIGRAMOS (MG)</option>                        
                        <option>GRAMOS (G)</option>
                        <option>KILOGRAMOS (KG)</option>                        
                        <option>CAPSULA</option>
                        <option>TABLETA</option>
                        <option>UNIDAD</option>
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