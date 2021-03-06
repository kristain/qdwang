<?php
class ModelReportPro2VenReport extends Model {
	public function getProductsViewed($data = array()) {

		/*code start*/
		$sql = "SELECT pd.name, p.model, p.viewed FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "vendor v ON (pd.product_id = v.vproduct_id) WHERE v.vendor= '" . $this->user->getVP() . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.viewed > 0 ORDER BY p.viewed DESC";
		/*code end*/
		
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
	
	public function getTotalProductsViewed() {
		/*code start*/
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) WHERE p.viewed > 0 AND v.vendor = '" . $this->user->getVP() . "'");
		/*code end*/
		return $query->row['total'];
	}
	
	public function getTotalProductViews() {
		/*code start*/
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) WHERE v.vendor = '" . $this->user->getVP() . "'");
		/*code end*/
		return $query->row['total'];
	}
			
	public function reset() {
		/*code start*/
		$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) SET p.viewed = '0' WHERE v.vendor = '" . (int)$this->user->getVP() . "'");
		/*code end*/
	}
	
	public function getPurchased($data = array()) {
		$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + op.total * op.tax / 100) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		/*code start*/
		$sql .= " AND vendor_id = '" . $this->user->getVP() . "'";
		/*code end*/
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " GROUP BY op.model ORDER BY total DESC";
					
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
	
	public function getTotalPurchased($data) {
      	$sql = "SELECT COUNT(DISTINCT op.model) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		/*code start*/
		$sql .= " AND vendor_id = '" . $this->user->getVP() . "'";
		/*code end*/
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
}
?>