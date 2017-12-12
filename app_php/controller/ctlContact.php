<?php

/**
 * 	【コントローラ】お問合せ
 *
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
class ctlContact {

	/**
	 * コンストラクタ
	 */
	function __construct() {
		
	}

	/**
	 * 画面処理分岐
	 */
	function process() {

		$objClsContact	 = new clsContact();
		$aryPostData	 = $objClsContact->pullConvertData();
		$objChecker		 = new clsContactChecker( $aryPostData );

		$blnLocationFlg = false;

		$objSendGrid = new clsHerokuSendGridApiVer1();

		//セッションに値が保存されていれば復元
		if ( !empty( f::filterArray( clsDefinition::SESSION_CONTACT, $_SESSION, 5 ) ) && $aryPostData["strAction"] !== "send" ) {
			foreach ( f::filterArray( clsDefinition::SESSION_CONTACT, $_SESSION, 5 ) AS $key => $val ) {
				switch ( $key ) {
					case "strAction":
						break;
					default:
						$aryPostData[$key] = $val;
						break;
				}
			}
			unset( $_SESSION[clsDefinition::SESSION_CONTACT] );
		}

		//件名タグ作成
		$strSubjectTag = $objClsContact->getSubjectTag( (int)$aryPostData["subject"] );

		switch ( $aryPostData["strAction"] ) {
			case "Validate";
				$blnCheckResult = $objChecker->execute( $aryPostData );
				echo $objChecker->getErrorJson();
				exit();
				break;

			case "confirm":
				//データチェック
				if ( f::filterArray( "company", $aryPostData, 5 ) === "" ) {
					$objChecker->setError();
					$objChecker->setErrorMessage( "auth", "お手数ですが初めからやり直して下さい。" );
					clsCommonFunction::putErrorLog( "Inform PHP error:　【お問い合わせ】POSTデータが存在しないためトップページに遷移しました。" );
					$blnLocationFlg = true;
					break;
				}

				//セッションに保存
				$_SESSION[clsDefinition::SESSION_CONTACT] = $aryPostData;
				break;

			case "send":
				//データチェック
				if ( !isset( $_SESSION[clsDefinition::SESSION_CONTACT] ) ) {
					$objChecker->setError();
					$objChecker->setErrorMessage( "auth", "完了画面で更新またはセッションデータが破棄されたためトップページに遷移しました。" );
					clsCommonFunction::putErrorLog( "Inform PHP error:　【お問い合わせ】ユーザーが完了画面で更新またはセッションデータが破棄されたためトップページに遷移しました。" );
					$blnLocationFlg = true;
					break;
				}

				//セッションデータを配列に格納
				$arySessionData = $_SESSION[clsDefinition::SESSION_CONTACT];

				//現時刻の取得
				$strNowDateYMD	 = clsCommonFunction::getCurrentDateYMD();
				$strNowDateHIS	 = clsCommonFunction::getCurrentDateHIS();

				//管理者へメール送信
				$result = $objSendGrid->sendContactToInformMail( $arySessionData, $strNowDateYMD, $strNowDateHIS );
				if ( $result !== true ) {
					$objChecker->setError();
					$objChecker->setErrorMessage( "auth", "メール送信に失敗しました。<br />お手数ですがお電話にてご連絡下さい。" );
					clsCommonFunction::putErrorLog( "Inform PHP msg:　【お問い合わせ】管理者へのメール送信時に「SendGridApi」でエラー発生" );
					$blnLocationFlg = true;
					break;
				} else {
					//クライアントへメール送信
					$result = $objSendGrid->sendContactToClientMail( $arySessionData, $strNowDateYMD, $strNowDateHIS );
					if ( $result !== true ) {
						$objChecker->setError();
						$objChecker->setErrorMessage( "auth", "メール送信に失敗しました。<br />お手数ですがお電話にてご連絡下さい。" );
						clsCommonFunction::putErrorLog( "Inform PHP error:　【お問い合わせ】クライアントへのメール送信時に「SendGridApi」でエラー発生" );
						$blnLocationFlg = true;
						break;
					}
				}

				//セッション情報削除
				unset( $_SESSION[clsDefinition::SESSION_CONTACT] );

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
			unset( $_SESSION[clsDefinition::SESSION_CONTACT] );
			header( 'Location:' . './index.php' );
			exit();
		}

		//画面VIEW選択
		switch ( $aryPostData["strAction"] ) {
			case "confirm":
				require_once( './dspContacConf.php' );
				break;

			case "send":
				require_once( './dspContacComp.php' );
				break;

			default:
				require_once( './dspContac.php' );
				break;
		}
	}

}

?>