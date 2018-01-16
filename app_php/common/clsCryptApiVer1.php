<?php

class clsCryptApiVer1 {

	private $_strKey;
	private $_strIv;
	private $_blnCryptOpenType;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->_strKey	 = getenv( "ENV_CRYPT_KEY" );
		$this->_strIv	 = getenv( "ENV_CRYPT_IV" );

		$this->_blnCryptOpenType = getenv( "ENV_CRYPT_TYPE" );
	}

	/**
	 * 暗号化処理
	 * 
	 * @param string $strValue
	 * @return type
	 */
	public function setTypeEncrypt( string $strValue ) {

		$strResult = "";

		if ( $this->_blnCryptOpenType ) {
			$strResult = $this->setOpensslEncrypt( $strValue );
		} else {
			$strResult = $this->setEncrypt( $strValue );
		}

		return $strResult;
	}

	/**
	 * 復号化処理
	 * 
	 * @param string $strValue
	 * @return type
	 */
	public function setTypeDecrypt( string $strValue ) {

		$strResult = "";

		if ( $this->_blnCryptOpenType ) {
			$strResult = $this->setOpensslDecrypt( $strValue );
		} else {
			$strResult = $this->setDecrypt( $strValue );
		}

		return $strResult;
	}

	/**
	 * 暗号化処理（配列）
	 * ※一次元配列のみ
	 * 
	 * @param array $aryValue
	 * @return type
	 */
	public function setTypeAryEncrypt( array $aryValue ) {

		$aryResult = [];

		foreach ( $aryValue AS $key => $val ) {
			if ( $this->_blnCryptOpenType ) {
				$aryResult[$key] = $this->setOpensslEncrypt( $val );
			} else {
				$aryResult[$key] = $this->setEncrypt( $val );
			}
		}

		return $aryResult;
	}

	/**
	 * 復号化処理（配列）
	 * ※一次元配列のみ
	 * 
	 * @param array $aryValue
	 * @return string
	 */
	public function setTypeAryDecrypt( array $aryValue ) {

		$aryResult = "";

		foreach ( $aryValue AS $key => $val ) {
			if ( $this->_blnCryptOpenType ) {
				$aryResult[$key] = $this->setOpensslDecrypt( $val );
			} else {
				$aryResult[$key] = $this->setDecrypt( $val );
			}
		}

		return $aryResult;
	}

	/**
	 * AES/CBC/PKCS5Padding Encrypter
	 * 
	 * @param string $strValue 文字列
	 * @return return string
	 */
	function setEncrypt( string $strValue ) {

		//AES/CBC/PKCS5Padding
		mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC ), MCRYPT_RAND );
		$encryptedStr	 = mcrypt_encrypt(
				MCRYPT_RIJNDAEL_128, hex2bin( md5( $this->_strKey ) ), $this->pkcs5_pad( $strValue, mcrypt_get_block_size( MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC ) ), MCRYPT_MODE_CBC, $this->_strIv )
		;
		$strBin2hex		 = bin2hex( $encryptedStr );

		//BASE64エンコード
		$strBase64 = base64_encode( $strBin2hex );

		//URLエンコード
		$strUrl = urlencode( $strBase64 );

		return $strUrl;
	}

	/**
	 * AES/CBC/PKCS5Padding Encrypter
	 * 
	 * @param string $strValue 文字列
	 * @return return string
	 */
	function setDecrypt( string $strValue ) {

		//URLデコード
		$strUrl = urldecode( $strValue );

		//BASE64デコード
		$strBase64 = base64_decode( $strUrl );

		//AES/CBC/PKCS5Padding
		$strEncrypted = $this->pkcs5_unpad( mcrypt_decrypt(
						MCRYPT_RIJNDAEL_128, hex2bin( md5( $this->_strKey ) ), hex2bin( $strBase64 ), MCRYPT_MODE_CBC, $this->_strIv
				) );

		return $strEncrypted;
	}

	/**
	 * AES/CBC/PKCS5Padding Encrypter
	 * 
	 * @param string $strValue 文字列
	 * @return return string
	 */
	function setOpensslEncrypt( string $strValue ) {

		//AES/CBC/PKCS5Padding
		$encryptedStr = openssl_encrypt( $strValue, 'AES-128-CBC', $this->_strKey, OPENSSL_RAW_DATA, $this->_strIv );

		//BASE64エンコード
		$strBase64 = base64_encode( $encryptedStr );

		//URLエンコード
		$strUrl = urlencode( $strBase64 );

		return $strUrl;
	}

	/**
	 * AES/CBC/PKCS5Padding Decrypter
	 * 
	 * @param string $strValue 文字列
	 * @return return string
	 */
	function setOpensslDecrypt( string $strValue ) {

		//URLデコード
		$strUrl = urldecode( $strValue );

		//BASE64デコード
		$strBase64 = base64_decode( $strUrl );

		//AES/CBC/PKCS5Padding
		$strEncrypted = openssl_decrypt( $strBase64, 'AES-128-CBC', $this->_strKey, OPENSSL_RAW_DATA, $this->_strIv );

		return $strEncrypted;
	}

	/**
	 * パッディング
	 * 
	 * @param string $text
	 * @param string $blocksize
	 * @return type
	 */
	function pkcs5_pad( string $text, string $blocksize ) {
		$pad = $blocksize - (strlen( $text ) % $blocksize);
		return $text . str_repeat( chr( $pad ), $pad );
	}

	/**
	 * パッディング
	 * 
	 * @param string $text
	 * @return boolean
	 */
	function pkcs5_unpad( string $text ) {
		$pad = ord( $text{strlen( $text ) - 1} );
		if ( $pad > strlen( $text ) )
			return false;
		if ( strspn( $text, chr( $pad ), strlen( $text ) - $pad ) != $pad )
			return false;
		return substr( $text, 0, -1 * $pad );
	}

}

?>