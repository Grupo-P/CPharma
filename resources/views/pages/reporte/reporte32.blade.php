@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Seguimiento de Tienda
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
        ?>

        <form action="/reporte32/" style="display: inline;">
            @csrf
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
            <button type="submit" name="Fecha" value="AYER" role="button" class="btn btn-outline-info btn-md">Ayer</button>
        </form>
        <form action="/reporte32/" style="display: inline;">
            @csrf
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
            <button type="submit" name="Fecha" value="HOY" role="button" class="btn btn-outline-success btn-md">Hoy</button>
        </form>
        <form action="/reporte32/" style="display: inline;">
            @csrf
            <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
            <input type="date" name="date_custom" class="btn btn-outline-dark btn-md">
            <button type="submit" name="Fecha" value="CUSTOM" role="button" class="btn btn-outline-dark btn-md">Filtrar</button>
        </form>

        <?php
        //TODO Borrar y dejar el comentario
        $hoy = '2021-03-15';
        //$hoy = date('Y-m-d');
        if(isset($_GET['Fecha'])){
            if($_GET['Fecha']=='AYER'){
                $fecha = date("Y-m-d",strtotime($hoy."- 1 days"));
                echo '<label class="h5 text-dark" style="margin-left:10%;" align="center"> Fecha: '.date('d-m-Y',strtotime($fecha)).'</label>';
            }
            else if($_GET['Fecha']=='HOY'){
                $fecha = $hoy;
                echo '<label class="h5 text-dark" style="margin-left:10%;" align="center"> Fecha: '.date('d-m-Y',strtotime($fecha)).'</label>';
            }
            else if($_GET['Fecha']=='CUSTOM'){
                $fecha = $_GET['date_custom'];
                echo '<label class="h5 text-dark" style="margin-left:10%;" align="center"> Fecha: '.date('d-m-Y',strtotime($fecha)).'</label>';
            }
        }else{
            $fecha = $hoy;
        }

            $conn = FG_Conectar_Smartpharma($_GET['SEDE']);
            $FFinal = date("Y-m-d",strtotime($fecha."+ 1 days"));

            $sqlDataGRVen = R32Q_data_grafico($fecha,$FFinal);
            $resultDataGRVen = $resulValidator = sqlsrv_query($conn,$sqlDataGRVen);
            $MontoArray = $UnidadesArray = $TransaccionesArray = $HoraArray = array();

            //echo"<pre>";
			//print_r($sqlDataGRVen);
			//echo"</pre>";

            $rowValidaror = sqlsrv_fetch_array($resulValidator, SQLSRV_FETCH_ASSOC);
            if( isset($rowValidaror['Monto']) && $rowValidaror['Monto']!=""){

            //TODO
            /*
                while($rowDataGRVen = sqlsrv_fetch_array($resultDataGRVen, SQLSRV_FETCH_ASSOC)) {
                    array_push($MontoArray,$rowDataGRVen['Monto']);
                    array_push($UnidadesArray,$rowDataGRVen['Unidades']);
                    array_push($TransaccionesArray,$rowDataGRVen['Transacciones']);
                    array_push($HoraArray,$rowDataGRVen['Hora']);
                }

                echo '<hr class="row align-items-start col-12">';
                echo '<h1 class="h5 text-dark" align="center">Ventas por Hora</h1>';

                echo'
                <div class="col-12">
                    <div class="col-6" style="float:left;">
                        <canvas id="VentasxHora"></canvas>
                    </div>
                    <div class="col-6" style="float:left;">
                        <canvas id="TransaccionesxHora"></canvas>
                    </div>
                </div>
                ';
                echo'<div style="clear:both"></div>';
                echo'
                <div class="col-12" style="margin-top:50px;">
                    <div class="col-6" style="float:left;">
                        <canvas id="UnidadesxHora"></canvas>
                    </div>
                    <div class="col-6" style="float:left;">
                        <canvas id="MixtoxHora"></canvas>
                    </div>
                </div>
                ';
            */
                echo'<div style="clear:both"></div>';

                R32_Seguimiento_Tienda($_GET['SEDE'],$fecha);
            }else{
                echo '<hr class="row align-items-start col-12">';
                echo '<h1 class="h5 text-danger" align="center">El dia '.date('d-m-Y',strtotime($fecha)).' no tiene ventas</h1>';
            }

            //FG_Guardar_Auditoria('CONSULTAR','REPORTE','Seguimiento de Tienda');
            $FinCarga = new DateTime("now");
            $IntervalCarga = $InicioCarga->diff($FinCarga);
            echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
        ?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Reporte1_Activacion_Proveedores
		FUNCION: Arma el reporte de Seguimiento de Tienda
		RETORNO: Lista de activacio de proveedores
		DESAROLLADO POR: SERGIO COVA
 	*/
	function R32_Seguimiento_Tienda($SedeConnection,$fecha) {
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();

        $FInicial = $fecha;
        $FFinal = date("Y-m-d",strtotime($FInicial."+ 1 days"));
        //TODO Borrar y dejar el comentario
        $TasaActual = '100000.00';
        //$TasaActual = FG_Tasa_Fecha($connCPharma,$FInicial);

        /*
        $sql6 = R32Q_Vent_generales($FInicial,$FFinal);
        $result6 = sqlsrv_query($conn,$sql6);

        $total_monto = $total_unid = $total_trans = $Sum_Trans_venta = $Sum_Trans_Dev =  0;

        $unid_med = $unid_misc = $monto_med = $monto_misc = "";

        while($row6 = sqlsrv_fetch_array($result6, SQLSRV_FETCH_ASSOC)) {
            if($row6['Tipo']=='Medicinas'){
                $unid_med = $row6['TotalUnidadesVendidas'];
                $monto_med = $row6['TotalVenta'];
            } else {
                $unid_misc = $row6['TotalUnidadesVendidas'];
                $monto_misc = $row6['TotalVenta'];
            }
        }

        $monto_med = (is_numeric($monto_med))?$monto_med:floatval($monto_med);
        $monto_misc = (is_numeric($monto_misc))?$monto_misc:floatval($monto_misc);

        $unid_med = (is_numeric($unid_med))?$unid_med:intval($unid_med);
        $unid_misc = (is_numeric($unid_misc))?$unid_misc:intval($unid_misc);

        $total_monto = $monto_med + $monto_misc;
        $total_unid = $unid_med + $unid_misc;

        $sql2 = R32Q_Vent_Cli_Nue_Rec_venta($FInicial,$FFinal);
        $result2 = sqlsrv_query($conn,$sql2);

		while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            $Sum_Trans_venta += $row2['TransaccionesFactura'];
        }

        $sql21 = R32Q_Vent_Cli_Nue_Rec_devolucion($FInicial,$FFinal);
        $result21 = sqlsrv_query($conn,$sql21);

		while($row21 = sqlsrv_fetch_array($result21, SQLSRV_FETCH_ASSOC)) {
            $Sum_Trans_Dev += $row21['TransaccionesDevolucion'];
        }

        $total_trans = $Sum_Trans_venta - $Sum_Trans_Dev;

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center" style="margin-top:50px;">Ventas Generales</h1>';

        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="bg-white" colspan="5" style="border-color:white;"></th>
                    <th scope="col" colspan="3">TOTAL</th>
                    <th scope="col" colspan="3">Promedio Por Factura</th>
                </tr>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Medicinas</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Miscelaneos</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Ventas<br>(Sin IVA)</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Trasaciones</th>
                    <th scope="col">Ventas<br>(Sin IVA)</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Valor Unidad</th>
                </tr>
            </thead>
            <tbody>
        ';

            echo '<tr>';
            echo '<td align="center">'.$FInicial.'</td>';
            echo '<td align="center">'.number_format($monto_med,2,"," ,"." ).'</td>';
            echo '<td align="center">'.intval($unid_med).'</td>';
            echo '<td align="center">'.number_format($monto_misc,2,"," ,"." ).'</td>';
            echo '<td align="center">'.intval($unid_misc).'</td>';
            echo '<td align="center">'.number_format($total_monto,2,"," ,"." ).'</td>';
            echo '<td align="center">'.intval($total_unid).'</td>';
            echo '<td align="center">'.intval($total_trans).'</td>';
            echo '<td align="center">'.number_format($total_monto/$total_trans,2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($total_unid/$total_trans,2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($total_monto/$total_unid,2,"," ,"." ).'</td>';
            echo '<tr>';
            echo'
            </tbody>
        </table>';

        if(isset($TasaActual)&&$TasaActual!=0){
            echo '
            <table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col" class="bg-white" colspan="5" style="border-color:white;"></th>
                        <th scope="col" colspan="3">TOTAL</th>
                        <th scope="col" colspan="3">Promedio Por Factura</th>
                    </tr>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Medicinas</th>
                        <th scope="col">Unidades</th>
                        <th scope="col">Miscelaneos</th>
                        <th scope="col">Unidades</th>
                        <th scope="col">Ventas<br>(Sin IVA)</th>
                        <th scope="col">Unidades</th>
                        <th scope="col">Trasaciones</th>
                        <th scope="col">Ventas<br>(Sin IVA)</th>
                        <th scope="col">Unidades</th>
                        <th scope="col">Valor Unidad</th>
                        <th scope="col">Tasa Mercado</th>
                    </tr>
                </thead>
                <tbody>
            ';

            echo '<tr>';
                echo '<td align="center">'.$FInicial.'</td>';
                echo '<td align="center">'.number_format($monto_med/$TasaActual,2,"," ,"." ).'</td>';
                echo '<td align="center">'.intval($unid_med).'</td>';
                echo '<td align="center">'.number_format($monto_misc/$TasaActual,2,"," ,"." ).'</td>';
                echo '<td align="center">'.intval($unid_misc).'</td>';
                echo '<td align="center">'.number_format($total_monto/$TasaActual,2,"," ,"." ).'</td>';
                echo '<td align="center">'.intval($total_unid).'</td>';
                echo '<td align="center">'.intval($total_trans).'</td>';
                echo '<td align="center">'.number_format( ($total_monto/$total_trans)/$TasaActual,2,"," ,"." ).'</td>';
                echo '<td align="center">'.number_format($total_unid/$total_trans,2,"," ,"." ).'</td>';
                echo '<td align="center">'.number_format( ($total_monto/$total_unid)/$TasaActual,2,"," ,"." ).'</td>';
                echo '<td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
                echo '<tr>';
                echo'
                </tbody>
            </table>';
        }
        */

        $sqlRC = R32Q_resumen_caja($FInicial,$FFinal);
        $resultRC = sqlsrv_query($conn,$sqlRC);

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center">Ventas por Caja</h1>';
        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Caja</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Frecuencia</th>
                    <th scope="col">Total</th>
                    <th scope="col">Factura Promedio</th>
                </tr>
            </thead>
            <tbody>
        ';

        while($rowRC = sqlsrv_fetch_array($resultRC, SQLSRV_FETCH_ASSOC)) {
            $unidades = $rowRC['Unidades'];
            $frecuencia = $rowRC['Frecuencia'];
            $total = $rowRC['Total'];

            if( $unidades>0 && $frecuencia>0 ){
                $facturaPromedio = ($total/$frecuencia);
                echo '<tr>';
                echo '<td align="center">'.$rowRC['Caja'].'</td>';
                echo '<td align="center">'.intval($unidades).'</td>';
                echo '<td align="center">'.intval($frecuencia).'</td>';
                echo '<td align="center">'.number_format($total,2,"," ,"." ).'</td>';
                echo '<td align="center">'.number_format($facturaPromedio,2,"," ,"." ).'</td>';
                echo '</tr>';
            }
        }
        echo '
            </tbody>
        </table>';
    //TODO
    /*
        $sql5 = R32Q_Vent_art_cond($FInicial,$FFinal);
        $result5 = sqlsrv_query($conn,$sql5);
        $result5a = sqlsrv_query($conn,$sql5);

        $sumUnidades = $sumMonto = $sumMontoDol = $sumCostoCalculado = $sumUtilidadBs = $sumUtilidadDol = 0;
        while($row5a = sqlsrv_fetch_array($result5a, SQLSRV_FETCH_ASSOC)) {
            $sumUnidades += $row5a['Unidades'];
            $sumMonto += $row5a['Monto'];
        }

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center">Ventas por Condicion</h1>';

        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Condicion</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">%</th>
                    <th scope="col">SKU</th>
                    <th scope="col">'.SigVe.'<br>(Sin IVA)</th>
                    <th scope="col">'.SigDolar.'<br>(Sin IVA)</th>
                    <th scope="col">%</th>
                    <th scope="col">Costo calculado<br>(Sin IVA)</th>
                    <th scope="col">Utilidad en '.SigVe.'</th>
                    <th scope="col">Utilidad en '.SigDolar.'</th>
                </tr>
            </thead>
            <tbody>
        ';

        $contador = 1;
		while($row5 = sqlsrv_fetch_array($result5, SQLSRV_FETCH_ASSOC)) {

            echo '<tr>';
            echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.$row5['PorcentajeUtilidad'].'</td>';
            echo '<td align="center">'.intval($row5['Unidades']).'</td>';
            echo '<td align="center">'.number_format((($row5['Unidades']/$sumUnidades)*100),2,"," ,"." ).'</td>';
            echo '<td align="center">'.intval($row5['sku']).'</td>';
            echo '<td align="center">'.number_format($row5['Monto'],2,"," ,"." ).'</td>';

            if(isset($TasaActual)&&$TasaActual!=0){
                echo '<td align="center">'.number_format(($row5['Monto']/$TasaActual),2,"," ,"." ).'</td>';
            }else{
                echo '<td align="center">0,00</td>';
            }

            echo '<td align="center">'.number_format((($row5['Monto']/$sumMonto)*100),2,"," ,"." ).'</td>';

            $costoCalculado = ($row5['Monto'])*( (100-$row5['PorcentajeUtilidad'])/100 );
            $sumCostoCalculado += $costoCalculado;
            echo '<td align="center">'.number_format($costoCalculado,2,"," ,"." ).'</td>';

            $utilidadBs = ($row5['Monto']-$costoCalculado);
            $sumUtilidadBs += $utilidadBs;
            echo '<td align="center">'.number_format($utilidadBs,2,"," ,"." ).'</td>';

            if(isset($TasaActual)&&$TasaActual!=0){
                $utilidadDol = ($utilidadBs/$TasaActual);
                $sumUtilidadDol +=  $utilidadDol;
                echo '<td align="center">'.number_format($utilidadDol,2,"," ,"." ).'</td>';
            }else{
                echo '<td align="center">0,00</td>';
            }

            echo '</tr>';
            $contador++;
        }

        $sumMontoDol = (isset($TasaActual)&&$TasaActual!=0)?$sumMonto/$TasaActual:"";

        echo'
            <tr>
                <td colspan="4"></td>
                <td align="center"><strong>Total</strong></td>
                <td align="center"><strong>'.number_format($sumMonto,2,"," ,"." ).'</strong></td>
                <td align="center"><strong>'.number_format($sumMontoDol,2,"," ,"." ).'</strong></td>
                <td></td>
                <td align="center"><strong>'.number_format($sumCostoCalculado,2,"," ,"." ).'</strong></td>
                <td align="center"><strong>'.number_format($sumUtilidadBs,2,"," ,"." ).'</strong></td>
                <td align="center"><strong>'.number_format($sumUtilidadDol,2,"," ,"." ).'</strong></td>
            </tr>
        ';

        $TotalPorcentual = ( 1- ($sumCostoCalculado/$sumMonto)  )*100;

        echo'
            <tr>
                <td colspan="9"></td>
                <td align="center" colspan="2"><strong>'.number_format($TotalPorcentual,2,"," ,"." ).' %</strong></td>
            </tr>
        ';

        echo '
            </tbody>
        </table>';

		$sql = R32Q_Venta_Articulos(5,$FInicial,$FFinal,'TotalVenta');
        $result = sqlsrv_query($conn,$sql);

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center">Ventas por Articulo</h1>';

		echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Codigo Interno</th>
                    <th scope="col">Codigo Barra</th>
                    <th scope="col">Articulo</th>
                    <th scope="col">Existencia</th>
                    <th scope="col">Precio</th>
                    <th scope="col">'.SigVe.'</th>
                    <th scope="col">'.SigDolar.'</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Frecuencia</th>
                    <th scope="col">Ultimo Proveedor</th>
                </tr>
            </thead>
            <tbody>
        ';
        $contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

            $IdArticulo = $row["InvArticuloId"];

            $sqlDet = SQG_Detalle_Articulo($IdArticulo);
            $resultDet = sqlsrv_query($conn,$sqlDet);
            $rowDet = sqlsrv_fetch_array($resultDet, SQLSRV_FETCH_ASSOC);

            $CodigoInterno = $rowDet["CodigoInterno"];
            $CodigoBarra = $rowDet["CodigoBarra"];
            $Existencia = $rowDet["Existencia"];
            $ExistenciaAlmacen1 = $rowDet["ExistenciaAlmacen1"];
            $ExistenciaAlmacen2 = $rowDet["ExistenciaAlmacen2"];
            $IsTroquelado = $rowDet["Troquelado"];
            $IsIVA = $rowDet["Impuesto"];
            $UtilidadArticulo = $rowDet["UtilidadArticulo"];
            $UtilidadCategoria = $rowDet["UtilidadCategoria"];
            $TroquelAlmacen1 = $rowDet["TroquelAlmacen1"];
            $PrecioCompraBrutoAlmacen1 = $rowDet["PrecioCompraBrutoAlmacen1"];
            $TroquelAlmacen2 = $rowDet["TroquelAlmacen2"];
            $PrecioCompraBrutoAlmacen2 = $rowDet["PrecioCompraBrutoAlmacen2"];
            $PrecioCompraBruto = $rowDet["PrecioCompraBruto"];
            $CondicionExistencia = 'CON_EXISTENCIA';

            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
            $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="left">'.$CodigoInterno.'</td>';
            echo '<td align="left">'.$CodigoBarra.'</td>';
            echo
            '<td align="left" class="CP-barrido">
            <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
                .FG_Limpiar_Texto($row['Descripcion']).
            '</a>
            </td>';
            echo '<td align="center">'.intval($Existencia).'</td>';
            echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';

            echo '<td align="center">'.number_format($row['TotalVenta'],2,"," ,"." ).'</td>';

            if(isset($TasaActual)&&$TasaActual!=0){
                echo '<td align="center">'.number_format(($row['TotalVenta']/$TasaActual),2,"," ,"." ).'</td>';
            }else{
                echo '<td align="center">0,00</td>';
            }

            echo '<td align="center">'.intval($row['TotalUnidadesVendidas']).'</td>';
            echo '<td align="center">'.intval($row['TotalVecesVendidas']).'</td>';
            echo '<td align="center">'.FG_Limpiar_Texto($rowDet['UltimoProveedorNombre']).'</td>';
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		    </tbody>
        </table>';

        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Codigo Interno</th>
                    <th scope="col">Codigo Barra</th>
                    <th scope="col">Articulo</th>
                    <th scope="col">Existencia</th>
                    <th scope="col">Precio</th>
                    <th scope="col">'.SigVe.'</th>
                    <th scope="col">'.SigDolar.'</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Frecuencia</th>
                    <th scope="col">Ultimo Proveedor</th>
                </tr>
            </thead>
            <tbody>
        ';

        $sql1 = R32Q_Venta_Articulos(5,$FInicial,$FFinal,'TotalUnidadesVendidas');
        $result1 = sqlsrv_query($conn,$sql1);

        $contador = 1;
		while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

            $IdArticulo = $row1["InvArticuloId"];

            $sqlDet = SQG_Detalle_Articulo($IdArticulo);
            $resultDet = sqlsrv_query($conn,$sqlDet);
            $rowDet = sqlsrv_fetch_array($resultDet, SQLSRV_FETCH_ASSOC);

            $CodigoInterno = $rowDet["CodigoInterno"];
            $CodigoBarra = $rowDet["CodigoBarra"];
            $Existencia = $rowDet["Existencia"];
            $ExistenciaAlmacen1 = $rowDet["ExistenciaAlmacen1"];
            $ExistenciaAlmacen2 = $rowDet["ExistenciaAlmacen2"];
            $IsTroquelado = $rowDet["Troquelado"];
            $IsIVA = $rowDet["Impuesto"];
            $UtilidadArticulo = $rowDet["UtilidadArticulo"];
            $UtilidadCategoria = $rowDet["UtilidadCategoria"];
            $TroquelAlmacen1 = $rowDet["TroquelAlmacen1"];
            $PrecioCompraBrutoAlmacen1 = $rowDet["PrecioCompraBrutoAlmacen1"];
            $TroquelAlmacen2 = $rowDet["TroquelAlmacen2"];
            $PrecioCompraBrutoAlmacen2 = $rowDet["PrecioCompraBrutoAlmacen2"];
            $PrecioCompraBruto = $rowDet["PrecioCompraBruto"];
            $CondicionExistencia = 'CON_EXISTENCIA';

            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
            $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="left">'.$CodigoInterno.'</td>';
            echo '<td align="left">'.$CodigoBarra.'</td>';
            echo
            '<td align="left" class="CP-barrido">
            <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
                .FG_Limpiar_Texto($row1['Descripcion']).
            '</a>
            </td>';
            echo '<td align="center">'.intval($Existencia).'</td>';
            echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';

            echo '<td align="center">'.number_format($row1['TotalVenta'],2,"," ,"." ).'</td>';

            if(isset($TasaActual)&&$TasaActual!=0){
                echo '<td align="center">'.number_format(($row1['TotalVenta']/$TasaActual),2,"," ,"." ).'</td>';
            }else{
                echo '<td align="center">0,00</td>';
            }

            echo '<td align="center">'.intval($row1['TotalUnidadesVendidas']).'</td>';
            echo '<td align="center">'.intval($row1['TotalVecesVendidas']).'</td>';
            echo '<td align="center">'.FG_Limpiar_Texto($rowDet['UltimoProveedorNombre']).'</td>';
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		    </tbody>
        </table>';

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center">Ventas por Clientes</h1>';

        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">'.SigVe.'</th>
                    <th scope="col">'.SigDolar.'</th>
                    <th scope="col">%</th>
                    <th scope="col">Trasacciones</th>
                    <th scope="col">%</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">%</th>
                </tr>
            </thead>
            <tbody>
        ';

        $Sum_Monto_Nue = $Sum_Trans_Nue =  $Sum_Unid_Nue = 0;
        $Sum_Monto_Rec = $Sum_Trans_Rec =  $Sum_Unid_Rec = 0;

        $sql2 = R32Q_Vent_Cli_Nue_Rec_venta($FInicial,$FFinal);
        $result2 = sqlsrv_query($conn,$sql2);

        $Sum_Monto_Nue_Fac = $Sum_Trans_Nue_Fac =  $Sum_Unid_Nue_Fac = 0;
        $Sum_Monto_Rec_Fac = $Sum_Trans_Rec_Fac =  $Sum_Unid_Rec_Fac = 0;

		while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            if($row2['TipoCliente']=='Nuevo'){
                $Sum_Monto_Nue_Fac += $row2['MontoFactura'];
                $Sum_Unid_Nue_Fac += $row2['UnidadesFactura'];
                $Sum_Trans_Nue_Fac += $row2['TransaccionesFactura'];
            }else{
                $Sum_Monto_Rec_Fac += $row2['MontoFactura'];
                $Sum_Unid_Rec_Fac += $row2['UnidadesFactura'];
                $Sum_Trans_Rec_Fac += $row2['TransaccionesFactura'];
            }
        }

        $sql21 = R32Q_Vent_Cli_Nue_Rec_devolucion($FInicial,$FFinal);
        $result21 = sqlsrv_query($conn,$sql21);

        $Sum_Monto_Nue_Dev = $Sum_Trans_Nue_Dev =  $Sum_Unid_Nue_Dev = 0;
        $Sum_Monto_Rec_Dev = $Sum_Trans_Rec_Dev =  $Sum_Unid_Rec_Dev = 0;

		while($row21 = sqlsrv_fetch_array($result21, SQLSRV_FETCH_ASSOC)) {
            if($row21['TipoCliente']=='Nuevo'){
                $Sum_Monto_Nue_Dev += $row21['MontoDevolucion'];
                $Sum_Unid_Nue_Dev += $row21['UnidadesDevolucion'];
                $Sum_Trans_Nue_Dev += $row21['TransaccionesDevolucion'];
            }else{
                $Sum_Monto_Rec_Dev += $row21['MontoDevolucion'];
                $Sum_Unid_Rec_Dev += $row21['UnidadesDevolucion'];
                $Sum_Trans_Rec_Dev += $row21['TransaccionesDevolucion'];
            }
        }

        $Sum_Monto_Nue = $Sum_Monto_Nue_Fac - $Sum_Monto_Nue_Dev;
        $Sum_Trans_Nue = $Sum_Trans_Nue_Fac - $Sum_Trans_Nue_Dev;
        $Sum_Unid_Nue = $Sum_Unid_Nue_Fac -  $Sum_Unid_Nue_Dev;
        $Sum_Monto_Rec = $Sum_Monto_Rec_Fac - $Sum_Monto_Rec_Dev;
        $Sum_Trans_Rec = $Sum_Trans_Rec_Fac - $Sum_Trans_Rec_Dev;
        $Sum_Unid_Rec = $Sum_Unid_Rec_Fac - $Sum_Unid_Rec_Dev;

        $total_p = $Sum_Monto_Nue + $Sum_Monto_Rec;
        $p_monto_Nue = ($Sum_Monto_Nue*100)/$total_p;
        $p_monto_Rec = ($Sum_Monto_Rec*100)/$total_p;

        $total_t = $Sum_Trans_Nue + $Sum_Trans_Rec;
        $p_trans_Nue = ($Sum_Trans_Nue*100)/$total_t;
        $p_trans_Rec = ($Sum_Trans_Rec*100)/$total_t;

        $total_u = $Sum_Unid_Nue + $Sum_Unid_Rec;
        $u_trans_Nue = ($Sum_Unid_Nue*100)/$total_u;
        $u_trans_Rec = ($Sum_Unid_Rec*100)/$total_u;

        echo '<tr>';
        echo '<td align="center"><strong>1</strong></td>';
        echo '<td align="center">Clientes Nuevos</td>';
        echo '<td align="center">'.number_format($Sum_Monto_Nue,2,"," ,"." ).'</td>';

        if(isset($TasaActual)&&$TasaActual!=0){
            echo '<td align="center">'.number_format(($Sum_Monto_Nue/$TasaActual),2,"," ,"." ).'</td>';
        }else{
            echo '<td align="center">0,00</td>';
        }
        echo '<td align="center">'.number_format($p_monto_Nue,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Trans_Nue).'</td>';
        echo '<td align="center">'.number_format($p_trans_Nue,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Unid_Nue).'</td>';
        echo '<td align="center">'.number_format($u_trans_Nue,2,"," ,"." ).' % </td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td align="center"><strong>2</strong></td>';
        echo '<td align="center">Clientes Recurrentes</td>';
        echo '<td align="center">'.number_format($Sum_Monto_Rec,2,"," ,"." ).'</td>';

        if(isset($TasaActual)&&$TasaActual!=0){
            echo '<td align="center">'.number_format(($Sum_Monto_Rec/$TasaActual),2,"," ,"." ).'</td>';
        }else{
            echo '<td align="center">0,00</td>';
        }
        echo '<td align="center">'.number_format($p_monto_Rec,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Trans_Rec).'</td>';
        echo '<td align="center">'.number_format($p_trans_Rec,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Unid_Rec).'</td>';
        echo '<td align="center">'.number_format($u_trans_Rec,2,"," ,"." ).' % </td>';
        echo '</tr>';

        echo '
  		    </tbody>
        </table>';

        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">'.SigVe.'</th>
                    <th scope="col">'.SigDolar.'</th>
                    <th scope="col">%</th>
                    <th scope="col">Trasacciones</th>
                    <th scope="col">%</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">%</th>
                </tr>
            </thead>
            <tbody>
        ';

        $Sum_Monto_Nat = $Sum_Trans_Nat =  $Sum_Unid_Nat = 0;
        $Sum_Monto_Jur = $Sum_Trans_Jur =  $Sum_Unid_Jur = 0;

        $sql3 = R32Q_Vent_Cli_Nat_Jur_venta($FInicial,$FFinal);
        $result3 = sqlsrv_query($conn,$sql3);

        $Sum_Monto_Nat_Fac = $Sum_Trans_Nat_Fac =  $Sum_Unid_Nat_Fac = 0;
        $Sum_Monto_Jur_Fac = $Sum_Trans_Jur_Fac =  $Sum_Unid_Jur_Fac = 0;

		while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
            if($row3['tipoPersona']=='1'){
                $Sum_Monto_Nat_Fac += $row3['MontoFactura'];
                $Sum_Unid_Nat_Fac += $row3['UnidadesFactura'];
                $Sum_Trans_Nat_Fac += $row3['TransaccionesFactura'];
            }else{
                $Sum_Monto_Jur_Fac += $row3['MontoFactura'];
                $Sum_Unid_Jur_Fac += $row3['UnidadesFactura'];
                $Sum_Trans_Jur_Fac += $row3['TransaccionesFactura'];
            }
          }

        $sql31 = R32Q_Vent_Cli_Nat_Jur_devolucion($FInicial,$FFinal);
        $result31 = sqlsrv_query($conn,$sql31);

        $Sum_Monto_Nat_Dev = $Sum_Trans_Nat_Dev =  $Sum_Unid_Nat_Dev = 0;
        $Sum_Monto_Jur_Dev = $Sum_Trans_Jur_Dev =  $Sum_Unid_Jur_Dev = 0;

		while($row31 = sqlsrv_fetch_array($result31, SQLSRV_FETCH_ASSOC)) {
            if($row31['tipoPersona']=='1'){
                $Sum_Monto_Nat_Dev += $row31['MontoDevolucion'];
                $Sum_Unid_Nat_Dev += $row31['UnidadesDevolucion'];
                $Sum_Trans_Nat_Dev += $row31['TransaccionesDevolucion'];
            }else{
                $Sum_Monto_Jur_Dev += $row31['MontoDevolucion'];
                $Sum_Unid_Jur_Dev += $row31['UnidadesDevolucion'];
                $Sum_Trans_Jur_Dev += $row31['TransaccionesDevolucion'];
            }
        }

        $Sum_Monto_Nat = $Sum_Monto_Nat_Fac - $Sum_Monto_Nat_Dev;
        $Sum_Trans_Nat = $Sum_Trans_Nat_Fac - $Sum_Trans_Nat_Dev;
        $Sum_Unid_Nat = $Sum_Unid_Nat_Fac - $Sum_Unid_Nat_Dev;
        $Sum_Monto_Jur = $Sum_Monto_Jur_Fac - $Sum_Monto_Jur_Dev;
        $Sum_Trans_Jur = $Sum_Trans_Jur_Fac - $Sum_Trans_Jur_Dev;
        $Sum_Unid_Jur = $Sum_Unid_Jur_Fac - $Sum_Unid_Jur_Dev;

        $total_p = $Sum_Monto_Nat + $Sum_Monto_Jur;
        $p_monto_Nat = ($Sum_Monto_Nat*100)/$total_p;
        $p_monto_Jur = ($Sum_Monto_Jur*100)/$total_p;

        $total_t = $Sum_Trans_Nat + $Sum_Trans_Jur;
        $p_trans_Nat = ($Sum_Trans_Nat*100)/$total_t;
        $p_trans_Jur = ($Sum_Trans_Jur*100)/$total_t;

        $total_u = $Sum_Unid_Nat + $Sum_Unid_Jur;
        $u_trans_Nat = ($Sum_Unid_Nat*100)/$total_u;
        $u_trans_Jur = ($Sum_Unid_Jur*100)/$total_u;

        echo '<tr>';
        echo '<td align="center"><strong>1</strong></td>';
        echo '<td align="center">Clientes Naturales</td>';
        echo '<td align="center">'.number_format($Sum_Monto_Nat,2,"," ,"." ).'</td>';

        if(isset($TasaActual)&&$TasaActual!=0){
            echo '<td align="center">'.number_format(($Sum_Monto_Nat/$TasaActual),2,"," ,"." ).'</td>';
        }else{
            echo '<td align="center">0,00</td>';
        }
        echo '<td align="center">'.number_format($p_monto_Nat,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Trans_Nat).'</td>';
        echo '<td align="center">'.number_format($p_trans_Nat,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Unid_Nat).'</td>';
        echo '<td align="center">'.number_format($u_trans_Nat,2,"," ,"." ).' % </td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td align="center"><strong>2</strong></td>';
        echo '<td align="center">Clientes Juridicos</td>';
        echo '<td align="center">'.number_format($Sum_Monto_Jur,2,"," ,"." ).'</td>';

        if(isset($TasaActual)&&$TasaActual!=0){
            echo '<td align="center">'.number_format(($Sum_Monto_Jur/$TasaActual),2,"," ,"." ).'</td>';
        }else{
            echo '<td align="center">0,00</td>';
        }
        echo '<td align="center">'.number_format($p_monto_Jur,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Trans_Jur).'</td>';
        echo '<td align="center">'.number_format($p_trans_Jur,2,"," ,"." ).' % </td>';
        echo '<td align="center">'.intval($Sum_Unid_Jur).'</td>';
        echo '<td align="center">'.number_format($u_trans_Jur,2,"," ,"." ).' % </td>';
        echo '</tr>';

        echo '
  		    </tbody>
        </table>';

        $sql4 = R32Q_Vent_Cli_Top($FInicial,$FFinal);
        $result4 = sqlsrv_query($conn,$sql4);

		echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">'.SigVe.'</th>
                    <th scope="col">'.SigDolar.'</th>
                    <th scope="col">Trasacciones</th>
                    <th scope="col">Unidades</th>
                </tr>
            </thead>
            <tbody>
        ';
        $contador = 1;
		while($row4 = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row4['Nombre']." ".$row4['Apellido']).'</td>';
            echo '<td align="center">'.number_format($row4['MontoFactura'],2,"," ,"." ).'</td>';

            if(isset($TasaActual)&&$TasaActual!=0){
                echo '<td align="center">'.number_format(($row4['MontoFactura']/$TasaActual),2,"," ,"." ).'</td>';
            }else{
                echo '<td align="center">0,00</td>';
            }

            echo '<td align="center">'.intval($row4['Transacciones']).'</td>';
            echo '<td align="center">'.intval($row4['Unidades']).'</td>';
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		    </tbody>
        </table>';
        */

        $sqlRCD = R32Q_devolucion_caja($FInicial,$FFinal);
        $resultRCD = sqlsrv_query($conn,$sqlRCD);

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center">Devoluciones por Caja</h1>';
        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Caja</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Frecuencia</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
        ';

        while($rowRCD = sqlsrv_fetch_array($resultRCD, SQLSRV_FETCH_ASSOC)) {
            $unidades = $rowRCD['UnidadesDev'];
            $frecuencia = $rowRCD['FrecuenciaDev'];
            $total = $rowRCD['TotalDev'];

            if( $unidades>0 && $frecuencia>0 ){
                echo '<tr>';
                echo '<td align="center">'.$rowRCD['Caja'].'</td>';
                echo '<td align="center">'.intval($unidades).'</td>';
                echo '<td align="center">'.intval($frecuencia).'</td>';
                echo '<td align="center">'.number_format($total,2,"," ,"." ).'</td>';
                echo '</tr>';
            }
        }
        echo '
            </tbody>
        </table>';

        $sqlRDC = R32Q_devolucion_causa($FInicial,$FFinal);
        $resultRDC = sqlsrv_query($conn,$sqlRDC);

        echo '<hr class="row align-items-start col-12">';
        echo '<h1 class="h5 text-dark" align="center">Devoluciones por Causa</h1>';
        echo '
		<table class="table table-striped table-bordered col-12 sortable" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Causa</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
        ';

        while($rowRDC = sqlsrv_fetch_array($resultRDC, SQLSRV_FETCH_ASSOC)) {
            $unidades = $rowRDC['Cantidad'];
            $total = $rowRDC['Total'];

            if( $unidades>0 ){
                echo '<tr>';
                echo '<td align="center">'.$rowRDC['DescripcionOperacion'].'</td>';
                echo '<td align="center">'.intval($unidades).'</td>';
                echo '<td align="center">'.number_format($total,2,"," ,"." ).'</td>';
                echo '</tr>';
            }
        }
        echo '
            </tbody>
        </table>';

        mysqli_close($connCPharma);
		sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: R32Q_Venta_Articulos
		FUNCION: Query que genera la lista de los proveedores con la diferencia en dias desde el ultimo despacho de mercancia.
		RETORNO: Lista de proveedores con diferencia en dias respecto al dia actual
		DESAROLLADO POR: SERGIO COVA
	 */
	function R32Q_Venta_Articulos($Top,$FInicial,$FFinal,$ordenarPor) {
        $sql = "
        SELECT TOP $Top
        -- Id Articulo
        VenFacturaDetalle.InvArticuloId,
        InvArticulo.Descripcion,
        --Veces Vendidas
        ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
        --Unidades Vendidas
        (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas,
        --Veces Devueltas
        ISNULL((SELECT
        ISNULL(COUNT(*),CAST(0 AS INT))
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucionDetalle.InvArticuloId
        ),CAST(0 AS INT)) AS VecesDevueltas,
        --Unidades Devueltas
        ISNULL((SELECT
        (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucionDetalle.InvArticuloId
        ),CAST(0 AS INT)) AS UnidadesDevueltas,
        --Total Veces Vendidas
        ((ISNULL(COUNT(*),CAST(0 AS INT)))
        -
        (ISNULL((SELECT
        ISNULL(COUNT(*),CAST(0 AS INT))
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucionDetalle.InvArticuloId
        ),CAST(0 AS INT)))) AS TotalVecesVendidas,
        --Total Unidades Vendidas (En Rango)
        (((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
        -
        (ISNULL((SELECT
        (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucionDetalle.InvArticuloId
        ),CAST(0 AS INT)))) AS TotalUnidadesVendidas,
        --Veces Conpradas (En Rango)
        ISNULL((SELECT
        ISNULL(COUNT(*),CAST(0 AS INT))
        FROM ComFacturaDetalle
        INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
        WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
        GROUP BY ComFacturaDetalle.InvArticuloId
        ),CAST(0 AS INT)) AS VecesCompradas,
        --Unidades Conpradas (En Rango)
        ISNULL((SELECT
        (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
        FROM ComFacturaDetalle
        INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
        WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
        GROUP BY ComFacturaDetalle.InvArticuloId
        ),CAST(0 AS INT)) AS UnidadesCompradas,
        --Veces Reclamadas (En Rango)
        ISNULL((SELECT
        ISNULL(COUNT(*),CAST(0 AS INT))
        FROM ComReclamoDetalle
        INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
        WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
        GROUP BY ComReclamoDetalle.InvArticuloId
        ),CAST(0 AS INT)) AS VecesReclamadas,
        --Unidades Reclamadas (En Rango)
        ISNULL((SELECT
        (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM ComReclamoDetalle
        INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
        WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
        GROUP BY ComReclamoDetalle.InvArticuloId
        ),CAST(0 AS INT)) AS UnidadesReclamadas,
        --Total Veces Compradas (En Rango)
        ((ISNULL((SELECT
        ISNULL(COUNT(*),CAST(0 AS INT))
        FROM ComFacturaDetalle
        INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
        WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
        GROUP BY ComFacturaDetalle.InvArticuloId
        ),CAST(0 AS INT)))
        -
        (ISNULL((SELECT
        ISNULL(COUNT(*),CAST(0 AS INT))
        FROM ComReclamoDetalle
        INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
        WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
        GROUP BY ComReclamoDetalle.InvArticuloId
        ),CAST(0 AS INT)))) AS TotalVecesCompradas,
        --Total de Unidades Compradas (En Rango)
        ((ISNULL((SELECT
        (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
        FROM ComFacturaDetalle
        INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
        WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
        GROUP BY ComFacturaDetalle.InvArticuloId
        ),CAST(0 AS INT)))
        -
        (ISNULL((SELECT
        (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM ComReclamoDetalle
        INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
        WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
        GROUP BY ComReclamoDetalle.InvArticuloId
        ),CAST(0 AS INT)))) AS TotalUnidadesCompradas,
        -- SubTotal Venta (En Rango)
        ISNULL((SELECT
        (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
        FROM VenVentaDetalle
        INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
        WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
        AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND VenVenta.estadoVenta = 2),CAST(0 AS INT)) AS SubTotalVenta,
        --SubTotal Devolucion (En Rango)
        ISNULL((SELECT
        (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) as SubTotalDevolucion,
        --TotalVenta (En Rango)
        ((ISNULL((SELECT
        (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
        FROM VenVentaDetalle
        INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
        WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
        AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND VenVenta.estadoVenta = 2),CAST(0 AS INT)))
        -
        (ISNULL((SELECT
        (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta
        --Tabla Principal
        FROM VenFacturaDetalle
        --Joins
        INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
        LEFT JOIN InvArticulo on InvArticulo.Id = VenFacturaDetalle.InvArticuloId
        --Condicionales
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        --Agrupamientos
        GROUP BY VenFacturaDetalle.InvArticuloId, InvArticulo.Descripcion
        --Ordenamientos
        ORDER BY $ordenarPor DESC
        ";
        return $sql;
    }

    /*
        TITULO: R32Q_Vent_Cli_Nue_Rec_venta
    */
    function R32Q_Vent_Cli_Nue_Rec_venta($FInicial,$FFinal){
        $sql = "
        --Clientes Nuevos y Recurrentes
        select TipoCliente,
        SUM(MontoFactura) AS MontoFactura,
        SUM(Unidades) AS UnidadesFactura,
        SUM(Transacciones) AS TransaccionesFactura
        from
        (SELECT
        VenFactura.Id,
        IIF( CONVERT(date,VenCliente.Auditoria_FechaCreacion) = '$FInicial', 'Nuevo' ,'Recurrente') as TipoCliente,
        (VenFactura.M_MontoTotalFactura) AS MontoFactura,
        ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
        COUNT(DISTINCT VenFactura.Id) as Transacciones
        FROM VenFactura
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY VenFactura.M_MontoTotalFactura,VenCliente.Auditoria_FechaCreacion,VenFactura.Id) AS Factura
        GROUP BY TipoCliente
        ";
        return $sql;
    }
    /*
        TITULO: R32Q_Vent_Cli_Nue_Rec_devolucion
    */
    function R32Q_Vent_Cli_Nue_Rec_devolucion($FInicial,$FFinal){
        $sql = "
        --Clientes Devolucion Nuevos y Recurrentes
        select TipoCliente,
        SUM(MontoDevolucion) AS MontoDevolucion,
        SUM(Unidades) AS UnidadesDevolucion,
        SUM(Transacciones) AS TransaccionesDevolucion
        from
        (SELECT
        VenDevolucion.Id,
        IIF( CONVERT(date,VenCliente.Auditoria_FechaCreacion) = '$FInicial', 'Nuevo' ,'Recurrente') as TipoCliente,
        (VenDevolucion.M_MontoTotalDevolucion) AS MontoDevolucion,
        ((ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
        COUNT(DISTINCT VenFactura.Id) as Transacciones
        FROM VenDevolucion
        LEFT JOIN VenDevolucionDetalle ON VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id
        LEFT JOIN VenFactura ON VenFactura.Id = VenDevolucion.VenFacturaId
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        WHERE
        (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucion.M_MontoTotalDevolucion,VenCliente.Auditoria_FechaCreacion,VenDevolucion.Id) AS Devolucion
        GROUP BY TipoCliente
        ";
        return $sql;
    }
    /*
        TITULO: R32Q_Vent_Cli_Nat_Jur_venta
    */
    function R32Q_Vent_Cli_Nat_Jur_venta($FInicial,$FFinal){
        $sql = "
        --Clientes por Tipo Persona
        select tipoPersona,
        SUM(Monto) AS MontoFactura,
        SUM(Unidades) AS UnidadesFactura,
        SUM(Transacciones) AS TransaccionesFactura
        from
        (SELECT
        VenFactura.id,
        GenPersona.tipoPersona,
        (VenFactura.M_MontoTotalFactura) AS Monto,
        ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
        COUNT(DISTINCT VenFactura.Id) as Transacciones
        FROM VenFactura
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY VenFactura.M_MontoTotalFactura,GenPersona.tipoPersona,VenFactura.id) AS Factura
        GROUP BY tipoPersona
        ";
        return $sql;
    }
    /*
        TITULO: R32Q_Vent_Cli_Nat_Jur_devolucion
    */
    function R32Q_Vent_Cli_Nat_Jur_devolucion($FInicial,$FFinal){
        $sql = "
        --Clientes por Tipo Persona
        select tipoPersona,
        SUM(MontoFactura) AS MontoDevolucion,
        SUM(Unidades) AS UnidadesDevolucion,
        SUM(Transacciones) AS TransaccionesDevolucion
        from
        (SELECT
        VenDevolucion.Id,
        GenPersona.tipoPersona,
        (VenDevolucion.M_MontoTotalDevolucion) AS MontoFactura,
        ((ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
        COUNT(DISTINCT VenDevolucion.Id) as Transacciones
        FROM VenDevolucion
        LEFT JOIN VenDevolucionDetalle ON VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id
        LEFT JOIN VenFactura ON VenFactura.id = VenDevolucion.VenFacturaId
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        WHERE
        (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucion.M_MontoTotalDevolucion,GenPersona.tipoPersona,VenDevolucion.Id) AS Devolucion
        GROUP BY tipoPersona
        ";
        return $sql;
    }
     /*
        TITULO: R32Q_Vent_Cli_Top
    */
    function R32Q_Vent_Cli_Top($FInicial,$FFinal){
        $sql = "
        SELECT top 5
        VenCliente.id as IdCliente,
        GenPersona.Nombre,
        GenPersona.Apellido,
        (VenFactura.M_MontoTotalFactura) AS MontoFactura,
        ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
            (select COUNT(DISTINCT VenFactura.Id)
            FROM VenFactura
            WHERE
            (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
            and VenFactura.VenClienteId = VenCliente.id) as Transacciones
        FROM VenFactura
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY GenPersona.Nombre,GenPersona.Apellido,VenCliente.id, VenFactura.M_MontoTotalFactura
        ORDER BY VenFactura.M_MontoTotalFactura DESC
        ";
        return $sql;
    }

    /*
        TITULO: R32Q_Vent_art_cond
    */
    function R32Q_Vent_art_cond($FInicial,$FFinal){
        $sql = "
        select PorcentajeUtilidad, sum(TotalUnidadesVendidas) as Unidades, sum(TotalVenta) as Monto, count(*) as sku
        from
        (select
        VenCondicionVenta.PorcentajeUtilidad as PorcentajeUtilidad,
        InvArticulo.id,
        InvArticulo.Descripcion,
        (ISNULL((SELECT
        (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM VenFacturaDetalle
        INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
        WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
        AND(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY VenFacturaDetalle.InvArticuloId
        ),CAST(0 AS INT))) as unidadesVendidas,
        (ISNULL((SELECT
        (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
        AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucionDetalle.InvArticuloId
        ),CAST(0 AS INT))) as unidadesDevueltas,
        --Total Unidades Vendidas (En Rango)
        (
        (ISNULL((SELECT
        (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM VenFacturaDetalle
        INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
        WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
        AND(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY VenFacturaDetalle.InvArticuloId
        ),CAST(0 AS INT)))
        -
        (ISNULL((SELECT
        (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
        AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        GROUP BY VenDevolucionDetalle.InvArticuloId
        ),CAST(0 AS INT)))
        ) AS TotalUnidadesVendidas,
        (ISNULL((SELECT
        (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
        FROM VenVentaDetalle
        INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
        WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
        AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND VenVenta.estadoVenta = 2),CAST(0 AS INT))) as Venta,
        (ISNULL((SELECT
        (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT))) as Devolucion,
        --TotalVenta (En Rango)
        ((ISNULL((SELECT
        (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
        FROM VenVentaDetalle
        INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
        WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
        AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
        AND VenVenta.estadoVenta = 2),CAST(0 AS INT)))
        -
        (ISNULL((SELECT
        (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
        FROM VenDevolucionDetalle
        INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
        WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta
        from VenFactura
        left join VenFacturaDetalle on VenFacturaDetalle.VenFacturaId = VenFactura.Id
        left join InvArticulo on InvArticulo.id = VenFacturaDetalle.InvArticuloId
        left join VenCondicionVenta_VenCondicionVentaCategoria on VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId
        left join VenCondicionVenta on VenCondicionVenta.id = VenCondicionVenta_VenCondicionVentaCategoria.Id
        left join VenDevolucionDetalle on VenDevolucionDetalle.InvArticuloId = InvArticulo.id
        left join VenDevolucion on VenDevolucion.id = VenDevolucionDetalle.VenDevolucionId
        WHERE (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        or (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
        group by VenCondicionVenta.PorcentajeUtilidad, VenFacturaDetalle.InvArticuloId, InvArticulo.id, InvArticulo.Descripcion
        ) as condicion
        group by PorcentajeUtilidad
        order by PorcentajeUtilidad asc
        ";
        return $sql;
    }
    /*
    */
    function R32Q_Vent_generales($FInicial,$FFinal){
        $sql = "
        select Tipo, sum(TotalUnidadesVendidas) as TotalUnidadesVendidas, sum(TotalVenta) as TotalVenta
        from
        (
        select
                VenCondicionVenta.PorcentajeUtilidad as PorcentajeUtilidad,
                InvArticulo.id,
                --Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
                IIF( (ISNULL((SELECT
                    InvArticuloAtributo.InvArticuloId
                    FROM InvArticuloAtributo
                    WHERE InvArticuloAtributo.InvAtributoId =
                    (SELECT InvAtributo.Id
                    FROM InvAtributo
                    WHERE
                    InvAtributo.Descripcion = 'Medicina')
                    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) = '0', 'Miscelaneos' ,'Medicinas') AS Tipo,
                InvArticulo.Descripcion,
                (ISNULL((SELECT
                (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
                FROM VenFacturaDetalle
                INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
                WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
                AND(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
                GROUP BY VenFacturaDetalle.InvArticuloId
                ),CAST(0 AS INT))) as unidadesVendidas,
                (ISNULL((SELECT
                (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
                FROM VenDevolucionDetalle
                INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
                WHERE VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
                AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
                GROUP BY VenDevolucionDetalle.InvArticuloId
                ),CAST(0 AS INT))) as unidadesDevueltas,
                --Total Unidades Vendidas (En Rango)
                (
                (ISNULL((SELECT
                (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
                FROM VenFacturaDetalle
                INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
                WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
                AND(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
                GROUP BY VenFacturaDetalle.InvArticuloId
                ),CAST(0 AS INT)))
                -
                (ISNULL((SELECT
                (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
                FROM VenDevolucionDetalle
                INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
                WHERE VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
                AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
                GROUP BY VenDevolucionDetalle.InvArticuloId
                ),CAST(0 AS INT)))
                ) AS TotalUnidadesVendidas,
                (ISNULL((SELECT
                (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
                FROM VenVentaDetalle
                INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
                WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
                AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
                AND VenVenta.estadoVenta = 2),CAST(0 AS INT))) as Venta,
                (ISNULL((SELECT
                (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
                FROM VenDevolucionDetalle
                INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
                WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
                AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT))) as Devolucion,
                --TotalVenta (En Rango)
                ((ISNULL((SELECT
                (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
                FROM VenVentaDetalle
                INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
                WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
                AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
                AND VenVenta.estadoVenta = 2),CAST(0 AS INT)))
                -
                (ISNULL((SELECT
                (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
                FROM VenDevolucionDetalle
                INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
                WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
                AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta
                from VenFactura
                left join VenFacturaDetalle on VenFacturaDetalle.VenFacturaId = VenFactura.Id
                left join InvArticulo on InvArticulo.id = VenFacturaDetalle.InvArticuloId
                left join VenCondicionVenta_VenCondicionVentaCategoria on VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId
                left join VenCondicionVenta on VenCondicionVenta.id = VenCondicionVenta_VenCondicionVentaCategoria.Id
                left join VenDevolucionDetalle on VenDevolucionDetalle.InvArticuloId = InvArticulo.id
                left join VenDevolucion on VenDevolucion.id = VenDevolucionDetalle.VenDevolucionId
                WHERE (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
                or (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
                group by VenCondicionVenta.PorcentajeUtilidad, VenFacturaDetalle.InvArticuloId, InvArticulo.id, InvArticulo.Descripcion
        ) as ventaGeneral
        group by Tipo
        ";
        return $sql;
    }

    /*
        TITULO: R32Q_Vent_Cli_Top
    */
    /*
    function R32Q_resumen_caja($FInicial,$FFinal){
        $sql = "
        select
        VenCaja.Id as IdCaja,
        VenCaja.EstacionTrabajo as Caja,
        --PrimerMonto
        (select top 1
        VenFactura.M_MontoTotalFactura
        from VenFactura
        where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
        and VenFactura.VenCajaId = VenCaja.Id
        order by VenFactura.FechaDocumento asc) as PrimerMonto,
        --PrimeraHora
        (select top 1
        VenFactura.FechaDocumento
        from VenFactura
        where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
        and VenFactura.VenCajaId = VenCaja.Id
        order by VenFactura.FechaDocumento asc) as PrimeraHora,
        --UltimoMonto
        (select top 1
        VenFactura.M_MontoTotalFactura
        from VenFactura
        where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
        and VenFactura.VenCajaId = VenCaja.Id
        order by VenFactura.FechaDocumento desc) as UltimoMonto,
        --UltimaHora
        (select top 1
        VenFactura.FechaDocumento
        from VenFactura
        where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
        and VenFactura.VenCajaId = VenCaja.Id
        order by VenFactura.FechaDocumento desc) as UltimaHora
        from Vencaja
        order by VenCaja.Id asc
        ";
        return $sql;
    }
    */

    function R32Q_resumen_caja($FInicial,$FFinal){
        $sql = "SELECT
        AUX.IdCaja,
        AUX.Caja,
        ( ISNULL(AUX.UnidadesSinDev,0) - ISNULL(AUX.UnidadesDev,0) ) as Unidades,
        ( ISNULL(AUX.FrecuenciaSinDev,0) - ISNULL(AUX.FrecuenciaDev,0) ) as Frecuencia,
        ( ISNULL(AUX.TotalSinDev,0) - ISNULL(AUX.TotalDev,0) ) as Total
        from
        (
            select
            VenCaja.Id as IdCaja,
            VenCaja.EstacionTrabajo as Caja,

            (select
                SUM(VenFacturaDetalle.Cantidad)
                from VenFactura
                LEFT JOIN VenFacturaDetalle on VenFactura.Id = VenFacturaDetalle.VenFacturaId
                where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
                and VenFactura.VenCajaId = VenCaja.Id
            ) as UnidadesSinDev,

            (select
                COUNT(VenFactura.Id)
                from VenFactura
                where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
                and VenFactura.VenCajaId = VenCaja.Id
            ) as FrecuenciaSinDev,

            (select
                SUM(VenFactura.M_MontoTotalFactura)
                from VenFactura
                where VenFactura.FechaDocumento > '$FInicial' and VenFactura.FechaDocumento < '$FFinal'
                and VenFactura.VenCajaId = VenCaja.Id
            ) as TotalSinDev,


            (select
                SUM(VenDevolucionDetalle.Cantidad)
                from VenDevolucion
                LEFT JOIN VenDevolucionDetalle on VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
                where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
                and VenDevolucion.VenCajaId = VenCaja.Id
            ) as UnidadesDev,

            (select
                COUNT(VenDevolucion.Id)
                from VenDevolucion
                where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
                and VenDevolucion.VenCajaId = VenCaja.Id
            ) as FrecuenciaDev,

            (select
                SUM(VenDevolucion.M_MontoTotalDevolucion)
                from VenDevolucion
                where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
                and VenDevolucion.VenCajaId = VenCaja.Id
            ) as TotalDev

            from Vencaja
        ) as AUX
        order by AUX.IdCaja asc
        ";
        return $sql;
    }

    /*
        TITULO: R32Q_data_grafico
    */
    function R32Q_data_grafico($FInicial,$FFinal){
        $sql = "
        SELECT
        ((ISNULL(FAC.MontoFactura,CAST(0 AS INT))) - (ISNULL(DEV.MontoDevolucion,CAST(0 AS INT)))) AS Monto,
        ((ISNULL(FAC.UnidadesFactura,CAST(0 AS INT))) - (ISNULL(DEV.UnidadesDevolucion,CAST(0 AS INT)))) as Unidades,
        ((ISNULL(FAC.TransaccionesFactura,CAST(0 AS INT))) - (ISNULL(DEV.TransaccionesDevolucion,CAST(0 AS INT)))) as Transacciones,
        concat(FAC.hora,'00:00') as Hora
        FROM
        (select
        SUM(MontoFactura) AS MontoFactura,
        SUM(Unidades) AS UnidadesFactura,
        SUM(Transacciones) AS TransaccionesFactura,
        convert(char(2), FechaDocumento, 108) as hora
        from
            (SELECT
            VenFactura.Id,
            (VenFactura.M_MontoTotalFactura) AS MontoFactura,
            ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
            COUNT(DISTINCT VenFactura.Id) as Transacciones,
            VenFactura.FechaDocumento as FechaDocumento
            FROM VenFactura
            LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
            WHERE
            (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
            GROUP BY VenFactura.M_MontoTotalFactura,VenFactura.Id,VenFactura.FechaDocumento) AS Factura
        GROUP BY convert(char(2), FechaDocumento, 108)
        ) AS FAC
        LEFT JOIN
        (select
        SUM(MontoDevolucion) AS MontoDevolucion,
        SUM(Unidades) AS UnidadesDevolucion,
        SUM(Transacciones) AS TransaccionesDevolucion,
        convert(char(2), FechaDocumento, 108) as hora
            from
            (SELECT
            VenDevolucion.Id,
            (VenDevolucion.M_MontoTotalDevolucion) AS MontoDevolucion,
            ((ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
            COUNT(DISTINCT VenFactura.Id) as Transacciones,
            VenDevolucion.FechaDocumento as FechaDocumento
            FROM VenDevolucion
            LEFT JOIN VenDevolucionDetalle ON VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id
            LEFT JOIN VenFactura ON VenFactura.Id = VenDevolucion.VenFacturaId
            WHERE
            (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
            GROUP BY VenDevolucion.M_MontoTotalDevolucion,VenDevolucion.Id,VenDevolucion.FechaDocumento) AS Devolucion
        GROUP BY convert(char(2), FechaDocumento, 108)
        ) AS DEV ON DEV.hora = FAC.hora
        ORDER BY FAC.hora
        ";
        return $sql;
    }

    function R32Q_devolucion_caja($FInicial,$FFinal){
        $sql = "SELECT
        VenCaja.Id as IdCaja,
        VenCaja.EstacionTrabajo as Caja,
        (select
            SUM(VenDevolucionDetalle.Cantidad)
            from VenDevolucion
            LEFT JOIN VenDevolucionDetalle on VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
            where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
            and VenDevolucion.VenCajaId = VenCaja.Id
        ) as UnidadesDev,
        (select
            COUNT(VenDevolucion.Id)
            from VenDevolucion
            where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
            and VenDevolucion.VenCajaId = VenCaja.Id
        ) as FrecuenciaDev,
        (select
            SUM(VenDevolucion.M_MontoTotalDevolucion)
            from VenDevolucion
            where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
            and VenDevolucion.VenCajaId = VenCaja.Id
        ) as TotalDev
        from Vencaja
        order by IdCaja asc
        ";
        return $sql;
    }

    function R32Q_devolucion_causa($FInicial,$FFinal){
        $sql = "SELECT
        AUX1.causaID,
        AUX1.DescripcionOperacion as DescripcionOperacion,
        SUM(AUX1.Cantidad) as Cantidad,
        SUM(VenDevolucion.M_MontoTotalDevolucion) as Total
        from (
                SELECT
                VenCausaOperacionDevolucion.Id as causaID,
                VenCausaOperacionDevolucion.DescripcionOperacion,
                AUX.VenDevolucionId as DevolucionID,
                SUM(AUX.Cantidad) AS Cantidad
                FROM (
                    SELECT
                    VenDevolucionDetalle.VenDevolucionId,
                    VenDevolucionDetalle.Cantidad,
                    VenDevolucionDetalle.VenCausaOperacionId
                    from VenDevolucion
                    LEFT JOIN VenDevolucionDetalle on VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
                    where VenDevolucion.FechaDocumento > '$FInicial' and VenDevolucion.FechaDocumento < '$FFinal'
                ) as AUX
            left join VenCausaOperacionDevolucion on AUX.VenCausaOperacionId = VenCausaOperacionDevolucion.Id
            group by AUX.VenDevolucionId, AUX.VenCausaOperacionId, VenCausaOperacionDevolucion.DescripcionOperacion, VenCausaOperacionDevolucion.Id
            ) as AUX1
            left join VenDevolucion on AUX1.DevolucionID = VenDevolucion.Id
        group by AUX1.causaID, AUX1.DescripcionOperacion
        order by AUX1.causaID asc
        ";
        return $sql;
    }
?>

@section('scriptsFoot')
<script src="{{asset('assets/chart.js/Chart.min.js')}}"></script>

<?php
    if(isset($MontoArray) && !empty($MontoArray) && isset($HoraArray) && !empty($HoraArray)){
?>
    <script type="text/javascript">
        ArrMonto = eval(<?php echo json_encode($MontoArray) ?>);
        ArrHora = eval(<?php echo json_encode($HoraArray) ?>);

        var VxH = document.getElementById("VentasxHora");
        var VentasxHora = new Chart(VxH, {
        type: 'bar',
        data: {
        labels: ArrHora,
        datasets: [{
            label: 'VENTAS',
            data: ArrMonto,
            backgroundColor : 'rgba(78, 115, 223, 0.7)',
            borderColor :  '#4e73df',
            borderWidth: 1,
        }],
        },
        options: {
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    stacked: true
                }]
            },
            legend: {
                display: true
            }
        },
    });
    </script>
<?php
    }
?>


<?php
    if(isset($TransaccionesArray) && !empty($TransaccionesArray) && isset($HoraArray) && !empty($HoraArray)){
?>
    <script>
        ArrTransacciones = eval(<?php echo json_encode($TransaccionesArray) ?>);
        ArrHora = eval(<?php echo json_encode($HoraArray) ?>);

        var TxH = document.getElementById("TransaccionesxHora");
        var TransaccionesxHora = new Chart(TxH, {
            type: 'line',
            data: {
            labels: ArrHora,
            datasets: [{
                label: 'TRANSACCIONES',
                data: ArrTransacciones,
                backgroundColor : 'rgba(0, 0, 0, 0.0)',
                borderColor :  '#34ebc0',
                borderWidth: 4,
            }],
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                },
                legend: {
                    display: true
                },
                elements: {
                    line: {
                        tension: 0,
                    }
                },
            },
        });
    </script>
<?php
    }
?>

<?php
    if(isset($UnidadesArray) && !empty($UnidadesArray) && isset($HoraArray) && !empty($HoraArray)){
?>
    <script>
        ArrUnidades = eval(<?php echo json_encode($UnidadesArray) ?>);
        ArrHora = eval(<?php echo json_encode($HoraArray) ?>);

        var UxH = document.getElementById("UnidadesxHora");
        var UnidadesxHora = new Chart(UxH, {
            type: 'line',
            data: {
            labels: ArrHora,
            datasets: [{
                label: 'UNIDADES',
                data: ArrUnidades,
                backgroundColor : 'rgba(0, 0, 0, 0.0)',
                borderColor :  '#eb9634',
                borderWidth: 4,
            }],
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                },
                legend: {
                    display: true
                },
                elements: {
                    line: {
                        tension: 0,
                    }
                },
            },
        });
    </script>
<?php
    }
?>

<?php
    if(
        (isset($UnidadesArray) && !empty($UnidadesArray) && isset($HoraArray) && !empty($HoraArray))
        &&(isset($TransaccionesArray) && !empty($TransaccionesArray) && isset($HoraArray) && !empty($HoraArray))
        &&(isset($TransaccionesArray) && !empty($TransaccionesArray) && isset($HoraArray) && !empty($HoraArray))
    ){
?>
    <script>
        ArrMonto = eval(<?php echo json_encode($MontoArray) ?>);
        ArrTransacciones = eval(<?php echo json_encode($TransaccionesArray) ?>);
        ArrUnidades = eval(<?php echo json_encode($UnidadesArray) ?>);
        ArrHora = eval(<?php echo json_encode($HoraArray) ?>);

        var MxH = document.getElementById("MixtoxHora");
        var mixedChart = new Chart(MxH, {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'VENTAS',
                    data: ArrMonto,
                    backgroundColor : 'rgba(78, 115, 223, 0.5)',
                    borderColor :  '#4e73df',
                    borderWidth: 4,
                    yAxisID: 'left-y-axis',
                },{
                    label: 'TRANSACCIONES',
                    data: ArrTransacciones,
                    backgroundColor : 'rgba(0, 0, 0, 0.0)',
                    borderColor :  '#34ebc0',
                    borderWidth: 4,
                    type: 'line',
                    yAxisID: 'top-y-axis',
                },{
                    label: 'UNIDADES',
                    data: ArrUnidades,
                    backgroundColor : 'rgba(0, 0, 0, 0.0)',
                    borderColor :  '#eb9634',
                    borderWidth: 4,
                    type: 'line',
                    yAxisID: 'right-y-axis',
                }],
                labels: ArrHora,
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        id: 'left-y-axis',
                        type: 'linear',
                        position: 'left',
                    }, {
                        id: 'top-y-axis',
                        type: 'linear',
                        position: 'right',
                    },{
                        id: 'right-y-axis',
                        type: 'linear',
                        position: 'right',
                    }]
                },
                legend: {
                    display: true
                },
                elements: {
                    line: {
                        tension: 0,
                    }
                },
            },
        });
    </script>
<?php
    }
?>
@endsection
