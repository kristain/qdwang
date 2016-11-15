<?php  
class ControllerUserUser extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('user/user');
		
    	$this->getList();
  	}
   
  	public function insert() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			if (!file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username'])) && (isset($this->request->post['generate_path']))) {
				mkdir(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']), 0777);
			}
			
			$this->model_user_user->addUser($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			if (file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username'])) && (isset($this->request->post['remove_path']))) {
				$this->recursiveDelete(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']));
			} elseif (!file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username'])) && (isset($this->request->post['generate_path']))) {
				mkdir(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $this->request->post['username']), 0777);
			}
			
			$this->model_user_user->editUser($this->request->get['user_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getForm();
  	}
 
  	public function delete() { 
    	$this->load->language('user/user');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('user/user');
		
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
      		foreach ($this->request->post['selected'] as $user_id) {
				$this->model_user_user->deleteUser($user_id);	
			}

			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
					
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->getList();
  	}

  	private function getList() {

			if (isset($this->request->get['filter_status'])) {
				$filter_status = $this->request->get['filter_status'];
			} else {
				$filter_status = null;
			}
			
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'username';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
			
		$url = '';
		

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
			
		$this->data['insert'] = $this->url->link('user/user/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('user/user/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');			
			
    	$this->data['users'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		
		$user_total = $this->model_user_user->getTotalUsers();
		
		$results = $this->model_user_user->getUsers($data);
    	
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'] . $url, 'SSL')
			);
					

			if ($result['status'] == 5) {
				$status = $this->language->get('txt_pending_approval');
			} elseif ($result['status']) {
				$status = $this->language->get('text_enabled');
			} else {
				$status = $this->language->get('text_disabled');
			}
			
      		$this->data['users'][] = array(
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				
			'status'     => $status,
			
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'   => isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}	
			
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_username'] = $this->language->get('column_username');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$this->data['sort_username'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=username' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
								
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'user/user_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
	
	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');
		
    	$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_user_group'] = $this->language->get('entry_user_group');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');

			$this->data['entry_folder_path'] = $this->language->get('entry_folder_path');
			$this->data['entry_folder_path_remove'] = $this->language->get('entry_folder_path_remove');
			$this->data['entry_vendor'] = $this->language->get('entry_vendor');
			$this->data['entry_category'] = $this->language->get('entry_category');
			$this->data['entry_store'] = $this->language->get('entry_store');
			$this->data['text_none'] = $this->language->get('text_none');
			$this->data['text_default'] = $this->language->get('text_default');
			$this->data['text_select_all'] = $this->language->get('text_select_all');
			$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
			$this->data['text_remove'] = $this->language->get('text_remove');
			$this->data['txt_pending_approval'] = $this->language->get('txt_pending_approval');
			
    
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
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
		
	 	if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
	 	if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['user_id'])) {
			$this->data['action'] = $this->url->link('user/user/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('user/user/update', 'token=' . $this->session->data['token'] . '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');
		}
		  
    	$this->data['cancel'] = $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$user_info = $this->model_user_user->getUser($this->request->get['user_id']);
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
  
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
    	} elseif (!empty($user_info)) {
			$this->data['firstname'] = $user_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($user_info)) {
			$this->data['lastname'] = $user_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
   		}
  
    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($user_info)) {
			$this->data['email'] = $user_info['email'];
		} else {
      		$this->data['email'] = '';
    	}

    	if (isset($this->request->post['user_group_id'])) {
      		$this->data['user_group_id'] = $this->request->post['user_group_id'];
    	} elseif (!empty($user_info)) {
			$this->data['user_group_id'] = $user_info['user_group_id'];
		} else {
      		$this->data['user_group_id'] = '';
    	}
		

			$this->load->model('user/user');
			$this->data['vendor_List'] = $this->model_user_user->getVendors();
			
			$this->load->model('catalog/category');
			$this->data['categories'] = $this->model_catalog_category->getCategories(0);
			
			$this->load->model('setting/store');		
			$this->data['stores'] = $this->model_setting_store->getStores();
			
			if (isset($this->request->post['vendor_product'])) {
				$this->data['vendor_product'] = $this->request->post['vendor_product'];
			} elseif (!empty($user_info)) {
				$this->data['vendor_product'] = $user_info['vendor_permission'];
			} else { 
				$this->data['vendor_product'] = '';
			}
			
			if (isset($user_info['cat_permission'])) {
				$cat_permission = unserialize($user_info['cat_permission']);
			} else {
				$cat_permission = '';
			}		
			
			if (isset($this->request->post['vendor_category'])) {
				$this->data['vendor_category'] = $this->request->post['vendor_category'];
			} elseif (isset($cat_permission)) {
				$this->data['vendor_category'] = $cat_permission;
			} else { 
				$this->data['vendor_category'] = array();
			}
			
			if (isset($user_info['store_permission'])) {
				$store_permission = unserialize($user_info['store_permission']);
			} else {
				$store_permission = '';
			}
			
			if (isset($this->request->post['product_store'])) {
				$this->data['product_store'] = $this->request->post['product_store'];
			} elseif (isset($store_permission)) {
				$this->data['product_store'] = $store_permission;
			} else {
				$this->data['product_store'] = array();
			}	
			
			if (isset($user_info['folder'])) {
				$this->data['folder_path'] = $user_info['folder'];
			} else {
				$this->data['folder_path'] = '';
			}
			
		$this->load->model('user/user_group');
		
    	$this->data['user_groups'] = $this->model_user_user_group->getUserGroups();
 
     	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} elseif (!empty($user_info)) {
			$this->data['status'] = $user_info['status'];
		} else {
      		$this->data['status'] = 0;
    	}
		
		$this->template = 'user/user_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
  	
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
    
    	if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}
		
		$user_info = $this->model_user_user->getUserByUsername($this->request->post['username']);
		
		if (!isset($this->request->get['user_id'])) {
			if ($user_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
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


			Private function recursiveDelete($directory) {
				if (is_dir($directory)) {
					$handle = opendir($directory);
				}
				
				if (!$handle) {
					return false;
				}
				
				while (false !== ($file = readdir($handle))) {
					if ($file != '.' && $file != '..') {
						if (!is_dir($directory . '/' . $file)) {
							unlink($directory . '/' . $file);
						} else {
							$this->recursiveDelete($directory . '/' . $file);
						}
					}
				}
				
				closedir($handle);
				
				rmdir($directory);
				
				return true;
			}
			
  	private function validateDelete() { 
    	if (!$this->user->hasPermission('modify', 'user/user')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	} 
	  	  
		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
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