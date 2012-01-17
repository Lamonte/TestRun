<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */

 class Url {
	
	/* SINGLETON METHOD */
	public static $instance = null;
	private function __construct() {}
	public static function instance() {
	
		if( is_null( self::$instance ) ) {
			$class = __CLASS__;
			self::$instance = new $class(); 
		}
		
		return self::$instance;
	}
	
	public function addressbar() {
		return "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	
 }