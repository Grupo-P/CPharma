<?php
    use Illuminate\Http\Request;
    use compras\Subcategoria;
    use compras\Categoria;
?>

@extends('layouts.model')

@section('title')
    Subategoria
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
                <h4 class="h6">La subcategoria no fue almacenada, el codigo ya esta registrado</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    <h1 class="h5 text-info">
        <i class="fas fa-plus"></i>
        Agregar Subcategoria
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/subcategoria/" method="POST" style="display: inline;">
        @csrf
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'subcategoria.store', 'method' => 'POST']) !!}
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
                <th scope="row">{!! Form::label('codigo_categoria', 'Categoria') !!}
                </th>
                <td>
                    <select name="codigo_categoria" class="form-control">
                        <?php
                        foreach($categorias as $categoria){
                            $cat = Categoria::where('codigo',$categoria)->get();
                        ?>
                        <option value="<?php echo $categoria; ?>">
                            <?php echo $categoria." - ".$cat[0]->nombre; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('codigo', 'Codigo') !!}</th>
                <td>{!! Form::text('codigo', null, [ 'class' => 'form-control', 'autofocus','required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('codigo_app', 'Codigo App') !!}</th>
                <td>{!! Form::text('codigo_app', null, [ 'class' => 'form-control', 'autofocus','required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('nombre', 'Nombre') !!}</th>
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'required']) !!}</td>
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
