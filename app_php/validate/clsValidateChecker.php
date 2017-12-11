<?php

/**
 * 汎用エラーチェック処理クラス。
 * (※利用時はこのクラスを継承したサブクラスを作成すること）
 * 
 * value_checker.jsで設定された項目の値をそれぞれ検査する。
 * 値にエラーがある場合はエラーメッセージを生成する。
 * getErrorMessage関数を用いて、項目のname属性と
 * エラーメッセージの連想配列を取得できる。
 * ※サブクラス作成時にコンストラクタを作成する場合は、
 * サブクラスのコンストラクタから、このクラスのコンストラクタを呼び出すこと。
 * 
 * @author 2012/04/27 Kim
 * */
abstract class clsValidateChecker {

	//検査項目ごとのエラーメッセージ定数
	/**
	 * isNotEmptyファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_NOT_EMPTY = '・入力してください。';

	/**
	 * isCheckedファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_SELECTED = '・選択してください。';

	/**
	 * isSelectedファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_CHECKED = '・選択してください。';

	/**
	 * isNumericファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_DIGITS = '・半角数字を入力してください。';

	/**
	 * isNumericファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_NUMBER = '・整数を入力してください。';

	/**
	 * isNumericファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_NUMERIC = '・数値を入力してください。';

	/**
	 * isAlphamericファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_ALPHAMERIC = '・半角英数字を入力してください。';

	/**
	 * isEnEnglishファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_EN_ENGLISH = '・半角英字を入力してください。';

	/**
	 * isHiraganaファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_HIRAGANA = '・ひらがなを入力してください。';

	/**
	 * isKatakanaファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_KATAKANA = '・カタカナを入力してください。';

	/**
	 * isKatakanaファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_NOT_H_KATAKANA = '・半角カタカナは入力しないでください。';

	/**
	 * isPhoneNumberファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_PHONE_NUMBER = '・電話番号はハイフンを含む半角数字を入力してください。<br />　入力例：[　12-3456-7890　]';

	/**
	 * isPhoneNumberファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_PHONE_NUMBER2 = '・電話番号は半角数字を入力してください。<br />　※半角スペース、ハイフン、括弧()を含む';

	/**
	 * isPhoneNumberファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_FAX_NUMBER = '・FAX番号はハイフンを含む半角数字を入力してください。<br />　入力例：[　12-3456-7890　]';

	/**
	 * isPostCodeファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_POST_CODE = '・郵便番号はハイフンを含む半角数字を入力してください。<br />　入力例：[　123-4567　]';

	/**
	 * chkMaxLengthファンクションの戻り値がfalse時のメッセージ
	 * 文末に最大文字数の値を付加すること。
	 */
	const ERROR_MAX_LENGTH = '・最大文字数までの文字を入力してください。<br />　最大文字数：';

	/**
	 * chkMaxValueファンクションの戻り値がfalse時のメッセージ
	 * 文末に最大値の値を付加すること
	 */
	const ERROR_MAX_VALUE = '・最大値以下の数値を入力してください。<br />　最大値：';

	/**
	 * chkMinValueファンクションの戻り値がfalse時のメッセージ
	 * 文末に最小値の値を付加すること
	 */
	const ERROR_MIN_VALUE = '・最小値以上の数値を入力してください。<br />　最小値：';

	/**
	 * isDateファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_DATE = '・有効な日付を入力してください。<br />　入力形式：[　2000　]年[　01　]月[　01　]日';

	/**
	 * isMailAddressファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_MAIL_ADDRESS = '・メールアドレスを入力してください。<br />　入力例：[　example@example.co.jp　]';

	/**
	 * isUrlファンクションの戻り値がfalse時のメッセージ
	 */
	const ERROR_URL = '・正しいURLを入力してください。<br />　入力例：[　http://www.example.com/　]';

	/**
	 * 検査する項目のname属性および値の連想配列
	 * key: 項目のname属性   value: 項目の値
	 */
	private $_aryInputs = array();

	/**
	 * エラーメッセージ出力フラグ
	 */
	private $_blnError = false;

	/**
	 * 検査結果のエラーメッセージ
	 */
	private $_aryErrorMessages = array();

