<?php

/**
 * 共通エラー画面処理
 * 
 * @version	1.0
 */
require_once("../../include.php");
require_once(DIR_MDL . "/clsCmnError.php");
require_once(DIR_CTR . "/ctlCmnError.php");

//Ziggeoコントローラ呼出し
$objCtlCmnError = new ctlCmnError();
$objCtlCmnError->process();

//オブジェクト開放
unset( $objCtlCmnError );
?>