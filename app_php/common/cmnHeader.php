<?php
/**
 * 	共通ヘッダー
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
<meta name="keywords" content="FLASH作成,FLASH制作,ホームページ作成,ホームページ制作,WEB作成,WEB制作,サイト作成,サイト制作,WEBデザイン,ホームページデザイン,SEO,SEM,ストリーミング,アクセス解析,サーバ管理,ネットワーク構築,アプリケーション開発,大阪市,淀川区,インフォームシステム株式会社" />
<meta name="description" content="WEBサイトの企画コンサルティングから制作、FLASH作成、専用サーバー構築・WEBサイト運用まですべてのWEBソリューションをご提供します。ITの活用を真剣にお考えのお客様お待ちしております。" />
<link rel="shortcut icon" href="../images/favicon.ico">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<!--[if lte IE 8]><script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
<link rel="stylesheet" href="../assets/css/main.css" />
<!--[if lte IE 8]><link rel="stylesheet" href="../assets/css/ie8.css" /><![endif]-->
<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
<link rel="stylesheet" href="../css/form.css">

<script type="text/javascript" src="../js/jQuery/jquery-2.2.4.js"></script>
<link rel="stylesheet" href="../js/Datepicker/css/jquery-ui.css">
<script type="text/javascript" src="../js/Datepicker/js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/Datepicker/js/datepicker.js"></script>
<script type="text/javascript" src="../js/Datepicker/js/datepicker-ja.js"></script>
<script type="text/javascript" src="../js/ModalDialog/modalDialog.js?var={$strJavaNow}"></script>
<script type="text/javascript" src="../js/Common/value_checker.js?var={$strJavaNow}"></script>
<script type="text/javascript" src="../js/Common/ISCommon.js?var={$strJavaNow}"></script>
<script type="text/javascript" src="../urchin.js"></script>
<script type="text/javascript">
	urchinTracker();
</script>
<!--Google Analytics code 20120620 -->
<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-25759311-1']);
	_gaq.push(['_trackPageview']);

	(function () {
		var ga = document.createElement('script');
		ga.type = 'text/javascript';
		ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ga, s);
	})();

</script>
<script src="../assets/js/jquery.dropotron.min.js"></script>
<script src="../assets/js/jquery.scrolly.min.js"></script>
<script src="../assets/js/jquery.scrollgress.min.js"></script>
<script src="../assets/js/skel.min.js"></script>
<script src="../assets/js/util.js"></script>
<!--[if lte IE 8]><script src="../assets/js/ie/respond.min.js"></script><![endif]-->
<script src="../assets/js/main.js"></script>
EOT;

echo $strEcho;
