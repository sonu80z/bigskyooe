<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends UR_Controller {
	public function __construct(){
		parent::__construct();
        $this->load->library('GoogleAuthenticator');
		$this->load->model('users/user_model', 'user_model');
        $this->load->model('admin/admin_model', 'admin_model');
        $this->load->model('admin/config_model', 'config_model');
        $this->load->model('admin/auth_model', 'auth_model');
        $this->load->model('admin/log_model', 'log_model');
	}
	//-------------------------------------------------------------------------
	public function index(){
		if($this->input->post('submit')){
			$data = array(
//				'username' => $this->input->post('username'),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
//				'email' => $this->input->post('email'),
				'mobile_no' => $this->input->post('mobile_no'),
				'updated_at' => date('Y-m-d H:i:s'),
			);
			$data = $this->security->xss_clean($data);
			$result = $this->user_model->update_user($data);

            //record logs
            $this->log_model->record_system_log( '', 6, 'profileA', '' );

			if($result){
				$this->session->set_flashdata('msg', 'Profile has been Updated Successfully!');
				redirect(base_url('users/profile'), 'refresh');
			}
		}
		else{
			$data['user'] = $this->user_model->get_user_detail();
			$data['title'] = 'Manager Profile';
			$data['view'] = 'users/profile/index';
			$data['page_js'] = array('users.js');
			$this->load->view('layout', $data);
		}
	}

	//-------------------------------------------------------------------------
	public function change_pwd(){
		$id = $this->session->userdata('user_id');
		if($this->input->post('submit')){
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');
			if ($this->form_validation->run() == FALSE) {
				$data['user'] = $this->user_model->get_user_detail();
                $data['title'] = 'Change Password';
				$data['view'] = 'users/profile/change_pwd';
				$data['page_js'] = array();
				$this->load->view('layout', $data);
			}
			else{
				$data = array(
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
				);
				$data = $this->security->xss_clean($data);
				$result = $this->user_model->change_pwd($data, $id);

                //record logs
                $this->log_model->record_system_log( '', 7, 'passwordA', '' );

				if($result){
					$this->session->set_flashdata('msg', 'Password has been changed successfully!');
					redirect(base_url('users/profile/change_pwd'));
				}
			}
		}
		else{
			$data['user'] = $this->user_model->get_user_detail();
			$data['title'] = 'Change Password';
			$data['view'] = 'users/profile/change_pwd';
			$data['page_js'] = array();
			$this->load->view('layout', $data);
		}
	}

    // go to security question
    public function security_question()
    {
        $id = $this->session->userdata('did');
        if($this->input->post('submit')){
            $this->form_validation->set_rules('question', 'Question', 'trim|required');
            $this->form_validation->set_rules('answer', 'Answer', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $data['user_info'] = $this->admin_model->get_user_detail();
                $data['questions'] = $this->config_model->get_security_questions();
                $data['own_questions'] = $this->admin_model->get_user_security_questions($id);
                $data['title'] = 'Security Questions Setting';
                $data['view'] = 'users/profile/security_question';
                $data['page_js'] = array();
                $this->load->view('layout', $data);
            }
            else{
                $data = array(
                    'user' => $id,
                    'question' => $this->input->post('question'),
                    'answer' => $this->input->post('answer')
                );
                $data = $this->security->xss_clean($data);
                $result = $this->admin_model->add_security_qestion($data);
                if($result){
                    $this->session->set_flashdata('msg', 'Security question has been added successfully!');
                    redirect(base_url('users/profile/security_question'));
                }
            }
        }
        else{
            $data['user_info'] = $this->admin_model->get_user_detail();
            $data['questions'] = $this->config_model->get_security_questions();
            $data['own_questions'] = $this->admin_model->get_user_security_questions($id);
            $data['title'] = 'Security Questions Setting';
            $data['view'] = 'users/profile/security_question';
            $data['page_js'] = array();
            $this->load->view('layout', $data);
        }
    }

    // go to security question
    public function security_question_edit( $id, $answer )
    {
        $this->admin_model->security_question_edit( $id, $answer );
        $this->session->set_flashdata('msg', 'Security answer has been updated successfully!');
        redirect("users/profile/security_question");
    }

    // go to security question
    public function security_question_del( $id )
    {
        $this->admin_model->security_question_del( $id );
        $this->session->set_flashdata('msg', 'Security question has been deleted successfully!');
        redirect("users/profile/security_question");
    }

    // change security question state
    public function update_security_question_dstate( $is_question )
    {
        $data = array(
            'is_question' => $is_question,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $data = $this->security->xss_clean($data);
        $result = $this->admin_model->update_user($data);
        $this->session->set_flashdata('msg', 'Security question state has been changed successfully!');
        redirect("users/profile/security_question");
    }

    // go to google authority setting page
    public function authy_setting()
    {
        $data['user_info'] = $this->admin_model->get_user_detail();
        $data['qrurl'] = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=otpauth://totp/DigitalSurety:'. $data['user_info']['username']
            .'?secret='. $data['user_info']['auty_key'].'&issuer=DigitalSurety';
        $data['title'] = '2FA Authentication';
        $data['view'] = 'users/profile/authy_setting';
        $data['page_js'] = array('config.js');
        $this->load->view('layout', $data);
    }

    // change 2fa state
    public function update_2fa_dstate( $is_2fa )
    {
        $data = array(
            'is_2fa' => $is_2fa,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $data = $this->security->xss_clean($data);
        $result = $this->admin_model->update_user_for_2fa($data);
        if ( $result )
            $this->session->set_flashdata('msg', '2FA state has been changed successfully!');
        else
            $this->session->set_flashdata('msg', 'You have set authy secret key firstly');
        redirect("users/profile/authy_setting");
    }
    // change 2fa state
    public function set_2fa_key(  )
    {
        $gaobj = new GoogleAuthenticator();

        if ( $this->input->post("reset") ) {        // 2fa secrete key reset
            $secret = $gaobj->createSecret();
            $data = array(
                'is_2fa' => 0,
                'auty_key' => $secret,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $data = $this->security->xss_clean($data);
            $result = $this->admin_model->update_user($data);

            //record logs
            $this->log_model->record_system_log( '', 10, '2faRA', '' );

            if ( $result )
                $this->session->set_flashdata('msg', '2FA secret key has been set successfully!');
            else
                $this->session->set_flashdata('msg', 'Failed to set secret key');
            redirect("users/profile/authy_setting");
        } else if ( $this->input->post("enable") ) {    // enable 2fa
            $user = $this->admin_model->get_user_detail();
            $oneCode = $this->input->post('code');
            $checkResult = true; // $gaobj->verifyCode($user["auty_key"], $oneCode, 2);
            if ( !$checkResult ) {
                $this->session->set_flashdata('msg', 'Do not match 2FA code');
                redirect("users/profile/authy_setting");
            }
            $data = array(
                'is_2fa' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $data = $this->security->xss_clean($data);
            $this->admin_model->update_user_for_2fa($data);

            //record logs
            $this->log_model->record_system_log( '', 8, '2faEA', '' );

            $this->session->set_flashdata('msg', '2FA state has been enabled successfully!');
            redirect("users/profile/authy_setting");

        } else if ( $this->input->post("disable") ) {   // disable 2fa
            $data = array(
                'is_2fa' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $data = $this->security->xss_clean($data);
            $this->admin_model->update_user_for_2fa($data);

            //record logs
            $this->log_model->record_system_log( '', 9, '2faDA', '' );

            $this->session->set_flashdata('msg', '2FA state has been disabled successfully!');
            redirect("users/profile/authy_setting");
        }
    }

    // confirm usesr`s password
    public function confirm_user_password( )
    {
        $data = array(
            'username' => $_SESSION["username"],
            'password' => $this->input->post('pass')
        );
        $result = $this->auth_model->login($data);

        $res_data = array(
            'status' => 0
        );

        if ($result) {
            $res_data = array(
                'status' => 1
            );

        }
        res_write($res_data);

    }

    // change 2fa state
    public function confirm_2fa_enable( )
    {
        $id = $this->session->userdata("did");
        $gaobj = new GoogleAuthenticator();
        $user = $this->user_model->get_user_by_id($id);
        $oneCode = $this->input->post('token');
        $checkResult = true; // $gaobj->verifyCode($user["auty_key"], $oneCode, 2); // 2 = 2*30sec clock tolerance

        $res_data = array(
            'status' => 0
        );

        if ($checkResult) {
            $res_data = array(
                'status' => 1
            );

            $data = array(
                'is_2fa' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $data = $this->security->xss_clean($data);
            $result = $this->user_model->edit_user($data, $id);
        }
        res_write($res_data);

    }
}

?>
