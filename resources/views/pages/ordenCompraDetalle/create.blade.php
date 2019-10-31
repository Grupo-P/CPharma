@extends('layouts.model')

@section('title')
    Detalle de orden de compra
@endsection

@section('scriptsHead')
  <style>
    .campoNulo {border-width: 3px !important;}
    .campoNulo::placeholder {color: #dc3545; font-weight: bold;}
  </style>

  <script>
    var activarDangerRequerido = (Input) => {
      Input.addClass('border border-danger campoNulo');
    };
  </script>
@endsection

<?php
    use Illuminate\Http\Request;
    use compras\OrdenCompra;
  
    $usuario = auth()->user()->name;
    $OrdenActiva = 
    OrdenCompra::where('user',$usuario)
    ->where('estatus','ACTIVO')
    ->get();

    if(!empty($OrdenActiva[0]->id)) {
       $OrdenCompra = OrdenCompra::find($OrdenActiva[0]->id);
    }
    else{
      return back()->with('Error', 'No posee una orden activa');
    }
?>

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
                <h4 class="h6">El articulo no pudo ser almacenado</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    <h1 class="h5 text-info">
        <i class="far fa-file-alt"></i>
        Detalle de orden de compra
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/ordenCompraDetalle/" method="POST" style="display: inline;">                
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'ordenCompraDetalle.store', 'method' => 'POST', 'id' => 'guardar']) !!}
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
                <th scope="row">{!! Form::label('orden', 'Codigo de orden') !!}</th>
                <td scope="row">{!! Form::label('codigo_orden',$OrdenCompra->codigo) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('sedeDestino', 'Sede Destino') !!}</th>
                <td scope="row">{!! Form::label('sede_destino',$OrdenCompra->sede_destino) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('moneda', 'Moneda') !!}</th>
                <td scope="row">{!! Form::label('moneda_orden',$OrdenCompra->moneda) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('proveedor', 'Proveedor') !!}</th>
                <td scope="row">{!! Form::label('proveedor_orden',$OrdenCompra->proveedor) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('operador', 'Operador') !!}</th>
                <td scope="row">{!! Form::label('user_orden',$OrdenCompra->user) !!}</td>
            </tr>
            <?php
                if($OrdenCompra->sede_destino=='CENTRO DE DISTRIBUCION'){  
            ?>
            <tr>
                <th scope="row">{!! Form::label('total_unidades', 'Total de Unidades') !!}</th>
                <td>{!! Form::text('totalUnidades', null, [ 'class' => 'form-control', 'autofocus', 'required', 'id' => 'totalUnidades', 'onblur' =>'disponible()']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('disponibles', 'Unidades Disponibles') !!}</th>
                <td>{!! Form::text('unidadesDisponibles', null, [ 'class' => 'form-control', 'autofocus', 'required', 'id' => 'unidadesDisponibles', 'disabled'=>'disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('sede1', 'Cantidad para FTN') !!}</th>
                <td>{!! Form::number('sede1', null, [ 'class' => 'form-control', 'autofocus', 'required', 'onblur' =>'sumaTotal()', 'id' => 'sede1']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('sede2', 'Cantidad para FLL') !!}</th>
                <td>{!! Form::number('sede2', null, [ 'class' => 'form-control', 'autofocus', 'required', 'onblur' =>'sumaTotal()', 'id' => 'sede2']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('sede3', 'Cantidad para FAU') !!}</th>
                <td>{!! Form::number('sede3', null, [ 'class' => 'form-control', 'autofocus', 'required', 'onblur' =>'sumaTotal()', 'id' => 'sede3']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('sede4', 'Cantidad para MC') !!}</th>
                <td>{!! Form::number('sede4', null, [ 'class' => 'form-control', 'autofocus', 'required', 'onblur' =>'sumaTotal()', 'id' => 'sede4']) !!}</td>
            </tr>
            
            <?php
            }
            else if($OrdenCompra->sede_destino!='CENTRO DE DISTRIBUCION'){
            ?>
            
            <?php
            }
            ?>
            <tr>
                <th scope="row">{!! Form::label('costo_unitario', 'Costo Unitario') !!}</th>
                <td>{!! Form::number('costo_unitario', null, [ 'class' => 'form-control', 'autofocus', 'required', 'id'=>'CostoUnitario', 'onblur' =>'costoTotal()']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('costo_total', 'Costo Total') !!}</th>
                <td>{!! Form::text('CostoTotal', null, [ 'class' => 'form-control', 'autofocus', 'required', 'id' => 'CostoTotal', 'disabled' => 'disabled']) !!}</td>
            </tr>

        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id'=>'enviar']) !!}
    </fieldset>
    {!! Form::close()!!} 
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
        $('#exampleModalCenter').modal('show');

        var clasesError = 'border border-danger campoNulo';
        var unidadesDisponibles = $('#unidadesDisponibles');
        var guardar = $('#guardar');    

        function disponible(){
            totalUnidades = parseInt(document.getElementById('totalUnidades').value);

            var unidadesDisponibles = isNaN(totalUnidades) ? 0 : totalUnidades;

            document.getElementById('unidadesDisponibles').value = parseInt(unidadesDisponibles);

            document.getElementById('sede1').value = 0;
            document.getElementById('sede2').value = 0;
            document.getElementById('sede3').value = 0;
            document.getElementById('sede4').value = 0;
            unidadesDisponibles.removeClass(clasesError);
        }

        function sumaTotal(){
            total = 0;
            sede1 = parseInt(document.getElementById('sede1').value);
            sede2 = parseInt(document.getElementById('sede2').value);
            sede3 = parseInt(document.getElementById('sede3').value);
            sede4 = parseInt(document.getElementById('sede4').value);

            totalUnidades = document.getElementById('totalUnidades').value;

            var sub1 = isNaN(sede1) ? 0 : sede1;
            var sub2 = isNaN(sede2) ? 0 : sede2;
            var sub3 = isNaN(sede3) ? 0 : sede3;
            var sub4 = isNaN(sede4) ? 0 : sede4;

            total = sub1+sub2+sub3+sub4;

            totalUnidades = totalUnidades-total;
            document.getElementById('unidadesDisponibles').value = totalUnidades;
             unidadesDisponibles.removeClass(clasesError);
        }

        function costoTotal(){
            CostoUnitario = parseInt(document.getElementById('CostoUnitario').value);
            TotalUnidades = parseInt(document.getElementById('totalUnidades').value);

            CostoTotal = CostoUnitario*TotalUnidades;
            document.getElementById('CostoTotal').value = parseInt(CostoTotal);
        }

        document.getElementById("enviar").addEventListener("click", function(event){
          event.preventDefault();

          if( (document.getElementById('unidadesDisponibles').value)!= 0 ){
            unidadesDisponibles.addClass(clasesError);
          }
          else{
            guardar.submit();
          }
        });
    </script>
@endsection