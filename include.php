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
			putenv( $strRep2 );
		}
	}
}

if ( getenv( "ENV_MODE_VARS" ) !== false ) {
	$strPgRoot = "/app/app_php/common";
} else {
	$strPgRoot	 = filter_input( INPUT_SERVER, "DOCUMENT_ROOT" );
	$aryPgRoot	 = explode( "/", $strPgRoot );
	$strPgRoot	 = str_replace( $aryPgRoot[count( $aryPgRoot ) - 1], "", $strPgRoot ) . "app_php/common";
}

//共通定義
require_once $strPgRoot . "/clsDefinition.php";

//HTML表示フォーマット用の特殊クラス
require_once DIR_CMN . "/f.php";

//共通メソッド
require_once DIR_CMN . "/clsCommonFunction.php";

//共通処理
require_once DIR_CMN . "/preFunction.php";

