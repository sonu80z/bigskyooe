<?php
	class Dashboard_model extends CI_Model{

		public function get_all_users(){
			return $this->db->count_all('tbl_user');
		}
		public function get_active_users(){
			return $this->db->count_all_results('tbl_user');
		}
		public function get_deactive_users(){
			return $this->db->count_all_results('tbl_user');
		}
	}

?>
