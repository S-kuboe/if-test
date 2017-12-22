<?php

/**
 * Transloaditアドオンを使用したサンプル
 * 　動画ファイルアップロード
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsTransloadit.php");
require_once(DIR_CTR . "/ctlTransloadit.php");

//Transloaditコントローラ呼出し
$objCtlTransloadit = new ctlTransloadit();
$objCtlTransloadit->processTransloadit();

//オブジェクト開放
unset( $objCtlTransloadit );
?>