	/**
	 * コンストラクタ
	 * 
	 * 検査する項目のname属性および値を連想配列で受け取り、格納する。
	 * 
	 * @param array $aryInputs : フォームのPOSTデータを格納する配列
	 * @author 2012/04/27 Kim
	 */
	public function __construct( array $aryInputs ) {
		//フォームのPOSTデータの格納
		$this->setInputs( $aryInputs );
	}

	/**
	 * フォームのname属性と値の連想配列($_aryInputs)のgetter関数
	 * 
	 * @return array $_aryInputs
	 * @author 2012/5/9 Kim
	 */
	public function getInputs() {
		return $this->_aryInputs;
	}

	/**
	 * フォームのname属性と値の連想配列($_aryInputs)のsetter関数
	 * 
	 * @param array $aryInputs
	 * @author 2012/5/9 Kim
	 */
	public function setInputs( $aryInputs ) {
		$this->_aryInputs = $aryInputs;
	}

	/**
	 * 年月日の3つのテキストボックスの値を、
	 * 一つの日付の値としてフォーマットする関数。
	 * 
	 * 検査する項目の連想配列に、第1引数($strName)の末尾に
	 * それぞれ｢Year｣｢Month｣｢Day｣が付加されている項目が
	 * 存在する場合は、yyyy/mm/ddのフォーマット形式に連結し、
	 * $strNameで指定した名前の項目として登録しtrueを返す。
	 * フォーマットに成功した場合、連結前の項目をunset関数で
	 * 連想配列から削除する。
	 * どれか一項目でも存在しない場合はfalseを返す。
	 * 
	 * @param string  $strName  : フォームのname属性
	 * @return bool : フォーマットの成否
	 * @author 2012/04/27 Kim
	 */
	public function formatYMD( string $strName ) {
		$blnResult = false;

		$aryInputs = $this->getInputs();

		if ( array_key_exists( $strName . "Year", $aryInputs ) && array_key_exists( $strName . "Month", $aryInputs ) && array_key_exists( $strName . "Day", $aryInputs ) && ( "" != $aryInputs[$strName . "Year"] || "" != $aryInputs[$strName . "Month"] || "" != $aryInputs[$strName . "Day"])
		) {
			$strYmd				 = $aryInputs[$strName . "Year"]
					. '/' . $aryInputs[$strName . "Month"]
					. '/' . $aryInputs[$strName . "Day"];
			$aryInputs[$strName] = $strYmd;
			unset( $aryInputs[$strName . "Year"] );
			unset( $aryInputs[$strName . "Month"] );
			unset( $aryInputs[$strName . "Day"] );

			$this->setInputs( $aryInputs );
			$blnResult = true;
		}


		return $blnResult;
	}

	/**
	 * 格納されたフォームの値の連想配列($_aryInputs)から
	 * 指定されたname属性の値を取り出す。
	 * 
	 * 存在しないname属性が指定された場合はnullを返す。
	 * 
	 * @param string  $strName  : フォームのname属性
	 * @return string $strValue : 指定されたフォームのname属性の値
	 * @author 2012/04/27 Kim
	 */
	public function getValue( string $strName ) {

		$aryInputs = $this->getInputs();

		$strValue = null;

		if ( isset( $aryInputs[$strName] ) ) {
			$strValue = $aryInputs[$strName];
		}

		return $strValue;
	}

	/**
	 * 格納されたフォームの値の連想配列($_aryInputs)に
	 * 指定されたname属性の値を設定する。
	 * 
	 * @param string  $strName  : フォームのname属性
	 * @param string  $strValue  : name属性の値
	 * @author 2012/05/30 Kim
	 */
	public function setValue( string $strName, string $strValue ) {

		$aryInputs			 = $this->getInputs();
		$aryInputs[$strName] = $strValue;

		$this->setInputs( $aryInputs );
	}

	/**
	 * エラーメッセージ出力フラグ設定関数
	 * 
	 * 検査結果がエラー時に呼び出し、$_blnErrorをtrueに設定する。
	 * 
	 * @author 2012/04/27 Kim
	 */
	public function setError() {
		//インスタンスのエラーメッセージ出力フラグをtrueにする。
		$this->_blnError = true;
	}

	/**
	 * エラーメッセージ出力フラグ取得関数
	 * 
	 * 検査結果がエラー時に呼び出す。
	 * 
	 * @return bool $_blnError
	 * @author 2012/04/27 Kim
	 */
	public function isError() {
		return $this->_blnError;
	}

