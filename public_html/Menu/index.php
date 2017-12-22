<?php

/**
 * メニュー画面処理
 * 
 * @version	1.0
 */
require_once("../../include.php");
require_once(DIR_MDL . "/clsMenu.php");
require_once(DIR_CTR . "/ctlMenu.php");

//Ziggeoコントローラ呼出し
$objCtlMenu = new ctlMenu();
$objCtlMenu->process();

//オブジェクト開放
unset( $objCtlMenu );
?>