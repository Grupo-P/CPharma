<?php

namespace compras\Http\Controllers;

use DateTime;
use GuzzleHttp\Client as GuzzleHttp;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory as PhpSpreadsheet;

class Reporte50Controller extends Controller
{
    public function reporte50()
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $conn = FG_Conectar_Smartpharma($_GET['SEDE']);
        $Hoy = new DateTime('now');
        $Hoy = $Hoy->format('Y-m-d');

        $InicioCarga = new DateTime("now");

        $articulos = [];

        $i = 0;

        // Cobeca

        try {
            $client = new GuzzleHttp();

            $request = $client->request('POST', 'http://www.cobeca.com:8080/pedidofl/api/Login', [
                'json' => [
                    'Usuario' => 'F27336',
                    'Clave' => 'FTN2016+'
                ]
            ]);

            $response = json_decode($request->getBody(), true);

            $token = $response['token'];

            $request = $client->request('POST', 'http://www.cobeca.com:8080/pedidofl/api/Articulos', [
                'headers' => [
                    'Autorizacion' => $token,
                ],
                'form_params' => [
                    'cod_drogueria' => '7',
                ]
            ]);

            $cobeca = json_decode($request->getBody(), true);

            $cobecaFechaActualizacion = date('d/m/Y h:i A');

            $fopen = fopen('cobeca.json', 'w+');
            fwrite($fopen, $request->getBody());
            fclose($fopen);
        } catch (Exception $exception) {
            $cobeca = file_get_contents('cobeca.json');
            $cobeca = json_decode($cobeca, true);
        }

        $cobeca = isset($cobeca['articulos']) ? $cobeca['articulos'] : $cobeca;

        foreach ($cobeca as $item) {
            $articulos[$i]['codigo_barra'] = $item['cod_barra'];
            $articulos[$i]['descripcion'] = $item['desc_articulo'];
            $articulos[$i]['existencia_cobeca'] = $item['existencia'];
            $articulos[$i]['precio_cobeca'] = $item['monto_final'];

            $i++;
        }

        try {
            $ftp_connect = ftp_connect('ftp.dronena.com');
            ftp_login($ftp_connect, '9431-foraneo', '5svjk431');

            $fopen = fopen('dronena.txt', 'w+');

            ftp_pasv($ftp_connect, true);
            ftp_fget($ftp_connect, $fopen, '/Clientes/9431/Inventario.txt', FTP_ASCII, 0);
            ftp_close($ftp_connect);

            fclose($fopen);

            $content = file_get_contents('dronena.txt');
            $lines = explode("\r\n", $content);
        } catch (Exception $exception) {
            $content = file_get_contents('dronena.txt');
            $lines = explode("\r\n", $content);
        }

        // Nena

        try {
            $ftp_connect = ftp_connect('ftp.dronena.com');
            ftp_login($ftp_connect, '9431-foraneo', '5svjk431');

            $fopen = fopen('dronena.txt', 'w+');

            ftp_pasv($ftp_connect, true);
            ftp_fget($ftp_connect, $fopen, '/Clientes/9431/Inventario.txt', FTP_ASCII, 0);
            ftp_close($ftp_connect);

            fclose($fopen);

            $content = file_get_contents('dronena.txt');
            $lines = explode("\r\n", $content);
        } catch (Exception $exception) {
            $content = file_get_contents('dronena.txt');
            $lines = explode("\r\n", $content);
        }

        unset($lines[0]);

        foreach ($lines as $field) {
            if (in_array(trim(substr($field, 130, 17)), array_column($articulos, 'codigo_barra'))) {
                $index = array_search(trim(substr($field, 130, 17)), array_column($articulos, 'codigo_barra'));
                $articulos[$index]['existencia_nena'] = trim(substr($field, 64, 12));
                $articulos[$index]['precio_nena'] = trim(substr($field, 49, 15));
            } else {
                $articulos[$i]['codigo_barra'] = trim(substr($field, 130, 17));
                $articulos[$i]['descripcion'] = trim(substr($field, 8, 42));
                $articulos[$i]['existencia_nena'] = trim(substr($field, 64, 12));
                $articulos[$i]['precio_nena'] = trim(substr($field, 49, 15));

                $i++;
            }
        }

