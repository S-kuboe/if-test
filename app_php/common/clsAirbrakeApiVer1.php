<?php

/**
 * 【Heroku】Airbrake
 * 　エラー監視
 *
 * @author		kuboe 2017/12/06
 * @version	1.0
 */
require(DIR_APP . '/vendor/autoload.php');

class clsAirbrakeApiVer1 {

	//インスタンス
	private $_objNotifier;
	private $_objHandler;
	//パラメータ
	private $_strProjectId;
	private $_strProjectKey;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->_strProjectId	 = getenv( 'AIRBRAKE_PROJECT_ID' );
		$this->_strProjectKey	 = getenv( 'AIRBRAKE_API_KEY' );

		if ( $this->_strProjectId !== false && $this->_strProjectKey !== false ) {
			$this->_objNotifier = new Airbrake\Notifier( array(
				'projectId'	 => $this->_strProjectId,
				'projectKey' => $this->_strProjectKey,
					) );

			// Set global notifier instance.
			Airbrake\Instance::set( $this->_objNotifier );

			// Register error and exception handlers.
			$this->_objHandler = new Airbrake\ErrorHandler( $this->_objNotifier );
			$this->_objHandler->register();
		}
	}

//----------------------------------------------------------------------------------------------------------------------
// 共通
//----------------------------------------------------------------------------------------------------------------------
	/**
	 * エラーコード/エラー原因の取得
	 * 
	 * ソースからThorwを投げる場合の例
	 * 
	 * try{
	 * 	throw new Exception( 'hello from phpbrake' )
	 * } catch ( Exception $ex ) {
	 * 	clsAirbrakeApiVer1::setExceptionNotify( $ex );
	 * }
	 * 
	 * @param Exception $ex
	 */
	function setExceptionNotify( Exception $ex ) {
		Airbrake\Instance::notify( $ex );
	}

	/**
	 * エラーコード/エラー原因の取得
	 * 
	 * ソースからThorwを投げる場合の例
	 * 
	 * try{
	 * 	throw new Exception( 'hello from phpbrake' )
	 * } catch ( PDOException $ex ) {
	 * 	clsAirbrakeApiVer1::setPDOExceptionNotify( $ex );
	 * }
	 * 
	 * @param PDOException $ex
	 */
	function setPDOExceptionNotify( PDOException $ex ) {
		Airbrake\Instance::notify( $ex );
	}

}

?>