	/**
	 * エラーメッセージ登録関数
	 * 
	 * エラーのある項目名とエラーメッセージを受け取り、
	 * 連想配列に格納する。
	 * 一つの項目にエラーが複数ある場合は、エラーメッセージを追記する。
	 * 
	 * @param string $strName    : 項目名
	 * @param string $strMessage : エラーメッセージ
	 * @author 2012/04/27 Kim
	 */
	public function setErrorMessage( string $strName, string $strMessage ) {
		//エラーメッセージ配列から、該当する検査項目のエラーメッセージを追記する。
		//複数エラーの場合に備えて、改行タグを追加する。
		if ( false == isset( $this->_aryErrorMessages[$strName] ) ) {
			$this->_aryErrorMessages[$strName]			 = array();
			$this->_aryErrorMessages[$strName]["errMsg"] = "";
		}
		$this->_aryErrorMessages[$strName]["errId"] = $strName;
		if ( "" !== $strMessage ) {
			$this->_aryErrorMessages[$strName]["errMsg"] .= $strMessage . "<br />";
		}
	}

	/**
	 * エラーメッセージ取得関数
	 * 
	 * 検査の結果、エラーのあった項目のエラーメッセージを返す
	 * 
	 * @return $aryReturnMessage array : エラーメッセージ配列
	 * @author 2012/04/27 Kim
	 */
	public function getErrorMessage() {
		$aryReturn = array();
		foreach ( $this->_aryErrorMessages as $msg ) {
			$aryReturn[] = $msg;
		}

		return $aryReturn;
	}

	public function getErrorJson() {

		$aryErr = array(
			"errFlg"	 => $this->isError(),
			"errInfo"	 => $this->getErrorMessage()
		);
		return json_encode( $aryErr );
	}