        // Oeste

        try {
            $ftp_connect = ftp_connect('03bb052.netsolhost.com');
            ftp_login($ftp_connect, 'clientes_electronico', 'Horizonte777');
            ftp_pasv($ftp_connect, true);

            $fopen = fopen('drooeste.csv', 'w+');

            ftp_fget($ftp_connect, $fopen, '/Inventario/I_BTIERR/BTIERR.txt', FTP_ASCII, 0);
            ftp_close($ftp_connect);

            fclose($fopen);
        } catch (Exception $exception) {}

        $spreadsheet = PhpSpreadsheet::load('drooeste.csv');
        $oeste = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($oeste as $item) {
            if (in_array($item['A'], array_column($articulos, 'codigo_barra'))) {
                $index = array_search($item['A'], array_column($articulos, 'codigo_barra'));
                $articulos[$index]['existencia_oeste'] = $item['D'];

                $precio_oeste = $item['E'];

                $descuento1 = $precio_oeste * ((int) $item['G'] / 100);
                $descuento2 = $precio_oeste * ((int) $item['H'] / 100);
                $descuento3 = $precio_oeste * ((int) $item['I'] / 100);

                $precio_oeste = $precio_oeste - $descuento1 - $descuento2 - $descuento3;

                $articulos[$index]['precio_oeste'] = $precio_oeste;
            } else {
                $articulos[$i]['codigo_barra'] = $item['A'];
                $articulos[$i]['descripcion'] = $item['C'];
                $articulos[$i]['existencia_oeste'] = $item['D'];

                $precio_oeste = str_replace(',', '.', $precio_oeste);

                $descuento1 = $precio_oeste * ((int) $item['G'] / 100);
                $descuento2 = $precio_oeste * ((int) $item['H'] / 100);
                $descuento3 = $precio_oeste * ((int) $item['I'] / 100);

                $precio_oeste = $precio_oeste - $descuento1 - $descuento2 - $descuento3;

                $articulos[$i]['precio_oeste'] = $precio_oeste;

                $i++;
            }
        }

        // Drolanca

        try {
            $ftp_connect = ftp_connect('ftp.drolanca.com');
            ftp_login($ftp_connect, '17900', '17900');
            ftp_pasv($ftp_connect, true);

            $fopen = fopen('drolanca.csv', 'w+');

            ftp_fget($ftp_connect, $fopen, '/inventario/Inventario.txt', FTP_ASCII, 0);
            ftp_close($ftp_connect);

            fclose($fopen);
        } catch (Exception $exception) {}

        $spreadsheet = PhpSpreadsheet::load('drolanca.csv');
        $drolanca = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($drolanca as $item) {
            if (in_array($item['B'], array_column($articulos, 'codigo_barra'))) {
                $index = array_search($item['B'], array_column($articulos, 'codigo_barra'));
                $articulos[$index]['existencia_drolanca'] = $item['J'];

                $precio_drolanca = $item['I'];
                $precio_drolanca = str_replace(',', '.', $precio_drolanca);

                $descuento1 = $precio_drolanca * ((int) $item['L'] / 100);
                $descuento2 = $precio_drolanca * ((int) $item['M'] / 100);
                $descuento3 = $precio_drolanca * ((int) $item['N'] / 100);
                $descuento4 = $precio_drolanca * ((int) $item['AE'] / 100);

                $precio_drolanca = $precio_drolanca - $descuento1 - $descuento2 - $descuento3 - $descuento4;

                $articulos[$index]['precio_drolanca'] = $precio_drolanca;
            } else {
                $articulos[$i]['codigo_barra'] = $item['B'];
                $articulos[$i]['descripcion'] = $item['C'];
                $articulos[$i]['existencia_drolanca'] = $item['D'];

                $precio_drolanca = $item['I'];
                $precio_drolanca = str_replace(',', '.', $precio_drolanca);

                $descuento1 = $precio_drolanca * ((int) $item['L'] / 100);
                $descuento2 = $precio_drolanca * ((int) $item['M'] / 100);
                $descuento3 = $precio_drolanca * ((int) $item['N'] / 100);
                $descuento4 = $precio_drolanca * ((int) $item['AE'] / 100);

                $precio_drolanca = $precio_drolanca - $descuento1 - $descuento2 - $descuento3 - $descuento4;

                $articulos[$i]['precio_drolanca'] = $precio_drolanca;

                $i++;
            }
        }

