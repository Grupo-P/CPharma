@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

<script>
  function calcularFactura() {
    var fac1=0,
        fac2=0,
        fac3=0,
        totalFacBs=0,
        totalFacDs=0,
        tasa=0,
        decimales=0;

    fac1=parseFloat(document.getElementById('fac1').value);
    fac2=parseFloat(document.getElementById('fac2').value);
    fac3=parseFloat(document.getElementById('fac3').value);
    tasa = parseFloat(document.getElementById('tasa').value);
    decimales = parseInt(document.getElementById('decimales').value);

    if(fac1<0 || fac2<0 || fac3<0) {
      alert("No se admiten valores negativos.");
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
  }
</script>

@section('content')
  <hr class="row align-items-start col-12">
  <h5 class="text-info">
    <i class="fas fa-money-bill-alt"></i>
    Interfaz de venta
  </h5>
  <hr class="row align-items-start col-12">

  <form name="cuadre" class="form-group">
    <table class="table table-bordered table-hover">
      <thead>
        <th scope="col" colspan="2" class="text-center bg-success">
          <b>Cuadre de Conversiones de Facturas y Pagos</b></th>
        <th scope="col" colspan="2"><b>INFORMACI&Oacute;N</b></th>
      </thead>
    
      <tbody>
        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2">* Solo se deben llenar los campos en color amarillo.</td>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura Bs (Con IVA) #1:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="fac1" class="form-control text-center bg-warning" autofocus onblur="calcularFactura();">
          </td>
          <td colspan="2">* Cuando el cliente aun deba algo se marcara el saldo en color rojo!</td>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura Bs (Con IVA) #2:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="fac2" class="form-control text-center bg-warning" onblur="calcularFactura();">
          </td>
          <td colspan="2">* Pendiente con el monto de la tasa, asegurarse de actualizarla cada dia.</td>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura Bs (Con IVA) #3:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="fac3" class="form-control text-center bg-warning" onblur="calcularFactura();">
          </td>
          <td colspan="2">* El campo decimales solo acepta numeros entre 0 y 2.</td>
        </tr>

        <tr>
          <td class="text-right">
            Total Facturas Bs (Con IVA):
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalFacBs" class="form-control text-center" disabled>
          </td>
          <td colspan="2">* El campo Tasa de Cambio Acepta numeros mayores a Bs 4500<br> y menores a Bs 10000.</td>
        </tr>

        <tr>
          <td class="text-right">
            Total Factura $:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="totalFacDs" class="form-control text-center" disabled>
          </td>
          <td colspan="2">* El campo de Abonos en $ acepta montos mayores o iguales a 0 y menores<br>a 2000$.</td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2">* El bot&oacute;n de borrado no toca el campo de tasa de cambio y decimales.</td>
        </tr>

        <tr>
          <td class="text-right">
            Abono #1 en $:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center bg-warning" >
          </td>
          <td colspan="2">* Importante en Saldo restante en $ NO se le debe cobrar al cliente,<br>sino lo reflejado en el recuadro final.</td>
        </tr>

        <tr>
          <td class="text-right">
            Abono #2 en Bs:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center bg-warning">
          </td>
          <td colspan="2">* Todos los pagos del cliente se debe relacionar en los abonos y buscar<br>el saldo quede en 0 o cercano a 0.</td>
        </tr>

        <tr>
          <td class="text-right">
            Conversion Abono #1 en Bs:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center" disabled>
          </td>
          <td colspan="2">* La tolerancia del vuelto lo que valida es el monto minimo para generar<br>un vuelto al cliente.</td>
        </tr>

        <tr>
          <td class="text-right">
            Total Abonos Bs:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center" disabled>
          </td>
          <td colspan="2">* El campo Fecha Tasa debe ser de hoy de lo contrario la hoja no permite<br> ser usada.</td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2"><b>CONFIGURACI&Oacute;N</b></td>
        </tr>

        <tr>
          <td class="text-right">
            Saldo Restante en Bs:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center" disabled>
          </td>
          <td class="text-right">
            Tasa de Cambio:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" value="7200" id="tasa" class="form-control text-center bg-warning">
          </td>
        </tr>

        <tr>
          <td class="text-right">
            Saldo Restante en $:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center" disabled>
          </td>
          <td class="text-right">
            Fecha Tasa de Cambio:
          </td>
          <td>
            <input type="date" id="fecha" class="form-control text-center bg-warning">
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
          <td class="text-right">
            Cantidad Decimales:
          </td>
          <td>
            <input type="number" min="0" max="2" placeholder="0" value="2" id="decimales" class="form-control text-center bg-warning">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input type="number" step="0.01" min="0" placeholder="0,00" class="form-control text-center" disabled>
          </td>
          <td class="text-right">
            Tolerancia Vuelto en Bs:
          </td>
          <td>
            <input type="number" step="0.01" min="0" placeholder="0,00" id="tolerancia" class="form-control text-center bg-warning">
          </td>
        </tr>
      </tbody>
    </table>

    <div class="text-center">
      <button type="reset" class="btn btn-danger">
        Borrar y empezar de nuevo
      </button>
    </div>
  </form>
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
@endsection