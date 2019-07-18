<?php
/**********************************************************************************/
/***************************** CONSTANTES GLOBALES *******************************/
define("SigDolar","$");
define("SigVe","Bs.S");
define("Utilidad",0.77);
define("Impuesto",1.16);
/**********************************************************************************/
/************************* NOMBRES SEDES ON-LINE *********************************/
define ("SedeFTN","Farmacia Tierra Negra");
define ("SedeFLL","Farmacia La Lago");
define ("SedeFAU","Farmacia Avenida Universidad");
define ("SedeDBs","Servidor de Desarrollo Sergio");
define ("SedeDBm","Servidor de Desarrollo Manuel");
/**********************************************************************************/
/************************ NOMBRES SEDES OFF-LINE *********************************/
define ("SedeFTNOFF","Farmacia Tierra Negra (OFF-LINE)");
define ("SedeFLLOFF","Farmacia La Lago (OFF-LINE)");
define ("SedeFAUOFF","Farmacia Avenida Universidad (OFF-LINE)");
/**********************************************************************************/
/********* CADENA DE CONEXION CON LA BASE DE DATOS LOCAL DEL CPHARMA *************/
 define ("serverCP","localhost:3306");	
 define ("userCP","root");
 define ("passCP","");
 define ("nameCP","cpharma");
/**********************************************************************************/
/************************* CONEXION ON LINE FTN **********************************/
define ("serverFTN" , "10.100.0.2\SMARTPHARMA,1450");
define ("userFTN" , "sa");
define ("passFTN" , "Soporte123");
define ("nameFTN" , "SMART");
/**********************************************************************************/
/************************* CONEXION ON LINE FLL **********************************/
define ("serverFLL" , "192.168.7.34\SMARTPHARMA");
define ("userFLL" , "sa");
define ("passFLL" , "Soporte123");
define ("nameFLL" , "smartlalago");
/**********************************************************************************/
/************************* CONEXION ON LINE FAU **********************************/
define ("serverFAU" , "10.100.0.34\SMARTPHARMA");
define ("userFAU" , "sa");
define ("passFAU" , "Soporte123");
define ("nameFAU" , "smartfau");
/**********************************************************************************/
/********************* CONEXION SERVER DESARROLLO DE MANUEL **********************/
 define ("serverDBm" , "DESKTOP-AGKHTEB");
 define ("userDBm" , "sa");
 define ("passDBm" , "soporte123");
 define ("nameDBm" , "SMART");
/**********************************************************************************/
/********************* CONEXION SERVER DESARROLLO DE SERGIO **********************/
 define ("serverDBs" , "SERGIO-PC");
 define ("userDBs" , "sa");
 define ("passDBs" , "soporte123");
 define ("nameDBs" , "SMART");
 define ("nameDBm" , "SMART");
/**********************************************************************************/


/***************************** Smartpharma FAU ********************************/
/*
	Cadenas de conexion Servidor Smartpharma FAU
	BaseDatosLocal: Smartpharma FAU
	Estatus: OFF LINE - ON LINE
*/
/*
	Cadena de conexion Smartpharma FAU (ON LINE)

define ("serverFAU" , "10.100.0.34\SMARTPHARMA");
define ("userFAU" , "sa");
define ("passFAU" , "Soporte123");
define ("nameFAU" , "smartfau");

	Cadena de conexion Smartpharma FAU-FTN (OFF LINE)

define ("serverFAUFTN" , "SERGIO-PC");
define ("userFAUFTN" , "sa");
define ("passFAUFTN" , "soporte123");
define ("nameFAUFTN" , "cpharmafau");

	Cadena de conexion Smartpharma FAU-FLL (OFF LINE)

define ("serverFAUFLL" , "SERGIO-PC");
define ("userFAUFLL" , "sa");
define ("passFAUFLL" , "soporte123");
define ("nameFAUFLL" , "cpharmafll");
*/

?>