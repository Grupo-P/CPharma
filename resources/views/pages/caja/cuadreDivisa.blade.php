@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');
  include(app_path().'\functions\reportes.php');

  function ValidarFecha($FechaTasaDolar,$Moneda){
    $arrayValidaciones = array(2);
    $FechaTasaDolar = date("Y-m-d",strtotime($FechaTasaDolar."- 1 days"));
    $TasaDolar = TasaFechaConversion($FechaTasaDolar,$Moneda);
    $arrayValidaciones[0] = $FechaTasaDolar;
    $arrayValidaciones[1] = $TasaDolar;
    return $arrayValidaciones;
  }

  $Moneda = 'Dolar';

  $FechaTasaDolar = new DateTime("now");
  $FechaActual = $FechaTasaDolar = $FechaTasaDolar->format("Y-m-d");
  $TasaDolar = TasaFechaConversion($FechaTasaDolar,$Moneda);

  while(is_null($TasaDolar)) {
    $arrayResult =  ValidarFecha($FechaTasaDolar,$Moneda);
    $FechaTasaDolar = $arrayResult[0];
    $TasaDolar = $arrayResult[1];
  }
?>

<script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
<script>
  function limpiarClases() {
    var resultado=document.getElementById('resultado');
    resultado.value = "-";
    resultado.classList.remove("bg-danger", "text-white");
    document.getElementById("fac1").focus();
  }

  function calcularFactura() {
    var fac1=0,fac2=0,fac3=0,totalFacBs=0,totalFacDs=0,tasa=0,decimales=0;

    fac1=parseFloat(document.getElementById('fac1').value);
    fac2=parseFloat(document.getElementById('fac2').value);
    fac3=parseFloat(document.getElementById('fac3').value);
    tasa = parseFloat(document.getElementById('tasa').value);
    decimales = parseInt(document.getElementById('decimales').value);

    if(fac1<0 || fac2<0 || fac3<0) {
      //alert("No se admiten valores negativos.");
      if(fac1<0) {
        document.getElementById('fac1').value=0;
        fac1=0;
      }

      if(fac2<0) {
        document.getElementById('fac2').value=0;
        fac2=0;
      }

      if(fac3<0) {
        document.getElementById('fac3').value=0;
        fac3=0;
      }
    }

    if(isNaN(fac1) || isNaN(fac2) || isNaN(fac3)) {
      if(!isNaN(fac1)) {
        totalFacBs = fac1;
        if(!isNaN(fac2)) {
          totalFacBs = fac1 + fac2;
        }
        if(!isNaN(fac3)) {
          totalFacBs = fac1 + fac3;
        }
      }

      if(!isNaN(fac2)) {
        totalFacBs = fac2;
        if(!isNaN(fac1)) {
          totalFacBs = fac2 + fac1;
        }
        if(!isNaN(fac3)) {
          totalFacBs = fac2 + fac3;
        }
      }

      if(!isNaN(fac3)) {
        totalFacBs = fac3;
        if(!isNaN(fac1)) {
          totalFacBs = fac3 + fac1;
        }
        if(!isNaN(fac2)) {
          totalFacBs = fac3 + fac2;
        }
      }
    }
    else{
      totalFacBs = fac1 + fac2 + fac3;
    }

    totalFacDs = parseFloat(totalFacBs).toFixed(decimales) / tasa;

    document.getElementById('totalFacBs').value = parseFloat(totalFacBs).toFixed(decimales);
    document.getElementById('totalFacDs').value = parseFloat(totalFacDs).toFixed(decimales);

    var tolerancia=parseFloat(document.getElementById('tolerancia').value);
    var resultado=document.getElementById('resultado');
    var saldoRestanteBs=parseFloat(totalFacBs).toFixed(decimales);
    var saldoRestanteDs=parseFloat(totalFacDs).toFixed(decimales);

    document.getElementById('saldoRestanteBs').value = saldoRestanteBs;
    document.getElementById('saldoRestanteDs').value = saldoRestanteDs;

    if(saldoRestanteBs>0) {
      document.getElementById('resultado').value = "El cliente debe: Bs. "+saldoRestanteBs;
      resultado.classList.add("bg-danger", "text-white");
    }
    else if(saldoRestanteBs<((-1)*tolerancia)) {
      document.getElementById('resultado').value = "Hay un vuelto pendiente de: Bs. "+saldoRestanteBs;
      resultado.classList.remove("bg-danger", "text-white");
    }
    else {
      document.getElementById('resultado').value = "-";
      resultado.classList.remove("bg-danger", "text-white");
    }
  }

  function calcularAbono() {
    var abono1=0,abono2=0,convAbono1=0,totalAbonos=0,tasa=0,decimales=0;

    abono1 = parseFloat(document.getElementById('abono1').value);
    abono2 = parseFloat(document.getElementById('abono2').value);
    tasa = parseFloat(document.getElementById('tasa').value);
    decimales = parseInt(document.getElementById('decimales').value);

    if(abono1<0 || abono2<0) {
      //alert("No se admiten valores negativos.");
      if(abono1<0) {
        document.getElementById('abono1').value=0;
        abono1=0;
      }

      if(abono2<0) {
        document.getElementById('abono2').value=0;
        abono2=0;
      }
    }

    if(!isNaN(abono1) && abono1>2000) {
      //alert("Los abonos ingresados en $ deben ser menores a 2000");
      document.getElementById('abono1').value=0;
      abono1=0;
    }
    else if(!isNaN(abono1)) {
      convAbono1 = abono1*tasa;
      totalAbonos=convAbono1;
      if(!isNaN(abono2)) {
        totalAbonos = convAbono1+abono2;
      }
    }

    if(!isNaN(abono2)) {
      totalAbonos=abono2;
      if(!isNaN(convAbono1)) {
        totalAbonos = convAbono1+abono2;
      }
    }

    var totalFacBs=0,saldoRestanteBs=0,saldoRestanteDs=0,resultado='';

    totalFacBs=parseFloat(document.getElementById('totalFacBs').value);

    saldoRestanteBs = totalFacBs-totalAbonos;
    saldoRestanteDs = saldoRestanteBs/tasa;

    document.getElementById('convAbono1').value = parseFloat(convAbono1).toFixed(decimales);
    document.getElementById('totalAbonos').value = parseFloat(totalAbonos).toFixed(decimales);
    document.getElementById('saldoRestanteBs').value = parseFloat(saldoRestanteBs).toFixed(decimales);
    document.getElementById('saldoRestanteDs').value = parseFloat(saldoRestanteDs).toFixed(decimales);

    var tolerancia=parseFloat(document.getElementById('tolerancia').value);
    var resultado=document.getElementById('resultado');

    if(saldoRestanteBs>0) {
      document.getElementById('resultado').value = "El cliente debe: Bs. "+saldoRestanteBs.toFixed(decimales);
      resultado.classList.add("bg-danger", "text-white");
    }
    else if(saldoRestanteBs<((-1)*tolerancia)) {
      document.getElementById('resultado').value = "Hay un vuelto pendiente de: Bs. "+saldoRestanteBs.toFixed(decimales);
      resultado.classList.remove("bg-danger", "text-white");
    }
    else {
      document.getElementById('resultado').value = "-";
      resultado.classList.remove("bg-danger", "text-white");
    }
  }
