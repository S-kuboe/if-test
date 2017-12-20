<?php

class f {

	private static $_arySearchData;

	/**
	 * 文字列にhtmlspecialcharsをかけて出力する関数
	 * 使用例：<?php f::p($aryData["name1"]); ?>
	 *
	 * @param string $strValue
	 */
	static function p( string $strValue ) {
		echo htmlspecialchars( $strValue );
	}

	/**
	 * 文字列にhtmlspecialcharsをかけて出力する関数
	 * 使用例：<?php f::p($aryData["name1"]); ?>
	 *
	 * @param string $strValue
	 */
	static function pq( string $strValue ) {
		echo htmlspecialchars( $strValue, ENT_QUOTES );
	}

	/**
	 * 文字列にhtmlspecialcharsをかけて出力する関数
	 * 改行コードを<br>タグに変換する。
	 *
	 * @param string $strValue
	 */
	static function br( string $strValue ) {
		echo nl2br( htmlspecialchars( $strValue ) );
	}

	/**
	 * 文字列にhtmlspecialcharsをかけて出力する関数
	 * 改行コードを<br>タグに変換する。
	 *
	 * @param string $strValue
	 */
	static function brq( string $strValue ) {
		echo nl2br( htmlspecialchars( $strValue, ENT_QUOTES ) );
	}

	/**
	 * 整数文字にnumber_formatをかけて出力する関数
	 * 念のため数値にキャストしてます。
	 *
	 * @param string $strValue
	 * @param int $IntDecimals
	 */
	static function n( string $strValue, int $IntDecimals = 0 ) {
		echo number_format( (float)$strValue, $IntDecimals );
	}

	/**
	 * 年月日の出力関数
	 *
	 * @param string $strValue
	 */
	static function d( string $strValue ) {
		echo date( 'Y/m/d', strtotime( $strValue ) );
	}

	/**
	 * 年月日時分の出力関数
	 *
	 * @param string $strValue
	 */
	static function t( string $strValue ) {
		echo date( 'Y/m/d H:i', strtotime( $strValue ) );
	}

	/**
	 * 省略文字列取得関数
	 *
	 * @param string $strString 省略元の文字列
	 * @param int $intLength 省略までの文字数　デフォルト：[30]
	 * @param string $strAbbrCharcter 末尾に付加する省略文字　デフォルト：[...]
	 * @return string 省略語の文字列
	 */
	static function abbr( string $strString, int $intLength = 30, string $strAbbrCharcter = '…' ) {
		if ( mb_strlen( $strString ) > $intLength ) {
			echo htmlspecialchars( mb_substr( $strString, 0, $intLength - 1 ) . $strAbbrCharcter );
		} else {
			echo htmlspecialchars( $strString );
		}
	}

	/**
	 * POST値のフィルタリング
	 *
	 * @param string $strName POST名
	 * @param bool $blnSpecialChar htmlspecialchars（true:使用する false:使用しない）
	 * @param bool $blnTrim トリム（true:使用する false:使用しない）
	 * @param bool $blnBlank 全角空白を半角空白に置換（true:使用する false:使用しない）
	 * @return string POST値
	 */
	static function filterPost( string $strName, bool $blnSpecialChar = false, bool $blnTrim = false, bool $blnBlank = false ) {

		$arySearchData	 = array(
			"　",
		);
		$aryReplaceData	 = array(
			" ",
		);

		if ( $blnSpecialChar ) {
			$strPostData = filter_input( INPUT_POST, $strName, FILTER_SANITIZE_SPECIAL_CHARS );
		} else {
			$strPostData = filter_input( INPUT_POST, $strName );
		}

		//DOUPAは直接$_POSTに代入があるため考慮
		if ( is_null( $strPostData ) ) {
			if ( isset( $_POST ) ) {
				if ( array_key_exists( $strName, $_POST ) ) {
					$strPostData = $_POST[$strName];
				}
			}
		}

		if ( $strPostData !== false && isset( $strPostData ) ) {
			//リプレイス処理
			if ( $blnBlank ) {
				$strPostData = str_replace( $arySearchData, $aryReplaceData, $strPostData );
			}

			//トリム処理
			if ( $blnTrim ) {
				$strPostData = trim( $strPostData );
			}
		} else {
			$strPostData = "";
		}

		return $strPostData;
	}

