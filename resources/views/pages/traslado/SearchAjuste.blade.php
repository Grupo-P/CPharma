@extends('layouts.model')

@section('title')
  Traslado
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
  </style>
@endsection

@section('content')
	<h1 class="h5 text-info">
    <i class="fas fa-people-carry"></i>
    Traslado
  </h1>
	<hr class="row align-items-start col-12">
  <?php	
  	include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";

    $_GET['SEDE'] = FG_Mi_Ubicacion();

    if (isset($_GET['SEDE'])) {     
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';
      echo'
        <form action="/traslado/" method="POST" style="display: inline;">                   
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
        </form>
        <br/><br/>
      ';
  	
  	if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	} 
  	else {
      $InicioCarga = new DateTime("now");

      $conn = FG_Conectar_Smartpharma(FG_Mi_Ubicacion());

      $sql = Q_Lista_Ajuste();

      $result = sqlsrv_query($conn,$sql);

      echo '
        <table class="table table-striped table-bordered col-12 sortable">
          <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Número de ajuste</th>
              <th scope="col">Fecha y hora</td>
              <th scope="col">Operador</td>
              <th scope="col">Acción</td>
            </tr>
          </thead>
          <tbody>
      ';

      $contador = 1;

      while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $numero_ajuste = $row['NumeroAjuste'];
        $fecha_hora = $row['Auditoria_FechaCreacion']->format('d/m/Y H:i A');
        $operador = $row['Auditoria_Usuario'];
        $id_ajuste = $row['Id'];

        $connCPharma = FG_Conectar_CPharma();

        $sql2 = "SELECT * FROM traslados WHERE numero_ajuste = $numero_ajuste";

        $query2 = mysqli_query($connCPharma, $sql2);

        $row2 = mysqli_fetch_assoc($query2);

        if ($row2) {
            continue;
        }

        echo '<tr>';
        echo '<td class="text-center">'.$contador.'</td>';
        echo '<td class="text-center">'.$numero_ajuste.'</td>';
        echo '<td class="text-center">'.$fecha_hora.'</td>';
        echo '<td class="text-center">'.$operador.'</td>';

        echo '<td class="text-center">';
        echo '<a href="/traslado/create?Ajuste='.$numero_ajuste.'&Id='.$id_ajuste.'&SEDE='.FG_Mi_Ubicacion().'" class="btn btn-outline-success">Crear traslado</a>';
        echo '</td>';

        echo '</tr>';

        $contador++;
      }

      echo '</tbody></table>';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	} 
  ?>
@endsection

@section('scriptsFoot')
  <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      ArrJs = eval(<?php echo $ArtJson ?>);
      autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
    </script> 
  <?php
    }
  ?> 
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: Q_Lista_Ajuste
    FUNCION: Armar una lista de todos los ajustes
    RETORNO: Lista de numeros de ajuste
    DESAROLLADO POR: NISA DELGADO
  */
  function Q_Lista_Ajuste() {
    $sql = "
        SELECT
          *
        FROM
            InvAjuste
        WHERE
            InvAjuste.Id IN (
                SELECT
                    InvAjusteDetalle.InvAjusteId
                FROM
                    InvAjusteDetalle
                WHERE
                    InvAjusteDetalle.InvCausaId = (
                        SELECT
                            InvCausa.Id
                        FROM
                            InvCausa
                        WHERE
                            InvCausa.Descripcion = 'Traslado'
                    )
                )
        ORDER BY
            InvAjuste.NumeroAjuste ASC
    ";
    return $sql;
  }
?>
