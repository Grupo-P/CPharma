<?php

namespace compras\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function __construct()
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';
        include app_path() . '/functions/querys_sqlserver.php';

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
        ini_set('memory_limit', '1G');

        if (request()->ajax()) {
            $sql   = SQG_Detalle_Articulo(request()->id_articulo);
            $query = sqlsrv_query($this->conn, $sql);
            $row   = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

            $Existencia                = $row["Existencia"];
            $ExistenciaAlmacen1        = $row["ExistenciaAlmacen1"];
            $ExistenciaAlmacen2        = $row["ExistenciaAlmacen2"];
            $IsTroquelado              = $row["Troquelado"];
            $UtilidadArticulo          = $row["UtilidadArticulo"];
            $UtilidadCategoria         = $row["UtilidadCategoria"];
            $TroquelAlmacen1           = $row["TroquelAlmacen1"];
            $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
            $TroquelAlmacen2           = $row["TroquelAlmacen2"];
            $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
            $PrecioCompraBruto         = $row["PrecioCompraBruto"];
            $IsIVA                     = $row["Impuesto"];
            $CondicionExistencia       = 'CON_EXISTENCIA';

            $precio = FG_Calculo_Precio_Alfa($Existencia, $ExistenciaAlmacen1, $ExistenciaAlmacen2, $IsTroquelado, $UtilidadArticulo, $UtilidadCategoria, $TroquelAlmacen1, $PrecioCompraBrutoAlmacen1, $TroquelAlmacen2, $PrecioCompraBrutoAlmacen2, $PrecioCompraBruto, $IsIVA, $CondicionExistencia);

            $tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');
            $tasa = ($tasa) ? $tasa : 0;

            if ($tasa) {
                $precio_ds = $precio / $tasa;
            } else {
                $precio_ds = 0;
            }

            $resultado['codigo_interno'] = $row['CodigoInterno'];
            $resultado['codigo_barra']   = $row['CodigoBarra'];
            $resultado['descripcion']    = $row['Descripcion'];
            $resultado['precio_bs']      = number_format($precio, 2, ',', '.');
            $resultado['precio_ds']      = number_format($precio_ds, 2, ',', '.');

            return $resultado;
        }

        $sql = "
            SELECT
                CONCAT(GenPersona.Nombre, ' ', GenPersona.Apellido) AS nombre,
                GenPersona.IdentificacionFiscal AS ci_rif,
                GenPersona.DireccionCorta AS direccion
            FROM
                VenCliente LEFT JOIN GenPersona ON VenCliente.GenPersonaId = GenPersona.Id
            ORDER BY
                nombre ASC;
        ";

        $query = sqlsrv_query($this->conn, $sql);

        $i = 0;

        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            $clientes[$i]['label']     = $row['nombre'] . ' | ' . $row['ci_rif'];
            $clientes[$i]['value']     = $row['nombre'];
            $clientes[$i]['ci_rif']    = $row['ci_rif'];
            $clientes[$i]['direccion'] = $row['direccion'];

            $i = $i + 1;
        }

        $clientes = mb_convert_encoding($clientes, 'utf-8');
        $clientes = json_encode($clientes);

        $sql = "
            SELECT
                InvArticulo.DescripcionLarga AS descripcion,
                InvArticulo.Id AS id_articulo,
                (
                    SELECT
                        InvCodigoBarra.CodigoBarra
                    FROM
                        InvCodigoBarra
                    WHERE
                        InvCodigoBarra.EsPrincipal = 1
                            AND
                        InvCodigoBarra.InvArticuloId = InvArticulo.Id
                ) AS codigo_barra,
                InvArticulo.CodigoArticulo AS codigo_interno
            FROM
                InvArticulo
            ORDER BY
                InvArticulo.Descripcion ASC;
        ";

        $query = sqlsrv_query($this->conn, $sql);

        $i = 0;

        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {

            $articulos[$i]['value']          = $row['codigo_barra'] . ' | ' . $row['descripcion'];
            $articulos[$i]['label']          = $row['codigo_barra'] . ' | ' . $row['descripcion'];
            $articulos[$i]['codigo_interno'] = $row['codigo_interno'];
            $articulos[$i]['codigo_barra']   = $row['codigo_barra'];
            $articulos[$i]['descripcion']    = $row['descripcion'];
            $articulos[$i]['id_articulo']    = $row['id_articulo'];

            $i = $i + 1;
        }

        $articulos = mb_convert_encoding($articulos, 'utf-8');
        $articulos = json_encode($articulos);

        return view('pages.cotizacion.create', compact('articulos', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
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
