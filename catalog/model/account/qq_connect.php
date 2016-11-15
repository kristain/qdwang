<?php
class ModelAccountQQConnect extends Model {
	public function CheckTotalOpenid($openid, $customer_id = false) {
		if ($customer_id){
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qqconnect WHERE customer_id = '" . (int)$customer_id . "'");
		} else {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "qqconnect WHERE openid = '" . $this->db->escape($openid) . "'");
		}
		return $query->row['total'];
	}
	
	public function addCustomer($data) {
/**/
 	if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		$this->load->model('account/customer_group');
		
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
/**/			  
      	$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['create_firstname']) . "',  email = '" . $this->db->escape($data['create_email']) . "', telephone = '" . $this->db->escape($data['create_telephone']) . "',  password = '" . $this->db->escape(md5($data['create_password'])) . "', newsletter = '1', customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "', status = '1',token= '" . $this->db->escape($data['accessToken']) . "',nickname= '" . $this->db->escape($data['nickname']) . "',date_added = NOW()");
      	
		$customer_id = $this->db->getLastId();
			
      	$this->db->query("INSERT INTO " . DB_PREFIX . "qqconnect SET customer_id = '" . (int)$customer_id . "', openid = '" . $this->db->escape($data['openid']) . "'");
		
		if (!$this->config->get('config_customer_approval')) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '0' WHERE customer_id = '" . (int)$customer_id . "'");
		}	
/**/			
		$this->language->load('mail/customer');
		
		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		
		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
		
		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}
		
		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
        //将原有的email修改为create_email
		$mail->setTo($data['create_email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
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

	public function bindCustomer($data, $customer_id) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "qqconnect SET customer_id = '" . (int)$customer_id . "', openid = '" . $this->db->escape($data['openid']) . "'");
	}
	
	public function getInfo($openid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "qqconnect WHERE openid = '" . $this->db->escape($openid) . "'");
		
		return $query->row;
	}
	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "'");
		
		return $query->row;
	}	
}
?>