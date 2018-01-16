<?php

/**
 * 共通関数クラス
 * 　共通関数の定義をおこなうクラス
 *
 * @version 1.0
 */
class clsCommonFunction extends clsApp {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * セレクトボックスを選択状態にする為の関数
	 *
	 * @param int $intPostDate ：送られてきたデータ
	 * @param int $intChkDate  ：選択状態にしたいデータ
	 * @return 一致した場合：選択状態にする
	 */
	public static function chkSelectedDate( int $intPostDate, int $intChkDate ) {
		if ( $intPostDate == $intChkDate ) {
			return "selected=selected";
		}
	}

	/**
	 * チェックボックスを選択状態にする為の関数
	 *
	 * @param int $intPostDate ：送られてきたデータ
	 * @param int $intChkDate  ：選択状態にしたいデータ
	 * @return 一致した場合：選択状態にする
	 */
	public static function chkCheckedDate( int $intPostDate, int $intChkDate ) {
		if ( $intPostDate == $intChkDate ) {
			return "checked=checked";
		}
	}

	/**
	 * ユーザーIP取得
	 * 
	 * @return string ユーザーIP
	 */
	public static function getUserIP() {
		if ( SERVER_MODE === false ) {
			$strIP = getenv( "REMOTE_ADDR" );
		} else {
			$strIP = getenv( "HTTP_X_FORWARDED_FOR" );
		}

		return $strIP;
	}

	/**
	 * ユーザーエージェント取得
	 * 
	 * @return string ユーザーエージェント
	 */
	public static function getUserAgent() {
		return getenv( "HTTP_USER_AGENT" );
	}

	/**
	 * ホスト名取得
	 * 
	 * @return string ホスト名
	 */
	public static function getUserHost() {
		if ( SERVER_MODE === false ) {
			$strIP = getenv( "REMOTE_ADDR" );
		} else {
			$ipAddresses = explode( ',', getenv( "HTTP_X_FORWARDED_FOR" ) );
			$strIP		 = trim( end( $ipAddresses ) );
		}

		return gethostbyaddr( $strIP );
	}

	/**
	 * サーバー名取得
	 * 
	 * @return string サーバー名
	 */
	public static function getServerName() {
		return getenv( "SERVER_NAME" );
	}

	/**
	 * サーバープロトコル取得
	 * 
	 * @return string サーバープロトコル
	 */
	public static function getServerProtocol() {
		return getenv( "SERVER_PROTOCOL" );
	}

	/**
	 * サーバーポート取得
	 * 
	 * @return string サーバーポート
	 */
	public static function getServerPort() {
		return getenv( "SERVER_PORT" );
	}

	/**
	 * サーバのCGIバージョン取得
	 * 
	 * @return string サーバのCGIバージョン
	 */
	public static function getGatewayInterface() {
		return getenv( "GATEWAY_INTERFACE" );
	}

	/**
	 * リファラー取得
	 * 
	 * @return string リファラー
	 */
	public static function getHttpReferer() {
		return getenv( "HTTP_REFERER" );
	}

	/**
	 * 現在時刻の文字列を取得
	 *
	 * @return string 'YYYY/MM/DD HH/MI/SS'
	 */
	public static function getCurrentDate() {
		return date( 'Y/m/d H:i:s' );
	}

	/**
	 * 現在時刻の文字列を取得
	 *
	 * @return string 'YYYY/MM/DD HH/MI/SS'
	 */
	public static function getCurrentDateYMD() {
		return date( 'Y年m月d日' );
	}

	/**
	 * 現在時刻の文字列を取得
	 *
	 * @return string 'YYYY/MM/DD HH/MI/SS'
	 */
	public static function getCurrentDateHIS() {
		return date( 'H:i:s' );
	}

	/**
	 * 日付から曜日を日本語で割り出す関数
	 * 
	 * @return string 曜日
	 */
	public static function getTagWeekdayJP( $strDate ) {
		$aryWeekDay = array('日', '月', '火', '水', '木', '金', '土');
		return $aryWeekDay[date( 'w', strtotime( $strDate ) )];
	}

	/**
	 * トークン取得
	 * 
	 * @return string トークン
	 */
	public static function getToken() {
		return md5( uniqid( rand(), true ) );
	}

	/**
	 * IPチェック
	 */
	public static function blnWhiteList() {
		$strUserIP = self::getUserIP();

		//ローカル環境であればTRUE
		if ( getenv( "ENV_MODE_VARS" ) === false ) {
			return true;
		}
		
		//環境変数が登録されていなければTRUE
		if ( getenv( 'ENV_WHITE_IP' ) === false ) {
			return true;
		}

		//中身が空であればTRUE
		if ( getenv( 'ENV_WHITE_IP' ) === "" ) {
			return true;
		}

		//配列に分解
		$aryWhiteIP = explode( ",", getenv( 'ENV_WHITE_IP' ) );

		//許可IPであればTRUE
		if ( in_array( $strUserIP, $aryWhiteIP ) ) {
			return true;
		}

		return false;
	}

	/**
	 * エラーログ出力
	 * 
	 * @param string $strMsg 単品メッセージ
	 * @param array $aryMsg 配列
	 * @return boolean
	 */
	public static function putErrorLog( string $strMsg, array $aryMsg = [] ) {

		//アクセス者情報の取得
		$strMsg	 .= " ｜ ";
		//アクセスしたユーザーのIP
		$strMsg	 .= self::getUserIP() . " ｜ ";
		//アクセスしたユーザーのユーザーエージェント
		$strMsg	 .= self::getUserAgent() . " ｜ ";
		//アクセスしたユーザーのホスト
		$strMsg	 .= self::getUserHost() . " ｜ ";

		//配列データがあればvar_dumpにて格納
		if ( !empty( $aryMsg ) ) {
			ob_start();
			var_dump( $aryMsg );
			$strVarDump = ob_get_contents();
			ob_end_clean();

			$strMsg .= $strVarDump . " ｜ ";
		}

		try {
			error_log( $strMsg );
		} catch ( Exception $ex ) {
			clsAirbrakeApiVer1::setNotify( $ex );
			return false;
		}

		return true;
	}

}
