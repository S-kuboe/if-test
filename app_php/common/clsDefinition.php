<?php
session_start();

define( 'SERVER_MODE', getenv("ENV_MODE_VARS"));
if ( SERVER_MODE !== false ) {
	define( 'RUN_MODE', TRUE );
	define ("DIR_APP", "/app/");
} else {
	define( 'RUN_MODE', FALSE );
	$strPgRoot = filter_input(INPUT_SERVER, "DOCUMENT_ROOT");
	$aryPgRoot = explode("/", $strPgRoot);
	$strPgRoot = str_replace($aryPgRoot[count($aryPgRoot) - 1], "", $strPgRoot);

	define ("DIR_APP", $strPgRoot);
}

define( 'DIR_DMT', filter_input(INPUT_SERVER, "DOCUMENT_ROOT") );
define( 'DIR_CMN', DIR_APP . "app_php/common" );
define( 'DIR_MDL', DIR_APP . "app_php/model" );
define( 'DIR_CTR', DIR_APP . "app_php/controller");
define( 'DIR_VAL', DIR_APP . "app_php/validate");

class clsDefinition {

	//■セッション名
	const SESSION_ERROR_JSON					 = "s-error-json";
	const SESSION_TOKEN							 = "s-token";
	const SESSION_CONTACT						 = "s-contact";
	

}

