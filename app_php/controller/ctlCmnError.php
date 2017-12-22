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
	 * 　共通エラー
	 */
	function process() {

		//オブジェト作成
		$objClsCmnError	 = new clsCmnError();
		$aryPostData	 = $objClsCmnError->pullConvertData();

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