<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('GoogleAuthenticator');
        $this->load->library('session');
        $this->load->model('admin/user_model', 'user_model');
        $this->load->model('admin/config_model', 'config_model');
        $this->load->model('admin/log_model', 'log_model');
    }
    //--------------------------------------------------------------
    public function index(){
        $data['index'] = 1;
//        $this->load->view('frontend/pages/home', $data);
        redirect(base_url('admin/auth/'), 'refresh');
    }

    // go to customer login page
    public function login( $is_email_sent=0 )
    {
        if($this->input->post('submit')){
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['is_iframe'] = '1';
                $data['view'] = 'frontend/pages/login';
                $this->load->view('frontend/layout', $data);
            }
            else {
                $data = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password')
                );
                $result = $this->trader_model->login($data);
                if( $result != -1 && $result != -2  ){
                    if ( $result["is_active"] != "1" ) {
                        $data['msg'] = 'Your account application is still under review by Digital Surety.';
                        $data['is_iframe'] = '1';
                        $data['view'] = 'frontend/pages/login';
                        $this->load->view('frontend/layout', $data);
                        return;
                    } else if ( $result['is_2fa'] == "1" ) {
                        $customer_data = array(
                            'customer_username' => $result['username'],
                            'customer_id' => $result['id'],
                            'customer_email' => $result['email'],
                            'customer_full_name' => $result['lastname']." ".$result['firstname'],
                            'is_influencer' => $result['is_influencer'],
                            'customer_role' => $result['role']
                        );
                        $this->session->set_userdata($customer_data);
                        redirect(base_url('home/google_authy/'.$result['id'].'/login'), 'refresh');
                    } else {
                        $customer_data = array(
                            'customer_username' => $result['username'],
                            'customer_id' => $result['id'],
                            'customer_email' => $result['email'],
                            'customer_full_name' => $result['lastname']." ".$result['firstname'],
                            'is_influencer' => $result['is_influencer'],
                            'is_customer_login' => TRUE,
                            'customer_role' => $result['role']
                        );
                        $this->session->set_userdata($customer_data);
                        redirect(base_url('front/ticket/tickets'), 'refresh');
                    }
                }
                else{
                    $data['is_iframe'] = '1';
                    if ( $result == -1 ) $data['msg'] = 'Invalid Username!';
                    if ( $result == -2 ) $data['msg'] = 'Invalid Password!';
                    $data["username"] = ( $result == -1 )?"":$this->input->post('username');
                    $data['view'] = 'frontend/pages/login';
                    $this->load->view('frontend/layout', $data);
                }
            }
        }
        else{
            if($this->session->has_userdata('is_customer_login')) {
				redirect('front/ticket/tickets');
			} else {
                $data['is_iframe'] = '1';
                $data["is_email_sent"] = $is_email_sent;
                $data['view'] = 'frontend/pages/login';
                $this->load->view('frontend/layout', $data);
            }
        }
    }

    // google authoriation
    public function google_authy($id, $case)
    {
        if($this->input->post('submit')) {
            $this->form_validation->set_rules('authy', 'Authy', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data['is_iframe'] = '1';
                $data['user'] = $id;
                $data['case'] = $case;
                $data['view'] = 'frontend/pages/google_authy';
                $this->load->view('frontend/layout', $data);
            } else {
                $gaobj = new GoogleAuthenticator();
                $trader = $this->trader_model->get_trader_by_id($_SESSION["customer_id"]);
                $oneCode = $this->input->post('authy');
                $checkResult = true; // $gaobj->verifyCode($trader["secret_key"], $oneCode, 2); // 2 = 2*30sec clock tolerance
                if (!$checkResult) {
                    $data['msg'] = 'Invalid Code!';
                    $data['is_iframe'] = '1';
                    $data['user'] = $id;
                    $data['case'] = $case;
                    $data['view'] = 'frontend/pages/google_authy';
                    $this->load->view('frontend/layout', $data);
                } else {
                    if ($case == 'login'){
                        $customer_data = array(
                            'is_customer_login' => TRUE
                        );
                        $this->session->set_userdata($customer_data);

                        // record log
                        $this->log_model->record_system_log( '', 40, 'loginU', '' );

                        redirect(base_url('front/ticket/tickets'), 'refresh');
                    } else {
                        redirect(base_url('front/ticket/tickets/true'), 'refresh');
                    }
                }
            }
        } else {
            $data['is_iframe'] = '1';
            $data['user'] = $id;
            $data['case'] = $case;
            $data['view'] = 'frontend/pages/google_authy';
            $this->load->view('frontend/layout', $data);
        }
    }

    // go to 2fa page
    public function google_authy_ajax()
    {
        $id = $this->input->post('user_id');
        $token = $this->input->post('token');
        $gaobj = new GoogleAuthenticator();
        $trader = $this->trader_model->get_trader_by_id($_SESSION["customer_id"]);
        $oneCode = $this->input->post('token'); // $token = $gaobj->getCode($secret);
        $checkResult = true; // $gaobj->verifyCode($trader["secret_key"], $oneCode, 2); // 2 = 2*30sec clock tolerance
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



    // go to customer logout
    public function logout()
    {
        $this->session->sess_destroy();

        // record log
        $this->log_model->record_system_log( '', 41, 'logoutU', '' );

        redirect('home/login');
    }

    // go to customer sign up check
    public function check_email_username_via_ajax()
    {
        $res = $this->trader_model->check_email_username_via_ajax();

        $res_data = array(
            'status' => 0
        );

        if( $res ) {
            $res_data = array(
                'status' => 1
            );
        }

        res_write($res_data);
    }

    /**
     * go to sign up page (trader, influencer)
     * @param $key: invite unique key (once submit signup info then ignore)
     * @param $is_influencer: 0=>signup trader, 1=>signup influencer
    */
    public function signup($key=null, $is_influencer=0, $invite_date=null )
    {
        $signup_trader = $this->session->userdata('signup_trader');
        if ($signup_trader && $signup_trader['key'] == $key) {
            if ($signup_trader['step'] == 1) {
                redirect("home/signup_step1");

            } else if ($signup_trader['step'] == 2) {
                redirect("home/signup_step2");

            } else if ($signup_trader['step'] == 3) {
                redirect("home/signup_step3");

            } else if ($signup_trader['step'] == 4) {
                redirect("home/signup_step4");

            } else {
                redirect("home/signup_step5");
            }

        } else {

            if ($this->session->userdata("secret_key")==null) {
                $gaobj = new GoogleAuthenticator();
                $sessionData = array(
                    'secret_key' => $gaobj->createSecret()
                );
                $this->session->set_userdata($sessionData);
            }

            if ( !$this->user_model->is_invited_user( $key ) ) {
                redirect("/");
                /*
                $data['msg'] = 'Only invited user can sign up to our Escrow Transaction System.';
                $this->load->view('frontend/pages/non_invited', $data);
                */
                return;
            }

            $result = $this->user_model->get_invited_user($key);

            // if already did signup, then go to expried page
            if ($result['is_recieved'] != 0) {
                $data['is_iframe'] = '1';
                $data['invite_email'] = $result['to_email'];
                $data['view'] = 'frontend/pages/signup_expire';
                $this->load->view('frontend/layout', $data);
                return;
            }

            // if passed 3 days from invite date, then go to exprired page
            $diff = date_diff(date_create($invite_date), date_create(date("Y-m-d")));
            $dif_day = intval($diff->format("%a"));
            if ( $dif_day > 3 ) {
                $data['is_iframe'] = '1';
                $data['invite_email'] = $result['to_email'];
                $data['view'] = 'frontend/pages/signup_expire';
                $this->load->view('frontend/layout', $data);
                return;
            }

            $signup_trader = array(
                'account_type' => '',
                'firstname' => '',
                'lastname' => '',
                'username' => '',
                'email' => $result['to_email'],
                'phone' => '',
                'password' => '',
                'role' => '',
                'secret_key' => $this->session->userdata("secret_key"),
                'is_2fa' => '',
                'created_at' => '',
                'updated_at' => '',
                'accept_terms' => '',
                'passphrase1' => '',
                'passphrase2' => '',
                'passphrase3' => '',
                'key' => $result['invite_number'],
                'is_influencer' => $is_influencer,
                'invite_number' => $result['invite_number'],
                'first_wire_reference_id' => substr(md5(uniqid(rand(), true)),0,11),
                'step' => 1
            );
            $this->session->set_userdata('signup_trader', $signup_trader);

            redirect("home/signup_step1");

        }
    }

    public function signup_step1() {
        $signup_trader = $this->session->userdata('signup_trader');
        if (!$signup_trader) {
            redirect("home/signup");
            return;
        }

        $data = [];
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['user'] = $signup_trader;
            $data['msg'] = '';
            $data['is_iframe'] = '1';
            $data['is_influencer'] = $signup_trader["is_influencer"];
            $data['view'] = 'frontend/pages/signup_step1';
            $this->load->view('frontend/layout', $data);

            return;

        } else if ($this->input->server('REQUEST_METHOD') == 'POST') {

            // if username is different then set different secret key, so that should set different qr code
            if ( !$signup_trader['username'] != $this->input->post('username') ) {
                $gaobj = new GoogleAuthenticator();
                $signup_trader['secret_key'] = $gaobj->createSecret();
            }

            $signup_trader['account_type'] = $this->input->post('account_type');
            $signup_trader['firstname'] = $this->input->post('firstname');
            $signup_trader['lastname'] = $this->input->post('lastname');
            $signup_trader['username'] = $this->input->post('username');
            $signup_trader['email'] = $this->input->post('email');
            $signup_trader['phone'] = $this->input->post('phone');
            $signup_trader['password'] =  password_hash($this->input->post('password'), PASSWORD_BCRYPT);

            $this->session->set_userdata('signup_trader', $signup_trader);

            if ($this->input->post('password') != $this->input->post('rpassword')) {
                $data['msg'] = 'The password does not match!';
                $data['user'] = $signup_trader;
                $data['is_iframe'] = '1';
                $data['is_influencer'] = $signup_trader["is_influencer"];
                $data['view'] = 'frontend/pages/signup_step1';
                $this->load->view('frontend/layout', $data);
                return;
            }

            if (strlen($this->input->post('password')) < 8) {
                $data['msg'] = 'The password should be 8 characters or more in length';
                $data['user'] = $signup_trader;
                $data['is_iframe'] = '1';
                $data['is_influencer'] = $signup_trader["is_influencer"];
                $data['view'] = 'frontend/pages/signup_step1';
                $this->load->view('frontend/layout', $data);
                return;
            }

            $username = $this->input->post('username');
            $user_num = $this->trader_model->get_trader_counts_by_username($username);

            if ($user_num > 0) {
                $data['msg'] = 'This username is already taken.';
                $data['user'] = $signup_trader;
                $data['is_iframe'] = '1';
                $data['is_influencer'] = $signup_trader["is_influencer"];
                $data['view'] = 'frontend/pages/signup_step1';
                $this->load->view('frontend/layout', $data);

            } else {
                $signup_trader['step'] = 2;
                $this->session->set_userdata('signup_trader', $signup_trader);

                redirect("home/signup_step2");
            }
        }
    }

    public function signup_step2() {
        $signup_trader = $this->session->userdata('signup_trader');
        if (!$signup_trader) {
            redirect("home/signup");
            return;
        }

        $data = [];
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['msg'] = '';
            $data['is_iframe'] = '1';
            $data['is_influencer'] = $signup_trader["is_influencer"];
            $data['user'] = $signup_trader;
            $data['view'] = 'frontend/pages/signup_step2';
            $this->load->view('frontend/layout', $data);

        } else if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $accept_terms = $this->input->post('accept_terms');
            if ($accept_terms == 1) {
                $signup_trader['accept_terms'] = $accept_terms;
                $signup_trader['step'] = 3;
                $this->session->set_userdata('signup_trader', $signup_trader);
                redirect("home/signup_step3");

            } else {
                $signup_trader['accept_terms'] = $accept_terms;
                $this->session->set_userdata('signup_trader', $signup_trader);

                $data['msg'] = "You must read and agree to Digital Surety's Trading Rules before you can proceed";
                $data['user'] = $signup_trader;
                $data['is_iframe'] = '1';
                $data['is_influencer'] = $signup_trader["is_influencer"];
                $data['view'] = 'frontend/pages/signup_step2';
                $this->load->view('frontend/layout', $data);
            }
        }
    }

    public function signup_step3() {
        $signup_trader = $this->session->userdata('signup_trader');
        if (!$signup_trader) {
            redirect("home/signup");
            return;
        }

        $data = [];
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['msg'] = '';
            $data['is_iframe'] = '1';
            $data['is_influencer'] = $signup_trader["is_influencer"];
            $data['user'] = $signup_trader;
            $data['view'] = 'frontend/pages/signup_step3';
            $data['qrurl'] = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=otpauth://totp/DigitalSurety:'.$signup_trader['username']
                                .'?secret='.$signup_trader['secret_key'].'&issuer=DigitalSurety';
            $this->load->view('frontend/layout', $data);

        } else if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $gaobj = new GoogleAuthenticator();
            $code = $this->input->post('code');
            $checkResult = true; // $gaobj->verifyCode($signup_trader["secret_key"], $code, 2);
            if ($checkResult) {
                $signup_trader['is_2fa'] = 1;
                $signup_trader['step'] = 4;
                $this->session->set_userdata('signup_trader', $signup_trader);
                redirect("home/signup_step4");

            } else {
                $data['msg'] = 'Invalid Code.';
                $data['is_influencer'] = $signup_trader["is_influencer"];
                $data['is_iframe'] = '1';
                $data['view'] = 'frontend/pages/signup_step3';
                $data['qrurl'] = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=otpauth://totp/DigitalSurety:'.$signup_trader['username']
                                .'?secret='.$signup_trader['secret_key'].'&issuer=DigitalSurety';
                $this->load->view('frontend/layout', $data);
            }
        }
    }

    public function signup_step4() {
        $signup_trader = $this->session->userdata('signup_trader');
        if (!$signup_trader) {
            redirect("home/signup");
            return;
        }

        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['msg'] = '';
            $data['is_iframe'] = '1';
            $data['is_influencer'] = $signup_trader["is_influencer"];
            $data['user'] = $signup_trader;
            $data['view'] = 'frontend/pages/signup_step4';
            $this->load->view('frontend/layout', $data);

        } else if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $signup_trader['passphrase1'] = $this->input->post('passphrase1');
            $signup_trader['passphrase2'] = $this->input->post('passphrase2');
            $signup_trader['passphrase3'] = $this->input->post('passphrase3');
            $signup_trader['step'] = 5;
            $this->session->set_userdata('signup_trader', $signup_trader);

            if ( !has_only_character($signup_trader['passphrase1']) ) $_SESSION["v_passphrase1"] = "1"; else $_SESSION["v_passphrase1"] = NULL;
            if ( !has_only_character($signup_trader['passphrase2']) ) $_SESSION["v_passphrase2"] = "1"; else $_SESSION["v_passphrase2"] = NULL;
            if ( !has_only_character($signup_trader['passphrase3']) ) $_SESSION["v_passphrase3"] = "1"; else $_SESSION["v_passphrase3"] = NULL;
            if ( $signup_trader['passphrase1'] == $signup_trader['passphrase2'] ||
                $signup_trader['passphrase1'] == $signup_trader['passphrase3'] ||
                $signup_trader['passphrase2'] == $signup_trader['passphrase3'] ) $_SESSION["v_passphrase_is_same"] = "1"; else $_SESSION["v_passphrase_is_same"] = NULL;
            if ( isset($_SESSION["v_passphrase1"]) || isset($_SESSION["v_passphrase2"]) || isset($_SESSION["v_passphrase3"]) || isset($_SESSION["v_passphrase_is_same"]) )
                redirect("home/signup_step4");
            else
                redirect("home/signup_step5");
        }
    }

    public function signup_step5() {
        $signup_trader = $this->session->userdata('signup_trader');
        if (!$signup_trader) {
            redirect("home/signup");
            return;
        }

        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['msg'] = '';
            $data['is_iframe'] = '1';
            $data['is_influencer'] = $signup_trader["is_influencer"];
            $data['user'] = $signup_trader;
            $data['view'] = 'frontend/pages/signup_step5';
            $this->load->view('frontend/layout', $data);

        }
    }

    public function signup_step6() {
        $signup_trader = $this->session->userdata('signup_trader');
        $is_influencer = $signup_trader["is_influencer"];
        if (!$signup_trader) {
            redirect("home/signup");
            return;
        }

        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['msg'] = '';
            $data['is_iframe'] = '1';
            $data['is_influencer'] = $signup_trader["is_influencer"];
            $data['user'] = $signup_trader;
            $data['view'] = 'frontend/pages/signup_step6';
            $this->load->view('frontend/layout', $data);

        } else if ($this->input->server('REQUEST_METHOD') == 'POST') {

            date_default_timezone_set('UTC');
            $signup_trader['created_at'] = date("Y-m-d H:i:s");
            $signup_trader['updated_at'] = date("Y-m-d H:i:s");

            $signup_trader = $this->security->xss_clean($signup_trader);
            $result = $this->trader_model->add_trader_by_invite($signup_trader);
            if($result) {
                redirect("front/email/send_acccount_create_mail/".$result."/".$is_influencer);
            }
        }
    }

    /**
     * go to signup confirm page
     * @param $is_influencer: 0=>trader sign u confirm, 1=>influencer sign up confirm
     * 2018:09:03 by hmc
    */
    public function signup_confirm( $is_influencer=0 )
    {
        $data['is_iframe'] = '1';
        $data['is_influencer'] = $is_influencer;
        $data['view'] = 'frontend/pages/signup_confirm';
        $this->load->view('frontend/layout', $data);
    }

    // go to customer forgot password page
    public function forgot()
    {
        $data['is_iframe'] = '1';
        $data['view'] = 'frontend/pages/forgot';
        $this->load->view('frontend/layout', $data);
    }

    /**
     * go to alert page that password reset password email sent successfully
     *2018:09:01 by hmc
    */
    public function forgot_sent()
    {
        $data['is_iframe'] = '1';
        $data['view'] = 'frontend/pages/forgot_sent';
        $this->load->view('frontend/layout', $data);
    }

    /**
     * go to don`t have 2fa page
     *2018:09:01 by hmc
     */
    public function no_2fa()
    {
        $data['is_iframe'] = '1';
        $data['view'] = 'frontend/pages/no_2fa';
        $this->load->view('frontend/layout', $data);
    }

    /**
     * send forgot password email
     *2018:09:01 by hmc
    */
    public function send_email_for_reset_password() {
        $email = $this->input->post('email');
        // confirm entered email is vaild
        $res = $this->trader_model->confirm_trader_email($email);;
        if ( $res < 1 )
        {
            $data['is_iframe'] = '1';
            $data['msg'] = 'Invalid Email Address!';
            $data['view'] = 'frontend/pages/forgot';
            $this->load->view('frontend/layout', $data);
            return;
        }
        $_SESSION["fpr_email"] = $email;
        redirect("front/email/send_email_for_reset_password/");
    }

    /**
     * go to reset forgot password page
     * 2018:09:01 by hmc
    */
    public function reset_forgot_password( $sent_reset_email_datetime=null, $reset_eamil=null ) {
        $data['is_iframe'] = '1';
        $data['email'] = dec_email_url($reset_eamil);
        $data['view'] = 'frontend/pages/forgot_password_reset';
        $this->load->view('frontend/layout', $data);
    }

    /**
     * forgort password reset funciton
     * 2018:09:01 by hmc
    */
    public function forgot_password_reset_proc()
    {
        $email =  $this->input->post('email');
        $password =  password_hash($this->input->post('npassword'), PASSWORD_BCRYPT);

        if ($this->input->post('npassword') != $this->input->post('cpassword')) {
            $data['email'] = $email;
            $data['msg'] = 'The password does not match!';
            $data['is_iframe'] = '1';
            $data['view'] = 'frontend/pages/forgot_password_reset';
            $this->load->view('frontend/layout', $data);
            return;
        }

        if (strlen($this->input->post('npassword')) < 8) {
            $data['email'] = $email;
            $data['msg'] = 'The password should be 8 characters or more in length';
            $data['is_iframe'] = '1';
            $data['view'] = 'frontend/pages/forgot_password_reset';
            $this->load->view('frontend/layout', $data);
            return;
        }

        $ret = $this->trader_model->update_trader_password($password, $email);

        $_SESSION["fpr_email"] = $email;
        redirect("front/email/send_reset_password_confirm/");
    }

    // confirm 2fa state
    public function confirm_2fa_enable( )
    {
        $gaobj = new GoogleAuthenticator();
        $trader = $this->trader_model->get_trader_by_id($_SESSION["customer_id"]);
        $oneCode = $this->input->post('token');
        $checkResult = true; // $gaobj->verifyCode($trader["secret_key"], $oneCode, 2); // 2 = 2*30sec clock tolerance

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

}

?>
