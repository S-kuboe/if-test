<?php

require_once( DIR_VAL . '/clsValidateChecker.php' );

class clsHostlookChecker extends clsValidateChecker {

	public function execute() {

		//検査対象のnameを配列で登録する。
		$aryCheckNames = array(
			'company',
			'domain',
			'tel',
			'mail_address',
			'ipinfo',
			'comment'
		);

		foreach ( $aryCheckNames as $strName ) {

			$strValue = $this->getValue( $strName );

			switch ( $strName ) {
				case 'company':
					if ( true == $this->isNotEmpty( $strValue ) ) {
						if ( false == $this->isNotHankakuKatakana( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_NOT_H_KATAKANA );
							$this->setError();
						} else {
							// 最大文字数チェック
							if ( false == $this->chkMaxLength( $strValue, 60 ) ) {
								$this->setErrorMessage( $strName, parent::ERROR_MAX_VALUE . '60文字' );
								$this->setError();
							}
						}
					}
					break;

				case 'domain':
					if ( true == $this->isNotEmpty( $strValue ) ) {
						if ( false == $this->isNotHankakuKatakana( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_NOT_H_KATAKANA );
							$this->setError();
						} else {
							// 最大文字数チェック
							if ( false == $this->chkMaxLength( $strValue, 60 ) ) {
								$this->setErrorMessage( $strName, parent::ERROR_MAX_VALUE . '60文字' );
								$this->setError();
							}
						}
					}
					break;

				case 'tel':
					if ( true == $this->isNotEmpty( $strValue ) ) {
						if ( false == $this->isNotHankakuKatakana( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_NOT_H_KATAKANA );
							$this->setError();
						} else {
							// 最大文字数チェック
							if ( false == $this->chkMaxLength( $strValue, 60 ) ) {
								$this->setErrorMessage( $strName, parent::ERROR_MAX_VALUE . '60文字' );
								$this->setError();
							}
						}
					}
					break;

				case 'mail_address':
					if ( true == $this->isNotEmpty( $strValue ) ) {
						if ( false == $this->isNotHankakuKatakana( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_NOT_H_KATAKANA );
							$this->setError();
						} else {
							// 最大文字数チェック
							if ( false == $this->chkMaxLength( $strValue, 60 ) ) {
								$this->setErrorMessage( $strName, parent::ERROR_MAX_VALUE . '60文字' );
								$this->setError();
							}else{
//								if ( false == $this->isMailAddress( $strValue ) ) {
//									$this->setErrorMessage( $strName, parent::ERROR_MAIL_ADDRESS );
//									$this->setError();
//								}								
							}
						}
					}
					break;

				case 'ipinfo':
					if ( false == $this->isNotEmpty( $strValue ) ) {
						$this->setErrorMessage( $strName, parent::ERROR_SELECTED );
						$this->setError();
					}
					break;

				case 'comment':
					if ( true == $this->isNotEmpty( $strValue ) ) {
						if ( false == $this->isNotHankakuKatakana( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_NOT_H_KATAKANA );
							$this->setError();
						}
					}
					break;

				default:
					break;
			}
		}

		return !$this->isError();
	}

}

?>
