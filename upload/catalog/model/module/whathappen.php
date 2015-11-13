<?php
class ModelModuleWhathappen extends Model {
	public function getWhatHappen($whathappen_limit) {
		if ($whathappen_limit!=1){
		$this->db->query("DELETE FROM " . DB_PREFIX . "whathappen WHERE id NOT IN (SELECT id FROM (SELECT id FROM " . DB_PREFIX . "whathappen ORDER BY date_added DESC LIMIT ".$whathappen_limit.") foo);");
	}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "whathappen ORDER BY id DESC LIMIT ".$whathappen_limit.";");
	
		return $query->rows;	
	}
	public function getLastID() {
		$query = $this->db->query("SELECT id FROM " . DB_PREFIX . "whathappen ORDER BY id DESC LIMIT 1;");
		$row=$query->row;
		return $row['id'];	
	}
}