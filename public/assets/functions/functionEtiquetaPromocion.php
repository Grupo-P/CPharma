<?php
    include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $IdArticulo = $_POST["IdArticulo"];
  $tipo = $_POST["tipo"];
    $clasificacion = $_POST["clasificacion"];
  $Etiqueta = FG_Generer_Etiqueta_Unica($IdArticulo,$clasificacion,$tipo);
  echo''.$Etiqueta;
?>
<?php
/**********************************************************************************/
    /*
        TITULO: FG_Generer_Etiqueta_Unica
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Generer_Etiqueta_Unica($IdArticulo,$clasificacion,$tipo) {
        $SedeConnection = FG_Mi_Ubicacion();
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $arraySugeridos = array();
        $Etiqueta = '';

    $FHoy = date("Y-m-d");
        $FManana = date("Y-m-d",strtotime($FHoy."+1 days"));

    $result1 = $connCPharma->query("SELECT COUNT(*) AS Cuenta FROM etiquetas WHERE id_articulo = '$IdArticulo' AND clasificacion = '$clasificacion'");
        $row1= $result1->fetch_assoc();
        $Cuenta = $row1['Cuenta'];

        if($Cuenta==1){

            $sql3 = SQL_Es_Dolarizado($IdArticulo);
            $result3 = sqlsrv_query($conn,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
            $Dolarizado = FG_Producto_Dolarizado($row3['Dolarizado']);

        if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){
                $Etiqueta = FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,false,false);
            }
            else if(($Dolarizado=='NO')&&($tipo!='DOLARIZADO')){
                $Etiqueta = FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,false,false);
            }
            else if($tipo=='TODO'){
                $Etiqueta = FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,false,false);
            }
            else{
                //$Etiqueta = 'EL ARTICULO NO PERTENECE A LA CATEGORIA SELECCIONADA';
            }
        }
        else{
            //$Etiqueta = 'EL ARTICULO NO PERTENECE A LA CATEGORIA SELECCIONADA';
        }

        return $Etiqueta;
        mysqli_close($connCPharma);
    sqlsrv_close($conn);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Etiqueta_Unica
        FUNCION: crea la etiqueta para el caso que corresponda
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Etiqueta_Unica($conn,$connCPharma,$IdArticulo,$Dolarizado,$IsPrecioAyer,$FechaCambio) {
        $Etiqueta = '';
        $mensajePie = "";

        $sql2 = SQG_Detalle_Articulo($IdArticulo);
        $result2 = sqlsrv_query($conn,$sql2);
        $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

        $CodigoArticulo = $row2["CodigoInterno"];
    $CodigoBarra = $row2["CodigoBarra"];
    $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
    $Existencia = $row2["Existencia"];
    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
    $IsTroquelado = $row2["Troquelado"];
    $IsIVA = $row2["Impuesto"];
    $UtilidadArticulo = $row2["UtilidadArticulo"];
    $UtilidadCategoria = $row2["UtilidadCategoria"];
    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
    $CondicionExistencia = 'CON_EXISTENCIA';

        if(intval($Existencia)>0){

            $PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

            if($IsPrecioAyer==true){

                $sqlCC = MySQL_DiasCero_PrecioAyer($IdArticulo,$FechaCambio);
                $resultCC = mysqli_query($connCPharma,$sqlCC);
                $rowCC = mysqli_fetch_assoc($resultCC);
                $PrecioAyer = $rowCC["precio"];

                if( floatval(round($PrecioHoy,2)) != floatval($PrecioAyer) ){

                    if($Dolarizado=='SI'){
                        $simbolo = '*';
                        $moneda = SigDolarPublico;

                        if(_MensajeDolar_== 'SI' && _EtiquetaDolar_=='SI'){
                            $tam_dolar = "font-size:1.7rem;";
                            $mensajePie = '
                                <tr>
                                    <td class="centrado titulo rowCenter" colspan="2">
                                        '._MensajeDolarLegal_.'
                                    </td>
                                </tr>
                            ';
                        }else{
                            $mensajePie = "";
                            $tam_dolar = "";
                        }

                        if(_EtiquetaDolar_=='SI'){
                            $precioPartes = explode(".",$PrecioHoy);
                            /*
                            $sqlSCN = "SELECT sum(CuentaNull) as SumCuentaNull from(
                                select M_PrecioTroquelado, IIF(M_PrecioTroquelado IS NULL,1,0) as CuentaNull from InvLote where InvLote.id in (
                                    select InvLoteAlmacen.InvLoteId from InvLoteAlmacen
                                    where InvLoteAlmacen.InvArticuloId=".$IdArticulo."
                                    and Existencia>0
                                    and (InvLoteAlmacen.InvAlmacenId=1 OR InvLoteAlmacen.InvAlmacenId=2)
                                )
                            ) as axu
                            ";
                            $resultSCN = sqlsrv_query($conn,$sqlSCN);
                            $rowSCN = sqlsrv_fetch_array($resultSCN,SQLSRV_FETCH_ASSOC);
                            $SumCuentaNull = $rowSCN["SumCuentaNull"];
                            */

                            $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));
                            $PrecioHoy = $PrecioHoy/$TasaActual;

                            $sqlCC = MySQL_DiasCero_PrecioAyer_Dolar($IdArticulo,$FechaCambio);
                            $resultCC = mysqli_query($connCPharma,$sqlCC);
                            $rowCC = mysqli_fetch_assoc($resultCC);
                            $PrecioAyer = $rowCC["precio_dolar"];

                            if(substr($precioPartes[1],-2)==DecimalEtiqueta){
                                $flag_imprime = true;
                            }
                            else{
                                $flag_imprime = false;
                            }
                        }else if(_EtiquetaDolar_=='NO'){
                            $flag_imprime = true;
                            $moneda = 'Bs.S';
                        }
                    }
                    else{
                        $simbolo = '';
                        $moneda = 'Bs.S';
                        $flag_imprime = true;
                    }

                    if($flag_imprime == true){
                        $connCPharma = FG_Conectar_CPharma();
                        $query = $connCPharma->query("SELECT * FROM unidads WHERE id_articulo = '$IdArticulo'");

                        if ($query->num_rows) {
                            $unidad= $query->fetch_assoc();

                            $unidadMinima = strtolower($unidad['unidad_minima']);
                            $divisor = intval($unidad['divisor']);
                            $precioUnidadMinima = (($PrecioHoy / $divisor) < 1) ? number_format($PrecioHoy / $divisor, 4, ',', '.') : number_format($PrecioHoy / $divisor, 2, ',', '.');

                            $unidadMinima = '
                                <tr>
                                    <td style="font-size: 2em; text-align: center; color: red" colspan="2">Precio por '.$unidadMinima.' '.$precioUnidadMinima.'</td>
                                </tr>
                            ';
                        } else {
                            $unidadMinima = '';
                        }

                        $Etiqueta = $Etiqueta.'
                            <table class="etq" style="display: inline;">
                                <thead class="etq">
                                    <tr>
                                        <td class="centrado titulo rowCenter" colspan="2">
                                            Código: '.$CodigoBarra.'
                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="etq">
                                    <tr rowspan="2">
                                        <td class="centrado descripcion aumento rowCenter" colspan="2">
                                            <strong>'.$Descripcion.'</strong>
                                        </td>
                                    </tr>
                                    ';
                                        if( floatval(round($PrecioHoy,2)) < floatval($PrecioAyer) ){
                                        $Etiqueta = $Etiqueta.'
                                            <tr>
                                                <td class="izquierda rowIzq rowIzqA aumento1" style="color:red;">
                                                    Precio '.$moneda.' Antes
                                                </td>
                                                <td class="derecha rowDer rowDerA aumento1" style="color:red;">
                                                    <del>
                                                    '.number_format ($PrecioAyer,2,"," ,"." ).'
                                                    </del>
                                                </td>
                                            </tr>
                                        ';
                                        }
                                    $Etiqueta = $Etiqueta.'
                                    <tr>
                                        <td class="centrado rowDer rowDerA aumentoPrecio preciopromo" colspan="2">
                                            <strong>
                                            '.number_format ($PrecioHoy,2,"," ,"." ).'
                                            </strong>
                                        </td>
                                    </tr>
                                    '.$unidadMinima.'
                                    <tr>
                                        <td class="izquierda rowIzq rowIzqA aumento1">
                                            <strong>Total a Pagar '.$moneda.'</strong>
                                        </td>
                                        <td class="derecha rowDer rowDerA aumento1">
                                            <strong>'.$simbolo.'</strong> '.date("d-m-Y").'
                                        </td>
                                    </tr>
                                        '.$mensajePie.'
                                </tbody>
                            </table>
                        ';
                    }
                    else{
                        $Etiqueta = $Etiqueta.'
                            <table class="etq" style="display: inline;">
                                <thead class="etq">
                                    <tr>
                                        <td class="centrado titulo rowCenter aumento" colspan="2">
                                            Código: '.$CodigoBarra.'
                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="etq">
                                    <tr rowspan="2">
                                        <td class="centrado descripcion aumento rowCenter" colspan="2">
                                            <strong>'.$Descripcion.'</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="centrado rowDer rowDerA aumento1 preciopromo" colspan="2">
                                            <strong class="text-danger">
                                            Solicite ayuda al dpto. de procesamiento
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        ';
                    }
                }
                else{
                    $Etiqueta = 'EL ARTICULO NO POSEE CAMBIO DE PRECIO';
                }
            }
            else if($IsPrecioAyer==false){

                if($Dolarizado=='SI'){
                    $bolivarDigital = '';
                    $simbolo = '*';
                    $moneda = SigDolarPublico;

                    if(_MensajeDolar_== 'SI' && _EtiquetaDolar_=='SI'){
                        $tam_dolar = "font-size:1.7rem;";
                        $mensajePie = '
                            <tr>
                                <td class="centrado titulo rowCenter" colspan="2">
                                    '._MensajeDolarLegal_.'
                                </td>
                            </tr>
                        ';
                    }else{
                        $tam_dolar = "";
                        $mensajePie = "";
                    }

                    if(_EtiquetaDolar_=='SI'){
                        $precioPartes = explode(".",$PrecioHoy);
                        /*
                        $sqlSCN = "SELECT sum(CuentaNull) as SumCuentaNull from(
                            select M_PrecioTroquelado, IIF(M_PrecioTroquelado IS NULL,1,0) as CuentaNull from InvLote where InvLote.id in (
                                select InvLoteAlmacen.InvLoteId from InvLoteAlmacen
                                where InvLoteAlmacen.InvArticuloId=".$IdArticulo."
                                and Existencia>0
                                and (InvLoteAlmacen.InvAlmacenId=1 OR InvLoteAlmacen.InvAlmacenId=2)
                            )
                        ) as axu
                        ";
                        $resultSCN = sqlsrv_query($conn,$sqlSCN);
                        $rowSCN = sqlsrv_fetch_array($resultSCN,SQLSRV_FETCH_ASSOC);
                        $SumCuentaNull = $rowSCN["SumCuentaNull"];
                        */

                        $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));
                        $PrecioHoy = $PrecioHoy/$TasaActual;

                        if(substr($precioPartes[1],-2)==DecimalEtiqueta){
                            $flag_imprime = true;
                        }
                        else{
                            $flag_imprime = false;
                        }
                    }else if(_EtiquetaDolar_=='NO'){
                        $flag_imprime = true;
                        $moneda = 'Bs.S';
                    }
                }
                else{
                    $precioBolivarDigital = number_format(precioReconversion($PrecioHoy, 'BSS'), 2);
                    $bolivarDigital = '
                        <strong>Bs.S '.$precioBolivarDigital.'</strong>
                    ';
                    $simbolo = '';
                    $moneda = 'Bs.S';
                    $flag_imprime = true;
                }

                if($flag_imprime == true){
                    $connCPharma = FG_Conectar_CPharma();
                    $query = $connCPharma->query("SELECT * FROM unidads WHERE id_articulo = '$IdArticulo'");

                    if ($query->num_rows) {
                        $unidad= $query->fetch_assoc();

                        $unidadMinima = strtolower($unidad['unidad_minima']);
                        $divisor = intval($unidad['divisor']);
                        $precioUnidadMinima = (($PrecioHoy / $divisor) < 1) ? number_format($PrecioHoy / $divisor, 4, ',', '.') : number_format($PrecioHoy / $divisor, 2, ',', '.');

                        $unidadMinima = '
                            <tr>
                                <td style="font-size: 2em; text-align: center; color: red" colspan="2">Precio por '.$unidadMinima.' '.$precioUnidadMinima.'</td>
                            </tr>
                        ';
                    } else {
                        $unidadMinima = '';
                    }

                    $tamano = ($bolivarDigital)?'aumento':'aumentoPrecio';

                    $precio = ($Dolarizado == 'SI') ? SigDolarPublico.' '.number_format ($PrecioHoy,2,"," ,"." ) : SigVe.' '.number_format (precioReconversion($PrecioHoy, 'BSD'),2,"," ,"." );

                    $Etiqueta = $Etiqueta.'
                        <table class="etq" style="display: inline;">
                            <thead class="etq">
                                <tr>
                                    <td class="centrado titulo rowCenter aumento1" colspan="2">
                                        Código: '.$CodigoBarra.'
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="etq">
                                <tr rowspan="2">
                                    <td class="centrado descripcion aumento rowCenter" colspan="2">
                                        '.$Descripcion.'
                                    </td>
                                </tr>
                                <tr>
                                    <td class="centrado rowDer rowDerA '.$tamano.' preciopromo" colspan="2">
                                        <strong>
                                        '.$precio.'
                                        </strong>
                                    </td>
                                </tr>
                                '.$unidadMinima.'
                                <tr>
                                    <td class="izquierda rowIzq rowIzqA aumento1">

                                    </td>
                                    <td class="derecha rowDer rowDerA aumento1">
                                        <strong>'.$simbolo.'</strong> '.date("d-m-Y").'
                                    </td>
                                </tr>
                                '.$mensajePie.'
                            </tbody>
                        </table>
                    ';
                }
                else{
                    $Etiqueta = $Etiqueta.'
                        <table class="etq" style="display: inline;">
                            <thead class="etq">
                                <tr>
                                    <td class="centrado titulo rowCenter aumento1" colspan="2">
                                        Código: '.$CodigoBarra.'
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="etq">
                                <tr rowspan="2">
                                    <td class="centrado descripcion aumento rowCenter aumento" colspan="2">
                                        <strong>'.$Descripcion.'</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="centrado rowDer rowDerA aumento1 preciopromo" colspan="2">
                                        <strong class="text-danger">
                                        Solicite ayuda al dpto. de procesamiento
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    ';
                }
            }
            else{
                $Etiqueta = 'EL ARTICULO PRESENTO CAMBIO DE PRECIO';
            }
        }
        else{
            $Etiqueta = 'EL ARTICULO NO POSEE EXISTENCIA';
        }
        return $Etiqueta;
    }
?>
