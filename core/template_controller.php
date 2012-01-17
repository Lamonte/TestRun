<?php

/**
 * @Copyright 	2011-2012
 * @Author	Lamonte Harris
 */
 
class template_controller extends controller {
	
	/* DEFAULT TEMPLATE */
	public $template_file 	= "body";
	
	/* FORCE OUTPUT */
	public $auto_render 		= true;
	
	/* TEMPLATE OBJECT */
	public $template 		= null;
	
	/* LOAD DEFAULT TEMPLATE */
	public function __construct() {
		parent::__construct();
		$this->template = new view( $this->template_file );
	}
	
	/* RENDER DATA */
	public function __render() {
		if( $this->auto_render == true ) {
			$this->template->render();
		}
	}
}