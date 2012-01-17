<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
class Controller {
	
	/* DATABASE OBJECT */
	public $db = null;
	
	/* MODEL OBJECT ARRAY */
	public $model = null;
	
	public function __construct() {
		
		//try connecting to the database if it's enabled
		if( enable_database == true ) {
			$this->db = database::instance();
			$this->db->connect();
		}
		
	}
	
	/* LOAD MODEL CLASS INTO CONTROLLER */
	public function load_model( $model ) {
		
		//model class name
		$model = "Model_" . $model;
		
		if( !class_exists( $model ) ) {
			throw new Exception( "The following model class could not be loaded: " . $model );
		}
		
		$this->model->{$model} = new $model();
	}
	
	/* LOAD MULTIPLE MODEL CLASSES */
	public function load_models( array $models ) {
		
		//load multiple models by looping throw an array and reusing our code
		foreach( $models as $model ) {
			$this->load_model( $model );
		}
	}
}