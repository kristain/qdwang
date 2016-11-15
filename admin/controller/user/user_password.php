<?php  
class ControllerUserUserPassword extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title_password'));
	
		$this->load->model('user/user');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			if ($this->user->getId()) {
			
				$this->model_user_user->editPassword($this->user->getId(), $this->request->post['password']);
				
				$this->session->data['success'] = $this->language->get('text_change_password');
			}
				
			$this->redirect($this->url->link('user/user_password', 'token=' . $this->session->data['token'], 'SSL'));
    	}
		
		$this->data['heading_title_password'] = $this->language->get('heading_title_password');
		$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
       	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$this->data['error_confirm'] = $this->error['confirm'];
		} else {
			$this->data['error_confirm'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title_password'),
			'href'      => $this->url->link('user/user_password', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('user/user_password', 'token=' . $this->session->data['token'], 'SSL');
    	$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		
		if ($this->user->getId()) {
			$user_info = $this->model_user_user->getUser($this->user->getId());
    	}

		if (isset($this->request->post['username'])) {
      		$this->data['username'] = $this->request->post['username'];
    	} elseif (!empty($user_info)) {
			$this->data['username'] = $user_info['username'];
		} else {
      		$this->data['username'] = '';
    	}
		
    	if (isset($this->request->post['password'])) {
    		$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
  		if (isset($this->request->post['confirm'])) {
    		$this->data['confirm'] = $this->request->post['confirm'];
		} else {
			$this->data['confirm'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
  
  		$this->template = 'user/user_password.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
     	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/user_password')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if (isset($this->request->post['username'])) {
			$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);
			
			if ($user_info['user_id'] != $this->user->getId()) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
       	if ($this->request->post['password'] || ($this->user->getId())) {
      		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

 }
?>