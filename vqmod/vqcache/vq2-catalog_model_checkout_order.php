<?php
class ModelCheckoutOrder extends Model {	
	public function addOrder($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "',  email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_company_id = '" . $this->db->escape($data['payment_company_id']) . "', payment_tax_id = '" . $this->db->escape($data['payment_tax_id']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "',  shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', language_id = '" . (int)$data['language_id'] . "', currency_id = '" . (int)$data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float)$data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" .  $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', date_added = NOW(), date_modified = NOW()");

		$order_id = $this->db->getLastId();

		foreach ($data['products'] as $product) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET order_id = '" . (int)$order_id . "', product_id = '" . (int)$product['product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (int)$product['quantity'] . "'
				, price = '" . (float)$product['price'] . "', cost = '" . (float)$product['cost'] . "'
			, total = '" . (float)$product['total'] . "', tax = '" . (float)$product['tax'] . "', reward = '" . (int)$product['reward']. "', commission =  '" . (float)$product['commission']."', vendor_id =  '" . (int)$product['vendor_id'] . "', vendor_total = '" . (float)$product['vendor_total'] . "', vendor_tax = '" . (float)$product['vendor_tax'] . "', store_tax = '" . (float)$product['store_tax'] . "'");
 
			$order_product_id = $this->db->getLastId();

