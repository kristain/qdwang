<?php
class ControllerSalePaymentHistory extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/payment_history');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/payment_history');

    	$this->getList();
  	}
	
   	private function getList() {
			
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

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
			'href'      => $this->url->link('sale/payment_history', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
	
		$this->data['histories'] = array();

		$data = array(
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$payment_histories = $this->model_sale_payment_history->getPaymentHistory($data);
		$order_total = $this->model_sale_payment_history->getTotalPaymentHistory($data);

    	foreach ($payment_histories as $payment_history) {
			$this->data['histories'][] = array (
				'payment_id'	=> $payment_history['payment_id'],
				'name'			=> $payment_history['name'],
				'details'		=> unserialize(trim($payment_history['details'])),
				'amount'		=> $this->currency->format($payment_history['payment_amount'], $this->config->get('config_currency')),
				'date'			=> date($this->language->get('date_format_short'), strtotime($payment_history['payment_date']))				
				);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$this->data['column_order_product'] = $this->language->get('column_order_product');
		$this->data['column_payment_amount'] = $this->language->get('column_payment_amount');
		$this->data['column_payment_date'] = $this->language->get('column_payment_date');

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
		
		$json['success'] = $this->language->get('text_success');
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/payment_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/payment_history_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
  	}	
}
?>