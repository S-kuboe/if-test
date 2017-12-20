<?php

require(DIR_APP . '/vendor/autoload.php');

use transloadit\Transloadit;

class ctlTransloadit {

	private $_strKey;
	private $_strSecret;
	private $_strHostName;
	private $_strUserName;
	private $_strPassword;
	private $_strDatabase;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->_strKey	 = getenv( 'TRANSLOADIT_AUTH_KEY' );
		$this->_strSecret = getenv( 'TRANSLOADIT_SECRET_KEY' );

		$url = parse_url( getenv( "JAWSDB_URL" ) );
		$this->_strHostName	 = $url["host"];
		$this->_strUserName	 = $url["user"];
		$this->_strPassword	 = $url["pass"];
		$this->_strDatabase	 = ltrim( $url["path"], '/' );
	}

	/**
	 * 画面処理分岐
	 */
	function processTransloadit() {

		$objClsTransloadit	 = new clsTransloadit();
		$aryPostData		 = $objClsTransloadit->pullConvertData();
		$objChecker			 = new clsTransloaditChecker( $aryPostData );

		$transloadit = new Transloadit( array(
//	'key'	 => getenv( 'TRANSLOADIT_AUTH_KEY' ),
//	'secret' => getenv( 'TRANSLOADIT_SECRET_KEY' )
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
				exit;
			}

			$blnDsp = true;
		}
		require_once( './dspTransloadit.php' );
	}

	/**
	 * 画面処理分岐
	 */
	function processTransloaditS3() {

		$objClsTransloadit	 = new clsTransloadit();
		$aryPostData		 = $objClsTransloadit->pullConvertData();
		$objChecker			 = new clsTransloaditChecker( $aryPostData );

		$transloadit = new Transloadit( array(
//	'key'	 => getenv( 'TRANSLOADIT_AUTH_KEY' ),
//	'secret' => getenv( 'TRANSLOADIT_SECRET_KEY' )
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
							'bucket' => 'kuboe',
							'key'	 => 'AKIAJ66PLU22ANUDAJHA',
							'secret' => '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',
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
				} catch ( PDOException $e ) {
					echo "Connection failed: " . $e->getMessage() . "<br />";
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
				exit;
			}

			$blnDsp = true;
		}
		require_once( './dspTransloaditS3.php' );
	}

	/**
	 * 画面処理分岐
	 */
	function processTransloaditS3List() {

		$objClsTransloadit	 = new clsTransloadit();
		$aryPostData		 = $objClsTransloadit->pullConvertData();
		$objChecker			 = new clsTransloaditChecker( $aryPostData );

		try {
			$conn = new PDO( "mysql:host=$this->_strHostName;dbname=$this->_strDatabase", $this->_strUserName, $this->_strPassword );
			// set the PDO error mode to exception
			$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			echo "Connected successfully<br />";
		} catch ( PDOException $e ) {
			echo "Connection failed: " . $e->getMessage() . "<br />";
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
			if ( @file_get_contents( $val["upd_url"]) !== false ) {
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
HTML;
				}
			}
		}

		echo $strHTML = <<<HTML
</body>
</html>
HTML;
	}

}

?>