<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
/* BASE DIRECTORY CONSTANT */
define( "BASEDIR", str_replace("\\", "/", dirname(__FILE__) ) . "/" );

/* INCLDE CONFIG SETTINGS */
require_once BASEDIR . "application/config.php";

/* AUTOLOAD CLASSES */
function __autoload( $classname ) {
	
	$folders = array(
		'core/',
		'core/db/',
		'application/controllers/',
		'application/libraries/',
		'application/models/'
	);
	
	foreach( $folders as $folder ) {
		if( file_exists( BASEDIR . $folder . strtolower( $classname ) . ".php" ) ) {
			require_once BASEDIR . $folder . strtolower( $classname ) . ".php";
			return true;
		}
	}
}

/* INCLUDE INJECT FILE */
require_once BASEDIR . "application/inject.php";

//Remap current url 
Remapper::initiate();

//Load controllers
try {
	Request::controllers();
} catch ( Exception $e ) {
	echo $e->getMessage();
}