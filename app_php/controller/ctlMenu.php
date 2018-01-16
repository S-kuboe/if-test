<?php

/**
 * 	【コントローラ】メニュー画面処理
 *
 * 	@version	1.0
 */
class ctlMenu extends ctlApp {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * 画面処理分岐
	 * 　動画アップロード
	 */
	function process() {
		//オブジェト作成
		$objClsMenu	 = new clsMenu();
		$aryPostData = $objClsMenu->pullConvertData();

		require_once( './dspMenu.php' );
		
		//オブジェクト解放
		unset($objClsMenu);
		
	}

}

?>