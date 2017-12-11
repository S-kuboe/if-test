<?php

/**
 * 	【モデル】お問い合わせ
 *
 * 	@author		kuboe 2017/12/02
 * 	@version		1.0
 */
class clsContact {

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
		$this->_aryDisp['post']			 = f::filterPost( 'post', FALSE );
		$this->_aryDisp['name']			 = f::filterPost( 'name', FALSE );
		$this->_aryDisp['tel']			 = f::filterPost( 'tel', FALSE );
		$this->_aryDisp['mail_address']	 = f::filterPost( 'mail_address', FALSE );
		$this->_aryDisp['subject']		 = f::filterPost( 'subject', FALSE );
		$this->_aryDisp['msg']			 = f::filterPost( 'msg', FALSE );

		$this->_aryDisp['strAction'] = f::filterPost( 'strAction', FALSE );

		return $this->_aryDisp;
	}
	
	/**
	 * 件名タグ作成
	 * 
	 * @param int $intSubject 選択値
	 * @return string
	 */
	public function getSubjectTag(int $intSubject = 0){
		$strSubjectTag = "";
		
		foreach(clsDefinition::$CONTACT_SUBJECT AS $key => $val){
			$strSubjectTag .= "<option value=" . $key . " " . clsCommonFunction::chkSelectedDate( $intSubject, $key ) . " >" . $val . "</option>";
		}
		
		return $strSubjectTag;
	}
	

}

?>