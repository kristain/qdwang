<?php 
class ControllerAffiliateAccount extends Controller { 
	public function index() {
		if (!$this->affiliate->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('affiliate/account', '', 'SSL');
	  
	  		$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
    	} 
	
		$this->language->load('affiliate/account');

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('affiliate/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

$this->data['text_balance'] = $this->language->get('text_balance');
		$this->document->setTitle($this->language->get('heading_title'));
$this->load->model('affiliate/affiliate');
			$this->load->model('affiliate/transaction');

    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
    	$this->data['text_my_tracking'] = $this->language->get('text_my_tracking');
		$this->data['text_my_transactions'] = $this->language->get('text_my_transactions');
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_password'] = $this->language->get('text_password');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_tracking'] = $this->language->get('text_tracking');
		$this->data['text_transaction'] = $this->language->get('text_transaction');
$this->data['balance'] = $this->currency->format($this->model_affiliate_transaction->getBalance());
			
		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliate($this->affiliate->getId());
		}

		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($affiliate_info)) {
			$this->data['firstname'] = $affiliate_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}
		
		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($affiliate_info)) {
			$this->data['lastname'] = $affiliate_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

    	$this->data['edit'] = $this->url->link('affiliate/edit', '', 'SSL');
		$this->data['password'] = $this->url->link('affiliate/password', '', 'SSL');
		$this->data['payment'] = $this->url->link('affiliate/payment', '', 'SSL');
		$this->data['tracking'] = $this->url->link('affiliate/tracking', '', 'SSL');
    	$this->data['transaction'] = $this->url->link('affiliate/transaction', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/affiliate/account.tpl';
		} else {
			$this->template = 'default/template/affiliate/account.tpl';
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
}
?>