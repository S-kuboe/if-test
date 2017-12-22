<?php

/**
 * 定義クラス
 * 　項目の定義をおこなうクラス
 *
 * @version	1.0
 */
define( 'SERVER_MODE', getenv( "ENV_MODE_VARS" ) );
if ( SERVER_MODE !== false ) {
	define( 'RUN_MODE', TRUE );
	define( "DIR_APP", "/app/" );
} else {
	define( 'RUN_MODE', FALSE );
	$strPgRoot	 = filter_input( INPUT_SERVER, "DOCUMENT_ROOT" );
	$aryPgRoot	 = explode( "/", $strPgRoot );
	$strPgRoot	 = str_replace( $aryPgRoot[count( $aryPgRoot ) - 1], "", $strPgRoot );

	define( "DIR_APP", $strPgRoot );
}

if ( RUN_MODE ) {
	define( 'STR_HTTP', "https://" );
} else {
	if ( filter_input( INPUT_SERVER, "HTTPS" ) !== null && filter_input( INPUT_SERVER, "HTTPS" ) == 'on' ) {
		define( 'STR_HTTP', "https://" );
	} else {
		define( 'STR_HTTP', "http://" );
	}
}

define( 'DIR_DMT', filter_input( INPUT_SERVER, "DOCUMENT_ROOT" ) );
define( 'DIR_CMN', DIR_APP . "app_php/common" );
define( 'DIR_MDL', DIR_APP . "app_php/model" );
define( 'DIR_CTR', DIR_APP . "app_php/controller" );

class clsDefinition {

	//■セッション名
	const SESSION_ERROR_MSG	 = "s-error-msg";
	const SESSION_ERROR_JSON	 = "s-error-json";
	const SESSION_TOKEN		 = "s-token";
	const SESSION_CONTACT		 = "s-contact";

	//■IPチェック許可ディレクトリ
	public static $IGNORE_DIR = [
		"/CmnError/"
	];

}
