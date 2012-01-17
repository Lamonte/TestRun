<?php 

/**
 * Configuration File
 *
 * This file is used to modify some of the global
 * settings that control the framework.
 * Editing these settings will control how some things
 * work throughout the framework.
 */
 
//uncomment to enable sessions
//session_start(); 

//Set to "true" to enable errors
define("enable_errors", true);

//Set to true to enable database connection 
define("enable_database", false);

//Set to true to enable 404 display
define("enable_404", false);

define("workspace", "dev");

define("default_controller", "example");

//Settings array
$settings['dev'] = array(
	'db' => array(
		'user' => '',
		'pass' => '',
		'host' => '',
		'tble' => '',
		'type' => 'Mysql',
	),
	'url' => 'http://localhost/minw2/',
);