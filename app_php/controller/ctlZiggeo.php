<?php

require(DIR_APP . '/vendor/autoload.php');

use ziggeo\ziggeophpsdk;

class ctlZiggeo {

	private $_strAppToken;
	private $_strPrivateKey;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$url					 = parse_url( getenv( "ZIGGEO_URL" ) );
		$this->_strAppToken		 = $url["host"];
		$this->_strPrivateKey	 = $url["user"];
	}

	/**
	 * 画面処理分岐
	 */
	function processForm() {

		$objClsZiggeo	 = new clsZiggeo();
		$aryPostData	 = $objClsZiggeo->pullConvertData();
		$objChecker		 = new clsZiggeoChecker( $aryPostData );

		$count = 0;

		if ( isset( $_POST, $_POST['submitted'] ) && $_POST['submitted'] === 'submitted' && isset( $_FILES ) ) {
			//This is where we actually process everything
			//We require the Ziggeo SDK Entry file
			//require_once('/app/vendor/ziggeo/ziggeophpsdk/Ziggeo.php');

			$arguments = Array();

			//OK we have some tags set, we should add them to the videos
			if ( isset( $_POST['tags'] ) ) {
				$arguments['tags'] = $_POST['tags'];
			}

			//Data for output..
			$names = '';

			$ziggeo = new Ziggeo( $this->_strAppToken, $this->_strPrivateKey );

			$c = count( $_FILES['files']['name'] );

			for ( $i = 0; $i < $c; $i++ ) {

				//Setting up the arguments to pass over.. of course we can do this differently, this is just for example, but remember that each video has a different name while other parameters would be shared!
				$tmp_arguments			 = $arguments;
				$tmp_arguments['file']	 = $_FILES['files']['tmp_name'][$i];

				//the actual passing of the videos to Ziggeo from our PC / Mac
				$ziggeo->videos()->create( $tmp_arguments );

				//This is just for display purposes upon the upload
				$count++;
				$names .= '<li>' . $_FILES['files']['name'][$i] . '</li>';
			}
		}

		require_once( './dspZiggeoForm.php' );
	}

	/**
	 * 画面処理分岐
	 */
	function processList() {

		$objClsZiggeo	 = new clsZiggeo();
		$aryPostData	 = $objClsZiggeo->pullConvertData();
		$objChecker		 = new clsZiggeoChecker( $aryPostData );

		$settings = array(
			"title"			 => "Title of your Wall",
			"heading"		 => "Title Heading",
			"subtitle"		 => "Subtitle of your wall.",
			"css"			 => array("styles.css"),
			"token"			 => "12daf14e307244590dcac02d26804ed7", // Ziggeo Application Token
			"private_key"	 => "d0e3691dbf3de6c3c109cd79429c3301", // Ziggeo Application Private Key
			"sdkpath"		 => DIR_APP . 'vendor/ziggeo/ziggeophpsdk/Ziggeo.php', // path to Ziggeo php sdk
			//	"sdkpath"		 => '../vendor/ziggeo/ziggeophpsdk/Ziggeo.php', // path to Ziggeo php sdk
			"name_google"	 => FALSE,
			"open"			 => TRUE // People can record themselves
		);

		require_once( './dspZiggeoList.php' );
	}

}

?>