<?php

/**
 * 	【コントローラ】PHP INFO画面処理
 *
 * 	@version	1.0
 */
class ctlPhpInfo extends preApp {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 画面処理分岐
	 * 　PHP INFO
	 */
	function process() {
		//オブジェト作成
		$objClsPhpInfo	 = new clsPhpInfo();
		$aryPostData	 = $objClsPhpInfo->pullConvertData();

		require_once( './dspPhpInfo.php' );
		
		//オブジェクト解放
		unset($objClsPhpInfo);
		
	}

}

?>