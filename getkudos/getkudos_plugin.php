<?php
/**
 * getkudos Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.getkudos
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */
 
class GetkudosPlugin extends Plugin {

	public function __construct() {
		Language::loadLang("getkudos", null, dirname(__FILE__) . DS . "language" . DS);
		
		// Load components required by this plugin
		Loader::loadComponents($this, array("Input", "Record"));
			
        // Set the company ID
        $this->company_id = Configure::get("Blesta.company_id");
		
        // Load modules for this plugun
        Loader::loadModels($this, array("ModuleManager", "Companies"));

		$this->loadConfig(dirname(__FILE__) . DS . "config.json");
	}
	
	/**
	 * Performs any necessary bootstraping actions
	 *
	 * @param int $plugin_id The ID of the plugin being installed
	 */
	public function install($plugin_id) {
			
		// Add the system overview table, *IFF* not already added
		try {
			$value = array('site_name' => "your_site_name_here" );
			$this->Companies->setSetting($this->company_id , "AnnouncementsPlugin", serialize($value) );		
		}
		catch(Exception $e) {
			// Error adding... no permission?
			// $this->Input->setErrors(array('db'=> array('create'=>$e->getMessage())));
			return;
		}
	}
	
    /**
     * Performs migration of data from $current_version (the current installed version)
     * to the given file set version
     *
     * @param string $current_version The current installed version of this plugin
     * @param int $plugin_id The ID of the plugin being upgraded
     */
	public function upgrade($current_version, $plugin_id) {
		
		// Upgrade if possible
		if (version_compare($this->getVersion(), $current_version, ">")) {
			// Handle the upgrade, set errors using $this->Input->setErrors() if any errors encountered
		}
	}
	
    /**
     * Performs any necessary cleanup actions
     *
     * @param int $plugin_id The ID of the plugin being uninstalled
     * @param boolean $last_instance True if $plugin_id is the last instance across all companies for this plugin, false otherwise
     */
	public function uninstall($plugin_id, $last_instance) {
		if (!isset($this->Record))
			Loader::loadComponents($this, array("Record"));
		
		// Remove all tables *IFF* no other company in the system is using this plugin
		if ($last_instance) {
			try {
			
				$this->Companies->unsetSetting($this->company_id , "GetkudosPlugin");
			}
			catch (Exception $e) {
				// Error dropping... no permission?
				// $this->Input->setErrors(array('db'=> array('create'=>$e->getMessage())));
				return;
			}
		}
 
	}

	
 
    public function getEvents() {
        return array(
            array(
                'event' => "Appcontroller.structure",
                'callback' => array("this", "widgets_init")
            )
            // Add multiple events here
        );
    }
 
    public function widgets_init($event) {

		$params = $event->getParams();
		$return = $event->getReturnVal();
			
        // Set return val if not set
        if (!isset($return['body_end']))
                $return['body_end'] = null;
				
        // Update return val -- ONLY set if client portal
        if ($params['portal'] == "client") {
		
		Loader::loadModels($this, array("Getkudos.Getkudos"));
		$this->GetkudosSettings = $this->Companies->getSetting($this->company_id , "GetkudosPlugin");
		$settings = unserialize($this->GetkudosSettings->value) ;
		
			$return['body_end'] .= "
				<!-- Start of GetKudos Script -->
				<script>
				(function(w,t,gk,d,s,fs){if(w[gk])return;d=w.document;w[gk]=function(){
				(w[gk]._=w[gk]._||[]).push(arguments)};s=d.createElement(t);s.async=!0;
				s.src='//static.getkudos.me/widget.js';fs=d.getElementsByTagName(t)[0];
				fs.parentNode.insertBefore(s,fs)})(window,'script','getkudos');

				getkudos('create', '".$settings['site_name']."');
				</script>
				<!-- End of GetKudos Script -->				
			" ;
		}
		$event->setReturnVal($return);	
		
	}
	
	/**
	 * Returns all actions to be configured for this widget (invoked after install() or upgrade(), overwrites all existing actions)
	 *
	 * @return array A numerically indexed array containing:
	 * 	-action The action to register for
	 * 	-uri The URI to be invoked for the given action
	 * 	-name The name to represent the action (can be language definition)
	 */
	public function getActions() {

	}

	
	/**
	 * Execute the cron task
	 *
	 */

	public function cron($key) {
		// Todo a task 
	}

	
	/**
	 * Attempts to add new cron tasks for this plugin
	 *
	 */

	private function addCronTasks(array $tasks) {
		// TODO
	}	
	
}
?>