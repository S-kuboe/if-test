<?php

/**
 * 	【モデル】Herokuで現在使われているIP取得処理
 *
 * 	@version	1.0
 */
class clsHerokuIP {

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