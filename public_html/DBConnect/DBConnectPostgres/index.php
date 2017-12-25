<?php

/**
 * Heroku Postgresアドオンを使用したサンプル
 * 　SHOW・CREATE・INSERT・SELECT・DELETE
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsDBConnect.php");
require_once(DIR_CTR . "/ctlDBConnect.php");

//Heroku Postgresコントローラ呼出し
$objCtlDBConnectPostgres = new ctlDBConnect();
$objCtlDBConnectPostgres->processDBConnectPostgres();

//オブジェクト開放
unset( $objCtlDBConnectPostgres );
?>