<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */

class Remapper {

	public static $masks = array();
	
	/**
	 * Put it all together
	 */
	public function initiate() {
		self::general();
		self::init_masking();
	}
	
	/**
	 * General Remapping
	 * - Remaps url data into get data allowing us to access controllers/actions
	 */
	public function general() {
		global $settings;
		
		//get the current url from the address bar
		$current_url = Url::addressbar();
		
		//remove any get data from the url 
		$current_url = preg_replace( "/\?.*/i", "", $current_url );
		
		//make sure url has a forward slash at the end
		$current_url = preg_replace( "/\/$/i", "", $current_url ) . "/";
		
		//remove settings url from address bar url 
		$current_url = str_replace( $settings[workspace]['url'], "", $current_url );
		
		//now replace the forward slash at the end
		$current_url = preg_replace( "/^\/|\/$/i", "", $current_url );
		
		//explode url into an array now
		$current_url = @explode( "/", $current_url );
		
		$_GET['_controller']= ( isset( $current_url[0] ) && !empty( $current_url[0] ) ? $current_url[0] : null );
		$_GET['_action'] 	= ( isset( $current_url[1] ) && !empty( $current_url[1] ) ? $current_url[1] : null );
		$_GET['_params'] 	= null;
		
		unset( $current_url[0] );
		unset( $current_url[1] );
		
		//make sure you get the params and add them .
		if( !empty( $current_url ) ) {
			$temp_array = array();
			
			foreach( $current_url as $array ) {
				$temp_array[] = $array;
			}
			
			$_GET['_params'] = $temp_array;
		}
		
	}
	
	public function init_masking() {
	
		foreach( self::$masks as $mask ) {
			
			if( empty( $mask[1] ) ) continue;
			
			$addr = url::addressbar();
			$addr = preg_replace( "/\?.*$/", "", $addr );
			
			//are we using regular expressions or not?
			if( $mask[0] == true ) {
				
				if( preg_match( "/(" . $mask[1] . ")$/i", $addr, $matches ) ) {
				
					unset( $matches[0] ); 
					unset( $matches[1] );
					
					$values = array();
					
					//replace regular expression values
					$count = 1;
					foreach( $matches as $match ) {
					
						foreach( $mask[2]['params'] as $key => $val ) {
						
							$mask[2]['params'][$key] = str_replace( '$' . $count, $match, $mask[2]['params'][$key] );
							
						}
						
						++$count;
					}
					
					$_GET['_controller']= $mask[2]['controller'];
					$_GET['_action'] 	= $mask[2]['action'];
					$_GET['_params'] 	= $mask[2]['params'];
					
				}
				
			} else {
				
				if( preg_match( "/(" . preg_quote( $mask[1], "/" ) . ")$/i", $addr ) ) {
					
					$_GET['_controller']= $mask[2]['controller'];
					$_GET['_action'] 	= $mask[2]['action'];
					$_GET['_params'] 	= $mask[2]['params'];
					
				}
				
			}
		}
	}
	
	/**
	 * Set url mask
	 * - Setting up url masks allows you to manipulate the url to your likings
	 */
	public function set_mask( $regex, $match, $controller_data ) {
		self::$masks[] = array( $regex, $match, $controller_data );
	}
}