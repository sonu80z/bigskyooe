<?php
	class User_model extends CI_Model{

		//--------------------------------------------------------------------
		public function get_user_detail(){
			$id = $this->session->userdata('did');
			$query = $this->db->get_where('tbl_user', array('id' => $id));
			return $result = $query->row_array();
		}
		//--------------------------------------------------------------------
		public function update_user($data){
			$id = $this->session->userdata('user_id');
			$this->db->where('id', $id);
			$this->db->update('tbl_user', $data);
			return true;
		}
		//--------------------------------------------------------------------
		public function change_pwd($data, $id){
			$this->db->where('id', $id);
			$this->db->update('tbl_user', $data);
			return true;
		}

        // Get user detial by ID
        public function get_user_by_id($id){
            $sql = "SELECT *, concat(`firstname`, ' ', `lastname`) as fullname
                  FROM tbl_user
                  where `is_admin`<>1 and `id`=$id";
            $query = $this->db->query($sql);
            return $result = $query->row_array();
        }

	}

?>
