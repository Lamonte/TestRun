<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
class database {
	
	/* CLASS INSTANCE VARIABLE */
	public static $instance 	= null;
	
	/* DATABASE CLASS NAME */
	public $dbclass 		= null;
	
	/* ARE WE CONNECTED */
	public $connected 		= false;
	
	/* DATABASE VARIABLE SETTINGS */
	public $settings 		= null;
	
	/* CLASS SINGLETON METHOD */
	public static function instance() {
		
		//class name
		$class = __CLASS__;
		
		if( is_null( self::$instance ) ) {
			self::$instance = new $class();
		}
		
		return self::$instance;
	}
	
	private function __construct() {
		global $settings;
		
		//set global database setting variable 
		$this->settings = $settings[workspace]['db'];
		
		//load database class from settings
		$this->dbclass = ( isset( $this->settings['type'] ) && !empty( $this->settings['type'] ) ? $this->settings['type'] . "_database" : null );
		
		//make sure class exists 
		if( !class_exists( $this->dbclass ) ) {
			throw new Exception( "The following database class could not be loaded:" . $this->dbclass );
		}
		
		$this->dbclass = new $this->dbclass();
	}
	
	/* CONNECT US TO THE DATABSE */
	public function connect() {
		
		//try connecting to the database
		$this->dbclass->connect( $this->settings['host'], $this->settings['user'], $this->settings['pass'], $this->settings['tble'] );
		$this->connected = true;
		
	}
	
	/* CLOSE DATABASE */
	public function close() {
		$this->db->close();
		$this->connected = false;
	}
	
	/* QUERY SQL DATA */
	public function query( $query ) {
		$this->dbclass->query( $query );
	}
	
	/* RETRIEVE SQL DATA */
	public function fetch( $result_type = MYSQL_ASSOC ) {
		return $this->dbclass->fetch( $result_type );
	}
	
	/* RETRIEVE TOTAL TABLE ROWS */
	public function rows() {
		return $this->dbclass->rows();
	}
	
}