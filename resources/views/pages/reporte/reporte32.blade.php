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
        
        <?php
        $hoy = date('Y-m-d');
        if(isset($_GET['Fecha'])){
            if($_GET['Fecha']=='AYER'){
                $fecha = date("Y-m-d",strtotime($hoy."- 1 days"));
            }
            else if($_GET['Fecha']=='HOY'){
                $fecha = $hoy;
            }
        }else{
            $fecha = $hoy;
        }

            R32_Seguimiento_Tienda($_GET['SEDE'],$fecha);
            FG_Guardar_Auditoria('CONSULTAR','REPORTE','Seguimiento de Tienda');

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
        //$FInicial = '2020-12-01';
        $FFinal = date("Y-m-d",strtotime($FInicial."+ 1 days"));
        $TasaActual = FG_Tasa_Fecha($connCPharma,$FInicial);
        //$TasaActual = 1800000.00;

		$sql = R32Q_Venta_Articulos(5,$FInicial,$FFinal,'TotalVenta');
        $result = sqlsrv_query($conn,$sql);          
        
        echo '<hr class="row align-items-start col-12">';        
        echo '<h1 class="h5 text-dark" align="left">Ventas por Articulo</h1>';

		echo '		
		<table class="table table-striped table-bordered col-12 sortable" style="width:60%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Articulo</th>
                    <th scope="col" class="CP-sticky">'.SigVe.'</th>
                    <th scope="col" class="CP-sticky">'.SigDolar.'</th>
                    <th scope="col" class="CP-sticky">Cantidad</th>
                    <th scope="col" class="CP-sticky">Frecuencia</th>
                </tr>
            </thead>
            <tbody>
        ';
        $contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {					
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row['Descripcion']).'</td>';
            echo '<td align="center">'.number_format($row['TotalVenta'],2,"," ,"." ).'</td>';
            
            if(isset($TasaActual)&&$TasaActual!=0){
                echo '<td align="center">'.number_format(($row['TotalVenta']/$TasaActual),2,"," ,"." ).'</td>';        			
            }else{
                echo '<td align="center">0,00</td>';
            }
            
            echo '<td align="center">'.intval($row['TotalUnidadesVendidas']).'</td>';
            echo '<td align="center">'.intval($row['TotalVecesVendidas']).'</td>';
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		    </tbody>
        </table>';
        
        echo '		
		<table class="table table-striped table-bordered col-12 sortable" style="width:60%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Articulo</th>
                    <th scope="col" class="CP-sticky">'.SigVe.'</th>
                    <th scope="col" class="CP-sticky">'.SigDolar.'</th>
                    <th scope="col" class="CP-sticky">Cantidad</th>
                    <th scope="col" class="CP-sticky">Frecuencia</th>
                </tr>
            </thead>
            <tbody>
        ';

        $sql1 = R32Q_Venta_Articulos(5,$FInicial,$FFinal,'TotalUnidadesVendidas');
        $result1 = sqlsrv_query($conn,$sql1); 

        $contador = 1;
		while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {					
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row1['Descripcion']).'</td>';
            echo '<td align="center">'.number_format($row1['TotalVenta'],2,"," ,"." ).'</td>';
            
            if(isset($TasaActual)&&$TasaActual!=0){
                echo '<td align="center">'.number_format(($row1['TotalVenta']/$TasaActual),2,"," ,"." ).'</td>';       			
            }else{
                echo '<td align="center">0,00</td>';
            }

            echo '<td align="center">'.intval($row1['TotalUnidadesVendidas']).'</td>';
            echo '<td align="center">'.intval($row1['TotalVecesVendidas']).'</td>';
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		    </tbody>
        </table>';
        
        echo '<hr class="row align-items-start col-12">';        
        echo '<h1 class="h5 text-dark" align="left">Ventas por Clientes</h1>';
        
        echo '		
		<table class="table table-striped table-bordered col-12 sortable" style="width:60%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">'.SigVe.'</th>
                    <th scope="col" class="CP-sticky">'.SigDolar.'</th>
                    <th scope="col" class="CP-sticky">%</th>
                    <th scope="col" class="CP-sticky">Trasacciones</th>
                    <th scope="col" class="CP-sticky">%</th>
                    <th scope="col" class="CP-sticky">Unidades</th>
                    <th scope="col" class="CP-sticky">%</th>
                </tr>
            </thead>
            <tbody>
        ';

        $sql2 = R32Q_Vent_Cli_Nue_Rec($FInicial,$FFinal);
        $result2 = sqlsrv_query($conn,$sql2);
        
        $Sum_Monto_Nue = $Sum_Trans_Nue =  $Sum_Unid_Nue = 0;
        $Sum_Monto_Rec = $Sum_Trans_Rec =  $Sum_Unid_Rec = 0;

		while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
            if($row2['TipoCliente']=='Nuevo'){
                $Sum_Monto_Nue += $row2['MontoFactura'];
                $Sum_Unid_Nue += $row2['Unidades'];
                $Sum_Trans_Nue += $row2['Transacciones'];
            }else{
                $Sum_Monto_Rec += $row2['MontoFactura'];
                $Sum_Unid_Rec += $row2['Unidades'];
                $Sum_Trans_Rec += $row2['Transacciones'];
            }
          }
          	  	
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
		<table class="table table-striped table-bordered col-12 sortable" style="width:60%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">'.SigVe.'</th>
                    <th scope="col" class="CP-sticky">'.SigDolar.'</th>
                    <th scope="col" class="CP-sticky">%</th>
                    <th scope="col" class="CP-sticky">Trasacciones</th>
                    <th scope="col" class="CP-sticky">%</th>
                    <th scope="col" class="CP-sticky">Unidades</th>
                    <th scope="col" class="CP-sticky">%</th>
                </tr>
            </thead>
            <tbody>
        ';

        $sql3 = R32Q_Vent_Cli_Nat_Jur($FInicial,$FFinal);
        $result3 = sqlsrv_query($conn,$sql3);
        
        $Sum_Monto_Nat = $Sum_Trans_Nat =  $Sum_Unid_Nat = 0;
        $Sum_Monto_Jur = $Sum_Trans_Jur =  $Sum_Unid_Jur = 0;

		while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
            if($row3['tipoPersona']=='1'){
                $Sum_Monto_Nat += $row3['MontoFactura'];
                $Sum_Unid_Nat += $row3['Unidades'];
                $Sum_Trans_Nat += $row3['Transacciones'];
            }else{
                $Sum_Monto_Jur += $row3['MontoFactura'];
                $Sum_Unid_Jur += $row3['Unidades'];
                $Sum_Trans_Jur += $row3['Transacciones'];
            }
          }
          	  	
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
		<table class="table table-striped table-bordered col-12 sortable" style="width:60%;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Nombre</th>
                    <th scope="col" class="CP-sticky">'.SigVe.'</th>
                    <th scope="col" class="CP-sticky">'.SigDolar.'</th>
                    <th scope="col" class="CP-sticky">Trasacciones</th>
                    <th scope="col" class="CP-sticky">Unidades</th>                     
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
        TITULO: R32Q_Vent_Cli_Nue_Rec
    */    
    function R32Q_Vent_Cli_Nue_Rec($FInicial,$FFinal){
        $sql = "
        --Clientes Nuevos y Recurrentes
        SELECT
        IIF( CONVERT(date,GenPersona.Auditoria_FechaCreacion) = '$FInicial', 'Nuevo' ,'Recurrente') as TipoCliente,
        SUM(VenFactura.M_MontoTotalFactura) AS MontoFactura,
        COUNT(DISTINCT VenFactura.Id) as Transacciones,
        ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades
        FROM VenFactura
        LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY TipoCliente,CONVERT(date,GenPersona.Auditoria_FechaCreacion)
        ";
        return $sql;
    }
    /*
        TITULO: R32Q_Vent_Cli_Nat_Jur
    */
    function R32Q_Vent_Cli_Nat_Jur($FInicial,$FFinal){
        $sql = "
        --Clientes por Tipo Persona
        SELECT
        GenPersona.tipoPersona,
        SUM(VenFactura.M_MontoTotalFactura) AS MontoFactura,
        COUNT(DISTINCT VenFactura.Id) as Transacciones,
        ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades
        FROM VenFactura
        LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY GenPersona.tipoPersona
        ";
        return $sql;
    }
     /*
        TITULO: R32Q_Vent_Cli_Top
    */ 
    function R32Q_Vent_Cli_Top($FInicial,$FFinal){
        $sql = "
        --Cliente por Monto Factura
        SELECT top 5
        VenCliente.id as IdCliente,
        GenPersona.Nombre,
        GenPersona.Apellido,
        SUM(VenFactura.M_MontoTotalFactura) AS MontoFactura,
        ((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0))) as Unidades,
        COUNT(DISTINCT VenFactura.Id) as Transacciones
        FROM VenFactura
        LEFT JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
        LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
        LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
        WHERE
        (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
        GROUP BY GenPersona.Nombre,GenPersona.Apellido,VenCliente.id
        ORDER BY MontoFactura DESC
        ";
        return $sql;
    }
?>