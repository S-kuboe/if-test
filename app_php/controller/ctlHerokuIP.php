<?php

/**
 * 	【コントローラ】Herokuで現在使われているIP取得画面処理
 *
 * 	@version	1.0
 */
class ctlHerokuIP {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		
	}

	/**
	 * 画面処理分岐
	 * 　動画アップロード
	 */
	function process() {
		//オブジェト作成
		$objClsHerokuIP	 = new clsHerokuIP();
		$aryPostData	 = $objClsHerokuIP->pullConvertData();

		require_once( './dspHerokuIP.php' );
		
		//オブジェクト解放
		unset($objClsHerokuIP);
		
	}

}

?>