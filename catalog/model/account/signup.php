<?php
class ModelAccountSignUp extends Model {
	
	public function addVendorSignUp($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', password = '" . $this->db->escape(md5($data['password'])) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', user_group_id = '50', date_added = NOW()");
		$user_id = $this->db->getLastId();
		
		if ($this->config->get('signup_commission')) {
			$signupCommission = $this->config->get('signup_commission');
		} else {
			$signupCommission = "1";
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendors SET user_id = '" . (int)$user_id . "', vendor_name = '" . $this->db->escape($data['company']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', commission_id = '" . (int)$signupCommission . "', product_limit_id = '" . (int)$this->db->escape($data['signup_product_limit']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal']) . "', vendor_description = '" . $this->db->escape($data['store_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '0'");
		$vendor_id = $this->db->getLastId();
	
		if ($this->config->get('signup_auto_approval')) {
			$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '1', folder = '" . $this->db->escape($data['username']) . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . serialize($this->config->get('signup_category')) . "', store_permission = '" . serialize($this->config->get('signup_store')) . "' WHERE user_id = '" . (int)$user_id . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '5', vendor_permission = '" . (int)$vendor_id . "' WHERE user_id = '" . (int)$user_id . "'");
		}
		
		$this->language->load('mail/signup');
		
		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		
		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
		
		if ($this->config->get('signup_auto_approval')) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}
		
		$message .= HTTP_ADMIN . "\n\n";
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
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText($message);
		$mail->send();
		
		/*trigger to store admin*/
		$subject_join = sprintf($this->language->get('text_subject1'), $data['username']);
		
		$text = sprintf($this->language->get('text_to'), $this->config->get('config_owner')) . "\n\n";
		$text .= sprintf($this->language->get('text_join'), $data['username'], $data['company']) . "\n\n";
		$text .= $this->language->get('text_thanks') . "\n";
		$text .= $this->config->get('config_name');
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject_join);
		$mail->setText($text);
		$mail->send();
		/*end trigger store admin*/
	}
	
	public function getUsernameBySignUp($username) {
		$query = $this->db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");
		return $query->row['total'];
	}
	
	public function getEmailBySignUp($email) {
		$query = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "user` WHERE email = '" . $this->db->escape($email) . "'");
		return $query->row['total'];
	}
	
	public function getProductLimit() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_limit Order by product_limit_id");
		return $query->rows;
	}
	

}
?>