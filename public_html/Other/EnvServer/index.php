<?php

/**
 * 環境変数またサーバー情報の取得
 * 
 * @version	1.0
 */
require_once("../../../include.php");
require_once(DIR_MDL . "/clsEnvServer.php");
require_once(DIR_CTR . "/ctlEnvServer.php");

//環境変数またサーバー情報コントローラ呼出し
$objCtlEnvServer = new ctlEnvServer();
$objCtlEnvServer->process();

//オブジェクト開放
unset( $objCtlEnvServer );
?>