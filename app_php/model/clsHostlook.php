<?php

/**
 * 	【モデル】ホスト情報・照会
 *
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
class clsHostlook {

	private $_aryDisp;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		
	}

	/**
	 * POSTデータ整頓
	 */
	public function pullConvertData() {

		$this->_aryDisp['company']		 = f::filterPost( 'company', FALSE );
		$this->_aryDisp['domain']		 = f::filterPost( 'domain', FALSE );
		$this->_aryDisp['tel']			 = f::filterPost( 'tel', FALSE );
		$this->_aryDisp['mail_address']	 = f::filterPost( 'mail_address', FALSE );
		$this->_aryDisp['ipinfo']		 = f::filterPost( 'ipinfo', FALSE );
		$this->_aryDisp['comment']		 = f::filterPost( 'comment', FALSE );

		$this->_aryDisp['remote_addr']	 = f::filterPost( 'remote_addr', FALSE );
		$this->_aryDisp['remote_host']	 = f::filterPost( 'remote_host', FALSE );

		$this->_aryDisp['strToken']	 = f::filterPost( 'strToken', FALSE );
		$this->_aryDisp['strAction'] = f::filterPost( 'strAction', FALSE );

		return $this->_aryDisp;
	}

	/**
	 * IP種別タグ作成
	 * 
	 * @param int $intIpinfo 選択値
	 * @return string
	 */
	public function getIpInfoTag( int $intIpinfo = 0 ) {
		$strIpinfoTag = "";

		foreach ( clsDefinition::$HOSTLOOK_IPINFO AS $key => $val ) {
			$strIpinfoTag .= "<option value=" . $key . " " . clsCommonFunction::chkSelectedDate( $intIpinfo, $key ) . " >" . $val . "</option>";
		}

		return $strIpinfoTag;
	}

}

?>