<?php

namespace compras\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use compras\Http\Controllers\Controller;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\ExcelMatch;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once app_path() . '/functions/config.php';
require_once app_path() . '/functions/functions.php';
require_once app_path() . '/functions/querys_mysql.php';
require_once app_path() . '/functions/querys_sqlserver.php';

class ReportesController extends Controller
{
    private static $MINIMO_CODIGO = 2;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function excel(Request $request)
    {
        // validar los datos
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
            'fecha_inicio' => 'required|date',
            'fecha_limite' => 'required|date|after_or_equal:fecha_inicio',
        ], [], [
            'excel_file' => 'Archivo Excel',
            'fecha_inicio' => 'Fecha de inicio',
            'fecha_limite' => 'Fecha limite'
        ]);

        $InicioCarga = new DateTime("now");
        $file = $request->file('excel_file');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaLimite = $request->input('fecha_limite');
        $sede = $request->input('sede');

        $excelNombre_actual = $file->getClientOriginalName();
        $listaCodigos = $this->obtener_arrayCodigos($file);

        // Validar si hay codigos
        if(count($listaCodigos) < 1) {
            $error = 'Error al encontrar Códigos de barras en el documento';
            return redirect()->back()->withErrors(['excel_file' => $error])->withInput();
        }

        $registrosVentas = $this->obtenerVentas($this->obtener_conexionSmart(), $listaCodigos, date('Y-m-d', strtotime($fechaInicio)), $fechaLimite);

        $fechaInicio = new DateTime($fechaInicio);
        $fechaInicio = $fechaInicio->format('Y-m-d');

        $fechaLimite = new DateTime($fechaLimite);
        $fechaLimite = $fechaLimite->format('Y-m-d');

        $datos_concurso = $registrosVentas['registros'];
        $codigos = implode(',', $registrosVentas['codigos']);
        $codigosNotFound = $registrosVentas['no_encontrado'];
        $codigosSinVenta = $this->obtenerNombre_articulo($registrosVentas['codigos_sin_venta']);

        return view('pages.reporte.reporte53_cajeros', compact('datos_concurso', 'codigos', 'codigosNotFound', 'codigosSinVenta', 'fechaInicio', 'fechaLimite', 'sede', 'InicioCarga'));
    }

    public function detalle(Request $request)
    {
        $InicioCarga = new DateTime("now");
        $inicio = str_replace('/', '-', $request->input('inicio'));
        $limite = str_replace('/', '-', $request->input('limite'));

        $cajero = $request->input('cajero');
        $fechaInicio = $request->input('inicio');
        $fechaLimite = $request->input('limite');
        $codigosLista = explode(',', $request->input('codigos'));
        $sede = $request->input('sede');

        $registrosVentas = $this->obtenerVentas($this->obtener_conexionSmart(), $codigosLista, date('Y-m-d', strtotime($fechaInicio)), $limite);

        $cajeroInfo = $registrosVentas['registros'][$cajero];
        $codigos = $registrosVentas['codigos'];

        return view('pages.reporte.reporte53_detalle', compact('cajeroInfo', 'cajero', 'codigos', 'sede', 'fechaInicio', 'fechaLimite', 'InicioCarga'));
    }

    private function obtenerVentas($conn, $listaCodigos, $fechaInicio, $fechaLimite)
    {
        $fechaFinal = new DateTime($fechaLimite);
        $fechaFinal->modify('+1 day');
        $fechaFinal = $fechaFinal->format('Y-m-d');

        $registrosCajeros = [];
        $codigosValidos = [];
        $codigosConVenta = [];
        $codigosNoFound = [];

        foreach ($listaCodigos as $index => $codigos) {
            if(!isset($codigos['barra']) || $codigos['barra']) {
                $codigoBarra = $codigos['barra'] ?? $codigos;
                // Valida si ya el codigo de barra se consulto
                if(array_search($codigoBarra, $codigosValidos) !== false) {
                    continue;
                }

                if(!$this->codigoEncontrado($codigoBarra, $conn)) {
                    $codigosNoFound[] = $codigoBarra;
                    continue;
                }

                array_push($codigosValidos, $codigoBarra);

                $sql = "SELECT
                        VenFactura.Auditoria_Usuario AS USUARIO,
                        InvArticulo.Descripcion AS ARTICULO,
                        CAST(SUM(VenFacturaDetalle.Cantidad) AS decimal(18, 0)) AS CANTIDAD,
                        CAST(ROUND(SUM(VenFacturaDetalle.PrecioNeto * VenFacturaDetalle.Cantidad), 2) AS decimal(18, 2)) AS MONTO
                    FROM InvCodigoBarra
                    INNER JOIN InvArticulo ON InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    INNER JOIN VenFacturaDetalle ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
                    INNER JOIN VenFactura ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
                    LEFT JOIN VenDevolucion ON VenDevolucion.VenFacturaId = VenFactura.Id
                    WHERE 
                         InvCodigoBarra.CodigoBarra = '".$codigoBarra."' AND
                        (VenFactura.FechaDocumento >= '".$fechaInicio."' AND VenFactura.FechaDocumento < '".$fechaFinal."') AND
                        VenFactura.estadoFactura = 2 AND VenDevolucion.Id IS NULL
                    GROUP BY 
                        VenFactura.Auditoria_Usuario, InvArticulo.Descripcion
                ";

                $sqlFacturas = "SELECT
                        VenFactura.Id AS ID_FACTURA,
                        VenFactura.Auditoria_Usuario AS USUARIO,
                        CONCAT(MAX(GenPersona.Nombre), ' ', MAX(GenPersona.Apellido)) AS NOMBRE,
                        InvArticulo.CodigoArticulo AS CODIGO_INTERNO,
                        InvCodigoBarra.CodigoBarra AS CODIGO_BARRA,
                        InvArticulo.Descripcion AS ARTICULO,
                        CAST(SUM(VenFacturaDetalle.Cantidad) AS decimal(18, 0)) AS CANTIDAD,
                        CAST(ROUND(SUM(VenFacturaDetalle.PrecioNeto * VenFacturaDetalle.Cantidad), 2) AS decimal(18, 2)) AS MONTO
                    FROM InvCodigoBarra
                    INNER JOIN InvArticulo ON InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    INNER JOIN VenFacturaDetalle ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
                    INNER JOIN VenFactura ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
                    INNER JOIN VenCajero ON VenFactura.Auditoria_Usuario = VenCajero.CodigoUsuarioCaja
                    INNER JOIN GenPersona ON VenCajero.GenPersonaId = GenPersona.Id
                    LEFT JOIN VenDevolucion ON VenDevolucion.VenFacturaId = VenFactura.Id
                    WHERE
                        InvCodigoBarra.CodigoBarra = '".$codigoBarra."' AND
                        (VenFactura.FechaDocumento >= '".$fechaInicio."' AND VenFactura.FechaDocumento < '".$fechaFinal."') AND
                        VenFactura.estadoFactura = 2
                    GROUP BY
                        VenFactura.Auditoria_Usuario, InvArticulo.CodigoArticulo, InvCodigoBarra.CodigoBarra, InvArticulo.Descripcion, VenFactura.Id
                ";

                $sqlDevoluciones = "SELECT
                        InvArticulo.CodigoArticulo AS ARTICULO,
                        VenDevolucion.Auditoria_Usuario AS USUARIO,
                        CAST(SUM(VenDevoluciondetalle.cantidad) AS DECIMAL(18,0)) AS CANTIDAD
                    from VenDevolucion, VenDevolucionDetalle
                    INNER JOIN InvCodigoBarra ON VenDevolucionDetalle.InvArticuloId = InvCodigoBarra.InvArticuloId
                    INNER JOIN InvArticulo ON InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    where
                    VenDevolucion.id = VenDevolucionDetalle.VenDevolucionId
                    AND VenDevolucion.FechaDocumento>= '".$fechaInicio."' AND VenDevolucion.FechaDocumento<= '".$fechaFinal."'
                    AND InvCodigoBarra.CodigoBarra = '".$codigoBarra."'
                    GROUP BY VenDevolucion.Auditoria_Usuario,  InvArticulo.CodigoArticulo
                ";

                $result = sqlsrv_query($conn,$sqlFacturas,[],['QueryTimeout'=>7200]);
                while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
                    $usuario = trim($row['USUARIO']);
                    $registrosCajeros[$usuario] = array_merge(
                        ['nombre' => $row['NOMBRE']],
                        $this->ordenarResultados($row, $registrosCajeros[$usuario] ?? [])
                    );

                    if(array_search($row['CODIGO_BARRA'], $codigosConVenta) === false) {
                        array_push($codigosConVenta,$row['CODIGO_BARRA']);
                    }
                }

                $result = sqlsrv_query($conn,$sqlDevoluciones,[],['QueryTimeout'=>7200]);
                while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
                    $usuario = trim($row['USUARIO']);

                    if(isset($registrosCajeros[$usuario])) {
                        $registrosCajeros[$usuario] = $this->analizarDevolucion($row, $registrosCajeros[$usuario] );
                    }
                }
            }
        }

        $codigosSinVenta = array_diff($codigosValidos, $codigosConVenta);

        return [
            'registros' => $registrosCajeros,
            'codigos' => $codigosConVenta,
            'no_encontrado' => $codigosNoFound,
            'codigos_sin_venta' => $codigosSinVenta
        ];
    }

    private function codigoEncontrado($codigo, $conn)
    {
        $sql = "SELECT TOP (1) InvArticuloId
            FROM InvCodigoBarra
            WHERE InvCodigoBarra.CodigoBarra = '".$codigo."'";

        $result = sqlsrv_query($conn,$sql,[],['QueryTimeout'=>7200]);
        return sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
    }

    private function ordenarResultados($fila, $registros_cajero)
    {
        if(count($registros_cajero) < 1) {
            return [
                'lista_facturas' => [$fila['ID_FACTURA'] => true],
                'articulos' => [
                    str_replace(' ', '_', $fila['ARTICULO']) => [
                        'codigo' => $fila['CODIGO_INTERNO'],
                        'nombre' => $fila['ARTICULO'],
                        'cantidad' => $fila['CANTIDAD'],
                        'monto' => $fila['MONTO']
                    ]
                ]
            ];
        }


        $listaArticulos = $registros_cajero['articulos'];
        $listaFacturas = $registros_cajero['lista_facturas'];
        $idFactura = $fila['ID_FACTURA'];
        $articuloKey = str_replace(' ', '_', $fila['ARTICULO']);
        
        $listaFacturas[$idFactura] = true; // Agregar nueva factura

        if(array_key_exists($articuloKey, $listaArticulos))
        {
            return [
                'lista_facturas' => $listaFacturas,
                'articulos' => array_merge(
                    $listaArticulos,
                    [
                        $articuloKey => [
                            'codigo' => $fila['CODIGO_INTERNO'],
                            'nombre' => $fila['ARTICULO'],
                            'cantidad' => ($listaArticulos[$articuloKey]['cantidad'] + $fila['CANTIDAD']),
                            'monto' => ($listaArticulos[$articuloKey]['monto'] + $fila['MONTO'])
                        ]
                    ]
                )
            ];
        }

        return [
            'lista_facturas' => $listaFacturas,
            'articulos' => array_merge(
                $listaArticulos,
                [
                    $articuloKey => [
                        'codigo' => $fila['CODIGO_INTERNO'],
                        'nombre' => $fila['ARTICULO'],
                        'cantidad' => $fila['CANTIDAD'],
                        'monto' => $fila['MONTO']
                    ]
                ]
            )
        ];
    }

    private function analizarDevolucion($row, array $registro) {
        $articuloBuscar = $row['ARTICULO'];
        $cantidadDev = $row['CANTIDAD'];
        $articulos = $registro['articulos'] ?? [];
        $nuevoRegistro = $registro;

        foreach ($articulos as $key => $articulo) {
            if($articulo['codigo'] == $articuloBuscar) {
				$monto = $articulo['monto'] / $articulo['cantidad'];
                $nuevoRegistro['articulos'][$key]['cantidad'] -= $cantidadDev;
				$nuevoRegistro['articulos'][$key]['monto'] -= $monto * $cantidadDev;
            }
        }

        return $nuevoRegistro;
    }

    private function obtener_arrayCodigos($file)
    {
        // Leer Excel
        $excelData = IOFactory::load($file);
        $hojaExcel = $excelData->getActiveSheet();
    
        $articulosObtenidos = [];
        $columnaIndex_barra = null;
        $columnaIndex_interno = null;

        foreach ($hojaExcel->getRowIterator() as $index => $fila)
        {
            $codigos = [];

            foreach ($fila->getCellIterator() as $indexCol => $columna)
            {
                $colValue = strval($columna->getCalculatedValue());

                if(strpos(strtolower($colValue), 'barra') > -1 && !$columnaIndex_barra) {
                    $columnaIndex_barra = $indexCol;
                    continue;
                }

                if(strpos(strtolower($colValue), 'interno') > -1  && !$columnaIndex_interno) {
                    $columnaIndex_interno = $indexCol;
                    continue;
                }

                if($indexCol === $columnaIndex_barra && strlen($colValue) >= self::$MINIMO_CODIGO) {
                    $codigos['barra'] =  $colValue;
                } else if($indexCol === $columnaIndex_interno && strlen($colValue) >= self::$MINIMO_CODIGO) {
                    $codigos['interno'] =  $colValue;
                }
            }

            if(!$columnaIndex_barra && !$columnaIndex_interno) {
                continue;
            }

            if(count($codigos) > 0)
            {
                if(!array_key_exists('barra', $codigos)) {
                    $codigos['barra'] = null;
                }else if(!array_key_exists('interno', $codigos)) {
                    $codigos['interno'] = null;
                }

                array_push($articulosObtenidos, $codigos);
            }
        }

        return $articulosObtenidos;
    }

    private function obtener_conexionSmart()
    {
        $SedeConnection = FG_Mi_Ubicacion();

        return FG_Conectar_Smartpharma($SedeConnection);
    }

    private function obtenerNombre_articulo(array $codigos)
		{
			$conn = $this->obtener_conexionSmart();
            $listaCodigos = [];

            foreach ($codigos as $index => $codigo)
            {
                $sql = "
                    SELECT InvArticulo.Descripcion
                    FROM InvCodigoBarra
                    INNER JOIN InvArticulo ON InvCodigoBarra.InvArticuloId = InvArticulo.Id
                    WHERE InvCodigoBarra.CodigoBarra = '$codigo'
			    ";

                $result = sqlsrv_query($conn,$sql,[],['QueryTimeout'=>7200]);
                if($result) {
                    $result = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
                    array_push($listaCodigos, [
                        'codigo' => $codigo,
                        'nombre' => $result['Descripcion']
                    ]);
                }
            }

            return $listaCodigos;
		}
}
