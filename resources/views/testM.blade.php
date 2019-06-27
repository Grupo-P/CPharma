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

<style>
  form table thead + tbody tr td input {text-align:center;}
</style>

<script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
<script>
  function calcularCantidadJarabes() {
    var cantDJ=0,itervHJ=0,diasJ=0,resultadoJ1=0;

    cantDJ=parseFloat(document.getElementById('cantDJ').value);
    itervHJ=parseFloat(document.getElementById('itervHJ').value);
    diasJ = parseInt(document.getElementById('diasJ').value);

    if(cantDJ<0 || itervHJ<0 || diasJ<0) {
      $('#errorModalCenter').modal('show');
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
      if((cantDJ>0) && (itervHJ>0) && (diasJ>0)) {
        resultadoJ1 = cantDJ * (24/itervHJ) * diasJ;
        document.getElementById('resultadoJ1').value = resultadoJ1.toFixed(2);

        var medicamentoJ = parseFloat(document.getElementById('medicamentoJ').value);

        if(!isNaN(medicamentoJ)) {
          if(medicamentoJ<0) {
            $('#errorModalCenter').modal('show');
            document.getElementById('medicamentoJ').value=0;
            medicamentoJ=0;
          }
          else if(medicamentoJ>0) {
            var resultadoJ2 = (resultadoJ1/medicamentoJ).toFixed(2);
            var resultadoJ3 = Math.ceil(resultadoJ2);

            document.getElementById('resultadoJ2').value = resultadoJ2;
            document.getElementById('resultadoJ3').value = resultadoJ3;

            if(24%itervHJ!==0) {
              $('#exampleModalCenter').modal('show');
            }
          }
        }
      }
    }
  }

  function calcularCantidadTabletas() {
    var cantDT=0,itervHT=0,diasT=0,resultadoT1=0;

    cantDT=parseFloat(document.getElementById('cantDT').value);
    itervHT=parseFloat(document.getElementById('itervHT').value);
    diasT = parseInt(document.getElementById('diasT').value);

    if(cantDT<0 || itervHT<0 || diasT<0) {
      $('#errorModalCenter').modal('show');
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
    }

    if(!isNaN(cantDT) && !isNaN(itervHT) && !isNaN(diasT)) {
      if((cantDT>0) && (itervHT>0) && (diasT>0)) {
        resultadoT1 = cantDT * (24/itervHT) * diasT;
        document.getElementById('resultadoT1').value = resultadoT1.toFixed(2);

        var medicamentoJ = parseFloat(document.getElementById('medicamentoJ').value);

        if(!isNaN(medicamentoJ)) {
          if(medicamentoJ<0) {
            $('#errorModalCenter').modal('show');
            document.getElementById('medicamentoJ').value=0;
            medicamentoJ=0;
          }
          else if(medicamentoJ>0) {
            var resultadoJ2 = (resultadoJ1/medicamentoJ).toFixed(2);
            var resultadoJ3 = Math.ceil(resultadoJ2);

            document.getElementById('resultadoJ2').value = resultadoJ2;
            document.getElementById('resultadoJ3').value = resultadoJ3;

            if(24%itervHJ!==0) {
              $('#exampleModalCenter').modal('show');
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
          <h4 class="h6">El valor colocado en <b>Cada cuantas horas la dosis</b> no es un n&uacute;mero divisible entre 24 hrs.</h4>
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
          <h4 class="h6"><b>No se admiten</b> valores negativos.</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

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
          
          <td>
            <button type="reset" tabindex="5" class="btn btn-success">
              Borrado dosificacion de jarabes
            </button>
          </td>

          <td>
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

          <td align="left" colspan="2">
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