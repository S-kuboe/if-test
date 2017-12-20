<?php

require(DIR_APP . '/vendor/autoload.php');

use transloadit\Transloadit;

class ctlDBConnectTest {

	private $_strHostName;
	private $_strUserName;
	private $_strPassword;
	private $_strDatabase;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		$url = parse_url( getenv( "JAWSDB_URL" ) );

		$this->_strHostName	 = $url["host"];
		$this->_strUserName	 = $url["user"];
		$this->_strPassword	 = $url["pass"];
		$this->_strDatabase	 = ltrim( $url["path"], '/' );
	}

	/**
	 * 画面処理分岐
	 */
	function processDBConnectTest() {

		$objClsDBConnectTest	 = new clsDBConnectTest();
		$aryPostData		 = $objClsDBConnectTest->pullConvertData();

		try {
			$conn = new PDO( "mysql:host=$this->_strHostName;dbname=$this->_strDatabase", $this->_strUserName, $this->_strPassword );
			// set the PDO error mode to exception
			$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			echo "Connected successfully<br />";
		} catch ( PDOException $e ) {
			echo "Connection failed: " . $e->getMessage() . "<br />";
		}

		$strWhereSql = <<< SQL
	SHOW TABLES 
	FROM
		{$this->_strDatabase} LIKE 'test_tbl';		
SQL;

		$prepare = $conn->prepare( $strWhereSql );
		$prepare->execute();
		$result	 = $prepare->fetchAll( PDO::FETCH_ASSOC );

		if ( count( $result ) == 0 ) {
			echo "該当テーブル無：新規作成<br />";
			//テーブル作成
			$strWhereSql = <<< SQL
	CREATE TABLE test_tbl ( 
		id INT PRIMARY KEY auto_increment
		, name VARCHAR (64)
		, upd_time DATETIME
	);	
SQL;

			$prepare = $conn->prepare( $strWhereSql );
			$prepare->execute();
		}

		echo "データ消去<br />";
		$strWhereSql = <<< SQL
	TRUNCATE test_tbl;
SQL;

		$prepare = $conn->prepare( $strWhereSql );
		$prepare->execute();

		echo "データインサート<br />";
//ダミーデータ
		for ( $i = 1; $i <= 3; $i++ ) {
			$strDate	 = date( "%Y-%m-%d H:i:s" );
			$strWhereSql = <<< SQL
	INSERT INTO test_tbl VALUES (null, 'user{$i}', '{$strDate}')
SQL;

			$prepare = $conn->prepare( $strWhereSql );
			$prepare->execute();
		}

		echo "値取得<br />";
		$strWhereSql = <<< SQL
	SELECT * FROM test_tbl
SQL;
		$prepare	 = $conn->prepare( $strWhereSql );
		$prepare->execute();
		$result		 = $prepare->fetchAll( PDO::FETCH_ASSOC );
		var_dump( $result );
	}

}

?>