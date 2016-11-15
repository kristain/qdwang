<?php
class ModelSaleVendorOrder extends Model {

	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");
				
		if ($order_query->num_rows) {
			$reward = 0;
				
			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
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
		
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}				
				
			$this->load->model('sale/affiliate');
				
			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
	
			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';				
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
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
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
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
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
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified']
			);
		} else {
			return false;
		}
	}
	
	public function getOrders($data = array()) {
		$sql = "SELECT DISTINCT(o.order_id), CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status,";
		
		if ($this->user->getVP()) {
			$sql .= " (SELECT SUM(op.total) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.vendor_id = '" . (int)$this->user->getVP() . "') AS total,";
			$sql .= " (SELECT os.cost FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = o.order_id AND os.vendor_id = '" . (int)$this->user->getVP() . "') AS shipping_cost,";
			if ($this->config->get('tax_status')) {
			$sql .= " (SELECT os.tax FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = o.order_id AND os.vendor_id = '" . (int)$this->user->getVP() . "') AS shipping_tax,";
			$sql .= " (SELECT SUM(op.tax*op.quantity) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id AND op.vendor_id = '" . (int)$this->user->getVP() . "') AS tax,";
			}
		} else {
			$sql .= " (SELECT SUM(op.total) FROM " . DB_PREFIX . "order_product op WHERE op.order_id = o.order_id) AS total,";
		}

		$sql .= " o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if ($this->user->getVP()) {
			$sql .= " AND op.vendor_id = '" . (int)$this->user->getVP() . "'";
		}
		
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_customer'])) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND total = '" . (float)$data['filter_total'] . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'shipping_cost',
			'total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getOrderProducts($order_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "' AND vendor_id = '" . (int)$this->user->getVP() . "'";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getOrderOption($order_id, $order_option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_option_id = '" . (int)$order_option_id . "'");

		return $query->row;
	}
	
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getOrderDownloads($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_download WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	public function getOrderVoucherByVoucherId($voucher_id) {
      	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int)$voucher_id . "'");

		return $query->row;
	}
	
	public function getOrderTotals($order_id) {
		$sql = "SELECT (SELECT os.cost FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = '" . (int)$order_id . "' AND os.vendor_id = '" . (int)$this->user->getVP() . "') AS shipping_cost, (SELECT os.tax FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = '" . (int)$order_id . "' AND os.vendor_id = '" . (int)$this->user->getVP() . "') AS shipping_tax, (SELECT os.weight FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = '" . (int)$order_id . "' AND os.vendor_id = '" . (int)$this->user->getVP() . "') AS shipping_weight, (SELECT ot.title FROM " . DB_PREFIX . "order_total ot WHERE ot.order_id = '" . (int)$order_id . "' AND ot.code =  'tax' LIMIT 1) as tax_title, (SELECT os.title FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = '" . (int)$order_id . "' AND os.vendor_id =  '" . (int)$this->user->getVP() . "') as title, SUM(quantity*tax) as tax, SUM(total) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'";
		
		if ($this->user->getVP()) {
			$sql .= " AND vendor_id = '" . (int)$this->user->getVP() . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(DISTINCT (o.order_id)) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		$sql .= " AND op.vendor_id = '" . (int)$this->user->getVP() . "'";
		
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getVendorTotalSales() {
		$query = $this->db->query("SELECT (SUM(op.total) + SUM(op.quantity*op.tax)) as total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE order_status_id > '0' AND op.vendor_id = '" . (int)$this->user->getVP() . "'");

		return $query->row['total'];
	}
	
	public function getVendorTotalSalesByYear($year) {
      	$query = $this->db->query("SELECT (SUM(op.total) + SUM(op.quantity*op.tax)) as total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE order_status_id > '0' AND YEAR(date_added) = '" . (int)$year . "' AND op.vendor_id = '" . (int)$this->user->getVP() . "'");

		return $query->row['total'];
	}

	public function createInvoiceNo($order_id) {
		$order_info = $this->getOrder($this->request->get['order_id']);
			
		if ($order_info && !$order_info['invoice_no']) {
			$query = $this->db->query("SELECT MAX(invoice_no) AS invoice_no FROM `" . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");
	
			if ($query->row['invoice_no']) {
				$invoice_no = $query->row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}
		
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_no = '" . (int)$invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int)$order_id . "'");
			
			return $order_info['invoice_prefix'] . $invoice_no;
		}
	}
	
	public function addOrderHistory($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$data['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$data['order_status_id'] . "', notify = '" . (isset($data['notify']) ? (int)$data['notify'] : 0) . "', comment = '" . $this->db->escape(strip_tags($data['comment'])) . "', vendor_id = '" . $this->user->getVP() . "', date_added = NOW()");

		$order_info = $this->getOrder($order_id);

		// Send out any gift voucher mails
		if ($this->config->get('config_complete_status_id') == $data['order_status_id']) {
			$this->load->model('sale/voucher');

			$results = $this->getOrderVouchers($order_id);
			
			foreach ($results as $result) {
				$this->model_sale_voucher->sendVoucher($result['voucher_id']);
			}
		}

      	if ($data['notify']) {
			$language = new Language($order_info['language_directory']);
			$language->load($order_info['language_filename']);
			$language->load('mail/order');

			$subject = sprintf($language->get('text_subject'), $order_info['store_name'], $order_id);

			$message  = $language->get('text_order') . ' ' . $order_id . "\n";
			$message .= $language->get('text_date_added') . ' ' . date($language->get('date_format_short'), strtotime($order_info['date_added'])) . "\n\n";
			
			$order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$data['order_status_id'] . "' AND language_id = '" . (int)$order_info['language_id'] . "'");
				
			if ($order_status_query->num_rows) {
				$message .= $language->get('text_order_status') . "\n";
				$message .= $order_status_query->row['name'] . "\n\n";
			}
			
			if ($order_info['customer_id']) {
				$message .= $language->get('text_link') . "\n";
				$message .= html_entity_decode($order_info['store_url'] . 'index.php?route=account/order/info&order_id=' . $order_id, ENT_QUOTES, 'UTF-8') . "\n\n";
			}
			
			
			
			if ($data['comment']) {
				$message .= $language->get('text_comment') . "\n\n";
				$message .= strip_tags(html_entity_decode($data['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
				
			}
			
			if ($this->getCurrentMultiVendorOrderStatus($order_id) == $this->getRequiredMultiVendorsOrderStatus($order_id)) {
				$getComments = $this->getVendorComment($order_id);
				foreach ($getComments AS $Comment) {
					if ($Comment['comment']) {
						$message .= strip_tags(html_entity_decode($Comment['comment'], ENT_QUOTES, 'UTF-8')) . "\n\n";
					}
				}
			}			
			
			$message .= $language->get('text_footer');
			
			if ($this->getCurrentMultiVendorOrderStatus($order_id) == $this->getRequiredMultiVendorsOrderStatus($order_id)) {
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
			}
		}
	}
	
	public function getOrderHistories($order_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' AND oh.vendor_id = '" . (int)$this->user->getVP() . "' ORDER BY oh.date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getTotalOrderHistories($order_id) {
	  	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
	
	public function getOrderVouchers($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_voucher WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->rows;
	}
	
	public function UpdateMultiVendorOrderStatus($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_status_vendor_update` SET order_status_id = '" . (int)$data['order_status_id'] . "' WHERE order_id = '" . $order_id . "' AND vendor_id = '" . (int)$this->user->getVP() . "'");
	}
	
	public function getThisVendorOrderStatus($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status_vendor_update WHERE order_id = '" . $order_id . "' AND order_status_id = '" . (int)$this->config->get('multivendor_order_status') . "' AND vendor_id = '" . (int)$this->user->getVP() . "'");
		return $query->row['total'];
	}
	
	public function getThisMaxVendorOrderStatus($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status_vendor_update WHERE order_id = '" . $order_id . "' AND vendor_id = '" . (int)$this->user->getVP() . "'");
		return $query->row['total'];
	}
	
	public function getCurrentMultiVendorOrderStatus($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status_vendor_update WHERE order_id = '" . $order_id . "' AND order_status_id = '" . (int)$this->config->get('multivendor_order_status') . "'");
		return $query->row['total'];
	}
	
	public function getRequiredMultiVendorsOrderStatus($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_status_vendor_update WHERE order_id = '" . $order_id . "'");
		return $query->row['total'];
	}
	
	public function getOrderStatusName($order_status_id) {
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "'");
		if (isset($query->row['name'])) {
			return $query->row['name'];
		} else {
			return false;
		}
	}
	
	public function getVendorOrderStatus($order_id) {
		$query = $this->db->query("SELECT order_status_id FROM " . DB_PREFIX . "order_status_vendor_update WHERE order_id = '" . (int)$order_id . "' AND vendor_id = '" . $this->user->getVP() . "' LIMIT 1");
		
		if (isset($query->row['order_status_id'])) {
			return $query->row['order_status_id'];
		} else {
			return false;
		}
	}
	
	public function getVendorLastUpdate($order_id) {
		//$query = $this->db->query("SELECT os.name FROM " . DB_PREFIX . "order_status_vendor_update osvu LEFT JOIN `" . DB_PREFIX . "order_status` os ON (osvu.order_status_id = os.order_status_id) WHERE osvu.order_id = '" . $order_id . "' AND osvu.vendor_id = '" . (int)$this->user->getVP() . "' ORDER BY osvu.date_add DESC LIMIT 1");
		$query = $this->db->query("SELECT os.name as name FROM " . DB_PREFIX . "order osvu LEFT JOIN `" . DB_PREFIX . "order_status` os ON (osvu.order_status_id = os.order_status_id) WHERE osvu.order_id = '" . $order_id ."' ORDER BY osvu.date_added DESC LIMIT 1");
		return $query->row['name'];
	}
	
	public function getVendorComment($order_id) {
		$query = $this->db->query("SELECT comment FROM " . DB_PREFIX . "order_history WHERE order_id = '" . (int)$order_id . "' ORDER BY order_history_id DESC");
		return $query->rows;
	}
	
	public function getTotalOrderProduct($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
		return $query->row['total'];
	}
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		return $query->row;
	}
	
	public function getZone($zone_id, $country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND zone_id = '" . (int)$zone_id . "'");
		return $query->row;
	}
}
?>