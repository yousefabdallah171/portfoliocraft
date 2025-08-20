<?php

if( !defined( 'ABSPATH' ) )
	exit; 

class portfoliocraft_Admin_Templates extends portfoliocraft_Base{

	public function __construct() {
		// Removed admin_menu action since templates now have their own menu item
	}
 
	// Removed register_page method since templates now have their own menu item
}
new portfoliocraft_Admin_Templates;
