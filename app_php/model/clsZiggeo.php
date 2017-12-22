<?php

/**
 * 	【モデル】Ziggeoアドオンを使用したサンプル処理
 *
 * 	@version	1.0
 */
class clsZiggeo {

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