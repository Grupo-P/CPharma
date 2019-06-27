@extends('layouts.modelUser')

@section('title')
  Dosificaciones
@endsection

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');
  include(app_path().'\functions\reportes.php');

  function ValidarFecha($FechaActual){
    $arrayValidaciones = array(2);
    $FechaActual = date("Y-m-d",strtotime($FechaActual."- 1 days"));
    $TasaActual = TasaFechaConversion($FechaActual,'USD $.');
    $arrayValidaciones[0] = $FechaActual;
    $arrayValidaciones[1] = $TasaActual;
    return $arrayValidaciones;
  }
?>

<script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
<script>
  /*function limpiarClases() {
    var resultado=document.getElementById('resultado');
    resultado.value = "-";
    resultado.classList.remove("bg-danger", "text-white");
  }*/

  function calcularCantidadJarabes() {
    var cantDJ=0,itervHJ=0,diasJ=0,resultadoJ1=0;

    cantDJ=parseFloat(document.getElementById('cantDJ').value);
    itervHJ=parseFloat(document.getElementById('itervHJ').value);
    diasJ = parseInt(document.getElementById('diasJ').value);

    if(cantDJ<0 || itervHJ<0 || diasJ<0) {
      alert("No se admiten valores negativos.");
      if(cantDJ<0) {
        document.getElementById('cantDJ').value=0;
        cantDJ=0;
      }

      if(itervHJ<0) {
        document.getElementById('itervHJ').value=0;
        itervHJ=0;
      }

      if(diasJ<0) {
        document.getElementById('diasJ').value=0;
        diasJ=0;
      }
    }

    if(!isNaN(cantDJ) && !isNaN(itervHJ) && !isNaN(diasJ)) {
      resultadoJ1 = cantDJ * (24/itervHJ) * diasJ;
      document.getElementById('resultadoJ1').value = resultadoJ1.toFixed(2);
    }
  }

  function calcularAbono() {
    var abono1=0,abono2=0,convAbono1=0,totalAbonos=0,tasa=0,decimales=0;

    abono1 = parseFloat(document.getElementById('abono1').value);
    abono2 = parseFloat(document.getElementById('abono2').value);
    tasa = parseFloat(document.getElementById('tasa').value);
    decimales = parseInt(document.getElementById('decimales').value);

    if(abono1<0 || abono2<0) {
      alert("No se admiten valores negativos.");
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
      alert("Los abonos ingresados en $ deben ser menores a 2000");
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
  <hr class="row align-items-start col-12">
  <h5 class="text-info">
    <i class="fas fa-eye-dropper"></i>
    C&Aacute;LCULO DE DOSIFICACIONES
    <a href="#ver-manual" tabindex="15" class="btn btn-primary">
      Ver instrucciones
    </a>
  </h5>
  <hr class="row align-items-start col-12">
  
  <a name="dosificaciones-jarabes"></a>
  <form name="jarabes" class="form-group">
    <table class="table table-borderless table-hover">
      <thead class="thead-dark" align="center">
        <th scope="col" colspan="4">
          <b>DOSIFICACIONES PARA JARABES</b>
        </th>
      </thead>
    
      <tbody>
        <tr>
          <td class="text-right">
            Cantidad CC por Dosis:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="1" placeholder="0,00" id="cantDJ" name="cantDJ" class="form-control text-center bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();" autofocus>
          </td>

          <td class="text-right">
            Cantidad de ML del medicamento:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="4" placeholder="0,00" id="medicamentoJ" name="medicamentoJ" class="form-control text-center bg-warning" onblur="" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Cada cuantas horas la Dosis:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="2" placeholder="0,00" id="itervHJ" name="itervHJ" class="form-control text-center bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>

          <td class="text-right">
            Unidades Requeridas a Comprar:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ2" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Cantidad de dias del Tratamiento:
          </td>

          <td>
            <input type="number" min="0" tabindex="3" placeholder="0" id="diasJ" name="diasJ" class="form-control text-center bg-warning" pattern="[0-9]*" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>
          
          <td class="text-right">
            Unidades Redondeadas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ3" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Cantidad de ML Requeridos:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ1" class="form-control text-center" disabled>
          </td>
          
          <td class="text-center">
            <button type="reset" tabindex="5" class="btn btn-success">
              Borrado dosificacion de jarabes
            </button>
          </td>

          <td class="text-center">
            <a href="#dosificaciones-tabletas" tabindex="6" class="btn btn-primary">
              Ir a dosificaciones para tabletas
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
  
  <a name="dosificaciones-tabletas"></a>
  <form name="tabletas" class="form-group">
    <table class="table table-borderless table-hover">
      <thead class="thead-dark" align="center">
        <th scope="col" colspan="4">
          <b>DOSIFICACIONES PARA TABLETAS</b>
        </th>
      </thead>
    
      <tbody>
        <tr>
          <td class="text-right">
            Cantidad MG por Dosis Recetada:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="7" placeholder="0,00" id="cantDT" class="form-control text-center bg-warning" onblur="">
          </td>

          <td class="text-right">
            Concentracion del medicamento (MG):
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="10" placeholder="0,00" id="concentracion" class="form-control text-center bg-warning" onblur="">
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Cuantas veces al dia la Dosis:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="8" placeholder="0,00" id="itervHT" class="form-control text-center bg-warning" onblur="">
          </td>

          <td class="text-right">
            Cantidad de Pastillas del medicamento:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="11" placeholder="0,00" id="medicamentoT" class="form-control text-center bg-warning" onblur="">
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Cantidad de dias del Tratamiento:
          </td>

          <td>
            <input type="number" min="0" tabindex="9" placeholder="0" id="diasT" class="form-control text-center bg-warning" onblur="">
          </td>

          <td class="text-right">
            Unidades Requeridas a Comprar:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoT2" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Cantidad de Dosis Requeridas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoT1" class="form-control text-center" disabled>
          </td>

          <td class="text-right">
            Unidades Redondeadas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoT3" class="form-control text-center" disabled>
          </td>
        </tr>

        <tr>
          <td class="text-center" colspan="2">
            <button type="reset" tabindex="12" class="btn btn-success">
              Borrado dosificacion de tabletas
            </button>
          </td>

          <td class="text-center" colspan="2">
            <a href="#dosificaciones-jarabes" tabindex="13" class="btn btn-primary">
              Ir a dosificaciones para jarabes
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </form>

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
          * Los Campos <b>"Cantidad dias de Tratamiento"</b> solo acepta numeros enteros.
        </td>
      </tr>

      <tr>
        <td>
          * Si los miligramos recomendados vs el recetado <b>no es igual</b> o la mitad o el doble se emite una alerta.
        </td>
      </tr>

      <tr>
        <td>
          * El boton de borrado solo toca los campos en amarillo.
        </td>
      </tr>

      <tr>
        <td>
          * El calculo que requiere el cliente no se mostrara hasta que se coloquen todos los campos en amarillo.
        </td>
      </tr>

      <tr>
        <td>
          * El Campo de dosis requeridas permite numeros decimales mayores a 0.
        </td>
      </tr>

      <tr>
        <td>
          * El valor colocado en la parte de jarabes para decir cada cuantas horas es el tratamiento deberia ser divisible entre 24 hrs.
        </td>
      </tr>
    </tbody>
  </table>

  <div class="text-center">
    <a href="#dosificaciones-jarabes" tabindex="14" title="Volver al inicio" class="btn btn-primary">
      Volver al inicio
    </a>
  </div>

  <script>
    function FocusChange() {
      if(event.keyCode == 13) {
        if(document.activeElement.name) {
          var ElementoActivo = document.activeElement.name;
        }
        
        if(document.activeElement.name == 'cantDJ') {
          document.getElementById("itervHJ").focus();
        }
        else if(document.activeElement.name == 'itervHJ') {
          document.getElementById("diasJ").focus();
        }
        else if(document.activeElement.name == 'diasJ') {
          document.getElementById("medicamentoJ").focus();
        }
        else if(document.activeElement.name == 'medicamentoJ') {
          document.getElementById("cantDJ").focus();
        }
      }
    }
  </script>
@endsection