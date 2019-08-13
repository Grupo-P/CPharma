@extends('layouts.model')

@section('title')
    Compromisos
@endsection

@section('content')
  <!-- Modal Error -->
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
              <h4 class="h6">El registro no pudo ser actualizado</h4>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
            </div>
          </div>
        </div>
      </div>
  @endif

  <h1 class="h5 text-info">
      <i class="fas fa-edit"></i>
      Actualizar carta compromiso
  </h1>

  <hr class="row align-items-start col-12">

  <form action="/cartaCompromiso/" method="POST" style="display: inline;">  
      @csrf
      <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
  </form>

  <br><br>

  {!!Form::model($cartaCompromiso, ['route' => ['cartaCompromiso.update', $cartaCompromiso], 'method' => 'PUT'])!!}
  <fieldset>
    <table class="table table-borderless table-striped">
      <thead class="thead-dark">
        <tr>
          <th scope="row" colspan="2"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>Proveedor</th>
          <td>
            <input id="proveedor" type="text" name="proveedor" value="{{$cartaCompromiso->proveedor}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>Art&iacute;culo</th>
          <td>
            <input id="articulo" type="text" name="articulo" value="{{$cartaCompromiso->articulo}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>Lote</th>
          <td>
            <input id="lote" type="text" name="lote" value="{{$cartaCompromiso->lote}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>Fecha de factura</th>
          <td>
            <input id="fecha_recepcion" type="date" name="fecha_recepcion" value="{{$cartaCompromiso->fecha_documento}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>Fecha de recepci&oacute;n (Art&iacute;culo)</th>
          <td>
            <input id="fecha_recepcion" type="date" name="fecha_recepcion" value="{{$cartaCompromiso->fecha_recepcion}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>Fecha de vencimiento (Art&iacute;culo)</th>
          <td>
            <input id="fecha_vencimiento" type="date" name="fecha_vencimiento" value="{{$cartaCompromiso->fecha_vencimiento}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>Fecha tope (Compromiso)</th>
          <td>
            <input id="fecha_tope" type="date" name="fecha_tope" value="{{$cartaCompromiso->fecha_tope}}" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <th>
            Causa
          </th>
          <td>
            <textarea name="causa" id="causa" class="form-control" rows="3" placeholder="Causa del compromiso" maxlength="450">
              {{$cartaCompromiso->causa}}
            </textarea>
          </td>
        </tr>

        <tr>
          <th>
            Nota
          </th>
          <td>
            <textarea name="nota" id="nota" class="form-control" rows="3" placeholder="Nota del compromiso" maxlength="450">
              {{$cartaCompromiso->nota}}
            </textarea>
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
    $('#exampleModalCenter').modal('show');
  </script>
@endsection

<style>
  * {
    box-sizing: border-box;
  }
  /*the container must be positioned relative:*/
  input {
    border: 1px solid transparent;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
  }

  input[type=date] {
    background-color: #f1f1f1;
    width: 100%;
  }
</style>