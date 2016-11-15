<?php

class ControllerModuleQqConnect extends Controller {
	private $error = array(); 
	
	public function index() {

		$this->load->language('module/qq_connect');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('qq_connect', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['table'] = false;
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status'); 
		$this->data['entry_appid'] = $this->language->get('entry_appid');
		$this->data['entry_appkey'] = $this->language->get('entry_appkey'); 
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/qq_connect', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/qq_connect', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['qq_appid'])) {
			$this->data['qq_appid'] = $this->request->post['qq_appid'];
		} else {
			$this->data['qq_appid'] = $this->config->get('qq_appid');
		}
		
		if (isset($this->request->post['qq_appkey'])) {
			$this->data['qq_appkey'] = $this->request->post['qq_appkey'];
		} else {
			$this->data['qq_appkey'] = $this->config->get('qq_appkey');
		}

		if (isset($this->request->post['qq_connect'])) {
			$this->data['qq_connect'] = $this->request->post['qq_connect'];
		} else {
			$this->data['qq_connect'] = $this->config->get('qq_connect');
		}
				
		$this->template = 'module/qq_connect.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/qq_connect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	public function install() {
		$this->db->query( "CREATE TABLE  IF NOT EXISTS `" . DB_PREFIX . "qqconnect` (
			`connect_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`openid` varchar(255) NOT NULL COLLATE utf8_bin,
			PRIMARY KEY (`connect_id`),
			KEY `customer_id` (`customer_id`)
		)"); 
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "qqconnect`"); 
	}
}
?>