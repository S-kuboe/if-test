<?php
session_start();

/**
 * 	定義クラス
 *
 * 	項目の定義をおこなうクラス
 *
 * 	@author		kuboe 2015/08/27
 * 	@version		1.0
 */

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

	//■共通
	//メール送信元
	const MAIL_FROM = "support@inform.co.jp";
	
	//送信者名
	//送信者名
	const MAIL_FROM_NAME = "インフォームシステム";
	
	//■お問い合わせ
	//件名
	const CONTACT_MAIL_TITLE = "お問合せ";
	
	//お問い合わせ件名
	public static $CONTACT_SUBJECT	 = array(
		0 => "お選びください",
		1 => "ホームページ制作について",
		2 => "システム開発について",
		3 => "ネットワーク構築について",
		4 => "セレクトサービスについて",
		5 => "その他"
	);	
	
	//■ホスト情報・紹介
	//送信者名
	const HOSTLOOK_MAIL_TITLE = " 様のホスト情報";
	
	//お問い合わせ件名
	public static $HOSTLOOK_IPINFO	 = array(
		0 => "固定IP",
		1 => "動的IP",
		2 => "不明"
	);		
	
	//■SendGrid
	//ローカル用
	const LOCAL_CONTACT_SEND_GRID_API_KEY = "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";
	
	//メール送信先
	//開発環境
	public static $DEV_CONTACT_TO_MAIL	 = array(
		'nambe@inform.co.jp',
		'ryamamoto@inform.co.jp',
		'kuboe@inform-us.com',
		'takada@inform.co.jp'
	);
	//ステージング
	public static $STG_CONTACT_TO_MAIL	 = array(
		'support@inform.co.jp'
	);
	//本番
	public static $PRD_CONTACT_TO_MAIL	 = array(
		'support@inform.co.jp'
	);
	
	//■セッション名
	const SESSION_ERROR_JSON					 = "s-error-json";
	const SESSION_TOKEN							 = "s-token";
	const SESSION_CONTACT						 = "s-contact";
	

}

