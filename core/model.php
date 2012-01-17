<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
class model {
	
	/* DATABASE OBJECT */
	public $db = null;
	
	public function __construct() {
		$this->db = database::instance();
		
		//are we allowed to connect to the database?
		if( enable_database == true ) {
			$this->db->connect();
		}
	}
}