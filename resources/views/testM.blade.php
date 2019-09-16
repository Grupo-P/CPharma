@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

<style>
  form table thead + tbody tr td input {text-align:center;}
</style>

@section('scriptsHead')
  <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
@endsection

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');
  include(app_path().'\functions\reportes.php');

  /*
    TITULO: ValidarFecha
    PARAMETROS : [$FechaTasaDolar] fecha actual
                 [$Moneda] la moneda a buscar
    FUNCION: Realizar la busqueda de la tasa segun la fecha, en caso de no ser la fecha del dia, se haran tantas iteraciones hacia atras como sean necesarias hasta encontrar una tasa valida
    RETORNO: Un array conteniendo la fecha y la tasa encontrada
  */

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

<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
  });

  function separarMiles(cantidad, decimales) {
    // por si pasan un numero en vez de un string
    cantidad += ''; 

    // elimino cualquier cosa que no sea numero o punto
    cantidad = parseFloat(cantidad.replace(/[^0-9\.]/g, '')); 
    
    // por si la variable no fue fue pasada
    decimales = decimales || 0; 

    // si no es un numero o es igual a cero retorno el mismo cero
    if(isNaN(cantidad) || cantidad === 0)  {
        return parseFloat(0).toFixed(decimales);
    }

    // si es mayor o menor que cero retorno el valor formateado como numero
    cantidad = '' + cantidad.toFixed(decimales);
    var cantidad_parts = cantidad.split('.'),
    regexp = /(\d+)(\d{3})/;

    while(regexp.test(cantidad_parts[0])) {
        cantidad_parts[0] = cantidad_parts[0].replace(regexp, '$1' + ',' + '$2');
    }

    return cantidad_parts.join('.');
  }

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
  }
  /*
    TITULO: limpiarClases
    PARAMETROS : No aplica
    FUNCION: Limpia todos los residuos de clases que pintan colores
    RETORNO: No aplica

    Variables:
      * resultado: El campo donde se refleja la deuda del cliente
  */
 
  function limpiarClases() {
    var resultado=document.getElementById('resultado');
    resultado.value = "-";
    resultado.classList.remove("bg-danger", "text-white");
    document.getElementById("fac1").focus();
  }

  /*
    TITULO: calcularFactura
    PARAMETROS : No aplica
    FUNCION: Realizar los calculos para conectar una o las tres facturas en un resultado dado en bolivares o divisas
    RETORNO: No aplica

    Variables:
      - Variables de entrada:
        * fac1: Factura #1 del cliente Bs 
        * fac2: Factura #2 del cliente Bs 
        * fac3: Factura #3 del cliente Bs 
        * tasa: Tasa en dolares traida de la BBDD
        * decimales: Cantidad de decimales a manejar traida de la BBDD
      - Variables de salida:
        * totalFacBs: Total de las facturas en Bs
        * totalFacDs: Total de las facturas en $
        * tolerancia: Limite de vuelto significativo traida de la BBDD
        * resultado: Campo que muestra el resultado final de los calculos
  */

  function calcularFactura() {
    var fac1=0,fac2=0,fac3=0,tasa=0,decimales=0,totalFacBs=0,totalFacDs=0;

    fac1=parseFloat(document.getElementById('fac1').value);
    fac2=parseFloat(document.getElementById('fac2').value);
    fac3=parseFloat(document.getElementById('fac3').value);
    tasa = parseFloat(document.getElementById('tasa').value);
    decimales = parseInt(document.getElementById('decimales').value);

    if(fac1<0 || fac2<0 || fac3<0) {
      $('#errorModalCenter').modal('show');
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
    else {
      totalFacBs = fac1 + fac2 + fac3;
    }

    totalFacDs = (totalFacBs/tasa).toFixed(decimales);
    totalFacBs = totalFacBs.toFixed(decimales);

    document.getElementById('totalFacBs').value = totalFacBs;
    document.getElementById('totalFacDs').value = totalFacDs;
    document.getElementById('saldoRestanteBs').value = totalFacBs;
    document.getElementById('saldoRestanteDs').value = totalFacDs;

    var tolerancia=parseFloat(document.getElementById('tolerancia').value);
    var resultado=document.getElementById('resultado');

    if(totalFacBs>0) {
      document.getElementById('resultado').value = "El cliente debe: Bs. "+totalFacBs;
      resultado.classList.add("bg-danger", "text-white");
    }
    else if(totalFacBs<((-1)*tolerancia)) {
      document.getElementById('resultado').value = "Hay un vuelto pendiente de: Bs. "+totalFacBs;
      resultado.classList.remove("bg-danger", "text-white");
    }
    else {
      document.getElementById('resultado').value = "-";
      resultado.classList.remove("bg-danger", "text-white");
    }
  }

  /*
    TITULO: calcularAbono
    PARAMETROS : No aplica
    FUNCION: Realizar los calculos para finiquitar la factura basado en los abonos del cliente, permite abonar en dolares y en bolivares simultaneamente
    RETORNO: No aplica

    Variables:
      - Variables de entrada:
        * abono1: Abono #1 del cliente $
        * abono2: Abono #2 del cliente Bs
        * tasa: Tasa en dolares traida de la BBDD
        * decimales: Cantidad de decimales a manejar traida de la BBDD
        * tolerancia: Limite de vuelto significativo traida de la BBDD
      - Variables de salida:
        * convAbono1: Campo que muestra la conversion del abono en $ a Bs
        * totalAbonos: Sumatoria total de abonos (Considera $ convertidos a Bs)
        * resultado: Campo que muestra el resultado final de los calculos
        * saldoRestanteBs: Muestra el saldo que todavia se debe en Bs
        * saldoRestanteDs: Muestra el saldo que todavia se debe en $
  */

  function calcularAbono() {
    var abono1=0,abono2=0,convAbono1=0,totalAbonos=0,tasa=0,decimales=0;

    abono1 = parseFloat(document.getElementById('abono1').value);
    abono2 = parseFloat(document.getElementById('abono2').value);
    tasa = parseFloat(document.getElementById('tasa').value);
    decimales = parseInt(document.getElementById('decimales').value);

    if(abono1<0 || abono2<0) {
      $('#errorModalCenter').modal('show');
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
      $('#errorModalRango').modal('show');
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

    var totalFacBs=0,saldoRestanteBs=0,saldoRestanteDs=0;

    totalFacBs=parseFloat(document.getElementById('totalFacBs').value);

    convAbono1 = convAbono1.toFixed(decimales);
    totalAbonos = totalAbonos.toFixed(decimales);
    saldoRestanteBs = (totalFacBs-totalAbonos).toFixed(decimales);
    saldoRestanteDs = (saldoRestanteBs/tasa).toFixed(decimales);

    document.getElementById('convAbono1').value = convAbono1;
    document.getElementById('totalAbonos').value = totalAbonos;
    document.getElementById('saldoRestanteBs').value = saldoRestanteBs;
    document.getElementById('saldoRestanteDs').value = saldoRestanteDs;

    var tolerancia=parseFloat(document.getElementById('tolerancia').value);
    var resultado=document.getElementById('resultado');

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

  <!-- Modal Valores Negativos -->
  <div class="modal fade" id="errorModalCenter" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalCenterTitle"><i class="fas fa-exclamation-circle"></i>&nbsp;Error</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">No se permiten valores negativos</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Error En Rango Dolares -->
  <div class="modal fade" id="errorModalRango" tabindex="-1" role="dialog" aria-labelledby="errorModalRangoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="errorModalRangoTitle"><i class="fas fa-exclamation-circle"></i>&nbsp;Error</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">Los Abonos en dolares deben ser menores a 2000</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  
  <a name="Inicio"></a>
  <hr class="row align-items-start col-12">
  <h5 class="text-info">
    <i class="fas fa-money-bill-alt"></i>
    C&Aacute;LCULO DE FACTURA EN DIVISA
  </h5>
  <hr class="row align-items-start col-12">
  
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
    
      <tbody align="right">
        <tr>
          <td>
            Total Factura Bs (Con IVA) #1:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="fac1" id="fac1" class="form-control bg-warning" autofocus onblur="calcularFactura();" onkeypress="FocusChange()">
          </td>

          <td>
            Tasa de Cambio:
          </td>
      <?php
        if($FechaTasaDolar != $FechaActual){
      ?>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="{{$TasaDolar}}" id="tasa" class="form-control bg-danger text-white" disabled>
          </td>
      <?php
        }
        else{
      ?>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="{{$TasaDolar}}" id="tasa" class="form-control bg-success text-white" disabled>
          </td>
      <?php   
        }
      ?>
        </tr>

        <tr>
          <td>
            Total Factura Bs (Con IVA) #2:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="fac2" id="fac2" class="form-control bg-warning" onblur="calcularFactura();" onkeypress="FocusChange()">
          </td>

          <td>
            Fecha Tasa de Cambio:
          </td>

      <?php
        if($FechaTasaDolar != $FechaActual){
      ?>
          <td>
            <input type="text" value="{{date('d-m-Y',strtotime($FechaTasaDolar))}}" id="fecha" class="form-control bg-danger text-white" disabled>
          </td>
      <?php
        }
        else{
      ?>
          <td>
            <input type="text" value="{{date('d-m-Y',strtotime($FechaTasaDolar))}}" id="fecha" class="form-control bg-success text-white" disabled>
          </td>
      <?php   
        }
      ?>
        </tr>

        <tr>
          <td>
            Total Factura Bs (Con IVA) #3:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="fac3" id="fac3" class="form-control bg-warning" onblur="calcularFactura();" onkeypress="FocusChange()">
          </td>

          <td>
            Cantidad Decimales:
          </td>

          <td>
            <input type="number" min="0" max="2" placeholder="0" value="2" id="decimales" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Total Facturas Bs (Con IVA):
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalFacBs" class="form-control" disabled>
          </td>

          <td>
            Tolerancia Vuelto en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="200" id="tolerancia" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Total Factura $:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalFacDs" class="form-control" disabled>
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

      <tbody align="right">
        <tr>
          <td>
            Abono #1 en $:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="abono1" id="abono1" class="form-control bg-warning" onblur="calcularAbono();" onkeypress="FocusChange()">
          </td>

          <td>
            Saldo Restante en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="saldoRestanteBs" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Abono #2 en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" name="abono2" id="abono2" class="form-control bg-warning" onblur="calcularAbono();" onkeypress="FocusChange()">
          </td>

          <td>
            Saldo Restante en $:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="saldoRestanteDs" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Conversion Abono #1 en Bs:
          </td>

          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="convAbono1" class="form-control" disabled>
          </td>

          <td colspan="2">
            <input type="text" placeholder="-" class="form-control" id="resultado" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Total Abonos Bs:
          </td>
          
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalAbonos" class="form-control" disabled>
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
        <td>
          * El boton de borrado solo afecta los campos en <span class="bg-warning text-dark"><b>AMARILLO<b></span>
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
    <a href="#Inicio" title="Volver al inicio" class="btn btn-primary">
      Volver al inicio
    </a>
  </div>

  <script type="text/javascript">
    $('#exampleModalCenter').modal('show');
  </script>
@endsection

