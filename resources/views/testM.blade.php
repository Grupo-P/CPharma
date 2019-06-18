@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

<style>
  .derecha {text-align:right;}
</style>

@section('content')
  <hr class="row align-items-start col-12">
  <h5 class="text-info">
    <i class="fas fa-money-bill-alt"></i>
    Interfaz de venta
  </h5>
  <hr class="row align-items-start col-12">

  <form name="cuadre" action="" method="POST" class="form-group text-center" enctype="multipart/form-data">
    @csrf
    <table class="table table-borderless table-hover">
      <thead>
        <th scope="col" colspan="2" class="bg-success">Cuadre de conversiones de facturas y de pagos</th>
        <th scope="col" colspan="2"><b>INFORMACI&Oacute;N</b></th>
      </thead>
    
      <tbody>
        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2">* Solo se deben llenar los campos en color amarillo.</td>
        </tr>

        <tr>
          <td class="derecha">
            Total Factura Bs (Con IVA) #1:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* Cuando el cliente aun deba algo se marcara el saldo en color rojo!</td>
        </tr>

        <tr>
          <td class="derecha">
            Total Factura Bs (Con IVA) #2:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* Pendiente con el monto de la tasa, asegurarse de actualizarla cada dia.</td>
        </tr>

        <tr>
          <td class="derecha">
            Total Factura Bs (Con IVA) #3:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* El campo decimales solo acepta numeros entre 0 y 2.</td>
        </tr>

        <tr>
          <td class="derecha">
            Total Facturas Bs (Con IVA):
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* El campo Tasa de Cambio Acepta numeros mayores a Bs 4500<br> y menores a Bs 10000.</td>
        </tr>

        <tr>
          <td class="derecha">
            Total Factura $:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* El campo de Abonos en $ acepta montos mayores o iguales a 0 y menores<br>a 2000$.</td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2">* El bot&oacute;n de borrado no toca el campo de tasa de cambio y decimales.</td>
        </tr>

        <tr>
          <td class="derecha">
            Abono #1 en $:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* Importante en Saldo restante en $ NO se le debe cobrar al cliente,<br>sino lo reflejado en el recuadro final.</td>
        </tr>

        <tr>
          <td class="derecha">
            Abono #2 en $:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* Todos los pagos del cliente se debe relacionar en los abonos y buscar<br>el saldo quede en 0 o cercano a 0.</td>
        </tr>

        <tr>
          <td class="derecha">
            Conversion Abono #1 en Bs:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* La tolerancia del vuelto lo que valida es el monto minimo para generar<br>un vuelto al cliente.</td>
        </tr>

        <tr>
          <td class="derecha">
            Total Abonos Bs:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td colspan="2">* El campo Fecha Tasa debe ser de hoy de lo contrario la hoja no permite<br> ser usada.</td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
          <td colspan="2"><b>CONFIGURACI&Oacute;N</b></td>
        </tr>

        <tr>
          <td class="derecha">
            Saldo Restante en Bs:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td class="derecha">
            Tasa de Cambio:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
        </tr>

        <tr>
          <td class="derecha">
            Saldo Restante en $:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
          <td class="derecha">
            Fecha Tasa de Cambio:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
        </tr>

        <tr>
          <td colspan="2">&nbsp;</td>
          <td class="derecha">
            Cantidad Decimales:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <input type="text" class="form-control" disabled>
          </td>
          <td class="derecha">
            Tolerancia Vuelto en Bs:
          </td>
          <td>
            <input type="text" class="form-control">
          </td>
        </tr>
      </tbody>
    </table>

    <button type="submit" class="btn btn-danger">Borrar y empezar de nuevo</button>
  </form>
@endsection