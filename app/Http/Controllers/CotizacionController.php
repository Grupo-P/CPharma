<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function __construct()
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        $this->ubicacion = FG_Mi_Ubicacion();
        $this->conn      = FG_Conectar_Smartpharma($this->ubicacion);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->ajax() && request()->tipo == 'cliente') {
            $buscar = request()->buscar;

            $sql = "
                SELECT
                    CONCAT(GenPersona.Nombre, ' ', GenPersona.Apellido) AS nombre,
                    GenPersona.IdentificacionFiscal AS ci_rif,
                    GenPersona.DireccionCorta AS direccion
                FROM
                    VenCliente LEFT JOIN GenPersona ON VenCliente.GenPersonaId = GenPersona.Id
                WHERE
                    CONCAT(GenPersona.Nombre, ' ', GenPersona.Apellido) LIKE '%$buscar%' OR
                    IdentificacionFiscal LIKE '%$buscar%'
                ORDER BY
                    nombre ASC;
            ";

            $query = sqlsrv_query($this->conn, $sql);

            $i = 0;

            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $clientes[$i]['label']     = $row['nombre'] . ' | ' . $row['ci_rif'];
                $clientes[$i]['value']     = $row['nombre'];
                $clientes[$i]['nombre']    = $row['nombre'];
                $clientes[$i]['ci_rif']    = $row['ci_rif'];
                $clientes[$i]['direccion'] = $row['direccion'];

                $i = $i + 1;
            }

            return mb_convert_encoding($clientes, 'utf-8');
        }

        if (request()->ajax() && request()->tipo == 'articulo') {
            $buscar = request()->buscar;

            $sql = "
                SELECT
                    --Id Articulo
                    InvArticulo.Id AS IdArticulo,
                    --Categoria Articulo
                    InvArticulo.InvCategoriaId,
                    --Codigo Interno
                    InvArticulo.CodigoArticulo AS codigo_interno,
                    --Codigo de Barra
                    (SELECT CodigoBarra
                    FROM InvCodigoBarra
                    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
                    --Descripcion
                    InvArticulo.Descripcion AS descripcion,
                    --Marca
                     (SELECT InvMarca.Nombre FROM InvMarca WHERE InvMarca.Id = InvArticulo.InvMarcaId) as Marca,
                     --Fecha Creacion
                     InvArticulo.Auditoria_FechaCreacion as FechaCreacion,
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
                    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
                    --Tabla principal
                    FROM InvArticulo
                    --Joins
                    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
                    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
                    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
                    -- Condiciones
                    WHERE (SELECT CodigoBarra
                    FROM InvCodigoBarra
                    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    AND InvCodigoBarra.EsPrincipal = 1) LIKE '%$buscar%' OR InvArticulo.DescripcionLarga LIKE '%$buscar%'
                    --Agrupamientos
                    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId,InvArticulo.InvMarcaId,InvArticulo.Auditoria_FechaCreacion
                    --Ordanamiento
                    ORDER BY InvArticulo.Id ASC
            ";

            $query = sqlsrv_query($this->conn, $sql);

            $i = 0;

            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $clientes[$i]['codigo_interno'] = $row['codigo_interno'];
                $clientes[$i]['codigo_barra'] = $row['codigo_barra'];
                $clientes[$i]['descripcion'] = $row['descripcion'];

                $i = $i + 1;
            }

            return mb_convert_encoding($clientes, 'utf-8');
        }

        return view('pages.cotizacion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
