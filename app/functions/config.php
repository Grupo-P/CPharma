<?php
/**********************************************************************************/
/***************************** CONSTANTES GLOBALES *******************************/
define("SigDolar","$");
define("SigDolarPublico","REF"); // Solo afecta etiquetas y consultor
define("SigVe","Bs.");
define("Utilidad",0.77);
define("Impuesto",1.16);
define("DecimalCorrida",0.01); //Decimal que se concatena al final a los precio en las corridas
define("DecimalEtiqueta",01);	//Decimal que se usa para comparar el precio de las etiquetas
define("Version","CPharma v.8.5"); //Estable: Sedes KDI y FSM (ON LINE - OFF LINE)
define("_ConsultorDolar_","SI"); //Le indica al consultor si mostrar precios en dolares o no
define("_EtiquetaDolar_","SI");	//Le indica al etiquetado si mostrar precios en dolares o no
define("_MensajeDolar_","SI");	//Le indica al etiquetado si mostrar el _MensajeDolarLegal_ o no
define("_MensajeDolarLegal_","");
define("_AntesReconversion_", "NO");
define("_DespuesReconversion_", "SI");
define("_FactorReconversion_", 1000000);
/**********************************************************************************/
/********* CADENA DE CONEXION CON LA BASE DE DATOS LOCAL DEL CPHARMA *************/
define ("serverCP","localhost:3306");
define ("userCP","root");
define ("passCP",""); //.JR@2ZUS8m2M*gW
define ("nameCP","cpharma");
/**********************************************************************************/
/************************* NOMBRES SEDES ON-LINE *********************************/
define ("SedeFTN","FARMACIA TIERRA NEGRA, C.A.");
define ("SedeFLL","FARMACIA LA LAGO,C.A.");
define ("SedeFAU","FARMACIA AVENIDA UNIVERSIDAD, C.A.");
define ("SedeKDI","FARMACIAS KD EXPRESS, C.A.");
define ("SedeFSM","FARMACIA MILLENNIUM 2000, C.A.");
define ("SedeFEC","FARMACIA EL CALLEJON, C.A.");
define ("SedeKD73","FARMACIAS KD EXPRESS, C.A. - KD73");
define ("SedeGP","Servidor de Testing");
define ("SedeDBs","Servidor de Desarrollo");
define ("SedeDBsa","Servidor de Desarrollo Sergio");
/**********************************************************************************/
/************************ NOMBRES SEDES OFF-LINE *********************************/
define ("SedeFTNOFF","FARMACIA TIERRA NEGRA, C.A.<br>OFF-LINE");
define ("SedeFLLOFF","FARMACIA LA LAGO,C.A.<br>OFF-LINE");
define ("SedeFAUOFF","FARMACIA AVENIDA UNIVERSIDAD, C.A.<br>OFF-LINE");
define ("SedeKDIOFF","FARMACIAS KD EXPRESS, C.A.<br>OFF-LINE");
define ("SedeFSMOFF","FARMACIA MILLENNIUM 2000, C.A.<br>OFF-LINE");
define ("SedeFECOFF","FARMACIA EL CALLEJON, C.A.<br>OFF-LINE");
define ("SedeKD73OFF","FARMACIAS KD EXPRESS, C.A. - KD73<br>OFF-LINE");
/**********************************************************************************/
/************************* CONEXION ON LINE FTN **********************************/
define ("serverFTN" , "10.100.0.2\SMARTPHARMA,1450");
define ("userFTN" , "sa");
define ("passFTN" , "Soporte123");
define ("nameFTN" , "SMART");
define ("nameFTNOFF" , "cpharmaftn");
/**********************************************************************************/
/************************* CONEXION ON LINE FLL **********************************/
define ("serverFLL" , "192.168.7.34\SMARTPHARMA");
define ("userFLL" , "sa");
define ("passFLL" , "Soporte123");
define ("nameFLL" , "smartlalago");
define ("nameFLLOFF" , "cpharmafll");
/**********************************************************************************/
/************************* CONEXION ON LINE FAU **********************************/
define ("serverFAU" , "10.100.0.34\SMARTPHARMA");
define ("userFAU" , "sa");
define ("passFAU" , "Soporte123");
define ("nameFAU" , "smartfau");
define ("nameFAUOFF" , "cpharmafau");
/**********************************************************************************/
/************************* CONEXION ON LINE KDI **********************************/
define ("serverKDI" , "192.168.15.35");
define ("userKDI" , "admin");
define ("passKDI" , "soporte123");
define ("nameKDI" , "KD_EXPRESS_SMART");
define ("nameKDIOFF" , "cpharmakdi");
/**********************************************************************************/
/************************* CONEXION ON LINE FSM **********************************/
define ("serverFSM" , "192.168.18.35\SMARTPHARMA");
define ("userFSM" , "admin");
define ("passFSM" , "soporte123");
define ("nameFSM" , "SMARTPHARMA");
define ("nameFSMOFF" , "cpharmafsm");
/**********************************************************************************/
/************************* CONEXION ON LINE FEC **********************************/
define ("serverFEC" , "10.100.0.50");
define ("userFEC" , "admin");
define ("passFEC" , "soporte123");
define ("nameFEC" , "BD_ELCALLEJON");
define ("nameFECOFF" , "cpharmafec");
/**********************************************************************************/
/************************* CONEXION ON LINE KD73 **********************************/
define ("serverKD73" , "192.168.60.35");
define ("userKD73" , "admin");
define ("passKD73" , "soporte123");
define ("nameKD73" , "BD_KDEXPRESS");
define ("nameKD73OFF" , "cpharmakd73");
/**********************************************************************************/
/********************* CONEXION SERVER DESARROLLO GRUPO P **********************/
define ("serverGP" , "TESTSERVER\TESTINGSERVER");
define ("userGP" , "sa");
define ("passGP" , "pCadm05");
define ("nameGP" , "cpharmaftn");
/**********************************************************************************/
/********************* CONEXION SERVER DESARROLLO DE NISAUL **********************/
define ("serverDBs" , "NISADELGADO\SQLEXPRESS");
define ("userDBs" , "sa");
define ("passDBs" , "123");
define ("nameDBs" , "smartpharma");
/**********************************************************************************/
/********************* CONEXION SERVER DESARROLLO DE SERGIO ARG **********************/
define ("serverDBsa" , "SERGIO-PC");
define ("userDBsa" , "sa");
define ("passDBsa" , "soporte123");
define ("nameDBsa" , "cpharmafau");
/**********************************************************************************/
/************* CONEXION GALAC NOMINA *******************/
define ("serverGN" , "10.100.0.34\SMARTPHARMA");
define ("userGN" , "sa");
define ("passGN" , "Soporte123");
define ("nameGN" , "NOMDB");
/**********************************************************************************/
/************* CONEXION GALAC SAW (Administrativo)*******************/
define ("serverGS" , "10.100.0.2\SMARTPHARMA,1450");
define ("userGS" , "sa");
define ("passGS" , "Soporte123");
define ("nameGS" , "SAWDB");
/********************** APP CONSULTA ************************/
date_default_timezone_set('America/Caracas');
define ("Nperdida" , 3);
define ("Dperdida" , 15);
$excluirComp = array (
"CAFEINA"
,"CAFEINA+DIHIDROERGOTAMINA"
,"DIHIDROERGOTAMINA"
,"TRAT/ CEFALEA (DOLOR DE CABEZA)"
,"ACEITE DE LINAZA+OMEGA 3"
,"ACEITE DE LINAZA+OMEGA 3+OMEGA 6"
,"ACEITE DE PESCADO"
,"ACEITE MINERAL+ACIDO ESTEARICO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"ACETAMINOFEN+ACIDO ACETILSALICILICO"
,"ACIDO ACETILSALICILICO+CAFEINA"
,"ACIDO BORICO+ALCANFOR"
,"ACIDO BORICO+ALCANFOR+CALAMINA"
,"ACIDO CITRICO"
,"ACIDO CITRICO+ALCOHOL CETILICO"
,"ACIDO CITRICO+ALCOHOL CETILICO+FENOXIETANOL"
,"ACIDO CITRICO+ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA"
,"ACIDO CITRICO+ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"ACIDO CLAVULANICO"
,"ACIDO ESTEARICO"
,"ACIDO ESTEARICO+AGUA"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"ACIDO FOLICO+BIOTINA"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)+VIT B5 (ACIDO PANTOTENICO)"
,"ACIDO FOLICO+HIERRO+VIT B1 (TIAMINA)"
,"ACIDO FOLICO+HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"ACIDO LACTICO+ALKILAMINOBETAINA"
,"AGUA+ALCOHOL CETILICO"
,"AGUA+ALCOHOL CETILICO+LANOLINA"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"AGUA+CARBOMEL"
,"AGUA+CARBOMEL+DIMETICONA"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"AGUA+CARBOMEL+GRANULOS DE POLIETILENO"
,"AGUA+CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO"
,"AGUA+CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO"
,"ALCACHOFA+CASCARA SAGRADA"
,"ALCANFOR+BENCIL NICOTINATO"
,"ALCANFOR+CALAMINA"
,"ALCANFOR+CALAMINA+OXIDO DE ZINC"
,"ALCANFOR+CALAMINA+OXIDO DE ZINC+TALCO"
,"ALCOHOL CETILICO"
,"ALCOHOL CETILICO+FENOXIETANOL"
,"ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA"
,"ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO"
,"ALCOHOL CETILICO+LANOLINA"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"ALKILAMINOBETAINA"
,"ALKILAMINOBETAINA+COCAMIDE D.O.A"
,"AMILASA"
,"AMILASA+LIPASA"
,"BELLADONA"
,"BELLADONA+ERGOTAMINA"
,"BENCIL NICOTINATO"
,"BENCIL NICOTINATO+MENTOL"
,"BETAMETASONA L.P"
,"BORNEOL"
,"BORNEOL+CANFENO"
,"BROMELINA"
,"CAFEINA+DIHIDROERGOTAMINA"
,"CALAMINA+OXIDO DE ZINC"
,"CALAMINA+OXIDO DE ZINC+TALCO"
,"CALCIO+HIERRO"
,"CALCIO+HIERRO+SPIRULINA"
,"CANFENO"
,"CANFENO+PINENO"
,"CARBIDOPA"
,"CARBIDOPA+ENTACAPONA"
,"CARBOHIDRATOS+LACTOBACILOS ACIDOFILOS"
,"CARBOMEL"
,"CARBOMEL+DIMETICONA"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"CARBOMEL+GRANULOS DE POLIETILENO"
,"CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO"
,"CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO"
,"CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO+PROPILENGLICOL"
,"CARISOPRODOL"
,"CARISOPRODOL+DIPIRONA"
,"CASCARA SAGRADA+FUCUS"
,"CEFOPERAZONA"
,"CETILPIRIDINIO"
,"CICLOBENZAPRINA"
,"CIPIONATO DE ESTRADIOL"
,"CIPROTERONA"
,"CITRATO DE SODIO"
,"CLIOQUINOL"
,"CLIOQUINOL+GENTAMICINA"
,"CLORFENIRAMINA+FENILEFRINA"
,"CLORFENIRAMINA+PSEUDOFEDRINA"
,"CLORMANIDON"
,"CLORURO DE CALCIO"
,"CLORURO DE CALCIO+CLORURO DE POTASIO"
,"CLORURO DE POTASIO+CLORURO DE SODIO+LACTATO DE SODIO"
,"CLORURO DE SODIO+LACTATO DE SODIO"
,"CLOTRIMAZOL+DEXAMETASONA"
,"CLOTRIMAZOL+DIPROPIONATO DE BETAMETASONA"
,"CLOTRIMAZOL+GENTAMICINA"
,"CLOTRIMAZOL+NEOMICINA"
,"COCAMIDE D.O.A"
,"COCOIL-ISETIONATO DE SODIO"
,"COCOIL-ISETIONATO DE SODIO+LAURIL SULFATO DE SODIO"
,"CONDROITINA"
,"DEXAMETASONA+NEOMICINA"
,"DEXTROMETORFANO+MIEL DE ABEJA"
,"DEXTROMETORFANO+SEUDOFEDRINA"
,"DIBUCAINA"
,"DIMETICONA+DIOXIDO DE TITANIO"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"DIMETILPOLISILOXANO"
,"DIOXIDO DE TITANIO+GLICERINA"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"DIPIRONA+SALICILAMIDA"
,"DIPROPIONATO DE BETAMETASONA"
,"DROSPIRENONA"
,"ENTACAPONA"
,"ERGOTAMINA"
,"ESCINA"
,"ESTEAROILGLICOLATO"
,"ETINILESTRADIOL"
,"FENILEFRINA"
,"FENILEFRINA+KANAMICINA"
,"FENOXIETANOL"
,"FENOXIETANOL+HIDROXIPROPILMETILCELULOSA"
,"FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO"
,"FLAVONOIDE"
,"FUCUS"
,"GENTAMICINA+IODOCLORHIDROXIQUINOLINA"
,"GESTODENO"
,"GLICERINA+HIDROXIDO DE ALUMINIO"
,"GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"GRANULOS DE POLIETILENO"
,"GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO"
,"GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO"
,"GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO+PROPILENGLICOL"
,"HEMICELULOSA"
,"HEMICELULOSA+PANCREATINA"
,"HEXAMIDINA"
,"HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO"
,"HIERRO+SACAROSA"
,"HIERRO+SPIRULINA"
,"HIERRO+SPIRULINA+VIT A (BETACAROTENO)"
,"HIERRO+SPIRULINA+VIT A (BETACAROTENO)+VIT B12 (CIANOCOBALAMINA)"
,"HIERRO+VIT B1 (TIAMINA)"
,"HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)"
,"HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"IODOCLORHIDROXIQUINOLINA"
,"KANAMICINA"
,"LACTATO DE SODIO"
,"LANOLINA"
,"LANOLINA+METILPARABENO"
,"LANOLINA+METILPARABENO+PROPILPARABENO"
,"LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"LAURIL SULFATO DE SODIO+METILPARABENO"
,"LAURIL SULFATO DE SODIO+METILPARABENO+PROPILENGLICOL"
,"LAURIL SULFATO DE SODIO+SELENIO"
,"LEVODOPA"
,"LIDOCAINA+VIT B1 (TIAMINA)"
,"LIDOCAINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"LIMECICLINA"
,"LIPASA"
,"LIPASA+PROTEASA"
,"L-ISOLEUCINA+L-LEUCINA"
,"L-ISOLEUCINA+L-LEUCINA+L-VALINA"
,"L-ISOLEUCINA+L-LEUCINA+L-VALINA+VIT B6 (PIRIDOXINA)"
,"L-LEUCINA+L-VALINA"
,"L-LEUCINA+L-VALINA+VIT B6 (PIRIDOXINA)"
,"L-LEUCINA+L-VALINA+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"LUTEINA"
,"LUTEINA+VIT C (ACIDO ASCORBICO)"
,"METILPARABENO"
,"METILPARABENO+PROPILENGLICOL"
,"METILPARABENO+PROPILPARABENO"
,"METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"MINERALES"
,"NEOMICINA"
,"NEOMICINA+POLIXIMINA"
,"OMEGA 6+OMEGA 9"
,"OMEGA 9"
,"OXIDO DE ZINC+TALCO"
,"OXYBENZONE"
,"OXYBENZONE+PROPILENGLICOL"
,"OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"PANCREATINA+SIMETICONA"
,"PINENO"
,"PIPERACILINA"
,"POLIXIMINA"
,"PROMETAZINA"
,"PROPILENGLICOL"
,"PROPILENGLICOL+VINYLPYRROLIDINE"
,"PROPILPARABENO"
,"PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"PROPINOX CLORHIDRATO"
,"PROTEASA"
,"PSEUDOFEDRINA"
,"SACAROSA"
,"SACAROSA+VIT B12 (CIANOCOBALAMINA)"
,"SALICILAMIDA"
,"SEUDOFEDRINA"
,"SORBITOL (EDULCORANTE P/DIABETICOS)"
,"SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"SPIRULINA"
,"SPIRULINA+VIT A (BETACAROTENO)+VIT B12 (CIANOCOBALAMINA)"
,"SULBACTAN"
,"SULFAMETOXAZOL"
,"TAZOBACTAN"
,"TRIETANOLAMINA"
,"TRIMETOPRIM"
,"VINYLPYRROLIDINE"
,"VIT A (BETACAROTENO)+VIT B12 (CIANOCOBALAMINA)"
,"VIT A (BETACAROTENO)+VIT B2 (RIBOFLAVINA)"
,"VIT A (BETACAROTENO)+VIT C (ACIDO ASCORBICO)"
,"VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)"
,"VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"VIT B1 (TIAMINA)+VIT B2 (RIBOFLAVINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)+VIT B6 (PIRIDOXINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"VIT B2 (RIBOFLAVINA)"
,"VIT B2 (RIBOFLAVINA)+VIT B6 (PIRIDOXINA)"
,"VIT B2 (RIBOFLAVINA)+VIT D"
,"VIT B5 (ACIDO PANTOTENICO)+VIT E"
,"VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"VIT C (ACIDO ASCORBICO)+VIT D"
,"VIT C (ACIDO ASCORBICO)+ZEAXANTINA"
,"VITAMINAS"
,"ZEAXANTINA"
,"ANALGESICO NEUROPATICO"
,"ANTIACIDO+ANTIESPASMODICO"
,"ANTIESPASMODICO+ANTIFLAMATORIO"
,"ANTIESPASMODICO+ANTIFLATULENTO"
,"ANTIHISTAMINICO+BRONCODILATADOR"
,"ANTIPIRETICO+ANTIPIRETICO PEDIATRIO"
,"COADYUVANTE/ ACTIVIDAD NEURONAL"
,"COADYUVANTE/ ACTIVIDAD NEURONAL+COMPLEJO VITAMINICO (AMPOLLA I.M)"
,"COADYUVANTE/ ACTIVIDAD NEURONAL+COMPLEJO VITAMINICO (AMPOLLA I.M)+TRATAMIENTO DE NEURITIS"
,"COMPLEJO VITAMINICO (AMPOLLA I.M)"
,"COMPLEJO VITAMINICO (AMPOLLA I.M)+TRATAMIENTO DE NEURITIS"
,"CORTICOSTEROIDE (TERAPIA RESPIRATORIA)"
,"CORTICOSTEROIDE (USO OTICO)"
,"DISTONIA NEUROVEGETATIVA"
,"ESTIMULANTE/SIST. NERVIOSO CENT"
,"HIDRATANTE CAPILAR"
,"JABON MEDICADO"
,"NEURALGIA ESTOMACAL"
,"REGULADOR DE PH"
,"VASODILATADOR"
,"VITAMINA"
,"ANTIACIDO+ANTIESPASMODICO"
,"ANTIESPASMODICO+ANTIFLATULENTO"
,"ANTIHISTAMINICO+BRONCODILATADOR"
,"ANTIPIRETICO+ANTIPIRETICO PEDIATRIO"
,"ASCARIDIASIS (ANTIPARASITARIO)"
,"BRONCODILATADOR+CORTICOSTEROIDE INHALADOR"
,"CICATRIZANTE DE USO VAGINAL"
,"CICATRIZANTE+HIDRATANTE DE PIEL  (MEDICADO)"
,"CICATRIZANTE+REGENERADOR DE PIEL"
,"COADYUVANTE/ ACTIVIDAD NEURONAL"
,"COADYUVANTE/ ACTIVIDAD NEURONAL+COMPLEJO VITAMINICO (AMPOLLA I.M)"
,"COADYUVANTE/ ACTIVIDAD NEURONAL+COMPLEJO VITAMINICO (AMPOLLA I.M)+COMPLEJO VITAMINICO (AMPOLLA I.V)"
,"COADYUVANTE/ ACTIVIDAD NEURONAL+COMPLEJO VITAMINICO (AMPOLLA I.M)+COMPLEJO VITAMINICO (AMPOLLA I.V)+TRATAMIENTO DE NEURITIS"
,"COADYUVANTE/ ACTIVIDAD NEURONAL+COMPLEJO VITAMINICO (AMPOLLA I.M)+TRATAMIENTO DE NEURITIS"
,"COMPLEJO VITAMINICO (AMPOLLA I.M)"
,"COMPLEJO VITAMINICO (AMPOLLA I.M)+COMPLEJO VITAMINICO (AMPOLLA I.V)"
,"COMPLEJO VITAMINICO (AMPOLLA I.M)+COMPLEJO VITAMINICO (AMPOLLA I.V)+TRATAMIENTO DE NEURITIS"
,"COMPLEJO VITAMINICO (AMPOLLA I.M)+TRATAMIENTO DE NEURITIS"
,"COMPLEJO VITAMINICO (AMPOLLA I.V)+TRATAMIENTO DE NEURITIS"
,"CORTICOSTEROIDE (USO OTICO)"
,"DISTONIA NEUROVEGETATIVA"
,"HIDRATANTE CAPILAR"
,"HIDRATANTE DE PIEL  (MEDICADO)+REGENERADOR DE PIEL"
,"INSULINA"
,"JABON MEDICADO"
,"NEURALGIA ESTOMACAL"
,"OXIURIASIS (ANTIPARASITARIO)"
,"TRAT/ ABRASIONES CUTANEAS"
,"TRATAMIENTO DE GASTRITIS"
,"TRATAMIENTO DE NEURITIS"
,"TRATAMIENTO DE ULCERAS CUTANEAS"
,"VASODILATADOR"
,"VITAMINA"
,"ACEITE DE LINAZA+OMEGA 3"
,"ACEITE DE LINAZA+OMEGA 3+OMEGA 6"
,"ACEITE DE NUEZ"
,"ACEITE DE PESCADO"
,"ACEITE MINERAL+ACIDO ESTEARICO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"ACEITE MINERAL+ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"ACETAMINOFEN+ACIDO ACETILSALICILICO"
,"ACETATO DE BETAMETASONA"
,"ACIDO ACETILSALICILICO+CAFEINA"
,"ACIDO BENZOICO"
,"ACIDO BORICO+ALCANFOR"
,"ACIDO BORICO+ALCANFOR+CALAMINA"
,"ACIDO CITRICO"
,"ACIDO CITRICO+ALCOHOL CETILICO"
,"ACIDO CITRICO+ALCOHOL CETILICO+FENOXIETANOL"
,"ACIDO CITRICO+ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA"
,"ACIDO CITRICO+ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"ACIDO CLAVULANICO"
,"ACIDO ESTEARICO"
,"ACIDO ESTEARICO+AGUA"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"ACIDO ESTEARICO+AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"ACIDO FOLICO+BIOTINA"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)"
,"ACIDO FOLICO+BIOTINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)+VIT B5 (ACIDO PANTOTENICO)"
,"ACIDO FOLICO+HIERRO+VIT B1 (TIAMINA)"
,"ACIDO FOLICO+HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"ACIDO FOLICO+VIT B12 (CIANOCOBALAMINA)"
,"ACIDO LACTICO+AGUA"
,"ACIDO LACTICO+AGUA+ALCOHOL CETILICO"
,"ACIDO LACTICO+ALANTOINA"
,"ACIDO LACTICO+ALANTOINA+COCAMIDE D.O.A"
,"ACIDO LACTICO+ALKILAMINOBETAINA"
,"AGUA+ALCOHOL CETILICO"
,"AGUA+ALCOHOL CETILICO+LANOLINA"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"AGUA+ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"AGUA+ALOE VERA"
,"AGUA+ALOE VERA+CAMOMILA"
,"AGUA+ALOE VERA+CAMOMILA+CARBOMEL"
,"AGUA+ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO"
,"AGUA+ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA"
,"AGUA+ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA"
,"AGUA+ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL"
,"AGUA+CARBOMEL"
,"AGUA+CARBOMEL+DIMETICONA"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"AGUA+CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"AGUA+CARBOMEL+GRANULOS DE POLIETILENO"
,"AGUA+CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO"
,"AGUA+CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO"
,"ALANTOINA"
,"ALANTOINA+COCAMIDE D.O.A"
,"ALANTOINA+COCAMIDE D.O.A+VIT B5 (ACIDO PANTOTENICO)"
,"ALCACHOFA+CASCARA SAGRADA"
,"ALCANFOR+BENCIL NICOTINATO"
,"ALCANFOR+CALAMINA"
,"ALCANFOR+CALAMINA+OXIDO DE ZINC"
,"ALCANFOR+CALAMINA+OXIDO DE ZINC+TALCO"
,"ALCANFOR+EUCALIPTO"
,"ALCOHOL CETILICO"
,"ALCOHOL CETILICO+FENOXIETANOL"
,"ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA"
,"ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"ALCOHOL CETILICO+FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO"
,"ALCOHOL CETILICO+LANOLINA"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"ALCOHOL CETILICO+LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"ALKILAMINOBETAINA"
,"ALKILAMINOBETAINA+COCAMIDE D.O.A"
,"ALOE VERA"
,"ALOE VERA+CAMOMILA"
,"ALOE VERA+CAMOMILA+CARBOMEL"
,"ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO"
,"ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA"
,"ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA"
,"ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL"
,"ALOE VERA+CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"ALOE VERA+OXIDO DE ZINC"
,"AMILASA"
,"AMILASA+LIPASA"
,"AMINOACIDOS+CALCIO"
,"AMINOACIDOS+CALCIO+LIPIDOS"
,"AMINOACIDOS+CALCIO+LIPIDOS+MALTODEXTRINA"
,"AMOXICILINA+CLARITROMICINA"
,"BECLOMETASONA+CLOTRIMAZOL"
,"BELLADONA"
,"BENCIL NICOTINATO"
,"BENCIL NICOTINATO+MENTOL"
,"BETAMETASONA L.P"
,"BORNEOL"
,"BORNEOL+CANFENO"
,"CAFEINA+CODEINA"
,"CAFEINA+DIHIDROERGOTAMINA"
,"CALAMINA+OXIDO DE ZINC"
,"CALAMINA+OXIDO DE ZINC+TALCO"
,"CALCIO+HIERRO"
,"CALCIO+HIERRO+SPIRULINA"
,"CALCIO+LIPIDOS"
,"CALCIO+LIPIDOS+MALTODEXTRINA"
,"CALCIO+LIPIDOS+PROTEINA"
,"CAMOMILA"
,"CAMOMILA+CARBOMEL"
,"CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO"
,"CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA"
,"CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA"
,"CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL"
,"CAMOMILA+CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"CANFENO"
,"CANFENO+CARBIDOPA"
,"CANFENO+PINENO"
,"CARBIDOPA"
,"CARBIDOPA+ENTACAPONA"
,"CARBOHIDRATOS+LACTOBACILOS ACIDOFILOS"
,"CARBOHIDRATOS+LACTOBACILOS ACIDOFILOS+PROTEINA+VIT A (BETACAROTENO)"
,"CARBOMEL"
,"CARBOMEL+DIMETICONA"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"CARBOMEL+DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"CARBOMEL+DIOXIDO DE TITANIO"
,"CARBOMEL+DIOXIDO DE TITANIO+GLICERINA"
,"CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA"
,"CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL"
,"CARBOMEL+DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"CARBOMEL+GRANULOS DE POLIETILENO"
,"CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO"
,"CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO"
,"CARBOMEL+GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO+PROPILENGLICOL"
,"CARISOPRODOL"
,"CARISOPRODOL+DIPIRONA"
,"CASCARA SAGRADA+FUCUS"
,"CEFOPERAZONA"
,"CICLODEXTRINA"
,"CILASTATINA"
,"CILASTATINA+IMIPENEM"
,"CIPIONATO DE ESTRADIOL"
,"CIPROFLOXACINA+VIT C (ACIDO ASCORBICO)"
,"CIPROTERONA"
,"CITRATO DE SODIO"
,"CLARITROMICINA+ESOMEPRAZOL"
,"CLIOQUINOL"
,"CLIOQUINOL+GENTAMICINA"
,"CLORFENIRAMINA+FENILEFRINA"
,"CLORFENIRAMINA+PSEUDOFEDRINA"
,"CLORFENIRAMINA+SEUDOFEDRINA"
,"CLORMANIDON"
,"CLORURO DE CALCIO"
,"CLORURO DE CALCIO+CLORURO DE POTASIO"
,"CLORURO DE SODIO+LACTATO DE SODIO"
,"CLOTRIMAZOL+DEXAMETASONA"
,"CLOTRIMAZOL+GENTAMICINA"
,"CLOTRIMAZOL+NEOMICINA"
,"COCAMIDE D.O.A"
,"COCAMIDE D.O.A+VIT B5 (ACIDO PANTOTENICO)"
,"COCOIL-ISETIONATO DE SODIO"
,"COCOIL-ISETIONATO DE SODIO+LAURIL SULFATO DE SODIO"
,"CONDROITINA"
,"DECAMETRINA"
,"DEXAMETASONA+NEOMICINA"
,"DIBUCAINA"
,"DIHIDROERGOTAMINA+IBUPROFENO"
,"DIMETICONA+DIOXIDO DE TITANIO"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"DIMETICONA+DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"DIMETICONA+HIDROXIDO DE ALUMINIO"
,"DIOXIDO DE TITANIO+GLICERINA"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"DIOXIDO DE TITANIO+GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA"
,"DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL"
,"DIOXIDO DE TITANIO+GLICERINA+OCTREOTIDA+PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"DIPIRONA+SALICILAMIDA"
,"DIPROPIONATO DE BETAMETASONA"
,"DOCUSATO DE SODIO"
,"DROSPIRENONA"
,"ENTACAPONA"
,"ESCINA"
,"ESTEAROILGLICOLATO"
,"ETINILESTRADIOL"
,"FENAZOPIRIDINA CLORHIDRATO"
,"FENILEFRINA"
,"FENILEFRINA+KANAMICINA"
,"FENOXIETANOL"
,"FENOXIETANOL+FENTICONAZOL"
,"FENOXIETANOL+HIDROXIPROPILMETILCELULOSA"
,"FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"FENOXIETANOL+HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO"
,"FLAVONOIDE"
,"FUCUS"
,"GENTAMICINA+IODOCLORHIDROXIQUINOLINA"
,"GESTODENO"
,"GLICERINA+HIDROXIDO DE ALUMINIO"
,"GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"GLICERINA+HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"GLICERINA+OCTREOTIDA"
,"GLICERINA+OCTREOTIDA+PROPILENGLICOL"
,"GLICERINA+OCTREOTIDA+PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"GRANULOS DE POLIETILENO"
,"GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO"
,"GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO"
,"GRANULOS DE POLIETILENO+LAURIL SULFATO DE SODIO+METILPARABENO+PROPILENGLICOL"
,"HEMICELULOSA"
,"HEMICELULOSA+PANCREATINA"
,"HEXAMIDINA"
,"HIDROXIDO DE ALUMINIO+OXYBENZONE"
,"HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL"
,"HIDROXIDO DE ALUMINIO+OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO"
,"HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO"
,"HIDROXIPROPILMETILCELULOSA+LAURIL SULFATO DE SODIO+SELENIO+SELENIO"
,"HIERRO+SACAROSA"
,"HIERRO+SPIRULINA"
,"HIERRO+SPIRULINA+VIT A (BETACAROTENO)"
,"HIERRO+SPIRULINA+VIT A (BETACAROTENO)+VIT B12 (CIANOCOBALAMINA)"
,"HIERRO+VIT B1 (TIAMINA)"
,"HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)"
,"HIERRO+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"IODOCLORHIDROXIQUINOLINA"
,"KANAMICINA"
,"LACTASA"
,"LACTATO DE SODIO"
,"LACTOBACILOS ACIDOFILOS+PROTEINA+VIT A (BETACAROTENO)"
,"LACTOBACILOS ACIDOFILOS+PROTEINA+VIT A (BETACAROTENO)+VIT D"
,"LANOLINA"
,"LANOLINA+METILPARABENO"
,"LANOLINA+METILPARABENO+PROPILPARABENO"
,"LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"LANOLINA+METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"LAURIL SULFATO DE SODIO+METILPARABENO"
,"LAURIL SULFATO DE SODIO+METILPARABENO+PROPILENGLICOL"
,"LAURIL SULFATO DE SODIO+SELENIO"
,"LAURIL SULFATO DE SODIO+SELENIO+SELENIO"
,"LAURIL SULFATO DE SODIO+SULFATO DE SELENIO"
,"LEVODOPA"
,"LIDOCAINA+VIT B1 (TIAMINA)"
,"LIDOCAINA+VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)"
,"LIMECICLINA"
,"LIPASA"
,"LIPASA+PROTEASA"
,"LIPIDOS"
,"LIPIDOS+MALTODEXTRINA"
,"LIPIDOS+MALTODEXTRINA+VITAMINAS Y MINERALES"
,"LIPIDOS+PROTEINA"
,"L-ISOLEUCINA"
,"L-ISOLEUCINA+L-LEUCINA"
,"L-ISOLEUCINA+L-LEUCINA+L-VALINA"
,"L-ISOLEUCINA+L-LEUCINA+L-VALINA+VIT B6 (PIRIDOXINA)"
,"L-LEUCINA"
,"L-LEUCINA+L-VALINA"
,"L-LEUCINA+L-VALINA+VIT B6 (PIRIDOXINA)"
,"L-LEUCINA+L-VALINA+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"LUTEINA"
,"LUTEINA+VIT C (ACIDO ASCORBICO)"
,"L-VALINA"
,"L-VALINA+VIT B6 (PIRIDOXINA)"
,"MALTODEXTRINA"
,"MALTODEXTRINA+VITAMINAS Y MINERALES"
,"METILPARABENO"
,"METILPARABENO+PROPILENGLICOL"
,"METILPARABENO+PROPILPARABENO"
,"METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"METILPARABENO+PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"MIEL DE ABEJA+VIT C (ACIDO ASCORBICO)"
,"MINERALES"
,"NEOMICINA"
,"NEOMICINA+POLIXIMINA"
,"NEOMICINA+SULFATO DE POLIMIXINA"
,"OCTREOTIDA+PROPILENGLICOL"
,"OCTREOTIDA+PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"OMEGA 6+OMEGA 9"
,"OMEGA 9"
,"OXIDO DE ZINC+TALCO"
,"OXIDO DE ZINC+VIT E"
,"OXYBENZONE"
,"OXYBENZONE+PROPILENGLICOL"
,"OXYBENZONE+PROPILENGLICOL+VINYLPYRROLIDINE"
,"PANCREATINA+SIMETICONA"
,"PINENO"
,"PIPERACILINA"
,"PIPERONILO"
,"PIRA"
,"POLIXIMINA"
,"PROMETAZINA"
,"PROPILENGLICOL"
,"PROPILENGLICOL+VINYLPYRROLIDINE"
,"PROPILENGLICOL+VIT B5 (ACIDO PANTOTENICO)"
,"PROPILPARABENO"
,"PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)"
,"PROPILPARABENO+SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"PROPINOX CLORHIDRATO"
,"PROTEASA"
,"PROTEINA DE LECHE"
,"PROTEINA+VIT A (BETACAROTENO)"
,"PROTEINA+VIT A (BETACAROTENO)+VIT D"
,"PSEUDOFEDRINA"
,"SACAROSA"
,"SACAROSA+VIT B12 (CIANOCOBALAMINA)"
,"SALICILAMIDA"
,"SELENIO+SELENIO"
,"SEUDOFEDRINA"
,"SORBITOL (EDULCORANTE P/DIABETICOS)"
,"SORBITOL (EDULCORANTE P/DIABETICOS)+TRIETANOLAMINA"
,"SPIRULINA"
,"SPIRULINA+VIT A (BETACAROTENO)+VIT B12 (CIANOCOBALAMINA)"
,"SULBACTAN"
,"SULFAMETIZOL"
,"SULFAMETOXAZOL"
,"SULFATO DE POLIMIXINA"
,"SULFATO DE SELENIO"
,"TAZOBACTAN"
,"TRIBENOSIDO"
,"TRIETANOLAMINA"
,"TRIMETOPRIM"
,"VINYLPYRROLIDINE"
,"VIT A (BETACAROTENO)+VIT B12 (CIANOCOBALAMINA)"
,"VIT A (BETACAROTENO)+VIT B2 (RIBOFLAVINA)"
,"VIT A (BETACAROTENO)+VIT C (ACIDO ASCORBICO)"
,"VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)"
,"VIT B1 (TIAMINA)+VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"VIT B1 (TIAMINA)+VIT B2 (RIBOFLAVINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B2 (RIBOFLAVINA)+VIT B6 (PIRIDOXINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)"
,"VIT B12 (CIANOCOBALAMINA)+VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"VIT B2 (RIBOFLAVINA)"
,"VIT B2 (RIBOFLAVINA)+VIT B6 (PIRIDOXINA)"
,"VIT B2 (RIBOFLAVINA)+VIT D"
,"VIT B3 (NIACINAMIDA)"
,"VIT B5 (ACIDO PANTOTENICO)+VIT E"
,"VIT B6 (PIRIDOXINA)+VIT C (ACIDO ASCORBICO)"
,"VIT C (ACIDO ASCORBICO)+VIT D"
,"VIT C (ACIDO ASCORBICO)+ZEAXANTINA"
,"VITAMINAS"
,"ZEAXANTINA"
);
?>