</script>

@section('content')
  <!-- Modal Fecha -->
  @if($FechaTasaDolar != $FechaActual)
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning" id="exampleModalCenterTitle"><i class="fas fa-exclamation-triangle"></i>&nbsp;Advertencia</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">La tasa de venta no est&aacute; actualizada, contacte a su supervisor.</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-warning" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <hr class="row align-items-start col-12">
  <h5 class="text-info">
    <i class="fas fa-money-bill-alt"></i>
    Calculo de factura en divisa
  </h5>
  <hr class="row align-items-start col-12">
  
  <a name="calculo-conversiones"></a>
  <form name="cuadre" class="form-group">
    <table class="table table-borderless table-hover">
      <thead class="thead-dark" align="center">
        <th scope="col" colspan="2">
          <b>FACTURAS DEL CLIENTE</b>
        </th>

        <th scope="col" colspan="2">
           <b>INFORMACI&Oacute;N</b>
        </th>
      </thead>
    
      <tbody>
        <tr>
          <td class="text-right">
            Total Factura Bs (Con IVA) #1:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="fac1" id="fac1" class="form-control text-center bg-warning" autofocus onblur="calcularFactura();" onkeypress="FocusChange()">
          </td>

          <td class="text-right">
            Tasa de Cambio:
          </td>
      <?php
        if($FechaTasaDolar != $FechaActual){
      ?>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="{{$TasaDolar}}" id="tasa" class="form-control text-center bg-danger text-white" disabled>
          </td>
      <?php
        }
        else{
      ?>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="{{$TasaDolar}}" id="tasa" class="form-control text-center bg-success text-white" disabled>
          </td>
      <?php   
        }
      ?>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura Bs (Con IVA) #2:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="fac2" id="fac2" class="form-control text-center bg-warning" onblur="calcularFactura();" onkeypress="FocusChange()">
          </td>

          <td class="text-right">
            Fecha Tasa de Cambio:
          </td>

      <?php
        if($FechaTasaDolar != $FechaActual){
      ?>
          <td>
            <input type="text" value="{{date('d-m-Y',strtotime($FechaTasaDolar))}}" id="fecha" class="form-control text-center bg-danger text-white" disabled>
          </td>
      <?php
        }
        else{
      ?>
          <td>
            <input type="text" value="{{date('d-m-Y',strtotime($FechaTasaDolar))}}" id="fecha" class="form-control text-center bg-success text-white" disabled>
          </td>
      <?php   
        }
      ?>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura Bs (Con IVA) #3:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="fac3" id="fac3" class="form-control text-center bg-warning" onblur="calcularFactura();" onkeypress="FocusChange()">
          </td>

          <td class="text-right">
            Cantidad Decimales:
          </td>

          <td>
            <input type="number" min="0" max="2" placeholder="0" value="2" id="decimales" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Total Facturas Bs (Con IVA):
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalFacBs" class="form-control text-center" disabled>
          </td>

          <td class="text-right">
            Tolerancia Vuelto en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="200" id="tolerancia" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura $:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalFacDs" class="form-control text-center" disabled>
          </td>
          
          <td colspan="2">&nbsp;</td>
        </tr>
      </tbody>

       <thead class="thead-dark" align="center">
        <th scope="col" colspan="2">
          <b>ABONOS DEL CLIENTE</b>
        </th>

        <th scope="col" colspan="2">
          <b>SALDOS RESTANTES</b>
        </th>
      </thead>

      <tbody>
        <tr>
          <td class="text-right">
            Abono #1 en $:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="abono1" id="abono1" class="form-control text-center bg-warning" onblur="calcularAbono();" onkeypress="FocusChange()">
          </td>

          <td class="text-right">
            Saldo Restante en $:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="saldoRestanteDs" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Abono #2 en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="abono2" id="abono2" class="form-control text-center bg-warning" onblur="calcularAbono();" onkeypress="FocusChange()">
          </td>

          <td class="text-right">
            Saldo Restante en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="saldoRestanteBs" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Conversion Abono #1 en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="convAbono1" class="form-control text-center" disabled>
          </td>

          <td colspan="2">
            <input type="text" placeholder="-" class="form-control text-center" id="resultado" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Total Abonos Bs:
          </td>
          
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalAbonos" class="form-control text-center" disabled>
          </td>

          <td class="text-center">
            <button type="reset" name="btn-borrarN" id="btn-borrarN" class="btn btn-success" onclick="limpiarClases();">
              Borrar y empezar de nuevo
            </button>
          </td>

          <td class="text-center">
            <a href="#ver-manual" title="Ir al manual de usuario" class="btn btn-primary">
              Ver instrucciones
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </form>

  <br><br>

  <a name="ver-manual"></a>
  <table class="table table-bordered table-striped">
    <thead class="thead-dark" align="center">
      <th scope="col">
        <b>INSTRUCCIONES</b>
      </th>
    </thead>

    <tbody>
      <tr>
        <td>* Solo debes colocar informacion en los campos <span class="bg-warning text-dark"><b>AMARILLOS<b></span>
        </td>
      </tr>

      <tr>
        <td>* Si el cliente presenta deuda, lo veras en color <span class="bg-danger text-white"><b>ROJO<b></span>
        </td>
      </tr>

      <tr>
        <td>
          * Verifica que la <b>tasa</b> sea la del dia en curso.
        </td>
      </tr>

      <tr>
        <td>
          * El campo de <b>abonos en dolares</b> solo acepta montos menores a 2000$.
        </td>
      </tr>

    </tbody>
  </table>

  <div class="text-center">
    <a href="#calculo-conversiones" title="Volver al inicio" class="btn btn-primary">
      Volver al inicio
    </a>
  </div>

  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
    $('#exampleModalCenter').modal('show');
  </script>

  <script>
      function FocusChange() {
        if (event.keyCode == 13) {

          if(document.activeElement.name){
            var ElementoActivo = document.activeElement.name;
          }
          
          if(document.activeElement.name == 'fac1'){
            document.getElementById("fac2").focus();
          }
          else if(document.activeElement.name == 'fac2'){
            document.getElementById("fac3").focus();
          }
          else if(document.activeElement.name == 'fac3'){
            document.getElementById("abono1").focus();
          }
          else if(document.activeElement.name == 'abono1'){
            document.getElementById("abono2").focus();
          }
          else if(document.activeElement.name == 'abono2'){
            document.getElementById("btn-borrarN").focus();
          }
        }
      };
    </script>
@endsection