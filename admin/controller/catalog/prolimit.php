<?php
class ControllerCatalogProLimit extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/prolimit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/prolimit');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_prolimit->addLimit($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->redirect($this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/prolimit');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_prolimit->editLimit($this->request->get['product_limit_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->redirect($this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/prolimit');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_limit_id) {
				$this->model_catalog_prolimit->deleteLimit($product_limit_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->redirect($this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'), 
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('catalog/prolimit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/prolimit/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['commissions'] = array();

		$data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$prolimit_total = $this->model_catalog_prolimit->getTotalLimits($data);  //count prolimit per page
		$results = $this->model_catalog_prolimit->getLimits($data); //get total prolimit name

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/prolimit/update', 'token=' . $this->session->data['token'] . '&product_limit_id=' . $result['product_limit_id'] . $url, 'SSL')
			);
			
			$total_vendors = $this->model_catalog_prolimit->getTotalVendorsByLimitId($result['product_limit_id']);
				
			$this->data['product_limits'][] = array(
				'product_limit_id' 	=> $result['product_limit_id'],
				'package_name' 		=> $result['package_name'],
				'product_limit'    	=> $result['product_limit'],
				'total_vendors'		=> $total_vendors,
				'sort_order'    	=> $result['sort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['product_limit_id'], $this->request->post['selected']),
				'action'     		=> $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_total_vendors'] = $this->language->get('column_total_vendors');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

 		$this->data['token'] = $this->session->data['token'];

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
		
		$this->data['sort_package_name'] = $this->url->link('catalog/prolimit&token=' . $this->session->data['token'] . '&sort=package_name' . $url, 'SSL');
		$this->data['sort_product_limit'] = $this->url->link('catalog/prolimit&token=' . $this->session->data['token'] . '&sort=product_limit' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/prolimit&token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $prolimit_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/prolimit_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		
    	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['package_name'])) {
			$this->data['error_package_name'] = $this->error['package_name'];
		} else {
			$this->data['error_package_name'] = '';
		}
		
		if (isset($this->error['product_limit'])) {
			$this->data['error_product_limit'] = $this->error['product_limit'];
		} else {
			$this->data['error_product_limit'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$this->data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$this->data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_commission_type'])) {
			$url .= '&filter_commission_type=' . $this->request->get['filter_commission_type'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
			'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['product_limit_id'])) {
			$this->data['action'] = $this->url->link('catalog/prolimit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/prolimit/update', 'token=' . $this->session->data['token'] . '&product_limit_id=' . $this->request->get['product_limit_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['product_limit_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$prolimit_info = $this->model_catalog_prolimit->getLimit($this->request->get['product_limit_id']);
    	}
		
		if (isset($this->request->post['package_name'])) {
      		$this->data['package_name'] = $this->request->post['package_name'];
    	} elseif (isset($prolimit_info)) {
			$this->data['package_name'] = $prolimit_info['package_name'];
		} else {	
      		$this->data['package_name'] = '';
    	}
			
		if (isset($this->request->post['product_limit'])) {
			$this->data['product_limit'] = $this->request->post['product_limit'];
		} elseif (isset($prolimit_info)) {
			$this->data['product_limit'] = $prolimit_info['product_limit'];
		} else {
			$this->data['product_limit'] = '';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($prolimit_info)) {
			$this->data['sort_order'] = $prolimit_info['sort_order'];
		} else {	
      		$this->data['sort_order'] = '';
    	}

		$this->template = 'catalog/prolimit_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/prolimit')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['package_name'])) < 1) || (strlen(utf8_decode($this->request->post['package_name'])) > 64)) {
      		$this->error['package_name'] = $this->language->get('error_package_name');
    	}
		
		if ((strlen(utf8_decode($this->request->post['product_limit'])) < 1) || (!is_numeric($this->request->post['product_limit']))) {
      		$this->error['product_limit'] = $this->language->get('error_product_limit');
    	}

    	if (!$this->error) {
			return TRUE;
    	} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/prolimit')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/prolimit');

		foreach ($this->request->post['selected'] as $product_limit_id) {
  			$prolimit_total = $this->model_catalog_prolimit->getTotalVendorsByLimitId($product_limit_id);
    		if ($prolimit_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_delete'), $prolimit_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

}
?>