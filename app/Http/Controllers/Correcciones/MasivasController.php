<?php

namespace compras\Http\Controllers\Correcciones;

use Illuminate\Http\Request;
use compras\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\ExcelMatch;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once app_path() . '/functions/config.php';
require_once app_path() . '/functions/functions.php';
require_once app_path() . '/functions/querys_mysql.php';
require_once app_path() . '/functions/querys_sqlserver.php';

class MasivasController extends Controller
{
    // Contantes
    private static $MINIMO_CODIGO = 2;
    private static $CORRECCIONES_SESSION = 'datos_correccion_masiva';
    private const ATRIBUTOS_VALIDOS = [
        'PROMOCIONCONSULTOR',
        'PROMOCION',
        'ARTICULO ESTRELLA',
        'EXCLUIREXCEL',
        'CONCURSOS'
    ];


    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index
    public function index()
    {
        $connCPharma = FG_Conectar_CPharma();

        $tasaActual = floatval(FG_Tasa_Fecha_Venta($connCPharma, date('Y-m-d')));
        $listaAtributos = $this->obtener_atributos($this->obtener_conexionSmart());
        $formularioConfirmado = false;

        return view('pages.correcciones.correccionesMasivas', compact('tasaActual', 'listaAtributos', 'formularioConfirmado'));
    }

