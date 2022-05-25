<?php

namespace compras\Console\Commands;

use compras\TrackImagen;
use Illuminate\Console\Command;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Exception\ClientException;

class ActualizaYummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Actualiza:Yummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando actualiza los precios y existencia de productos en Yummy';

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
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $this->info('Fecha inicio: ' . date('d/m/Y h:i A'));

        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $utilidad = round(( (100-10) /100),2);

        $sql = "
            SELECT
            --Id Articulo
                InvArticulo.Id AS IdArticulo,
            --Codigo de Barra
                (SELECT CodigoBarra
                FROM InvCodigoBarra
                WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
            --Descripcion
                InvArticulo.Descripcion,
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
            --PaginaWEB  (0 NO es PaginaWEB , Id Articulo SI es PaginaWEB )
                (ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'PaginaWEB '
                OR  InvAtributo.Descripcion = 'PAGINAWEB '
                OR  InvAtributo.Descripcion = 'paginaweb ')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS PaginaWEB
            --Tabla principal
                FROM InvArticulo
            --Joins
                LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
                LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
                LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
            --Condicionales
                WHERE
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > '15'
                AND ((ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'ExcluirExcel')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) = 0)
                AND (ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
                        FROM VenCondicionVenta
                        WHERE VenCondicionVenta.id = (
                          SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
                          FROM VenCondicionVenta_VenCondicionVentaCategoria
                          WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) <= '$utilidad')
            --Agrupamientos
                GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
            --Ordanamiento
                ORDER BY InvArticulo.Id ASC
        ";

        $query = sqlsrv_query($conn, $sql);

        $products = [];
        $i = 0;
        $sort = 1;

        $client = new Guzzle();

        $host = 'https://api-integraciones-integraciones.hugoapp.dev';
        $partnerId = 'Np9pKvMgjf';
        $username = 'farmaciassaas';
        $password = '_$d!W7Lc';

        $request = $client->request('POST', $host . '/api/v1/partners/tokens', [
            'form_params' => [
                'username' => $username,
                'password' => $password
            ]
        ]);

        $response = json_decode($request->getBody(), true);

        $token = $response['data']['access_token'];

        $request = $client->request('GET', $host . '/api/v1/partners/' . $partnerId, [
            'headers' => ['Authorization' => 'Bearer ' . $token]
        ]);

        $response = json_decode($request->getBody(), true);

        $location = $response['data']['locations_info']['60afd8f2a784eb00422dd1e2']['locations'][0]['id'];

        $connCPharma = FG_Conectar_CPharma();

        $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));

        echo sqlsrv_num_rows($query);

        while ($row = sqlsrv_fetch_array($query)) {


            $IdArticulo = $row["IdArticulo"];
            $CodigoBarra = $row['CodigoBarra'];

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

            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
                    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

            $sqlCategorizacion = "
                SELECT
                if(categorias.codigo_app is not null,categorias.codigo_app ,categorias.nombre) as categoria,
                if(subcategorias.codigo_app is not null,subcategorias.codigo_app ,subcategorias.nombre) as subcategoria
                FROM categorizacions
                INNER JOIN categorias ON categorias.codigo = codigo_categoria
                INNER JOIN subcategorias ON subcategorias.codigo = codigo_subcategoria
                WHERE id_articulo = '$IdArticulo';
            ";

            $ResultCategorizacion = mysqli_query($connCPharma,$sqlCategorizacion);
            $RowCategorizacion = mysqli_fetch_assoc($ResultCategorizacion);
            $categoria = ($RowCategorizacion['categoria']) ? $RowCategorizacion['categoria'] : "60edd31d97272a6eec15e017";

            $PrecioDolar = ($Precio/$TasaActual);
            $PrecioDolar = number_format($PrecioDolar,2,"." ,"," );

            if ($row['CodigoBarra'] == '16780010') {
                $PrecioDolar = date('d') . '.' . FG_Numero_Sede();
            }

            $url_app = $CodigoBarra;

            $TrackImagen =
            TrackImagen::orderBy('id','asc')
                ->where('codigo_barra',$CodigoBarra)
                ->get();

            if(!empty($TrackImagen[0]->codigo_barra)) {
                $url_app = $TrackImagen[0]->url_app;
            }

            $products[$i]['sku'] = $row['CodigoBarra'];
            $products[$i]['sort'] = $sort;
            $products[$i]['name'] = $row['Descripcion'];
            $products[$i]['description'] = $row['Descripcion'];
            $products[$i]['price'] = $PrecioDolar;
            $products[$i]['extra_info'] = '';
            $products[$i]['taxonomies'][] = $categoria;


            $products[$i]['qty'][0][$location] = $Existencia;
            $products[$i]['toggle_mode'] = false;

            $i++;
            $sort++;
        }

        $products = array_chunk($products, 100);

        foreach ($products as $product) {
            try {
                $request = $client->request('POST', $host . '/api/v1/products?partnerId=' . $partnerId, [
                    'headers' => ['Authorization' => 'Bearer ' . $token],
                    'form_params' => ['type' => 'inventory', 'products' => $product]
                ]);

            } catch (ClientException $exception) {
                $message = Message::toString($exception->getResponse());

                if (strpos($message, 'Este producto incluye un precio de cero, si la información es correcta no realice ningún cambio') === false) {
                    echo $message;
                }
            }
        }

        $this->info('Cantidad de artículos: ' . $i);
        $this->info('Resultado: Éxito');
        $this->info('Fecha final: ' . date('d/m/Y h:i A'));
    }
}

