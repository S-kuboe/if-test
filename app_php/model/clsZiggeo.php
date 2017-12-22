<?php

/**
 * 	【モデル】Ziggeoアドオンを使用したサンプル
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