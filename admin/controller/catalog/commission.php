<?php
class ControllerCatalogCommission extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_commission->addCommission($this->request->post);

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

			$this->redirect($this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_commission->editCommission($this->request->get['commission_id'], $this->request->post);

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

			$this->redirect($this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $commission_id) {
				$this->model_catalog_commission->deleteCommission($commission_id);
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

			$this->redirect($this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			$sort = 'commission_type';
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
       		'href'      => $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'), 
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('catalog/commission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/commission/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['commissions'] = array();

		$data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$commissions_total = $this->model_catalog_commission->getTotalCommissions($data);  //count commission per page
		$results = $this->model_catalog_commission->getCommissions($data); //get total commission name

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/commission/update', 'token=' . $this->session->data['token'] . '&commission_id=' . $result['commission_id'] . $url, 'SSL')
			);
			
			$total_vendors = $this->model_catalog_commission->getTotalVendorsByCommissionId($result['commission_id']);
			
			if ($result['commission_type'] == '0') { 
				$commission_type = $this->language->get('text_percentage');
				$commission = $result['commission'];
			 } elseif ($result['commission_type'] == '1') { 
				$commission_type = $this->language->get('text_fixed_rate');
				$commission = $result['commission'];
			} elseif ($result['commission_type'] == '2') {  
				$commission_type = $this->language->get('text_pf');
				if (!strpos($result['commission'], ':') === false) {
					$data = explode(':',$result['commission']);
					$commission = $data[0] . '% + ' . $data[1]; 
				} else {
					$commission = $result['commission'];
				}
			} elseif ($result['commission_type'] == '3') { 
				$commission_type = $this->language->get('text_fp');
				if (!strpos($result['commission'], ':') === false) {
					$data = explode(':',$result['commission']);
					$commission = $data[0] . ' + ' . $data[1] . '%'; 
				} else {
					$commission = $result['commission'];
				}
			}			
			
			$this->data['commissions'][] = array(
				'commission_id' 	=> $result['commission_id'],
				'commission_name' 	=> $result['commission_name'],
				'commission_type'   => $commission_type,
				'commission'    	=> $commission,
				'total_vendors'		=> $total_vendors,
				'sort_order'    	=> $result['sort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['commission_id'], $this->request->post['selected']),
				'action'     		=> $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_commission'] = $this->language->get('column_commission');
    	$this->data['column_total_vendors'] = $this->language->get('column_total_vendors');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$this->data['text_percentage'] = $this->language->get('text_percentage');
		$this->data['text_pf'] = $this->language->get('text_pf');
		$this->data['text_fp'] = $this->language->get('text_fp');

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
		
		$this->data['sort_commission_name'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=commission_name' . $url, 'SSL');
		$this->data['sort_commission_type'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=commission_type' . $url, 'SSL');
		$this->data['sort_commission'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=commission' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $commissions_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/commission_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$this->data['text_percentage'] = $this->language->get('text_percentage');
		$this->data['text_pf'] = $this->language->get('text_pf');
		$this->data['text_fp'] = $this->language->get('text_fp');
		
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		
    	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['commission_name'])) {
			$this->data['error_commission_name'] = $this->error['commission_name'];
		} else {
			$this->data['error_commission_name'] = '';
		}
		
		if (isset($this->error['commission'])) {
			$this->data['error_commission'] = $this->error['commission'];
		} else {
			$this->data['error_commission'] = '';
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
       		'href'      => $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['commission_id'])) {
			$this->data['action'] = $this->url->link('catalog/commission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/commission/update', 'token=' . $this->session->data['token'] . '&commission_id=' . $this->request->get['commission_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['commission_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$commissions_info = $this->model_catalog_commission->getCommission($this->request->get['commission_id']);
    	}
		
		if (isset($this->request->post['commission_name'])) {
      		$this->data['commission_name'] = $this->request->post['commission_name'];
    	} elseif (isset($commissions_info)) {
			$this->data['commission_name'] = $commissions_info['commission_name'];
		} else {	
      		$this->data['commission_name'] = '';
    	}
		
		if (isset($this->request->post['commission_type'])) {
      		$this->data['commission_type'] = $this->request->post['commission_type'];
    	} elseif (isset($commissions_info)) {
			$this->data['commission_type'] = $commissions_info['commission_type'];
		} else {	
      		$this->data['commission_type'] = '';
    	}
		
		if (isset($this->request->post['commission'])) {
			$this->data['commission'] = $this->request->post['commission'];
		} elseif (isset($commissions_info)) {
			$this->data['commission'] = $commissions_info['commission'];
		} else {
			$this->data['commission'] = '';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($commissions_info)) {
			$this->data['sort_order'] = $commissions_info['sort_order'];
		} else {	
      		$this->data['sort_order'] = '';
    	}

		$this->template = 'catalog/commission_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/commission')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['commission_name'])) < 1) || (strlen(utf8_decode($this->request->post['commission_name'])) > 64)) {
      		$this->error['commission_name'] = $this->language->get('error_commission_name');
    	}
		
		if ((strlen(utf8_decode($this->request->post['commission'])) < 1)) {
      		$this->error['commission'] = $this->language->get('error_commission');
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
    	if (!$this->user->hasPermission('modify', 'catalog/commission')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/commission');

		foreach ($this->request->post['selected'] as $commission_id) {
  			$commissions_total = $this->model_catalog_commission->getTotalVendorsByCommissionId($commission_id);
    		if ($commissions_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_delete'), $commissions_total);	
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