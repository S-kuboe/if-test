<?php

/**
 * 	【モデル】共通エラー処理
 *
 * 	@version	1.0
 */
class clsCmnError extends clsApp{

	private $_aryPostData;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		parent::__construct();
		
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