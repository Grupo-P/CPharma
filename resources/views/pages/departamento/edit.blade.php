@extends('layouts.model')

@section('title')
    Departamento
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
                <h4 class="h6">El departamento no pudo ser almacenado</h4>
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
        Modificar departamento
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/departamento/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    <table class="table table-bordered table-striped col-12">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky text-left">#</th>
                <th scope="col" class="CP-sticky text-left">Reporte</th>
                <th scope="col" class="CP-sticky text-left">#</th>
                <th scope="col" class="CP-sticky text-left">Reporte</th>
                <th scope="col" class="CP-sticky text-left">#</th>
                <th scope="col" class="CP-sticky text-left">Reporte</th>
                <th scope="col" class="CP-sticky text-left">#</th>
                <th scope="col" class="CP-sticky text-left">Reporte</th>
                <th scope="col" class="CP-sticky text-left">#</th>
                <th scope="col" class="CP-sticky text-left">Reporte</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="col">1</th>
                <td scope="col">Activacion de proveedores</td>
                <th scope="col">2</th>
                <td scope="col">Historico de productos</td>
                <th scope="col">3</th>
                <td scope="col">Productos mas vendidos</td>
                <th scope="col">4</th>
                <td scope="col">Productos menos vendidos</td>
                <th scope="col">5</th>
                <td scope="col">Productos en falla</td>
            </tr>
            <tr>
                <th scope="col">6</th>
                <td scope="col">Pedido de productos</td>
                <th scope="col">7</th>
                <td scope="col">Catalogo de proveedor</td>
                <th scope="col">9</th>
                <td scope="col">Productos para surtir</td>
                <th scope="col">10</th>
                <td scope="col">Analitico de precios</td>
                <th scope="col">12</th>
                <td scope="col">Detalle de movimientos</td>
            </tr>
            <tr>
                <th scope="col">13</th>
                <td scope="col">Productos Por Fallar</td>
                <th scope="col">14</th>
                <td scope="col">Productos en Caida</td>
                <th scope="col">15</th>
                <td scope="col">Articulos Devaluados</td>
                <th scope="col">16</th>
                <td scope="col">Articulos Estrella</td>
                <th scope="col">17</th>
                <td scope="col">Tri Tienda Por Articulo</td>
            </tr>
            <tr>
                <th scope="col">18</th>
                <td scope="col">Consulta Compras</td>
                <th scope="col">19</th>
                <td scope="col">Ventas Cruzadas</td>
                <th scope="col">20</th>
                <td scope="col">Tri Tienda Por Proveedor</td>
                <th scope="col">21</th>
                <td scope="col">Consultor de Precios</td>
                <th scope="col">22</th>
                <td scope="col">Reporte de Atributos</td>
            </tr>
            <tr>
                <th scope="col">24</th>
                <td scope="col">Articulos Nuevos</td>
            </tr>
        </tbody>
    </table>

    <br>

    {!! Form::model($departamentos, ['route' => ['departamento.update', $departamentos], 'method' => 'PUT']) !!}
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
                <th scope="row">{!! Form::label('nombre', 'Nombre') !!}</th>
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'TECNOLOGIA', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('descripcion', 'Reportes') !!}</th>
                <td>{!! Form::textarea('descripcion', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el numero correspondiente al reporte separado con comas. Ej.: 1,2,3', 'rows' => '2', 'required']) !!}</td>
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