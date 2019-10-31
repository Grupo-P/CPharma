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
        <i class="fas fa-edit"></i>
        Modificar orden de compra
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/ordenCompra/" method="POST" style="display: inline;">
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

    $sql = Lista_Proveedores();
    //$ArtJson = FG_Armar_Json($sql,MiUbicacion());
    $ArtJson = FG_Armar_Json($sql,'FTN');

    //$SedeDestino = FG_Nombre_Sede(MiUbicacion());
    $SedeDestino = FG_Nombre_Sede('FTN');

    //$SedeOrigen = FG_Nombre_Sede(MiUbicacion());
    $SedeOrigen = FG_Nombre_Sede('FTN');

    //$SiglasOrigen = MiUbicacion();
    $SiglasOrigen = 'FTN';

    $Operador = auth()->user()->name;
    $FHoy = date('Y-m-d H:i:s');
    ?>

    {!! Form::model($OrdenCompra, ['route' => ['ordenCompra.update', $OrdenCompra], 'method' => 'PUT']) !!}

    {!! Form::hidden('Operador', $Operador) !!}
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
              <th scope="row">{!! Form::label('fecha_actual)', 'Fecha Actual') !!}</th>
              <td><label><?php echo($OrdenCompra->created_at); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('Operador)', 'Operador') !!}</th>
              <td><label><?php echo($OrdenCompra->user); ?></label></td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('proveedor', 'Proveedor') !!}</th>
              <td>   
                <div class="autocomplete" style="width:100%;">
                <input id="myInput" class="form-control" type="text" name="proveedor" placeholder="Ingrese el nombre del proveedor " onkeyup="conteo()"  required="required" autofocus="autofocus" autocomplete="off">
                <input id="myId" name="IdP" type="hidden">
                </div>
              </td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('CDD', '¿Centro de Distribucion?') !!}
              </th>
              <td>
                <select name="CDD" id="CDD" class="form-control" required="required" onchange="sede_case()">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </td>
          </tr> 
          <tr>
            <th scope="row">{!! Form::label('destino', 'Destino') !!}</th>
            <td><label id="SedeD" name="SedeD"><?php echo($SedeDestino); ?></td>
            <input id="SedeDestino" name="SedeDestino" type="hidden" value="{{$SedeDestino}}">
            <input id="SedeOrigen" name="SedeOrigen" type="hidden" value="{{$SedeOrigen}}">
            <input id="SiglasOrigen" name="SiglasOrigen" type="hidden" value="{{$SiglasOrigen}}">
          </tr>
          <tr>
            <th>Fecha estimada de despacho</th>
            <td>
              <input id="fecha_estimada_despacho" type="date" name="fecha_estimada_despacho" required style="width:100%;" class="form-control">
            </td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('condicion','Condicion crediticia') !!}
              </th>
              <td>
                <select name="condicion" id="condicion" class="form-control" onchange="condicion_case()">
                  <option value="CONTADO">CONTADO</option>
                  <option value="CREDITO">CREDITO</option>
                  <option value="PRE PAGADO">PRE PAGADO</option>
                </select>
              </td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('dias_c', 'Dias de credito') !!}</th>
            <td>
              <input type="number" id="dias_credito" name="dias_credito" value="0" class="form-control" disabled="disabled">
            </td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('moneda','Moneda') !!}
              </th>
              <td>
                <select name="moneda" id="moneda" class="form-control">
                  <option value="<?php echo(SigVe); ?>"><?php echo(SigVe); ?></option>
                  <option value="<?php echo(SigDolar); ?>"><?php echo(SigDolar); ?></option>
                </select>
              </td>
          </tr>
          <tr>
            <th scope="row">{!! Form::label('observacion', 'Nota') !!}</th>
            <td>{!! Form::textarea('observacion', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes', 'rows' => '3']) !!}</td>
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

@section('scriptsFoot')
<?php
  if($ArtJson!=""){
?>
  <script type="text/javascript">
    ArrJs = eval(<?php echo $ArtJson ?>);
    autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
  </script> 
<?php
  }
?>  
  <script type="text/javascript">
    function sede_case(){
      var CDD = document.getElementById("CDD").value;
      if(CDD=="SI"){
        document.getElementById("SedeD").innerHTML = "CENTRO DE DISTRIBUCION";
      }
      else if(CDD=="NO"){
        document.getElementById("SedeD").innerHTML = document.getElementById("SedeDestino").value;
      }
    }

    function condicion_case(){
      var condicion = document.getElementById("condicion").value;
      console.log(condicion);

      if(condicion=="CREDITO"){
        document.getElementById("dias_credito").value = "0";
        document.getElementById("dias_credito").disabled = false;
      }
      else if(condicion!="CREDITO"){
        document.getElementById("dias_credito").value = "0";
        document.getElementById("dias_credito").disabled = "disabled";
      }
    }
  </script>
@endsection

<?php
/**********************************************************************************/
  /*
    TITULO: Lista_Proveedores
    FUNCION: Armar una lista de proveedores
    RETORNO: Lista de proveedores
    DESAROLLADO POR: SERGIO COVA
  */
  function Lista_Proveedores() {
    $sql = "
      SELECT
      GenPersona.Nombre,
      ComProveedor.Id
      FROM ComProveedor
      INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
      INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
      GROUP BY ComProveedor.Id, GenPersona.Nombre
      ORDER BY ComProveedor.Id ASC
    ";
    return $sql;
  }
?>