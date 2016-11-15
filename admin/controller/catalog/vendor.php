<?php
class ControllerCatalogVendor extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/vendor');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->addVendor($this->request->post);

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

			$this->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/vendor');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->editVendor($this->request->get['vendor_id'], $this->request->post);

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

			$this->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/vendor');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $vendor_id) {
				$this->model_catalog_vendor->deleteVendor($vendor_id);
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

			$this->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			$sort = 'v.vendor_name';
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
       		'href'      => $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'), 
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['insert'] = $this->url->link('catalog/vendor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('catalog/vendor/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['vendors'] = array();

		$data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');
		
		$vendors_total = $this->model_catalog_vendor->getTotalVendors($data);  //count vendor per page
		$results = $this->model_catalog_vendor->getVendors($data); //get total vendor name
		
		$this->load->model('catalog/commission');
		$this->data['commissions'] = $this->model_catalog_commission->getCommissions();
		
		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/vendor/update', 'token=' . $this->session->data['token'] . '&vendor_id=' . $result['vendor_id'] . $url, 'SSL')
			);
			
			$allproducts = array();
			
			$allproducts[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&filter_vendor=' . $result['vendor_id'] . $url, 'SSL')
			);
			
			if ($result['vendor_image'] && file_exists(DIR_IMAGE . $result['vendor_image'])) {
				$image = $this->model_tool_image->resize($result['vendor_image'], 120, 45);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 120, 45);
			}
		
			$total_products = $this->model_catalog_vendor->getTotalVendorsByVendorId($result['vendor_id']);
			
			switch ($result['commission_type']) {
				case '0': 
					$commission = $this->language->get('text_percentage') . ' (' . $result['commission'] . '%)';
					break;
				case '1':
					$commission = $this->language->get('text_fixed_rate')  . ' (' . $result['commission'] . ')';
					break;
				case '2':
					$commission_type = $this->language->get('text_pf');
					if (!strpos($result['commission'], ':') === false) {
						$data = explode(':',$result['commission']);
						$commission = $this->language->get('text_pf') . ' (' . $data[0] . '% + ' . $data[1] . ')'; 
					} else {
						$commission = $this->language->get('text_pf') . '(' . $result['commission'] . '%)';
					}
					break;
				case '3':
					if (!strpos($result['commission'], ':') === false) {
						$data = explode(':',$result['commission']);
						$commission = $this->language->get('text_fp') . ' (' . $data[0] . ' + ' . $data[1] . '%)'; 
					} else {
						$commission = $this->language->get('text_fp') . '(' . $result['commission'] . ')';
					}
					break;
			}
			
			$this->data['vendors'][] = array(
				'vendor_id' 		=> $result['vendor_id'],
				'vendor_name'    	=> $result['vendor_name'],
				'commission_id'    	=> $result['commission_id'],
				'commission'    	=> $commission,
				'image'      		=> $image,
				'total_products'	=> $total_products,
				'sort_order'    	=> $result['vsort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['vendor_id'], $this->request->post['selected']),
				'allproducts'		=> $allproducts,
				'action'     		=> $action
			);
    	}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$this->data['text_percentage'] = $this->language->get('text_percentage');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$this->data['column_vendor_commission'] = $this->language->get('column_vendor_commission');
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
		
		if (isset($this->request->get['filter_vendor_name'])) {
			$filter_vendor_name = $this->request->get['filter_vendor_name'];
		} else {
			$filter_vendor_name = NULL;
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$filter_sort_order = $this->request->get['filter_sort_order'];
		} else {
			$filter_sort_order = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . $this->request->get['filter_vendor_name'];
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

		$this->data['sort_vendor_name'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=vendor_name' . $url, 'SSL');
		$this->data['sort_commission'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=commission' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=vsort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $vendors_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_vendor_name'] = $filter_vendor_name;
			
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/vendor_list.tpl';
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
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$this->data['text_percentage'] = $this->language->get('text_percentage');
		$this->data['text_pf'] = $this->language->get('text_pf');
		$this->data['text_fp'] = $this->language->get('text_fp');
		
		$this->data['entry_vendor_name'] = $this->language->get('entry_vendor_name');
		$this->data['entry_user_account'] = $this->language->get('entry_user_account');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		/*add*/
		$this->data['entry_limit'] = $this->language->get('entry_limit');
	
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_paypal_email'] = $this->language->get('entry_paypal_email');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_store_url'] = $this->language->get('entry_store_url');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_image'] = $this->language->get('entry_image');
		
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
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
		
		if (isset($this->error['vendor_name'])) {
			$this->data['error_vendor_name'] = $this->error['vendor_name'];
		} else {
			$this->data['error_vendor_name'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_vendor_email'] = $this->error['email'];
		} else {
			$this->data['error_vendor_email'] = '';
		}
		
		if (isset($this->error['paypal_email'])) {
			$this->data['error_vendor_paypal_email'] = $this->error['paypal_email'];
		} else {
			$this->data['error_vendor_paypal_email'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$this->data['error_vendor_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_vendor_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$this->data['error_vendor_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_vendor_lastname'] = '';
		}		
	
		if (isset($this->error['telephone'])) {
			$this->data['error_vendor_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_vendor_telephone'] = '';
		}
		
  		if (isset($this->error['address_1'])) {
			$this->data['error_vendor_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_vendor_address_1'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$this->data['error_vendor_city'] = $this->error['city'];
		} else {
			$this->data['error_vendor_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_vendor_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_vendor_postcode'] = '';
		}
		
		if (isset($this->error['country'])) {
			$this->data['error_vendor_country'] = $this->error['country'];
		} else {
			$this->data['error_vendor_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_vendor_zone'] = $this->error['zone'];
		} else {
			$this->data['error_vendor_zone'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$this->data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$this->data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . $this->request->get['filter_vendor_name'];
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
       		'href'      => $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['vendor_id'])) {
			$this->data['action'] = $this->url->link('catalog/vendor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/vendor/update', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->get['vendor_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->get['vendor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$vendors_info = $this->model_catalog_vendor->getVendor($this->request->get['vendor_id']);
    	}
		
		$this->load->model('user/user');
		$this->data['user_accounts'] = $this->model_user_user->getUsers();
		
		if (isset($this->request->post['user_id'])) {
      		$this->data['user_id'] = $this->request->post['user_id'];
    	} elseif (isset($vendors_info)) {
			$this->data['user_id'] = $vendors_info['user_id'];
		} else {	
      		$this->data['user_id'] = '';
    	}
		
		if (isset($this->request->post['vendor_name'])) {
      		$this->data['vendor_name'] = $this->request->post['vendor_name'];
    	} elseif (isset($vendors_info)) {
			$this->data['vendor_name'] = $vendors_info['vendor_name'];
		} else {	
      		$this->data['vendor_name'] = '';
    	}
		
		if (isset($this->request->post['company'])) {
      		$this->data['company'] = $this->request->post['company'];
    	} elseif (isset($vendors_info)) {
			$this->data['company'] = $vendors_info['company'];
		} else {	
      		$this->data['company'] = '';
    	}
		
		if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
    	} elseif (isset($vendors_info)) {
			$this->data['firstname'] = $vendors_info['firstname'];
		} else {	
      		$this->data['firstname'] = '';
    	}

		if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (isset($vendors_info)) {
			$this->data['lastname'] = $vendors_info['lastname'];
		} else {	
      		$this->data['lastname'] = '';
    	}
		
		if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (isset($vendors_info)) {
			$this->data['telephone'] = $vendors_info['telephone'];
		} else {	
      		$this->data['telephone'] = '';
    	}
		
		if (isset($this->request->post['commission'])) {
      		$this->data['commission'] = $this->request->post['commission'];
    	} elseif (isset($vendors_info)) {
			$this->data['commission'] = $vendors_info['commission_id'];
		} else {	
      		$this->data['commission'] = '';
    	}
		/*add*/
		if (isset($this->request->post['product_limit'])) {
      		$this->data['product_limit'] = $this->request->post['product_limit'];
    	} elseif (isset($vendors_info)) {
			$this->data['product_limit'] = $vendors_info['product_limit_id'];
		} else {	
      		$this->data['product_limit'] = '';
    	}
		/*add*/
		if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (isset($vendors_info)) {
			$this->data['fax'] = $vendors_info['fax'];
		} else {	
      		$this->data['fax'] = '';
    	}
		
		if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (isset($vendors_info)) {
			$this->data['email'] = $vendors_info['email'];
		} else {	
      		$this->data['email'] = '';
    	}
		
		if (isset($this->request->post['paypal_email'])) {
      		$this->data['paypal_email'] = $this->request->post['paypal_email'];
    	} elseif (isset($vendors_info)) {
			$this->data['paypal_email'] = $vendors_info['paypal_email'];
		} else {	
      		$this->data['paypal_email'] = '';
    	}
		
		if (isset($this->request->post['address_1'])) {
      		$this->data['address_1'] = $this->request->post['address_1'];
    	} elseif (isset($vendors_info)) {
			$this->data['address_1'] = $vendors_info['address_1'];
		} else {	
      		$this->data['address_1'] = '';
    	}
		
		if (isset($this->request->post['address_2'])) {
      		$this->data['address_2'] = $this->request->post['address_2'];
    	} elseif (isset($vendors_info)) {
			$this->data['address_2'] = $vendors_info['address_2'];
		} else {	
      		$this->data['address_2'] = '';
    	}
		
		if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	} elseif (isset($vendors_info)) {
			$this->data['city'] = $vendors_info['city'];
		} else {	
      		$this->data['city'] = '';
    	}
		
		if (isset($this->request->post['postcode'])) {
      		$this->data['postcode'] = $this->request->post['postcode'];
    	} elseif (isset($vendors_info)) {
			$this->data['postcode'] = $vendors_info['postcode'];
		} else {	
      		$this->data['postcode'] = '';
    	}
		
		$this->load->model('localisation/country');
	   	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	} elseif (isset($vendors_info)) {
			$this->data['country_id'] = $vendors_info['country_id'];
		} else {	
      		$this->data['country_id'] = '';
    	}
		
	   	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	} elseif (isset($vendors_info)) {
			$this->data['zone_id'] = $vendors_info['zone_id'];
		} else {	
      		$this->data['zone_id'] = '';
    	}
		
		if (isset($this->request->post['vendor_description'])) {
      		$this->data['vendor_description'] = $this->request->post['vendor_description'];
    	} elseif (isset($vendors_info)) {
			$this->data['vendor_description'] = $vendors_info['vendor_description'];
		} else {	
      		$this->data['vendor_description'] = '';
    	}
		
		if (isset($this->request->post['store_url'])) {
      		$this->data['store_url'] = $this->request->post['store_url'];
    	} elseif (isset($vendors_info)) {
			$this->data['store_url'] = $vendors_info['store_url'];
		} else {	
      		$this->data['store_url'] = '';
    	}
		
		if (isset($this->request->post['vendor_image'])) {
			$this->data['vendor_image'] = $this->request->post['vendor_image'];
		} elseif (isset($vendors_info)) {
			$this->data['vendor_image'] = $vendors_info['vendor_image'];
		} else {
			$this->data['vendor_image'] = '';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$this->data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($vendors_info)) {
			$this->data['sort_order'] = $vendors_info['sort_order'];
		} else {	
      		$this->data['sort_order'] = '';
    	}
		
		$this->load->model('catalog/commission');
		$this->data['commissions'] = $this->model_catalog_commission->getCommissions();
		
		$this->load->model('catalog/prolimit');
		$this->data['prolimits'] = $this->model_catalog_prolimit->getLimits();

		$this->load->model('tool/image');

		if (isset($vendors_info) && $vendors_info['vendor_image'] && file_exists(DIR_IMAGE . $vendors_info['vendor_image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($vendors_info['vendor_image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
	
		$this->template = 'catalog/vendor_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/vendor')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->post['vendor_name']) < 3) || (utf8_strlen($this->request->post['vendor_name']) > 64)) {
      		$this->error['vendor_name'] = $this->language->get('error_vendor_name');
    	}
		
		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_vendor_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_vendor_lastname');
    	}
		
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_vendor_email');
    	}
		
		if (utf8_strlen($this->request->post['paypal_email']) > 0) {
			if ((utf8_strlen($this->request->post['paypal_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['paypal_email'])) {
				$this->error['paypal_email'] = $this->language->get('error_vendor_paypal_email');
			}
		}
		
		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_vendor_telephone');
    	}

    	if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_vendor_address_1');
    	}

    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
      		$this->error['city'] = $this->language->get('error_vendor_city');
    	}

		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
			$this->error['postcode'] = $this->language->get('error_vendor_postcode');
		}
		
    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_vendor_country');
    	}
		
    	if ($this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->language->get('error_vendor_zone');
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
    	if (!$this->user->hasPermission('modify', 'catalog/vendor')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/vendor');
		
		foreach ($this->request->post['selected'] as $vendor_id) {
			
  			$vendors_total = $this->model_catalog_vendor->getTotalVendorsByVendorId($vendor_id);
    
			if ($vendors_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_vendor'), $vendors_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
	
	public function zone() {
	
		$this->load->model('localisation/zone');
		
    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
		$output = '';
		
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';
				if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
					$output .= ' selected="selected"';
				}
				
			$output .= '>' . $result['name'] . '</option>';
		} 
		if (!$results) {		
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
		
		$this->response->setOutput($output);
  	}  

}
?>