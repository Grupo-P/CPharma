@extends('layouts.modelUser')

@section('title')
  Dosificaciones
@endsection

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

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
      - Variables de salida:
        * resultadoJ1: Cantidad de ML requeridos
        * resultadoJ2: Unidades requeridas a comprar
        * resultadoJ3: Unidades redondeadas
  */

  function calcularCantidadJarabes() {
    var cantDJ=0,itervHJ=0,diasJ=0,medicamentoJ=0;

    cantDJ=parseFloat(document.getElementById('cantDJ').value);
    itervHJ=parseFloat(document.getElementById('itervHJ').value);
    diasJ = parseInt(document.getElementById('diasJ').value);
    medicamentoJ = parseFloat(document.getElementById('medicamentoJ').value);

    if(cantDJ<0 || itervHJ<0 || diasJ<0 || medicamentoJ<0) {
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
    }

    if(!isNaN(cantDJ) && !isNaN(itervHJ) && !isNaN(diasJ)) {
      if((cantDJ>0) && (itervHJ>0) && (diasJ>0)) {
        var resultadoJ1 = cantDJ * (24/itervHJ) * diasJ;
        document.getElementById('resultadoJ1').value = resultadoJ1.toFixed(2);

        if(!isNaN(medicamentoJ)) {
          if(medicamentoJ>0) {
            var resultadoJ2 = (resultadoJ1/medicamentoJ).toFixed(2);
            var resultadoJ3 = Math.ceil(resultadoJ2);

            document.getElementById('resultadoJ2').value = resultadoJ2;
            document.getElementById('resultadoJ3').value = resultadoJ3;

            if(24%itervHJ!==0) {
              //Llama al modal de advertencia
              $('#exampleModalCenter').modal('show');
            }
          }
        }
      }
    }
  }

  /*
    TITULO: calcularCantidadTabletas
    PARAMETROS : No aplica
    FUNCION: Realizar los calculos necesarios para dosificar las tabletas
    RETORNO: No aplica

    Variables:
      - Variables de entrada:
        * cantDT: Cantidad de MG por dosis recetada
        * itervHT: Intervalo de horas de la dosis
        * diasT: Dias del tratamiento
        * concentracion: Concentracion del medicamento (MG)
        * medicamentoT: Cantidad de pastillas del medicamento
      - Variables de salida:
        * resultadoT1: Cantidad de dosis requerida
        * resultadoT2: Unidades requeridas a comprar
        * resultadoT3: Unidades redondeadas
  */

  function calcularCantidadTabletas() {
    var cantDT=0,itervHT=0,diasT=0,concentracion=0,medicamentoT=0;

    cantDT=parseFloat(document.getElementById('cantDT').value);
    itervHT=parseFloat(document.getElementById('itervHT').value);
    diasT = parseInt(document.getElementById('diasT').value);
    concentracion = parseFloat(document.getElementById('concentracion').value);
    medicamentoT = parseFloat(document.getElementById('medicamentoT').value);

    if(cantDT<0 || itervHT<0 || diasT<0 || concentracion<0 || medicamentoT<0) {
      $('#errorModalCenter').modal('show');//Llama al modal de error
      if(cantDT<0) {
        document.getElementById('cantDT').value=0;
        cantDT=0;
      }

      if(itervHT<0) {
        document.getElementById('itervHT').value=0;
        itervHT=0;
      }

      if(diasT<0) {
        document.getElementById('diasT').value=0;
        diasT=0;
      }

      if(concentracion<0) {
        document.getElementById('concentracion').value=0;
        concentracion=0;
      }

      if(medicamentoT<0) {
        document.getElementById('medicamentoT').value=0;
        medicamentoT=0;
      }
    }

    if(!isNaN(cantDT) && !isNaN(itervHT) && !isNaN(diasT)) {
      if((cantDT>0) && (itervHT>0) && (diasT>0)) {
        var resultadoT1 = itervHT * diasT;
        document.getElementById('resultadoT1').value = resultadoT1.toFixed(2);

        if(!isNaN(concentracion) && !isNaN(medicamentoT)) {
          if(concentracion>0 && medicamentoT>0) {

            var resultadoT2=0,resultadoT3=0;

            if(concentracion === cantDT) {
              resultadoT2 = (resultadoT1/medicamentoT).toFixed(2);
              resultadoT3 = Math.ceil(resultadoT2);
            }
            else if((concentracion<cantDT) || (concentracion>cantDT)) {
              resultadoT2 = (resultadoT1/(medicamentoT*(concentracion/cantDT))).toFixed(2);
              resultadoT3 = Math.ceil(resultadoT2);
            }

            document.getElementById('resultadoT2').value = resultadoT2;
            document.getElementById('resultadoT3').value = resultadoT3;

            if(((concentracion/cantDT)!==0.5) && ((concentracion/cantDT)!==2) && ((concentracion/cantDT)!==1)) {
              $('#errorModalMG').modal('show');//Llama al modal de error
            }
          }
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

  <!-- Modal Dosis Jarabe -->
  <div class="modal fade" id="errorModalMG" tabindex="-1" role="dialog" aria-labelledby="errorModalMGTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-warning" id="errorModalMGTitle"><i class="fas fa-exclamation-triangle"></i>&nbsp;Advertencia</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">Existe una inconsistencia entre el medicamento deseado y el recomendado</h4>
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
    C&Aacute;LCULO DE DOSIFICACIONES
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
            Unidades Requeridas a Comprar:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ2" class="form-control" disabled>
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
            Unidades Redondeadas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ3" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Cantidad de ML Requeridos:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoJ1" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <button type="reset" tabindex="5" class="btn btn-success">
              Borrado dosificacion de jarabes
            </button>
          </td>

          <td align="center">
            <a href="#dosificaciones-tabletas" tabindex="6" class="btn btn-info">
              Ir a dosificaciones para tabletas
            </a>
          </td>

          <td align="left">
            <a href="#ver-manual" tabindex="15" class="btn btn-primary">
              Ver instrucciones
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
    
      <tbody align="right">
        <tr>
          <td>
            Cantidad MG por Dosis Recetada:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="7" placeholder="0,00" id="cantDT" name="cantDT" class="form-control bg-warning" onblur="calcularCantidadTabletas()" onkeypress="FocusChange();">
          </td>

          <td>
            Concentracion del medicamento (MG):
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="10" placeholder="0,00" id="concentracion" name="concentracion" class="form-control bg-warning" onblur="calcularCantidadTabletas()" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td>
            Cuantas veces al dia la Dosis:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="8" placeholder="0,00" id="itervHT" name="itervHT" class="form-control bg-warning" onblur="calcularCantidadTabletas()" onkeypress="FocusChange();">
          </td>

          <td>
            Cantidad de Pastillas del medicamento:
          </td>

          <td>
            <input type="number" step="0.01" min="0" tabindex="11" placeholder="0,00" id="medicamentoT" name="medicamentoT" class="form-control bg-warning" onblur="calcularCantidadTabletas()" onkeypress="FocusChange();">
          </td>
        </tr>

        <tr>
          <td>
            Cantidad de dias del Tratamiento:
          </td>

          <td>
            <input type="number" min="0" tabindex="9" placeholder="0" id="diasT" name="diasT" class="form-control bg-warning" onblur="calcularCantidadTabletas()" onkeypress="FocusChange();">
          </td>

          <td>
            Unidades Requeridas a Comprar:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoT2" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td>
            Cantidad de Dosis Requeridas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoT1" class="form-control" disabled>
          </td>

          <td>
            Unidades Redondeadas:
          </td>

          <td>
            <input type="text" placeholder="-" id="resultadoT3" class="form-control" disabled>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <button type="reset" tabindex="12" class="btn btn-success">
              Borrado dosificacion de tabletas
            </button>
          </td>

          <td align="center">
            <a href="#dosificaciones-jarabes" tabindex="13" class="btn btn-info">
              Ir a dosificaciones para jarabes
            </a>
          </td>

          <td align="left">
            <a href="#ver-manual" tabindex="15" class="btn btn-primary">
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
    <a href="#Inicio" tabindex="14" title="Volver al inicio" class="btn btn-primary">
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
          document.getElementById("cantDJ").focus();
        }
        else if(document.activeElement.name == 'cantDT') {
          document.getElementById("itervHT").focus();
        }
        else if(document.activeElement.name == 'itervHT') {
          document.getElementById("diasT").focus();
        }
        else if(document.activeElement.name == 'diasT') {
          document.getElementById("concentracion").focus();
        }
        else if(document.activeElement.name == 'concentracion') {
          document.getElementById("medicamentoT").focus();
        }
        else if(document.activeElement.name == 'medicamentoT') {
          document.getElementById("cantDT").focus();
        }
      }
    }
  </script>
@endsection