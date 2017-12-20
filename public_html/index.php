<!DOCTYPE html>
<html>
    <head>
        <title>サンプル</title>
		<script type="text/javascript">
			$(document).ready(function () {
				Consol.log("Inform JS error:　テストJAVA");
			});
		</script>
	</head>
    <body>	
		■Ziggeo<br />
		　<a href="./Ziggeo/ZiggeoForm/">Ziggeo動画投稿フォーム</a><br />
		　<a href="./Ziggeo/ZiggeoList/">Ziggeo投稿動画閲覧</a><br />
		■Transloadit<br />
		　<a href="./Transloadit/Transloadit/">Transloadit動画投稿</a><br />
		　<a href="./Transloadit/TransloaditS3/">Transloadit動画投稿(S3)</a><br />
		　<a href="./Transloadit/TransloaditS3List/">Transloadit動画閲覧(S3)</a><br />
		■データベース<br />
		　<a href="./DBConnectTest/DBConnectTest/">JAWSDB接続サンプル</a><br />
		<?php
		error_log( "Inform error:  テスト" );
		?>
    </body>
</html>
