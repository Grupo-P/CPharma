<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/sobrestock', function () {
  $InicioCarga = new DateTime("now");

  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  
  $conn = FG_Conectar_Smartpharma($_GET['SEDE']);
  $connCPharma = FG_Conectar_CPharma();

  function R31Q_Total_Venta($IdArticulo,$FInicial,$FFinal) {
    $sql = "
      SELECT
    -- Id Articulo
      VenFacturaDetalle.InvArticuloId,
    --Veces Vendidas (En Rango)
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
    --Unidades Vendidas (En Rango)
      (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas,
    --Veces Devueltas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesDevueltas,
    --Unidades Devueltas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesDevueltas,
    --Total Veces Vendidas (En Rango)
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
      AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) AS SubTotalVenta,
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
      AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))
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
    --Condicionales
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      AND VenFacturaDetalle.InvArticuloId = '$IdArticulo'
    --Agrupamientos
      GROUP BY VenFacturaDetalle.InvArticuloId 
    --Ordenamientos
      ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }

  function R31Q_Articulos_Sobre_Stock($FInicial,$FFinal, $sobreStock) {
      $FInicial = str_replace('-', '', $FInicial);
      $FFinal = str_replace('-', '', $FFinal);

      $sql = "
        SELECT
          (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS id_articulo,
          (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS codigo_articulo,
          (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = ComFacturaDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
          (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS descripcion,
          (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS fecha_registro,
          ComFacturaDetalle.FechaVencimiento AS fecha_vencimiento,
          ComFacturaDetalle.CantidadRecibidaFactura AS cantidad_recibida,
          (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) AS existencia,
          (SELECT ComFactura.Auditoria_Usuario FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS operador,
          (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS proveedor,
          (SELECT SUM(VenFacturaDetalle.Cantidad) FROM VenFacturaDetalle WHERE VenFacturaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId AND VenFacturaDetalle.VenFacturaId IN (SELECT VenFactura.Id FROM VenFactura WHERE VenFactura.FechaDocumento BETWEEN DATEADD(DAY, -30, (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId)) AND (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS ventas_totales,
          (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS numero_factura,
          (SELECT VenVenta.Auditoria_FechaCreacion FROM VenVenta WHERE VenVenta.Id = (SELECT TOP 1 VenVentaDetalle.VenVentaId FROM VenVentaDetalle WHERE VenVentaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId ORDER BY VenVentaDetalle.VenVentaId DESC)) AS ultima_venta
        FROM
            ComFacturaDetalle
        WHERE
            ComFacturaDetalle.ComFacturaId IN (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal') AND 
            (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) > ComFacturaDetalle.CantidadRecibidaFactura
        ORDER BY (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId)
      ";

      return $sql;
   }

   $sql8 = R31Q_Articulos_Sobre_Stock($_GET['fechaInicio'], $_GET['fechaFin'], $_GET['sobreStock']);
   $result8 = sqlsrv_query($conn,$sql8);

   $resultado['resultado'] = [];

   $i = 0;
   $contador = 1;

   while ($row = sqlsrv_fetch_array($result8, SQLSRV_FETCH_ASSOC)) {
    $codigo = $row['codigo_articulo'];
      $codigo_barra = $row['codigo_barra'];
      $descripcion = FG_Limpiar_Texto($row['descripcion']);
      $fecha_registro = $row['fecha_registro'] ? $row['fecha_registro']->format('d/m/Y') : '';
      $fecha_vencimiento = $row['fecha_vencimiento'] ? $row['fecha_vencimiento']->format('d/m/Y') : '';
      $cantidad_recibida = intval($row['cantidad_recibida']);
      $existencia = intval($row['existencia']);
      $operador = $row['operador'];
      $id_articulo = $row['id_articulo'];
      $proveedor = FG_Limpiar_Texto($row['proveedor']);
      $numero_factura = $row['numero_factura'];
      $ultima_venta = ($row['ultima_venta']) ? $row['ultima_venta']->format('d/m/Y') : '-';

      $fechaInicioDiasCero = $row['fecha_registro']->format('Y-m-d');
      $fechaInicioDiasCero = date_modify(date_create($fechaInicioDiasCero), '-30day');
      $fechaInicioDiasCero = date_format($fechaInicioDiasCero, 'Y-m-d');

      $sql2 = MySQL_Cuenta_Veces_Dias_Cero($id_articulo,$fechaInicioDiasCero,$_GET['fechaInicio']);
      $result2 = mysqli_query($connCPharma,$sql2);
      $row2 = $result2->fetch_assoc();
      $RangoDiasQuiebre = $row2['Cuenta'];

      $sql3 = R31Q_Total_Venta($id_articulo, $fechaInicioDiasCero, $row['fecha_registro']->format('Y-m-d'));

      $result3 = sqlsrv_query($conn,$sql3);

      $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

      $VentaDiariaQuiebre = FG_Venta_Diaria($row3['TotalUnidadesVendidas'],$RangoDiasQuiebre);
      $DiasRestantesQuiebre = FG_Dias_Restantes($existencia,$VentaDiariaQuiebre);

      if ($DiasRestantesQuiebre >= $_GET['sobreStock']) {
        $resultado['resultado'][$i]['contador'] = $contador;
        $resultado['resultado'][$i]['tipo_movimiento'] = 'Compras';
        $resultado['resultado'][$i]['codigo'] = $codigo;
        $resultado['resultado'][$i]['codigo_barra'] = $codigo_barra;
        $resultado['resultado'][$i]['descripcion'] = '<a href="/reporte2?Id='.$id_articulo.'&SEDE='.$_GET['SEDE'].'" style="text-decoration: none; color: black;" target="_blank">'.$descripcion.'</a>';
        $resultado['resultado'][$i]['fecha_registro'] = $fecha_registro;
        $resultado['resultado'][$i]['fecha_vencimiento'] = $fecha_vencimiento;
        $resultado['resultado'][$i]['dias_restantes'] = round($DiasRestantesQuiebre,2);
        $resultado['resultado'][$i]['cantidad_recibida'] = $cantidad_recibida;
        $resultado['resultado'][$i]['existencia'] = $existencia;
        $resultado['resultado'][$i]['operador'] = $operador;
        $resultado['resultado'][$i]['proveedor'] = $proveedor;
        $resultado['resultado'][$i]['numero_factura'] = $numero_factura;
        $resultado['resultado'][$i]['ultima_venta'] = $ultima_venta;

      $contador++;
      $i++;
      }
   }

   $FinCarga = new DateTime("now");
   $IntervalCarga = $InicioCarga->diff($FinCarga);

   $resultado['tiempo_carga'] = $IntervalCarga->format("%Y-%M-%D %H:%I:%S");

   return $resultado;
});
