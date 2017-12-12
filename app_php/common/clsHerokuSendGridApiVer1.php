<?php

/**
 * 	【Heroku】SendGridApi
 * 	SendGridにてメール送信を行う
 *
 * 	@author		kuboe 2017/12/06
 * 	@version		1.0
 */
require(DIR_APP . '/vendor/autoload.php');

use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Content;

class clsHerokuSendGridApiVer1 {

	//インスタンス
	private $_objSendGrid;
	//パラメータ
	private $_strApiKey;
	private $_objFrom;
	private $_strModeMailTitle;
	private $_aryContactToMail;

	/**
	 * コンストラクタ
	 */
	function __construct() {

		//Herokuサーバー
		if ( SERVER_MODE !== false ) {
			$this->_strApiKey = getenv( 'SENDGRID_API_KEY' );
			switch ( SERVER_MODE ) {
				case "DEV_MODE":
					$this->_aryContactToMail = clsDefinition::$DEV_CONTACT_TO_MAIL;
					break;

				case "STG_MODE":
					$this->_aryContactToMail = clsDefinition::$STG_CONTACT_TO_MAIL;
					break;

				case "PRD_MODE":
					$this->_aryContactToMail = clsDefinition::$PRD_CONTACT_TO_MAIL;
					break;
			}
			//ローカル
		} else {
			$this->_strApiKey		 = clsDefinition::LOCAL_CONTACT_SEND_GRID_API_KEY;
			$this->_aryContactToMail = clsDefinition::$DEV_CONTACT_TO_MAIL;
		}

		//インスタンス作成
		$this->_objSendGrid = new SendGrid( $this->_strApiKey );

		//From
		$this->_objFrom = new Email( clsDefinition::MAIL_FROM_NAME, clsDefinition::MAIL_FROM );

		//件名
		if ( SERVER_MODE !== false ) {
			switch ( SERVER_MODE ) {
				case "DEV_MODE":
					$strModeMailTitle = "【開発テスト】";
					break;

				case "STG_MODE":
					$strModeMailTitle = "【ステージングテスト】";
					break;

				case "PRD_MODE":
					$strModeMailTitle = "";
					break;
			}
		} else {
			$strModeMailTitle = "【ローカルテスト】";
		}
		$this->_strModeMailTitle = $strModeMailTitle;
	}

//----------------------------------------------------------------------------------------------------------------------
// 共通
//----------------------------------------------------------------------------------------------------------------------
	/**
	 * エラーコード/エラー原因の取得
	 * @param int $intStatusCode
	 * @return array
	 */
	function getStatusCode( int $intStatusCode, array $aryHeaders, array $aryBody ) {
		$aryRet = [];

		//コード設定
		$aryRet["code"] = $intStatusCode;

		if ( $intStatusCode === 200 || $intStatusCode === 202 ) {
			$aryRet["result"]	 = true;
			$aryRet["case"]		 = "";
		} else {
			$aryRet["result"]	 = false;
			$aryRet["msg"]		 = $aryBody["errors"][0]->message;
			switch ( $intStatusCode ) {
				case 400:
					$aryRet["case"]	 = "BAD REQUEST";
					break;
				case 401:
					$aryRet["case"]	 = "UNAUTHORIZED";
					break;
				case 403:
					$aryRet["case"]	 = "FORBIDDEN";
					break;
				case 404:
					$aryRet["case"]	 = "NOT FOUND";
					break;
				case 405:
					$aryRet["case"]	 = "METHOD NOT ALLOWED";
					break;
				case 413:
					$aryRet["case"]	 = "PAYLOAD TOO LARGE";
					break;
				case 415:
					$aryRet["case"]	 = "UNSUPPORTED MEDIA TYPE";
					break;
				case 429:
					$aryRet["case"]	 = "TOO MANY REQUESTS";
					break;
				case 500:
					$aryRet["case"]	 = "SERVER UNAVAILABLE";
					break;
				case 503:
					$aryRet["case"]	 = "SERVICE NOT AVAILABLE";
					break;
			}
		}

		return $aryRet;
	}

//----------------------------------------------------------------------------------------------------------------------
// お問い合わせ
//----------------------------------------------------------------------------------------------------------------------
	/**
	 * 【お問い合わせ】管理側へメール送信
	 * 
	 * @param array $aryPostData POST値
	 * @param string $strNowDateYMD 本日の年月日
	 * @param string $strNowDateHIS 本日の時分秒
	 */
	function sendContactToInformMail( array $aryPostData, string $strNowDateYMD, string $strNowDateHIS ) {

		$strMailTitle = $this->_strModeMailTitle . clsDefinition::CONTACT_MAIL_TITLE;

		//メール文面作成
		$strUserAgent	 = clsCommonFunction::getUserAgent();
		$strUserIp		 = clsCommonFunction::getUserIP();
		$strUserHost	 = clsCommonFunction::getUserHost();
		$strSubject		 = clsDefinition::$CONTACT_SUBJECT[(int)$aryPostData["subject"]];
		$stWeek			 = clsCommonFunction::getTagWeekdayJP( $strNowDateYMD );
		$strNow			 = $strNowDateYMD . "(" . $stWeek . ") " . $strNowDateHIS;

		$strMsg = <<< MSG
【会社名】\n
{$aryPostData["company"]}\n

【部署名】\n
{$aryPostData["post"]}\n

【氏名】\n
{$aryPostData["name"]}\n

【電話番号】\n
{$aryPostData["tel"]}\n

【メールアドレス】\n
{$aryPostData["mail_address"]}\n

【件名】\n
{$strSubject}\n

【内容】\n
{$aryPostData["msg"]}\n

【送信者情報】\n
・ブラウザー      : {$strUserAgent}\n
・送信元IPアドレス: {$strUserIp}\n
・送信元ホスト名  : {$strUserHost}\n
・送信日時        : {$strNow}\n
MSG;

		$content = new Content( "text/plain", $strMsg );

		//管理者にメール送信
		$mail = null;
		foreach ( $this->_aryContactToMail AS $key => $val ) {
			switch ( (int)$key ) {
				case 0:
					$to		 = new Email( null, $val );
					$mail	 = new Mail( $this->_objFrom, $strMailTitle, $to, $content );
					break;

				default:
					$to = new Email( null, $val );
					$mail->personalization[0]->addTo( $to );
					break;
			}
		}

		try {
			$response = $this->_objSendGrid->client->mail()->send()->post( $mail );

			//送信成功
			$aryHeaders	 = (!is_null( $response->headers() )) ? $response->headers() : [];
			$aryBody	 = (!is_null( $response->body() )) ? (array)json_decode( $response->body() ) : [];
			$aryResult	 = $this->getStatusCode( (int)$response->statusCode(), $aryHeaders, $aryBody );
			if ( $aryResult["result"] === false ) {
				$strStatusCode	 = $response->statusCode();
				$aryHeader		 = $response->headers();
				return false;
			}
		} catch ( Exception $ex ) {
			return false;
		}

		return true;
	}

