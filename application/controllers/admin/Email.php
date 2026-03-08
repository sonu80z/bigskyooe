<?php
/**
 * Created by PhpStorm.
 * User: ruh19
 * Date: 7/9/2018
 * Time: 6:05 AM
 */
class Email extends CI_Controller {

    public $mail_header = '<div style="width: 100%; background: #f1f1f1; color: #4d4d4d; padding: 0px 30px 30px; font-family: Helvetica; display: inline-block; overflow: hidden;">
                            <div style="margin: 0px auto 10px auto; width: 600px;">
                                <div style="border: 1px solid #ffffff; background: #ffffff; padding: 30px 20px; border-radius: 0px 0px 10px 10px;">
                                      <div style="text-align: center">
                                        <img src="http://digitalsurety.ch/dev/public/dist/img/srlogo.png" style="width: 80px;" />
                                        </div>';
    public $male_footer = '<div style="font-size: 16px; padding: 16px 0px 5px 0px;">
                                        The Digital Surety Team
                                      </div>
                                      <div>
                                        <a href="#" style="font-size: 16px; color: #ed2227; text-decoration: underline">digitalsurety.ch</a>
                                      </div>
                                    </div>
                                    <div style="color: #989898; font-size: 12px; margin-top: 5px; padding: 5px 20px 30px; text-align: justify; line-height: 15px;">
                                          <div style="text-align: center; font-size: 14px; line-height: 30px;;">© Digital Surety AG 2018</div>    
                                          <span style="font-size: 12px;">LEGAL DISCLAIMER: </span>  
                                          The information contained in this email message and its attachments are privileged and confidential and is 
                                          intended solely for the use of the intended recipient. If the recipient of this message and its attachments is 
                                          not the intended recipient, you are hereby advised that any dissemination, distribution or copy of this email 
                                          and its attachments or the content therein is strictly prohibited. If you received this email and/or attachments 
                                          and are not the intended recipient, please notify the sender immediately and destroy this email and its attachments.               
                                    </div>
                                </div>
                            </div>';

