<?php

/**
 * 【コントローラ】	Jaswdbアドオンを使用したサンプル処理
 * 					Heroku Postgresアドオンを使用したサンプル処理
 *
 * 	@version	1.0
 */
require(DIR_APP . '/vendor/autoload.php');

class ctlDBConnect extends preApp {

	//クラス変数
	private $_strMysqlHostName;
	private $_strMysqlUserName;
	private $_strMysqlPassword;
	private $_strMysqlDatabase;
	private $_strPostgresHostName;
	private $_strPostgresUserName;
	private $_strPostgresPassword;
	private $_strPostgresDatabase;
	private $_strPostgresPort;

	/**
	 * コンストラクタ
	 */
	function __construct() {
		parent::__construct();

		//JAWSDB接続情報の設定
		$aryMySQL = parse_url( getenv( "JAWSDB_URL" ) );

		$this->_strMysqlHostName = $aryMySQL["host"];
		$this->_strMysqlUserName = $aryMySQL["user"];
		$this->_strMysqlPassword = $aryMySQL["pass"];
		$this->_strMysqlDatabase = ltrim( $aryMySQL["path"], '/' );

		//Postgres接続情報の設定
		$aryPostgres = parse_url( getenv( "DATABASE_URL" ) );

		$this->_strPostgresHostName	 = $aryPostgres["host"];
		$this->_strPostgresUserName	 = $aryPostgres["user"];
		$this->_strPostgresPassword	 = $aryPostgres["pass"];
		$this->_strPostgresDatabase	 = ltrim( $aryPostgres["path"], '/' );
		$this->_strPostgresPort		 = $aryPostgres["port"];
	}

	/**
	 * 画面処理分岐
	 * 　データベース処理
	 */
	function processDBConnectMysql() {

		//オブジェクト作成
		$objClsDBConnectMySql	 = new clsDBConnect();
		$aryPostData			 = $objClsDBConnectMySql->pullConvertData();

		try {
			$conn = new PDO( "mysql:host=$this->_strMysqlHostName;dbname=$this->_strMysqlDatabase", $this->_strMysqlUserName, $this->_strMysqlPassword );
			// set the PDO error mode to exception
			$conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			echo "Connected successfully<br />";
		} catch ( PDOException $ex ) {
			clsAirbrakeApiVer1::setPDOExceptionNotify( $ex );
			echo "Connection failed: " . $ex->getMessage() . "<br />";
		}

		$strWhereSql = <<< SQL
	SHOW TABLES 
	FROM
		{$this->_strMysqlDatabase} LIKE 'test_tbl';		
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
			$strDate	 = date( "Y-m-d H:i:s" );
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

		//オブジェクト解放
		unset( $objClsDBConnectMySql );
	}

	/**
	 * 画面処理分岐
	 * 　データベース処理
	 */
	function processDBConnectPostgres() {

		//オブジェクト作成
		$objClsDBConnectPostgres = new clsDBConnect();
		$aryPostData			 = $objClsDBConnectPostgres->pullConvertData();

		try {
			$conn = new PDO( "pgsql:host=$this->_strPostgresHostName;dbname=$this->_strPostgresDatabase;port=$this->_strPostgresPort", $this->_strPostgresUserName, $this->_strPostgresPassword );
			// set the PDO error mode to exception
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			echo "Connected successfully<br />";
		} catch ( PDOException $ex ) {
			clsAirbrakeApiVer1::setPDOExceptionNotify( $ex );
			echo "Connection failed: " . $ex->getMessage() . "<br />";
		}

		$strWhereSql = <<< SQL
	SELECT
		relname
		, *
	FROM
		pg_class 
	WHERE
		relkind = 'r'
		AND relname = 'test_tbl'
SQL;

		$prepare = $conn->prepare( $strWhereSql );
		$prepare->execute();
		$result	 = $prepare->fetchAll( PDO::FETCH_ASSOC );

		if ( count( $result ) == 0 ) {
			echo "該当テーブル無：新規作成<br />";
			//テーブル作成
			$strWhereSql = <<< SQL
	CREATE TABLE test_tbl( 
		id SERIAL PRIMARY KEY
		, name VARCHAR (64)
		, upd_time TIMESTAMP
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
			$strDate	 = date( "Y-m-d H:i:s" );
			$strWhereSql = <<< SQL
	INSERT INTO test_tbl (name, upd_time) VALUES ('user{$i}', '{$strDate}')
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

		//オブジェクト解放
		unset( $objClsDBConnectPostgres );
	}

}

?>