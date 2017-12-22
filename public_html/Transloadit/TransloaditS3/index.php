<?php

/**
 * Transloaditアドオンを使用したサンプル
 * 　動画ファイルアップロードを行いS3に格納
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsTransloadit.php");
require_once(DIR_CTR . "/ctlTransloadit.php");

//Transloaditコントローラ呼出し
$objCtlTransloadit = new ctlTransloadit();
$objCtlTransloadit->processTransloaditS3();

//オブジェクト開放
unset( $objCtlTransloadit );
?>