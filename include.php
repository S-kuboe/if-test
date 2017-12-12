<?php
if ( getenv("ENV_MODE_VARS") !== false ) {
	$strPgRoot = "/app/app_php/common";
} else {
	$strPgRoot = filter_input(INPUT_SERVER, "DOCUMENT_ROOT");
	$aryPgRoot = explode("/", $strPgRoot);
	$strPgRoot = str_replace($aryPgRoot[count($aryPgRoot) - 1], "", $strPgRoot)  . "app_php/common";
}

//共通定義
require_once $strPgRoot . "/clsDefinition.php";

//HTML表示フォーマット用の特殊クラス
require_once DIR_CMN . "/f.php";

//共通メソッド
require_once DIR_CMN . "/clsCommonFunction.php";
