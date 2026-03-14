<?php
    class ToBigskyooe_model extends CI_Model{

        private static $_username_fields = null;
        
        public function get($rand){
            $time=time();
          //  echo date('Y-m-d H:i:s',$time);
            $time=$time-2*60;
            $query= $this->db->get_where('to_bigskyooe','datetime >= (now()-120) and `rand`="'.$rand.'"');
            $result = $query->row_array();
            return $result;
        }

        private function apply_username_where($username)
        {
            // Cache field existence check to avoid querying INFORMATION_SCHEMA on every call
            if (self::$_username_fields === null) {
                self::$_username_fields = array(
                    'username' => $this->db->field_exists('username', 'tbl_user'),
                    'fldUserName' => $this->db->field_exists('fldUserName', 'tbl_user'),
                );
            }

            $has_where = false;
            $this->db->group_start();
            if (self::$_username_fields['username']) {
                $this->db->where('username', $username);
                $has_where = true;
            }
            if (self::$_username_fields['fldUserName']) {
                if ($has_where) {
                    $this->db->or_where('fldUserName', $username);
                } else {
                    $this->db->where('fldUserName', $username);
                }
            }
            $this->db->group_end();
        }

        public function get_user_by_username($username)
        {
            $username = $this->db->escape_str($username);
            $this->db->from('tbl_user');
            $this->apply_username_where($username);
            $query = $this->db->get();
            if ($query->num_rows() == 0) {
                return null;
            }
            return $query->row_array();
        }

        /**
         * Clean and escape data before insert/update
         * @param $data: data array to clean
         * @return cleaned data array
         */
        public function clean_user_data($data) {
            if (isset($data['username'])) {
                $data['username'] = $this->db->escape_str($data['username']);
            }
            if (isset($data['firstname'])) {
                $data['firstname'] = $this->db->escape_str($data['firstname']);
            }
            if (isset($data['lastname'])) {
                $data['lastname'] = $this->db->escape_str($data['lastname']);
            }
            if (isset($data['email'])) {
                $data['email'] = $this->db->escape_str($data['email']);
            }
            return $data;
        }
        public function add_user($data){
        $data = $this->clean_user_data($data);
        $result = $this->db->insert('tbl_user', $data);
        if ($result) {
            $id = $this->db->insert_id();
            return $id;
        }
        return false;
    }

        public function edit_user($data, $id) {
        $data = $this->clean_user_data($data);
        $this->db->where('id', $id);
        $result = $this->db->update('tbl_user', $data);
        return $result;
    }

        //---------------------------------------------------
        // get all users for server-side datatable processing (ajax based)
        public function get_all_users(){
            $sql ='SELECT *, concat(firstname, " ", lastname) as fullname
                  FROM tbl_user where is_admin<>1 ';
            return $this->db->query($sql)->result_array();
        }

        /**
         * get users via rolw
         * @param $role: user's role string
         * @return users list
         * 2019-06-25 via hmc
        */
        public function get_users_via_role ( $role )
    {
        $query = $this->db->from('tbl_user')->where(array("role"=>$role))->order_by('lastname ASC, id ASC')->get();
        return $query->result_array();
    }

    /**
     * Get all technologists: role=8 OR also_technologist=1
     */
    public function get_technologists_all()
    {
        $query = $this->db->from('tbl_user')
            ->group_start()
                ->where('role', 8)
                ->or_where('also_technologist', 1)
            ->group_end()
            ->order_by('lastname ASC, id ASC')
            ->get();
        return $query->result_array();
    }

    /**
     * confirm whether same username exist already or now
     * @param $username: username that confirm
     * @return $ret: 1=> exist already, 0=> not exist same username
     * 2018:09:13 by hmc
    */
    public function confirm_admin_username($username)
    {
        $username = $this->db->escape_str($username);

        $this->db->from('tbl_user');
        $this->apply_username_where($username);
        $query = $this->db->get();
        if ($query->num_rows() == 0){
            return false;
        }
        return true;
    }

    /**
     * get user info
     * @param $user: user's primary key
     * @return user info
     * 2019-06-25 via hmc
     */
    public function get_user_info( $id )
    {
        return $this->db->get_where("tbl_user", array("id"=>$id))->row_array();
        }
}

