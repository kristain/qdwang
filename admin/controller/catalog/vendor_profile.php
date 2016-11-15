<?php
class ControllerCatalogVendorProfile extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title_profile'));

		$this->load->model('catalog/vendor');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->editVendorProfile($this->user->getId(), $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_profile');

			$this->redirect($this->url->link('catalog/vendor_profile', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title_profile'] = $this->language->get('heading_title_profile');

    	$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_select'] = $this->language->get('text_select');
		
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
	
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
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
  		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
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

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
			'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/vendor_profile', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title_profile'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('catalog/vendor_profile', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];
		
		$vendors_info = $this->model_catalog_vendor->getVendorProfile($this->user->getId());
		
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
	
		$this->load->model('tool/image');

		if (isset($vendors_info) && $vendors_info['vendor_image'] && file_exists(DIR_IMAGE . $vendors_info['vendor_image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($vendors_info['vendor_image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
	
		$this->template = 'catalog/vendor_profile.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/vendor_profile')) {
      		$this->error['warning'] = $this->language->get('error_permission');
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