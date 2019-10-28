@extends('layouts.model')

@section('title')
  Reporte
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
    background-color: #f1f1f1;
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
  .barrido{
    text-decoration: none;
    transition: width 1s, height 1s, transform 1s;
  }
  .barrido:hover{
    text-decoration: none;
    transition: width 1s, height 1s, transform 1s;
    transform: translate(20px,0px);
  }
  .alerta{
    color: red;
    text-transform: uppercase;
  }
  </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Productos en Caida
  </h1>
  <hr class="row align-items-start col-12">

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $InicioCarga = new DateTime("now");

  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  R14_Productos_EnCaida($_GET['SEDE']);
  FG_Guardar_Auditoria('CONSULTAR','REPORTE','Productos en Caida');

  $FinCarga = new DateTime("now");
  $IntervalCarga = $InicioCarga->diff($FinCarga);
  echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
?>
@endsection

<?php 
  /**********************************************************************************/
  /*
    TITULO: ReporteActivacionProveedores
    FUNCION: Armar el reporte de activacion de proveedores
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R14_Productos_EnCaida($SedeConnection) {

    $connCPharma = FG_Conectar_CPharma();

  /*INCIO PARA CALCULOS CON DIAS EN CERO*/
    $sql = MySQL_Rango_Dias_Cero();
    $result = mysqli_query($connCPharma,$sql);
    $row = $result->fetch_assoc();
    $DC_FInicialImp = date("d-m-Y", strtotime($row['Inicio']));
    $DC_FFinalImp = date("d-m-Y", strtotime($row['Fin']));
 /*FIN PARA CALCULOS CON DIAS EN CERO*/

    $FFinal = date("Y-m-d");
    $FInicial = date("Y-m-d",strtotime($FFinal."-10 days"));

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $sql = "SELECT * FROM productos_caida ORDER BY CAST(UnidadesVendidas AS INT) DESC";
    $result = mysqli_query($connCPharma,$sql);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
    </div>
    <br/>
    ';
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
    echo'<h6 align="center">La data recolectada para el calculo <span style="color:red;">(Real)</span> va desde el <span style="color:red;">'.$DC_FInicialImp.'</span> al <span style="color:red;">'.$DC_FFinalImp.'</span> </h6>';
    echo'<h6 align="center">Los productos de este reporte cumplen con los siguientes criterios</h6>';

    echo '
    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Compra</th>
          <th scope="col">Venta</th>
          <th scope="col">Existencia</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td scope="col">
              <ul>
                <li><span class="alerta">NO</span> tener <span class="alerta">compras</span> en el <span class="alerta">rango</span></li>
              </ul>
          </td>
          <td scope="col">
              <ul>
                <li>Tener <span class="alerta">venta al menos 5 dias</span> en el rango</li>
              </ul>
          </td>
          <td scope="col">
              <ul>
                <li>Tener <span class="alerta">existencia</span> el dia de <span class="alerta">hoy</span></li>
                <li>Dias restantes <span class="alerta">menor a 11</span></li>
                <li>Tener existencia <span class="alerta">TODOS</span> los dias del rango</li>
                <li>Que la <span class="alerta">existencia decrezca</span> al menos 5 dias</li>
              </ul>
          </td>
        </tr>
      </tbody
    </table>';
  
    echo '
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>      
            <th scope="col" class="CP-sticky">Codigo</th>
              <th scope="col" class="CP-sticky">Descripcion</td>
              <th scope="col" class="CP-sticky">Precio</br>(Con IVA) '.SigVe.'</td>
              <th scope="col" class="CP-sticky">Existencia</td>
              <th scope="col" class="CP-sticky">Dia 10</td>
              <th scope="col" class="CP-sticky">Dia 9</td>
              <th scope="col" class="CP-sticky">Dia 8</td>
              <th scope="col" class="CP-sticky">Dia 7</td>
              <th scope="col" class="CP-sticky">Dia 6</td>
              <th scope="col" class="CP-sticky">Dia 5</td>
              <th scope="col" class="CP-sticky">Dia 4</td>
              <th scope="col" class="CP-sticky">Dia 3</td>
              <th scope="col" class="CP-sticky">Dia 2</td>
              <th scope="col" class="CP-sticky">Dia 1</td>
              <th scope="col" class="CP-sticky">Unidades Vendidas</td>
              <th scope="col" class="CP-sticky">Dias Restantes</td> 
              <th scope="col" class="CP-sticky bg-danger text-white">Dias restantes (Real)</th>                  
          </tr>
        </thead>
        <tbody>
      ';

    $contador = 1;
    while($row = mysqli_fetch_assoc($result)){
      $IdArticulo = $row['IdArticulo'];
      $CodigoArticulo = $row['CodigoArticulo'];
      $Descripcion = FG_Limpiar_Texto($row['Descripcion']);
      $Precio = $row['Precio'];
      $Existencia = $row['Existencia'];
      $Dia10 = $row['Dia10'];
      $Dia9 = $row['Dia9'];
      $Dia8 = $row['Dia8'];
      $Dia7 = $row['Dia7'];
      $Dia6 = $row['Dia6'];
      $Dia5 = $row['Dia5'];
      $Dia4 = $row['Dia4'];
      $Dia3 = $row['Dia3'];
      $Dia2 = $row['Dia2'];
      $Dia1 = $row['Dia1'];
      $UnidadesVendidas = $row['UnidadesVendidas'];
      $DiasRestantes = $row['DiasRestantes'];

      $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$FInicial,$FFinal);
      $result2 = mysqli_query($connCPharma,$sql2);
      $row2 = $result2->fetch_assoc();
      $RangoDiasQuiebre = $row2['Cuenta'];
      $VentaDiariaQuiebre = FG_Venta_Diaria($UnidadesVendidas,$RangoDiasQuiebre);
      $DiasRestantesQuiebre = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre);

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td>'.$row["CodigoArticulo"].'</td>';

      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';

      echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
      echo '<td align="center">'.intval($Existencia).'</td>';
      echo '<td align="center">'.intval($Dia10).'</td>';
      echo '<td align="center">'.intval($Dia9).'</td>';
      echo '<td align="center">'.intval($Dia8).'</td>';
      echo '<td align="center">'.intval($Dia7).'</td>';
      echo '<td align="center">'.intval($Dia6).'</td>';
      echo '<td align="center">'.intval($Dia5).'</td>';
      echo '<td align="center">'.intval($Dia4).'</td>';
      echo '<td align="center">'.intval($Dia3).'</td>';
      echo '<td align="center">'.intval($Dia2).'</td>';
      echo '<td align="center">'.intval($Dia1).'</td>';

      echo 
      '<td align="center" class="CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinal.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesVendidas).
      '</a>
      </td>';

      echo '<td align="center">'.round($DiasRestantes,2).'</td>';
      echo '<td align="center" class="bg-danger text-white">'.round($DiasRestantesQuiebre,2).'</td>';
      echo '</tr>';
    $contador++;
    }
    echo '
        </tbody>
    </table>';
    mysqli_close($connCPharma);
  }
?>