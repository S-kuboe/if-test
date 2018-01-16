<?php

//暗号化処理
require_once DIR_CMN . "/clsCryptApiVer1.php";

//Herokuエラー収集アドオン
require_once DIR_CMN . "/clsAirbrakeApiVer1.php";

/**
 * 【モデル】共通処理
 *
 * @author		kuboe 2018/01/16
 * @version	1.0
 */
class clsApp {

	//オブジェクト
	public $_objCrypt;
	public $_objAirbrake;

	/**
	 * コンストラクタ
	 */
	public function __construct() {	
	}

}

?>