<?php 
class ControllerAccountQQConnect extends Controller {
	private $error = array();
	
	public function index() {
		
		if ((!$this->request->server['REQUEST_METHOD'] == 'POST') || 
			empty($this->request->post['openid']) || 
				$this->config->get('qq_connect') !== '1') {
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	}
	 
		
    	$this->language->load('account/qq_connect');
		$this->load->model('account/customer');
		$this->load->model('account/qq_connect');
    	if ($this->model_account_qq_connect->CheckTotalOpenid($this->request->post['openid'])) {
			unset($this->session->data['guest']);
			
			$getinfo = $this->model_account_qq_connect->getInfo($this->request->post['openid']);
		
			$this->session->data['customer_id'] = $getinfo['customer_id'];
			
	  		$this->redirect($this->url->link('common/home'));
    	}
		
		if (isset($this->request->post['bind_email']) && isset($this->request->post['bind_password']) && $this->validate()) {
			if ($this->customer->isLogged() && !$this->model_account_qq_connect->CheckTotalOpenid('', $this->customer->isLogged())){
				$this->model_account_qq_connect->bindCustomer($this->request->post, $this->customer->isLogged());
	  			$this->redirect($this->url->link('common/home'));
			} else {
      			$this->customer->logout();
				$this->error['warning'] = $this->language->get('error_openid');
			}
		}
		
		if (isset($this->request->post['create_email']) && $this->validate2()) {
		
			$this->model_account_qq_connect->addCustomer($this->request->post);
		
			$this->customer->login($this->request->post['create_email'], $this->request->post['create_password']);
			
			$this->redirect($this->url->link('common/home'));
		}

    	$this->document->setTitle($this->language->get('heading_title'));
		
      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	);
  
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_connect'),
			'href'      => $this->url->link('account/qq_connect', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['action'] = $this->url->link('account/qq_connect', '', 'SSL');
		$this->data['back'] = $this->url->link('account/login', '', 'SSL');
				
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	$this->data['text_create'] = $this->language->get('text_create');
    	$this->data['text_info'] = $this->language->get('text_info');
    	$this->data['text_binding'] = $this->language->get('text_binding');
		
    	$this->data['entry_binding'] = $this->language->get('entry_binding');
    	$this->data['entry_create'] = $this->language->get('entry_create');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->request->post['openid'])) {
			$this->data['openid'] = $this->request->post['openid'];
			$this->data['accesstoken'] = $this->request->post['accesstoken'];
		} else {
			$this->data['openid'] = '';
		}
		if (isset($this->request->post['create_firstname'])) {
    		$this->data['create_firstname'] = $this->request->post['create_firstname'];
		} else {
			$this->data['create_firstname'] = '';
		}

		if (isset($this->request->post['create_lastname'])) {
    		$this->data['create_lastname'] = $this->request->post['create_lastname'];
		} else {
			$this->data['create_lastname'] = '';
		}
		
		if (isset($this->request->post['create_email'])) {
    		$this->data['create_email'] = $this->request->post['create_email'];
		} else {
			$this->data['create_email'] = '';
		}
		
		if (isset($this->request->post['create_telephone'])) {
    		$this->data['create_telephone'] = $this->request->post['create_telephone'];
		} else {
			$this->data['create_telephone'] = '';
		}
		
		if (isset($this->request->post['create_fax'])) {
    		$this->data['create_fax'] = $this->request->post['create_fax'];
		} else {
			$this->data['create_fax'] = '';
		}
		
		if (isset($this->request->post['create_password'])) {
    		$this->data['create_password'] = $this->request->post['create_password'];
		} else {
			$this->data['create_password'] = '';
		}
		
		if (isset($this->request->post['create_confirm'])) {
    		$this->data['create_confirm'] = $this->request->post['create_confirm'];
		} else {
			$this->data['create_confirm'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/qq_connect.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/qq_connect.tpl';
		} else {
			$this->template = 'default/template/account/qq_connect.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());
  	}
  
  	private function validate() {
    	if (!$this->customer->login($this->request->post['bind_email'], $this->request->post['bind_password'])) {
      		$this->error['warning'] = $this->language->get('error_login');
    	}
	
    	/*if ($this->request->post['bind_confirm'] != $this->request->post['bind_password']) {
      		$this->error['warning'] = $this->language->get('error_confirm');
    	}*/
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}	
  	}

  	private function validate2() {
    	if ((strlen(utf8_decode($this->request->post['create_telephone'])) < 3) || (strlen(utf8_decode($this->request->post['create_telephone'])) > 32)) {
      		$this->error['warning'] = $this->language->get('error_telephone');
    	}

    	if ((strlen(utf8_decode($this->request->post['create_lastname'])) < 1) || (strlen(utf8_decode($this->request->post['create_lastname'])) > 32)) {
      		$this->error['warning'] = $this->language->get('error_lastname');
    	}

    	if ((strlen(utf8_decode($this->request->post['create_firstname'])) < 1) || (strlen(utf8_decode($this->request->post['create_firstname'])) > 32)) {
      		$this->error['warning'] = $this->language->get('error_firstname');
    	}

    	if ($this->request->post['create_confirm'] != $this->request->post['create_password']) {
      		$this->error['warning'] = $this->language->get('error_confirm');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['create_password'])) < 4) || (strlen(utf8_decode($this->request->post['create_password'])) > 20)) {
      		$this->error['warning'] = $this->language->get('error_password');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['create_email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['create_email'])) {
      		$this->error['warning'] = $this->language->get('error_email');
    	}

    	if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['create_email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
}
?>