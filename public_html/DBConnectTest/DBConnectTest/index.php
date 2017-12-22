<?php

/**
 * Jaswdbアドオンを使用したサンプル
 * 　SHOW・CREATE・INSERT・SELECT・DELETE
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsDBConnectTest.php");
require_once(DIR_CTR . "/ctlDBConnectTest.php");

//Jaswdbせコントローラ呼出し
$objCtlDBConnectTest = new ctlDBConnectTest();
$objCtlDBConnectTest->processDBConnectTest();

//オブジェクト開放
unset( $objCtlDBConnectTest );
?>