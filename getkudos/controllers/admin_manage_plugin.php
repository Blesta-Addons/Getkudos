<?php
/**
 * getkudos Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.getkudos
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
 
class AdminManagePlugin extends AppController {
	
	/**
	 * Performs necessary initialization
	 */
	private function init() {
		// Require login
		$this->parent->requireLogin();
		
		$this->uses(array("Getkudos.Getkudos"));		
		$this->uses(array("Companies"));		
		// Set the company ID
		$this->company_id = Configure::get("Blesta.company_id");
		
		// Set the plugin ID
		$this->plugin_id = (isset($this->get[0]) ? $this->get[0] : null);
		
		$this->GetkudosSettings = $this->Companies->getSetting($this->company_id , "GetkudosPlugin");
		// Restore structure view location of the admin portal
		$this->structure->setDefaultView(APPDIR);
		$this->structure->setView(null, $this->structure->view);
		
		// Set the page title
		$this->parent->structure->set("page_title", Language::_("GetkudosPlugin.admin_main", true));
		
	
		
	}
	
	/**
	 * Returns the view to be rendered when managing this plugin
	 */
	public function index() {
		$this->init();

		if (!empty($this->post)) {
			
			if (!$this->Getkudos->getkudos_check_site_name($this->post["site_name"])) {
				$this->parent->setMessage("error", Language::_("GetkudosPlugin.!error.sitename", true) , false, null, false);
			} else {
				$this->Companies->setSetting($this->company_id , "GetkudosPlugin", serialize($this->post)  );
				$this->parent->setMessage("success", Language::_("GetkudosPlugin.!success.settings_saved", true) , false, null, false);
				
				$this->GetkudosSettings =  $this->Companies->getSetting($this->company_id , "GetkudosPlugin");	
			}

		}
		// print_r($this->GetkudosSettings['site_name']);
		$vars = array(
			'plugin_id'=> $this->plugin_id,
			'settings'=> unserialize($this->GetkudosSettings->value)
		);
		
		$this->view->setView(null, "Getkudos.default");	
		return $this->partial("admin_manage_plugin", $vars);
		

	}
}	
?>