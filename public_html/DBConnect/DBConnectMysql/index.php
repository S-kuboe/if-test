<?php

/**
 * Jaswdbアドオンを使用したサンプル
 * 　SHOW・CREATE・INSERT・SELECT・DELETE
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsDBConnect.php");
require_once(DIR_CTR . "/ctlDBConnect.php");

//Jaswdbコントローラ呼出し
$objCtlDBConnectMysql = new ctlDBConnectMysql();
$objCtlDBConnectMysql->processDBConnectMysql();

//オブジェクト開放
unset( $objCtlDBConnectMysql );
?>