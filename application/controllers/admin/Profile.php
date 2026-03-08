<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends MU_Controller {
	public function __construct(){
		parent::__construct();
        $this->load->library('GoogleAuthenticator');
		$this->load->model('admin/admin_model', 'admin_model');
        $this->load->model('admin/user_model', 'user_model');
        $this->load->model('admin/config_model', 'config_model');
        $this->load->model('admin/log_model', 'log_model');
	}
	//-------------------------------------------------------------------------
	public function index(){
		if($this->input->post('submit', TRUE)){
            $admin_id = $this->session->userdata("did");
            $user_info = $this->admin_model->get_user_detail();
            $checkResult = $this->config_model->verify_2fa_code( $user_info["auty_key"], $this->input->post('ap_2fa') );

            if ($checkResult) {
                $data = array(
                    'username' => $this->input->post('username', TRUE),
                    'firstname' => $this->input->post('firstname', TRUE),
                    'lastname' => $this->input->post('lastname', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'phone' => $this->input->post('mobile_no', TRUE),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'profile_image' => $this->input->post('profile_image', TRUE)
                );
                $data = $this->security->xss_clean($data);
                $result = $this->admin_model->update_user($data);

                //record logs
                $this->log_model->record_system_log( '', 6, 'profileA', '' );

                if($result){
                    $this->session->set_flashdata('msg', 'Profile has been Updated Successfully!');
                    redirect(base_url('admin/profile'), 'refresh');
                }
            } else {
                $data['admin'] = $this->admin_model->get_user_detail();
                $data['title'] = 'Admin Profile';
                $data['msg'] = 'Invalid code';
                $data['view'] = 'admin/profile/index';
                $data['page_js'] = array('users.js');
                $this->load->view('layout', $data);
            }

		}
		else{
			$data['admin'] = $this->admin_model->get_user_detail();
			$data['title'] = 'Admin Profile';
			$data['view'] = 'admin/profile/index';
			$data['page_js'] = array('users.js');
			$this->load->view('layout', $data);
		}
	}

	//-------------------------------------------------------------------------
	public function change_pwd(){
		$id = $this->session->userdata('did');
		if($this->input->post('submit')){
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');
			if ($this->form_validation->run() == FALSE) {
				$data['user'] = $this->admin_model->get_user_detail();
                $data['title'] = 'Change Password';
				$data['view'] = 'admin/profile/change_pwd';
				$data['page_js'] = array();
				$this->load->view('layout', $data);
			}
			else{
				$data = array(
					'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
				);
				$data = $this->security->xss_clean($data);
				$result = $this->admin_model->change_pwd($data, $id);

                //record logs
                $this->log_model->record_system_log( '', 7, 'passwordA', '' );

				if($result){
					$this->session->set_flashdata('msg', 'Password has been changed successfully!');
					redirect(base_url('admin/profile/change_pwd'));
				}
			}
		}
		else{
			$data['user'] = $this->admin_model->get_user_detail();
			$data['title'] = 'Change Password';
			$data['view'] = 'admin/profile/change_pwd';
			$data['page_js'] = array();
			$this->load->view('layout', $data);
		}
	}

    // go to google authority setting page
    public function authy_setting()
    {
        $data['user_info'] = $this->admin_model->get_user_detail();
        $otpauth = 'otpauth://totp/DigitalSurety:'. $data['user_info']['username']
            .'?secret='. $data['user_info']['auty_key'].'&issuer=DigitalSurety';
        $data['qrurl'] = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($otpauth);
        $data['title'] = '2FA Authentication';
        $data['view'] = 'admin/profile/authy_setting';
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
        redirect("admin/profile/authy_setting");
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
            redirect("admin/profile/authy_setting");
        } else if ( $this->input->post("enable") ) {    // enable 2fa
            $user = $this->admin_model->get_user_detail();
            $checkResult = $this->config_model->verify_2fa_code( $user["auty_key"], $this->input->post('code') );
            if ( !$checkResult ) {
                $this->session->set_flashdata('msg', 'Do not match 2FA code');
                redirect("admin/profile/authy_setting");
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
            redirect("admin/profile/authy_setting");

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
            redirect("admin/profile/authy_setting");
        }
    }

    // photo upload
    public function user_photo_upload()
    {
        header('Content-Type: application/json');
        $config['upload_path']   = './uploads/profiles/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = 1024;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('image')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }else {
            $data = $this->upload->data();
            $success = ['success'=>$data['file_name']];
            echo json_encode($success);
        }
    }

}

?>	