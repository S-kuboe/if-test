<?php

/**
 * 	お問い合わせ
 * 
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsZiggeo.php");
require_once(DIR_CTR . "/ctlZiggeo.php");

//お問い合わせコントローラ呼出し
$objCtlZiggeo = new ctlZiggeo();
$objCtlZiggeo->processForm();

//クラス開放
unset( $objCtlZiggeo );
?>