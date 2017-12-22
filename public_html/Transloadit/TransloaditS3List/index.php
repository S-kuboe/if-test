<?php

/**
 * Transloaditアドオンを使用したサンプル
 * 　S3に格納した動画をhtml5のvideoタグで表示
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsTransloadit.php");
require_once(DIR_CTR . "/ctlTransloadit.php");

//Transloaditコントローラ呼出し
$objCtlTransloadit = new ctlTransloadit();
$objCtlTransloadit->processTransloaditS3List();

//オブジェクト開放
unset( $objCtlTransloadit );
?>