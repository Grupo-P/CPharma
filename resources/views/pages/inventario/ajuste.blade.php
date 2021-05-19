@extends('layouts.model')

@section('title')
    Inventario
@endsection

@section('content')

    <style>
    table{
        border-collapse: collapse;
        text-align: center;
    }
    thead{
        border: 1px solid black;
        border-radius: 0px;
        background-color: #e3e3e3;
    }
    tbody{
        border: 1px solid black;
        border-radius: 0px;
    }
    td{
        border: 1px solid black;
        border-radius: 0px;
        padding: 10px;
    }
    th{
        border: 1px solid black;
        border-radius: 0px;
        padding: 10px;
    }
    .alinear-der{
        text-align: right;
        padding-right: 20px;
    }
    .alinear-izq{
        text-align: left;
        padding-left: 20px;
    }
    .aumento{
        font-size: 1.2em;
        text-transform: uppercase;
    }
</style>

    <h1 class="h5 text-info">
        <i class="far fa-eye"></i>
        Soporte de ajuste
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/inventario/" method="POST" style="display: inline;">
        @csrf
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>
    <input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">

    <br><br>

    <table>
        <thead>
                <tr>
                    <th scope="row" colspan="4" width="20%">
                    <span class="navbar-brand text-info CP-title-NavBar">
                        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                    </span>
                </th>
                    <th colspan="9">ARTICULOS INVENTARIADOS</th>
                </tr>
            <tr>
                <th scope="row" colspan="1">#</th>
                <th scope="row" colspan="3">Codigo</th>
                <th scope="row" colspan="1">Descripcion</th>
                <th scope="row" colspan="1">Cantidad inventariada</th>
                <th scope="row" colspan="1">Cantidad ajustada</th>
                <th scope="row" colspan="1">Resultado</th>
            </tr>
        </thead>

        <tbody>
            @php $total = 0 @endphp
            @foreach($inventariados as $inventariado)
                @php
                    $sql = "
                        SELECT *
                        FROM InvAjusteDetalle
                        WHERE
                            InvAjusteDetalle.InvAjusteId IN (SELECT InvAjuste.Id FROM InvAjuste WHERE InvAjuste.NumeroAjuste IN ($inventario->numero_ajuste)) AND
                            InvAjusteDetalle.InvArticuloId = $inventariado->id_articulo
                    ";

                    $query = sqlsrv_query($conn, $sql);
                    $conteo = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
                    $cantidad_inventariada = ($inventariado->re_conteo) ? $inventariado->re_conteo - $inventariado->existencia_actual : $inventariado->conteo - $inventariado->existencia_actual;
                    $conteo = ($conteo['InvCausaId'] == 15) ? -$conteo['Cantidad'] : $conteo['Cantidad'];
                    $conteo = intval($conteo);
                    $resultado = $cantidad_inventariada - $conteo;
                    $total = $total + $resultado;
                @endphp

                <tr>
                    <td><b>{{ $loop->iteration }}</b></td>
                    <td colspan="3">{{ $inventariado->codigo_articulo }}</td>
                    <td>{{ $inventariado->descripcion }}</td>
                    <td>{{ $cantidad_inventariada }}</td>
                    <td>{{ $conteo }}</td>
                    <td>{{ $resultado }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" style="border: 0px"></td>
                <td>{{ $total }}</td>
            </tr>
        </tfoot>
    </table>

    <br><br>

    @if($contador === 1)
        <table>
            <thead>
                    <tr>
                        <th scope="row" colspan="4" width="20%">
                        <span class="navbar-brand text-info CP-title-NavBar">
                            <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                        </span>
                    </th>
                        <th colspan="9">ARTICULOS AJUSTADOS</th>
                    </tr>
                <tr>
                    <th scope="row" colspan="1">#</th>
                    <th scope="row" colspan="3">Codigo</th>
                    <th scope="row" colspan="1">Número ajuste</th>
                    <th scope="row" colspan="1">Descripcion</th>
                    <th scope="row" colspan="1">Cantidad inventariada</th>
                    <th scope="row" colspan="1">Cantidad ajustada</th>
                    <th scope="row" colspan="1">Resultado</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $sql = "
                        SELECT
                            (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = InvAjusteDetalle.InvArticuloId) AS codigo_articulo,
                            InvAjusteDetalle.InvArticuloId AS id_articulo,
                            (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = InvAjusteDetalle.InvArticuloId) AS descripcion,
                            CONVERT(INT, (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvArticuloId = InvAjusteDetalle.InvArticuloId AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2))) AS existencia_actual,
                            CONVERT(INT, InvAjusteDetalle.Cantidad) AS conteo,
                            InvAjusteDetalle.InvCausaId AS causa,
                            (SELECT InvAjuste.NumeroAjuste FROM InvAjuste WHERE InvAjuste.Id = InvAjusteDetalle.InvAjusteId) AS numero_ajuste
                        FROM InvAjusteDetalle
                        WHERE InvAjusteId IN (SELECT InvAjuste.Id FROM InvAjuste WHERE InvAjuste.NumeroAjuste IN ($inventario->numero_ajuste))
                    ";

                    $query = sqlsrv_query($conn, $sql);

                    $total = 0;
                @endphp

                @while($ajustados = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
                    @php
                        $conn = FG_Conectar_CPharma();
                        $sql = "SELECT * FROM inventario_detalles WHERE codigo_conteo = '{$inventario->codigo}' AND id_articulo = '{$ajustados['id_articulo']}'";
                        $query2 = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($query2);
                        $cantidad_inventariada = ($row['re_conteo']) ? $row['re_conteo'] - $row['existencia_actual'] : $row['conteo'] - $row['existencia_actual'];
                        $conteo = ($ajustados['causa'] == 15) ? -$ajustados['conteo'] : $ajustados['conteo'];
                        $resultado = $cantidad_inventariada - $conteo;

                        $total = $total + $resultado;
                    @endphp

                    <tr>
                        <td><b>{{ $contador++ }}</b></td>
                        <td colspan="3">{{ $ajustados['codigo_articulo'] }}</td>
                        <td>{{ $ajustados['numero_ajuste'] }}</td>
                        <td>{{ $ajustados['descripcion'] }}</td>
                        <td>{{ $cantidad_inventariada }}</td>
                        <td>{{ $conteo }}</td>
                        <td>{{ $resultado }}</td>
                    </tr>
                @endwhile
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="7" style="border: 0px"></td>
                    <td>{{ $total }}</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <br><br>
@endsection
