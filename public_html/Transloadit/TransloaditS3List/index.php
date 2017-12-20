<?php

/**
 * 	お問い合わせ
 * 
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsTransloadit.php");
require_once(DIR_CTR . "/ctlTransloadit.php");
require_once(DIR_VAL . "/clsTransloaditChecker.php");

//お問い合わせコントローラ呼出し
$objCtlTransloadit = new ctlTransloadit();
$objCtlTransloadit->processTransloaditS3List();

//クラス開放
unset( $objCtlTransloadit );
?>