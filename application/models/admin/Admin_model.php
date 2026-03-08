<?php
	class Admin_model extends CI_Model{

		//--------------------------------------------------------------------
		public function get_user_detail(){

            $id = $this->db->escape_str($this->session->userdata('did'));

			$query = $this->db->get_where('tbl_user', array('id' => $id));
			if ( $query ) return $query->row_array();
			else return null;
		}
		//--------------------------------------------------------------------
		public function update_user($data){

            $id = $this->db->escape_str($this->session->userdata('did'));
            $data = $this->db->escape_str($data);

			$this->db->where('id', $id);
			$this->db->update('tbl_user', $data);
			return true;
		}
		//--------------------------------------------------------------------
		public function change_pwd($data, $id){

            $id = $this->db->escape_str($id);
            $data = $this->db->escape_str($data);

			$this->db->where('id', $id);
			$this->db->update('tbl_user', $data);
			return true;
		}

        /**
         * update user`s 2fa state
         * $param $data: update info
         */
        public function update_user_for_2fa( $data )
        {
            $id = $this->db->escape_str($_SESSION["did"]);
            $data = $this->db->escape_str($data);

            $this->db->where("id", $id);
            $this->db->update("tbl_user", $data);
            return true;
        }

	}

?>