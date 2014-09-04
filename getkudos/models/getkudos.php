<?php
/**
 * getkudos Plugin 
 * 
 * @package blesta
 * @subpackage blesta.plugins.getkudos
 * @copyright Copyright (c) 2005, Naja7host SARL.
 * @link http://www.naja7host.com/ Naja7host
 */

class Getkudos extends GetkudosModel {
	
	/**
	 * Initialize
	 */
	public function __construct() {
		parent::__construct();
		
		// Language::loadLang("knowledgebase_categories", null, PLUGINDIR . "knowledgebase" . DS . "language" . DS);
	}
	
	public function getkudos_check_site_name($siteName) {
		$post_url = 'https://www.getkudos.me/'.$siteName.'/post';
		
		$headers = get_headers($post_url);
		$response = substr($headers[0], 9, 3);
		
		if ( $response == 200 ) {
			return true;
		} else {
			return false;
		}
	}	
}
?>