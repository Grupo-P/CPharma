@extends('layouts.model')

@section('title')
  Unidad Minima
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
		<i class="fas fa-less-than-equal"></i>
        Unidad minima de expresion
	</h1>

	<hr class="row align-items-start col-12">

    @if(session('unidad'))
        <div class="alert alert-success">
            <h5>¡Unidad mínima creada!</h5>

            <ul>
                <li>Código interno: {{ session('unidad')->codigo_interno }}</li>
                <li>Código barra: {{ session('unidad')->codigo_barra }}</li>
                <li>Descripción: {{ session('unidad')->articulo }}</li>
                <li>Divisor: {{ session('unidad')->divisor }}</li>
                <li>Unidad mínima: {{ session('unidad')->unidad_minima }}</li>
            </ul>
        </div>
    @endif

    <?php
    	include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $sede = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($sede);

        $sql = "
            SELECT
                --Id Articulo
                InvArticulo.Id AS Id,
                --Codigo Interno
                InvArticulo.CodigoArticulo AS CodigoInterno,
                --Codigo de Barra
                (SELECT CodigoBarra
                FROM InvCodigoBarra
                WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
                --Descripcion
                InvArticulo.Descripcion,
                --Existencia (Segun el almacen del filtro)
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                            FROM InvLoteAlmacen
                            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia
                --Tabla principal
            FROM InvArticulo
                --Joins
                LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
                LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
                LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
                LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
                --Condicionales
            WHERE
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                        FROM InvLoteAlmacen
                        WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                        AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0
            --Agrupamientos
            GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion
            --Ordanamiento
            ORDER BY InvArticulo.Id ASC
        ";

        $query = sqlsrv_query($conn, $sql);

        echo '<table style="width:100%;" class="CP-stickyBar">
                    <tr>
                        <td style="width:100%;">
                            <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                              <div class="input-group-prepend">
                                <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                                    aria-hidden="true"></i></span>
                              </div>
                              <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
                            </div>
                        </td>
                    </tr>
                </table>';

        echo '<table class="mt-2 table table-striped table-borderless col-12 sortable" id="myTable">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th scope="col" class="CP-sticky">#</th>';
        echo '<th scope="col" class="CP-sticky">Codigo interno</th>';
        echo '<th scope="col" class="CP-sticky">Codigo barra</th>';
        echo '<th scope="col" class="CP-sticky">Descripcion</th>';
        echo '<th scope="col" class="CP-sticky">Divisor</th>';
        echo '<th scope="col" class="CP-sticky">Unidad</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        echo '<form method="POST" action="/unidad">';

        $contador = 1;
        $i = 0;

        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            if ($contador >= 51) {
                break;
            }

            $codigo_interno = $row['CodigoInterno'];
            $codigo_barra = $row['CodigoBarra'];
            $descripcion = FG_Limpiar_Texto($row['Descripcion']);
            $id = $row['Id'];

            $unidad = compras\Unidad::where('codigo_barra', $codigo_barra)->get();

            if ($unidad->count()) {
                continue;
            }

            $etiqueta = compras\Etiqueta::where('codigo_articulo', $codigo_interno)
                ->where('clasificacion', 'ETIQUETABLE')
                ->orWhere('clasificacion', 'OBLIGATORIO ETIQUETABLE')
                ->get();

            if (!$etiqueta->count()) {
                continue;
            }

            echo '<tr>';

            echo '<td class="text-center" scope="col">'.$contador.'</td>';
            echo '<td class="text-center" scope="col">'.$codigo_interno.'</td>';
            echo '<td class="text-center" scope="col">'.$codigo_barra.'</td>';
            echo '<td class="text-center" scope="col">'.$descripcion.'</td>';

            echo '<td class="text-center" scope="col"><input class="form-control" name="unidades['.$i.'][divisor]"></td>';

            echo '<td class="text-center" scope="col">';
            echo '<select class="form-control" name="unidades['.$i.'][unidad_minima]">';
            echo '<option value="">Seleccione...</option>';
            echo '<option value="METROS (M)">METROS (M)</option>';
            echo '<option value="MILILITROS (ML)">MILILITROS (ML)</option>';
            echo '<option value="MILIGRAMOS (MG)">MILIGRAMOS (MG)</option>';
            echo '<option value="GRAMOS (G)">GRAMOS (G)</option>';
            echo '<option value="KILOGRAMOS (KG)">KILOGRAMOS (KG)</option>';
            echo '<option value="CAPSULA">CAPSULA</option>';
            echo '<option value="TABLETA">TABLETA</option>';
            echo '<option value="UNIDAD">UNIDAD</option>';
            echo '</select>';
            echo '</td>';

            echo '<input type="hidden" name="unidades['.$i.'][id_articulo]" value="'.$id.'">';
            echo '<input type="hidden" name="unidades['.$i.'][codigo_interno]" value="'.$codigo_interno.'">';
            echo '<input type="hidden" name="unidades['.$i.'][codigo_barra]" value="'.$codigo_barra.'">';
            echo '<input type="hidden" name="unidades['.$i.'][articulo]" value="'.$descripcion.'">';
            echo '<input type="hidden" name="_token" value="'.csrf_token().'">';

            echo '</tr>';

            $contador++;
            $i++;
        }

        echo '<tr>';
        echo '<td class="text-center" colspan="6">';
        echo '<button type="submit" class="btn btn-outline-success">Guardar</button>';
        echo '</td>';
        echo '</tr>';

        echo '</form>';
        echo '</tbody>';
        echo '</table>';
    ?>
@endsection