	/**
	 * 【お問い合わせ】クライアントへメール送信
	 * 
	 * @param array $aryPostData POST値
	 */
	function sendContactToClientMail( array $aryPostData ) {

		$strMailTitle = $this->_strModeMailTitle . clsDefinition::CONTACT_MAIL_TITLE;

		$strMsg = <<< MSG
{$aryPostData["name"]}様\n
こんにちは。インフォームシステムです。\n
お問合せありがとうございました。\n
MSG;

		$content = new Content( "text/plain", $strMsg );

		$to		 = new Email( null, $aryPostData["mail_address"] );
		$mail	 = new Mail( $this->_objFrom, $strMailTitle, $to, $content );

		try {
			$response = $this->_objSendGrid->client->mail()->send()->post( $mail );

			//送信成功
			$aryHeaders	 = (!is_null( $response->headers() )) ? $response->headers() : [];
			$aryBody	 = (!is_null( $response->body() )) ? (array)json_decode( $response->body() ) : [];
			$aryResult	 = $this->getStatusCode( (int)$response->statusCode(), $aryHeaders, $aryBody );
			if ( $aryResult["result"] === false ) {
				$strStatusCode	 = $response->statusCode();
				$aryHeader		 = $response->headers();
				return false;
			}
		} catch ( Exception $ex ) {
			return false;
		}


		return true;
	}

//----------------------------------------------------------------------------------------------------------------------
// ホスト情報・紹介
//----------------------------------------------------------------------------------------------------------------------
	/**
	 * 【ホスト情報・紹介】管理側へメール送信
	 * 
	 * @param array $aryPostData POST値
	 * @param string $strNowDateYMD 本日の年月日
	 * @param string $strNowDateHIS 本日の時分秒
	 */
	function sendHostlookToInformMail( array $aryPostData, string $strNowDateYMD, string $strNowDateHIS ) {

		$strMailTitle = $this->_strModeMailTitle . $aryPostData["company"] . clsDefinition::HOSTLOOK_MAIL_TITLE;

		//メール文面作成
		$strIpinfo	 = clsDefinition::$HOSTLOOK_IPINFO[(int)$aryPostData["ipinfo"]];
		$stWeek		 = clsCommonFunction::getTagWeekdayJP( $strNowDateYMD );
		$strNow		 = $strNowDateYMD . " " . $strNowDateHIS;

		//インフォーム用に必要なデータ設定
		$strServerName		 = clsCommonFunction::getServerName();
		$strServerProtocol	 = clsCommonFunction::getServerProtocol();
		$strServerPort		 = clsCommonFunction::getServerPort();
		$strGatewayInterface = clsCommonFunction::getGatewayInterface();
		$strHttpReferer		 = clsCommonFunction::getHttpReferer();
		$strUserAgent		 = clsCommonFunction::getUserAgent();
		$strUserHost		 = clsCommonFunction::getUserHost();
		$strUserIP			 = clsCommonFunction::getUserIP();

		$strMsg	 = <<< MSG
このメールはお客様のホスト情報を通知しています。\n
================================================================\n
会社名・ご担当者様お名前　:{$aryPostData["company"]}\n
お客様ドメイン　　　　　　:{$aryPostData["domain"]}\n
ご連絡先お電話番号　　　　:{$aryPostData["tel"]}\n
e-mail　　　　　　　　　　:{$aryPostData["mail_address"]}\n
IP情報　　　　　　　　　　:{$strIpinfo}\n
DATE　　　　　　　　　　　:{$strNow}\n
COMMENT　　　　　　　　　 :\n{$aryPostData["comment"]}\n
================================================================\n
SERVER_NAME　　　　:{$strServerName}\n
SERVER_PROTOCOL　　:{$strServerProtocol}\n
SERVER_PORT　　　　:{$strServerPort}\n
GATEWAY_INTERFACE　:{$strGatewayInterface}\n
HTTP_REFERER 　　　:{$strHttpReferer}\n
HTTP_USER_AGENT　　:{$strUserAgent}\n
REMOTE_HOST　　　　:{$strUserHost}\n
REMOTE_ADDR　　　　:{$strUserIP}\n
================================================================
--- message --- 
 
================================================================
MSG;
		$content = new Content( "text/plain", $strMsg );

		//管理者にメール送信
		$mail = null;
		foreach ( $this->_aryContactToMail AS $key => $val ) {
			switch ( (int)$key ) {
				case 0:
					$to		 = new Email( null, $val );
					$mail	 = new Mail( $this->_objFrom, $strMailTitle, $to, $content );
					break;
				default:
					$to		 = new Email( null, $val );
					$mail->personalization[0]->addTo( $to );
					break;
			}
		}

		try {
			$response = $this->_objSendGrid->client->mail()->send()->post( $mail );

			//送信成功
			$aryHeaders	 = (!is_null( $response->headers() )) ? $response->headers() : [];
			$aryBody	 = (!is_null( $response->body() )) ? (array)json_decode( $response->body() ) : [];
			$aryResult	 = $this->getStatusCode( (int)$response->statusCode(), $aryHeaders, $aryBody );
			if ( $aryResult["result"] === false ) {
				$strStatusCode	 = $response->statusCode();
				$aryHeader		 = $response->headers();
				return false;
			}
		} catch ( Exception $ex ) {
			return false;
		}


		return true;
	}

}

?>