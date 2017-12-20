<?php

/**
 * 	お問い合わせ
 * 
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsDBConnectTest.php");
require_once(DIR_CTR . "/ctlDBConnectTest.php");

//お問い合わせコントローラ呼出し
$objCtlDBConnectTest = new ctlDBConnectTest();
$objCtlDBConnectTest->processDBConnectTest();

//クラス開放
unset( $objCtlDBConnectTest );
?>