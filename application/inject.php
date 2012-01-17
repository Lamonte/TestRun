<?php

/** 
 * URL Remapping examples
 - Below are examples of how to remap urls to controllers with ease (regular expression example)
 *
Remapper::set_mask( false, "contact.html", array(
	'controller' => 'example',
	'action' => 'test',
	'params' => array(
		'test'
	)
));

Remapper::set_mask( true, "article-(\d+)-(\d+)\.html", array(
	'controller' => 'articles',
	'action' => 'article',
	'params' => array(
		'$1', '$2'
	)
));
*/