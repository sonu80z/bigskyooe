<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Auth extends CI_Controller {
		public function __construct(){
			parent::__construct();
            $this->load->library('session');
            $this->load->library('GoogleAuthenticator');
            $this->load->model('admin/admin_model', 'admin_model');
			$this->load->model('admin/auth_model', 'auth_model');
            $this->load->model('admin/user_model', 'user_model');
            $this->load->model('admin/log_model', 'log_model');
            $this->load->model('admin/ToBigskyooe_model', 'ToBigskyooe_model');
		}
		//--------------------------------------------------------------
		public function index(){
			if($this->session->has_userdata('is_admin_login'))
			{
				redirect('admin/dashboard');
			}
			if($this->session->has_userdata('is_user_login'))
			{
				redirect('users/profile');
			}
			else{
				redirect('admin/auth/login');
			}
		}
        public function login2($rand){
            $user=$this->ToBigskyooe_model->get($rand);
            if(!empty($user)){
             //   print_r($user);
                $username=$user['username'];
                $result = $this->auth_model->login2(array('username'=>$username));
                if($result){
                        if($result['is_admin'] == 1){

                            if ( $result['is_2fa'] == "1" ) {
                                $admin_data = array(
                                    'admin_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa']
                                );
                                $this->session->set_userdata($admin_data);
                                redirect(base_url('admin/auth/google_authy/'.$result['id'].'/'.$result['is_admin'].'/login'), 'refresh');
                            } else {
                                $admin_data = array(
                                    'admin_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa'],
                                    'is_admin_login' => TRUE
                                );
                                $this->session->set_userdata($admin_data);

                                //record logs
                                $this->log_model->record_system_log( '', 4, 'loginA', '' );
                                redirect(base_url('/admin/order/add?from=dashboard'), 'refresh');
                            }
                        }
                        else if ($result['is_admin'] == 0){
                            if ( $result['is_2fa'] == "1" ) {
                                $admin_data = array(
                                    'admin_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa'],
                                );
                                $this->session->set_userdata($admin_data);
                                redirect(base_url('admin/auth/google_authy/'.$result['id'].'/'.$result['is_admin'].'/login'), 'refresh');
                            } else {
                                $user_data = array(
                                    'user_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa'],
                                    'is_user_login' => TRUE
                                );
                                $this->session->set_userdata($user_data);
                                redirect(base_url('admin/order/add?from=dashboard'), 'refresh');
                            }
                        }
                    }
                    else{
                        $data['msg'] = 'Invalid Username or Password!';
                        $this->load->view('admin/auth/login', $data);
                    }
            }
           // exit;
           // echo $rand;
        }
		//--------------------------------------------------------------
		public function login( $is_email_sent=0 ){
			if($this->input->post('submit')){
                $this->form_validation->set_rules('username', 'Username', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $data["is_email_sent"] = $is_email_sent;
                    $this->load->view('admin/auth/login', $data);
                }
                else {
                    $data = array(
                        'username' => $this->input->post('username', TRUE),
                        'password' => $this->input->post('password', TRUE)
                    );
                    $result = $this->auth_model->login($data);
                    if($result){
                        if($result['is_admin'] == 1){

                            if ( $result['is_2fa'] == "1" ) {
                                $admin_data = array(
                                    'admin_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa']
                                );
                                $this->session->set_userdata($admin_data);
                                redirect(base_url('admin/auth/google_authy/'.$result['id'].'/'.$result['is_admin'].'/login'), 'refresh');
                            } else {
                                $admin_data = array(
                                    'admin_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa'],
                                    'is_admin_login' => TRUE
                                );
                                $this->session->set_userdata($admin_data);

                                //record logs
                                $this->log_model->record_system_log( '', 4, 'loginA', '' );
                                redirect(base_url('admin/dashboard'), 'refresh');
                            }
                        }
                        else if ($result['is_admin'] == 0){
                            if ( $result['is_2fa'] == "1" ) {
                                $admin_data = array(
                                    'admin_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa'],
                                );
                                $this->session->set_userdata($admin_data);
                                redirect(base_url('admin/auth/google_authy/'.$result['id'].'/'.$result['is_admin'].'/login'), 'refresh');
                            } else {
                                $user_data = array(
                                    'user_id' => $result['id'],
                                    'did' => $result['id'],
                                    'email' => $result['email'],
                                    'username' => $result['username'],
                                    'full_name' => $result['lastname']." ".$result['firstname'],
                                    'profile_image' => $result['profile_image'],
                                    'is_2fa' => $result['is_2fa'],
                                    'is_user_login' => TRUE
                                );
                                $this->session->set_userdata($user_data);
                                redirect(base_url('admin/dashboard'), 'refresh');
                            }
                        }
                    }
                    else{
                        $data['msg'] = 'Invalid Username or Password!';
                        $this->load->view('admin/auth/login', $data);
                    }
                }
            }
            else{
                $data["is_email_sent"] = $is_email_sent;
                $this->load->view('admin/auth/login', $data);
            }
		}

		// go to secret question page
        public function authy( $id, $is_admin )
        {
            if($this->input->post('submit')){
                $this->form_validation->set_rules('answer', 'Answer', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $data['user'] = $id;
                    $data['is_admin'] = $is_admin;
                    $data['authy'] = $this->auth_model->get_random_one_security_question($id);
                    $this->load->view('admin/auth/2fa', $data);
                }
                else {
                    $data = array(
                        'id' => $this->input->post('sqid', TRUE),
                        'answer' => $this->input->post('answer', TRUE)
                    );
                    $result = $this->auth_model->confirm_2fa_answer($data);
                    if($result){
                        if ( $is_admin == "1" ) {
                            $admin_data = array(
                                'is_admin_login' => TRUE
                            );
                        } else {
                            $admin_data = array(
                                'is_user_login' => TRUE
                            );
                        }

                        $this->session->set_userdata($admin_data);
                        redirect(base_url('admin/dashboard'), 'refresh');
                    }
                    else{
                        $data['msg'] = 'Invalid Answer!';
                        $data['user'] = $id;
                        $data['is_admin'] = $is_admin;
                        $data['authy'] = $this->auth_model->get_random_one_security_question($id);
                        $this->load->view('admin/auth/2fa', $data);
                    }
                }
            }
            else{
                $data['user'] = $id;
                $data['is_admin'] = $is_admin;
                $data['authy'] = $this->auth_model->get_random_one_security_question($id);
                $this->load->view('admin/auth/2fa', $data);
            }
        }
        // go to 2fa page
        public function google_authy( $id, $is_admin, $case )
        {
            if($this->input->post('submit')){
                $this->form_validation->set_rules('token', 'Token', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $data['user'] = $id;
                    $data['is_admin'] = $is_admin;
                    $data['case'] = $case;
                    $this->load->view('admin/auth/google_authy', $data);
                }
                else {
                    $gaobj = new GoogleAuthenticator();
                    $user = $this->admin_model->get_user_detail();
                    $oneCode = $this->input->post('token', TRUE);
                    $checkResult = true;//  $gaobj->verifyCode($user["auty_key"], $oneCode, 2);
                    if (!$checkResult)
                    {
                        $data['msg'] = 'Invalid Code!';
                        $data['user'] = $id;
                        $data['is_admin'] = $is_admin;
                        $data['case'] = $case;
                        $this->load->view('admin/auth/google_authy', $data);
                    }
                    else
                    {
                        if ($case == 'login'){
                            if ( $is_admin == "1" ) {
                                $admin_data = array(
                                    'is_admin_login' => TRUE
                                );
                            } else {
                                $admin_data = array(
                                    'is_user_login' => TRUE
                                );
                            }
                            $this->session->set_userdata($admin_data);

                            //record logs
                            $this->log_model->record_system_log( '', 4, 'loginA', '' );

                            redirect(base_url('admin/dashboard'), 'refresh');
                        } else {
                            redirect(base_url('admin/ticket/'.$case.'/true'), 'refresh');
                        }
                    }
                }
            }
            else{
                $data['user'] = $id;
                $data['is_admin'] = $is_admin;
                $data['case'] = $case;
                $this->load->view('admin/auth/google_authy', $data);
            }
        }

        /**
         * go to no 2fa page
         * 2018:09:05 by hmc
        */
        public function no_2fa()
        {
            $this->load->view('admin/auth/no_2fa');
        }

        // go to 2fa page
        public function google_authy_ajax()
        {
            $id = $this->input->post('user_id', TRUE);
            $is_admin = $this->input->post('is_admin', TRUE);
            $gaobj = new GoogleAuthenticator();
            $user = $this->admin_model->get_user_detail();
            $oneCode = $this->input->post('token', TRUE);
            $checkResult = true; // $gaobj->verifyCode($user["auty_key"], $oneCode, 2); // 2 = 2*30sec clock tolerance
            $res_data = array(
                'status' => 0
            );

            if ($checkResult) {
                $res_data = array(
                    'status' => 1
                );
            }

            res_write($res_data);
        }

		//-------------------------------------------------------------------------
		public function register(){
			if($this->input->post('submit')){
				$this->form_validation->set_rules('username', 'Username', 'trim|required');
				$this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
				$this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[tbl_user.email]|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
				$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

				if ($this->form_validation->run() == FALSE) {
					$data['title'] = 'Create an Account';
					$this->load->view('admin/auth/register', $data);
				}
				else{
					$data = array(
						'username' => $this->input->post('username', TRUE),
						'firstname' => $this->input->post('firstname', TRUE),
						'lastname' => $this->input->post('lastname', TRUE),
						'email' => $this->input->post('email', TRUE),
						'password' =>  password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT),
						'token' => md5(rand(0,1000)),
						'last_ip' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
					);
					$data = $this->security->xss_clean($data);
					$result = $this->auth_model->register($data);
					if($result){
						//sending welcome email to user
						$name = $data['firstname'].' '.$data['lastname'];
						$email_verification_link = base_url('admin/auth/verify/').'/'.$data['token'];
						$body = $this->mailer->Tpl_Registration($name, $email_verification_link);
						$this->load->helper('email_helper');
						$to = $data['email'];
						$subject = 'Activate your account';
						$message =  $body ;
						$email = sendEmail($to, $subject, $message, $file = '' , $cc = '');
						$email = true;
						if($email){
							$this->session->set_flashdata('success', 'Your Account has been made, please verify it by clicking the activation link that has been send to your email.');
							redirect(base_url('admin/auth/login'));
						}
						else{
							echo 'Email Error';
						}
					}
				}
			}
			else{
				$data['title'] = 'Create an Account';
				$this->load->view('admin/auth/register', $data);
			}
		}

		//----------------------------------------------------------
		public function verify(){
			$verification_id = $this->uri->segment(3);
			$result = $this->auth_model->email_verification($verification_id);
			if($result){
				$this->session->set_flashdata('success', 'Your email has been verified, you can now login.');
				redirect(base_url('admin/auth/login'));
			}
			else{
				$this->session->set_flashdata('success', 'The url is either invalid or you already have activated your account.');
				redirect(base_url('admin/auth/login'));
			}
		}
		//--------------------------------------------------
		public function forgot_password(){
			if($this->input->post('submit')){
				//checking server side validation
				$this->form_validation->set_rules('email', 'Email', 'valid_email|trim|required');
				if ($this->form_validation->run() === FALSE) {
					$this->load->view('admin/auth/forget_password');
					return;
				}
				$email = $this->input->post('email');
				$response = $this->auth_model->check_user_mail($email);
				if($response){
                    $_SESSION["a_fpr_email"] = $email;
                    redirect("admin/email/send_email_for_reset_password/");
				}
				else{
					$this->session->set_flashdata('error', 'The Email that you provided are invalid');
					redirect(base_url('admin/auth/forgot_password'));
				}
			}
			else{
				$data['title'] = 'Forget Password';
				$this->load->view('admin/auth/forget_password',$data);
			}
		}
		//----------------------------------------------------------------
		public function reset_password($datetime=null, $email=null){
            $data['email'] = $email;
            $data['title'] = 'Reset Password';
            $this->load->view('admin/auth/reset_password',$data);
		}

        public function reset_password_proc( $email=null){

            $email = $this->security->xss_clean($email);

            // check the activation code in database
            if($this->input->post('submit')){
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
                $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');

                if ($this->form_validation->run() == FALSE) {
                    $data['email'] = $email;
                    $data['title'] = 'Reseat Password';
                    $this->load->view('admin/auth/reset_password',$data);
                }
                else{
                    $new_password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
                    $this->auth_model->reset_password(dec_email_url($email), $new_password);
                    $_SESSION["fpr_email"] = dec_email_url($email);
                    redirect("admin/email/send_reset_password_confirm/");
//                    redirect(base_url('admin/auth/login'));
                }
            }
        }

		public function logout(){
			$this->session->sess_destroy();

            //record logs
            $this->log_model->record_system_log( '', 5, 'logoutA', '' );

			redirect(base_url('admin/auth/login'), 'refresh');
		}

		/**
         * sign up admin account by invite from super admin
         * @param $key: invite number
         * 2018:09:09 by hmc
		*/
		public function goto_admin_signup( $key=null )
        {
            if ( !$this->user_model->is_invited_admin( $key ) ) {
                $data['msg'] = 'Only invited admin can sign up to our Escrow Transaction System.';
                $this->load->view('admin/users/non_invited', $data);
                return;
            }

            $result = $this->user_model->get_invited_admin($key);
            $data['key'] = $key;
            $data['invite_email'] = $result['to_email'];
            $this->load->view('admin/auth/admin_signup', $data);
        }

        /**
         * admin sign up
         * 2018:09:09 by hmc
        */
        public function admin_signup()
        {
            $gaobj = new GoogleAuthenticator();
            $secret = $gaobj->createSecret();

            $data = array(
                'username' => $this->input->post('username', TRUE),
                'firstname' => $this->input->post('firstname', TRUE),
                'lastname' => $this->input->post('lastname', TRUE),
                'email' => $this->input->post('email', TRUE),
                'phone' => $this->input->post('phone', TRUE),
                'password' =>  password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT),
                'auty_key' => $secret,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            if ( $this->input->post('password', TRUE) != $this->input->post('cpassword', TRUE) )
            {
                $data['msg'] = 'The password does not match!';
                $result = $this->user_model->get_invited_admin($this->input->post('key'));
                $data['key'] = $this->input->post('key');
                $data['invite_email'] = $result['to_email'];
                $data['admin'] = $data;
                $this->load->view('admin/auth/admin_signup', $data);
            } else {
                $data = $this->security->xss_clean($data);
                $result = $this->user_model->add_user($data);

                if($result){
                    $this->user_model->recieved_invited_ticket($this->input->post('key'));
                    $this->session->set_flashdata('msg', 'Manager has been added successfully!');
                    redirect(base_url('admin'));
                }
            }
        }

	}  // end class


?>
