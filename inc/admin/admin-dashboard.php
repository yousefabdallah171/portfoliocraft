<?php
/**
* The portfoliocraft_Admin_Dashboard base class
*/

if( !defined( 'ABSPATH' ) )
	exit; 

class portfoliocraft_Admin_Dashboard extends portfoliocraft_Admin_Page {
	protected $id = null;
	protected $page_title = null;
	protected $menu_title = null;
	public $position = null;
	public function __construct() {
		$this->id = 'pxlart';
		$this->page_title = portfoliocraft()->get_name();
		$this->menu_title = portfoliocraft()->get_name();
		$this->position = '50';

		parent::__construct();
	}

	public function display() {
		get_template_part( 'inc/admin/views/admin-dashboard' );
	}
 
	public function save() {

	}
}
new portfoliocraft_Admin_Dashboard;
