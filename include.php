<?php


//セッションの開始
session_start();

//ヘッダー
header( 'Expires:-1' );
header( 'Cache-Control:' );
header( 'Pragma:' );

header('Content-Type: text/html; charset=UTF-8');

//タイムゾーン設定
date_default_timezone_set( 'Asia/Tokyo' );

//Windows判定
define( "IS_WIN", strpos(PHP_OS, "WIN") === 0 );

//envファイルから一時的に環境変数を設定
if ( file_exists( __DIR__ . "/.env" ) === true ) {
	$aryEnv = file( __DIR__ . "/.env" );

	foreach ( $aryEnv AS $strEnv ) {
		
		//シングル削除
		$strRep1 = str_replace( "'", "", $strEnv );
		//改行削除
		$strRep2 = preg_replace( '/\n|\r|\r\n/', '', $strRep1 );
		
		if(IS_WIN){
			$strRep2 = mb_convert_encoding($strRep2, "SJIS", "UTF-8");
		}
		
		if ( $strRep2 !== "" && strpos( $strRep2, "ENV_MODE_VARS" ) === false ) {
			if ( getenv( $strRep2 ) === false ) {
				putenv( $strRep2 );
			}
		}
	}
}

//ルートディレクトリの取得
define( "DIR_APP", str_replace( "\\", "/", __DIR__ ) );
//ドキュメントルートディレクトリ設定
define( 'DIR_DMT', filter_input( INPUT_SERVER, "DOCUMENT_ROOT" ) );
//共通ディレクトリ設定
define( 'DIR_CMN', DIR_APP . "/app_php/common" );
//モデルディレクトリ設定
define( 'DIR_MDL', DIR_APP . "/app_php/model" );
//コントローラディレクトリ設定
define( 'DIR_CTR', DIR_APP . "/app_php/controller" );
//バリデーションディレクトリ設定
define( 'DIR_VAL', DIR_APP . "/app_php/validate" );


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

//共通制御
require_once DIR_CMN . '/preApp.php';

//共通定義
require_once DIR_CMN . "/clsDefinition.php";

//HTML表示フォーマット用の特殊クラス
require_once DIR_CMN . "/f.php";

//共通メソッド
require_once DIR_CMN . "/clsCommonFunction.php";

