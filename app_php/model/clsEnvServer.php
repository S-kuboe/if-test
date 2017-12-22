<?php

/**
 * 	【モデル】環境変数またサーバー情報処理
 *
 * 	@version	1.0
 */
class clsEnvServer {

	private $_aryPostData;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		$this->_aryPostData = [];
	}

	/**
	 * POSTデータ整頓
	 */
	public function pullConvertData() {

		return $this->_aryPostData;
	}

}

?>