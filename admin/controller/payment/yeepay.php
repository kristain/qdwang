<?php 
class ControllerPaymentYeepay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/yeepay');

		$this->document->settitle($this->language->get('heading_title'));
		
		if (isset($this->error['yeepay_merid'])) {
			$this->data['error_merid'] = $this->error['yeepay_merid'];
		} else {
			$this->data['error_merid'] = '';
		}

		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}
		
   		$this->data['breadcrumbs']  = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' =>' > '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/yeepay&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' =>' > '
   		);
   		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('yeepay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect( HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_seller_email'] = $this->language->get('entry_seller_email');
		$this->data['entry_bargainor_id'] = $this->language->get('entry_bargainor_id');
		$this->data['entry_seller'] = $this->language->get('entry_seller');
		$this->data['entry_trade_type'] = $this->language->get('entry_trade_type');

		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}


		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/yeepay&token=' . $this->session->data['token'];
		
		$this->data['cancel'] =  HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		
		
		if (isset($this->request->post['yeepay_merid'])) {
			$this->data['yeepay_merid'] = $this->request->post['yeepay_merid'];
		} else {
			$this->data['yeepay_merid'] = $this->config->get('yeepay_merid');
		}

		if (isset($this->request->post['yeepay_key'])) {
			$this->data['yeepay_key'] = $this->request->post['yeepay_key'];
		} else {
			$this->data['yeepay_key'] = $this->config->get('yeepay_key');
		}
		
		if (isset($this->request->post['yeepay_order_status_id'])) {
			$this->data['yeepay_order_status_id'] = $this->request->post['yeepay_order_status_id'];
		} else {
			$this->data['yeepay_order_status_id'] = $this->config->get('yeepay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['yeepay_status'])) {
			$this->data['yeepay_status'] = $this->request->post['yeepay_status'];
		} else {
			$this->data['yeepay_status'] = $this->config->get('yeepay_status');
		}
		
		if (isset($this->request->post['yeepay_sort_order'])) {
			$this->data['yeepay_sort_order'] = $this->request->post['yeepay_sort_order'];
		} else {
			$this->data['yeepay_sort_order'] = $this->config->get('yeepay_sort_order');
		}
		
		$this->template = 'payment/yeepay.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/yeepay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
	
		if (!$this->request->post['yeepay_merid']) {
			$this->error['yeepay_merid'] = $this->language->get('error_merid');
		}

		if (!$this->request->post['yeepay_key']) {
			$this->error['key'] = $this->language->get('yeepay_key');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>