    // Cargar Excel
    public function excel(Request $request)
    {
        // validar los datos
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
            'atributo' => 'required|not_in:-1',
            'accion_ejecutar' => 'required|in:agregar,quitar',
        ], [], [
            'excel_file' => 'Archivo Excel',
            'atributo' => 'Atributos',
            'accion' => 'Accion a ejecutar'
        ]);

        $file = $request->file('excel_file');
        $atributo = intval($request->input('atributo'));
        $accionEjecutar = $request->input('accion_ejecutar');
        $formularioConfirmado = $request->input('confirmado');


        $excelNombre_actual = $file->getClientOriginalName();
        $excelNombre_anterior = $request->input('excel_nombre');
        
        $listaCodigos = $this->obtener_arrayCodigos($file);

        // Validar que el atributo sea valido
        $atributoNombre = $this->obtener_atributo($this->obtener_conexionSmart(), $atributo);
        if(!$atributoNombre) {
            $error = 'El atributo seleccionado no es valido';
            return redirect()->route('atributos.masivos')->withErrors(['atributo' => $error])->withInput();
        }
        
        // Validar que el excel sea el mismo al momento de confirmar
        if($formularioConfirmado == true && $excelNombre_actual != $excelNombre_anterior) {
            $error = 'El excel de confirmacion no coincide con el ya cargado.';
            return redirect()->route('atributos.masivos')->withErrors(['excel_file' => $error])->withInput();
        }

        // Validar si hay codigos
        if(count($listaCodigos) < 1) {
            $error = 'Error al encontrar Códigos de barras y Códigos internos en el documento';
            return redirect()->back()->withErrors(['excel_file' => $error])->withInput();
        }

        $resultado = $this->actualizarAtributos($listaCodigos, $atributo, $accionEjecutar, $formularioConfirmado);

        session()->forget(self::$CORRECCIONES_SESSION);
        session()->put(self::$CORRECCIONES_SESSION, ($resultado+[
            'url_excel' => $file->getRealPath(),
            'excel_nombre' => $excelNombre_actual,
            'atributo' => $atributoNombre
        ]));

        if (!$resultado['confirmado']) {
            return redirect()
                ->route('atributos.confirmar')
                ->withInput($request->input());
        }
        

        return redirect()->route('atributos.historial');
    }

    // Confirmar operacion
    public function confirmar(Request $request)
    {
        $resultado = session()->get(self::$CORRECCIONES_SESSION);
        session()->forget(self::$CORRECCIONES_SESSION);

        if(!is_array($resultado) || count($resultado) < 1) {
            return redirect()->route('atributos.masivos');
        }

        $formularioConfirmado = true;

        return view('pages.correcciones.correccionesConfirmacion', compact('formularioConfirmado', 'resultado'));
    }

    // Ver historial
    public function historial(Request $request)
    {
        $resultado = session()->get(self::$CORRECCIONES_SESSION);
        $keyResultado = self::$CORRECCIONES_SESSION;
        session()->forget(self::$CORRECCIONES_SESSION);

        if(!is_array($resultado) || count($resultado) < 1) {
            return redirect()->route('atributos.masivos');
        }

        return view('pages.correcciones.correccionesHistorial', compact('resultado', 'keyResultado'));
    }

    // Funciones privadas
    private function actualizarAtributos($lista_codigos, $atributo, $accion, $confirmado)
    {
        $articulosActualizados = [];
        $codigosFallidos = [];

        $conexion = $this->obtener_conexionSmart();

        foreach ($lista_codigos as $index => $codigo)
        {
            $cBarra = $codigo['barra'];
            $cInterno = $codigo['interno'];

            $articulo = $this->obtener_articulo($cBarra, $cInterno);

            // Validar articulo
            if(!$articulo) {
                array_push($codigosFallidos, $codigo);
                continue;
            }

            if($confirmado !== 'si') {
                array_push($articulosActualizados, $articulo);
                continue;
            }

            $sqlConsulta = $this->obtener_sqlAccion($accion, $articulo['id_articulo'], $atributo);

            // Validar consulta
            if(!$sqlConsulta) {
                array_push($codigosFallidos, $codigo);
                continue;
            }

            // Ejecutar consulta
            $result = sqlsrv_query($conexion,$sqlConsulta,[],['QueryTimeout'=>7200]);

            if($result) {
                array_push($articulosActualizados, $articulo);
            } else {
                array_push($codigosFallidos, $codigo);
            }
        }

        return [
            'exitoso' => $articulosActualizados,
            'fallido' => $codigosFallidos,
            'confirmado' => $confirmado === 'si'
        ];
    }

    private function obtener_sqlAccion($accion, $id_articulo, $id_atributo)
    {
        $sql = null;

        switch($accion)
        {
            case 'agregar':
                $sql = "INSERT INTO InvArticuloAtributo (InvArticuloId, InvAtributoId)
                    SELECT $id_articulo, $id_atributo
                    WHERE NOT EXISTS (
                        SELECT 1
                        FROM InvArticuloAtributo
                        WHERE InvArticuloId = $id_articulo AND InvAtributoId = $id_atributo
                    );
                ";
                break;
            case 'quitar':
                $sql = "DELETE FROM InvArticuloAtributo
                    WHERE InvArticuloId = $id_articulo AND InvAtributoId = $id_atributo;
                ";
                break;
            default:
                $sql = null;
                break;
        }

        return $sql;
    }

    private function obtener_articulo($codigo_barra, $codigo_interno)
    {
        $conexion = $this->obtener_conexionSmart();

        $sql = "SELECT TOP (1)
            InvArticulo.Id as id_articulo,
            InvArticulo.Descripcion as descripcion,
            InvCodigoBarra.CodigoBarra AS codigo_barra
        FROM InvCodigoBarra
        INNER JOIN InvArticulo ON InvCodigoBarra.InvArticuloId = InvArticulo.Id
        WHERE CodigoBarra = '$codigo_barra'";

        // Ejecutar consulta
        $result = sqlsrv_query($conexion,$sql,[],['QueryTimeout'=>7200]);
        $informacion = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

        return $informacion;
    }

    private function obtener_atributos($conn)
    {
        $sql = "SELECT Id ,Descripcion FROM InvAtributo ORDER BY Descripcion ASC";

        $result = sqlsrv_query($conn,$sql,[],['QueryTimeout'=>7200]);
        $lista = [];

        while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
            array_push($lista, [
                'id' => $row['Id'],
                'nombre' => mb_convert_encoding($row['Descripcion'], 'UTF-8', 'UTF-8')
            ]);
        }

        return $this->obtener_atributosValidos($lista);
    }

    private function obtener_atributosValidos(array $lista) 
    {
        $atributosValidos = [];

        foreach ($lista as $index => $atributo)
        {
            if(in_array(strtoupper($atributo['nombre']), self::ATRIBUTOS_VALIDOS)) {
                array_push($atributosValidos, $atributo);
            }
        }
        return $atributosValidos;
    }

    private function obtener_atributo($conn, $atributo_id)
    {
        $sql = "SELECT Id ,Descripcion FROM InvAtributo WHERE Id = $atributo_id";

        $result = sqlsrv_query($conn,$sql,[],['QueryTimeout'=>7200]);
        $atributo = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
        
        return ($atributo['Descripcion'] ?? null);
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

                if(strpos(strtolower($colValue), 'barra') > -1  && !$columnaIndex_barra) {
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
}
