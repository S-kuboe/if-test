<?php

/**
 * Herokuで現在使われているIP取得
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsHerokuIP.php");
require_once(DIR_CTR . "/ctlHerokuIP.php");

//Herokuで現在使われているIP取得コントローラ呼出し
$objCtlHerokuIP = new ctlHerokuIP();
$objCtlHerokuIP->process();

//オブジェクト開放
unset( $objCtlHerokuIP );
?>