        // Drocerca

        try {
            $ftp_connect = ftp_connect('Drocerca.proteoerp.org');
            ftp_login($ftp_connect, 'C00660', 'TIKpjKjZfD');
            ftp_pasv($ftp_connect, true);

            $fopen = fopen('drocerca.csv', 'w+');

            @ftp_fget($ftp_connect, $fopen, '/inventario.txt', FTP_ASCII, 0);
            ftp_close($ftp_connect);

            fclose($fopen);
        } catch (Exception $exception) {}

        $spreadsheet = PhpSpreadsheet::load('drocerca.csv');
        $drocerca = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        if ($drocerca[1]['A'] != null) {
            foreach ($drocerca as $item) {
                if (in_array($item['B'], array_column($articulos, 'codigo_barra'))) {
                    $index = array_search($item['B'], array_column($articulos, 'codigo_barra'));
                    $articulos[$index]['existencia_drocerca'] = (int) $item['D'] + (int) $item['E'];

                    $precio_drocerca = $item['H'];
                    $precio_drocerca = str_replace(',', '.', $precio_drocerca);

                    $articulos[$index]['precio_drocerca'] = $precio_drocerca;
                } else {
                    $articulos[$i]['codigo_barra'] = $item['B'];
                    $articulos[$i]['descripcion'] = $item['C'];
                    $articulos[$i]['existencia_drocerca'] = (int) $item['D'] + (int) $item['E'];

                    $precio_drocerca = $item['H'];
                    $precio_drocerca = str_replace(',', '.', $precio_drocerca);

                    $articulos[$i]['precio_drocerca'] = $precio_drocerca;

                    $i++;
                }
            }
        }

        $codigos = array_column($articulos, 'codigo_barra');
        $codigos = array_slice($codigos, 0, 101);
        $codigosChunk = array_chunk($codigos, 1);

        for ($j=0; $j <= count($codigosChunk) - 1; $j++) {
            $codigosSeparadoPorComa = implode("', '", $codigosChunk[$j]);

            $sql = "
                SELECT
                    --Id Articulo
                    InvArticulo.Id AS IdArticulo,
                    --Codigo Interno
                    InvArticulo.CodigoArticulo AS CodigoInterno,
                    --Codigo de Barra
                    (SELECT CodigoBarra  FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
                    --Descripcion
                    InvArticulo.Descripcion,
                    --Existencia (Segun el almacen del filtro)
                    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia
                --Tabla principal
                FROM InvArticulo
                    --Joins
                    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
                    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
                    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
                --Condicionales
                WHERE
                    (SELECT CodigoBarra
                    FROM InvCodigoBarra
                    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    AND InvCodigoBarra.EsPrincipal = 1) IN ('$codigosSeparadoPorComa')
                --Agrupamientos
                GROUP BY
                    InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion
                --Ordanamiento
                ORDER BY InvArticulo.Id ASC
            ";

            $result = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $index = array_search($row['CodigoBarra'], array_column($articulos, 'codigo_barra'));

                $articulos[$index]['descripcion'] = $row['Descripcion'];
                $articulos[$index]['existencia'] = $row['Existencia'];

            }

        }

        return view('pages.reporte.reporte50', compact('articulos', 'InicioCarga'));
    }
}
