<?php

/**
 * Ziggeoアドオンを使用したサンプル
 * 　動画ファイルアップロード
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsZiggeo.php");
require_once(DIR_CTR . "/ctlZiggeo.php");

//Ziggeoコントローラ呼出し
$objCtlZiggeo = new ctlZiggeo();
$objCtlZiggeo->processForm();

//オブジェクト開放
unset( $objCtlZiggeo );
?>