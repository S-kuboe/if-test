<?php

/**
 * 全画面共通前処理
 * 　全画面共通の前処理を記述
 *
 * @version	1.0
 */
//セッションの開始
session_start();

//ヘッダー
header( 'Expires:-1' );
header( 'Cache-Control:' );
header( 'Pragma:' );

//タイムゾーン設定
date_default_timezone_set( 'Asia/Tokyo' );

//Herokuエラー収集アドオン
$objAirbrake = new clsAirbrakeApiVer1();

//許可ディレクトリ
$strDirName			 = dirname( filter_input( INPUT_SERVER, "SCRIPT_NAME" ) ) . '/';
$blnIgnoreLoinCheck	 = false;
foreach ( clsDefinition::$IGNORE_DIR as $strIgnoreDir ) {
	if ( strpos( $strDirName, $strIgnoreDir ) !== false ) {
		$blnIgnoreLoinCheck = true;
		break;
	}
}

//許可IPチェック
if ( !clsCommonFunction::blnWhiteList() && !$blnIgnoreLoinCheck ) {
	header( 'Location: ' . STR_HTTP . f::filterServer( "HTTP_HOST" ) . "/CmnError/" );
	exit();
}