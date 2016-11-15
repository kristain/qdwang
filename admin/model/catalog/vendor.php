<?php
class ModelCatalogVendor extends Model {
	public function addVendor($data) {
		/*add*/
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendors SET vendor_name = '" . $this->db->escape($data['vendor_name']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', commission_id = '" . (int)$data['commission'] . "', product_limit_id = '" . (int)$data['product_limit'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$vendor_id = $this->db->getLastId();

		if (isset($data['vendor_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
		}
		
		$this->cache->delete('vendor');
	}

	public function editVendor($vendor_id, $data) {
		/*add*/
		$this->db->query("UPDATE " . DB_PREFIX . "vendors SET user_id = '" . (int)$this->db->escape($data['user_id']) . "', vendor_name = '" . $this->db->escape($data['vendor_name']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', commission_id = '" . (int)$data['commission'] . "', product_limit_id = '" . (int)$data['product_limit'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE vendor_id = '" . (int)$vendor_id . "'");

		if (isset($data['vendor_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
		}

		$this->cache->delete('vendor');
	}
	
	public function editVendorProfile($user_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "vendors SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', store_url = '" . $this->db->escape($data['store_url']) . "' WHERE user_id = '" . (int)$user_id . "'");

		if (isset($data['vendor_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE user_id = '" . (int)$user_id . "'");
		}

		$this->cache->delete('vendor');
	}
 
	public function deleteVendor($vendor_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendors WHERE vendor_id = '" . (int)$vendor_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "user WHERE vendor_permission = '" . (int)$vendor_id . "'");
	}
	
	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE vendor_id = '" . (int)$vendor_id . "'");
		return $query->row; 
	}
	
	public function getVendorProfile($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE user_id = '" . (int)$user_id . "'");
		return $query->row; 
	}
	
	public function getVendors($data = array()) {
		
		if ($data) {
			$sql = "SELECT *,v.commission_id AS commission_id, c.commission AS commission,v.sort_order as vsort_order FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "commission c ON (v.commission_id = c.commission_id)";
			
			$sort_data = array(
				'vendor_name',
				'commission',
				'vsort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY vendor_name";	
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
		} else {
			
			if (!$vendors_data) {
				$query = $this->db->query("SELECT *,v.commission_id AS commission_id, c.commission AS commission,v.sort_order as sort_order FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "commission c ON (v.commission_id = c.commission_id)");
			
				$vendors_data = $query->rows;

				$this->cache->set('vendor', $vendors_data);
			}

			return $vendors_data;
		}
	}
	public function getTotalVendorsByVendorId($vendors) {
      	$query = $this->db->query("SELECT COUNT(vendor) AS total FROM " . DB_PREFIX . "vendor WHERE vendor = '" . (int)$vendors . "'");
		return $query->row['total'];
	}

	public function getTotalVendors($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendors";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getByCountryZone($country_id,$zone_id) {
		$query = $this->db->query("SELECT zone FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND zone_id = '" . (int)$zone_id . "'");
		
		return $query->row;
	}
}
?>