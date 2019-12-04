@extends('layouts.modelUser')

@section('title')
  Dosificaciones
@endsection

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');

  function ValidarFecha($FechaActual){
    $arrayValidaciones = array(2);
    $FechaActual = date("Y-m-d",strtotime($FechaActual."- 1 days"));
    $TasaActual = TasaFechaConversion($FechaActual,'USD $.');
    $arrayValidaciones[0] = $FechaActual;
    $arrayValidaciones[1] = $TasaActual;
    return $arrayValidaciones;
  }
?>

<style>
  form table thead + tbody tr td input {text-align:center;}
</style>

<script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
<script>
  /*
    TITULO: calcularCantidadJarabes
    PARAMETROS : No aplica
    FUNCION: Realizar los calculos necesarios para dosificar los jarabes
    RETORNO: No aplica

    Variables:
      - Variables de entrada:
        * cantDJ: Cantidad de CC por dosis
        * itervHJ: Intervalo de horas de la dosis
        * diasJ: Dias del tratamiento
        * medicamentoJ: Cantidad de ML del medicamento
        * concentracion: Concentracion del medicamento
        * concentracionR: Concentracion recomendada del medicamento
        * concentracionD: Concentracion disponible del medicamento
      - Variables de salida:
        * resultadoJ1: Cantidad de ML requeridos
        * resultadoJ2: ML Requeridos Convertidos
        * resultadoJ3: Nueva Dosis Convertida (CC)
        * resultadoJ4: Cantidad Frascos a Comprar
        * resultadoJ5: Unidades de Frascos Redondeadas
  */

  function calcularCantidadJarabes() {
    var cantDJ=0,itervHJ=0,diasJ=0,medicamentoJ=0,
    concentracion=0,concentracionR=0,concentracionD=0;

    cantDJ=parseFloat(document.getElementById('cantDJ').value);
    itervHJ=parseFloat(document.getElementById('itervHJ').value);
    diasJ = parseInt(document.getElementById('diasJ').value);
    medicamentoJ = parseFloat(document.getElementById('medicamentoJ').value);
    concentracion = parseFloat(document.getElementById('concentracion').value);
    concentracionR = parseFloat(document.getElementById('concentracionR').value);
    concentracionD = parseFloat(document.getElementById('concentracionD').value);

    if(cantDJ<0 || itervHJ<0 || diasJ<0 || medicamentoJ<0 || concentracion<0 || concentracionR<0 || concentracionD<0) {
      $('#errorModalCenter').modal('show');//Llama al modal de error
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
      if(medicamentoJ<0) {
        document.getElementById('medicamentoJ').value=0;
        medicamentoJ=0;
      }
      if(concentracion<0) {
        document.getElementById('concentracion').value=0;
        concentracion=0;
      }
      if(concentracionR<0) {
        document.getElementById('concentracionR').value=0;
        concentracionR=0;
      }
      if(concentracionD<0) {
        document.getElementById('concentracionD').value=0;
        concentracionD=0;
      }
    }

    if(!isNaN(cantDJ) && !isNaN(itervHJ) && !isNaN(diasJ)) {
      if((cantDJ>0) && (itervHJ>0) && (diasJ>0)) {
        var resultadoJ1 = cantDJ * (24/itervHJ) * diasJ;
        document.getElementById('resultadoJ1').value = resultadoJ1.toFixed(2);

        if(!isNaN(medicamentoJ) && !isNaN(concentracion) && !isNaN(concentracionR) && !isNaN(concentracionD)) {
          if(medicamentoJ>0 && concentracion>0 && concentracionR>0 && concentracionD>0) {
            var aux1 = (concentracionR/concentracion);
            var aux2 = (concentracionD/concentracion);
            var aux3 = (aux1/aux2);
            var resultadoJ2 = (resultadoJ1*aux3).toFixed(2);
            var resultadoJ3 = (aux3*cantDJ).toFixed(2);
            var resultadoJ4 = ((resultadoJ1*aux3)/medicamentoJ).toFixed(2);
            var resultadoJ5 = Math.ceil(resultadoJ4);

            document.getElementById('resultadoJ2').value = resultadoJ2;
            document.getElementById('resultadoJ3').value = resultadoJ3;
            document.getElementById('resultadoJ4').value = resultadoJ4;
            document.getElementById('resultadoJ5').value = resultadoJ5;
          }
        }
        else if(!isNaN(medicamentoJ)) {
          var resultadoJ4 = (resultadoJ1/medicamentoJ).toFixed(2);
          var resultadoJ5 = Math.ceil(resultadoJ4);

          document.getElementById('resultadoJ4').value = resultadoJ4;
          document.getElementById('resultadoJ5').value = resultadoJ5;
        }
      }
    }
  }
