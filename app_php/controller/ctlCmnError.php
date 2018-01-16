<?php

/**
 * 	【コントローラ】共通エラー画面処理
 *
 * 	@version	1.0
 */
class ctlCmnError extends ctlApp {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		parent::__construct();
	}


	/**
	 * 画面処理分岐
	 * 　共通エラー
	 */
	function process() {

		//オブジェト作成
		$objClsCmnError	 = new clsCmnError();
		$aryPostData	 = $objClsCmnError->pullConvertData();
		
        $strErrorMsg = "アクセスしていただいたページは存在しません。" . PHP_EOL ."お手数ですが、アクセスされたURLを今一度ご確認お願いします。。";

		if ( f::filterArray( clsDefinition::SESSION_ERROR_MSG, $_SESSION ) !== "" ) {
			$strErrorMsg = f::filterArray( clsDefinition::SESSION_ERROR_MSG, $_SESSION );
			unset( $_SESSION[clsDefinition::SESSION_ERROR_MSG] );
		} else {
			//アクセス時
			clsCommonFunction::putErrorLog( "Inform PHP msg:　【アクセス】許可しないIPアドレスでのアクセスがありました。" );
		}

		require_once( './dspErrorPage.php' );
		
		//オブジェクト解放
		unset($objClsCmnError);
		
	}

}

?>