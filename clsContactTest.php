<?php

require_once("./include.php");
require_once("./app_php/validate/clsContactChecker.php");

require('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class clsContactTest extends TestCase {

	private $_aryPostData;
	private $_Object;

	protected function setUp() {
		// テストするクラスのインスタンスを生成
		$this->_aryPostData = [
			'company' => "",
			'post' => "",
			'name' => "",
			'tel' => "",
			'mail_address' => "",
			'subject' => "",
			'msg' => ""		
		];
	}

	public function testNotEmpty() {
		// テストするクラスのインスタンスを生成
		$this->_Object = new clsContactChecker($this->_aryPostData);
		
		$this->_Object->execute();
		$this->assertEquals(true, $this->_Object->isError());
	}

}

?>