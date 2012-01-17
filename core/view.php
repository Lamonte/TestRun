<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
class view {

	/* DATA ARRAY USED INSIDE VIEW FILES */
	private $_data 		= array();
	
	/* TEMPLATE FILE */
	private $_template 		= null;
	
	/* TEMPLATE FILE DATA */
	private $template_data	= null;
	
	/* SINGLETON VARIABLE */
	public static $instance 	= null;
	
	public function __construct( $template = 'body' ) {
		$this->template = $template;
	}
	
	public static function instance( $template = 'body' ) {
		
		$class = __CLASS__;
		if( is_null( self::$instance ) ) {
			self::$instance = new $class( $template );
		}
		
		return self::$instance;
	}
	
	/* SET DATA TO CLASS ARRAY */
	public function __set( $key, $value ) {
		$this->_data[$key] = $value;
	}
	
	/* GET DATA FROM CLASS ARRAY */
	public function __get( $key ) {
		return $this->_data[$key];
	}
	
	/* RETURN TEMPLATE IF CLASS PRINTED */
	public function __toString() {
		return $this->render(true);
	}
	
	/* RENDER TEMPLATE DATA */
	public function render( $return = false ) {
		
		//check if template file exists
		if( !file_exists( BASEDIR . "application/frontend/" . $this->template . ".php" ) ) {
			throw new Exception( "The following template view file couldn't be loaded: " . BASEDIR .  "application/frontend/" . $this->template . ".php" );
		}
		
		/* SAVE TEMPLATE DATA */
		
		//turn on output buffering 
		ob_start();
		
		//extract data
		extract( $this->_data );
		
		//include file
		require_once BASEDIR . "application/frontend/" . $this->template . ".php";
		
		//store template data into a variable
		$this->template_data = ob_get_contents();
		
		//erase the output buffer and turn off output buffering
		ob_end_clean();
		
		//do we want to output data or return it?
		if( $return == true ) {
			return $this->template_data;
		} else {
			echo $this->template_data;
		}
		
	}
	
}