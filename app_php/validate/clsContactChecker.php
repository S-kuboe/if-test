<?php

require_once( DIR_VAL . '/clsValidateChecker.php' );

class clsContactChecker extends clsValidateChecker {

	public function execute() {

		//検査対象のnameを配列で登録する。
		$aryCheckNames = array(
			'company',
			'post',
			'name',
			'tel',
			'mail_address',
			'subject',
			'msg'
		);

		foreach ( $aryCheckNames as $strName ) {

			$strValue = $this->getValue( $strName );

			switch ( $strName ) {
				case 'company':
					if ( false == $this->isNotEmpty( $strValue ) ) {
						$this->setErrorMessage( $strName, parent::ERROR_NOT_EMPTY );
						$this->setError();
					}
					break;

				case 'post':
					break;

				case 'name':
					if ( false == $this->isNotEmpty( $strValue ) ) {
						$this->setErrorMessage( $strName, parent::ERROR_NOT_EMPTY );
						$this->setError();
					}
					break;

				case 'tel':
					if ( false == $this->isNotEmpty( $strValue ) ) {
						$this->setErrorMessage( $strName, parent::ERROR_NOT_EMPTY );
						$this->setError();
					} else {
						if ( false == $this->isPhoneNumber2( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_PHONE_NUMBER2 );
							$this->setError();
						}
					}
					break;

				case 'mail_address':
					if ( false == $this->isNotEmpty( $strValue ) ) {
						$this->setErrorMessage( $strName, parent::ERROR_NOT_EMPTY );
						$this->setError();
					} else {
						if ( false == $this->isMailAddress( $strValue ) ) {
							$this->setErrorMessage( $strName, parent::ERROR_MAIL_ADDRESS );
							$this->setError();
						}
					}
					break;

				case 'subject':
					if ( 0 === (int)$strValue ) {
						$this->setErrorMessage( $strName, parent::ERROR_SELECTED );
						$this->setError();
					}
					break;

				case 'msg':
					if ( false == $this->isNotEmpty( $strValue ) ) {
						$this->setErrorMessage( $strName, parent::ERROR_NOT_EMPTY );
						$this->setError();
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
