<?php

namespace compras\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArticulosVencer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articulos:vencer {mes?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene información para el reporte de artículos por vencer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo date('h:i:s');

        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $meses = $this->argument('mes') ? $this->argument('mes') : 3;
        $FInicial = (date_modify(date_create(), '+'.$meses.'month'))->format('Y-m-d');

        $Inicio = date_modify(date_create(), '-30day');
        $Inicio = $Inicio->format('Y-m-d');
        $Fin = date_create()->format('Y-m-d');

        $sql = "
            SELECT
            -- TotalUnidadesVendidas
            (SELECT (((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
              -
              (ISNULL((SELECT
              (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
              FROM VenDevolucionDetalle
              INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
              WHERE VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
              AND(VenDevolucion.FechaDocumento >= '$Inicio' AND VenDevolucion.FechaDocumento <= '$Fin')
              GROUP BY VenDevolucionDetalle.InvArticuloId
              ),CAST(0 AS INT)))) FROM VenFacturaDetalle WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id AND VenFacturaDetalle.VenFacturaId IN (SELECT VenFactura.Id FROM VenFactura WHERE VenFactura.FechaDocumento >= '$Inicio' AND VenFactura.FechaDocumento <= '$Fin')) AS TotalUnidadesVendidas,
            --Id Articulo
            InvArticulo.Id AS IdArticulo,
            --Categoria Articulo
            InvArticulo.InvCategoriaId,
            --Codigo Interno
            InvArticulo.CodigoArticulo AS CodigoInterno,
            --Codigo de Barra
            (SELECT CodigoBarra
            FROM InvCodigoBarra
            WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
            AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
            --Descripcion
            InvArticulo.Descripcion,
            --Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
            (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
            --Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Troquelados'
            OR  InvAtributo.Descripcion = 'troquelados')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
            --UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
            ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
            FROM VenCondicionVenta
            WHERE VenCondicionVenta.Id = (
            SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
            FROM VenCondicionVenta_VenCondicionVentaArticulo
            WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
            --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
            ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
            FROM VenCondicionVenta
            WHERE VenCondicionVenta.id = (
            SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
            FROM VenCondicionVenta_VenCondicionVentaCategoria
            WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
            --Precio Troquel Almacen 1
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioTroquelado
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE(InvLoteAlmacen.InvAlmacenId = '1')
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
            --Precio Compra Bruto Almacen 1
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioCompraBruto
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            AND (InvLoteAlmacen.InvAlmacenId = '1')
            ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
            --Precio Troquel Almacen 2
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioTroquelado
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE(InvLoteAlmacen.InvAlmacenId = '2')
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
            --Precio Compra Bruto Almacen 2
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioCompraBruto
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            AND (InvLoteAlmacen.InvAlmacenId = '2')
            ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
            --Precio Compra Bruto
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioCompraBruto
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
            --Existencia (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
            --ExistenciaAlmacen1 (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
            --ExistenciaAlmacen2 (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
            --ExistenciaLote (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND InvLoteAlmacen.InvLoteId = InvLote.Id) AS DECIMAL(38,0)),2,0))  AS ExistenciaLote,
            --Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Dolarizados'
            OR  InvAtributo.Descripcion = 'Giordany'
            OR  InvAtributo.Descripcion = 'giordany')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
            --Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Medicina')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
            --Articulo Estrella (0 NO es Articulo Estrella , Id SI es Articulo Estrella)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Articulo Estrella')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS ArticuloEstrella,
            -- Ultima Venta (Fecha)
            (SELECT TOP 1
            CONVERT(DATE,VenFactura.FechaDocumento)
            FROM VenFactura
            INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
            WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY FechaDocumento DESC) AS UltimaVenta,
            --Tiempo sin Venta (En dias)
            (SELECT TOP 1
            DATEDIFF(DAY,CONVERT(DATE,VenFactura.FechaDocumento),GETDATE())
            FROM VenFactura
            INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
            WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY FechaDocumento DESC) AS TiempoSinVenta,
            --Ultimo Lote (Fecha)
            (SELECT TOP 1
            CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
            FROM InvLote
            WHERE InvLote.InvArticuloId  = InvArticulo.Id
            ORDER BY UltimoLote DESC) AS UltimoLote,
            -- Ultima Compra (Fecha de ultima compra)
            (SELECT TOP 1
            CONVERT(DATE,ComFactura.FechaRegistro)
            FROM ComFacturaDetalle
            INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
            INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
            WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY ComFactura.FechaRegistro DESC) AS  UltimaCompra,
            --Tiempo Tienda (En dias)
            (SELECT TOP 1
            DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
            FROM InvLoteAlmacen
            INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
            WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
            ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
            --Ultimo Proveedor (Id Proveedor)
            (SELECT TOP 1
            ComProveedor.Id
            FROM ComFacturaDetalle
            INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
            INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
            INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
            WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
            --Ultimo Proveedor (Nombre Proveedor)
            (SELECT TOP 1
            GenPersona.Nombre
            FROM ComFacturaDetalle
            INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
            INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
            INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
            WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
            InvLote.Auditoria_FechaCreacion as FechaLote,
            InvLote.FechaVencimiento as FechaVencimiento,
            InvLote.Numero,
            InvLote.LoteFabricante,
            InvLote.Id as LoteId
            FROM InvLote
            INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvLoteId = InvLote.Id
            INNER JOIN InvArticulo ON InvArticulo.Id = InvLoteAlmacen.InvArticuloId
            WHERE InvLote.FechaVencimiento < '$FInicial'
            AND InvLote.FechaVencimiento <> ''
            AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND InvLoteAlmacen.Existencia > 0
            ORDER BY InvLote.FechaVencimiento DESC
        ";

        $result = sqlsrv_query($conn,$sql,[],['QueryTimeout'=>7200]);

        DB::table('articulos_vencer')->truncate();

        $connCPharma = FG_Conectar_CPharma();
        $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));

        $conectividad_ftn = FG_Validar_Conectividad('FTN');
        $conectividad_fau = FG_Validar_Conectividad('FAU');
        $conectividad_fll = FG_Validar_Conectividad('FLL');
        $conectividad_fsm = FG_Validar_Conectividad('FSM');
        $conectividad_fec = FG_Validar_Conectividad('FEC');
        $conectividad_flf = FG_Validar_Conectividad('FLF');

        $connFAU = FG_Conectar_Smartpharma('FAU');
        $connFTN = FG_Conectar_Smartpharma('FTN');
        $connFLL = FG_Conectar_Smartpharma('FLL');
        $connFSM = FG_Conectar_Smartpharma('FSM');
        $connFEC = FG_Conectar_Smartpharma('FEC');
        $connFLF = FG_Conectar_Smartpharma('FLF');

        while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
            $Existencia = $row["Existencia"];
            $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
            $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
            $IsTroquelado = $row["Troquelado"];
            $IsIVA = $row["Impuesto"];
            $UtilidadArticulo = $row["UtilidadArticulo"];
            $UtilidadCategoria = $row["UtilidadCategoria"];
            $TroquelAlmacen1 = $row["TroquelAlmacen1"];
            $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
            $TroquelAlmacen2 = $row["TroquelAlmacen2"];
            $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
            $PrecioCompraBruto = $row["PrecioCompraBruto"];
            $CondicionExistencia = 'CON_EXISTENCIA';
            $IdArticulo = $row["IdArticulo"];

            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

            $ExistenciaLote = $row["ExistenciaLote"];
            $fechaInicioDiasCero = date_modify(date_create(), '-30day');
            $fechaInicioDiasCero = date_format($fechaInicioDiasCero, 'Y-m-d');
            $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$fechaInicioDiasCero,date_create()->format('Y-m-d'));
            $result22 = mysqli_query($connCPharma,$sql2);
            $row2 = $result22->fetch_assoc();
            $RangoDiasQuiebre = $row2['Cuenta'];
            $VentaDiaria = FG_Venta_Diaria($row['TotalUnidadesVendidas'], $RangoDiasQuiebre);

            $DiasRestantes30 = FG_Dias_Restantes(intval($ExistenciaLote), $VentaDiaria);
            $diasVencer = FG_Validar_Fechas(date('Y-m-d H:i:s'),$row["FechaVencimiento"]->format('d-m-Y'));
            $diasRiesgo = ($diasVencer-$DiasRestantes30);

            $UltimaCompra = !is_null($row["UltimaCompra"]) ? $row["UltimaCompra"]->format('d-m-Y') : '-';

            $vidaUtil = FG_Rango_Dias($row["FechaVencimiento"]->format('d-m-Y'),$row["FechaLote"]->format('d-m-Y'));

            $precioLoteVE = intval($row["ExistenciaLote"]) * $Precio;

            if ($TasaActual!=0) {
                $PrecioDolar = $Precio/$TasaActual;
                $precioLoteDolar = intval($row["ExistenciaLote"]) * $PrecioDolar;
            } else {
                $precioLoteDolar = 0;
            }

            $Tipo = FG_Tipo_Producto($row["Tipo"]);

            $Dolarizado = $row["Dolarizado"];
            $Dolarizado = FG_Producto_Dolarizado($Dolarizado);

            $Gravado = FG_Producto_Gravado($IsIVA);

            $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
            $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
            $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
            $clasificacion = $RowCPharma['clasificacion'];
            $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

            $loteFabricante = $row["LoteFabricante"] ? $row["LoteFabricante"] : '-';

            $ultima_venta = $row["UltimaVenta"]!=null ? $row["UltimaVenta"]->format('d-m-Y') : '-';

            $CodigoBarra = $row['CodigoBarra'];

            $descripcion_sede_1 = '-';
            $descripcion_sede_2 = '-';
            $descripcion_sede_3 = '-';
            $descripcion_sede_4 = '-';
            $descripcion_sede_5 = '-';

            $existencia_sede_1 = '-';
            $existencia_sede_2 = '-';
            $existencia_sede_3 = '-';
            $existencia_sede_4 = '-';
            $existencia_sede_5 = '-';

            if (isset($SedeConnection) & ($SedeConnection == 'FAU' || $SedeConnection == 'DBs')) {
                if ($conectividad_ftn == 1) {
                  $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                  $result3 = sqlsrv_query($connFTN,$sql3);

                  $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                  $descripcion_sede_1 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                  $existencia_sede_1 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_fll == 1) {
                  $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                  $result3 = sqlsrv_query($connFLL,$sql3);
                  $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                  $descripcion_sede_2 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                  $existencia_sede_2 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_fsm == 1) {
                  $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                  $result3 = sqlsrv_query($connFSM,$sql3);
                  $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                  $descripcion_sede_3 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                  $existencia_sede_3 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_fec == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFEC,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_4 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_4 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_flf == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFLF,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_5 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_5 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

            }

            if (isset($SedeConnection) & $SedeConnection == 'FTN') {
                if ($conectividad_fau == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFAU,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_1 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_1 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_fll == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFLL,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_2 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_2 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_fsm == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFSM,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_3 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_3 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_fec == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFEC,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_4 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_4 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }

                if ($conectividad_flf == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFLF,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_5 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_5 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }
            }

            if (isset($SedeConnection) & $SedeConnection == 'FLL') {
                 if ($conectividad_ftn == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFTN,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_1 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_1 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }

                  if ($conectividad_fau == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFAU,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_2 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_2 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }

                  if ($conectividad_fsm == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFSM,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_3 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_3 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }

                    if ($conectividad_fec == 1) {
                        $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                        $result3 = sqlsrv_query($connFEC,$sql3);
                        $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                        $descripcion_sede_4 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                        $existencia_sede_4 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                    }

                    if ($conectividad_flf == 1) {
                        $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                        $result3 = sqlsrv_query($connFLF,$sql3);
                        $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                        $descripcion_sede_5 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                        $existencia_sede_5 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                    }
              }

              if (isset($SedeConnection) & $SedeConnection == 'FSM') {
                 if ($conectividad_ftn == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFTN,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_1 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_1 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }

                  if ($conectividad_fau == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFAU,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_2 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_2 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }

                  if ($conectividad_fll == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFLL,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_3 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_3 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }

                    if ($conectividad_fec == 1) {
                        $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                        $result3 = sqlsrv_query($connFEC,$sql3);
                        $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                        $descripcion_sede_4 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                        $existencia_sede_4 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                    }

                    if ($conectividad_flf == 1) {
                        $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                        $result3 = sqlsrv_query($connFLF,$sql3);
                        $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                        $descripcion_sede_5 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                        $existencia_sede_5 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                    }
              }

              if (isset($SedeConnection) & $SedeConnection == 'FEC') {
                if ($conectividad_ftn == 1) {
                   $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                   $result3 = sqlsrv_query($connFTN,$sql3);
                   $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                   $descripcion_sede_1 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                   $existencia_sede_1 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                 }

                 if ($conectividad_fau == 1) {
                   $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                   $result3 = sqlsrv_query($connFAU,$sql3);
                   $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                   $descripcion_sede_2 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                   $existencia_sede_2 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                 }

                 if ($conectividad_fll == 1) {
                   $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                   $result3 = sqlsrv_query($connFLL,$sql3);
                   $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                   $descripcion_sede_3 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                   $existencia_sede_3 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                 }

                   if ($conectividad_fsm == 1) {
                       $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                       $result3 = sqlsrv_query($connFSM,$sql3);
                       $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                       $descripcion_sede_4 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                       $existencia_sede_4 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                   }

                   if ($conectividad_flf == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFLF,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_5 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_5 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                }
              }

              if (isset($SedeConnection) & $SedeConnection == 'FLF') {
                if ($conectividad_ftn == 1) {
                   $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                   $result3 = sqlsrv_query($connFTN,$sql3);
                   $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                   $descripcion_sede_1 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                   $existencia_sede_1 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                 }

                 if ($conectividad_fau == 1) {
                   $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                   $result3 = sqlsrv_query($connFAU,$sql3);
                   $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                   $descripcion_sede_2 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                   $existencia_sede_2 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                 }

                 if ($conectividad_fll == 1) {
                   $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                   $result3 = sqlsrv_query($connFLL,$sql3);
                   $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                   $descripcion_sede_3 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                   $existencia_sede_3 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                 }

                   if ($conectividad_fsm == 1) {
                       $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                       $result3 = sqlsrv_query($connFSM,$sql3);
                       $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                       $descripcion_sede_4 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                       $existencia_sede_4 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                   }

                   if ($conectividad_fec == 1) {
                    $sql3 = $this->R27_Q_Descripcion_Existencia_Articulo($CodigoBarra);
                    $result3 = sqlsrv_query($connFEC,$sql3);
                    $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

                    $descripcion_sede_5 = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
                    $existencia_sede_5 = ($row3['existencia']) ? intval($row3['existencia']) : '-';
                  }
              }

            $ultimo_proveedor_nombre = !is_null($row["UltimoProveedorNombre"]) ? $row["UltimoProveedorNombre"] : '-';
            $ultimo_proveedor_id = !is_null($row["UltimoProveedorID"]) ? $row["UltimoProveedorID"] : '-';

            DB::table('articulos_vencer')
                ->insert([
                    'id_articulo' => $IdArticulo,
                    'codigo' => $row['CodigoInterno'],
                    'codigo_barra' => $row['CodigoBarra'],
                    'descripcion' => $row['Descripcion'],
                    'precio_iva_bs' => number_format($Precio,2,"," ,"." ),
                    'dias_restantes_30' => $DiasRestantes30,
                    'dias_riesgo' => $diasRiesgo,
                    'ultima_compra' => $UltimaCompra,
                    'fecha_lote' => $row["FechaLote"]->format('d-m-Y'),
                    'fecha_vencimiento' => $row["FechaVencimiento"]->format('d-m-Y'),
                    'vida_util' => $vidaUtil,
                    'dias_para_vencer' => $diasVencer,
                    'existencia_total' => intval($Existencia),
                    'existencia_lote' => intval($row["ExistenciaLote"]),
                    'valor_lote_bs' => (number_format($precioLoteVE,2,"," ,"." )),
                    'valor_lote_ds' => (number_format($precioLoteDolar,2,"," ,"." )),
                    'numero_lote' => $row["Numero"],
                    'lote_fabricante' => $loteFabricante,
                    'tipo' => $Tipo,
                    'dolarizado' => $Dolarizado,
                    'gravado' => $Gravado,
                    'clasificacion' => $clasificacion,
                    'ultima_venta' => $ultima_venta,
                    'ultimo_proveedor_nombre' => $ultimo_proveedor_nombre,
                    'ultimo_proveedor_id' => $ultimo_proveedor_id,
                    'descripcion_sede_1' => $descripcion_sede_1,
                    'existencia_sede_1' => $existencia_sede_1,
                    'descripcion_sede_2' => $descripcion_sede_2,
                    'existencia_sede_2' => $existencia_sede_2,
                    'descripcion_sede_3' => $descripcion_sede_3,
                    'existencia_sede_3' => $existencia_sede_3,
                    'descripcion_sede_4' => $descripcion_sede_4,
                    'existencia_sede_4' => $existencia_sede_4,
                    'descripcion_sede_5' => $descripcion_sede_5,
                    'existencia_sede_5' => $existencia_sede_5,
                    'created_at' => date('Y-m-d h:i:s'),
                    'updated_At' => date('Y-m-d h:i:s'),
                ]);
        }

        echo "\n";
        echo date('h:i:s');
    }

    public function R27_Q_Descripcion_Existencia_Articulo($CodigoBarra)
    {
        $CodigoBarra = str_replace("'", "''", $CodigoBarra);

        $sql = "
          SELECT
            InvArticulo.DescripcionLarga AS descripcion,
            (SELECT SUM(Existencia) FROM InvLoteAlmacen WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AS existencia
          FROM
          InvArticulo
          WHERE InvArticulo.Id = (SELECT InvCodigoBarra.InvArticuloId FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$CodigoBarra' AND InvCodigoBarra.EsPrincipal = 1)
        ";

        return $sql;
    }
}
