@extends('layouts.model')

@section('title')
    Tasa venta
@endsection

@section('content')
    <?php
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\querys.php');
        include(app_path().'\functions\funciones.php');

        $conCP = ConectarXampp();
        
        $sql1 = QG_RangoMinTasaVenta();
        $result1 = mysqli_query($conCP, $sql1);
        $row1 = mysqli_fetch_assoc($result1);
        $minimo = floatval($row1['valor']);

        $sql2 = QG_RangoMaxTasaVenta();
        $result2 = mysqli_query($conCP, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $maximo = floatval($row2['valor']);

        mysqli_close($conCP);
    ?>

    <!-- Modal Guardar -->
    @if(session('Error'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
                  <i class="fas fa-exclamation-triangle text-danger"></i>{{ session('Error') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">El registro no pudo ser almacenado, la fecha ya esta registrada</h4>
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
        Modificar tasa de venta
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/tasaVenta/" method="POST" style="display:inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
          <i class="fa fa-reply">&nbsp;Regresar</i>
        </button>
    </form>

    <br>
    <br>

    {!! Form::model($tasaVenta, ['route' => ['tasaVenta.update', $tasaVenta], 'method' => 'PUT']) !!}
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
                  <th scope="row">{!! Form::label('tasa', 'Tasa') !!}</th>
                  <td>{!! Form::number('tasa', null, [ 'class' => 'form-control', 'placeholder' => 'xx.xx', 'step' => '0.01', 'min' => $minimo, 'max' => $maximo, 'autofocus', 'required']) !!}</td>
              </tr>
              <tr>
                <th>Moneda</th>
                <td>
                  <select name="moneda" id="moneda" class="form-control" style="width:100%;" disabled>
                    <option>Dolar</option>
                    <option>Bolivar Soberano</option>
                    <option>Peso Colombiano</option>
                    <option>Euro</option>
                  </select>
                </td>
              </tr>
              <tr>
                  <th>Fecha</th>

                  <?php
                      $Hoy = new DateTime();
                      $Hoy = $Hoy->format('d-m-Y');
                      $HoyEnviar = date('d-m-Y', strtotime($Hoy . ' 00:00:00'));
                  ?>

                  <td>
                    <input type="text" class="form-control" value="<?php echo $Hoy ?>" style="width:100%;" required disabled>
                    <input type="hidden" id="fecha" name="fecha" value="<?php echo $HoyEnviar ?>">
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