	/**
	 * POST値の配列フィルタリング
	 *
	 * @param string $strName POST名
	 * @return string POST値
	 */
	static function filterAryPost( string $strName ) {

		$arySearchData	 = array(
			"　",
		);
		$aryReplaceData	 = array(
			" ",
		);

		$strPostData = filter_input( INPUT_POST, $strName, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		//DOUPAは直接$_POSTに代入があるため考慮
		if ( is_null( $strPostData ) ) {
			if ( isset( $_POST ) ) {
				if ( array_key_exists( $strName, $_POST ) ) {
					$strPostData = $_POST[$strName];
				}
			}
		}

		if ( $strPostData !== false && isset( $strPostData ) ) {
			
		} else {
			$strPostData = "";
		}

		return $strPostData;
	}

	/**
	 * GET値のフィルタリング
	 *
	 * @param string $strName GET名
	 * @param bool $blnSpecialChar htmlspecialchars（true:使用する false:使用しない）
	 * @return string GET値
	 */
	static function filterGet( string $strName, bool $blnSpecialChar = false ) {


		if ( $blnSpecialChar ) {
			$strPostData = filter_input( INPUT_GET, $strName, FILTER_SANITIZE_SPECIAL_CHARS );
		} else {
			$strPostData = filter_input( INPUT_GET, $strName );
		}

		//DOUPAは直接$_GETに代入があるため考慮
		if ( is_null( $strPostData ) ) {
			if ( isset( $_GET ) ) {
				if ( array_key_exists( $strName, $_GET ) ) {
					$strPostData = $_GET[$strName];
				}
			}
		}

		if ( $strPostData === false || !isset( $strPostData ) ) {
			$strPostData = "";
		}

		return $strPostData;
	}

	/**
	 * GET値の配列フィルタリング
	 *
	 * @param string $strName GET名
	 * @param bool $blnSpecialChar htmlspecialchars（true:使用する false:使用しない）
	 * @return string GET値
	 */
	static function filterAryGet( string $strName, bool $blnSpecialChar = false ) {


		$strPostData = filter_input( INPUT_GET, $strName, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		//DOUPAは直接$_GETに代入があるため考慮
		if ( is_null( $strPostData ) ) {
			if ( isset( $_GET ) ) {
				if ( array_key_exists( $strName, $_GET ) ) {
					$strPostData = $_GET[$strName];
				}
			}
		}

		if ( $strPostData === false || !isset( $strPostData ) ) {
			$strPostData = "";
		}

		return $strPostData;
	}

	/**
	 * SERVER値のフィルタリング
	 *
	 * @param string $strName POST名
	 * @param bool $blnSpecialChar htmlspecialchars（true:使用する false:使用しない）
	 * @return string SERVER値
	 */
	static function filterServer( string $strName, bool $blnSpecialChar = false ) {

		if ( $blnSpecialChar ) {
			$strPostData = filter_input( INPUT_SERVER, $strName, FILTER_SANITIZE_SPECIAL_CHARS );
		} else {
			$strPostData = filter_input( INPUT_SERVER, $strName );
		}

		if ( $strPostData === false || !isset( $strPostData ) ) {
			$strPostData = "";

			//IP取得の考慮
			if ( $strName == "HTTP_X_FORWARDED_FOR" ) {
				$strPostData = filter_input( INPUT_SERVER, "REMOTE_ADDR", FILTER_SANITIZE_SPECIAL_CHARS );
				if ( $strPostData === false || !isset( $strPostData ) ) {
					$strPostData = "";
				}
			}
		}

		return $strPostData;
	}

	/**
	 * 値取得用関数
	 * 
	 * @param string $search_element キー名
	 * @param array $aryData 格納する配列
	 * @param int $intRetFlg 値が無い場合の返却値
	 *              1:""
	 *              2:null
	 *              3:0
	 *              4:boolean
	 *              5:array
	 *              6:$strRetValの値
	 * @param string $strRetVal $intRetFlgが5の場合に使用
	 *              指定した値が返却される
	 * @return string 配値
	 */
	static function filterArray( string $search_element, array $aryData, int $intRetFlg = 1, string $strRetVal = "" ) {
		self::$_arySearchData = false;

		if ( isset( $aryData[$search_element] ) ) {
			return $aryData[$search_element];
		}

		$recursive_func = function ($search_element, $aryData, $intRetFlg, $strRetVal) use (&$recursive_func) {

			
			if ( is_array( $aryData ) ) {
				if ( array_key_exists( $search_element, $aryData ) ) {
					self::$_arySearchData = $aryData[$search_element];
					switch ( (int)$intRetFlg ) {
						case 1:
							return (string)self::$_arySearchData;
							break;
						case 3:
							return (int)self::$_arySearchData;
							break;
						default:
							return self::$_arySearchData;
							break;
					}
				} else {
					foreach ( $aryData as $key => $value ) {
						if ( is_array( $value ) ) {
							if ( $recursive_func( $search_element, $value, $intRetFlg, $strRetVal ) !== false ) {
								if ( self::$_arySearchData !== false )
									switch ( (int)$intRetFlg ) {
										case 1:
											return (string)self::$_arySearchData;
											break;
										case 3:
											return (int)self::$_arySearchData;
											break;
										default:
											return self::$_arySearchData;
											break;
									}
							}
						}
					}
				}
			}

			switch ( (int)$intRetFlg ) {
				case 1:
					return "";
					break;
				case 2:
					return null;
					break;
				case 3:
					return 0;
					break;
				case 4:
					return false;
					break;
				case 5:
					return array();
					break;
				case 6:
					return $strRetVal;
					break;
			}
			return "";
		};
		return $recursive_func( $search_element, $aryData, $intRetFlg, $strRetVal );
	}

}
