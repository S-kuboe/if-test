<?php

/**
 * 	【コントローラ】環境変数またサーバー情報画面処理
 *
 * 	@version	1.0
 */
class ctlEnvServer extends ctlApp {

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
		$objClsEnvServer = new clsEnvServer();
		$aryPostData	 = $objClsEnvServer->pullConvertData();

		require_once( './dspEnvServer.php' );
		
		//オブジェクト解放
		unset($objClsEnvServer);
		
	}

}

?>