    function __construct() {
        parent::__construct();
        $this->load->model('admin/ticket_model', 'ticket_model');
        $this->load->model('admin/trader_model', 'trader_model');
        $this->load->library('session');
        $this->load->helper('form');
    }
    public function index() {
        $this->load->helper('form');
        $this->load->view('contact_email_form');
    }
    /**
     * send invite email to trader or influencer ( trader and influencer )
     * @param $is_influence: 0=>trader, 1=>influencer
     * 2018-09-22 by hmc
    */
    public function send_mail($is_influencer) {

        $is_influencer = $this->security->xss_clean($is_influencer);

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $btncolor = "#55ad79";
        $btnbcolor = "#26b56a";
        $ttxt = "Congratulations, someone you know has invited you to create an account with 
                Digital Surety Settlement Service. 
                Click the like below to accept your inivitation 
                and apply for an account.";
        if ( $is_influencer == "1" ) {
            $btncolor = "#566586";
            $btnbcolor = "#566586";
            $ttxt = "Congratulations, someone you know has invited you to create an Influencer account 
                    with Digital Surety Settlement Service.";
        }

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">Your Invitation to Digital Surety Settlement Service</div>
                      <div style="font-size: 16px">'.$ttxt.'
                      </div>
                      <div style="text-align: center; padding: 20px 0px;">
                          <a href="'.base_url().'home/signup/'.$_SESSION["inumber"].'/'.$is_influencer.'/'.date('Y-m-d').'"
                            style="background: '.$btncolor.'; color: white; padding: 5px 100px; text-decoration: none;
                            border: 1px solid '.$btnbcolor.'; font-size: 16px; border-radius: 5px; font-weight: bolder;">Get Started</a>                                                                      
                      </div>
                      <div style="font-size: 16px">
                            For security purposes this link expires in 3 days. If you have any questions or your 
                            invitation link has expired, simply reply to this email and a member of our 
                            team will assist you. 
                      </div>';

        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $to = $_SESSION["iemail"];

        $from = "digital@bernstein.metanet.ch";
        $server_name = "Digital Surety Invitations";

        $this->email->to($to);
        $this->email->from(SMTP_USER, SERVER_NAME);
//        $this->email->from($from, $server_name);
        $this->email->set_header('Sender', $server_name);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject("You're invited to create an account with Digital Surety");
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'Invite email has been sent successfully!');
            $_SESSION["inumber"] = null;
            redirect("admin/traders/invite_list/".$is_influencer);
        } else {
            show_error($this->email->print_debugger());
        }
    }
    /**
     * when admin approve trader ( influencer ) sign up request
     * @param $is_influencer: 0=>trader, 1=>influencer
     * 2018:09:22 by hmc
     */
    public function send_account_approve_mail_for_trader_influecner( $is_influencer=0 ) {

        $is_influencer = $this->security->xss_clean($is_influencer);

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">'.(($is_influencer=="1")?"Influencer Account ":"").'Application Approved</div>
                      <div style="font-size: 16px">
                        Congratulations, your application for an'.(($is_influencer=="1")?" influencer":"").' account was approved and you can now login 
                        to the Digital Surety platform.
                      </div>
                      <div style="text-align: center; padding: 20px 0px;">
                          <a href="'.base_url().'home/login'.$_SESSION["inumber"].'"
                            style="background: '.(($is_influencer=="0")?"#55ad79":"#566586").'; color: white; padding: 5px 100px; text-decoration: none;
                            border: 1px solid '.(($is_influencer=="0")?"#26b56a":"#566586").'; font-size: 16px; border-radius: 5px; font-weight: bolder;">Login</a>                                                                      
                      </div>';
        /*
                      <div style="font-size: 16px">
                            '.(($is_influencer=="0")?"After you login, it is highly recommended to setup banking and wallet address information in the digital and 
                            fiat currencies that you plan to transact. If this information is not setup you will experience transactional delays.":"After you login, 
                            it is highly recommended to setup banking and wallet address information in all digital and fiat currencies that you plan to receive.").'
                      </div>';
        */

        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $to = $_SESSION["iemail"];
        $this->email->to($to);
        $this->email->from(SMTP_USER, SERVER_NAME);
        $this->email->set_header('Sender', SERVER_NAME);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('Your Digital Surety account application was approved');
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'User has been updated successfully!');
            redirect("admin/traders/traders_list/".$is_influencer);
        } else {
            show_error($this->email->print_debugger());
        }
    }
    /**
     * when admin approve admin sign up request
     * @param $is_user: 0=>admin
     * 2018:09:22 by hmc
     */
    public function send_account_approve_mail_for_admin( $is_user=0 ) {

        $is_user = $this->security->xss_clean($is_user);

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">Application Approved</div>
                      <div style="font-size: 16px">
                        Congratulations, your application for an account is approved and you can now login 
                        to the Digital Surety platform.
                      </div>
                      <div style="text-align: center; padding: 20px 0px;">
                          <a href="'.base_url().'home/login'.$_SESSION["inumber"].'"
                            style="background: #55ad79; color: white; padding: 5px 50px; text-decoration: none;
                            border: 1px solid #26b56a; font-size: 16px; border-radius: 5px; font-weight: bolder;">Login</a>                                                                      
                      </div>
                      <div style="font-size: 16px">
                            After you login, it is highly recommended to setup banking and wallet address information in the digital and 
                            fiat currencies that you plan to transact. If this information is not setup you will experience transactional delays.
                      </div>';

        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $to = $_SESSION["iemail"];
        $this->email->to($to);
        $this->email->from(SMTP_USER, SERVER_NAME);
        $this->email->set_header('Sender', SERVER_NAME);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('Your Digital Surety account application was approved');
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'Admin has been updated successfully!');
            redirect("admin/traders/users/");
        } else {
            show_error($this->email->print_debugger());
        }
    }
    /**
     * when admin decline trader ( influencer ) sign up request
     * @param $is_influencer: 0=>trader, 1=>influencer
     * 2018:09:22 by hmc
     */
    public function send_account_decline_mail_for_trader_influecner( $is_influencer=0 ) {

        $is_influencer = $this->security->xss_clean($is_influencer);

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">'.(($is_influencer=="1")?"Influencer Account ":"").'Application Declined</div>
                      <div style="font-size: 16px">
                        Your application for an '.(($is_influencer=="1")?"influencer ":"").'account with Digital Surety was declined.  <br><br>
                        It may be possible that your application was missing or contained incomplete information. 
                        If you have questions regarding the decision you can contact us at 
                        <a href="#" style="font-size: 16px; color: blue; text-decoration: underline">support@digitalsurety.ch</a>
                      </div>';

        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $to = $_SESSION["iemail"];
        $this->email->to($to);
        $this->email->from(SMTP_USER, SERVER_NAME);
        $this->email->set_header('Sender', SERVER_NAME);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('Your Digital Surety account application was declined');
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'User has been updated successfully!');
            redirect("admin/traders/traders_list/".$is_influencer);
        } else {
            show_error($this->email->print_debugger());
        }
    }
    /**
     * send email to update email initiator, counterparty, admin or manager
     * @param $transaction_id: transaction`s primary key
     * @param $state: transaction`s updated state ( 2=>approve, 3=>complete, 4=>declied, 6=>caceled )
     * 2018-09-22 by hmc
     */
    public function send_update_transaction_email( $transaction_id=0, $state=NULL ) {

        $transaction_id = $this->security->xss_clean($transaction_id);
        $state = $this->security->xss_clean($state);

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">There is a change to one of your transactions</div>
                      <div style="font-size: 16px">
                        Login to Digital Surety to view details of the change. Your action may be required.
                      </div>
                      <div style="text-align: center; padding: 20px 0px;">
                          <a href="'.base_url().'home/login"
                            style="background: #55ad79; color: white; padding: 5px 50px; text-decoration: none;
                            border: 1px solid #26b56a; font-size: 16px; border-radius: 5px; font-weight: bolder;">Login</a>                                                                      
                      </div>';
        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $transaction_info = $this->ticket_model->get_ticketinfo_via_id($transaction_id);
        $to = $transaction_info->initiator_email;
        $cc = $transaction_info->counterparty_email;

        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->from(SMTP_USER, SERVER_NAME);
        $this->email->set_header('Sender', SERVER_NAME);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('There was a change to one of your transactions');
        $this->email->message($htmlContent);

        $str = "approved";
        if ( $state == "4" ) $str = "declined";
        else if ( $state == "3" ) $str = "completed";
        else if ( $state == "6" ) $str = "canceled";
        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'Transaction has been ' . $str . ' successfully!');
            redirect(base_url('admin/ticket/tickets'), 'refresh');
        } else {
            show_error($this->email->print_debugger());
        }
    }

    /**
     * send forgot password email for admin
     * 2018:09:01
     */
    public function send_email_for_reset_password() {

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $email_sent_datetime = date("Y-m-d H:i:s");
        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">Reset Your Password</div>
                          <div style="font-size: 16px">
                            You requested to reset your Digital Surety password.
                          </div>
                          <div style="text-align: center; padding: 20px 0px;">
                              <a href="'.base_url().'admin/auth/reset_password/'.$email_sent_datetime.'/'.enc_email_url($_SESSION["a_fpr_email"]).'"
                                style="background: #55ad79; color: white; padding: 5px 50px; text-decoration: none;
                                border: 1px solid #26b56a; font-size: 16px; border-radius: 5px; font-weight: bolder;">Reset Password</a>                                                                      
                          </div>
                          <div style="font-size: 16px">
                            This link is valid for 30 minutes. If you did not request this action, 
                            you can ignore this email and your password will remain secure and unchanged.
                          </div>';
        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $to = $_SESSION["a_fpr_email"];
        $this->email->to($to);
        $this->email->from(SMTP_USER, SERVER_NAME);
        $this->email->set_header('Sender', SERVER_NAME);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('Digital Surety Password Reset');
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'Forgot password reset email has been sent succssfully!');
            $_SESSION["a_fpr_email"] = null;
            redirect("admin/auth/login/1");
        } else {
            show_error($this->email->print_debugger());
        }
    }

    /**
     * send email after resert password
     * 2018-09-22 by hmc
     */
    public function send_reset_password_confirm()
    {
        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">Password Changed</div>
                          <div style="font-size: 16px">
                            The password for your Digital Surety account was recently changed. <br><br>
                            If you made this change, no action is required and you can proceed to login with your new password.<br><br>
                            If you did not make this change, please contact Digital Surety Support immediately 
                            <a href="#" style="font-size: 16px; color: #0000ff; text-decoration: underline">support@digitalsurety.ch.</a>
                          </div>';
        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;

        $to = $_SESSION["fpr_email"];
        $this->email->to($to);
        $this->email->from(SMTP_USER, SERVER_NAME);
        $this->email->set_header('Sender', SERVER_NAME);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('Your Digital Surety Password was changed');
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'Forgot Password has been reset successfuly!');
            $_SESSION["fpr_email"] = null;
            redirect("admin/auth/login/");
        } else {
            show_error($this->email->print_debugger());
        }
    }

    /**
     * admin invite email
     * 2018-09-22 by hmc
     */
    public function send_admin_invite_mail( ) {

        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $date = new DateTime(date("Y-m-d"));
        $date->modify("+3 day");
        $expire_date =  $date->format("d F Y");

        $htmlContent = '<div style="font-size: 24px; padding: 10px 0px;">Your Invitation to Digital Surety Settlement Service</div>
                      <div style="font-size: 16px">Congratulations, someone you know has invited you to create an account with 
                            Digital Surety Settlement Service. Click the like below to accept your inivitation 
                            and apply for an account.
                      </div>
                      <div style="text-align: center; padding: 20px 0px;">
                          <a href="'.base_url().'admin/auth/goto_admin_signup/'.$_SESSION["inumber"].'"
                            style="background: #55ad79; color: white; padding: 5px 50px; text-decoration: none;
                            border: 1px solid #26b56a; font-size: 16px; border-radius: 5px; font-weight: bolder;">Get Started</a>                                                                      
                      </div>
                      <div style="font-size: 16px">
                            For security purposes this link expires in 3 days. If you have any questions or your 
                            invitation link has expired, simply reply to this email and a member of our 
                            team will assist you. 
                      </div>
                      ';

        $htmlContent = $this->mail_header.$htmlContent.$this->male_footer;


        $to = $_SESSION["iemail"];

        $server_name = "Digital Surety Invitation";

        $this->email->to($to);
        $this->email->from(SMTP_USER, $server_name);
        $this->email->set_header('Sender', $server_name);
        $this->email->reply_to(SMTP_USER, $server_name);
        $this->email->reply_to("support@digitalsurety.ch", SMTP_REPLYTO);
        $this->email->subject('You`re invited to create an account with Digital Surety');
        $this->email->message($htmlContent);

        if ( $this->email->send() ) {
            $this->session->set_flashdata('msg', 'Invite email has been sent successfully!');
            $_SESSION["inumber"] = null;
            redirect("admin/users/admin_invite_list/");
        } else {
            show_error($this->email->print_debugger());
        }
    }
}