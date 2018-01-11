<?php

/**
 * 定義クラス
 * 　項目の定義をおこなうクラス
 *
 * @version	1.0
 */
//動作環境設定
define( 'SERVER_MODE', getenv( "ENV_MODE_VARS" ) );

//SSLの設定
if ( SERVER_MODE !== false ) {
	define( 'STR_HTTP', "https://" );
} else {
	if ( filter_input( INPUT_SERVER, "HTTPS" ) !== null && filter_input( INPUT_SERVER, "HTTPS" ) == 'on' ) {
		define( 'STR_HTTP', "https://" );
	} else {
		define( 'STR_HTTP', "http://" );
	}
}

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
