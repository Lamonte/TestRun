<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */

class Request {
	
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
	
	/* LOAD CONTROLLERS */
	public function controllers() {
	
			$controller 	= self::get( "_controller" );
			$action 		= self::get( "_action" );
			$params 		= self::get( "_params" );
			
			$controller 	= empty( $controller ) ? default_controller : $controller;
			$action 		= empty( $action ) ? "index" : $action;
			$params 		= empty( $params ) || !is_array( $params ) ? array() : $params;
			
			//fix up action name
			$action 		= "action_" . $action;
			
			$controller_file 		= BASEDIR . "application/controllers/" . strtolower($controller) . ".php";
			$controller_file_basic 	= "application/controllers/" . strtolower($controller) . ".php";
			$controller_class 	= $controller . "_controller";
			
			if( !file_exists ( $controller_file ) ) {
				
				/* add 404 code here  */
				
				//throw exception
				throw new Exception( "The following controller file does not exist: " . $controller_file_basic );
			}
			
			require_once $controller_file;
			
			//make sure controller class exists 
			if( !class_exists( $controller_class ) ) {
				
				/* add 404 code here */
				
				throw new Exception( "The following controller class doesn't exist: " . $controller_class );
			}
			
			$controller = new $controller_class();
			
			//make sure controller action exists
			if( !method_exists( $controller, $action ) ) {
			
				/* add 404 code here */
				
				throw new Exception( "The following controller action does not exists: " . $action );
			}
			
			//load the class method and send the params to the function parameters
			call_user_func_array( array( $controller, $action ), $params );
			
			//check if parent class is template controller
			$is_template_child = is_a( $controller, "Template_Controller" ) ? true : false;
			
			//render template file if class is child of the template controller
			if( $is_template_child ) {
				$controller->__render();
			}
			
	}
	
	/**
	 * Post data
	 * - Returns post data
	 */
	public function post( $input ) {
		return self::data( "POST", $input );
	}
	
	/**
	 * Raw post data
	 * - Returns raw post data that isn't sanitized (htmlentities stripped)
	 */
	public function postr( $input ) {
		return self::data( "POSTR", $input );
	}
	
	/**
	 * Get data
	 * - Returns get data
	 */
	public function get( $input ) {
		return self::data( "GET", $input );
	}
	
	/**
	 * Return GET/POST data
	 * - Returns get/post data that has been sanitized thoroughly
	 */
	private function data( $type, $input, $xss = true ) {
		
		switch( $type ) {
		
			case "GET": 
				
				//Clean up data
				self::instance()->clean( $_GET[$input], true, false, true );
				
				return $_GET[$input];
				
			break;
			
			case "POST":
				
				//Clean up data
				self::instance()->clean( $_POST[$input], true, true );
				
				return $_POST[$input];
				
			break;
			
			case "POSTR":
				
				//Clean up data
				self::instance()->clean( $_POST[$input], true, true );
				
				return $_POST[$input];
				
			break;
		}
	}
	
	/**	
	 * Clean GET/POST values 
	 */
	private function clean( &$data, $xss = true, $entity = false, $urldecode = false ) {
		
		if( is_array( $data ) ) {
		
			foreach( $data as $key => $value ) {
				$data[$key] = $this->clean( $value, $xss, $entity, $urldecode );
			}
			
		} else { 
			
			$data = trim( $data );
			$data = ( get_magic_quotes_gpc() ? stripslashes( $data ) : $data );
			
			if( $entity == true ) {
				$data = htmlentities( $data );
			}
			
			if( $urldecode == true ) {
				$data = urldecode( $data );
			}
			
			if( $xss == true ) {
				$data = self::remove_xss( $data );
			}
			
			return $data;
		}
	}
	
	private function remove_xss( $data ) {
		// +----------------------------------------------------------------------+
		// | Copyright (c) 2001-2006 Bitflux GmbH                                 |
		// +----------------------------------------------------------------------+

		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);
		
		return $data;
	}
}