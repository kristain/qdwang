<?php
class ControllerReportVendorTransaction extends Controller { 
	public function index() {  
		$this->load->language('report/vendor_transaction');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('report/vendor_transaction');
	
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
		if (isset($this->request->get['filter_vendor_group'])) {
			$filter_vendor_group = $this->request->get['filter_vendor_group'];
		} else {
			$filter_vendor_group = 0;
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$filter_paid_status = $this->request->get['filter_paid_status'];
		} else {
			$filter_paid_status = 0;
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_vendor_group'])) {
			$url .= '&filter_vendor_group=' . $this->request->get['filter_vendor_group'];
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
			'href'      => $this->url->link('report/vendor_transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
			
		$this->load->model('report/vendor_transaction');
		
		$this->data['orders'] = array();
		$this->data['histories'] = array();
		
		$data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_vendor_group'    => $filter_vendor_group,
			'filter_paid_status'     => $filter_paid_status,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$order_total = $this->model_report_vendor_transaction->getVendorTotalOrders($data);
		$results = $this->model_report_vendor_transaction->getVendorOrders($data);
		$store_revenues = $this->model_report_vendor_transaction->getVendorTotalAmount($data);
		$this->data['vendors_name'] = $this->model_report_vendor_transaction->getVendorsName($data);
		
		$payment_histories = $this->model_report_vendor_transaction->getPaymentHistory(0);
		
		$shipping_charges = $this->model_report_vendor_transaction->getShippingChargedTotal($data);
		
		$shipping_charged = 0;
		foreach ($shipping_charges AS $shipping_charge) {
			$shipping_charged = $shipping_charge['shipping_charged'];
			$shipping_tax = $shipping_charge['shipping_tax'];
		}

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
	
		$this->data['commission_data'] = $this->model_report_vendor_transaction->getCommissionData();
		
		foreach ($payment_histories as $payment_history) {
			$this->data['histories'][] = array (
				'payment_id'	=> $payment_history['payment_id'],
				'name'			=> $payment_history['name'],
				'details'		=> unserialize(trim($payment_history['details'])),
				'amount'		=> $this->currency->format($payment_history['payment_amount'], $this->config->get('config_currency')),
				'payment_type'	=> $payment_history['payment_type'],
				'date'			=> date($this->language->get('date_format_short'), strtotime($payment_history['payment_date']))				
				);
		}
		
		foreach ($results as $result) {
			$this->data['orders'][] = array(
				'product_id'	=> $result['product_id'],
				'date' 			=> date($this->language->get('date_format_short'), strtotime($result['date'])),
				'order_id'  	=> $result['order_id'],
				'order_product_id'	=> $result['order_product_id'],
				'order_status'  => $result['order_status'],
				'product_name'  => $result['product_name'],
				'price'         => $this->currency->format($result['price'] + ($this->config->get('tax_status') ? $result['tax']: 0), $this->config->get('config_currency')),
				'quantity'  	=> $result['quantity'],
				'vendor_id'		=> $result['vendor_id'],
				'commission'  	=> $this->currency->format($result['commission'] + ($this->config->get('tax_status') ? $result['store_tax']: 0), $this->config->get('config_currency')),
				'total'			=> $this->currency->format($result['total'] + ($this->config->get('tax_status') ? $result['total_tax']: 0), $this->config->get('config_currency')),
				'amount'  		=> $this->currency->format($result['amount'] + ($this->config->get('tax_status') ? $result['vendor_tax']: 0),$this->config->get('config_currency')),
				'paypal_amount'	=> $this->currency->format($result['amount'] + ($this->config->get('tax_status') ? $result['vendor_tax']: 0),$this->config->get('config_currency'), false, false),
				'paypal_email'	=> $this->model_report_vendor_transaction->getVendorPaypalEmail($result['vendor_id']),
				'tax'			=> $result['total_tax'],
				'store_tax'		=> $result['store_tax'],
				'vendor_tax'	=> $result['vendor_tax'],
				'paid_status'   => $result['paid_status']
			);
		}

		foreach ($store_revenues AS $store) {
			$this->data['store_revenue'][] = array (
				'vendor_id'			=> $store['vendor_id'],
				'paypal_email'		=> $store['paypal_email'],
				'company'			=> $store['company'],
				'paid_amount'		=> $store['vendor_amount'],
				'shipping_charged'	=> $this->currency->format($shipping_charged + ($this->config->get('tax_status') ? $shipping_tax: 0), $this->config->get('config_currency')),
				'vendor_amount'  	=> $this->currency->format($store['vendor_amount'] + ($this->config->get('tax_status') ? $store['total_vendor_tax']: 0), $this->config->get('config_currency')),
				'amount_pay_vendor' => $this->currency->format($store['vendor_amount'] + $shipping_charged + ($this->config->get('tax_status') ? ($store['total_vendor_tax'] + $shipping_tax): 0), $this->config->get('config_currency')),
				'paypal_amount' 	=> $this->currency->format($store['vendor_amount'] + $shipping_charged + ($this->config->get('tax_status') ? ($store['total_vendor_tax'] + $shipping_tax): 0), $this->config->get('config_currency'), false, false),
				'commission'  		=> $this->currency->format($store['commission'] + ($this->config->get('tax_status') ? $store['total_store_tax']: 0), $this->config->get('config_currency')),
				'revenue_shipping' 	=> $this->currency->format($store['gross_amount'] + $shipping_charged + ($this->config->get('tax_status') ? ($store['total_tax'] + $shipping_tax): 0), $this->config->get('config_currency')),
				'gross_amount'  	=> $this->currency->format($store['gross_amount'] + ($this->config->get('tax_status') ? $store['total_tax']: 0), $this->config->get('config_currency'))
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		$this->data['text_all_vendors'] = $this->language->get('text_all_vendors');
		$this->data['text_gross_incomes'] = $this->language->get('text_gross_incomes');
		$this->data['text_commission'] = $this->language->get('text_commission');
		$this->data['text_shipping'] = $this->language->get('text_shipping');
		$this->data['text_vendor_earning'] = $this->language->get('text_vendor_earning');
		$this->data['text_vendor_revenue'] = $this->language->get('text_vendor_revenue');
		$this->data['text_amount_pay_vendor'] = $this->language->get('text_amount_pay_vendor');
		$this->data['text_payment_history'] = $this->language->get('text_payment_history');
		$this->data['text_vendor_payment_history'] = $this->language->get('text_vendor_payment_history');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['title_payment_type'] = $this->language->get('title_payment_type');
		$this->data['title_gross_revenue'] = $this->language->get('title_gross_revenue');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_order_id'] = $this->language->get('column_order_id');
    	$this->data['column_product_name'] = $this->language->get('column_product_name');
		$this->data['column_unit_price'] = $this->language->get('column_unit_price');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_commission'] = $this->language->get('column_commission');
		$this->data['column_amount'] = $this->language->get('column_amount');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_transaction_status'] = $this->language->get('column_transaction_status');
		$this->data['column_paid_status'] = $this->language->get('column_paid_status');
		$this->data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$this->data['column_payment_amount'] = $this->language->get('column_payment_amount');
		$this->data['column_payment_type'] = $this->language->get('column_payment_type');
		$this->data['column_payment_date'] = $this->language->get('column_payment_date');
		$this->data['column_order_product'] = $this->language->get('column_order_product');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_group'] = $this->language->get('entry_group');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		
		$this->data['button_Paypal'] = $this->language->get('button_Paypal');
		$this->data['button_addPayment'] = $this->language->get('button_addPayment');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_vendor_group'])) {
			$url .= '&filter_vendor_group=' . $this->request->get['filter_vendor_group'];
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/vendor_transaction', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;	
		$this->data['filter_vendor_group'] = $filter_vendor_group;	
		$this->data['filter_paid_status'] = $filter_paid_status;		
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		
		$this->data['addPayment'] = $this->url->link('report/vendor_transaction/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
				 
		$this->template = 'report/vendor_transaction.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function insert() {
    	$this->load->language('report/vendor_transaction');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('report/vendor_transaction');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
			} else {
				$filter_date_start = '';
			}

			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
			} else {
				$filter_date_end = '';
			}
			
			if (isset($this->request->get['filter_vendor_group'])) {
				$filter_vendor_group = $this->request->get['filter_vendor_group'];
			} else {
				$filter_vendor_group = 0;
			}
			
			if (isset($this->request->get['filter_paid_status'])) {
				$filter_paid_status = $this->request->get['filter_paid_status'];
			} else {
				$filter_paid_status = 0;
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
			} else {
				$filter_order_status_id = 0;
			}	
					
			$url = '';
							
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			
			if (isset($this->request->get['filter_vendor_group'])) {
				$url .= '&filter_vendor_group=' . $this->request->get['filter_vendor_group'];
			}
			
			if (isset($this->request->get['filter_paid_status'])) {
				$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
				
			$this->load->model('report/vendor_transaction');
			
			$orders = array();
			$store_revenue = array();
			
			$data = array(
				'filter_date_start'	     => $filter_date_start, 
				'filter_date_end'	     => $filter_date_end, 
				'filter_vendor_group'    => $filter_vendor_group,
				'filter_paid_status'     => $filter_paid_status,
				'filter_order_status_id' => $filter_order_status_id
			);
			
			$results = $this->model_report_vendor_transaction->getVendorOrders($data);
			
			$store_revenues = $this->model_report_vendor_transaction->getVendorTotalAmount($data);
			
			$shipping_charges = $this->model_report_vendor_transaction->getShippingChargedTotal($data);
		
			$shipping_charged = 0;
			foreach ($shipping_charges AS $shipping_charge) {
				$shipping_charged = $shipping_charge['shipping_charged'];
				$shipping_tax = $shipping_charge['shipping_tax'];
			}
				
			foreach ($results as $result) {
				$orders[] = array(
					'product_id'	=> $result['product_id'],
					'order_id'  	=> $result['order_id'],
					'product_name'  => $result['product_name'],
					'paid_status'   => $result['paid_status']
				);
			}
			
			$payment_option = $this->request->get['payment_option'];
			$chequeno = isset($this->request->get['chequeno']) ? $this->request->get['chequeno'] : '';
			$opm = isset($this->request->get['opm']) ? $this->request->get['opm'] : '';
			
			switch ($payment_option) {
				case 'paypal_standard':
				$payment_type = 'Paypal Standard';
				break;
				
				case 'pay_cheque':
				$payment_type = 'Pay Cheque' . ' - No. : ' . $chequeno;
				break;
				
				case 'other_payment_method':
				$payment_type = 'Other Payment Method' . ' - ' . $opm;
				break;
			}
			
			foreach ($store_revenues AS $store) {
				$store_revenue[] = array (
					'vendor_id'			=> $store['vendor_id'],
					'payment_type'		=> $payment_type,
					'paid_amount'		=> $store['vendor_amount'] + $shipping_charged + ($this->config->get('tax_status') ? ($shipping_tax + $store['total_vendor_tax']): 0)
				);
			}
		
			$this->model_report_vendor_transaction->addPaymentToVendor($store_revenue,serialize($orders));
			
			$this->redirect($this->url->link('report/vendor_transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->response->setOutput($this->render());
  	}
	
	public function removeHistory() {
		$this->language->load('report/vendor_transaction');

		$this->load->model('report/vendor_transaction');

		$json = array();

		if (!$this->user->hasPermission('modify', 'report/vendor_transaction')) {
      		$json['error'] = $this->language->get('error_permission');
    	} else {
		
			if (isset($this->request->get['payment_id'])) {
				$this->model_report_vendor_transaction->removeHistory($this->request->get['payment_id']);
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}
	
	public function addPaymentRecord() {
		
		$this->language->load('report/vendor_transaction');
		$this->load->model('report/vendor_transaction');
		
		$order_detail = array();
		$order_id = $this->request->get['oid'];
		$product_id = $this->request->get['pid'];
		$order_product_id = $this->request->get['opid'];
		
		$getOPs = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product op WHERE op.order_id = '" . (int)$order_id . "' AND op.product_id = '" . (int)$product_id . "' AND op.order_product_id = '" . (int)$order_product_id . "' AND op.vendor_paid_status = '0'");
				
		if ($getOPs->row) {
			$order_detail[] = array(
				'product_id'	=> $getOPs->row['product_id'],
				'order_id'  	=> $getOPs->row['order_id'],
				'product_name'  => $getOPs->row['name']
			);
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_payment SET vendor_id = '" . (int)$getOPs->row['vendor_id'] . "', payment_info = '" . serialize($order_detail) . "', payment_amount = '" . (float)($getOPs->row['vendor_total']+($this->config->get('tax_status') ? $getOPs->row['vendor_tax']: 0)) . "', payment_type = '" . $this->language->get('text_paypal_standard') ."', payment_status = '5', payment_date = Now()");
			$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '1' WHERE op.order_id = '" . (int)$getOPs->row['order_id'] . "' AND op.product_id = '" . (int)$getOPs->row['product_id'] . "' AND op.order_product_id = '" . (int)$order_product_id . "' AND op.vendor_paid_status = '0'");					
		}
		
		$this->redirect($this->url->link('report/vendor_transaction', 'token=' . $this->session->data['token'], 'SSL'));

		$this->response->setOutput($this->render());
  	}
}
?>