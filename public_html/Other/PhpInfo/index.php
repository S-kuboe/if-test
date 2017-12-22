<?php

/**
 * PHP INFO
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsPhpInfo.php");
require_once(DIR_CTR . "/ctlPhpInfo.php");

//PHP INFOコントローラ呼出し
$objCtlPhpInfo = new ctlPhpInfo();
$objCtlPhpInfo->process();

//オブジェクト開放
unset( $objCtlPhpInfo );
?>