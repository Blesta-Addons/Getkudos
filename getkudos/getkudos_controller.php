<?php
/**
 * getkudos Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.getkudos
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
 
class GetkudosController extends AppController {
	/**
	 * Setup
	 */
	public function preAction() {
		$this->structure->setDefaultView(APPDIR);
		parent::preAction();
		
		// Override default view directory
		$this->view->view = "default";
		$this->orig_structure_view = $this->structure->view;
		$this->structure->view = "default";
	}
}
?>