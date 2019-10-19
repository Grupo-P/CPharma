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
                <h4 class="h6">El traslado no pudo ser almacenado, ya existe un traslado para este ajuste</h4>
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
        Agregar traslado
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/traslado/" method="POST" style="display: inline;">
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    <?php
      include(app_path().'\functions\config.php');
      include(app_path().'\functions\functions.php');
      include(app_path().'\functions\querys_mysql.php');
      include(app_path().'\functions\querys_sqlserver.php');

      $conn = FG_Conectar_Smartpharma($_GET['SEDE']);
      $sql = QAjuste_Encabezado($_GET['Id']);
      $result = sqlsrv_query($conn,$sql);
      $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

      $IdAjuste = $row["IdAjuste"];
      $NumeroAjuste = $row["NumeroAjuste"];

      $FechaAjuste = $row["FechaAjuste"];
      $FechaAjusteImp = $FechaAjuste->format("d-m-Y h:i:s a");
      $FechaAjuste = $FechaAjuste->format("Y-m-d h:i:s");
      $OperadorAjuste = $row["OperadorAjuste"];

      $Comentario = $row["Comentario"];
      $M_TotalCostoAjuste = $row["M_TotalCostoAjuste"];

      $FechaActual = $row["FechaActual"];
      $FechaActualImp = $FechaActual->format("d-m-Y h:i:s a");
      $FechaActual =$FechaActual->format("Y-m-d h:i:s");
      $OperadorTraslado = auth()->user()->name;

      $SedeOrigen = FG_Nombre_Sede($_GET['SEDE']);
    ?>

    {!! Form::open(['route' => 'traslado.store', 'method' => 'POST']) !!}
    <fieldset>

        <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row"></th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            {!! Form::hidden('IdAjuste', $IdAjuste) !!}
            {!! Form::hidden('SEDE', $_GET['SEDE']) !!}
            {!! Form::hidden('numero_ajuste', $NumeroAjuste) !!}
            {!! Form::hidden('fecha_ajuste',$FechaAjuste) !!}
            {!! Form::hidden('operador_ajuste',$OperadorAjuste) !!}
            {!! Form::hidden('fecha_traslado',$FechaActual) !!}
            {!! Form::hidden('operador_traslado',$OperadorTraslado) !!}
            {!! Form::hidden('sede_emisora',$SedeOrigen) !!}
            <tr>
                <th scope="row">{!! Form::label('numero_ajuste', 'Numero de Ajuste') !!}</th>
                <td><label><?php echo($NumeroAjuste); ?></label></td>
            </tr> 
            <tr>
                <th scope="row">{!! Form::label('FechaAjusteImp', 'Fecha de Ajuste') !!}</th>
                <td><label><?php echo($FechaAjusteImp); ?></label></td>
            </tr> 
            <tr>
                <th scope="row">{!! Form::label('operador_ajuste', 'Operador de Ajuste') !!}</th>
                <td><label><?php echo($OperadorAjuste); ?></label></td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('comentario', 'Comentario') !!}</th>
                <td><label><?php echo($Comentario); ?></label></td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('costo', 'Costo Total del Ajuste') !!}</th>
                <td><label><?php echo(number_format($M_TotalCostoAjuste,2,"," ,"." )); ?></label> {{SigVe}}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('FechaActualImp', 'Fecha de Traslado') !!}</th>
                <td><label><?php echo($FechaActualImp); ?></label></td>   
            </tr> 
            <tr>
                <th scope="row">{!! Form::label('operador_traslado', 'Operador de Traslado') !!}</th>
                <td><label><?php echo($OperadorTraslado); ?></label></td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('sede_emisora', 'Sede Emisora') !!}</th>
                <td><label><?php echo($SedeOrigen); ?></label></td>
            </tr> 
            <tr>
                <th scope="row">{!! Form::label('sede_destino', 'Sede Destino') !!}
                </th>
                <td>
                    <select name="sede_destino" class="form-control" required="required" autofocus="autofocus">
                        <option value="" selected="selected">Seleccione una sede...</option>
                        <?php
                        foreach($sedes as $sede){
                        ?>
                          <?php 
                            if($sede!=$SedeOrigen){
                              echo'<option value="'.$sede.'">'.$sede.'</option>';
                            }
                          ?>
                        <?php
                        }
                        ?>
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

<?php
    /***********************************************************************/
    /*
    TITULO: QAjuste_Encabezado
    FUNCION: armar el encabezado para el ajuste
    RETORNO: encabezado del ajuste
    DESAROLLADO POR: SERGIO COVA
  */
  function QAjuste_Encabezado($IdAjuste) {
    $sql = "
      SELECT
      InvAjuste.Id AS IdAjuste,
      InvAjuste.NumeroAjuste AS NumeroAjuste,
      InvAjuste.Auditoria_FechaCreacion AS FechaAjuste,
      GETDATE() AS FechaActual,
      InvAjuste.Auditoria_Usuario AS OperadorAjuste,
      InvAjuste.Comentario,
      InvAjuste.M_TotalCostoAjuste
      FROM InvAjuste
      WHERE InvAjuste.Id = '$IdAjuste'
    ";
    return $sql;
  }
?>