<?php

//暗号化処理
require_once DIR_CMN . "/clsCryptApiVer1.php";

//Herokuエラー収集アドオン
require_once DIR_CMN . "/clsAirbrakeApiVer1.php";

/**
 * 【コントローラ】共通画面処理
 *
 * @author		kuboe 2018/01/16
 * @version	1.0
 */
class ctlApp {

	//オブジェクト
	public $_objCrypt;
	public $_objAirbrake;
	
	//■IPチェック許可ディレクトリ
	public static $IGNORE_DIR = [
		"/CmnError/"
	];
	/**
	 * コンストラクタ
	 */
	function __construct() {

		//共通処理実行
		$this->setApp();
	}

	/**
	 * 画面処理分岐
	 */
	function setApp() {

		//暗号化処理
		$this->_objCrypt = new clsCryptApiVer1();

		//Herokuエラー収集アドオン
		$this->_objAirbrake = new clsAirbrakeApiVer1();

		//許可ディレクトリ
		$strDirName			 = dirname( filter_input( INPUT_SERVER, "SCRIPT_NAME" ) ) . '/';
		$blnIgnoreLoinCheck	 = false;
		foreach ( $this->IGNORE_DIR as $strIgnoreDir ) {
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
	}

}

?>