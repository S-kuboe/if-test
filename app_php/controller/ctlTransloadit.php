<?php

/**
 * 	【コントローラ】Transloaditアドオンを使用したサンプル
 *
 * 	@version	1.0
 */
require(DIR_APP . '/vendor/autoload.php');

use transloadit\Transloadit;

class ctlTransloadit {

	//クラス変数
	private $_strKey;
	private $_strSecret;
	private $_strHostName;
	private $_strUserName;
	private $_strPassword;
	private $_strDatabase;
	private $_strS3Bucket;
	private $_strS3Key;
	private $_strS3Secret;
	private $_strS3Region;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->_strS3Bucket	 = getenv( 'S3_BUCKET' );
		$this->_strS3Key	 = getenv( 'S3_ACCESS_KEY' );
		$this->_strS3Secret	 = getenv( 'S3_SECRET_KEY' );
		$this->_strS3Region	 = getenv( 'S3_REGION' );

		$this->_strKey		 = getenv( 'TRANSLOADIT_AUTH_KEY' );
		$this->_strSecret	 = getenv( 'TRANSLOADIT_SECRET_KEY' );

		$url				 = parse_url( getenv( "JAWSDB_URL" ) );
		$this->_strHostName	 = $url["host"];
		$this->_strUserName	 = $url["user"];
		$this->_strPassword	 = $url["pass"];
		$this->_strDatabase	 = ltrim( $url["path"], '/' );
	}

	/**
	 * 画面処理分岐
	 * 　動画アップロード
	 */
	function processTransloadit() {

		$objClsTransloadit	 = new clsTransloadit();
		$aryPostData		 = $objClsTransloadit->pullConvertData();

		$transloadit = new Transloadit( array(
			'key'	 => $this->_strKey,
			'secret' => $this->_strSecret
				) );


		$redirectUrl = sprintf(
				'http://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']
		);

		$blnDsp = false;

		if ( isset( $_POST, $_POST['submitted'] ) && $_POST['submitted'] === 'submitted' && isset( $_FILES ) ) {

			$tmp_arguments['file'] = $_FILES['files']['tmp_name'];

			$response = $transloadit->createAssembly( array(
				'files'	 => $tmp_arguments['file'],
				'params' => array(
					'steps'			 => array(
						"encode" => array(
							"robot"	 => "/video/encode",
							"use"	 => ":original",
							"preset" => "iphone"
						)
					),
					'redirect_url'	 => $redirectUrl
				),
					) );

			if ( $response ) {
				echo '<h1>Assembly status:</h1>';
				echo '<pre>';
				print_r( $response );
				echo '</pre>';

				//オブジェクト解放
				unset( $objClsTransloadit );

				exit();
			}

			$blnDsp = true;
		}

		require_once( './dspTransloadit.php' );

		//オブジェクト解放
		unset( $objClsTransloadit );
	}

	/**
	 * 画面処理分岐
	 * 　動画アップロードを行いS3に転送
	 */
	function processTransloaditS3() {

		$objClsTransloadit	 = new clsTransloadit();
		$aryPostData		 = $objClsTransloadit->pullConvertData();

		$transloadit = new Transloadit( array(
			'key'	 => $this->_strKey,
			'secret' => $this->_strSecret
				) );

		$redirectUrl = sprintf(
				'http://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']
		);

		$blnDsp = false;

		if ( isset( $_POST, $_POST['submitted'] ) && $_POST['submitted'] === 'submitted' && isset( $_FILES ) ) {

			$tmp_arguments['file'] = $_FILES['files']['tmp_name'];


			$response = $transloadit->createAssembly( [
				'files'	 => $tmp_arguments['file'],
				'params' => [
					'steps'			 => [
						'encode_video'	 => [
							'use'	 => ':original',
							'robot'	 => '/video/encode',
							'preset' => 'android'
						],
						'thumb'			 => [
							'use'		 => 'encode_video',
							'robot'		 => '/video/thumbs',
							'count'		 => 1,
							'offsets'	 => [1],
							'width'		 => 100,
							'height'	 => 100,
						],
						'export'		 => [
							'use'	 => ['encode_video', 'thumb'],
							'robot'	 => '/s3/store',
							'bucket' => $this->_strS3Bucket,
							'key'	 => $this->_strS3Key,
							'secret' => $this->_strS3Secret,
							'path'	 => 'heroku/${previous_step.name}/${file.id}.${file.ext}'
						],
					],
					'redirect_url'	 => $redirectUrl
				],
					] );

			if ( $response ) {
				$aryData	 = (array)$response;
				$resultUrl	 = $aryData["data"]["assembly_url"];

				try {
					$conn = new PDO( "mysql:host=$this->_strHostName;dbname=$this->_strDatabase", $this->_strUserName, $this->_strPassword );
					// set the PDO error mode to exception
					$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
					$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
					echo "Connected successfully<br />";
				} catch ( PDOException $ex ) {
					clsAirbrakeApiVer1::setPDOExceptionNotify( $ex );
					echo "Connection failed: " . $ex->getMessage() . "<br />";
				}

				$strDate	 = date( "%Y-%m-%d H:i:s" );
				$strWhereSql = <<< SQL
	INSERT INTO transloadit_tbl VALUES (null, '{$resultUrl}', '{$strDate}')
SQL;

				$prepare = $conn->prepare( $strWhereSql );
				$prepare->execute();

				echo '<h1>Assembly status:</h1>';
				echo '<pre>';
				var_dump( $aryData );
				echo '</pre>';

				//オブジェクト解放
				unset( $objClsTransloadit );

				exit();
			}

			$blnDsp = true;
		}

		require_once( './dspTransloaditS3.php' );

		//オブジェクト解放
		unset( $objClsTransloadit );
	}

	/**
	 * 画面処理分岐
	 * 　S3に転送した動画を取得して表示
	 */
	function processTransloaditS3List() {

		$objClsTransloadit	 = new clsTransloadit();
		$aryPostData		 = $objClsTransloadit->pullConvertData();

		try {
			$conn = new PDO( "mysql:host=$this->_strHostName;dbname=$this->_strDatabase", $this->_strUserName, $this->_strPassword );
			// set the PDO error mode to exception
			$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			echo "Connected successfully<br />";
		} catch ( PDOException $ex ) {
			clsAirbrakeApiVer1::setPDOExceptionNotify( $ex );
			echo "Connection failed: " . $ex->getMessage() . "<br />";
		}

		$strWhereSql = <<< SQL
	SELECT * FROM transloadit_tbl
SQL;
		$prepare	 = $conn->prepare( $strWhereSql );
		$prepare->execute();
		$result		 = $prepare->fetchAll( PDO::FETCH_ASSOC );

		echo $strHTML = <<<HTML
	<!DOCTYPE html>
	<html lang="ja">
	<head>
	<meta charset="UTF-8">
	<title>サイトのタイトル</title>
	</head>
	<body>
HTML;

		foreach ( $result AS $key => $val ) {
			if ( @file_get_contents( $val["upd_url"] ) !== false ) {
				$res = json_decode( file_get_contents( $val["upd_url"] ) );

				if ( $res->ok === 'ASSEMBLY_COMPLETED' ) {
					$urlThumb	 = $res->results->thumb[0]->url;
					$urlMovie	 = $res->results->encode_video[0]->url;

					echo $strHTML = <<<HTML
		【サムネイル】
		<img src="{$urlThumb}"></img>
		【動画】
		<video controls="" autoplay="" name="media">
			<source src="{$urlMovie}" type="video/mp4">
		</video>
		<br />
HTML;
				}
			}
		}

		echo $strHTML = <<<HTML
</body>
</html>
HTML;

		//オブジェクト解放
		unset( $objClsTransloadit );
	}

}

?>