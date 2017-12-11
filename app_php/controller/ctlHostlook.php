<?php

/**
 * 	【コントローラ】ホスト情報・照会
 *
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
class ctlHostlook {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		
	}

	/**
	 * 画面処理分岐
	 */
	function process() {

		$objClsHostlook	 = new clsHostlook();
		$aryPostData	 = $objClsHostlook->pullConvertData();
		$objChecker		 = new clsHostlookChecker( $aryPostData );

		$blnLocationFlg = false;

		$objSendGrid = new clsHerokuSendGridApiVer1();
		
		//件名タグ作成
		$strIpinfoTag = $objClsHostlook->getIpInfoTag( (int)$aryPostData["ipinfo"] );

		switch ( $aryPostData["strAction"] ) {
			case "Validate";
				$blnCheckResult = $objChecker->execute( $aryPostData );
				echo $objChecker->getErrorJson();
				exit();
				break;

			case "send":
				$strSessiontoken = f::filterArray( clsDefinition::SESSION_TOKEN, $_SESSION );
				if ( $strSessiontoken === "" || f::filterArray( "strToken", $aryPostData ) === "" || $strSessiontoken !== f::filterArray( "strToken", $aryPostData ) ) {
					$objChecker->setError();
					$objChecker->setErrorMessage( "auth", "完了画面で更新またはセッションデータが破棄されたためトップページに遷移しました。" );
					clsCommonFunction::putErrorLog( "Inform PHP msg:　【ホスト情報・照会】ユーザーが完了画面で更新またはセッションデータが破棄されたためトップページに遷移しました。" );
					$blnLocationFlg = true;
					break;
				}

				//現時刻の取得
				$strNowDateYMD	 = clsCommonFunction::getCurrentDateYMD();
				$strNowDateHIS	 = clsCommonFunction::getCurrentDateHIS();

				//管理者へメール送信
				$result = $objSendGrid->sendHostlookToInformMail( $aryPostData, $strNowDateYMD, $strNowDateHIS );
				if ( $result !== true ) {
					$objChecker->setError();
					$objChecker->setErrorMessage( "auth", "メール送信に失敗しました。<br />お手数ですがお電話にてご連絡下さい。" );
					clsCommonFunction::putErrorLog( "Inform PHP error:　【ホスト情報・照会】管理者へのメール送信時に「SendGridApi」でエラー発生" );
					$blnLocationFlg = true;
					break;
				}

				unset( $_SESSION[clsDefinition::SESSION_TOKEN] );

				break;

			default:
				break;
		}

		//エラーがあった場合はエラー情報のJSON文字列を取得する
		if ( $objChecker->isError() ) {
			$_SESSION[clsDefinition::SESSION_ERROR_JSON] = $objChecker->getErrorJson();
		}

		//ロケーション判定
		if ( $blnLocationFlg ) {
			unset( $_SESSION[clsDefinition::SESSION_TOKEN] );
			header( 'Location:' . './index.php' );
			exit();
		}

		//画面VIEW選択
		switch ( $aryPostData["strAction"] ) {
			case "send":
				require_once( './dspHostlookComp.php' );
				break;

			default:
				$strToken								 = clsCommonFunction::getToken();
				$_SESSION[clsDefinition::SESSION_TOKEN]	 = $strToken;
				require_once( './dspHostlook.php' );
				break;
		}
	}

}

?>