<?php

/**
 * 	【PHPUnit】お問い合わせテスト
 *
 * 	@version	1.0
 */
require_once("./include.php");

require('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;

class clsContactTest extends TestCase {

	private $_aryPostData;
	private $_Object;

	protected function setUp() {
	}

	public function testNotEmpty() {
		$this->assertEquals(true, true);
	}

}

?>