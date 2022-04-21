@extends('layouts.etiquetas')

@section('title')
    Traslado
@endsection

@section('content')
<style>
    table{
        display: inline;
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
        width: 12cm;
    }
    th{
        border: 1px solid black;
        border-radius: 0px;
    }
    .alinear-der{
        text-align: right;
        padding-right: 10px;
    }
    .alinear-izq{
        text-align: left;
        padding-left: 10px;
    }
    .aumento{
        font-size: 1em;
        text-transform: uppercase;
    }
    .aumentoT{
        font-size: 1.2em;
        text-transform: uppercase;
    }
    .espacioT{
        padding-top: 5px;
        padding-bottom: 5px;
    }
    /*@page {size: landscape}*/

    @media print{
        .saltoDePagina{
            display:block;
            page-break-before:always;
        }
    }

    .border-none {
        border: 1px solid white !important;
    }
</style>

<?php
    include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $Ajuste = $_GET['Ajuste'];
    $connCPharma = FG_Conectar_CPharma();
    $sql = "SELECT * FROM traslados WHERE numero_ajuste='$Ajuste'";
    $result = mysqli_query($connCPharma,$sql);
    $row = $result->fetch_assoc();
    mysqli_close($connCPharma);

    $numero_ajuste = $row['numero_ajuste'];
    $fecha_ajuste = $row['fecha_ajuste'];
    $fecha_embalaje = $row['fecha_embalaje'];
    $sede_emisora = $row['sede_emisora'];
    $sede_destino = $row['sede_destino'];
    $operador_envio = $row['operador_envio'];
    $operador_embalaje = $row['operador_embalaje'];
    $bultos = $row['bultos'];
    $bultos_fragiles = $row['bultos_fragiles'];
    $bultos_refrigerados = $row['bultos_refrigerados'];
?>

    <h1 class="h5 text-info" style="display: inline;">
        <i class="fas fa-tag"></i>
        Guia de envio y etiquetas
    </h1>
    <form action="/traslado/" method="POST" style="display: inline; margin-left: 50px;">
        @csrf
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>
    <input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">

    <hr class="row align-items-start col-12">

    <table>
        <thead>
            <tr>
                    <th scope="row" colspan="3">
                        <span class="navbar-brand text-info CP-title-NavBar">
                            <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                        </span>
                    </th>
                    <th scope="row" colspan="3" class="aumento">Guia de Envio</th>
            </tr>
    </thead>
        <tbody>
            <tr>
                <td colspan="3" class="alinear-der"># de Ajuste:</td>
                <td colspan="3" class="alinear-izq">{{$numero_ajuste}}</td>
            </tr>
            <tr>
                <td colspan="3" class="alinear-der">Fecha de Ajuste:</td>
                <td colspan="3" class="alinear-izq">{{$fecha_ajuste}}</td>
            </tr>
            <tr>
                <td colspan="3" class="alinear-der">Fecha de Embalaje:</td>
                <td colspan="3" class="alinear-izq">{{$fecha_embalaje}}</td>
            </tr>
            <tr>
                <td colspan="3" class="alinear-der">Sede Emisora:</td>
                <td colspan="3" class="alinear-izq">{{$sede_emisora}}</td>
            </tr>
            <tr>
                <td colspan="3" class="alinear-der">Sede Destino:</td>
                <td colspan="3" class="alinear-izq">{{$sede_destino}}</td>
            </tr>
            <tr>
                <td colspan="3" class="alinear-der">Operador emisor de la guia:</td>
                <td colspan="3" class="alinear-izq">{{$operador_envio}}</td>
            </tr>
            <tr>
                <td colspan="3" class="alinear-der">Cantida total de bultos:</td>
                <td colspan="3" class="alinear-izq">
                    Bultos normales: {{$bultos}}<br/>
                    Bultos refrigerados: {{$bultos_refrigerados}}<br/>
                    Bultos fragiles: {{$bultos_fragiles}}
                </td>
            </tr>
            <tr>
                <td colspan="2" class="alinear-izq">
                    <br/><br/><br/>
                    <span>______________________________</span><br/>
                    <span>Preparado por</span><br/>
                    <span>Nombre:</span><br/>
                    <span>Apellido:</span><br/>
                    <span>Fecha:</span><br/>
                    <span>Hora:</span><br/>
                </td>
                <td colspan="2" class="alinear-izq">
                    <br/><br/><br/>
                    <span>______________________________</span><br/>
                    <span>Quien Recibe</span><br/>
                    <span>Nombre:</span><br/>
                    <span>Apellido:</span><br/>
                    <span>Fecha:</span><br/>
                    <span>Hora:</span><br/>
                </td>
                <td colspan="2" class="alinear-izq">
                    <br/><br/>
                    <span>______________________________</span><br/>
                    <span>Quien Entrega</span><br/>
                    <span>Nombre:</span><br/>
                    <span>Apellido:</span><br/>
                    <span>Fecha:</span><br/>
                    <span>Hora:</span><br/>
                </td>
            </tr>
            <thead>
                <tr>
                    <th colspan="6">
                            Recuerde entregar el soporte de traslado junto con la mercancia
                    </th>
                </tr>
            </thead>
        </tbody>
    </table>
    <br><br>
    <div class="saltoDePagina"></div>

    <?php
        Etiquetas_Traslado($numero_ajuste,$fecha_ajuste,$sede_emisora,$sede_destino,$bultos,$fecha_embalaje,$operador_embalaje);
    ?>
