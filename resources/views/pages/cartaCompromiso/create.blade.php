@extends('layouts.model')

@section('title')
    Carta de compromiso
@endsection

@section('content')
  <!-- Modal Error -->
  @if(session('Error'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
              <i class="fas fa-exclamation-triangle text-danger"></i>{{session('Error')}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">
              El registro no pudo ser almacenado
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">
              Aceptar
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>
    Agregar carta de compromiso
  </h1>

  <hr class="row align-items-start col-12">
  <form action="/cartaCompromiso/" method="POST" style="display:inline;">  
    @csrf                       
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br><br>

  {!!Form::open(['route' => 'cartaCompromiso.store', 'method' => 'POST'])!!}
  <fieldset>
    <table class="table table-borderless table-striped">
      <thead class="thead-dark">
        <tr>
          <th scope="row" colspan="2"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">{!! Form::label('articulo', 'Art&iacute;culo') !!}</th>
          <td>
            {!!Form::text('articulo', null, ['class' => 'form-control', 'placeholder' => 'Nombre del art&iacute;culo', 'autofocus', 'required'])!!}
          </td>
        </tr>

        <tr>
          <th scope="row">{!! Form::label('lote', 'Lote') !!}</th>
          <td>
            {!!Form::text('lote', null, ['class' => 'form-control', 'placeholder' => 'Lote del art&iacute;culo', 'required'])!!}
          </td>
        </tr>

        <tr>
          <th>
            Fecha de vencimiento
          </th>
          <td>
            <input id="fecha_vencimiento" type="date" name="fecha_vencimiento" class="form-control" required>
          </td>
        </tr>

        <tr>
          <th scope="row">{!! Form::label('proveedor', 'Proveedor') !!}</th>
          <td>
            {!!Form::text('proveedor', null, ['class' => 'form-control', 'placeholder' => 'Nombre del proveedor', 'required'])!!}
          </td>
        </tr>

        <tr>
          <th>
            Fecha de recepci&oacute;n
          </th>
          <td>
            <input id="fecha_recepcion" type="date" name="fecha_recepcion" class="form-control" required>
          </td>
        </tr>

        <tr>
          <th>
            Fecha tope
          </th>
          <td>
            <input id="fecha_tope" type="date" name="fecha_tope" class="form-control" required>
          </td>
        </tr>

        <tr>
          <th>
            Causa
          </th>
          <td>
            <textarea name="causa" id="causa" class="form-control" rows="4" placeholder="Causa del compromiso" maxlength="450"></textarea>
          </td>
        </tr>

        <tr>
          <th>
            Nota
          </th>
          <td>
            <textarea name="nota" id="nota" class="form-control" rows="4" placeholder="Nota del compromiso" maxlength="450"></textarea>
          </td>
        </tr>
      </tbody>
    </table>
    {!!Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md'])!!}
  </fieldset>
  {!!Form::close()!!} 

  <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection

<style>
  * {box-sizing:border-box;}
  /*the container must be positioned relative:*/
  input {
    border:1px solid transparent; 
    background-color:#f1f1f1; 
    border-radius:5px; 
    padding:10px; 
    font-size:16px;
  }

  input[type=date] {
    background-color:#f1f1f1;
    width:100%;
  }
</style>