@extends('layouts.model')

@section('title')
    Orden de compra
@endsection

@section('scriptsHead')
    <style>
    * {
      box-sizing: border-box;
    }
    .autocomplete {
      position: relative;
      display: inline-block;
    }
    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }
    input[type=text] {
      background-color: #fff;
      width: 100%;
    }
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      top: 100%;
      left: 0;
      right: 0;
    }
    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff; 
      border-bottom: 1px solid #d4d4d4; 
    }
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
    }
    .autocomplete-active {
      background-color: DodgerBlue !important; 
      color: #ffffff; 
    }
  </style>
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
                <h4 class="h6">La orden de compra no pudo ser almacenada</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    <h1 class="h5 text-info">
        <i class="fas fa-check"></i>
        Ingresar orden de compra
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/ordenCompra/" method="POST" style="display: inline;">
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    <?php
      use Illuminate\Http\Request;
      use compras\OrdenCompra;
      
      $id = $_GET['id'];

      $OrdenCompra = OrdenCompra::find($id);
    ?>

   <form action="/ordenCompra/{{$OrdenCompra->id}}" method="POST">
    @method('DELETE')
    @csrf 

    {!! Form::hidden('Ingresar','valido') !!}
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
              <th scope="row">{!! Form::label('codigo)', 'Orden de compra') !!}</th>
              <td><label><?php echo($OrdenCompra->codigo); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('proveedor)', 'Proveedor') !!}</th>
              <td><label><?php echo($OrdenCompra->proveedor); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('condicion)', 'Condicion crediticia') !!}</th>
              <td><label><?php echo($OrdenCompra->condicion_crediticia); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('destino)', 'Destino') !!}</th>
              <td><label><?php echo($OrdenCompra->sede_destino); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('fecha_actual)', 'Fecha Orden') !!}</th>
              <td><label><?php echo($OrdenCompra->created_at); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('Operador)', 'Operador') !!}</th>
              <td><label><?php echo($OrdenCompra->user); ?></label></td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('montoTotalReal', 'Monto Total Real') !!}</th>
            <td>{!! Form::number('montoTotalReal', null, [ 'class' => 'form-control', 'autofocus', 'required', 'step' => '0.01']) !!}</td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('calificacion', 'Calificacion') !!}</th>
            <td>{!! Form::number('calificacion', null, [ 'class' => 'form-control', 'autofocus', 'required']) !!}</td>
          </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    </form>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
        $('#exampleModalCenter').modal('show')
    </script>
@endsection