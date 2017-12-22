<?php

/**
 * 	【コントローラ】共通エラー画面処理
 *
 * 	@version	1.0
 */
class ctlCmnError {

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
		
		$strErrorMsg = "アクセスしていただいたページは存在しません。" . PHP_EOL ."お手数ですが、アクセスされたURLを今一度ご確認お願いします。。";
		
		if(f::filterArray( clsDefinition::SESSION_ERROR_MSG, $_SESSION ) !== ""){
			$strErrorMsg = f::filterArray( clsDefinition::SESSION_ERROR_MSG, $_SESSION );
			unset($_SESSION[clsDefinition::SESSION_ERROR_MSG]);
		}
		
		require_once( './dspErrorPage.php' );
	}

}

?>