@endsection

<?php

/**********************************************************************************/
  /*
    TITULO: Etiquetas_Traslado
    FUNCION: Imprimir las etiquetas para el tarslado
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA
  */
 function Etiquetas_Traslado($numero_ajuste,$fecha_ajuste,$sede_emisora,$sede_destino,$bultos,$fecha_embalaje,$operador_embalaje){

    $Contador = 1;
    $CuentaEtiqueta = 0;

    while($Contador<=$bultos){
        echo'
        <table class="border-none" style="display: inline;">
            <tr class="border-none">
                <td class="border-none">
                    <table class="table-borderless" style="display: inline;">
                        <thead>
                        <tr>
                                <th scope="row" class="espacioT">
                                    <span class="navbar-brand text-info CP-title-NavBar">
                                        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                                    </span>
                                    <span class="aumentoT espacioT">Bulto de traslados internos</span>
                                </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td class="aumento espacioT">Soporte #'.$numero_ajuste.' del '.$fecha_ajuste.'</td>
                        </tr>
                        <tr>
                        <td class="aumentoT espacioT"><strong>De: '.$sede_emisora.'</strong></td>
                        </tr>
                        <tr>
                        <td class="aumentoT espacioT"><strong>Para: '.$sede_destino.'</strong></td>
                        </tr>
                        <tr>
                        <td class="alinear-der aumento espacioT">Preparado el '.$fecha_embalaje.' por '.$operador_embalaje.'</td>
                        </tr>
                        <tr>
                        <td class="aumento espacioT">Favor no aplilar demasiadas cajas y contemplar que el contenido es fragil para el momento de su movilizacion</td>
                        </tr>
                        <tr>
                        <td class="alinear-der aumento espacioT"><strong>Bulto '.$Contador.' de '.$bultos.'</strong></td>
                        </tr>
                    </tbody>
                    </table>
                </td>
        ';

        $Contador++;

        if ($Contador<=$bultos) {

            echo '
                <td class="border-none">
                    <table class="table-borderless" style="display: inline;">
                        <thead>
                        <tr>
                                <th scope="row" class="espacioT">
                                    <span class="navbar-brand text-info CP-title-NavBar">
                                        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                                    </span>
                                    <span class="aumentoT espacioT">Bulto de traslados internos</span>
                                </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td class="aumento espacioT">Soporte #'.$numero_ajuste.' del '.$fecha_ajuste.'</td>
                        </tr>
                        <tr>
                        <td class="aumentoT espacioT"><strong>De: '.$sede_emisora.'</strong></td>
                        </tr>
                        <tr>
                        <td class="aumentoT espacioT"><strong>Para: '.$sede_destino.'</strong></td>
                        </tr>
                        <tr>
                        <td class="alinear-der aumento espacioT">Preparado el '.$fecha_embalaje.' por '.$operador_embalaje.'</td>
                        </tr>
                        <tr>
                        <td class="aumento espacioT">Favor no aplilar demasiadas cajas y contemplar que el contenido es fragil para el momento de su movilizacion</td>
                        </tr>
                        <tr>
                        <td class="alinear-der aumento espacioT"><strong>Bulto '.$Contador.' de '.$bultos.'</strong></td>
                        </tr>
                    </tbody>
                    </table>
                </td>
            ';
        }

        echo '
        </tr>
        </table>
        <br/><br/>
        ';

        $Contador++;
        $CuentaEtiqueta++;

        if($CuentaEtiqueta == 4){
            echo'<br/>';
            $CuentaEtiqueta=0;
        }
    }
 }
?>
