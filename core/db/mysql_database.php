<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
class mysql_database {
	
	/* MYSQL DATABASE CONNECTION */
	public $connect 	= null;
	
	/* MYSQL QUERY RESULT */
	public $result 		= null;
	
	/* MYSQL DAABASE TABLE SELECT */
	public $table 		= null;
	
	/* CONNECT TO MYSQL DATABASE */
	public function connect( $host, $user, $pass, $tble ) {
		
		//make sure we connect to the database
		$this->connect = msyql_connect( $host, $user, $pass );
		if( !$this->connect ) {
			throw new Exception( "Could not connect to the database: " . mysql_error() );
		}
		
		//try selecting the database table once connecting
		$this->table = mysql_select_db( $tble, $this->connect );
		if( !$this->table ) {
			throw new Exception( "Could not select database table: " . mysql_error() );
		}
		
	}
	
	/* QUERY MYSQL DATA */
	public function query( $query ) {
		
		$this->result = mysql_query( $query );
		if( !$this->result ) {
			throw new Exception( "Could not parse query: " . mysql_error() );
		}
		
		return $this;
		
	}
	
	/* RETRIEVE QUERY DATA */
	public function fetch( $result_type = MYSQL_ASSOC ) {
		return mysql_fetch_array( $this->result, $result_type );
	}
	
	/* RETRIEVE TOTAL TABLE ROWS FROM A QUERY */
	public function rows() {
		return mysql_num_rows( $this->result );
	}
	
	/* CLOSE CONNECT */
	public function close() {
		return mysql_close( $this->connect );
	}
	
}