<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Invite extends UR_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('admin/user_model', 'user_model');
    }
    // go to invite page
    public function index(){
        if($this->input->post('submit')){
            $data = array(
                'from_id'=>$_SESSION["did"],
                'to_email' => $this->input->post('invite_email'),
                'created_at' => date('Y-m-d h:m:s')
            );
            $data = $this->security->xss_clean($data);
            $result = $this->user_model->add_invite_user($data);
            if($result){
                $this->session->set_flashdata('msg', 'Invite email has been sent successfully!');
                redirect(base_url('users/invite/invite_list'));
            }
        } else{
            $data['title'] = 'User Invite';
            $data['view'] = 'users/invite/user_invite';
            $data['page_js'] = array();
            $this->load->view('layout', $data);
        }
    }

    // go to invite lists page
    public function invite_list()
    {
        $data['lists'] = $this->user_model->invite_user_list($_SESSION["did"]);
        $data['title'] = 'User Invite List';
        $data['view'] = 'users/invite/user_invite_list';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }
}

?>