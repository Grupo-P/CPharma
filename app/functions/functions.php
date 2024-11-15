<?php
/*Funciones Generales al sistema CPharma*/
    /**********************************************************************************/
    /*
        TITULO: FG_Armar_Json
        FUNCION: Armar un arreglo Json con las palabras claves encontradas
        RETORNO: Arreglo Json
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Armar_Json($sql,$SedeConnection) {
        $result = FG_Consulta_Especial_Smartpharma ($sql,$SedeConnection);
        $arrayJson = FG_array_flatten_recursive($result);
        return json_encode($arrayJson);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Consulta_Especial_Smartpharma
        FUNCION: Hacer una consulta a smartpharma
        RETORNO: Arreglo de datos
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Consulta_Especial_Smartpharma($sql,$SedeConnection) {
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        if($conn) {
            $stmt = sqlsrv_query($conn,$sql);
            if($stmt === false) {
                die(print_r(sqlsrv_errors(),true));
            }
            $i = 0;
            $final[$i] = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_NUMERIC);
            $i++;
            if($final[0] != NULL) {
                while($result = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_NUMERIC)) {
                    $final[$i] = $result;
                    $i++;
                }
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
                return $final;
            }
            else {
                sqlsrv_free_stmt( $stmt);
                sqlsrv_close($conn);
                return NULL;
            }
        }
        else {
            die(print_r(sqlsrv_errors(),true));
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_array_flatten_recursive
        FUNCION: Iterar en el arreglo para la busqueda de variables
        RETORNO: Palabra clave buscada
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_array_flatten_recursive($array) {
       if(!is_array($array)) {
           return false;
       }
       $flat = array();
       $RII = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
       $i = 0;
       foreach($RII as $key => $value) {
           $flat[$i] = utf8_encode($value);
           $i++;
       }
       return $flat;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_LastRestoreDB
        FUNCION: Busca la fecha de la ultima restauracion de la base de datos
        RETORNO: Fecha de ultima restauracion
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_LastRestoreDB($nameDataBase,$SedeConnection){
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $sql = "SELECT TOP 1 * FROM $nameDataBase.[dbo].VenFactura ORDER BY FechaDocumento DESC";
        $result = sqlsrv_query($conn,$sql);
        $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
        $FechaRestauracion = $row["FechaDocumento"]->format("Y-m-d h:i:s a");
        return $FechaRestauracion;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Validar_Conectividad
        FUNCION: Valida la conexion con un servidor definido
        RETORNO: Estatus de la conectividad
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Validar_Conectividad($SedeConnection){
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $Conectividad = false;

        if($conn) {
     $Conectividad = true;
        }else{
     $Conectividad = false;
        }
    return $Conectividad;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Mi_Ubicacion
        FUNCION: Descifrar desde que sede estoy entrando a la aplicacion
        RETORNO: Sede en la que me encuentro
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Mi_Ubicacion(){
        $NombreCliente = gethostname();
        $IpCliente = gethostbyname($NombreCliente);
        $Octeto = explode(".", $IpCliente);

        switch ($Octeto[2]) {
        //INICIO BLOQUE DE FTN
            case '1':
                return 'FTN';
            break;
            case '2':
                return 'FTN';
            break;
        //FIN BLOQUE DE FTN
        //INICIO BLOQUE DE FLL
            case '7':
                return 'FLL';
            break;
        //FIN BLOQUE DE FLL
        //INICIO BLOQUE DE FAU
            case '12':
                if($IpCliente == "192.168.12.36" || $IpCliente == "192.168.12.40"){
                    return 'DBs';
                }else if($IpCliente == "192.168.12.30"){
                    return 'GP';
                }else{
                    return 'FAU';
                }
            break;
        //FIN BLOQUE DE FAU
        //INICIO BLOQUE DE KDI
            case '15':
                return 'KDI';
            break;
        //FIN BLOQUE DE KDI
        //INICIO BLOQUE DE FSM
            case '18':
                return 'FSM';
            break;
        //FIN BLOQUE DE FSM
        //INICIO BLOQUE DE FEC
            case '20':
                return 'FEC';
            break;
        //FIN BLOQUE DE FEC
        //INICIO BLOQUE DE FLF
            case '28':
                return 'FLF';
            break;
        //FIN BLOQUE DE FLF
        //INICIO BLOQUE DE CDD
        case '10':
            return 'CDD';
        break;
        //FIN BLOQUE DE CDD
        //INICIO BLOQUE DE KD73
            case '60':
                return 'KD73';
            break;
        //FIN BLOQUE DE KD73
            default:
                return 'ARG';
                //return ''.$Octeto[2];
            break;
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Nombre_Sede
        FUNCION: Retornar el nombre de la sede con la que se esta conectando
        RETORNO: Nombre de la sede conectada
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Nombre_Sede($SedeConnection) {
        switch($SedeConnection) {
        //INICIO BLOQUE DE FTN
            case 'FTN':
                $sede = SedeFTN;
                return $sede;
            break;
            case 'FTNFLL':
                $sede = SedeFLLOFF;
                return $sede;
            break;
            case 'FTNFAU':
                $sede = SedeFAUOFF;
                return $sede;
            break;
            case 'FTNKDI':
                $sede = SedeKDIOFF;
                return $sede;
            break;
            case 'FTNFSM':
                $sede = SedeFSMOFF;
                return $sede;
            break;
        //FIN BLOQUE DE FTN
        //INICIO BLOQUE DE FLL
            case 'FLL':
                $sede = SedeFLL;
                return $sede;
            break;
            case 'FLLFTN':
                $sede = SedeFTNOFF;
                return $sede;
            break;
            case 'FLLFAU':
                $sede = SedeFAUOFF;
                return $sede;
            break;
            case 'FLLKDI':
                $sede = SedeKDIOFF;
                return $sede;
            break;
            case 'FLLFSM':
                $sede = SedeFSMOFF;
                return $sede;
            break;
        //FIN BLOQUE DE FLL
        //INICIO BLOQUE DE FAU
            case 'FAU':
                $sede = SedeFAU;
                return $sede;
            break;
            case 'FAUFTN':
                $sede = SedeFTNOFF;
                return $sede;
            break;
            case 'FAUFLL':
                $sede = SedeFLLOFF;
                return $sede;
            break;
            case 'FAUKDI':
                $sede = SedeKDIOFF;
                return $sede;
            break;
            case 'FAUFSM':
                $sede = SedeFSMOFF;
                return $sede;
            break;
        //FIN BLOQUE DE FAU
        //INICIO BLOQUE DE KDI
            case 'KDI':
                $sede = SedeKDI;
                return $sede;
            break;
            case 'KDIFTN':
                $sede = SedeFTNOFF;
                return $sede;
            break;
            case 'KDIFLL':
                $sede = SedeFLLOFF;
                return $sede;
            break;
            case 'KDIFAU':
                $sede = SedeFAUOFF;
                return $sede;
            break;
            case 'KDIFSM':
                $sede = SedeFSMOFF;
                return $sede;
            break;
        //FIN BLOQUE DE KDI
        //INICIO BLOQUE DE FSM
            case 'FSM':
                $sede = SedeFSM;
                return $sede;
            break;
            case 'FSMFTN':
                $sede = SedeFTNOFF;
                return $sede;
            break;
            case 'FSMFLL':
                $sede = SedeFLLOFF;
                return $sede;
            break;
            case 'FSMFAU':
                $sede = SedeFAUOFF;
                return $sede;
            break;
            case 'FSMKDI':
                $sede = SedeKDIOFF;
                return $sede;
            break;
        //FIN BLOQUE DE FSM
        //INICIO BLOQUE DE GRUPO P
            case 'GP':
                $sede = SedeGP;
                return $sede;
            break;
            case 'GPFTN':
                $sede = SedeFTNOFF;
                return $sede;
            break;
            case 'GPFLL':
                $sede = SedeFLLOFF;
                return $sede;
            break;
            case 'GPFAU':
                $sede = SedeFAUOFF;
                return $sede;
            break;
            case 'GPKDI':
                $sede = SedeKDIOFF;
                return $sede;
            break;
            case 'GPFSM':
                $sede = SedeFSMOFF;
                return $sede;
            break;
        //FIN BLOQUE DE GRUPO P
        //INICIO BLOQUE FEC
            case 'FEC':
                $sede = SedeFEC;
                return $sede;
            break;
        //FIN BLOQUE FEC
        //INICIO BLOQUE FLF
            case 'FLF':
                $sede = SedeFLF;
                return $sede;
            break;
        //FIN BLOQUE FLF
        //INICIO BLOQUE CDD
        case 'CDD':
            $sede = SedeCDD;
            return $sede;
        break;
        //FIN BLOQUE CDD
        //INICIO BLOQUE KD73
            case 'KD73':
                $sede = SedeKD73;
                return $sede;
            break;
        //FIN BLOQUE KD73
        //INICIO BLOQUE DE TEST
            case 'DBs':
                $sede = SedeDBs;
                return $sede;
            break;
            case 'ARG':
                $sede = SedeDBsa;
                return $sede;
            break;
        //FIN BLOQUE DE TEST
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Conectar_Smartpharma
        FUNCION: Conectar el sistema con la sede que se desea
        RETORNO: Conexion a Smartpharma
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Conectar_Smartpharma($SedeConnection) {
        switch($SedeConnection) {
        //INICIO BLOQUE DE FTN
            case 'FTN':
                $connectionInfo = array(
                    "Database"=>nameFTN,
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNFLL':
                $connectionInfo = array(
                    "Database"=>nameFLLOFF,
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNFAU':
                $connectionInfo = array(
                    "Database"=>nameFAUOFF,
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNKDI':
                $connectionInfo = array(
                    "Database"=>nameKDIOFF,
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNFSM':
                $connectionInfo = array(
                    "Database"=>nameFSMOFF,
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FTN
        //INICIO BLOQUE DE FLL
            case 'FLL':
                $connectionInfo = array(
                    "Database"=>nameFLL,
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLFTN':
                $connectionInfo = array(
                    "Database"=>nameFTNOFF,
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLFAU':
                $connectionInfo = array(
                    "Database"=>nameFAUOFF,
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLKDI':
                $connectionInfo = array(
                    "Database"=>nameKDIOFF,
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLFSM':
                $connectionInfo = array(
                    "Database"=>nameFSMOFF,
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FLL
        //INICIO BLOQUE DE FAU
            case 'FAU':
                $connectionInfo = array(
                    "Database"=>nameFAU,
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUFTN':
                $connectionInfo = array(
                    "Database"=>nameFTNOFF,
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUFLL':
                $connectionInfo = array(
                    "Database"=>nameFLLOFF,
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUKDI':
                $connectionInfo = array(
                    "Database"=>nameKDIOFF,
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUFSM':
                $connectionInfo = array(
                    "Database"=>nameFSMOFF,
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FAU
        //INICIO BLOQUE DE GRUPO P
            case 'GP':
                $connectionInfo = array(
                    "Database"=>nameGP,
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFTN':
                $connectionInfo = array(
                    "Database"=>nameFTNOFF,
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFLL':
                $connectionInfo = array(
                    "Database"=>nameFLLOFF,
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFAU':
                $connectionInfo = array(
                    "Database"=>nameFAUOFF,
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPKDI':
                $connectionInfo = array(
                    "Database"=>nameKDIOFF,
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFSM':
                $connectionInfo = array(
                    "Database"=>nameFSMOFF,
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE GRUPO P
        //INICIO BLOQUE DE KDI
            case 'KDI':
                $connectionInfo = array(
                    "Database"=>nameKDI,
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFLL':
                $connectionInfo = array(
                    "Database"=>nameFLLOFF,
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFAU':
                $connectionInfo = array(
                    "Database"=>nameFAUOFF,
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFTN':
                $connectionInfo = array(
                    "Database"=>nameFTNOFF,
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFSM':
                $connectionInfo = array(
                    "Database"=>nameFSMOFF,
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE KDI
        //INICIO BLOQUE DE FSM
            case 'FSM':
                $connectionInfo = array(
                    "Database"=>nameFSM,
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMFLL':
                $connectionInfo = array(
                    "Database"=>nameFLLOFF,
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMFAU':
                $connectionInfo = array(
                    "Database"=>nameFAUOFF,
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMFTN':
                $connectionInfo = array(
                    "Database"=>nameFTNOFF,
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMKDI':
                $connectionInfo = array(
                    "Database"=>nameKDIOFF,
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE KDI
        //INICIO BLOQUE FEC
            case 'FEC':
                $connectionInfo = array(
                    "Database"=>nameFEC,
                    "UID"=>userFEC,
                    "PWD"=>passFEC
                );
                $conn = sqlsrv_connect(serverFEC,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FEC
        //INICIO BLOQUE FLF
            case 'FLF':
                $connectionInfo = array(
                    "Database"=>nameFLF,
                    "UID"=>userFLF,
                    "PWD"=>passFLF
                );
                $conn = sqlsrv_connect(serverFLF,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FLF

        //INICIO BLOQUE CDD
        case 'CDD':
            $connectionInfo = array(
                "Database"=>nameCDD,
                "UID"=>userCDD,
                "PWD"=>passCDD
            );
            $conn = sqlsrv_connect(serverCDD,$connectionInfo);
            return $conn;
        break;
        //FIN BLOQUE DE CDD

        //INICIO BLOQUE KD73
            case 'KD73':
                $connectionInfo = array(
                    "Database"=>nameKD73,
                    "UID"=>userKD73,
                    "PWD"=>passKD73
                );
                $conn = sqlsrv_connect(serverKD73,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE KD73
        //INICIO BLOQUE DE TEST
            case 'DBs':
                $connectionInfo = array(
                    "Database"=>nameDBs,
                    "UID"=>userDBs,
                    "PWD"=>passDBs
                );
                $conn = sqlsrv_connect(serverDBs,$connectionInfo);
                return $conn;
            break;
            case 'ARG':
                $connectionInfo = array(
                    "Database"=>nameDBsa,
                    "UID"=>userDBsa,
                    "PWD"=>passDBsa
                );
                $conn = sqlsrv_connect(serverDBsa,$connectionInfo);
                return $conn;
            break;
            //FIN BLOQUE DE TEST
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Conectar_CPharma
        FUNCION: Conexion con servidor de XAMPP
        RETORNO: Conexion a XAMPP
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Conectar_CPharma() {
        $conexion = mysqli_connect(serverCP,userCP,passCP);
        mysqli_select_db($conexion,nameCP);
    return $conexion;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Conectar_Mod_Atte_Clientes
        FUNCION: Conectar el sistema con la sede que se desea
        RETORNO: Conexion a Smartpharma
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Conectar_Mod_Atte_Clientes($SedeConnection) {
        switch($SedeConnection) {
        //INICIO BLOQUE DE FTN
            case 'FTN':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNFLL':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNFAU':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNKDI':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
            case 'FTNFSM':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFTN,
                    "PWD"=>passFTN
                );
                $conn = sqlsrv_connect(serverFTN,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FTN
        //INICIO BLOQUE DE FLL
            case 'FLL':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLFTN':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLFAU':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLKDI':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
            case 'FLLFSM':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFLL,
                    "PWD"=>passFLL
                );
                $conn = sqlsrv_connect(serverFLL,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FLL
        //INICIO BLOQUE DE FAU
            case 'FAU':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUFTN':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUFLL':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUKDI':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
            case 'FAUFSM':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFAU,
                    "PWD"=>passFAU
                );
                $conn = sqlsrv_connect(serverFAU,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FAU
        //INICIO BLOQUE DE GRUPO P
            case 'GP':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFTN':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFLL':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFAU':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPKDI':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
            case 'GPFSM':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userGP,
                    "PWD"=>passGP
                );
                $conn = sqlsrv_connect(serverGP,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE GRUPO P
        //INICIO BLOQUE DE KDI
            case 'KDI':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFTN':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFLL':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFAU':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
            case 'KDIFSM':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userKDI,
                    "PWD"=>passKDI
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE KDI
        //INICIO BLOQUE DE FSM
            case 'FSM':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMFTN':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMFLL':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMFAU':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
            case 'FSMKDI':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userFSM,
                    "PWD"=>passFSM
                );
                $conn = sqlsrv_connect(serverFSM,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE FSM
        //INICIO BLOQUE DE FEC
        case 'FEC':
            $connectionInfo = array(
                "Database"=>"Mod_Atte_Cliente",
                "UID"=>userFEC,
                "PWD"=>passFEC
            );
            $conn = sqlsrv_connect(serverFEC,$connectionInfo);
            return $conn;
        break;
        //FIN BLOQUE DE FEC
        //INICIO BLOQUE DE FLF
        case 'FLF':
            $connectionInfo = array(
                "Database"=>"Mod_Atte_Cliente",
                "UID"=>userFLF,
                "PWD"=>passFLF
            );
            $conn = sqlsrv_connect(serverFLF,$connectionInfo);
            return $conn;
        break;
        //FIN BLOQUE DE FLF

        //INICIO BLOQUE DE CDD
        case 'CDD':
            $connectionInfo = array(
                "Database"=>"Mod_Atte_Cliente",
                "UID"=>userCDD,
                "PWD"=>passCDD
            );
            $conn = sqlsrv_connect(serverCDD,$connectionInfo);
            return $conn;
        break;
        //FIN BLOQUE DE CDD

        //INICIO BLOQUE DE KD73
            case 'KD73':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userKD73,
                    "PWD"=>passKD73
                );
                $conn = sqlsrv_connect(serverKDI,$connectionInfo);
                return $conn;
            break;
        //FIN BLOQUE DE KD73
        //INICIO BLOQUE DE TEST
            case 'DBs':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userDBs,
                    "PWD"=>passDBs
                );
                $conn = sqlsrv_connect(serverDBs,$connectionInfo);
                return $conn;
            break;
            case 'ARG':
                $connectionInfo = array(
                    "Database"=>"Mod_Atte_Cliente",
                    "UID"=>userDBsa,
                    "PWD"=>passDBsa
                );
                $conn = sqlsrv_connect(serverDBsa,$connectionInfo);
                return $conn;
            break;
            //FIN BLOQUE DE TEST
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Limpiar_Texto
        FUNCION: limpia el texto de caracteres no imprimibles
        RETORNO: Texto limpio
        DESARROLLADO POR: SERGIO COVA
        ACTUALIZADO POR:  MANUEL HENRIQUEZ 07/10/2019
     */
    function FG_Limpiar_Texto($texto) {
        $texto = utf8_encode($texto);
        $texto = preg_replace("/[^A-Za-z0-9_\-\&\ñ\Ñ\'\(\)\.\,\s][á|é|í|ó|ú|Á|É|Í|Ó|Ú]/",'',$texto);
        return $texto;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Guardar_Auditoria
        FUNCION: capturar y guardar el evento en la auditoria
        RETORNO: no aplica
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Guardar_Auditoria($accion,$tabla,$registro) {
        $connCPharma = FG_Conectar_CPharma();
        $date = new DateTime('now');
        $date = $date->format("Y-m-d H:i:s");
        $user = auth()->user()->name;
        $sql = MySQL_Guardar_Auditoria($accion,$tabla,$registro,$user,$date);
        mysqli_query($connCPharma,$sql);
        mysqli_close($connCPharma);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Producto_Gravado
        FUNCION: determina si un producto es gravado o no
        RETORNO: retorna si el producto es gravado o no
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Producto_Gravado($IsIVA){
        if($IsIVA == FG_ID_Impuesto_Sede()) {
            $EsGravado = 'SI';
        }
        else {
            $EsGravado = 'NO';
        }
        return $EsGravado;
    }

    function FG_ID_Impuesto_Sede(){
        $sede = FG_Mi_Ubicacion();

        if($sede == 'CDD'){
            return 3; //Este es el ID FinConceptoImpto para CDD
        }else{
            return 1; //Este es el ID FinConceptoImpto para el resto de sedes
        }
    }

    /**********************************************************************************/
    /*
        TITULO: FG_Tipo_Producto
        FUNCION: Determina si el producto es miscelaneo o medicina
        RETORNO: Retorna si el producto es miscelaneo o medicina
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Tipo_Producto($TipoArticulo) {
        if($TipoArticulo == 0) {
            $Tipo = 'MISCELANEO';
        }
        else {
            $Tipo = 'MEDICINA';
        }
        return $Tipo;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Producto_Dolarizado
        FUNCION: Determina si el producto esta dolarizado
        RETORNO: Retorna si el producto esta dolarizado o no
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Producto_Dolarizado($IsDolarizado) {
        if($IsDolarizado == 0) {
            $EsDolarizado = 'NO';
        }
        else {
            $EsDolarizado = 'SI';
        }
        return $EsDolarizado;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Tasa_Fecha
        FUNCION: Buscar el valor de la tasa
        RETORNO: Valor de la tasa
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Tasa_Fecha($connCPharma,$Fecha) {
        $sql = MySQL_Tasa_Fecha($Fecha);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $Tasa = $row['tasa'];
        return $Tasa;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Tasa_Fecha
        FUNCION: Buscar el valor de la tasa
        RETORNO: Valor de la tasa
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Tasa_Fecha_Venta($connCPharma,$Fecha) {
        $sql = MySQL_Tasa_Fecha_Venta($Fecha);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $Tasa = $row['tasa'];
        return $Tasa;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Tasa_Fecha_Venta_General
        FUNCION: Buscar el valor de la tasa
        RETORNO: Valor de la tasa
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Tasa_Fecha_Venta_General($connCPharma) {
        $sql = MySQL_Tasa_Fecha_Venta_General();
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $Tasa = $row['tasa'];
        return $Tasa;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Rango_Dias
        FUNCION: Calcular el rango de diferencia de dias entre el las fechas
        RETORNO: rango de dias
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Rango_Dias($FInicial,$FFinal) {
        $FechaI = new DateTime($FInicial);
    $FechaF = new DateTime($FFinal);
    $Rango = $FechaI->diff($FechaF);
    $Rango = $Rango->format('%a');
    return $Rango;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Rango_Dias_Negativo
        FUNCION: Calcular el rango de diferencia de dias entre el las fechas
        RETORNO: rango de dias
        DESARROLLADO POR: NISAUL DELGADO
     */
    function FG_Rango_Dias_Negativo($FInicial,$FFinal) {
        $FechaI = new DateTime($FInicial);
    $FechaF = new DateTime($FFinal);
    $Rango = $FechaI->diff($FechaF);
    $Rango = $Rango->format('%r%a');
    return $Rango;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Venta_Diaria
        FUNCION: Calcular la venta al diaria promedio en base al rango de dias
        RETORNO: Venta diaria promedio
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Venta_Diaria($Venta,$RangoDias) {
        $VentaDiaria = 0;
        if($RangoDias != 0){
            $VentaDiaria = $Venta/$RangoDias;
        }
        $VentaDiaria = round($VentaDiaria,2);
        return $VentaDiaria;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Dias_Restantes
        FUNCION: Calcular los dias restantes de inventario promedio
        RETORNO: Dias restantes promedio
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Dias_Restantes($Existencia,$VentaDiaria) {
        $DiasRestantes = 0;
        if($VentaDiaria != 0){
            $DiasRestantes = $Existencia/$VentaDiaria;
        }
        $DiasRestantes = round($DiasRestantes,2);
        return $DiasRestantes;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Cantidad_Pedido
        FUNCION: calcular la cantidad a pedir
        RETORNO: cantidad a pedir
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia) {
        $CantidadPedido = (($VentaDiaria * $DiasPedido)-$Existencia);
        return $CantidadPedido;
    }
    /**********************************************************************************/
    /*
        TITULO: ProductoUnico
        FUNCION: determinar si un prducto es unico
        RETORNO: SI o NO segun sea el caso
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Producto_Unico($conn,$IdArticulo,$IdProveedor) {
        $sql = SQL_Provedor_Unico($IdProveedor,$IdArticulo);
        $params = array();
        $options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
        $result = sqlsrv_query($conn,$sql,$params,$options);
        $row_count = sqlsrv_num_rows($result);

        if($row_count == 0) {
            $Unico = 'SI';
        }
        else {
            $Unico = 'NO';
        }
        return $Unico;
    }
  /**********************************************************************************/
    /*
        TITULO: FG_Validar_Fechas
        FUNCION: Calcula la diferencia entre ambas fechas restando la primera a la segunda
        RETORNO:
            - Un numero positivo en caso de que la segunda fecha sea mayor
            - Un numero negativo en caso de que la segunda fecha sea menor
            - Cero en caso de que ambas fechas sean iguales
        DESARROLLADO POR: MANUEL HENRIQUEZ
     */
    function FG_Validar_Fechas($Fecha1,$Fecha2) {
        $fecha_inicial = new DateTime($Fecha1);
        $fecha_final = new DateTime($Fecha2);

        $diferencia = $fecha_inicial->diff($fecha_final);
        $diferencia_numero = (int)$diferencia->format('%R%a');

        return $diferencia_numero;
  }
  /**********************************************************************************/
  /*
        TITULO: FG_Traslado_Detalle
        FUNCION: Ejecuta las validaciones para el traslado detalle
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Traslado_Detalle($SedeConnection,$NumeroAjuste,$IdAjuste) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    //TODO: En caso de falla comentar aqui
    $array_result = array();

    $sql = SQL_Articulos_Ajuste($IdAjuste);
    $result = sqlsrv_query($conn,$sql);

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["InvArticuloId"];
      $Cantidad = $row["Cantidad"];
      $IdLote = $row["lote"];
      $IdTraslado = $NumeroAjuste;

      $sql1 = SQG_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
        $ExistenciaAlmacen1 = $row1["ExistenciaAlmacen1"];
        $ExistenciaAlmacen2 = $row1["ExistenciaAlmacen2"];
        $IsTroquelado = $row1["Troquelado"];
        $IsIVA = $row1["Impuesto"];
        $UtilidadArticulo = $row1["UtilidadArticulo"];
        $UtilidadCategoria = $row1["UtilidadCategoria"];
        $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
        $PrecioCompraBrutoAlmacen1 = $row1["PrecioCompraBrutoAlmacen1"];
        $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
        $PrecioCompraBrutoAlmacen2 = $row1["PrecioCompraBrutoAlmacen2"];
        $PrecioCompraBruto = $row1["PrecioCompraBruto"];
      $Dolarizado = $row1["Dolarizado"];
      $Gravado = FG_Producto_Gravado($IsIVA);
      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $CondicionExistencia = 'CON_EXISTENCIA';

      $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);

      if($Existencia==0){

        $sql3 = SQG_Detalle_Articulo_Lote($IdLote,$IdArticulo);
          $result3 = sqlsrv_query($conn,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $ExistenciaSE = $row3["Existencia"];
            $ExistenciaAlmacen1SE = $row3["ExistenciaAlmacen1"];
            $ExistenciaAlmacen2SE = $row3["ExistenciaAlmacen2"];
            $IsTroqueladoSE = $row3["Troquelado"];
            $IsIVASE = $row3["Impuesto"];
            $UtilidadArticuloSE = $row3["UtilidadArticulo"];
            $UtilidadCategoriaSE = $row3["UtilidadCategoria"];
            $TroquelAlmacen1SE = $row3["TroquelAlmacen1"];
            $PrecioCompraBrutoAlmacen1SE = $row3["PrecioCompraBrutoAlmacen1"];
            $TroquelAlmacen2SE = $row3["TroquelAlmacen2"];
            $PrecioCompraBrutoAlmacen2SE = $row3["PrecioCompraBrutoAlmacen2"];
            $PrecioCompraBrutoSE = $row3["PrecioCompraBruto"];
            $CondicionExistenciaSE = 'SIN_EXISTENCIA';

            $UtilidadSE = FG_Utilidad_Alfa($UtilidadArticuloSE,$UtilidadCategoriaSE);

        if($Dolarizado=='SI') {

            //TODO: En caso de falla comentar aqui
            $array_result = FG_costo_mayor($IdArticulo,$IdLote,$CondicionExistenciaSE);
                if($array_result['fallas']!=""){
                    echo 'Se presento una o varias fallas, a continuacion se presentan en detalle: <br>'.$array_result['fallas'];
                    $costo_unit_usd_sin_iva = round(0.01,2);
                }
                else{
                    $costo_unit_usd_sin_iva = round($array_result['costo_D'],2);
                }

            //$TasaActualSE = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
            //$PrecioSE = FG_Calculo_Precio_Alfa($ExistenciaSE,$ExistenciaAlmacen1SE,$ExistenciaAlmacen2SE,$IsTroqueladoSE,$UtilidadArticuloSE,$UtilidadCategoriaSE,$TroquelAlmacen1SE,$PrecioCompraBrutoAlmacen1SE,$TroquelAlmacen2SE, $PrecioCompraBrutoAlmacen2SE,$PrecioCompraBrutoSE,$IsIVASE,$CondicionExistenciaSE);

            if($Gravado=='SI' && $UtilidadSE!= 1){
              //$costo_unit_bs_sin_iva = ($PrecioSE/Impuesto)*$UtilidadSE;
              //$costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
              $total_imp_usd = round(($costo_unit_usd_sin_iva*$Cantidad)*(Impuesto-1),2);
            }
            else if($Gravado== 'NO' && $UtilidadSE!= 1){
              //$costo_unit_bs_sin_iva = ($PrecioSE)*$UtilidadSE;
              //$costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
              $total_imp_usd = 0.00;
            }
            $total_usd = round((($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd),2);
            $costo_unit_bs_sin_iva = '-';
            $total_imp_bs = '-';
            $total_bs = '-';
          }
          else if($Dolarizado=='NO') {
            $costo_unit_bs_sin_iva = round($PrecioCompraBrutoSE,2);

            if($Gravado== 'SI' && $UtilidadSE!= 1){
              $total_imp_bs = round((($costo_unit_bs_sin_iva*$Cantidad)*(Impuesto-1)),2);
            }
            else if($Gravado== 'NO' && $UtilidadSE!= 1){
              $total_imp_bs = 0.00;
            }
            $total_bs = round((($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs),2);
            $costo_unit_usd_sin_iva = '-';
            $total_imp_usd = '-';
            $total_usd = '-';
          }
          $date = new DateTime('now');
          $date = $date->format("Y-m-d H:i:s");

          $sqlCP = MySQL_Guardar_Traslado_Detalle($IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
          mysqli_query($connCPharma,$sqlCP);
      }
      else if($Existencia>0){

        $sql3 = SQG_Detalle_Articulo_Lote($IdLote,$IdArticulo);
          $result3 = sqlsrv_query($conn,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $ExistenciaSE = $row3["Existencia"];
            $ExistenciaAlmacen1SE = $row3["ExistenciaAlmacen1"];
            $ExistenciaAlmacen2SE = $row3["ExistenciaAlmacen2"];
            $IsTroqueladoSE = $row3["Troquelado"];
            $IsIVASE = $row3["Impuesto"];
            $UtilidadArticuloSE = $row3["UtilidadArticulo"];
            $UtilidadCategoriaSE = $row3["UtilidadCategoria"];
            $TroquelAlmacen1SE = $row3["TroquelAlmacen1"];
            $PrecioCompraBrutoAlmacen1SE = $row3["PrecioCompraBrutoAlmacen1"];
            $TroquelAlmacen2SE = $row3["TroquelAlmacen2"];
            $PrecioCompraBrutoAlmacen2SE = $row3["PrecioCompraBrutoAlmacen2"];
            $PrecioCompraBrutoSE = $row3["PrecioCompraBruto"];
            $CondicionExistenciaSE = 'SIN_EXISTENCIA';

            $UtilidadSE = FG_Utilidad_Alfa($UtilidadArticuloSE,$UtilidadCategoriaSE);

          if($ExistenciaSE==0){

            if($Dolarizado=='SI') {

                //TODO: En caso de falla comentar aqui
                $array_result = FG_costo_mayor($IdArticulo,$IdLote,$CondicionExistenciaSE);
                $array_result_normal = FG_costo_mayor($IdArticulo,$IdLote,$CondicionExistencia);

                if($array_result['fallas']!=""||$array_result_normal['fallas']!=""){
                    echo 'Se presento una o varias fallas, a continuacion se presentan en detalle: <br>'.$array_result['fallas'];
                    $costo_unit_usd_sin_iva = round(0.01,2);
                }
                else{
                    $costo_unit_usd_sin_iva = round($array_result['costo_D'],2);
                    $costo_unit_usd_sin_iva_normal = round($array_result_normal['costo_D'],2);

                    if($costo_unit_usd_sin_iva>$costo_unit_usd_sin_iva_normal){
                        $costo_unit_usd_sin_iva = $costo_unit_usd_sin_iva;
                    }
                    else{
                        $costo_unit_usd_sin_iva = $costo_unit_usd_sin_iva_normal;
                    }
                }

                //$TasaActualSE = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
                //$PrecioSE = FG_Calculo_Precio_Alfa($ExistenciaSE,$ExistenciaAlmacen1SE,$ExistenciaAlmacen2SE,$IsTroqueladoSE,$UtilidadArticuloSE,$UtilidadCategoriaSE,$TroquelAlmacen1SE,$PrecioCompraBrutoAlmacen1SE,$TroquelAlmacen2SE, $PrecioCompraBrutoAlmacen2SE,$PrecioCompraBrutoSE,$IsIVASE,$CondicionExistenciaSE);

            if($Gravado=='SI' && $UtilidadSE!= 1){
             // $costo_unit_bs_sin_iva = ($PrecioSE/Impuesto)*$UtilidadSE;
              //$costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
              $total_imp_usd = round(($costo_unit_usd_sin_iva*$Cantidad)*(Impuesto-1),2);
            }
            else if($Gravado== 'NO' && $UtilidadSE!= 1){
              //$costo_unit_bs_sin_iva = ($PrecioSE)*$UtilidadSE;
              //$costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
              $total_imp_usd = 0.00;
            }
            $total_usd = round((($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd),2);
            $costo_unit_bs_sin_iva = '-';
            $total_imp_bs = '-';
            $total_bs = '-';
              }
              else if($Dolarizado=='NO') {
                $costo_unit_bs_sin_iva = round($PrecioCompraBrutoSE,2);

                if($Gravado== 'SI' && $UtilidadSE!= 1){
                  $total_imp_bs = round((($costo_unit_bs_sin_iva*$Cantidad)*(Impuesto-1)),2);
                }
                else if($Gravado== 'NO' && $UtilidadSE!= 1){
                  $total_imp_bs = 0.00;
                }
                $total_bs = round((($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs),2);
                $costo_unit_usd_sin_iva = '-';
                $total_imp_usd = '-';
                $total_usd = '-';
              }
              $date = new DateTime('now');
              $date = $date->format("Y-m-d H:i:s");

              $sqlCP = MySQL_Guardar_Traslado_Detalle($IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
              mysqli_query($connCPharma,$sqlCP);
          }
          else{

            if($Dolarizado=='SI') {

                //TODO: En caso de falla comentar aqui
                $array_result = FG_costo_mayor($IdArticulo,$IdLote,$CondicionExistencia);
                if($array_result['fallas']!=""){
                    echo 'Se presento una o varias fallas, a continuacion se presentan en detalle: <br>'.$array_result['fallas'];
                    $costo_unit_usd_sin_iva = round(0.01,2);
                }
                else{
                    $costo_unit_usd_sin_iva = round($array_result['costo_D'],2);
                }

            //$TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
            //$Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

            if($Gravado=='SI' && $Utilidad!= 1){
              //$costo_unit_bs_sin_iva = ($Precio/Impuesto)*$Utilidad;
              //$costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActual),2);
              $total_imp_usd = round(($costo_unit_usd_sin_iva*$Cantidad)*(Impuesto-1),2);
            }
            else if($Gravado== 'NO' && $Utilidad!= 1){
              //$costo_unit_bs_sin_iva = ($Precio)*$Utilidad;
              //$costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActual),2);
              $total_imp_usd = 0.00;
            }
            $total_usd = round((($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd),2);
            $costo_unit_bs_sin_iva = '-';
            $total_imp_bs = '-';
            $total_bs = '-';
              }
              else if($Dolarizado=='NO') {
                $costo_unit_bs_sin_iva = round($PrecioCompraBruto,2);

                if($Gravado== 'SI' && $Utilidad!= 1){
                  $total_imp_bs = round((($costo_unit_bs_sin_iva*$Cantidad)*(Impuesto-1)),2);
                }
                else if($Gravado== 'NO' && $Utilidad!= 1){
                  $total_imp_bs = 0.00;
                }
                $total_bs = round((($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs),2);
                $costo_unit_usd_sin_iva = '-';
                $total_imp_usd = '-';
                $total_usd = '-';
              }
              $date = new DateTime('now');
              $date = $date->format("Y-m-d H:i:s");

              $sqlCP = MySQL_Guardar_Traslado_Detalle($IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
              mysqli_query($connCPharma,$sqlCP);
            }
        }
    }
    sqlsrv_close($conn);
    mysqli_close($connCPharma);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Calculo_Precio_Alfa
        FUNCION: Calcular el precio del articulo
        RETORNO: Precio del articulo
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
        $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia) {
        $FlagPrecio = FALSE;

        if( ($Existencia==0) && ($CondicionExistencia=='CON_EXISTENCIA') ) {
            $Precio = 0;
        }
        else if(($Existencia!=0) && ($CondicionExistencia=='CON_EXISTENCIA')) {

            if($IsTroquelado!=0) {
                /*CASO 1: Cuando el articulo tiene el atributo troquelado*/
                if( ($TroquelAlmacen1!=NULL) && ($FlagPrecio==FALSE) ){
                    $Precio = $TroquelAlmacen1;
                    $FlagPrecio=TRUE;
                }
                else if( ($TroquelAlmacen1==NULL) && ($ExistenciaAlmacen1!=0) && ($FlagPrecio==FALSE)) {
                    $Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen1);
                    $FlagPrecio=TRUE;
                }
                else if( ($TroquelAlmacen2!=NULL) && ($FlagPrecio==FALSE) ){
                    $Precio = $TroquelAlmacen2;
                    $FlagPrecio=TRUE;
                }
                else if( ($TroquelAlmacen2==NULL) && ($ExistenciaAlmacen2!=0) && ($FlagPrecio==FALSE) ) {
                    $Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen2);
                    $FlagPrecio=TRUE;
                }
            }
            else if($IsTroquelado==0) {
                /*CASO 2: Cuando el articulo NO tiene el atributo troquelado*/
                $PrecioCalculado = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBruto);

                $precioPartes = explode(".",$TroquelAlmacen1);

                if( count($precioPartes)>=2 && substr($precioPartes[1],-2)==DecimalEtiqueta){
                    $Precio = $TroquelAlmacen1;
                }else{
                    $Precio = max($PrecioCalculado,$TroquelAlmacen1,$TroquelAlmacen2);
                }

            }
        }
        else if(($Existencia==0) && ($CondicionExistencia=='SIN_EXISTENCIA')) {

            if($IsTroquelado!=0) {
                /*CASO 1: Cuando el articulo tiene el atributo troquelado*/
                if( ($TroquelAlmacen1!=NULL) && ($FlagPrecio==FALSE) ){
                    $Precio = $TroquelAlmacen1;
                    $FlagPrecio=TRUE;
                }
                else if( ($TroquelAlmacen1==NULL) && ($ExistenciaAlmacen1!=0) && ($FlagPrecio==FALSE)) {
                    $Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen1);
                    $FlagPrecio=TRUE;
                }
                else if( ($TroquelAlmacen2!=NULL) && ($FlagPrecio==FALSE) ){
                    $Precio = $TroquelAlmacen2;
                    $FlagPrecio=TRUE;
                }
                else if( ($TroquelAlmacen2==NULL) && ($ExistenciaAlmacen2!=0) && ($FlagPrecio==FALSE) ) {
                    $Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen2);
                    $FlagPrecio=TRUE;
                }
            }
            else if($IsTroquelado==0) {
                /*CASO 2: Cuando el articulo NO tiene el atributo troquelado*/
                $PrecioCalculado = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBruto);

                $precioPartes = explode(".",$TroquelAlmacen1);

                if( count($precioPartes)>=2 && substr($precioPartes[1],-2)==DecimalEtiqueta){
                    $Precio = $TroquelAlmacen1;
                }else{
                    $Precio = max($PrecioCalculado,$TroquelAlmacen1,$TroquelAlmacen2);
                }

            }
        }

        if(!isset($Precio)){
            $Precio = 0.01;
        }

        return $Precio;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Precio_Calculado_Alfa
        FUNCION: Calcular el precio del articulo
        RETORNO: Precio del articulo
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBruto){
            if($IsIVA==FG_ID_Impuesto_Sede()){
                /*Caso 1: Aplica Impuesto*/
                if($UtilidadArticulo!=1) {
                    $PrecioCalculado = ($PrecioCompraBruto/$UtilidadArticulo)*Impuesto;
                }
                else if($UtilidadArticulo==1){

                    if($UtilidadCategoria!=1){
                        $PrecioCalculado = ($PrecioCompraBruto/$UtilidadCategoria)*Impuesto;
                    }
                    else if($UtilidadCategoria==1){
                        /*$PrecioCalculado = 0;*/
                        $PrecioCalculado = ($PrecioCompraBruto/Utilidad)*Impuesto;
                    }
                }
            }
            if($IsIVA==0){
                /*Caso 1: NO Aplica Impuesto*/
                if($UtilidadArticulo!=1) {
                    $PrecioCalculado = ($PrecioCompraBruto/$UtilidadArticulo);
                }
                else if($UtilidadArticulo==1){

                    if($UtilidadCategoria!=1){
                        $PrecioCalculado = ($PrecioCompraBruto/$UtilidadCategoria);
                    }
                    else if($UtilidadCategoria==1){
                        /*$PrecioCalculado = 0;*/
                        $PrecioCalculado = ($PrecioCompraBruto/Utilidad);
                    }
                }
            }
        return $PrecioCalculado;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Utilidad_Alfa
        FUNCION: Decide la utilidad a usar para la regresion o calculo de precio
        RETORNO: retorna la utilidad
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria){
        if($UtilidadArticulo!=1) {
            $utilidad = $UtilidadArticulo;
        }
        else if($UtilidadArticulo==1){

            if($UtilidadCategoria!=1){
                $utilidad = $UtilidadCategoria;
            }
            else if($UtilidadCategoria==1){
                $utilidad = Utilidad;
            }
        }
        return $utilidad;
    }
  /**********************************************************************************/
  /*
        TITULO: FG_Ruta_Reporte
        FUNCION:Busca la ruta que le pertenece al nombre del reporte
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Ruta_Reporte($NombreReporte){
        switch ($NombreReporte) {
            case 'Activacion de proveedores':
                $ruta = '/reporte1';
            break;
            case 'Historico de productos':
                $ruta = '/reporte2';
            break;
            case 'Productos mas vendidos':
                $ruta = '/reporte3';
            break;
            case 'Productos menos vendidos':
                $ruta = '/reporte4';
            break;
            case 'Productos en falla':
                $ruta = '/reporte5';
            break;
            case 'Pedido de productos':
                $ruta = '/reporte6';
            break;
            case 'Catalogo de proveedor':
                $ruta = '/reporte7';
            break;
            case 'Productos para surtir':
                $ruta = '/reporte9';
            break;
            case 'Analitico de precios':
                $ruta = '/reporte10';
            break;
            case 'Detalle de movimientos':
                $ruta = '/reporte12';
            break;
            case 'Productos Por Fallar':
                $ruta = '/reporte13';
            break;
            case 'Productos en Caida':
                $ruta = '/reporte14';
            break;
            case 'Articulos Devaluados':
                $ruta = '/reporte15';
            break;
            case 'Articulos Estrella':
                $ruta = '/reporte16';
            break;
            case 'Tri Tienda Por Articulo':
                $ruta = '/reporte17';
            break;
            case 'Consulta Compras':
                $ruta = '/reporte18';
            break;
            case 'Ventas Cruzadas':
                $ruta = '/reporte19';
            break;
            case 'Tri Tienda Por Proveedor':
                $ruta = '/reporte20';
            break;
            case 'Consultor de Precios':
                $ruta = '/reporte21';
            break;
            case 'Reporte de Atributos':
                $ruta = '/reporte22';
            break;
            case 'Articulos Nuevos':
                $ruta = '/reporte24';
            break;
            case 'Articulos en Cero':
                $ruta = '/reporte25';
            break;
            case 'Ultimas Entradas en Cero':
                $ruta = '/reporte26';
            break;
            case 'Artículos por Vencer':
                $ruta = '/reporte27';
            break;
            case 'Artículos sin fecha de vencimiento':
                $ruta = '/reporte28';
            break;
            case 'Compra por Marca':
                $ruta = '/reporte29';
            break;
            case 'Registro de Compras':
                $ruta = '/reporte30';
            break;
            case 'Monitoreo de Inventarios':
                $ruta = '/reporte31';
            break;
            case 'Seguimiento de Tienda':
                $ruta = '/reporte32';
            break;
            case 'Registro de Fallas':
                $ruta = '/falla';
            break;
            case 'Devoluciones de clientes':
                $ruta = '/reporte33';
            break;
            case 'Articulos estancados en tienda':
                $ruta = '/reporte34';
            break;
            case 'Artículos sin ventas':
                $ruta = '/reporte35';
            break;
            case 'Lista de precios':
                $ruta = '/reporte36';
            break;
            case 'Traslados entre tiendas':
                $ruta = '/reporte37';
            break;
            case 'Registro de Reclamos':
                $ruta = '/reporte38';
            break;
            case 'Revision de inventarios fisicos':
                $ruta = '/reporte39';
            break;
            case 'Surtido de gavetas':
                $ruta = '/reporte40';
            break;
            case 'Articulos competidos':
                $ruta = '/reporte41';
            break;
            case 'Ajustes de inventario':
                $ruta = '/reporte42';
            break;
            case 'Traslados':
                $ruta = '/reporte43';
            break;
            case 'Traslados por llegar':
                $ruta = '/reporte44';
            break;
            case 'Articulos sin imagen':
                $ruta = '/reporte45';
            break;
            case 'Compras por archivo':
                $ruta = '/reporte46';
            break;
            case 'Cruce de aplicación de consultas':
                $ruta = '/reporte47';
            break;
            case 'Cambio de precios':
                $ruta = '/reporte48';
            break;
            case 'Reposicion de Inventario':
                $ruta = '/reporte49';
            break;
            case 'Catálogo de droguerías':
                $ruta = '/reporte50';
            break;
            case 'Ventas por cajas/cajeros':
                $ruta = '/reporte51';
            break;
            case 'Lotes a la baja':
                $ruta = '/reporte52';
            break;
            case 'Ventas de concursos':
                $ruta = '/reporte53';
            break;
            default:
                $ruta = '#';
            break;
        }
        return $ruta;
    }
    /**********************************************************************************/
  /*
        TITULO: FG_Ruta_Reporte
        FUNCION:Busca la ruta que le pertenece al nombre del reporte
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Nombre_Reporte($NumeroReporte){
        switch ($NumeroReporte) {
            case 0:
                $nombre = 'No posee reportes';
            break;
            case 1:
                $nombre = 'Activacion de proveedores';
            break;
            case 2:
                $nombre = 'Historico de productos';
            break;
            case 3:
                $nombre = 'Productos mas vendidos';
            break;
            case 4:
                $nombre = 'Productos menos vendidos';
            break;
            case 5:
                $nombre = 'Productos en falla';
            break;
            case 6:
                $nombre = 'Pedido de productos';
            break;
            case 7:
                $nombre = 'Catalogo de proveedor';
            break;
            case 9:
                $nombre = 'Productos para surtir';
            break;
            case 10:
                $nombre = 'Analitico de precios';
            break;
            case 12:
                $nombre = 'Detalle de movimientos';
            break;
            case 13:
                $nombre = 'Productos Por Fallar';
            break;
            case 14:
                $nombre = 'Productos en Caida';
            break;
            case 15:
                $nombre = 'Articulos Devaluados';
            break;
            case 16:
                $nombre = 'Articulos Estrella';
            break;
            case 17:
                $nombre = 'Tri Tienda Por Articulo';
            break;
            case 18:
                $nombre = 'Consulta Compras';
            break;
            case 19:
                $nombre = 'Ventas Cruzadas';
            break;
            case 20:
                $nombre = 'Tri Tienda Por Proveedor';
            break;
            case 21:
                $nombre = 'Consultor de Precios';
            break;
            case 22:
                $nombre = 'Reporte de Atributos';
            break;
            case 24:
                $nombre = 'Articulos Nuevos';
            break;
            case 25:
                $nombre = 'Articulos en Cero';
            break;
            case 26:
                $nombre = 'Ultimas Entradas en Cero';
            break;
            case 27:
                $nombre = 'Artículos por Vencer';
            break;
            case 28:
                $nombre = 'Artículos sin fecha de vencimiento';
                break;
            case 29:
                $nombre = 'Compra por Marca';
            break;
            case 30:
                $nombre = 'Registro de Compras';
            break;
            case 31:
                $nombre = 'Monitoreo de Inventarios';
            break;
            case 32:
                $nombre = 'Seguimiento de Tienda';
            break;
            case 99:
                $nombre = 'Registro de Fallas';
            break;
            case 33:
                $nombre = 'Devoluciones a clientes';
            break;
            case 34:
                $nombre = 'Artículos estancados en tienda';
            break;
            case 35:
                $nombre = 'Artículos sin ventas';
            break;
            case 36:
                $nombre = 'Lista de precios';
            break;
            case 37:
                $nombre = 'Traslados entre tiendas';
            break;
            case 38:
                $nombre = 'Registro de reclamos';
            break;
            case 39:
                $nombre = 'Revisión de inventarios físicos';
            break;
            case 40:
                $nombre = 'Surtido de gavetas';
            break;
            case 41:
                $nombre = 'Artículos competidos';
            break;
            case 42:
                $nombre = 'Ajustes de inventario';
            break;
            case 43:
                $nombre = 'Traslados';
            break;
            case 44:
                $nombre = 'Traslados por llegar';
            break;
            case 45:
                $nombre = 'Ajustes de inventario';
            break;
            case 46:
                $nombre = 'Compras por archivo';
            break;
            case 47:
                $nombre = 'Cruce de aplicación de consultas';
            break;
            case 48:
                $nombre = 'Cambio de precios';
                break;
            case 49:
                $nombre = 'Reposicion de Inventario';
            break;
            case 50:
                $nombre = 'Catálogo de droguerías';
            break;
            case 51:
                $nombre = 'Ventas por cajas/cajeros';
            break;
            case 52:
                $nombre = 'Lotes a la baja';
            break;
            case 53:
                $nombre = 'Ventas de concurso';
            break;
            default:
                $nombre = 'Reporte desconocido';
            break;
        }
        return $nombre;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Reportes_Departamento
        FUNCION: Consigue el numero de reportes disponibles para el departamento
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Reportes_Departamento($Departamento) {
        switch ($Departamento) {
            case 'COMPRAS':
                $Numero_Reportes = 19;
            break;
            case 'OPERACIONES':
                $Numero_Reportes = 8;
            break;
            case 'ALMACEN':
                $Numero_Reportes = 3;
            break;
            case 'DEVOLUCIONES':
                $Numero_Reportes = 2;
            break;
            case 'SURTIDO':
                $Numero_Reportes = 6;
            break;
            case 'ADMINISTRACION':
                $Numero_Reportes = 1;
            break;
            case 'LÍDER DE TIENDA':
                $Numero_Reportes = 14;
            break;
            case 'GERENCIA':
                $Numero_Reportes = 21;
            break;
            case 'TECNOLOGIA':
                $Numero_Reportes = 21;
            break;
            case 'RECEPCION':
                $Numero_Reportes = 1;
            break;
            default:
                $Numero_Reportes = 0;
            break;
        }
        return $Numero_Reportes;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Sede_OnLine
        FUNCION: Determina si la sede es on-line
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Sede_OnLine($Sede) {
        switch ($Sede) {
            case 'FTN':
                $Flag = TRUE;
            break;
            case 'FLL':
                $Flag = TRUE;
            break;
            case 'FAU':
                $Flag = TRUE;
            break;
            case 'GP':
                $Flag = TRUE;
            break;
            case 'ARG':
                $Flag = TRUE;
            break;
            case 'DBs':
                $Flag = TRUE;
            break;
            case 'DBsa':
                $Flag = TRUE;
            break;
            case 'KDI':
                $Flag = TRUE;
            break;
            case 'FSM':
                $Flag = TRUE;
            break;
            case 'FEC':
                $Flag = TRUE;
            break;
            case 'FLF':
                $Flag = TRUE;
            break;
            case 'CDD':
                $Flag = TRUE;
            break;
            case 'KD73':
                $Flag = TRUE;
            break;
            default:
                $Flag = FALSE;
            break;
        }
        return $Flag;
  }
  /**********************************************************************************/
  /*
        TITULO: FG_Dias_EnCero
        FUNCION: Captura y almacena la data para dias en cero
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Dias_EnCero() {
        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();

        $sql = SQL_Dias_EnCero();
        $result = sqlsrv_query($conn,$sql);

        $TasaActual = FG_Tasa_Fecha_Venta_General($connCPharma);
        $FechaCaptura = new DateTime("now");
        $FechaCaptura = $FechaCaptura->format('Y-m-d');
        $user = 'SYSTEM';
        $date = '';

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $IdArticulo = $row["IdArticulo"];
            $CodigoInterno = $row["CodigoInterno"];
            $CodigoBarra = $row["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
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
      $Dolarizado = $row["Dolarizado"];
      $Gravado = FG_Producto_Gravado($IsIVA);
      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $CondicionExistencia = 'CON_EXISTENCIA';

        $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

        $PrecioDolar = $Precio/$TasaActual;

            $date = date('Y-m-d h:i:s',time());

            $sqlCPharma = MySQL_Guardar_Dias_EnCero($IdArticulo,$CodigoInterno,$Descripcion,$Existencia,$Precio,$FechaCaptura,$user,$date,$PrecioDolar);
            mysqli_query($connCPharma,$sqlCPharma);
        }
        FG_Guardar_Captura_Diaria($connCPharma,$FechaCaptura,$date);

        $sqlCC = MySQL_Validar_Captura_Diaria($FechaCaptura);
        $resultCC = mysqli_query($connCPharma,$sqlCC);
        $rowCC = mysqli_fetch_assoc($resultCC);
        $CuentaCaptura = $rowCC["CuentaCaptura"];

        if($CuentaCaptura == 0){
            $sqlB = MySQL_Borrar_DiasCero($FechaCaptura);
            mysqli_query($connCPharma,$sqlB);
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
            FG_Dias_EnCero();
        }
        else{
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Guardar_Captura_Diaria
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Guardar_Captura_Diaria($connCPharma,$FechaCaptura,$date) {
        $sql = MySQL_Captura_Diaria($FechaCaptura);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $TotalRegistros = $row["TotalRegistros"];

        $sql1 = MySQL_Guardar_Captura_Diaria($TotalRegistros,$FechaCaptura,$date);
        mysqli_query($connCPharma,$sql1);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Prouctos_EnCaida
        FUNCION: Captura y almacena la data para productos en caida
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Prouctos_EnCaida() {
        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();

        $sqlB = MySQL_Borrar_ProductosCaida();
        mysqli_query($connCPharma,$sqlB);

        $FechaCaptura = new DateTime("now");
        $FechaCaptura = $FechaCaptura->format('Y-m-d');
        $user = 'SYSTEM';
        $date = date('Y-m-d h:i:s',time());

        /* Rangos de Fecha */
        $FFinal = date("Y-m-d");
        $FInicial = date("Y-m-d",strtotime($FFinal."-10 days"));
        $RangoDias = intval(FG_Rango_Dias($FInicial,$FFinal));
        /* FILTRO 1:
        *  Articulos con veces vendidas en el rango
        *  Articulos sin compra en el rango
        */
        $sql = SQL_Clean_Table('CP_QG_Unidades_Vendidas');
        sqlsrv_query($conn,$sql);
        $sql = SQL_Clean_Table('CP_QG_Unidades_Devueltas');
        sqlsrv_query($conn,$sql);
        $sql = SQL_Clean_Table('CP_QG_Unidades_Compradas');
        sqlsrv_query($conn,$sql);
        $sql = SQL_Clean_Table('CP_QG_Unidades_Reclamadas');
        sqlsrv_query($conn,$sql);

        $sql1 = SQL_ArtVendidos_ProductoCaida($FInicial,$FFinal);
        sqlsrv_query($conn,$sql1);
        $sql1 = SQL_ArtDevueltos_ProductoCaida($FInicial,$FFinal);
        sqlsrv_query($conn,$sql1);
        $sql1 = SQL_ArtComprados_ProductoCaida($FInicial,$FFinal);
        sqlsrv_query($conn,$sql1);
        $sql1 = SQL_ArtReclamados_ProductoCaida($FInicial,$FFinal);
        sqlsrv_query($conn,$sql1);

        $sql3 = SQL_Integracion_ProductoCaida($RangoDias);
        $result = sqlsrv_query($conn,$sql3);

        $sql = SQL_Clean_Table('CP_QG_Unidades_Vendidas');
        sqlsrv_query($conn,$sql);
        $sql = SQL_Clean_Table('CP_QG_Unidades_Devueltas');
        sqlsrv_query($conn,$sql);
        $sql = SQL_Clean_Table('CP_QG_Unidades_Compradas');
        sqlsrv_query($conn,$sql);
        $sql = SQL_Clean_Table('CP_QG_Unidades_Reclamadas');
        sqlsrv_query($conn,$sql);

        /* Inicio while que itera en los articulos con existencia actual > 0*/
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

            $IdArticulo = $row["InvArticuloId"];
            $sql1 = SQG_Detalle_Articulo($IdArticulo);
        $result1 = sqlsrv_query($conn,$sql1);
        $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

        $CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
      $ExistenciaAlmacen1 = $row1["ExistenciaAlmacen1"];
      $ExistenciaAlmacen2 = $row1["ExistenciaAlmacen2"];
      $IsTroquelado = $row1["Troquelado"];
      $IsIVA = $row1["Impuesto"];
      $UtilidadArticulo = $row1["UtilidadArticulo"];
      $UtilidadCategoria = $row1["UtilidadCategoria"];
      $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
      $PrecioCompraBrutoAlmacen1 = $row1["PrecioCompraBrutoAlmacen1"];
      $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
      $PrecioCompraBrutoAlmacen2 = $row1["PrecioCompraBrutoAlmacen2"];
      $PrecioCompraBruto = $row1["PrecioCompraBruto"];
      $CondicionExistencia = 'CON_EXISTENCIA';

        $UnidadesVendidas = $row["UnidadesVendidas"];
        $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
        $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);
            /* FILTRO 2:
            *   Dias restantes
            *   si dias restantes es menor de 10 entra en el rango sino es rechazado
            */
            if($DiasRestantes<11){
                /* FILTRO 3:
                *   Mantuvo Existencia en rango
                *   se cuenta las apariciones del articulo en la tabla de dias
                *   en cero, la misma debe ser igual la diferencia de dias +1
                */
                $CuentaExistencia = FG_Cuenta_Existencia($connCPharma,$IdArticulo,$FInicial,$FFinal);

                if($CuentaExistencia==$RangoDias){
                    /*  FILTRO 4:
                    *   Venta en dia, esta se acumula
                    *   El articulo debe tener venta al menos la mita de dias del rango
                    */
                    $CuentaVenta = FG_Cuenta_Venta($conn,$IdArticulo,$FInicial,$FFinal);

                    if($CuentaVenta>=($RangoDias/2)){

                        $ExistenciaDecreciente = FG_Existencia_Decreciente($connCPharma,$IdArticulo,$FInicial,$FFinal,$RangoDias);

                        $CuentaDecreciente = array_pop($ExistenciaDecreciente);

                        /*  FILTRO 5:
                        *   Si el articulo decrece su existencia rango
                        */
                        if($CuentaDecreciente==TRUE){

                            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

                            $Dia1 = array_pop($ExistenciaDecreciente);
                            $Dia2 = array_pop($ExistenciaDecreciente);
                            $Dia3 = array_pop($ExistenciaDecreciente);
                            $Dia4 = array_pop($ExistenciaDecreciente);
                            $Dia5 = array_pop($ExistenciaDecreciente);
                            $Dia6 = array_pop($ExistenciaDecreciente);
                            $Dia7 = array_pop($ExistenciaDecreciente);
                            $Dia8 = array_pop($ExistenciaDecreciente);
                            $Dia9 = array_pop($ExistenciaDecreciente);
                            $Dia10 = array_pop($ExistenciaDecreciente);

                            $sqlCPharma = MySQL_Guardar_Productos_Caida($IdArticulo,$CodigoArticulo,$Descripcion,$Precio,$Existencia,$Dia10,$Dia9,$Dia8,$Dia7,$Dia6,$Dia5,$Dia4,$Dia3,$Dia2,$Dia1,$UnidadesVendidas,$DiasRestantes,$FechaCaptura,$user,$date,$date);

                            mysqli_query($connCPharma,$sqlCPharma);
                        }
                    }
                }
            }
        }
        FG_Guardar_Captura_Caida($connCPharma,$FechaCaptura,$date);

        $sqlCC = MySQL_Validar_Captura_Caida($FechaCaptura);
        $resultCC = mysqli_query($connCPharma,$sqlCC);
        $rowCC = mysqli_fetch_assoc($resultCC);
        $CuentaCaptura = $rowCC["CuentaCaptura"];

        if($CuentaCaptura == 0){
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
            FG_Prouctos_EnCaida();
        }
        else{
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Cuenta_Existencia
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Cuenta_Existencia($connCPharma,$IdArticulo,$FInicial,$FFinal) {
        $sql = MySQL_Cuenta_Existencia($IdArticulo,$FInicial,$FFinal);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $Cuenta = $row["Cuenta"];
        return $Cuenta;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Cuenta_Venta
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Cuenta_Venta($conn,$IdArticulo,$FInicial,$FFinal) {

        $FFinalPivote = date("Y-m-d",strtotime($FInicial."+1 days"));
        $FFinal = date("Y-m-d",strtotime($FFinal."+1 days"));
        $CuentaVenta = 0;

        while($FFinalPivote!=$FFinal){

            $sql = SQL_CuentaVenta($IdArticulo,$FInicial,$FFinalPivote);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
            $VecesVendida = $row["Cuenta"];

            if($VecesVendida>0){
                $CuentaVenta++;
            }

            $FInicial = date("Y-m-d",strtotime($FInicial."+1 days"));
        $FFinalPivote = date("Y-m-d",strtotime($FFinalPivote."+1 days"));
        }
        return $CuentaVenta;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Existencia_Decreciente
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Existencia_Decreciente($connCPharma,$IdArticulo,$FInicial,$FFinal,$RangoDias) {

        $PrimerCiclo = TRUE;
        $ExistenciaAyer = 0;
        $CuentaDecreciente = TRUE;
        $CuentaDecrece = 0;
        $ExistenciaDecreciente = array();

        while( ($FInicial!=$FFinal) && ($CuentaDecreciente==TRUE) ) {

            $sql = MySQL_Existencia_Dias_Cero($IdArticulo,$FInicial);
            $result = mysqli_query($connCPharma,$sql);
            $row = mysqli_fetch_assoc($result);
            $ExistenciaHoy = $row["existencia"];

            if($PrimerCiclo == TRUE){
                $PrimerCiclo = FALSE;
                $ExistenciaAyer = $ExistenciaHoy;
            }

            if($ExistenciaAyer>=$ExistenciaHoy){
                $CuentaDecreciente = TRUE;
                array_push($ExistenciaDecreciente,$ExistenciaHoy);

                if($ExistenciaAyer>$ExistenciaHoy){
                    $CuentaDecrece++;
                }
            }
            else{
                $CuentaDecreciente = FALSE;
            }

            $FInicial = date("Y-m-d",strtotime($FInicial."+1 days"));
        }

        if(($CuentaDecrece>=($RangoDias/2))&&($CuentaDecreciente==TRUE)){
            $CuentaDecreciente = TRUE;
        }
        else{
            $CuentaDecreciente = FALSE;
        }
        array_push($ExistenciaDecreciente,$CuentaDecreciente);
        return $ExistenciaDecreciente;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Guardar_Captura_Caida
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Guardar_Captura_Caida($connCPharma,$FechaCaptura,$date) {
        $sql = MySQL_Captura_Caida($FechaCaptura);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $TotalRegistros = $row["TotalRegistros"];

        $sql1 = MySQL_Guardar_Captura_Caida($TotalRegistros,$FechaCaptura,$date);
        mysqli_query($connCPharma,$sql1);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Validar_Etiquetas
        FUNCION: determinar si un prducto es unico
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Validar_Etiquetas() {
            $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();

        $sql = SQL_Existencia_Actual();
        $result = sqlsrv_query($conn,$sql);

        $FechaCaptura = new DateTime("now");
        $FechaCaptura = $FechaCaptura->format('Y-m-d');
        $date = '';

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $IdArticulo = $row["IdArticulo"];
            $CodigoInterno = $row["CodigoInterno"];
            $Descripcion=$row["Descripcion"];

            $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
            $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
            $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
            $IdArticuloCPharma = $RowCPharma['id_articulo'];

            if(is_null($IdArticuloCPharma)){

                $condicion = 'NO CLASIFICADO';
                $clasificacion = 'PENDIENTE';
                $estatus = 'ACTIVO';
                $user = 'SYSTEM';
                $date = new DateTime('now');
                $date = $date->format("Y-m-d H:i:s");

                $sqlCP = MySQL_Guardar_Etiqueta_Articulo($IdArticulo,$CodigoInterno,$Descripcion,$condicion,$clasificacion,$estatus,$user,$date);
                mysqli_query($connCPharma,$sqlCP);
            }
        }
        FG_Guardar_Captura_Etiqueta($connCPharma,$FechaCaptura,$date);
        $sqlCC = MySQL_Validar_Captura_Etiqueta($FechaCaptura);
        $resultCC = mysqli_query($connCPharma,$sqlCC);
        $rowCC = mysqli_fetch_assoc($resultCC);
        $CuentaCaptura = $rowCC["CuentaCaptura"];

        if($CuentaCaptura == 0){
            $sqlB = MySQL_Borrar_Captura_Etiqueta($FechaCaptura);
            mysqli_query($connCPharma,$sqlB);
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
            FG_Validar_Etiquetas();
        }
        else{
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Guardar_Captura_Etiqueta
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Guardar_Captura_Etiqueta($connCPharma,$FechaCaptura,$date) {
        $sql = MySQL_Captura_Etiqueta($FechaCaptura);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $TotalRegistros = $row["TotalRegistros"];

        $sql1 = MySQL_Guardar_Captura_Etiqueta($TotalRegistros,$FechaCaptura,$date);
        mysqli_query($connCPharma,$sql1);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Etiquetas
        FUNCION: crea la etiqueta para el caso que corresponda
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,$IsPrecioAyer,$FechaCambio,$TasaActual) {
        $flag = false;
        $mensajePie = "";
        $tam_dolar = "";
        $flag_imprime = false;

        $bolivarDigital = '';

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

                if($Dolarizado=='SI' && _EtiquetaDolar_=='SI'){
                    $precioPartes = explode(".",$PrecioHoy); //Comentado por reconversion
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

                    $PrecioHoy = $PrecioHoy/$TasaActual;

                    $sqlCC = MySQL_DiasCero_PrecioAyer_Dolar($IdArticulo,$FechaCambio);
                    $resultCC = mysqli_query($connCPharma,$sqlCC);
                    $rowCC = mysqli_fetch_assoc($resultCC);
                    $PrecioAyer = $rowCC["precio_dolar"];
                }

                if( floatval(round($PrecioHoy,2)) != floatval($PrecioAyer) ){

                    if($Dolarizado=='SI'){
                        $simbolo = '*';
                        $moneda = SigDolarPublico;

                        if(_MensajeDolar_== 'SI' && _EtiquetaDolar_=='SI'){
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

                        if(isset($precioPartes) && count($precioPartes)>=2){
                            if(_EtiquetaDolar_=='SI'){
                                $tam_dolar = "font-size:1.7rem;";
                                $flag_imprime = true;
                                if(substr($precioPartes[1],-2)==DecimalEtiqueta){
                                    $flag_imprime = true;
                                }
                                else{
                                    $flag_imprime = false;
                                }

                            }else if(_EtiquetaDolar_=='NO'){
                                $flag_imprime = true;
                                $moneda = SigVe;
                            }
                        }else{
                            echo'
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
                                    <tr>
                                        <td class="centrado rowDer rowDerA aumento preciopromo" colspan="2">
                                            <strong class="text-danger">
                                            Solicite ayuda al dpto. de procesamiento (Sin Decimal)
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            ';
                        }
                    }
                    else{
                        $simbolo = '';
                        $moneda = SigVe;
                        $flag_imprime = true;
                        $tam_dolar = "";

                        $bolivarDigital = '
                            <td class="derecha rowDer rowDerA aumento">
                                <label style="margin-right:10px;'.$tam_dolar.'">
                                    <strong>
                                    ' . SigVe . ' '.number_format (precioReconversion($PrecioHoy, 'BSD'),2,"," ,"." ).'
                                    </strong>
                                </label>
                            </td>
                        ';
                    }


                    if($flag_imprime == true){
                        $connCPharma = FG_Conectar_CPharma();
                        $query = $connCPharma->query("SELECT * FROM unidads WHERE codigo_barra = '$CodigoBarra' LIMIT 1");

                        try {
                            if ($query->num_rows) {
                                $unidad= $query->fetch_assoc();

                                $unidadMinima = strtolower($unidad['unidad_minima']);
                                $divisor = intval($unidad['divisor']);
                                $precioUnidadMinima = (($PrecioHoy / $divisor) < 1) ? number_format($PrecioHoy / $divisor, 4, ',', '.') : number_format($PrecioHoy / $divisor, 2, ',', '.');

                                $unidadMinima = '
                                    <tr>
                                        <td style="font-weight: bold; text-align: center; color: red" colspan="2">Precio por '.$unidadMinima.' '.$precioUnidadMinima.'</td>
                                    </tr>
                                ';
                            } else {
                                $unidadMinima = '';
                            }
                        }
                        catch (Exception $e) {
                            $unidadMinima = '';
                        }

                        echo'
                            <table>
                                <thead>
                                    <tr>
                                        <td class="centrado titulo rowCenter" colspan="2">
                                            Código: '.$CodigoBarra.'
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr rowspan="2">
                                        <td class="centrado descripcion aumento rowCenter" colspan="2">
                                            <strong>'.$Descripcion.'</strong>
                                        </td>
                                    </tr>
                                    ';
                                        if( floatval(round($PrecioHoy,2)) < floatval($PrecioAyer) ){
                                            if ($moneda != SigDolarPublico) {
                                                echo'
                                                    <tr>
                                                        <td colspan="2" style="color:red;text-align: center">
                                                            Precio Antes:

                                                            ' . SigVe . ' <del>'.number_format (precioReconversion($PrecioAyer, 'BSD'),2,"," ,"." ).'</del>
                                                        </td>
                                                    </tr>
                                                ';
                                            } else {
                                                echo'
                                                    <tr>
                                                        <td class="izquierda rowIzq rowIzqA" style="color:red;">
                                                            Precio '.$moneda.' Antes
                                                        </td>
                                                        <td class="derecha rowDer rowDerA" style="color:red;">
                                                            <label style="margin-right:10px;">
                                                                <del>
                                                                '.number_format ($PrecioAyer,2,"," ,"." ).'
                                                                </del>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                        }

                                    $precio = ($Dolarizado == 'SI') ? $moneda.' '.number_format ($PrecioHoy,2,"," ,"." ) : ' '.SigVe.' '.number_format (precioReconversion($PrecioHoy, 'BS'),2,"," ,"." );

                                    echo'
                                    <tr>
                                        <td colspan="2" style="text-align: center" class="rowIzq rowIzqA aumento">
                                            <strong>Total a Pagar</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center" class="rowDer rowDerA aumento">
                                            <label style="margin-right:10px;'.$tam_dolar.'">
                                                <strong>
                                                '.$precio.'
                                                </strong>
                                            </label>
                                        </td>
                                    </tr>
                                    '.$unidadMinima.'
                                    <tr>
                                        <td class="izquierda dolarizado rowIzq rowIzqA">
                                        </td>
                                        <td class="derecha rowDer rowDerA">
                                            <strong>'.$simbolo.'</strong> '.date("d-m-Y").'
                                        </td>
                                    </tr>
                                    '.$mensajePie.'
                                </tbody>
                            </table>
                        ';

                        $flag = true;
                    }
                    else{
                        echo'
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
                                    <tr>
                                        <td class="centrado rowDer rowDerA aumento preciopromo" colspan="2">
                                            <strong class="text-danger">
                                            Solicite ayuda al dpto. de procesamiento (Decimal Diff. 0.0001)
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        ';
                    }
                }
            }
            else if($IsPrecioAyer==false){

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
                            $tam_dolar = "";
                            $mensajePie = "";
                        }

                        if(_EtiquetaDolar_=='SI'){
                            $precioPartes = explode(".",$PrecioHoy); //Comentado por reconversion
                            /*
                            $sqlSCN = "SELECT sum(CuentaNull) as SumCuentaNull from(
                                select M_PrecioTroquelado, IIF(M_PrecioTroquelado IS NULL,1,0) as CuentaNull from InvLote where InvLote.id in (
                                    select InvLoteAlmacen.InvLoteId from InvLoteAlmacen
                                    where InvLoteAlmacen.InvArticuloId=".$IdArticulo."
                                    and Existencia>0
                                    and (InvLoteAlmacen.InvAlmacenId=1 OR InvLoteAlmacen.InvAlmacenId=2)
                                )
                            ) as axu";

                            $resultSCN = sqlsrv_query($conn,$sqlSCN);
                            $rowSCN = sqlsrv_fetch_array($resultSCN,SQLSRV_FETCH_ASSOC);
                            $SumCuentaNull = $rowSCN["SumCuentaNull"];

                            */

                            $PrecioHoy = $PrecioHoy/$TasaActual;

                            if(isset($precioPartes) && count($precioPartes)>=2){
                                $flag_imprime = true;

                                if(substr($precioPartes[1],-2)==DecimalEtiqueta){
                                    $flag_imprime = true;
                                }
                                else{
                                    $flag_imprime = false;
                                }

                            }else{
                                echo'
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
                                        <tr>
                                            <td class="centrado rowDer rowDerA aumento preciopromo" colspan="2">
                                                <strong class="text-danger">
                                                Solicite ayuda al dpto. de procesamiento (Sin Decimal)
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                ';
                            }

                        }else if(_EtiquetaDolar_=='NO'){
                            $flag_imprime = true;
                            $moneda = SigVe;
                        }

                    }
                    else{
                        $simbolo = '';
                        $moneda = SigVe;
                        $flag_imprime = true;
                        $bolivarDigital = '
                            <td class="derecha rowDer rowDerA aumento">
                                <label style="margin-right:10px;'.$tam_dolar.'">
                                    <strong>
                                    Bs. '.number_format (precioReconversion($PrecioHoy, 'BSD'),2,"," ,"." ).'
                                    </strong>
                                </label>
                            </td>
                        ';
                    }

                    if($flag_imprime == true){

                    $connCPharma = FG_Conectar_CPharma();
                    $query = $connCPharma->query("SELECT * FROM unidads WHERE codigo_barra = '$CodigoBarra' LIMIT 1");

                    if ($query->num_rows) {
                        $unidad= $query->fetch_assoc();

                        $unidadMinima = strtolower($unidad['unidad_minima']);
                        $divisor = intval($unidad['divisor']);
                        $precioUnidadMinima = (($PrecioHoy / $divisor) < 1) ? number_format($PrecioHoy / $divisor, 4, ',', '.') : number_format($PrecioHoy / $divisor, 2, ',', '.');

                        $unidadMinima = '
                            <tr>
                                <td style="font-weight: bold; text-align: center; color: red" colspan="2">Precio por '.$unidadMinima.' '.$precioUnidadMinima.'</td>
                            </tr>
                        ';
                    } else {
                        $unidadMinima = '';
                    }

                    $precio = ($Dolarizado == 'SI') ? $moneda.' '.number_format ($PrecioHoy,2,"," ,"." ) : $moneda.' '.number_format (precioReconversion($PrecioHoy, 'BSS'),2,"," ,"." );

                    echo'
                        <table>
                            <thead>
                                <tr>
                                    <td class="centrado titulo rowCenter" colspan="2">
                                        Código: '.$CodigoBarra.'
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr rowspan="2">
                                    <td class="centrado descripcion aumento rowCenter" colspan="2">
                                        <strong>'.$Descripcion.'</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center" class="rowIzq rowIzqA aumento">
                                        <strong>Total a Pagar</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center" class="rowDer rowDerA aumento">
                                        <label style="margin-right:10px;'.$tam_dolar.'">
                                            <strong>
                                            '.$precio.'
                                            </strong>
                                        </label>
                                    </td>
                                </tr>
                                '.$unidadMinima.'
                                <tr>
                                    <td class="izquierda dolarizado rowIzq rowIzqA">
                                    </td>
                                    <td class="derecha rowDer rowDerA">
                                        <strong>'.$simbolo.'</strong> '.date("d-m-Y").'
                                    </td>
                                </tr>
                                '.$mensajePie.'
                            </tbody>
                        </table>
                    ';
                    $flag = true;
                }
                else{
                    echo'
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
                                <tr>
                                    <td class="centrado rowDer rowDerA aumento preciopromo" colspan="2">
                                        <strong class="text-danger">
                                        Solicite ayuda al dpto. de procesamiento (Decimal Diff. 0.0001)
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    ';
                }
            }
        }
        return $flag;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Generer_Etiquetas_Todo
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Generer_Etiquetas_Todo($clasificacion,$tipo) {
        $SedeConnection = FG_Mi_Ubicacion();
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
        $CuentaCard = 0;
        $CuentaEtiqueta = 0;
        $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));

        $result = $connCPharma->query("SELECT id_articulo FROM etiquetas WHERE clasificacion = '$clasificacion'");

        while($row = $result->fetch_assoc()){
            $IdArticulo = $row['id_articulo'];
            $sql3 = SQL_Es_Dolarizado($IdArticulo);
            $result3 = sqlsrv_query($conn,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
            $Dolarizado = FG_Producto_Dolarizado($row3['Dolarizado']);
            if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){
                $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,false,false,$TasaActual);
                if($flag==true){
                    $CuentaCard++;
                    $CuentaEtiqueta++;
                }
            }
            else if(($Dolarizado=='NO')&&($tipo!='DOLARIZADO')){
                $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,false,false,$TasaActual);
                if($flag==true){
                    $CuentaCard++;
                    $CuentaEtiqueta++;
                }
            }
            else if($tipo=='TODO'){
                $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,false,false,$TasaActual);
                if($flag==true){
                    $CuentaCard++;
                    $CuentaEtiqueta++;
                }
            }

            if($CuentaCard == 3){
                echo'<br>';
                $CuentaCard = 0;
            }
        }
        echo "<br/>Se imprimiran ".$CuentaEtiqueta." etiquetas<br/>";
        mysqli_close($connCPharma);
    sqlsrv_close($conn);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Generer_Etiquetas
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Generer_Etiquetas($clasificacion,$tipo,$dia) {
        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();
        $arraySugeridos = array();
        $CuentaCard = 0;
        $CuentaEtiqueta = 0;
        $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));

        $FHoy = date("Y-m-d");
        $FManana = date("Y-m-d",strtotime($FHoy."+1 days"));
        $FAyer = date("Y-m-d",strtotime($FHoy."-1 days"));

        $listado = [];

        if($dia=='HOY'){
            $FechaCambio = $FHoy;

            $result2 = $connCPharma->query("SELECT COUNT(id) as Cuenta FROM dias_ceros WHERE fecha_captura = '$FechaCambio'");
            $row2= $result2->fetch_assoc();
            $CuentaDiasCero = $row2['Cuenta'];

            if($CuentaDiasCero==0){
                echo'
                    <h2 class="text-danger">
                        Solicite ayuda al Dpto. de tecnologia.<br>
                        Existe una falla de captura de datos.<br>
                        Ref. dias en cero fecha '.$FechaCambio.'
                    </h2>
                ';
                return false;
            }

            $arraySugeridos = FG_Obtener_Casos_Etiqueta($conn,$FHoy,$FManana);
            $ArrayUnique = unique_multidim_array($arraySugeridos,'IdArticulo');
        }
        else if($dia=='AYER'){
            $FechaCambio = $FAyer;
            $arraySugeridos = FG_Obtener_Casos_Etiqueta($conn,$FAyer,$FHoy);
            $ArrayUnique = unique_multidim_array($arraySugeridos,'IdArticulo');
        }

        $result1 = $connCPharma->query("SELECT COUNT(*) AS Cuenta FROM etiquetas WHERE clasificacion = '$clasificacion'");
        $row1= $result1->fetch_assoc();
        $CuentaCPharma = $row1['Cuenta'];
        $CuentaSmart = count($ArrayUnique);

        /*Caso 1
            El tamano de cambios en el smart es MENOR
            al total de elementos en la clasificacion solicitada
        */

        if($CuentaCPharma>$CuentaSmart){

            foreach ($ArrayUnique as $Array) {
            $IdArticulo = $Array['IdArticulo'];
            $Dolarizado = FG_Producto_Dolarizado($Array['Dolarizado']);

            $result1 = $connCPharma->query("SELECT COUNT(*) AS Cuenta FROM etiquetas WHERE id_articulo = '$IdArticulo' AND clasificacion = '$clasificacion'");
                $row1= $result1->fetch_assoc();
                $Cuenta = $row1['Cuenta'];

                if($Cuenta==1){

                    if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){
                        $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio,$TasaActual);
                        if($flag==true){
                            $CuentaCard++;
                            $CuentaEtiqueta++;
                            $listado[] = $IdArticulo;
                        }
                    }
                    else if(($Dolarizado=='NO')&&($tipo!='DOLARIZADO')){
                        $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio,$TasaActual);
                        if($flag==true){
                            $CuentaCard++;
                            $CuentaEtiqueta++;
                            $listado[] = $IdArticulo;
                        }
                    }
                    else if($tipo=='TODO'){
                        $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio,$TasaActual);
                        if($flag==true){
                            $CuentaCard++;
                            $CuentaEtiqueta++;
                            $listado[] = $IdArticulo;
                        }
                    }

                    if($CuentaCard == 3){
                        echo'<br>';
                        $CuentaCard = 0;
                    }
                }
            }
        }
        /*Caso 2
            El tamano de cambios en el smart es MAYOR
            al total de elementos en la clasificacion solicitada
        */

        else if($CuentaCPharma<$CuentaSmart){
            $result = $connCPharma->query("SELECT * FROM etiquetas WHERE clasificacion = '$clasificacion'");

            while($row = $result->fetch_assoc()){

                $IdArticulo = $row['id_articulo'];


                foreach ($ArrayUnique as $Array) {
                    if (in_array($IdArticulo,$Array)) {
                    $Dolarizado = FG_Producto_Dolarizado($Array['Dolarizado']);

                    if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){
                            $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio,$TasaActual);
                            if($flag==true){
                                $CuentaCard++;
                                $CuentaEtiqueta++;
                                $listado[] = $IdArticulo;
                            }
                        }
                        else if(($Dolarizado=='NO')&&($tipo!='DOLARIZADO')){
                            $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio,$TasaActual);
                            if($flag==true){
                                $CuentaCard++;
                                $CuentaEtiqueta++;
                                $listado[] = $IdArticulo;
                            }
                        }
                        else if($tipo=='TODO'){
                            $flag = FG_Etiquetas($conn,$connCPharma,$IdArticulo,$Dolarizado,true,$FechaCambio,$TasaActual);
                            if($flag==true){
                                $CuentaCard++;
                                $CuentaEtiqueta++;
                                $listado[] = $IdArticulo;
                            }
                        }

                        if($CuentaCard == 3){
                            echo'<br>';
                            $CuentaCard = 0;
                        }
                    }
                }
            }
        }

        FG_Listado_Final_Etiquetas($clasificacion, $tipo, $dia, $listado);

        echo "<br/><br/>Se imprimiran ".$CuentaEtiqueta." etiquetas<br/>";
        echo 'Cuenta CPharma: '.$CuentaCPharma.'<br>';
        echo 'Cuenta Smart: '.$CuentaSmart.'<br>';
        mysqli_close($connCPharma);
        sqlsrv_close($conn);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Obtener_Casos_Etiqueta
        FUNCION: Arma un array con los articulos que entran en los filtros
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Obtener_Casos_Etiqueta($conn,$FechaI,$FechaF) {
        $arraySugeridos = array();
        /*Obtener datos del caso 1*/
        $arrayIteracion = array();
        $sql = SQL_CP_Etiqueta_C1($FechaI,$FechaF);
        $result = sqlsrv_query($conn,$sql);
        while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC) ){
            $arrayIteracion["IdArticulo"]=$row['IdArticulo'];
        $arrayIteracion["Dolarizado"]=$row['Dolarizado'];
        $arraySugeridos[]=$arrayIteracion;
      }
        /*Obtener datos del caso 2*/
        $arrayIteracion = array();
        $sql = SQL_CP_Etiqueta_C2($FechaI,$FechaF);
        $result = sqlsrv_query($conn,$sql);
        while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC) ){
            $arrayIteracion["IdArticulo"]=$row['IdArticulo'];
        $arrayIteracion["Dolarizado"]=$row['Dolarizado'];
        $arraySugeridos[]=$arrayIteracion;
      }
        /*Obtener datos del caso 3*/
        $arrayIteracion = array();
        $sql = SQL_CP_Etiqueta_C3($FechaI,$FechaF);
        $result = sqlsrv_query($conn,$sql);
        while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC) ){
            $arrayIteracion["IdArticulo"]=$row['IdArticulo'];
        $arrayIteracion["Dolarizado"]=$row['Dolarizado'];
        $arraySugeridos[]=$arrayIteracion;
      }

      if(_EtiquetaDolar_=="NO"){
        /*Obtener datos del caso 4*/
            $arrayIteracion = array();
            $sql = SQL_CP_Etiqueta_C4($FechaI,$FechaF);
            $result = sqlsrv_query($conn,$sql);
            while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC) ){
                $arrayIteracion["IdArticulo"]=$row['IdArticulo'];
            $arrayIteracion["Dolarizado"]=$row['Dolarizado'];
            $arraySugeridos[]=$arrayIteracion;
          }
      }

       /*Obtener datos del caso 5*/
       $arrayIteracion = array();
        $sql = SQL_CP_Etiqueta_C5($FechaI,$FechaF);
        $result = sqlsrv_query($conn,$sql);
        while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC) ){
            $arrayIteracion["IdArticulo"]=$row['IdArticulo'];
        $arrayIteracion["Dolarizado"]=$row['Dolarizado'];
        $arraySugeridos[]=$arrayIteracion;
      }

        return $arraySugeridos;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Tasa_FechaConversion
        FUNCION: Buscar el valor de la tasa
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Tasa_FechaConversion($Fecha,$Moneda) {
        $resultTasaConversion = MySQL_Tasa_Conversion($Fecha,$Moneda);
        $resultTasaConversion = mysqli_fetch_assoc($resultTasaConversion);
        $Tasa = $resultTasaConversion['tasa'];
        return $Tasa;
    }
    /**********************************************************************************/
  /*
    TITULO: FG_Acticulos_Estrella_Top
    FUNCION: Arma el reporte de articulos estrellas
    RETORNO: Lista de articulos estrella y su comportamiento
    DESAROLLADO POR: SERGIO COVA
  */
  function FG_Acticulos_Estrella_Top($SedeConnection) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));

    $sql = R16Q_Detalle_Articulo_Estrella_Top(10);
    $result = sqlsrv_query($conn,$sql);

    $FFinal = $FFinalEn = date("Y-m-d");
    //Si quiero mas dias de rango muevo este -1 days Ej: -5 days
    $FInicial = $FInicialEn = date("Y-m-d",strtotime($FFinal."-1 days"));
    $RangoDias = FG_Rango_Dias($FInicial,$FFinal)+1;

    $DiasPedido = 20;

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
    </div>
    <br/>
    ';
    //echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
    //echo'<h6 align="center">El calculo de dias a pedir se esta calculando en pedidos para 20 dias de inventario</h6>';
    echo'
    <table class="table table-sm table-striped table-bordered col-12 sortable table-condensed" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Codigo Interno</th>
          <th scope="col" class="CP-sticky">Descripcion</th>
          <th scope="col" class="CP-sticky">Utilidad Configurada</td>
          <th scope="col" class="CP-sticky">Precio '.SigVe.' (Con IVA)</th>
          <th scope="col" class="CP-sticky">Precio '.SigDolar.' (Con IVA)</th>
          <th scope="col" class="CP-sticky">Existencia</th>
    ';
    while($FFinalEn!=$FInicialEn){
      $Dia = date("D", strtotime($FInicialEn));
      $FImpresion = date("d-m-Y", strtotime($FInicialEn));
      echo'<th scope="col" class="CP-sticky">'.$Dia.' '.$FImpresion.'</th>';
      $FInicialEn = date("Y-m-d",strtotime($FInicialEn."+1 days"));
    }
    /*
    echo'
          <th scope="col" class="CP-sticky">Ventas Hoy</th>
          <th scope="col" class="CP-sticky">Ventas Totales</th>
          <th scope="col" class="CP-sticky">Dias en Quiebre</th>
          <th scope="col" class="CP-sticky">Dias Restantes</th>
          <th scope="col" class="CP-sticky">Cantidad a Pedir</th>
        </tr>
      </thead>
      <tbody>
    ';
    */

    echo'
        </tr>
      </thead>
      <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

      $FInicialBo = $FInicial;
      $FFinalBo = $FFinal;

      $IdArticulo = $row["IdArticulo"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
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
      $Dolarizado = $row["Dolarizado"];
      $CondicionExistencia = 'CON_EXISTENCIA';

      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
      $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
      $Utilidad = (1 - $Utilidad)*100;

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$row['CodigoInterno'].'</td>';
      echo
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';

      echo '<td align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';
      echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';

      if($TasaActual!=0){
        $PrecioDolar = $Precio/$TasaActual;
        echo '<td align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>';
      }
      else{
        echo '<td align="center">0,00</td>';
      }

      echo '<td align="center">'.intval($Existencia).'</td>';

      while($FFinalBo!=$FInicialBo){
        $FPivoteBo = date("Y-m-d",strtotime($FInicialBo."+1 days"));
        $sql1 = R16Q_Detalle_Venta_Top($IdArticulo,$FInicialBo,$FPivoteBo);
        $result1 = sqlsrv_query($conn,$sql1);
        $row1= sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
        $TotalUnidadesVendidas = $row1['TotalUnidadesVendidas'];
        echo '<td align="center">'.intval($TotalUnidadesVendidas).'</td>';
        $FInicialBo = date("Y-m-d",strtotime($FInicialBo."+1 days"));
      }

      /*
      $FPivoteBo = date("Y-m-d",strtotime($FInicialBo."+1 days"));
      $sql1 = R16Q_Detalle_Venta_Top($IdArticulo,$FInicialBo,$FPivoteBo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1= sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
      $TotalUnidadesVendidas = $row1['TotalUnidadesVendidas'];
      echo '<td align="center">'.intval($TotalUnidadesVendidas).'</td>';

      $FPivoteBo = date("Y-m-d",strtotime($FFinal."+1 days"));
      $sql1 = R16Q_Detalle_Venta_Top($IdArticulo,$FInicial,$FPivoteBo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1= sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
      $TotalUnidadesVendidasG = $row1['TotalUnidadesVendidas'];
      echo '<td align="center">'.intval($TotalUnidadesVendidasG).'</td>';

      $FPivoteIDC = date("Y-m-d",strtotime($FInicial."-1 days"));
      $FPivoteFDC = date("Y-m-d",strtotime($FFinal."+1 days"));

      $sql2 = "SELECT COUNT(*) AS Cuenta FROM `dias_ceros` WHERE `fecha_captura` > '$FPivoteIDC' AND `fecha_captura` < '$FPivoteFDC' AND `id_articulo` = '$IdArticulo'";
      $result2 = mysqli_query($connCPharma,$sql2);
      $row2 = mysqli_fetch_assoc($result2);
      $Cuenta = $row2['Cuenta'];
      $DiasEnQuiebre = $RangoDias - $Cuenta;

      echo '<td align="center">'.intval($DiasEnQuiebre).'</td>';

      $VentaDiaria = FG_Venta_Diaria($TotalUnidadesVendidasG,$RangoDias);
      $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);
      $CantidadPedido = FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia);

      echo '<td align="center">'.round($DiasRestantes,2).'</td>';
      echo '<td align="center">'.intval($CantidadPedido).'</td>';
      */

      echo '</tr>';
      $contador++;
    }
    echo '
        </tbody>
      </table>';
  }
  /**********************************************************************************/
  /*
    TITULO: FG_Precio_Consultor
    FUNCION: Armma el precio para el consultor
    RETORNO: precio con IVA del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function FG_Precio_Consultor($IdArticulo) {
    $SedeConnection = FG_Mi_Ubicacion();
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql = SQG_Lite_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $Existencia = $row["Existencia"];
    $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
    $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
    $IsTroquelado = $row["Troquelado"];
    $UtilidadArticulo = $row["UtilidadArticulo"];
    $UtilidadCategoria = $row["UtilidadCategoria"];
    $TroquelAlmacen1 = $row["TroquelAlmacen1"];
    $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
    $TroquelAlmacen2 = $row["TroquelAlmacen2"];
    $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
    $PrecioCompraBruto = $row["PrecioCompraBruto"];
    $IsIVA = $row["Impuesto"];
    $CondicionExistencia = 'CON_EXISTENCIA';

    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    return $Precio;
  }
  /**********************************************************************************/
    /*
        TITULO: FG_Dominio_Sede
        FUNCION: Retornar el nombre de la sede con la que se esta conectando
        RETORNO: Nombre de la sede conectada
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Dominio_Sede($SedeConnection) {
        switch($SedeConnection) {
            case 'FTN':
                $dominio = 'http://cpharmaftn.com/';
                return $dominio;
            break;
            case 'FLL':
                $dominio = 'http://cpharmafll.com/';
                return $dominio;
            break;
            case 'FAU':
                $dominio = 'http://cpharmafau.com/';
                return $dominio;
            break;
            case 'GP':
                $dominio = 'http://cpharmatest.com/';
                return $dominio;
            break;
            case 'ARG':
                $dominio = 'http://cpharmade.com/';
                return $dominio;
            break;
            case 'DBs':
                $dominio = 'http://cpharmade.com/';
                return $dominio;
            break;
            case 'DBsa':
                $dominio = 'http://cpharmade.com/';
                return $dominio;
            break;
            case 'KDI':
                $dominio = 'http://cpharmakdi.com/';
                return $dominio;
            break;
            case 'FSM':
                $dominio = 'http://cpharmafsm.com/';
                return $dominio;
            break;
            case 'FEC':
                $dominio = 'http://cpharmafec.com/';
                return $dominio;
            break;
            case 'FLF':
                $dominio = 'http://cpharmaflf.com/';
                return $dominio;
            break;

            case 'CDD':
                $dominio = 'http://cpharmacdd.com/';
                return $dominio;
            break;

            case 'KD73':
                $dominio = 'http://cpharmakd73.com/';
                return $dominio;
            break;
        }
    }
    /**********************************************************************************/
    /*
        TITULO: unique_multidim_array
        FUNCION: Create multidimensional array unique for any single key index.
        DESAROLLADO POR: Ghanshyam Katriya
    */
    function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Conectar_Smartpharma
        FUNCION: Conectar el sistema con la sede que se desea
        RETORNO: Conexion a Smartpharma
        DESAROLLADO POR: SERGIO COVA
     */
    function FG_Conectar_GALAC($SedeConnection) {
        switch($SedeConnection) {
            case 'NOMINA':
                $connectionInfo = array(
                    "Database"=>nameGN,
                    "UID"=>userGN,
                    "PWD"=>passGN
                );
                $conn = sqlsrv_connect(serverGN,$connectionInfo);
                return $conn;
            break;
            case 'SAW':
                $connectionInfo = array(
                    "Database"=>nameGS,
                    "UID"=>userGS,
                    "PWD"=>passGS
                );
                $conn = sqlsrv_connect(serverGS,$connectionInfo);
                return $conn;
            break;
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Componente_Articulo
        FUNCION: Buscar el componente de un articulo
        RETORNO: Valor de la tasa
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Componente_Articulo($conn,$IdArticulo) {
        $componentes = '';
        $sql = SQL_Componente_Articulo($IdArticulo);
        $result = sqlsrv_query($conn,$sql);

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $IdComponentes = $row['InvComponenteId'];

            $sql1 = SQL_Componente($IdComponentes);
            $result1 = sqlsrv_query($conn,$sql1);
            $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
            $componentes = $componentes.' '.$row1['Nombre'];
        }
        return $componentes;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Aplicacion_Articulo
        FUNCION: Buscar la aplicacion o uso de un articulo
        RETORNO: Valor de la tasa
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Aplicacion_Articulo($conn,$IdArticulo) {
        $aplicacion = '';
        $sql = SQL_Aplicacion_Articulo($IdArticulo);
        $result = sqlsrv_query($conn,$sql);

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $IdAplicacion = $row['InvUsoId'];

            $sql1 = SQL_Aplicacion($IdAplicacion);
            $result1 = sqlsrv_query($conn,$sql1);
            $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
            $aplicacion = $aplicacion.' '.$row1['Descripcion'];
        }
        return $aplicacion;
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Validar_Categorias
        FUNCION: determinar si un prducto es unico
        DESARROLLADO POR: SERGIO COVA
     */
    function FG_Validar_Categorias() {
            $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();

        $sql = SQL_Existencia_Actual();
        $result = sqlsrv_query($conn,$sql);

        $FechaCaptura = new DateTime("now");
            $FechaCaptura = $FechaCaptura->format('Y-m-d');
            $date = '';

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $IdArticulo = $row["IdArticulo"];
            $CodigoInterno = $row["CodigoInterno"];
            $CodigoBarra = $row["CodigoBarra"];
            $Descripcion = $row["Descripcion"];
            $Marca = $row["Marca"];

            $sqlCPharma = SQL_Categoria_Articulo($IdArticulo);
            $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
            $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
            $IdArticuloCPharma = $RowCPharma['id_articulo'];

            if(is_null($IdArticuloCPharma)){

                $codigo_categoria = '1';
                $codigo_subcategoria = '1.1';
                $estatus = 'ACTIVO';
                $user = 'SYSTEM';
                $date = new DateTime('now');
                $date = $date->format("Y-m-d H:i:s");

                $sqlCP = MySQL_Guardar_Catagoria_Articulo($IdArticulo,$CodigoInterno,$CodigoBarra,$Descripcion,$Marca,$codigo_categoria,$codigo_subcategoria,$estatus,$user,$date);
                mysqli_query($connCPharma,$sqlCP);
            }
        }
        FG_Guardar_Captura_Categoria($connCPharma,$FechaCaptura,$date);
        $sqlCC = MySQL_Validar_Captura_Categoria($FechaCaptura);
        $resultCC = mysqli_query($connCPharma,$sqlCC);
        $rowCC = mysqli_fetch_assoc($resultCC);
        $CuentaCaptura = $rowCC["CuentaCaptura"];

        if($CuentaCaptura == 0){
            $sqlB = MySQL_Borrar_Captura_Categoria($FechaCaptura);
            mysqli_query($connCPharma,$sqlB);
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
            FG_Validar_Categorias();
        }
        else{
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
        }
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Guardar_Captura_Categoria
        FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Guardar_Captura_Categoria($connCPharma,$FechaCaptura,$date) {
        $sql = MySQL_Captura_Categoria($FechaCaptura);
        $result = mysqli_query($connCPharma,$sql);
        $row = mysqli_fetch_assoc($result);
        $TotalRegistros = $row["TotalRegistros"];

        $sql1 = MySQL_Guardar_Captura_Categoria($TotalRegistros,$FechaCaptura,$date);
        mysqli_query($connCPharma,$sql1);
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Corrida_Precio_Sede
        FUNCION: Ejecuta corrida en sede dada
        DESARROLLADO POR: NISAUL DELGADo
    */
    function FG_Corrida_Precio_Sede($sede, $tipoCorrida,$tasaCalculo,$user){

        $SedeConnection = $sede;
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = sql_articulos_corrida();
    $result = sqlsrv_query($conn,$sql);

    $cont_exito = $cont_falla = $cont_cambios = $cont_noCambio = 0;

    $fallas = "";

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["IdArticulo"];
      $CodigoInterno = $row["CodigoInterno"];
      $CodigoBarra = $row["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
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

        $sql1 = sql_lotes_corrida($IdArticulo);
        $result1 = sqlsrv_query($conn,$sql1);

       $CostoMayorD = 0;
       $fechaActualizacion = date('Y-m-d 07:07:07.0000007');

        while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

            $Fecha = $row1["Auditoria_FechaCreacion"]->format('Y-m-d');
            $sql = "SELECT tasa FROM dolars where fecha = '$Fecha'";
            $TasaMercado = DB::connection(strtolower($sede))->select($sql)[0]->tasa;

            if($TasaMercado!=0){

                $CostoDolar = ($row1["M_PrecioCompraBruto"]/$TasaMercado);
                $CostoDolar = round($CostoDolar,2);

                if($CostoDolar>$CostoMayorD){
                    $CostoMayorD = $CostoDolar;
                }
            }
            else{
                $cont_falla++;
                $fallas = $fallas."<br><br>No se logro actualizar el articulo: ".$Descripcion.
                "<br>Motivo: Fallo la tasa de mercado del dia ".$row1["Auditoria_FechaCreacion"]->format('d-m-Y');
            }
        }

            $costoBs = $CostoMayorD * $tasaCalculo;
            $precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$costoBs);
            $precio = round($precio,2);
            //$precio = ceil($precio)+0.01;
            $precio = $precio+0.0001;

            if($tipoCorrida=='subida'){

                $PrecioActual = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
                    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

                if($precio > $PrecioActual){
                    $sql_Troquel = SQL_Update_Troquel($IdArticulo,$precio,$fechaActualizacion);
                    sqlsrv_query($conn,$sql_Troquel);
                    $cont_cambios++;
                    $cont_exito++;
                    /*
                    echo "<br> * * * * * * * * * * * * * * * * * * * * * * * * * ";
                    echo "<br>Articulo: ".$IdArticulo;
                    echo "<br>El Precio Cambio";
                    echo "<br>Precio Nuevo: ".$precio;
                    echo "<br>Precio Anterior: ".$PrecioActual;
                    echo "<br> * * * * * * * * * * * * * * * * * * * * * * * * * ";
                    */
                }else{
                    $cont_noCambio++;
                    $cont_exito++;
                    /*
                    echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                    echo "<br>Articulo: ".$IdArticulo;
                    echo "<br>El Precio se mantiene";
                    echo "<br>Precio Propuesto: ".$precio;
                    echo "<br>Precio: ".$PrecioActual;
                    echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                    */
                }
                }
                else if($tipoCorrida=='bajada'){
                    $sql_Troquel = SQL_Update_Troquel($IdArticulo,$precio,$fechaActualizacion);
                sqlsrv_query($conn,$sql_Troquel);
                    $cont_cambios++;
                $cont_exito++;
                /*
                echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                echo "<br>Articulo: ".$IdArticulo;
                echo "<br>El Precio se mantiene";
                echo "<br>Precio Propuesto: ".$precio;
                echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                */
                }
    }

    $evaluados = ($cont_exito+$cont_falla);

    $sql2 = MySQL_Guardar_Auditoria_Corrida($evaluados,$cont_exito,$cont_falla,$cont_cambios,$cont_noCambio,$user,$tipoCorrida,$tasaCalculo,date('d-m-Y'),date('h:i:s a'),$fallas);

    mysqli_query($connCPharma,$sql2);
    mysqli_close($connCPharma);
        sqlsrv_close($conn);

        /*
        echo $fallas;
    echo "<br><br>Total Evaluados: ".($cont_exito+$cont_falla);
    echo "<br>Exito: ".$cont_exito;
    echo "<br>Fallas: ".$cont_falla;
    echo "<br>Cambios: ".$cont_cambios;
    echo "<br>Sin Cambios: ".$cont_noCambio;

    echo "<br>Corrida Ejecutada por: ".$user;
    echo "<br>Corrida de tipo: ".$tipoCorrida;
    echo "<br>Tasa de Calculo: ".$tasaCalculo;
    echo "<br>Dia de la corrida: ".date('d-m-Y');
    echo "<br>Hora de la corrida: ".date('h:i:s a');
    */
    }
    /**********************************************************************************/
    /*
        TITULO: FG_Corrida_Precio
        FUNCION: crea la etiqueta para el caso que corresponda
        DESARROLLADO POR: SERGIO COVA
    */
    function FG_Corrida_Precio($tipoCorrida,$tasaCalculo,$user){

        $SedeConnection = FG_Mi_Ubicacion();
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = sql_articulos_corrida();
    $result = sqlsrv_query($conn,$sql);

    $cont_exito = $cont_falla = $cont_cambios = $cont_noCambio = 0;

    $fallas = "";

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["IdArticulo"];
      $CodigoInterno = $row["CodigoInterno"];
      $CodigoBarra = $row["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
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

        $sql1 = sql_lotes_corrida($IdArticulo);
        $result1 = sqlsrv_query($conn,$sql1);

       $CostoMayorD = 0;
       $fechaActualizacion = date('Y-m-d 07:07:07.0000007');

        while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

            $TasaMercado = FG_Tasa_Fecha($connCPharma,$row1["Auditoria_FechaCreacion"]->format('Y-m-d'));

            if($TasaMercado!=0){

                $CostoDolar = ($row1["M_PrecioCompraBruto"]/$TasaMercado);
                $CostoDolar = round($CostoDolar,2);

                if($CostoDolar>$CostoMayorD){
                    $CostoMayorD = $CostoDolar;
                }
            }
            else{
                $cont_falla++;
                $fallas = $fallas."<br><br>No se logro actualizar el articulo: ".$Descripcion.
                "<br>Motivo: Fallo la tasa de mercado del dia ".$row1["Auditoria_FechaCreacion"]->format('d-m-Y');
            }
        }

            $costoBs = $CostoMayorD * $tasaCalculo;
            $precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$costoBs);
            $precio = round($precio,2);
            //$precio = ceil($precio)+0.01;
            $precio = $precio+0.0001;

            if($tipoCorrida=='subida'){

                $PrecioActual = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
                    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

                if($precio > $PrecioActual){
                    $sql_Troquel = SQL_Update_Troquel($IdArticulo,$precio,$fechaActualizacion);
                    sqlsrv_query($conn,$sql_Troquel);
                    $cont_cambios++;
                    $cont_exito++;
                    /*
                    echo "<br> * * * * * * * * * * * * * * * * * * * * * * * * * ";
                    echo "<br>Articulo: ".$IdArticulo;
                    echo "<br>El Precio Cambio";
                    echo "<br>Precio Nuevo: ".$precio;
                    echo "<br>Precio Anterior: ".$PrecioActual;
                    echo "<br> * * * * * * * * * * * * * * * * * * * * * * * * * ";
                    */
                }else{
                    $cont_noCambio++;
                    $cont_exito++;
                    /*
                    echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                    echo "<br>Articulo: ".$IdArticulo;
                    echo "<br>El Precio se mantiene";
                    echo "<br>Precio Propuesto: ".$precio;
                    echo "<br>Precio: ".$PrecioActual;
                    echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                    */
                }
                }
                else if($tipoCorrida=='bajada'){
                    $sql_Troquel = SQL_Update_Troquel($IdArticulo,$precio,$fechaActualizacion);
                sqlsrv_query($conn,$sql_Troquel);
                    $cont_cambios++;
                $cont_exito++;
                /*
                echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                echo "<br>Articulo: ".$IdArticulo;
                echo "<br>El Precio se mantiene";
                echo "<br>Precio Propuesto: ".$precio;
                echo "<br> / / / / / / / / / / / / / / / / / / / / / / / / / ";
                */
                }
    }

    $evaluados = ($cont_exito+$cont_falla);

    $sql2 = MySQL_Guardar_Auditoria_Corrida($evaluados,$cont_exito,$cont_falla,$cont_cambios,$cont_noCambio,$user,$tipoCorrida,$tasaCalculo,date('d-m-Y'),date('h:i:s a'),$fallas);

    mysqli_query($connCPharma,$sql2);
    mysqli_close($connCPharma);
        sqlsrv_close($conn);

        /*
        echo $fallas;
    echo "<br><br>Total Evaluados: ".($cont_exito+$cont_falla);
    echo "<br>Exito: ".$cont_exito;
    echo "<br>Fallas: ".$cont_falla;
    echo "<br>Cambios: ".$cont_cambios;
    echo "<br>Sin Cambios: ".$cont_noCambio;

    echo "<br>Corrida Ejecutada por: ".$user;
    echo "<br>Corrida de tipo: ".$tipoCorrida;
    echo "<br>Tasa de Calculo: ".$tasaCalculo;
    echo "<br>Dia de la corrida: ".date('d-m-Y');
    echo "<br>Hora de la corrida: ".date('h:i:s a');
    */
    }
    /***********************************************************************************/
  function sql_articulos_corrida(){
    $sql ="
    SELECT
    --Id Articulo
    InvArticulo.Id AS IdArticulo,
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
                        AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2
        --Tabla principal
        FROM InvArticulo
        --Joins
        LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
        LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
        LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
        --Condicionales
        WHERE InvLoteAlmacen.Existencia > 0
        AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
        AND
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
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) <> 0
        --Esto lo borramos despues
        --and InvArticulo.id = 30
        --Agrupamientos
        GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.InvCategoriaId,InvArticulo.FinConceptoImptoIdCompra
        --Ordanamiento
        ORDER BY InvArticulo.Id ASC
        ";
        return $sql;
  }
  /******************************************************************************/
  function sql_lotes_corrida($IdArticulo){
    $sql ="
    SELECT
    InvArticulo.id,
        InvArticulo.CodigoArticulo,
        InvArticulo.Descripcion,
        InvLoteAlmacen.Existencia,
        InvLoteAlmacen.InvLoteId, InvLote.Auditoria_FechaCreacion,
        InvLote.M_PrecioCompraBruto, InvLote.M_PrecioTroquelado
        from InvLoteAlmacen, InvLote, InvArticulo
        where InvAlmacenId = 1
        and InvLoteAlmacen.InvLoteId = InvLote.id and InvArticulo.Id = InvLote.InvArticuloId
        and InvLoteAlmacen.existencia > 0
        and
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
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) <> 0
        AND InvArticulo.id = '$IdArticulo'
        order by InvLote.M_PrecioTroquelado desc, InvLote.M_PrecioCompraBruto desc;
    ";
    return $sql;
  }
  /*****************************************************************************/
  function sql_lotes_corrida_especifico($IdArticulo,$IdLote){
    $sql ="
    SELECT
    InvArticulo.id,
        InvArticulo.CodigoArticulo,
        InvArticulo.Descripcion,
        InvLoteAlmacen.Existencia,
        InvLoteAlmacen.InvLoteId, InvLote.Auditoria_FechaCreacion,
        InvLote.M_PrecioCompraBruto, InvLote.M_PrecioTroquelado
        from InvLoteAlmacen, InvLote, InvArticulo
        where InvAlmacenId = 1
        and InvLoteAlmacen.InvLoteId = InvLote.id and InvArticulo.Id = InvLote.InvArticuloId
        and
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
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) <> 0
        AND InvArticulo.id = '$IdArticulo'
        AND InvLote.id = '$IdLote'
        order by InvLote.M_PrecioTroquelado desc, InvLote.M_PrecioCompraBruto desc;
    ";
    return $sql;
  }
  /******************************************************************************/
  function FG_costo_mayor($IdArticulo,$IdLote,$condicionExistencia){
    $SedeConnection = FG_Mi_Ubicacion();
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $fallas = "";
    $array_result = array();
    $CostoMayorD = 0;

        if($condicionExistencia=='CON_EXISTENCIA'){
            $sql1 = sql_lotes_corrida($IdArticulo);
        }
        else if($condicionExistencia=='SIN_EXISTENCIA'){
            $sql1 = sql_lotes_corrida_especifico($IdArticulo,$IdLote);
        }

    $result1 = sqlsrv_query($conn,$sql1);

        while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

            $TasaMercado = FG_Tasa_Fecha($connCPharma,$row1["Auditoria_FechaCreacion"]->format('Y-m-d'));
            $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);

            if($TasaMercado!=0){

                $CostoDolar = ($row1["M_PrecioCompraBruto"]/$TasaMercado);
                $CostoDolar = round($CostoDolar,2);

                if($CostoDolar>$CostoMayorD){
                    $CostoMayorD = $CostoDolar;
                }
            }
            else{
                $fallas = $fallas."<br><br>No se logro actualizar el articulo: ".$Descripcion.
                "<br>Motivo: Fallo la tasa de mercado del dia ".$row1["Auditoria_FechaCreacion"]->format('d-m-Y');
            }
        }

            $array_result['fallas'] = $fallas;
            $array_result['costo_D'] = $CostoMayorD;

        mysqli_close($connCPharma);
        sqlsrv_close($conn);

        //print_r($array_result);
        return $array_result;
  }

  function antesReconversion($precio) {
    if (_AntesReconversion_ == "SI") {
        $precio = $precio/_FactorReconversion_;
    }

    return $precio;
  }

  function validar_fecha_espanol($fecha){
        $valores = explode('/', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])){
            return true;
        }
        return false;
    }

  function Detalle_Articulo($IdArticulo) {
    $sql = "
      SELECT
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Categoria Articulo
  InvArticulo.InvCategoriaId ,
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
--Marca
    InvMarca.Nombre as Marca,
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
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
-- Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
-- Ultimo Proveedor (Nombre Proveedor)
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
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE InvArticulo.Id = '$IdArticulo'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }

  function Detalle_Articulo_CB($CodigoBarra) {
    $sql = "
      SELECT
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Categoria Articulo
  InvArticulo.InvCategoriaId ,
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
--Marca
    InvMarca.Nombre as Marca,
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
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
-- Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
-- Ultimo Proveedor (Nombre Proveedor)
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
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE (SELECT CodigoBarra
    FROM InvCodigoBarra
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) = '$CodigoBarra'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }

  function FG_Ordenar_Arreglo($array, $on, $order=SORT_ASC)
  {
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
  }

  function precioReconversion($precio, $moneda)
  {
    if (_AntesReconversion_ == 'SI' && _DespuesReconversion_ == 'NO') {
        if ($moneda == 'BSS') {
            $precio = $precio;
        }

        if ($moneda == 'BSD') {
            $precio = $precio / _FactorReconversion_;
        }
    }


    if (_DespuesReconversion_ == 'SI' && _AntesReconversion_ == 'NO') {
        if ($moneda == 'BSD') {
            $precio = $precio;
        }

        if ($moneda == 'BSS') {
            $precio = $precio * _FactorReconversion_;
        }
    }

    return $precio;
  }

  function siglasSegunNombre()
  {
    $array = [
        'FARMACIA TIERRA NEGRA, C.A.' => 'FTN',
        'FARMACIA TIERRA NEGRA, C.A. ( FTN )' => 'FTN',

        'FARMACIA LA LAGO,C.A.' => 'FLL',
        'FARMACIA LA LAGO,C.A. ( FLL )' => 'FLL',
        'FARMACIA LA LAGO CA (FLL)' => 'FLL',

        'FARMACIA AVENIDA UNIVERSIDAD, C.A.' => 'FAU',
        'FARMACIA AVENIDA UNIVERSIDAD, C.A. (FAU)' => 'FAU',
        'FARMACIA AVENIDA UNIVERSIDAD, CA' => 'FAU'
    ];

    return $array;
  }

  /**********************************************************************************/
  /*
    TITULO: Traslado_Transito
    FUNCION: Determina si articulo tiene traslado en otras sedes
    RETORNO: Arreglo
    DESAROLLADO POR: Nisa Delgado
  */
  function Traslado_Transito($codigo_barra = '')
  {
    $sede = FG_Mi_Ubicacion();

    if ($sede == 'FTN') {
        $nombre = 'FARMACIA TIERRA NEGRA, C.A.';
    }

    if ($sede == 'FAU' || $sede == 'DBs') {
        $nombre = 'FARMACIA AVENIDA UNIVERSIDAD, C.A.';
    }

    if ($sede == 'FLL') {
        $nombre = 'FARMACIA LA LAGO,C.A.';
    }

    if ($sede == 'FSM') {
        $nombre = 'FARMACIA MILLENNIUM 2000, C.A';
    }

    if ($sede == 'KDI') {
        return [];
    }

    if ($sede == 'FEC') {
        $nombre = 'FARMACIA EL CALLEJON, C.A.';
    }

    if ($sede == 'FLF') {
        $nombre = 'FARMACIA LA FUSTA';
    }

    if ($sede == 'CDD') {
        $nombre = 'CENTRO DE DISTRIBUCION GP';
    }

    if ($sede == 'KD73') {
        return [];
    }

    if ($codigo_barra) {
        $where = "traslados_detalle.codigo_barra = '$codigo_barra'";
    } else {
        $where = "
        traslados_detalle.id_traslado IN (
            SELECT
                traslados.numero_ajuste
            FROM
                traslados
            WHERE
                (traslados.estatus = 'PROCESADO' OR traslados.estatus = 'EMBALADO') AND
                traslados.sede_destino = '$nombre'
        )";
    }

    $array = [];
    $resultado = [];

    try {
        $ftn = DB::connection('ftn')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $ftn);
    } catch (Exception $excepcion) {

    }

    try {
        $fau = DB::connection('fau')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $fau);
    } catch (Exception $excepcion) {

    }

    try {
        $fll = DB::connection('fll')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $fll);
    } catch (Exception $excepcion) {

    }

    try {
        $fm = DB::connection('fm')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY.
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $fm);
    } catch (Exception $excepcion) {

    }

    try {
        $kdi = DB::connection('kdi')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $kdi);
    } catch (Exception $excepcion) {

    }

    try {
        $fec = DB::connection('fec')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $fec);
    } catch (Exception $excepcion) {

    }

    try {
        $flf = DB::connection('flf')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $flf);
    } catch (Exception $excepcion) {

    }

    try {
        $cdd = DB::connection('cdd')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $cdd);
    } catch (Exception $excepcion) {

    }

    try {
        $kd73 = DB::connection('kd73')->select("
            SELECT
                traslados_detalle.codigo_barra
            FROM
                traslados_detalle
            WHERE
                $where
            GROUP BY
                traslados_detalle.codigo_barra;
        ");

        $array = array_merge($array, $kd73);
    } catch (Exception $excepcion) {

    }

    foreach ($array as $item) {
        $resultado[] = $item->codigo_barra;
    }

    $resultado = array_unique($resultado);

    return $resultado;
  }

  function FG_Numero_Sede()
  {
    $sede = FG_Mi_Ubicacion();

    if ($sede == 'FTN') {
        return 1;
    }

    if ($sede == 'FAU' || $sede == 'DBs') {
        return 2;
    }

    if ($sede == 'FLL') {
        return 3;
    }

    if ($sede == 'FSM') {
        return 4;
    }

    if ($sede == 'FEC') {
        return 5;
    }

    if ($sede == 'FLF') {
        return 6;
    }

    if ($sede == 'CDD') {
        return 7;
    }
  }

  /**
   * Genera una tabla con la información de todas las etiquetas generadas
   * Autor: Nisa Delgado
   */
  function FG_Listado_Final_Etiquetas($clasificacion, $tipo, $dia, $listado)
  {
    echo '<div class="saltoDePagina"></div>';

    echo '<div class="list-group">';

    echo '<div class="list-group-item">';
    echo '<div class="row">';
    echo '<div class="col font-weight-bold">Código de barra</div>';
    echo '<div class="col font-weight-bold">Artículo</div>';
    echo '<div class="col font-weight-bold">Precio</div>';
    echo '<div class="col font-weight-bold">Existencia</div>';
    echo '<div class="col font-weight-bold">Clasificación</div>';
    echo '<div class="col font-weight-bold">Tipo</div>';
    echo '<div class="col font-weight-bold">Día</div>';
    echo '<div class="col font-weight-bold">Fecha y hora</div>';
    echo '</div>';
    echo '</div>';

    $fecha = date('d/m/Y h:i A');

    foreach ($listado as $item) {

        $conn = FG_Conectar_Smartpharma(FG_Mi_Ubicacion());
        $connCPharma = FG_Conectar_CPharma();

        $sql = SQG_Detalle_Articulo($item);
        $result = sqlsrv_query($conn,$sql);
        $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

        $codigo_barra = $row['CodigoBarra'];
        $articulo = $row['Descripcion'];
        $existencia = $row['Existencia'];
        $dolarizado = $row['Dolarizado'];
        $dolarizado = FG_Producto_Dolarizado($dolarizado);

        $precio = FG_Calculo_Precio_Alfa($row['Existencia'], $row['ExistenciaAlmacen1'], $row['ExistenciaAlmacen2'], $row['Troquelado'], $row['UtilidadArticulo'], $row['UtilidadCategoria'], $row['TroquelAlmacen1'], $row['PrecioCompraBrutoAlmacen1'], $row['TroquelAlmacen2'], $row['PrecioCompraBrutoAlmacen2'], $row['PrecioCompraBruto'], $row['Impuesto'], 'CON_EXISTENCIA');

        if ($dolarizado == 'SI') {
            $tasa = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));
            $precio = $precio / $tasa;
            $precio = SigDolar . ' ' . number_format($precio, 2);
        } else {
            $precio = SigVe . ' ' . number_format($precio, 2);
        }

        echo '<div class="list-group-item">';
        echo '<div class="row">';
        echo '<div class="col">'.$codigo_barra.'</div>';
        echo '<div class="col">'.FG_Limpiar_Texto($articulo).'</div>';
        echo '<div class="col">'.$precio.'</div>';
        echo '<div class="col">'.$existencia.'</div>';
        echo '<div class="col">'.$_GET['clasificacion'].'</div>';
        echo '<div class="col">'.$_GET['tipo'].'</div>';
        echo '<div class="col">'.$_GET['dia'].'</div>';
        echo '<div class="col">'.$fecha.'</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
  }
?>
