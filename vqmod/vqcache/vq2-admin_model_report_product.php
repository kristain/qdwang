<?php
class ModelReportProduct extends Model {

				public function getProfited($data = array()) {
					$sql = "SELECT YEAR(o.date_added) AS year,MONTHNAME(o.date_added) AS month, SUM(op.price*op.quantity) AS tprice, SUM(op.cost*op.quantity) AS tcost, SUM(op.price*op.quantity - op.cost*op.quantity) AS profit, o.date_added FROM `" . DB_PREFIX . "order_product` AS op LEFT JOIN `" . DB_PREFIX . "order` AS o ON (op.order_id = o.order_id)";
		
					if (!empty($data['filter_order_status_id'])) {
						$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
					} else {
						$sql .= " WHERE o.order_status_id > '0'";
					}
		
					if (!empty($data['filter_date_start'])) {
						$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					}

					if (!empty($data['filter_date_end'])) {
						$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
					}
		
					$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added) ORDER BY YEAR(o.date_added), MONTH(o.date_added) ASC";
					
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

				public function getTotalProfited($data) {		

					$sql = "SELECT COUNT(*), YEAR(o.date_added), MONTHNAME(o.date_added) 
							FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
		
					if (!empty($data['filter_order_status_id'])) {
						$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
					} else {
						$sql .= " WHERE o.order_status_id > '0'";
					}
		
					if (!empty($data['filter_date_start'])) {
						$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
					}

					if (!empty($data['filter_date_end'])) {
						$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
					}
		
					$sql .= " GROUP BY YEAR(o.date_added), MONTH(o.date_added)";
		
					$query = $this->db->query($sql);
		
					if ($query->num_rows) {
			
						return $query->num_rows;
					} else {
						return 0;		
					}
				}
			
	public function getProductsViewed($data = array()) {
		$sql = "SELECT pd.name, p.model, p.viewed FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.viewed > 0 ORDER BY p.viewed DESC";
					
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
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE viewed > 0");
		
		return $query->row['total'];
	}
	
	public function getTotalProductViews() {
      	$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");
		
		return $query->row['total'];
	}
			
	public function reset() {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = '0'");
	}
	
	public function getPurchased($data = array()) {
		$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + op.total * op.tax / 100) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " GROUP BY op.product_id ORDER BY total DESC";
					
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
	
	public function getProductsQuantity($data = array()) {
			$sql = "SELECT pd.name, p.model, p.quantity FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.quantity <6 and p.status=1 ORDER BY p.quantity ASC, pd.name ASC LIMIT 999";	
			$query = $this->db->query($sql);
			return $query->rows;
		}
	public function getTotalPurchased($data) {
      	$sql = "SELECT COUNT(DISTINCT op.product_id) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
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