	/**
	 * 検査対象の値が空白かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ空文字でなければtrueを返す。n　
	 * 
	 * @param string $strValue : 検査する値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isNotEmpty( string $strValue ) {

		if ( isset( $strValue ) && $strValue != "" ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 項目が選択されているかどうかの検査関数
	 * 
	 * 値がセットされていて、かつ空文字でなければtrueを返す。
	 * 
	 * @param string $strValue : 検査する値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isSelected( string $strValue ) {

		if ( isset( $strValue ) && $strValue != "" ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 項目がチェックされているかどうかの検査関数
	 * 
	 * 値がセットされていて、かつ要素が1つ以上ある配列の場合はtrueを返す。(checkbox)
	 * 値がセットされていて、かつ空文字でなければtrueを返す。(radio)
	 * 
	 * @param string $aryValue : 検査する値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isChecked( array $aryValue ) {

		if ( isset( $aryValue ) && (
				$aryValue != "" || (is_array( $aryValue ) && count( $aryValue ) > 0)
				)
		) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が数字かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ数字のみであればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param $strValue string : 検査する値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isDigits( string $strValue ) {

		if ( isset( $strValue ) && preg_match( "/^[0-9]+$/", $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が数字かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ数字のみであればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査する値
	 * @param bool $blnZero  : 0を許可するか　デフォルト:true
	 * @param bool $blnMinus : マイナスを許可するか　デフォルト:true
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isNumber( string $strValue, bool $blnZero = true, bool $blnMinus = true ) {

		$strPattern = "";

		if ( $blnZero ) {
			$strPattern .= "(0|[1-9][0-9]*)";
		} else {
			$strPattern .= "[1-9][0-9]*";
		}

		if ( $blnMinus ) {
			$strPattern = "(\-){0,1}" . $strPattern;
		}

		if ( isset( $strValue ) && preg_match( "/^" . $strPattern . "$/", $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が数値かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ数値であればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue  : 検査する値
	 * @param int $intPrecision : 総桁数
	 * @param int $intScale     : 少数桁数
	 * @param bool $blnZero  : 0を許可するか　デフォルト:true
	 * @param bool $blnMinus : マイナスを許可するか　デフォルト:true
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 * */
	public function isNumeric( string $strValue, int $intPrecision, int $intScale, bool $blnZero = true, bool $blnMinus = true ) {
		/*
		  if( false == $this->isNumber($intPrecision, $false, $false)
		  || false == $this->isNumber($intScale, $true, $false)
		  || $intPrecision <= $intScale
		  ){
		  return false;
		  }
		 */
		$intNumberLength = $intPrecision - $intScale - 1;

		$strPattern = "";

		if ( $blnZero ) {
			$strPattern .= "(0|[1-9][0-9]{0," . $intNumberLength . "})(\.[0-9]{0," . $intScale . "}){0,1}";
		} else {
			$strPattern .= "([1-9][0-9]{0," . $intNumberLength . "}(\.[0-9]{0," . $intScale . "}){0,1}|0\.[1-9][0-9]{0,1}|0\.[0-9][1-9])";
		}

		if ( $blnMinus ) {
			$strPattern = "(\-){0,1}" . $strPattern;
		}

		if ( isset( $strValue ) && preg_match( "/^" . $strPattern . "$/", $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が半角英数かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ半角英数のみであればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査する値
	 * @return bool : 検査結果
	 * @author 2012/05/09 Kim
	 */
	public function isAlphameric( string $strValue ) {
		if ( isset( $strValue ) && preg_match( "/^[0-9a-zA-Z]+$/", $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が半角英かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ半角英のみであればtrueを返す。
	 * 第二引数の$blnKigouがtrueの場合は『cf.Thomas O'Malley』のような'や.の入った入力も許可する。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査する値
	 * @param bool $blnKigou : 記号許可
	 * @return bool : 検査結果
	 * @author 2013/02/12 Nishi
	 */
	public function isEnEnglish( string $strValue, bool $blnKigou = false ) {

		$strPattern = "";
		if ( $blnKigou ) {
			$strPattern = "/^[a-zA-Z\.\']+$/u";
		} else {
			$strPattern = "/^[a-zA-Z]+$/u";
		}

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値がひらがなかどうかの検査関数
	 * 
	 * 値がセットされていて、かつひらがなのみであればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param $strValue string : 検査対象の値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isHiragana( string $strValue ) {
		if ( isset( $strValue ) && preg_match( "/^[ぁ-んーゝゞ゛゜]+$/u", $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値がカタカナかどうかの検査関数
	 * 
	 * 値がセットされていて、かつカタカナのみであればtrueを返す。
	 * 第二引数の$blnHankakuがtrueの場合は数字列のみの入力も許可する。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @param bool $blnHankaku : 半角のみ
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isKatakana( string $strValue, bool $blnHankaku = false ) {

		$strPattern = "";
		if ( $blnHankaku ) {
			$strPattern = "/^[ｦ-ﾟァ-ヶー]+$/u";
		} else {
			$strPattern = "/^[ァ-ヶー]+$/u";
		}

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値がカタカナかどうかの検査関数
	 * 
	 * 値がセットされていて、かつカタカナのみであればtrueを返す。
	 * 第二引数の$blnHankakuがtrueの場合は数字列のみの入力も許可する。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isNotHankakuKatakana( string $strValue ) {

		$strPattern = "/[ｦ-ﾟ]+$/u";

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )	) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 検査対象の値が電話(FAX)番号かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ電話(FAX)番号のフォーマットであればtrueを返す。
	 * 電話(FAX)番号フォーマット[ 数字列-数字列-数字列 ]
	 * 第二引数の$blnNoHyphenがtrueの場合は数字列のみの入力を許可する。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string  $strValue    : 検査対象の値
	 * @param bool $blnNoHyphen : ハイフン無しを許可するか(許可:true 不許可(デフォルト):false)
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isPhoneNumber( string $strValue, bool $blnNoHyphen = false ) {

		$strPattern = "";
		if ( $blnNoHyphen ) {
			//$strPattern = "/^([0-9]+(-[0-9]+){0,2})$/u";
			$strPattern = "/^[0-9]{6,11}+$/u";
		} else {
			$strPattern = "/^[0-9]+-[0-9]+-[0-9]+$/u";
		}

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が電話(FAX)番号かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ電話(FAX)番号のフォーマットであればtrueを返す。
	 * 電話(FAX)番号フォーマット[ 数字列-数字列-数字列 ]
	 * 第二引数の$blnNoHyphenがtrueの場合は数字列のみの入力を許可する。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string  $strValue    : 検査対象の値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isPhoneNumber2( string $strValue ) {

		$strPattern = "/^[0-9 |()-]+$/u";

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が郵便番号かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ郵便番号のフォーマットであればtrueを返す。
	 * 郵便番号フォーマット[ 数字3文字-数字4文字 ]
	 * 第二引数の$blnNoHyphenがtrueの場合は数字列のみの入力も許可する。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string  $strValue    : 検査対象の値
	 * @param bool $blnNoHyphen : ハイフン無しを許可するか(許可:true 不許可(デフォルト):false)
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isPostCode( string $strValue, bool $blnNoHyphen = false ) {

		$strPattern = "";
		if ( $blnNoHyphen ) {
			$strPattern = "/^([0-9]{7}|[0-9]{3}-[0-9]{4})$/u";
		} else {
			$strPattern = "/^[0-9]{3}-[0-9]{4}$/u";
		}

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値の文字数が指定した文字数以下かどうかの検査関数
	 * 
	 * 検査対象の値と指定した文字数の値がセットされていて、
	 * かつ、検査対象の値の文字数が指定した文字数以下であればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string  $strValue    : 検査対象の値
	 * @param int $intMaxLength : 最大文字数
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function chkMaxLength( string $strValue, int $intMaxLength ) {
		if ( isset( $strValue ) && isset( $intMaxLength ) && $this->isNumber( $intMaxLength, true, false ) && mb_strlen( $strValue, "UTF-8" ) <= $intMaxLength
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が指定した値以下かどうかの検査関数
	 * 
	 * 検査対象の値と指定した今朝数の値がセットされていて、
	 * かつ、入力した値の桁数が指定した桁数以下であればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @param int $intMaxValue : 指定桁数
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function chkMaxValue( string $strValue, int $intMaxValue ) {
		if ( isset( $strValue ) && isset( $intMaxValue ) && is_numeric( $strValue ) && is_numeric( $intMaxValue ) && (double)$strValue <= (double)$intMaxValue
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 検査対象の値が指定した値以上かどうかの検査関数
	 * 
	 * 検査対象の値と指定した今朝数の値がセットされていて、
	 * かつ、入力した値の桁数が指定した桁数以下であればtrueを返す。
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @param int $intMinValue : 指定桁数
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function chkMinValue( string $strValue, int $intMinValue ) {
		if ( isset( $strValue ) && isset( $intMinValue ) && is_numeric( $strValue ) && is_numeric( $intMinValue ) && (double)$strValue >= (double)$intMinValue
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 入力した値が日付かどうかの検査関数
	 * 
	 * 値がセットされていて、かつ日付のフォーマットであり、
	 * 存在する日付であればtrueを返す。
	 * 日付フォーマット[ 数字2または4文字(/|-)数字2文字(/|-)数字2文字 ]
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @return boolean : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isDate( string $strValue ) {
		$blnResult;

		if ( isset( $strValue ) && preg_match( "/^[0-9]{4}\/[0-9]{1,2}\/[0-9]{1,2}$/u", $strValue )
		) {
			$aryDate = preg_split( "/\//", $strValue, -1, PREG_SPLIT_NO_EMPTY );

			if ( checkdate( (int)$aryDate[1], (int)$aryDate[2], (int)$aryDate[0] ) ) {
				$blnResult = true;
			} else {
				$blnResult = false;
			}
		} elseif ( false == isset( $strValue ) ) {
			$blnResult = true;
		} else {
			$blnResult = false;
		}

		return $blnResult;
	}

	/**
	 * 入力した値がメールアドレスかどうかの検査関数
	 * 
	 * 値がセットされていて、かつメールアドレスのフォーマットであればtrueを返す。
	 * メールアドレス正規表現[ /^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i ]
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @return bool : 検査結果
	 * @author 2012/04/27 Kim
	 */
	public function isMailAddress( string $strValue ) {

		$strPattern = '/^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i';

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 入力した値がURLかどうかの検査関数
	 * 
	 * 値がセットされていて、かつURLのフォーマットであればtrueを返す。
	 * メールアドレス正規表現[ /^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i ]
	 * また、未入力の場合もtrueを返す。(必須以外の項目チェックのため)
	 * 
	 * @param string $strValue : 検査対象の値
	 * @return boolean : 検査結果
	 * @author 2013/03/05 Nambe
	 */
	public function isUrl( string $strValue ) {

		$strPattern = '/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i';

		if ( isset( $strValue ) && preg_match( $strPattern, $strValue )
		) {
			return true;
		} elseif ( isset( $strValue ) && "" == $strValue ) {
			return true;
		} else {
			return false;
		}
	}

}

?>