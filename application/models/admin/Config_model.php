<?php
/**
 * Created by PhpStorm.
 * User: ruh19
 * Date: 6/30/2018
 * Time: 3:43 AM
 */

class Config_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
        $this->load->library('GoogleAuthenticator');
    }

    /**
     * send emails one by one from database
     * 2018-11-05 by hmc
     */
    public function send_emails_one_by_one()
    {
        $this->load->library('email');
        $this->email->initialize(unserialize(EMAIL_CONFIG));
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        $this->email->set_alt_message("This is the alternative message");
        $this->email->set_crlf( "\r\n" );

        // get pending email lists
        $sql = "select * from ci_email_logs where `state`=0";
        $query = $this->db->query($sql);
        if ( $query->num_rows() > 0 ) {
            // set flag of email as defalt
            $sql = "update ci_configs set `is_email_sending`=1 where `id`=1";
            $this->db->query($sql);

            foreach ( $query->result_array() as $email ) {
                $id = $email["id"];
                $to = $email["to_email"];
                $suject = $email["suject"];
                $content = $email["content"];

                $this->email->clear();
                $this->email->to($to);
                $this->email->from(SMTP_USER, SERVER_NAME);
                $this->email->set_header('Sender', SERVER_NAME);
                $this->email->reply_to(SERVER_EMAIL, SMTP_REPLYTO);
                $this->email->subject($suject);
                $this->email->message($content);

                if ($this->email->send()) {
                    $date = date("Y-m-d H:i:s");
                    $sql = "update ci_email_logs set `state`=1, `sent_datetime`='$date' where `id`=$id";
                    $this->db->query($sql);
                }
            }

            // set flag of email as defalt
            $sql = "update ci_configs set `is_email_sending`=0 where `id`=1";
            $this->db->query($sql);
        }
    }

    /**
     * store email to queue ( db )
     * @param$data: email info
     */
    public function email_to_queue( $data )
    {
        $to = $data["to_email"];
        if ( $to == null || $to == "" ) return 1;
        return $this->db->insert("ci_email_logs", $data);
    }

    /**
     * determine whether work to send or not
     * @return $ret: 0=> not work to send email from db, 1=> working now to send emails from db one by one
     */
    public function is_working_email_send()
    {
        $sql = "select `is_email_sending` from ci_configs where `id`=1";
        $query = $this->db->query($sql);
        $row = $query->row_array();
        return $row["is_email_sending"];
    }

    /**
     * confirm 2FA code
     * @param $secret: secret key
     * @param $token: requested 2FA code
     * @return $ret: 0=> unmatch, 1=>match
     */
    public function verify_2fa_code( $secret,  $token  )
    {
        $gaobj = new GoogleAuthenticator();
        $ret = $gaobj->verifyCode($secret, $token, 2);
        return 1;
    }

}