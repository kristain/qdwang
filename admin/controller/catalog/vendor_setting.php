<?php
class ControllerCatalogVendorSetting extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('catalog/vendor_setting');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			if (isset($this->request->post['order_details'])) {
				$this->request->post['order_details'] = serialize($this->request->post['order_details']);
			}
			
			$this->model_setting_setting->editSetting('vendor_setting', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('catalog/vendor_setting', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_order_id'] = $this->language->get('entry_order_id');
		$this->data['entry_checkout_order_status'] = $this->language->get('entry_checkout_order_status');
		$this->data['entry_history_order_status'] = $this->language->get('entry_history_order_status');
		$this->data['entry_multivendor_order_status'] = $this->language->get('entry_multivendor_order_status');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$this->data['entry_cust_email'] = $this->language->get('entry_cust_email');
		$this->data['entry_cust_telephone'] = $this->language->get('entry_cust_telephone');
		$this->data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$this->data['entry_vendor_address'] = $this->language->get('entry_vendor_address');
		$this->data['entry_vendor_email'] = $this->language->get('entry_vendor_email');
		$this->data['entry_vendor_telephone'] = $this->language->get('entry_vendor_telephone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['entry_desgin_tab'] = $this->language->get('entry_desgin_tab');
		$this->data['entry_reward_points'] = $this->language->get('entry_reward_points');
		$this->data['entry_menu_bar'] = $this->language->get('entry_menu_bar');
		$this->data['entry_vendor_product_approval'] = $this->language->get('entry_vendor_product_approval');
		/*add*/
		$this->data['entry_vendor_tab'] = $this->language->get('entry_vendor_tab');
		$this->data['entry_category_menu'] = $this->language->get('entry_category_menu');
		$this->data['entry_product_limit'] = $this->language->get('entry_product_limit');
		/*add*/
		
		$this->data['entry_order_detail'] = $this->language->get('entry_order_detail');
		$this->data['entry_shipping_detail'] = $this->language->get('entry_shipping_detail');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_order_history'] = $this->language->get('entry_order_history');
		$this->data['entry_vendor_invoice_address'] = $this->language->get('entry_vendor_invoice_address');
		$this->data['entry_payment_detail'] = $this->language->get('entry_payment_detail');
		$this->data['entry_order_history_update'] = $this->language->get('entry_order_history_update');
				
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_order_id'] = $this->language->get('text_order_id');
		$this->data['text_order_status'] = $this->language->get('text_order_status');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_customer_contact'] = $this->language->get('text_customer_contact');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_vendor_address'] = $this->language->get('text_vendor_address');
		$this->data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$this->data['text_percentage'] = $this->language->get('text_percentage');
		$this->data['text_pf'] = $this->language->get('text_pf');
		$this->data['text_fp'] = $this->language->get('text_fp');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_sign_up'] = $this->language->get('entry_sign_up');
		$this->data['entry_commission'] = $this->language->get('entry_commission');
		$this->data['entry_policy'] = $this->language->get('entry_policy');
		$this->data['entry_vendor_approval'] = $this->language->get('entry_vendor_approval');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['text_select_all'] = $this->language->get('text_select_all');
		$this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$this->data['text_default'] = $this->language->get('text_default');
				
		$this->data['tab_mail_setting'] = $this->language->get('tab_mail_setting');
		$this->data['tab_catalog'] = $this->language->get('tab_catalog');
		$this->data['tab_sales'] = $this->language->get('tab_sales');
		$this->data['tab_signup'] = $this->language->get('tab_signup');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['code_message'])) {
			$this->data['error_code_message'] = $this->error['code_message'];
		} else {
			$this->data['error_code_message'] = '';
		}
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/vendor_setting', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('catalog/vendor_setting', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['vendor_email_message'])) {
			$this->data['vendor_email_message'] = $this->request->post['vendor_email_message'];
		} else {
			$this->data['vendor_email_message'] = $this->config->get('vendor_email_message');
		}

		if (isset($this->request->post['vendor_cust_order_id'])) {
			$this->data['vendor_cust_order_id'] = $this->request->post['vendor_cust_order_id'];
		} else {
			$this->data['vendor_cust_order_id'] = $this->config->get('vendor_cust_order_id');
		}	
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['vendor_checkout_order_status'])) {
			$this->data['vendor_checkout_order_status'] = $this->request->post['vendor_checkout_order_status'];
		} elseif ($this->config->get('vendor_checkout_order_status')) {
			$this->data['vendor_checkout_order_status'] = $this->config->get('vendor_checkout_order_status');
		} else { 
			$this->data['vendor_checkout_order_status'] = array();
		}
		
		if (isset($this->request->post['vendor_history_order_status'])) {
			$this->data['vendor_history_order_status'] = $this->request->post['vendor_history_order_status'];
		} elseif ($this->config->get('vendor_history_order_status')) {
			$this->data['vendor_history_order_status'] = $this->config->get('vendor_history_order_status');
		} else { 
			$this->data['vendor_history_order_status'] = array();
		}
		
		if (isset($this->request->post['multivendor_order_status'])) {
			$this->data['multivendor_order_status'] = $this->request->post['multivendor_order_status'];
		} else {
			$this->data['multivendor_order_status'] = $this->config->get('multivendor_order_status');
		}
		
		if (isset($this->request->post['vendor_cust_order_status'])) {
			$this->data['vendor_cust_order_status'] = $this->request->post['vendor_cust_order_status'];
		} else {
			$this->data['vendor_cust_order_status'] = $this->config->get('vendor_cust_order_status');
		}
		
		if (isset($this->request->post['vendor_cust_payment_method'])) {
			$this->data['vendor_cust_payment_method'] = $this->request->post['vendor_cust_payment_method'];
		} else {
			$this->data['vendor_cust_payment_method'] = $this->config->get('vendor_cust_payment_method');
		}
		
		if (isset($this->request->post['vendor_cust_email'])) {
			$this->data['vendor_cust_email'] = $this->request->post['vendor_cust_email'];
		} else {
			$this->data['vendor_cust_email'] = $this->config->get('vendor_cust_email');
		}
		
		if (isset($this->request->post['vendor_invoice_address'])) {
			$this->data['vendor_invoice_address'] = $this->request->post['vendor_invoice_address'];
		} else {
			$this->data['vendor_invoice_address'] = $this->config->get('vendor_invoice_address');
		}
		
		if (isset($this->request->post['vendor_cust_telephone'])) {
			$this->data['vendor_cust_telephone'] = $this->request->post['vendor_cust_telephone'];
		} else {
			$this->data['vendor_cust_telephone'] = $this->config->get('vendor_cust_telephone');
		}
		
		if (isset($this->request->post['vendor_cust_contacts'])) {
			$this->data['vendor_cust_contacts'] = $this->request->post['vendor_cust_contacts'];
		} else {
			$this->data['vendor_cust_contacts'] = $this->config->get('vendor_cust_contacts');
		}	

		if (isset($this->request->post['vendor_cust_shipping_address'])) {
			$this->data['vendor_cust_shipping_address'] = $this->request->post['vendor_cust_shipping_address'];
		} else {
			$this->data['vendor_cust_shipping_address'] = $this->config->get('vendor_cust_shipping_address');
		}	
		
		if (isset($this->request->post['vendor_address'])) {
			$this->data['vendor_address'] = $this->request->post['vendor_address'];
		} else {
			$this->data['vendor_address'] = $this->config->get('vendor_address');
		}
		
		if (isset($this->request->post['vendor_email'])) {
			$this->data['vendor_email'] = $this->request->post['vendor_email'];
		} else {
			$this->data['vendor_email'] = $this->config->get('vendor_email');
		}
		
		if (isset($this->request->post['vendor_telephone'])) {
			$this->data['vendor_telephone'] = $this->request->post['vendor_telephone'];
		} else {
			$this->data['vendor_telephone'] = $this->config->get('vendor_telephone');
		}
		
		if (isset($this->request->post['vendor_email_status'])) {
			$this->data['vendor_email_status'] = $this->request->post['vendor_email_status'];
		} else {
			$this->data['vendor_email_status'] = $this->config->get('vendor_email_status');
		}
		
		if (isset($this->request->post['vendor_desgin_tab'])) {
			$this->data['vendor_desgin_tab'] = $this->request->post['vendor_desgin_tab'];
		} else {
			$this->data['vendor_desgin_tab'] = $this->config->get('vendor_desgin_tab');
		}
			
		if (isset($this->request->post['vendor_reward_points'])) {
			$this->data['vendor_reward_points'] = $this->request->post['vendor_reward_points'];
		} else {
			$this->data['vendor_reward_points'] = $this->config->get('vendor_reward_points');
		}
		
		if (isset($this->request->post['vendor_menu_bar'])) {
			$this->data['vendor_menu_bar'] = $this->request->post['vendor_menu_bar'];
		} else {
			$this->data['vendor_menu_bar'] = $this->config->get('vendor_menu_bar');
		}
		
		if (isset($this->request->post['vendor_product_approval'])) {
			$this->data['vendor_product_approval'] = $this->request->post['vendor_product_approval'];
		} else {
			$this->data['vendor_product_approval'] = $this->config->get('vendor_product_approval');
		}
		
		/*add*/
		if (isset($this->request->post['vendor_tab'])) {
			$this->data['vendor_tab'] = $this->request->post['vendor_tab'];
		} else {
			$this->data['vendor_tab'] = $this->config->get('vendor_tab');
		}
		
		if (isset($this->request->post['vendor_category_menu'])) {
			$this->data['vendor_category_menu'] = $this->request->post['vendor_category_menu'];
		} else {
			$this->data['vendor_category_menu'] = $this->config->get('vendor_category_menu');
		}
		/*add*/
		if (isset($this->request->post['sales_order_detail'])) {
			$this->data['sales_order_detail'] = $this->request->post['sales_order_detail'];
		} else {
			$this->data['sales_order_detail'] = $this->config->get('sales_order_detail');
		}
		
		if (isset($this->request->post['sales_payment_detail'])) {
			$this->data['sales_payment_detail'] = $this->request->post['sales_payment_detail'];
		} else {
			$this->data['sales_payment_detail'] = $this->config->get('sales_payment_detail');
		}
		
		if (isset($this->request->post['sales_shipping_detail'])) {
			$this->data['sales_shipping_detail'] = $this->request->post['sales_shipping_detail'];
		} else {
			$this->data['sales_shipping_detail'] = $this->config->get('sales_shipping_detail');
		}
		
		if (isset($this->request->post['sales_product'])) {
			$this->data['sales_product'] = $this->request->post['sales_product'];
		} else {
			$this->data['sales_product'] = $this->config->get('sales_product');
		}
		
		if (isset($this->request->post['sales_order_history'])) {
			$this->data['sales_order_history'] = $this->request->post['sales_order_history'];
		} else {
			$this->data['sales_order_history'] = $this->config->get('sales_order_history');
		}
		
		if (isset($this->request->post['sales_order_history_update'])) {
			$this->data['sales_order_history_update'] = $this->request->post['sales_order_history_update'];
		} else {
			$this->data['sales_order_history_update'] = $this->config->get('sales_order_history_update');
		}
		
		if (isset($this->request->post['sign_up'])) {
			$this->data['sign_up'] = $this->request->post['sign_up'];
		} else {
			$this->data['sign_up'] = $this->config->get('sign_up');
		}
		
		if (isset($this->request->post['signup_auto_approval'])) {
			$this->data['signup_auto_approval'] = $this->request->post['signup_auto_approval'];
		} else {
			$this->data['signup_auto_approval'] = $this->config->get('signup_auto_approval');
		}
		
		$this->load->model('catalog/commission');
		$this->data['signup_commissions'] = $this->model_catalog_commission->getCommissions();
		
		if (isset($this->request->post['signup_commission'])) {
			$this->data['signup_commission'] = $this->request->post['signup_commission'];
		} else {
			$this->data['signup_commission'] = $this->config->get('signup_commission');
		}
		
		/*add*/
		$this->load->model('catalog/prolimit');
		$this->data['prolimits'] = $this->model_catalog_prolimit->getLimits();
		
		if (isset($this->request->post['signup_product_limit'])) {
			$this->data['signup_product_limit'] = $this->request->post['signup_product_limit'];
		} else {
			$this->data['signup_product_limit'] = $this->config->get('signup_product_limit');
		}
		
		$this->load->model('catalog/information');
		$this->data['informations'] = $this->model_catalog_information->getInformations();
		
		if (isset($this->request->post['signup_policy'])) {
			$this->data['signup_policy'] = $this->request->post['signup_policy'];
		} else {
			$this->data['signup_policy'] = $this->config->get('signup_policy');
		}
		
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategories(0);
		
		$this->data['signup_category'] = array();
		
		if (isset($this->request->post['signup_category'])) {
			$this->data['signup_category'] = $this->request->post['signup_category'];
		} else {
			$this->data['signup_category'] = $this->config->get('signup_category');
		}
		
		$this->load->model('setting/store');		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->data['signup_store'] = array();
		
		if (isset($this->request->post['signup_store'])) {
			$this->data['signup_store'] = $this->request->post['signup_store'];
		} else {
			$this->data['signup_store'] = $this->config->get('signup_store');
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		
		
		$this->template = 'catalog/vendor_setting.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/vendor_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['vendor_email_message']) {
			$this->error['code_message'] = $this->language->get('error_code_message');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>