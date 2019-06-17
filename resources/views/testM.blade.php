@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

@section('content')
<hr class="row align-items-start col-12">

  <h1 class="h5 text-info">
    <i class="fas fa-money-bill-alt"></i>
    Interfaz de venta
  </h1>

  <hr class="row align-items-start col-12">

  
  <table class="table table-striped table-bordered col-12 sortable">

    <thead class="thead-dark">
      <tr>
          <th scope="col" colspan="2">Cuadre</th>
          <th scope="col" colspan="2">Informaci&oacute;n</th>
      </tr>

    </thead>

    <tbody>

      <tr>
        <td scope="col">
          <label for="">Total Factura Bs (Con IVA) #1:</label>
          </br></br>
        </td>
        <td>
          <label for="">Total</label>
        </td>

        <td>
          <label for="">vol1</label>
        </td>
        <td>
          <label for="">vol2</label>
        </td>
      </tr>

      <tr>
        <td>
          <label for="">Total Factura Bs () #1:</label>  
        </td>
        <td>
          <label for="">a Bs () #1:</label>  
        </td>

        <td>
          <label for="">vol1</label>
        </td>
        <td>
          <label for="">vol2</label>
        </td>

      </tr>

    </tbody>
  </table>


   </br></br></br></br></br>


  <table class="table table-striped table-bordered col-6 sortable">
    <thead class="thead-dark">
      <tr>
          <th scope="col">Cuadre de Conversiones de Facturas y Pagos</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <label for="">Total Factura Bs (Con IVA) #1:</label>
          <input type="text" style="width:50%;">
        </td>
      </tr>
      <tr>
        <td>
          <label for="">Total Factura Bs (Con IVA) #2:</label>
          <input type="text" style="width:50%;">
        </td>
      </tr>
      <tr>
        <td>
          <label for="">Total Factura Bs (Con IVA) #3:</label>
          <input type="text" style="width:50%;">
        </td>
      </tr>
    </tbody>
  </table>

  <table class="table table-striped table-bordered col-6 sortable">
    <thead class="thead-dark">
      <tr>
          <th scope="col">Informaci&oacute;n</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>* Solo se deben llenar los campos en color amarillo.</td>
      </tr>
      <tr>
        <td>* Cuando el cliente aun deba algo se marcara el saldo en color rojo!</td>
      </tr>
      <tr>
        <td>* Pendiente con el monto de la tasa, asegurarse de actualizarla cada dia.</td>
      </tr>
      <tr>
        <td>* El campo decimales solo acepta numeros entre 0 y 2.</td>
      </tr>
      <tr>
        <td>* El campo Tasa de Cambio Acepta numeros mayores a Bs 4500 y menores a Bs 10000.</td>
      </tr>
      <tr>
        <td>* El campo de Abonos en $ acepta montos mayores o iguales a 0 y menores a 2000$.</td>
      </tr>
      <tr>
        <td>* El boton de borrado no toca el campo de tasa de cambio y decimales.</td>
      </tr>
      <tr>
        <td>* Importante en Saldo restante en $ NO se le debe cobrar al cliente, sino lo reflejado en el recuadro final.</td>
      </tr>
      <tr>
        <td>* Todos los pagos del cliente se debe relacionar en los abonos y buscar el saldo quede en 0 o cercano a 0.</td>
      </tr>
      <tr>
        <td>* La tolerancia del vuelto lo que valida es el monto minimo para generar un vuelto al cliente.</td>
      </tr>
      <tr>
        <td>* El campo Fecha Tasa debe ser de hoy de lo contrario la hoja no permite ser usada.</td>
      </tr>
    </tbody>
  </table>
@endsection