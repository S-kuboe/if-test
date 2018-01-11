<?php

//envファイルから一時的に環境変数を設定
if ( file_exists( __DIR__ . "/.env") === true ) {
	$aryEnv = file( __DIR__ . "/.env" );

	foreach ( $aryEnv AS $strEnv ) {
		//シングル削除
		$strRep1 = str_replace( "'", "", $strEnv );
		//改行削除
		$strRep2 = preg_replace( '/\n|\r|\r\n/', '', $strRep1 );
		if ( $strRep2 !== "" && strpos($strRep2, "ENV_MODE_VARS") === false) {
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

//共通定義
require_once DIR_CMN . "/clsDefinition.php";

//Herokuエラー収集アドオン
require_once DIR_CMN . "/clsAirbrakeApiVer1.php";

//HTML表示フォーマット用の特殊クラス
require_once DIR_CMN . "/f.php";

//共通メソッド
require_once DIR_CMN . "/clsCommonFunction.php";

//共通処理
require_once DIR_CMN . "/preFunction.php";