			foreach ($product['option'] as $option) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', product_option_id = '" . (int)$option['product_option_id'] . "', product_option_value_id = '" . (int)$option['product_option_value_id'] . "', name = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
			}
				
			foreach ($product['download'] as $download) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_download SET order_id = '" . (int)$order_id . "', order_product_id = '" . (int)$order_product_id . "', name = '" . $this->db->escape($download['name']) . "', filename = '" . $this->db->escape($download['filename']) . "', mask = '" . $this->db->escape($download['mask']) . "', remaining = '" . (int)($download['remaining'] * $product['quantity']) . "'");
			}	

			/*Get Vendor ID*/
			$getVenId = $this->db->query("SELECT vs.vendor_id AS vendor_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vs ON (v.vendor = vs.vendor_id) WHERE p.product_id = '" . (int)$product['product_id'] . "'");
			$getVenIds[] = array ('vendor_id' => $getVenId->row['vendor_id']);
			/*Get Vendor ID*/
			
		}
		

			/*Add shipping fee per vendor*/
			$vendor_cost = $this->getdescost();	
			$getUniVenId = array_map("unserialize", array_unique(array_map("serialize", $getVenIds)));
			foreach ($getUniVenId as $vendor) {
				if ($vendor['vendor_id']) {
					if ($this->session->data['shipping_method']['code'] == 'mvweight.mvweight') {
						$scost = 0;
						$sweight = 0;
									
						foreach ($vendor_cost as $vsc) {
							if ($vendor['vendor_id'] == $vsc['vendor_id']) {
								$scost = $vsc['cost'];
								$sweight = $vsc['weight'];
							}
						}
						
						if ($this->config->get('mvweight_tax_class_id')) {
							$shipping_tax = $this->tax->calculate($scost, $this->config->get('mvweight_tax_class_id'), $this->config->get('config_tax')) - $scost;
						} else {
							$shipping_tax = '0';
						}
						
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_shipping SET order_id = '" . (int)$order_id . "', vendor_id =  '" . (int)$vendor['vendor_id'] . "', title = 'Weight Base Shipping', cost =  '" . (float)$scost . "', tax =  '" . (float)$shipping_tax . "', weight = '" . (float)$sweight . "'");
					
					} elseif ($this->session->data['shipping_method']['code'] == 'mvflat.mvflat') {			
						$mv_flat = $this->mv_flat_shipping();			
						$scost = 0;
						$sweight = 0;
		
						foreach ($mv_flat as $mvflat) {
							if ($vendor['vendor_id'] == $mvflat['vendor']) {
								$scost += $mvflat['cost'];
								$sweight += $mvflat['weight'];
							}
						}
						
						if ($this->config->get('mvflat_tax_class_id')) {
							$shipping_tax = $this->tax->calculate($scost, $this->config->get('mvflat_tax_class_id'), $this->config->get('config_tax')) - $scost;
						} else {
							$shipping_tax = '0';
						}
						
						$this->db->query("INSERT INTO " . DB_PREFIX . "order_shipping SET order_id = '" . (int)$order_id . "', vendor_id =  '" . (int)$vendor['vendor_id'] . "', title = 'Flat Rate Shipping', tax =  '" . (float)$shipping_tax . "', cost =  '" . (float)$scost . "', weight = '" . (float)$sweight . "'");
					}
				}
			}
			/*Add shipping fee per vendor*/
			
		foreach ($data['vouchers'] as $voucher) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_voucher SET order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($voucher['description']) . "', code = '" . $this->db->escape($voucher['code']) . "', from_name = '" . $this->db->escape($voucher['from_name']) . "', from_email = '" . $this->db->escape($voucher['from_email']) . "', to_name = '" . $this->db->escape($voucher['to_name']) . "', to_email = '" . $this->db->escape($voucher['to_email']) . "', voucher_theme_id = '" . (int)$voucher['voucher_theme_id'] . "', message = '" . $this->db->escape($voucher['message']) . "', amount = '" . (float)$voucher['amount'] . "'");
		}
			
		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int)$order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', text = '" . $this->db->escape($total['text']) . "', `value` = '" . (float)$total['value'] . "', sort_order = '" . (int)$total['sort_order'] . "'");
		}	

		return $order_id;
	}

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");
			
		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}			
			
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");
			
			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';				
			}
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}
			
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);
			
			if ($language_info) {
				$language_code = $language_info['code'];
				$language_filename = $language_info['filename'];
				$language_directory = $language_info['directory'];
			} else {
				$language_code = '';
				$language_filename = '';
				$language_directory = '';
			}
		 			
			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],				
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],				
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],	
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],				
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],	
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'language_filename'       => $language_filename,
				'language_directory'      => $language_directory,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'], 
				'user_agent'              => $order_query->row['user_agent'],	
				'accept_language'         => $order_query->row['accept_language'],				
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added']
			);
		} else {
			return false;	
		}
	}	

	public function confirm($order_id, $order_status_id, $comment = '', $notify = false) {
		$order_info = $this->getOrder($order_id);
		 
		if ($order_info && !$order_info['order_status_id']) {
			// Fraud Detection
			if ($this->config->get('config_fraud_detection')) {
				$this->load->model('checkout/fraud');
				
				$risk_score = $this->model_checkout_fraud->getFraudScore($order_info);
				
				if ($risk_score > $this->config->get('config_fraud_score')) {
					$order_status_id = $this->config->get('config_fraud_status_id');
				}
			}

			// Blacklist
			$status = false;
			
			$this->load->model('account/customer');
			
			if ($order_info['customer_id']) {
				$results = $this->model_account_customer->getIps($order_info['customer_id']);
				
				foreach ($results as $result) {
					if ($this->model_account_customer->isBlacklisted($result['ip'])) {
						$status = true;
						
						break;
					}
				}
			} else {
				$status = $this->model_account_customer->isBlacklisted($order_info['ip']);
			}
			
			if ($status) {
				$order_status_id = $this->config->get('config_order_status_id');
			}		
				
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '1', comment = '" . $this->db->escape(($comment && $notify) ? $comment : '') . "', date_added = NOW()");

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($order_product_query->rows as $order_product) {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_id = '" . (int)$order_product['product_id'] . "' AND subtract = '1'");

			/*code start*/
			$option_data_vendor = array();
			$order_option_vendor_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_option` WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");
			foreach ($order_option_vendor_query->rows as $option) {
			  if ($option['type'] != 'file') {
				$option_data_vendor[] = array(
				  'name'  => $option['name'],
				  'value' => (strlen($option['value']) > 20 ? substr($option['value'], 0, 20) . '..' : $option['value'])
				);
			  } else {
				  $filename = substr($option['value'], 0, strrpos($option['value'], '.'));
				  $option_data_vendor[] = array(
				  'name'  => $option['name'],
				  'value' => (strlen($filename) > 20 ? substr($filename, 0, 20) . '..' : $filename)
				);	
			  }
			}
				
			$vmail = $this->db->query("SELECT pd.name AS name, p.model AS model, p.sku AS sku, vs.email AS email, vs.vendor_id AS vendor_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "vendor v ON (pd.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vs ON (v.vendor = vs.vendor_id) WHERE p.product_id = '" . (int)$order_product['product_id'] . "'");
				$vendor_products[] = array (
					'name'  	=> $vmail->row['name'],
					'option'	=> $option_data_vendor,
					'model' 	=> $vmail->row['model'],
					'sku' 		=> $vmail->row['sku'],
					'price'		=> $order_product['price'],
					'quantity'	=> $order_product['quantity'],
					'total'		=> $order_product['total'],
					'tax'		=> $order_product['tax'],
					'vendor_id'	=> $vmail->row['vendor_id'],
					'email'		=> $vmail->row['email']
				);
					
				$vendor_list[] = array ('vendor_id' => $vmail->row['vendor_id']);
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_status_vendor_update SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', vendor_id = '" . (int)$vmail->row['vendor_id'] . "', product_id = '" . (int)$order_product['order_product_id'] . "'");
			/*code end*/
			
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product['order_product_id'] . "'");
			
				foreach ($order_option_query->rows as $option) {
					$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET quantity = (quantity - " . (int)$order_product['quantity'] . ") WHERE product_option_value_id = '" . (int)$option['product_option_value_id'] . "' AND subtract = '1'");
				}
			}
			
			$this->cache->delete('product');

			/*code start*/
			$vendor_unique = array_map("unserialize", array_unique(array_map("serialize", $vendor_list))); 			
			if (($this->config->get('vendor_email_status')) && (in_array((int)$order_status_id,$this->config->get('vendor_checkout_order_status')))) {
				if ($vendor_products){
					$vendor_cost = $this->getdescost();					
					foreach ($vendor_unique as $vendor) {
						if ($vendor['vendor_id']) {
							$vemail = $this->db->query("SELECT *, CONCAT(firstname,' ',lastname) AS contact_name FROM `" . DB_PREFIX . "vendors` WHERE vendor_id = '" . (int)$vendor['vendor_id'] . "'");
							$cust_order_status_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
							$language = new Language($order_info['language_directory']);
							$language->load($order_info['language_filename']);
							$language->load('mail/vendor_email');
							$template = new Template();
							
							$mail = new Mail(); 
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->hostname = $this->config->get('config_smtp_host');
							$mail->username = $this->config->get('config_smtp_username');
							$mail->password = $this->config->get('config_smtp_password');
							$mail->port = $this->config->get('config_smtp_port');
							$mail->timeout = $this->config->get('config_smtp_timeout');
							$mail->setTo($vemail->row['email']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender($this->config->get('config_name'));
							$mail->setSubject($language->get('text_vendor_email_subject') . $this->config->get('config_title'));
									
							$template->data['text_order_details'] = $language->get('text_order_details');
							$template->data['text_shipping_address'] = "<b>" . $language->get('text_shipping_address') . "</b><br/>";
							$template->data['date_ordered'] = '<b>' . $language->get('text_date_ordered') . ' </b>' . date('F j\, Y') . '<br/>';
							$template->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
							$template->data['store_name'] = $order_info['store_name'];
							$template->data['store_url'] = $order_info['store_url'];
							
							/*Show header title*/
							$template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);		
							
							/*show vendor name*/
							$template->data['vendor_name'] = '<b>' . $language->get('text_title') . $vemail->row['contact_name'] . '</b>,' . "\n\n";
							
							/*show message to vendor*/
							$template->data['vendor_message'] = $this->config->get('vendor_email_message') . "\n\n";
																				
							/*show vendor customer order id*/
							if ($this->config->get('vendor_cust_order_id')) {
								$template->data['order_id'] = '<b>' . $language->get('text_vendor_order_id') . '</b>' . $order_id . '<br/>';
							} else {
								$template->data['order_id'] = '';
							}
							
							/*show vendor customer order status*/
							if ($this->config->get('vendor_cust_order_status')) {
								$template->data['order_status'] = '<b>' . $language->get('text_order_status') . ' </b>' . $cust_order_status_query->row['name'] . '<br/>';
							} else {
								$template->data['order_status'] = '';
							}
							
							/*show payment method*/
							if ($this->config->get('vendor_cust_payment_method')) {
								$template->data['payment_method'] = '<b>' . $language->get('text_payment_method') . ' </b>' . $order_info['payment_method'] . '<br/>';
							} else {
								$template->data['payment_method'] = '';
							}
							
							/*show vendor customer email*/
							if ($this->config->get('vendor_cust_email')) {
								$template->data['email_address'] = '<b>' . $language->get('text_email') . ' </b>' . $order_info['email'] . '<br/>';
							} else {
								$template->data['email_address'] = '';
							}
							
							/*show vendor customer telephone*/
							if ($this->config->get('vendor_cust_telephone')) {
								$template->data['telephone'] = '<b>' . $language->get('text_telephone') . ' </b>' . $order_info['telephone'] . '<br/>';
							} else {
								$template->data['telephone'] = '';
							}
							
							/*show vendor customer shipping address*/
							if ($this->config->get('vendor_cust_shipping_address')) {
								if (($order_info['shipping_firstname']) && ($order_info['shipping_address_1'])) { 
									$format = '<b>{firstname} {lastname}</b>' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
									$find = array(
									'{firstname}',
									'{lastname}',
									'{company}',
									'{address_1}',
									'{address_2}',
									'{city}',
									'{postcode}',
									'{zone}',
									'{zone_code}',
									'{country}'
									);
								
									$replace = array(
										'firstname' => $order_info['shipping_firstname'],
										'lastname'  => $order_info['shipping_lastname'],
										'company'   => $order_info['shipping_company'],
										'address_1' => $order_info['shipping_address_1'],
										'address_2' => $order_info['shipping_address_2'],
										'city'      => $order_info['shipping_city'],
										'postcode'  => $order_info['shipping_postcode'],
										'zone'      => $order_info['shipping_zone'],
										'zone_code' => $order_info['shipping_zone_code'],
										'country'   => $order_info['shipping_country']  
									);
									$template->data['cust_shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
								} else {
									$format = '<b>{firstname} {lastname}</b>' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
									$find = array(
										'{firstname}',
										'{lastname}',
										'{company}',
										'{address_1}',
										'{address_2}',
										'{city}',
										'{postcode}',
										'{zone}',
										'{zone_code}',
										'{country}'
									);
								
									$replace = array(
										'firstname' => $order_info['payment_firstname'],
										'lastname'  => $order_info['payment_lastname'],
										'company'   => $order_info['payment_company'],
										'address_1' => $order_info['payment_address_1'],
										'address_2' => $order_info['payment_address_2'],
										'city'      => $order_info['payment_city'],
										'postcode'  => $order_info['payment_postcode'],
										'zone'      => $order_info['payment_zone'],
										'zone_code' => $order_info['payment_zone_code'],
										'country'   => $order_info['payment_country']  
									);
									$template->data['cust_shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
								}
							} else {
								$template->data['cust_shipping_address'] = '';
							}
							
							/*show vendor information*/
							if ($this->config->get('vendor_address')) {
								$template->data['show_vendor_contact'] = True;
								$format = '<b>{firstname} {lastname}</b>' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
									$find = array(
										'{firstname}',
										'{lastname}',
										'{company}',
										'{address_1}',
										'{address_2}',
										'{city}',
										'{postcode}',
										'{zone}',
										'{country}'
									);
									
								$zone_name = $this->db->query("SELECT name FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$vemail->row['zone_id'] . "' AND country_id = '" . (int)$vemail->row['country_id'] . "'");			
								$country_name = $this->db->query("SELECT name FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$vemail->row['country_id'] . "'");	
								
									$replace = array(
										'firstname' => $vemail->row['firstname'],
										'lastname'  => $vemail->row['lastname'],
										'company'   => $vemail->row['company'],
										'address_1' => $vemail->row['address_1'],
										'address_2' => $vemail->row['address_2'],
										'city'      => $vemail->row['city'],
										'postcode'  => $vemail->row['postcode'],
										'zone'  	=> isset($zone_name->row['name']) ? $zone_name->row['name'] : 'None',
										'country'   => $country_name->row['name']
									);
									
								$vendor_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
										
								$template->data['vendor_address'] = $vendor_address . '<br/>';
								$template->data['text_vendor_contact'] = $language->get('text_vendor_contact');
								
								/*show vendor email*/
								if ($this->config->get('vendor_email')) {
									$template->data['vendor_email'] = '<b>' . $language->get('text_email') . ' </b>' . $vemail->row['email'] . '<br/>';
								} else {
									$template->data['vendor_email'] = '';
								}
								
								/*show vendor telephone*/
								if ($this->config->get('vendor_telephone')) {
									$template->data['vendor_telephone'] = '<b>' . $language->get('text_telephone') . ' </b>' . $vemail->row['telephone'] . '<br/>';
								} else {
									$template->data['vendor_telephone'] = '';
								}
				
							} else {
								$template->data['show_vendor_contact'] = False;
							}
							/*end show vendor address*/
							
							$subtotal = 0;
							$vsubtotal = $this->db->query("SELECT SUM(total) AS sum_product_total, SUM(quantity*tax) as sum_product_tax FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "vendor v ON ( op.product_id = v.vproduct_id ) WHERE v.vendor =  '" . (int)$vendor['vendor_id'] . "' AND op.order_id =  '" . (int)$order_id . "'");
							$subtotal = $vsubtotal->row['sum_product_total'];
							$vat = $this->db->query("SELECT title FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' AND code = 'tax'");
							
							/*Get Shipping Cost*/
							$shipcost = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_shipping` WHERE vendor_id = '" . (int)$vendor['vendor_id'] . "' AND order_id = '" . (int)$order_id . "'");
							
							if ($this->config->get('tax_status') && ($vsubtotal->row['sum_product_tax'] != 0)) {
								$template->data['text_tax'] = $vat->row['title'];
								$template->data['tax'] = $this->currency->format($vsubtotal->row['sum_product_tax'] + (isset($shipcost->row['tax']) ? $shipcost->row['tax'] : '0'));
							} else {
								$template->data['tax'] = '0';
							}
								
							if ($shipcost->rows) {
								if ($shipcost->row['cost']) {
									$total = $vsubtotal->row['sum_product_total'] + $shipcost->row['cost'] + ($this->config->get('tax_status') ? ($vsubtotal->row['sum_product_tax'] + $shipcost->row['tax']): 0);
								} else {
									$total = $vsubtotal->row['sum_product_total'] + ($this->config->get('tax_status') ? $vsubtotal->row['sum_product_tax']: 0);
								}
								
								$template->data['shipping'] = $shipcost->row['title'] . ' (' . $this->weight->format($shipcost->row['weight'], $this->config->get('config_weight_class_id')) . ')';
								$template->data['scost'] = $this->currency->format($shipcost->row['cost']);
								
							} else {
								$total = $vsubtotal->row['sum_product_total'] + ($this->config->get('tax_status') ? $vsubtotal->row['sum_product_tax']: 0);
								$template->data['scost'] = 0;
							}
									
							/*END Get Shipping Cost*/
										
							foreach ($vendor_products as $vendor_product) {
								if ($vendor['vendor_id'] == $vendor_product['vendor_id']) {
									$template->data['vendor_products'][] = array(
										'name'     => $vendor_product['name'],
										'option'   => $vendor_product['option'],
										'model'    => $vendor_product['model'],
										'sku'	   => $vendor_product['sku'],
										'price'	   => $this->currency->format($vendor_product['price'] + ($this->config->get('tax_status') ? $vendor_product['tax'] : 0)),
										'quantity' => $vendor_product['quantity'],
										'total'	   => $this->currency->format($vendor_product['total'] + ($this->config->get('tax_status') ? ($vendor_product['tax'] * $vendor_product['quantity']) : 0)),
										'email'    => $vendor_product['email']
									);
								}
							}
							
							$template->data['product'] = $language->get('column_product');
							$template->data['model'] = $language->get('column_model');
							$template->data['quantity'] = $language->get('column_quantity');
							$template->data['unit_price'] = $language->get('column_unit_price');
							$template->data['total'] = $language->get('column_total');
							$template->data['subtotal'] = $language->get('column_subtotal');
							$template->data['vendor_auto_msg'] = $language->get('text_vendor_auto_msg');
							$template->data['vendor_alert'] = $language->get('text_vendor_email') . $this->config->get('config_title');
							$template->data['vsubtotal'] = $this->currency->format($subtotal);
							$template->data['vtotal'] = $this->currency->format($total);
							
							if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/vendor_email.tpl')) {
								$html = $template->fetch($this->config->get('config_template') . '/template/mail/vendor_email.tpl');
							} else {
								$html = $template->fetch('default/template/mail/vendor_email.tpl');
							}
						
							$mail->setHtml($html);
							$mail->send();
						} 
					}
				}
			} else {
				/*Get Shipping Cost*/
					$shipcost = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_shipping` WHERE vendor_id = '" . (int)$vendor['vendor_id'] . "' AND order_id = '" . (int)$order_id . "'");
							
					if ($this->config->get('tax_status') && ($vsubtotal->row['sum_product_tax'] != 0)) {
						$template->data['text_tax'] = $vat->row['title'];
						$template->data['tax'] = $this->currency->format($vsubtotal->row['sum_product_tax'] + (isset($shipcost->row['tax']) ? $shipcost->row['tax'] : '0'));
					} else {
						$template->data['tax'] = '0';
					}
								
					if ($shipcost->rows) {
						if ($shipcost->row['cost']) {
							$total = $vsubtotal->row['sum_product_total'] + $shipcost->row['cost'] + ($this->config->get('tax_status') ? ($vsubtotal->row['sum_product_tax'] + $shipcost->row['tax']): 0);
						} else {
							$total = $vsubtotal->row['sum_product_total'] + ($this->config->get('tax_status') ? $vsubtotal->row['sum_product_tax']: 0);
						}
								
						$template->data['shipping'] = $shipcost->row['title'] . ' (' . $this->weight->format($shipcost->row['weight'], $this->config->get('config_weight_class_id')) . ')';
						$template->data['scost'] = $this->currency->format($shipcost->row['cost']);
								
					} else {
							$total = $vsubtotal->row['sum_product_total'] + ($this->config->get('tax_status') ? $vsubtotal->row['sum_product_tax']: 0);
							$template->data['scost'] = 0;
					}
				/*END Get Shipping Cost*/
			}
			/*code end*/
			
			
			// Downloads
			$order_download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "'");
			
			// Gift Voucher
			$this->load->model('checkout/voucher');
			
			$order_voucher_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
			
			foreach ($order_voucher_query->rows as $order_voucher) {
				$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $order_voucher);
				
				$this->db->query("UPDATE " . DB_PREFIX . "order_voucher SET voucher_id = '" . (int)$voucher_id . "' WHERE order_voucher_id = '" . (int)$order_voucher['order_voucher_id'] . "'");
			}			
			
			// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->model_checkout_voucher->confirm($order_id);
			}
					
			// Order Totals			
			$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			
			foreach ($order_total_query->rows as $order_total) {
				$this->load->model('total/' . $order_total['code']);
				
				if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
					$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
				}
			}
			
			// Send out order confirmation mail
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');
		 
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
			
			if ($order_status_query->num_rows) {
				$order_status = $order_status_query->row['name'];	
			} else {
				$order_status = '';
			}
			
			$subject = sprintf($language->get('text_new_subject'), $order_info['store_name'], $order_id);
		
			// HTML Mail
			$template = new Template();
			
			$template->data['title'] = sprintf($language->get('text_new_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
			
			$template->data['text_greeting'] = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
			$template->data['text_link'] = $language->get('text_new_link');
			$template->data['text_download'] = $language->get('text_new_download');
			$template->data['text_order_detail'] = $language->get('text_new_order_detail');
			$template->data['text_instruction'] = $language->get('text_new_instruction');
			$template->data['text_order_id'] = $language->get('text_new_order_id');
			$template->data['text_date_added'] = $language->get('text_new_date_added');
			$template->data['text_payment_method'] = $language->get('text_new_payment_method');	
			$template->data['text_shipping_method'] = $language->get('text_new_shipping_method');
			$template->data['text_email'] = $language->get('text_new_email');
			$template->data['text_telephone'] = $language->get('text_new_telephone');
			$template->data['text_ip'] = $language->get('text_new_ip');
			$template->data['text_payment_address'] = $language->get('text_new_payment_address');
			$template->data['text_shipping_address'] = $language->get('text_new_shipping_address');
			$template->data['text_product'] = $language->get('text_new_product');
			$template->data['text_model'] = $language->get('text_new_model');
			$template->data['text_quantity'] = $language->get('text_new_quantity');
			$template->data['text_price'] = $language->get('text_new_price');
			$template->data['text_total'] = $language->get('text_new_total');
			$template->data['text_footer'] = $language->get('text_new_footer');
			$template->data['text_powered'] = $language->get('text_new_powered');
			
			$template->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');		
			$template->data['store_name'] = $order_info['store_name'];
			$template->data['store_url'] = $order_info['store_url'];
			$template->data['customer_id'] = $order_info['customer_id'];
			$template->data['link'] = $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id;
			
			if ($order_download_query->num_rows) {
				$template->data['download'] = $order_info['store_url'] . 'index.php?route=account/download';
			} else {
				$template->data['download'] = '';
			}
			
			$template->data['order_id'] = $order_id;
			$template->data['date_added'] = date($language->get('date_format_short'), strtotime($order_info['date_added']));    	
			$template->data['payment_method'] = $order_info['payment_method'];
			$template->data['shipping_method'] = $order_info['shipping_method'];
			$template->data['email'] = $order_info['email'];
			$template->data['telephone'] = $order_info['telephone'];
			$template->data['ip'] = $order_info['ip']."<br/><b>".$language->get('text_new_comment')."</b> ".$order_info['comment'];
			
			if ($comment && $notify) {
				$template->data['comment'] = nl2br($comment);
			} else {
				$template->data['comment'] = '';
			}
						
			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
		
			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']  
			);
		
			$template->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));						
									
			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
		
			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']  
			);
		
			$template->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
			
			// Products
			$template->data['products'] = array();
				
			foreach ($order_product_query->rows as $product) {
				$option_data = array();
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);					
				}
			  
				$template->data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}
	
			// Vouchers
			$template->data['vouchers'] = array();
			
			foreach ($order_voucher_query->rows as $voucher) {
				$template->data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}
	
			$template->data['totals'] = $order_total_query->rows;
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/mail/order.tpl')) {
				$html = $template->fetch($this->config->get('config_template') . '/template/mail/order.tpl');
			} else {
				$html = $template->fetch('default/template/mail/order.tpl');
			}
			
			// Text Mail
			$text  = sprintf($language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8')) . "\n\n";
			$text .= $language->get('text_new_order_id') . ' ' . $order_id . "\n";
			$text .= $language->get('text_new_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n";
			$text .= $language->get('text_new_order_status') . ' ' . $order_status . "\n\n";
			
			if ($comment && $notify) {
				$text .= $language->get('text_new_instruction') . "\n\n";
				$text .= $comment . "\n\n";
			}
			
			// Products
			$text .= $language->get('text_new_products') . "\n";
			
			foreach ($order_product_query->rows as $product) {
				$text .= $product['quantity'] . 'x ' . $product['name'] . ' (' . $product['model'] . ') ' . html_entity_decode($this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8') . "\n";
				
				$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . $product['order_product_id'] . "'");
				
				foreach ($order_option_query->rows as $option) {
					$text .= chr(9) . '-' . $option['name'] . ' ' . (utf8_strlen($option['value']) > 20 ? utf8_substr($option['value'], 0, 20) . '..' : $option['value']) . "\n";
				}
			}
			
			foreach ($order_voucher_query->rows as $voucher) {
				$text .= '1x ' . $voucher['description'] . ' ' . $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']);
			}
						
			$text .= "\n";
			
			$text .= $language->get('text_new_order_total') . "\n";
			
			foreach ($order_total_query->rows as $total) {
				$text .= $total['title'] . ': ' . html_entity_decode($total['text'], ENT_NOQUOTES, 'UTF-8') . "\n";
			}			
			
			$text .= "\n";
			
			if ($order_info['customer_id']) {
				$text .= $language->get('text_new_link') . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
			}
		
			if ($order_download_query->num_rows) {
				$text .= $language->get('text_new_download') . "\n";
				$text .= $order_info['store_url'] . 'index.php?route=account/download' . "\n\n";
			}
			
			if ($order_info['comment']) {
				$text .= $language->get('text_new_comment') . "\n\n";
				$text .= $order_info['comment'] . "\n\n";
			}
			
			$text .= $language->get('text_new_footer') . "\n\n";
		
			$mail = new Mail(); 
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');			
			$mail->setTo($order_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($order_info['store_name']);
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($html);
			$mail->setText(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
			$mail->send();
         
			// Admin Alert Mail
			if ($this->config->get('config_alert_mail')) {
$mail->setTo($this->config->get('config_email'));

























































				$mail->send();
				
				// Send to additional alert emails
				$emails = explode(',', $this->config->get('config_alert_emails'));
				
				foreach ($emails as $email) {
					if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}				
			}		
		}
	}
	

			Private function getdescost() {
			$purchase_product = array();
			$vendor_country = array();
			$product_vendor = array();
			$purchase_data =array();
			$filtered_geo_zone_id = array();
			$vendor_data = array();
				
			$address = array();
			if (isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id']) { 
				$this->load->model('account/address');
				$address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['payment_address_id']) && $this->session->data['payment_address_id']) { 
				$this->load->model('account/address');
				$address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} else { 
				$address = (isset($this->session->data['guest'])) ? $this->session->data['guest'] : array();
			}

			$country_id	= (isset($address['country_id'])) ? $address['country_id'] : $this->session->data['guest']['payment']['country_id'];
			$zone_id 	= (isset($address['zone_id'])) ? $address['zone_id'] : $this->session->data['guest']['payment']['zone_id'];
			
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$purchase_product = $this->db->query("SELECT vs.vendor_id AS vendor_id, vs.country_id as country_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vs ON (v.vendor = vs.vendor_id) WHERE p.product_id = '" . (int)$product['product_id'] . "'");
						$purchase_data[] = array (
							'product_id'  	=> $product['product_id'],
							'weight'		=> (float)$product['weight'],
							'country_id'	=> $purchase_product->row['country_id'],
							'vendor_id'		=> $purchase_product->row['vendor_id']
						);
							
					$vendor_country[] = $purchase_product->row['country_id'];
					$product_vendor[] = array ('vendor_id'	=> $purchase_product->row['vendor_id']);
				}
			}
					
			$scountry = array_filter(array_unique($vendor_country));
			$svendor = array_map("unserialize", array_unique(array_map("serialize", $product_vendor)));
					
			$pquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY geo_zone_id");
			$pgetvendors = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors");

			foreach ($pquery->rows as $geo_zone) {
				if (in_array($this->config->get('mvweight_' . $geo_zone['geo_zone_id'] . '_scountry'),$scountry)) { 
					foreach ($pgetvendors->rows as $getvendor) {
						if ($this->config->get('mvweight_' . $geo_zone['geo_zone_id'] . '_vendors')) {
							if (in_array($getvendor['vendor_id'],$this->config->get('mvweight_' . $geo_zone['geo_zone_id'] . '_vendors'))) {
								$filtered_geo_zone_id[] = array('geo_zone'	=>	(int)$geo_zone['geo_zone_id']);
							}
						}
					}
				} 
			}
					
			$desGeoZone = array_map("unserialize", array_unique(array_map("serialize", $filtered_geo_zone_id)));
			
			$group_total_weight = 0;
			foreach ($svendor as $vendor) {
				${'group_' . $vendor['vendor_id'] . '_weight'} = '';
				foreach ($purchase_data as $pd_weight) {
					if (isset($pd_weight['product_id'])) {
						if ($pd_weight['vendor_id'] == $vendor['vendor_id']) {
							${'group_' . $vendor['vendor_id'] . '_weight'} += $pd_weight['weight'];
						}
					}
				}
				${'get_group_vendor_id_' . $vendor['vendor_id'] . '_weight'} = 'group_vendor_id_' . $vendor['vendor_id'] . '_weight';
				$group_total_weight += ${'group_' . $vendor['vendor_id'] . '_weight'};
			}
				
			foreach ($desGeoZone as $geo_zone) {
				if ($this->config->get('mvweight_' . (int)$geo_zone['geo_zone'] . '_status')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$geo_zone['geo_zone'] . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");
						
					if ($query->num_rows) {
						$status = true;
					} else {
						$status = false;
					}
							
				} else {
					$status = false;
				}
						
				if ($status) {
					foreach ($svendor as $vendor) {
						if (in_array(preg_replace("/[^0-9]/", '', ${'get_group_vendor_id_' . $vendor['vendor_id'] . '_weight'}), $this->config->get('mvweight_' . (int)$geo_zone['geo_zone'] . '_vendors'))) {
							$weight = ${'group_' . $vendor['vendor_id'] . '_weight'};
							$rates = explode(',', $this->config->get('mvweight_' . (int)$geo_zone['geo_zone'] . '_rate'));
							foreach ($rates as $rate) {
								$data = explode(':', $rate);
								if ($data[0] >= $weight) {
									if (isset($data[1])) {
										$vendor_data[] = array(
											'vendor_id'	=>	$vendor['vendor_id'],
											'cost'		=>	$data[1],
											'weight'	=>	(float)$weight
										);
									}
									break;
								}
							}
						}
					}
				}
			}
			return $vendor_data;
		}
		
		Private function mv_flat_shipping() {
				
			$mv_flat_data = array();		
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$vproduct = $this->db->query("SELECT v.vendor, v.prefered_shipping, v.shipping_cost, p.weight FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) WHERE p.product_id = '" . (int)$product['product_id'] . "'");
						if ($this->config->get('mvflat_shipping_method') == $vproduct->row['prefered_shipping']) {
							$mv_flat_data[] = array (
								'vendor' 	=> (int)$vproduct->row['vendor'],
								'weight'	=> (float)$vproduct->row['weight']*$product['quantity'],
								'cost'		=> (float)$vproduct->row['shipping_cost']*$product['quantity']
								);
						}
				}
			}
			
			return $mv_flat_data;
		
		}
		
	public function update($order_id, $order_status_id, $comment = '', $notify = false) {
	
		$order_info = $this->getOrder($order_id);

		if ($order_info && $order_info['order_status_id']) {
			// Fraud Detection
			if ($this->config->get('config_fraud_detection')) {
				$this->load->model('checkout/fraud');
				
				$risk_score = $this->model_checkout_fraud->getFraudScore($order_info);
				
				if ($risk_score > $this->config->get('config_fraud_score')) {
					$order_status_id = $this->config->get('config_fraud_status_id');
				}
			}			

			// Blacklist
			$status = false;
			
			$this->load->model('account/customer');
			
			if ($order_info['customer_id']) {
								
				$results = $this->model_account_customer->getIps($order_info['customer_id']);
				
				foreach ($results as $result) {
					if ($this->model_account_customer->isBlacklisted($result['ip'])) {
						$status = true;
						
						break;
					}
				}
			} else {
				$status = $this->model_account_customer->isBlacklisted($order_info['ip']);
			}
			
			if ($status) {
				$order_status_id = $this->config->get('config_order_status_id');
			}		
						
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
	
	        //订单成功后，要在hz_customer_transaction表增加一个本次交易使用的余额在负金额和正的返点金额
			if((int)$order_status_id==5){
			  $this->load->model('account/transaction');
			  //1.先计算该客户当前的可用余额信息
			  $balance = (float)$this->model_account_transaction->getTotalAmount($order_info['customer_id']);
			  //2.如果有余额，要计算本次需要扣除的余额，计算方式：
			  //  a.如果定单金额大于等于余额，则扣除全部余额大小
			  //  b.如果余额大于定单金额，则扣除定单金额大小
			  $order_total = (float)$order_info['total'];
			  if($balance>0){
			     $minus = 0;
				 if($order_total>=$balance){
				   $minus=$balance;				
				 }else{
				   $minus=$order_total;	
				 }
				 $this->model_account_transaction->addTransaction($order_info['customer_id'],'扣除','-'.$minus);
			  }
			  //3.加上本次订单金额的1%的返点
			  $add = $order_total * 0.008;
			  $this->model_account_transaction->addTransaction($order_info['customer_id'],'返点',$add);
			  
			  //4.清空购物车(缓存中)
			  $this->session->data['cart'] = array();
		      $this->data = array();
			  //4.清空购物车(数据库中)
			  $this->model_account_transaction->cleanCartFromDB($order_info['customer_id']);
			}
			// Send out any gift voucher mails
			if ($this->config->get('config_complete_status_id') == $order_status_id) {
				$this->load->model('checkout/voucher');
	
				$this->model_checkout_voucher->confirm($order_id);
			}	
	
			if (true) {
				$language = new Language($order_info['language_directory']);
				$language->load($order_info['language_filename']);
				$language->load('mail/order');
			
				$subject = sprintf($language->get('text_update_subject'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'), $order_id);
	
				//TODO:修改订单成功付款后的文字
				$message  = $language->get('text_update_order') . ' ' . $order_id . "\n";
				$message .= $language->get('text_update_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
				
				$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
				if ($order_status_query->num_rows) {
					$message .= $language->get('text_update_order_status') . "\n\n";
					$message .= $order_status_query->row['name'] . "\n\n";					
				}
				
				if ($order_info['customer_id']) {
					$message .= $language->get('text_update_link') . "\n";
					$message .= $order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id . "\n\n";
				}
				
				if ($comment) { 
					$message .= $language->get('text_update_comment') . "\n\n";
					$message .= $comment . "\n\n";
				}
					
				$message .= $language->get('text_update_footer');
				
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($order_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($order_info['store_name']);
				$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
				
				// Send to main admin email if new account email is enabled
				if ($this->config->get('config_account_mail')) {
					$mail->setTo($this->config->get('config_email'));
					$mail->send();
					
					// Send to additional alert emails if new account email is enabled
					$emails = explode(',', $this->config->get('config_alert_emails'));
					
					foreach ($emails as $email) {
						if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
							$mail->setTo($email);
							$mail->send();
						}
					}
				}
			}
		}
	}
}
?>