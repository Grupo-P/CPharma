<?php
/*
	Constantes Globales
 */
define("SigDolar","$");
define("SigVe","Bs.S");
define("Utilidad",0.77);
define("Impuesto",1.16);
define("ClenTable",10);

define ("SedeFTN","Farmacia Tierra Negra");
define ("SedeFLL","Farmacia La Lago");

define ("SedeFAU","Farmacia Avenida Universidad");
define ("SedeFAUFTN","Farmacia Tierra Negra (OFF-LINE)");
define ("SedeFAUFLL","Farmacia La Lago (OFF-LINE)");

define ("SedeDBs","Servidor de Desarrollo Sergio");
define ("SedeDBm","Servidor de Desarrollo Manuel");
/*
	Cadena de conexion con la base de datos de CPharma
*/
 define ("serverCP","localhost:3306");	
 define ("userCP","root");
 define ("passCP","");
 define ("nameCP","cpharma");
/*
	Cadena de conexion con la base de datos de Smartpharma FTN (ON LINE)
*/
define ("serverFTN" , "10.100.0.2\SMARTPHARMA,1450");
define ("userFTN" , "sa");
define ("passFTN" , "Soporte123");
define ("nameFTN" , "SMART");
/*
	Cadena de conexion con la base de datos de Smartpharma FLL (ON LINE)
*/
define ("serverFLL" , "192.168.7.34\SMARTPHARMA");
define ("userFLL" , "sa");
define ("passFLL" , "Soporte123");
define ("nameFLL" , "smartlalago");

 /*
	Cadena de conexion con la base de datos del servidor de Desarrollo Manuel.
*/
 define ("serverDBm" , "DESKTOP-AGKHTEB");
 define ("userDBm" , "sa");
 define ("passDBm" , "soporte123");
 define ("nameDBm" , "SMART");


/***************************** Servidor de Desarrollo Sergio ********************************/
/*
	Cadenas de conexion Servidor de Desarrollo Sergio
	BaseDatosLocal: FTN
	Estatus: OFF LINE - ON LINE
*/
 define ("serverDBs" , "SERGIO-PC");
 define ("userDBs" , "sa");
 define ("passDBs" , "soporte123");
 define ("nameDBs" , "SMART");



/***************************** Smartpharma FAU ********************************/
/*
	Cadenas de conexion Servidor Smartpharma FAU
	BaseDatosLocal: Smartpharma FAU
	Estatus: OFF LINE - ON LINE
*/
/*
	Cadena de conexion Smartpharma FAU (ON LINE)
*/
define ("serverFAU" , "10.100.0.34\SMARTPHARMA");
define ("userFAU" , "sa");
define ("passFAU" , "Soporte123");
define ("nameFAU" , "smartfau");
/*
	Cadena de conexion Smartpharma FAU-FTN (OFF LINE)
*/
define ("serverFAUFTN" , "SERGIO-PC");
define ("userFAUFTN" , "sa");
define ("passFAUFTN" , "soporte123");
define ("nameFAUFTN" , "cpharmafau");
/*
	Cadena de conexion Smartpharma FAU-FLL (OFF LINE)
*/
define ("serverFAUFLL" , "SERGIO-PC");
define ("userFAUFLL" , "sa");
define ("passFAUFLL" , "soporte123");
define ("nameFAUFLL" , "cpharmafll");

?>