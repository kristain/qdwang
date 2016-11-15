<?php
class ControllerCatalogCourier extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/courier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/courier');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_courier->addCourier($this->request->post);

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

			$this->redirect($this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/courier');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_courier->editCourier($this->request->get['courier_id'], $this->request->post);

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

			$this->redirect($this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/courier');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $courier_id) {
				$this->model_catalog_courier->deletecourier($courier_id);
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

			$this->redirect($this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			$sort = 'courier_name';
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
       		'href'      => $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'), 
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('catalog/courier/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/courier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['couriers'] = array();

		$data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');
		
		$couriers_total = $this->model_catalog_courier->getTotalCouriers($data);  //count courier per page
		$results = $this->model_catalog_courier->getCouriers($data); //get total courier name

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/courier/update', 'token=' . $this->session->data['token'] . '&courier_id=' . $result['courier_id'] . $url, 'SSL')
			);
			
			if ($result['courier_image'] && file_exists(DIR_IMAGE . $result['courier_image'])) {
				$image = $this->model_tool_image->resize($result['courier_image'], 120, 45);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 120, 45);
			}
			
			$total_products = $this->model_catalog_courier->getTotalCouriersByCourierId($result['courier_id']);
				
			$this->data['couriers'][] = array(
				'courier_id' 		=> $result['courier_id'],
				'courier_name'    	=> $result['courier_name'],
				'image'      		=> $image,
				'total_products'	=> $total_products,
				'sort_order'    	=> $result['sort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['courier_id'], $this->request->post['selected']),
				'action'     		=> $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_courier_name'] = $this->language->get('column_courier_name');
    	$this->data['column_total_products'] = $this->language->get('column_total_products');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

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
		
		if (isset($this->request->get['filter_courier_name'])) {
			$filter_courier_name = $this->request->get['filter_courier_name'];
		} else {
			$filter_courier_name = NULL;
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$filter_sort_order = $this->request->get['filter_sort_order'];
		} else {
			$filter_sort_order = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_courier_name'])) {
			$url .= '&filter_courier_name=' . $this->request->get['filter_courier_name'];
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$url .= '&filter_sort_order=' . $this->request->get['filter_sort_order'];
		} 

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_courier_name'] = $this->url->link('catalog/courier&token=' . $this->session->data['token'] . '&sort=courier_name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/courier&token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $couriers_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_courier_name'] = $filter_courier_name;
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/courier_list.tpl';
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
    	$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['entry_courier_name'] = $this->language->get('entry_courier_name');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['entry_courier_image'] = $this->language->get('entry_courier_image');
    	$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		
    	$this->data['tab_general'] = $this->language->get('tab_general');
    	
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['courier_name'])) {
			$this->data['error_courier_name'] = $this->error['courier_name'];
		} else {
			$this->data['error_courier_name'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$this->data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$this->data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_courier_name'])) {
			$url .= '&filter_courier_name=' . $this->request->get['filter_courier_name'];
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
       		'href'      => $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['courier_id'])) {
			$this->data['action'] = $this->url->link('catalog/courier/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/courier/update', 'token=' . $this->session->data['token'] . '&courier_id=' . $this->request->get['courier_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['courier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$couriers_info = $this->model_catalog_courier->getcourier($this->request->get['courier_id']);
    	}
		
		if (isset($this->request->post['courier_name'])) {
      		$this->data['courier_name'] = $this->request->post['courier_name'];
    	} elseif (isset($couriers_info)) {
			$this->data['courier_name'] = $couriers_info['courier_name'];
		} else {	
      		$this->data['courier_name'] = '';
    	}
		
		if (isset($this->request->post['courier_image'])) {
			$this->data['courier_image'] = $this->request->post['courier_image'];
		} elseif (isset($couriers_info)) {
			$this->data['courier_image'] = $couriers_info['courier_image'];
		} else {
			$this->data['courier_image'] = '';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($couriers_info)) {
			$this->data['sort_order'] = $couriers_info['sort_order'];
		} else {	
      		$this->data['sort_order'] = '';
    	}

		$this->load->model('tool/image');

		if (isset($couriers_info) && $couriers_info['courier_image'] && file_exists(DIR_IMAGE . $couriers_info['courier_image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($couriers_info['courier_image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$this->template = 'catalog/courier_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/courier')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['courier_name'])) < 1) || (strlen(utf8_decode($this->request->post['courier_name'])) > 64)) {
      		$this->error['courier_name'] = $this->language->get('error_courier_name');
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
    	if (!$this->user->hasPermission('modify', 'catalog/courier')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/courier');

		foreach ($this->request->post['selected'] as $courier_id) {
  			$couriers_total = $this->model_catalog_courier->getTotalCouriersByCourierId($courier_id);
    		if ($couriers_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_courier'), $couriers_total);	
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