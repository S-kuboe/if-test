<?php
/**
 * 	共通ヘッダー（照会フォーム用）
 *
 * 	@author		kuboe 2017/12/06
 * 	@version		1.0
 */

require_once("../../include.php");

//キャッシュ回避ｓ
$strJavaNow = date('YmdHis');

$strEcho = <<<EOT
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-store">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="-1">
<meta charset="utf-8" />

<link rel="stylesheet" href="../css/form.css">

<script type="text/javascript" src="../js/jQuery/jquery-2.2.4.js"></script>
<link rel="stylesheet" href="../js/Datepicker/css/jquery-ui.css">
<script type="text/javascript" src="../js/Datepicker/js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/Datepicker/js/datepicker.js"></script>
<script type="text/javascript" src="../js/Datepicker/js/datepicker-ja.js"></script>
<script type="text/javascript" src="../js/ModalDialog/modalDialog.js?var={$strJavaNow}"></script>
<script type="text/javascript" src="../js/Common/value_checker.js?var={$strJavaNow}"></script>
<script type="text/javascript" src="../js/Common/ISCommon.js?var={$strJavaNow}"></script>
EOT;

echo $strEcho;
