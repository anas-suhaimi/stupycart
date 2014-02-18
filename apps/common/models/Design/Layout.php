<?php

namespace Stupycart\Common\Models\Design;

class Layout  extends \Libs\Stupy\Model {	
	public function getLayout($route) {
		$query = $this->db_query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db_escape($route) . "' LIKE CONCAT(route, '%') AND store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;	
		}
	}
}
?>