</script>

@section('content')
  <!-- Modal Dosis Jarabe -->
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
          <h4 class="h6">El valor colocado en <b>horas de dosis</b> no es un n&uacute;mero divisible entre 24 horas</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-warning" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

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

  <a name="Inicio"></a>
  <hr class="row align-items-start col-12">
  <h5 class="text-info">
    <i class="fas fa-prescription"></i>
    CONVERSI&Oacute;N DE DOSIFICACIONES PARA JARABES
  </h5>
  <hr class="row align-items-start col-12">
  <a name="dosificaciones-jarabes"></a>
  <form name="jarabes" class="form-group">
    <table class="table table-borderless table-hover">
      <thead class="thead-dark" align="center">
        <th scope="col" colspan="4">
          <b>DOSIFICACIONES PARA JARABES - CONVERSI&Oacute;N</b>
        </th>
      </thead>
    
      <tbody align="right">
        <tr>
          <td>
            Cantidad CC por Dosis:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="1" placeholder="0,00" id="cantDJ" name="cantDJ" class="form-control bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();" autofocus>
          </td>

          <td>
            Cantidad de ML del medicamento:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="4" placeholder="0,00" id="medicamentoJ" name="medicamentoJ" class="form-control bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td>
            Cada cuantas horas la Dosis:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="2" placeholder="0,00" id="itervHJ" name="itervHJ" class="form-control bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>

          <td>
            Concentracion por (MG):
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="5" placeholder="0,00" id="concentracion" name="concentracion" class="form-control bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td>
            Cantidad de dias del Tratamiento:
          </td>

          <td>
            <input type="number" min="0" tabindex="3" placeholder="0" id="diasJ" name="diasJ" class="form-control bg-warning" pattern="[0-9]*" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>
          
          <td>
            Concentracion Recetada (MG):
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="6" placeholder="0,00" id="concentracionR" name="concentracionR" class="form-control bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>

          <td>
            Concentracion Disponible (MG):
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="7" placeholder="0,00" id="concentracionD" name="concentracionD" class="form-control bg-warning" onblur="calcularCantidadJarabes();" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td>
            Cantidad de ML Requeridos:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ1" class="form-control" disabled>
          </td>

          <td>
            ML Requeridos Convertidos:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ2" class="form-control" disabled>
          </td>
        </tr>
      </tbody>

      <thead class="thead-dark" align="center">
        <th scope="col" colspan="4">
          <b>RESULTADOS</b>
        </th>
      </thead>

      <tbody align="right">
        <tr>
          <td>
            Nueva Dosis Convertida (CC):
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ3" class="form-control" disabled>
          </td>

          <td>
            Cantidad Frascos a Comprar:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ4" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Unidades de Frascos Redondeadas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ5" class="form-control" disabled>
          </td>

          <td>
            <button type="reset" tabindex="8" class="btn btn-success">
              Borrado dosificacion de jarabes
            </button>
          </td>

          <td>
            <a href="#ver-manual" tabindex="9" class="btn btn-primary">
              Ver instrucciones
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
          * El Campo <b>Cantidad dias de Tratamiento</b> solo acepta numeros enteros
        </td>
      </tr>

      <tr>
        <td>
          * El boton de borrado solo afecta los campos en <span class="bg-warning text-dark"><b>AMARILLO<b></span>
        </td>
      </tr>

      <tr>
        <td>
          * El calculo que requiere el cliente solo se mostrara al completar <b>TODOS</b> los campos <span class="bg-warning text-dark"><b>AMARILLOS<b></span>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="text-center">
    <a href="#Inicio" tabindex="10" title="Volver al inicio" class="btn btn-primary">
      Volver al inicio
    </a>
  </div>

  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
  </script>

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
          document.getElementById("concentracion").focus();
        }
        else if(document.activeElement.name == 'concentracion') {
          document.getElementById("concentracionR").focus();
        }
        else if(document.activeElement.name == 'concentracionR') {
          document.getElementById("concentracionD").focus();
        }
        else if(document.activeElement.name == 'concentracionD') {
          document.getElementById("cantDJ").focus();
        }
      }
    }
  </script>
@endsection