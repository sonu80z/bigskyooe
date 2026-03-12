<?php
/**
 * Created by PhpStorm.
 * User: ruh19
 * Date: 9/28/2018
 * Time: 11:59 PM
 */

class Toprs_model extends CI_Model
{
    /**
     * record system log
     * @param $tfa: 2fa code
     * @param $action_type: actor_type is 0 (0=>admin(manage) management ), actor_type is 0 or 1 (1=>ticket management, 2=>user(with influencer) management, 3=>accounting management, 4=>site management, 5=>profile management, 6=>invite user, 7=>invite admin), actor_type is 1 or 2 (8=>ticket, 9=>profile, 10=>invite)
     * @param $action_tag: action tag
     * @param $to: action_type 0=>manager username, 1=>ticket id, 2=>user username, 3=>wallet address or wire reference id, 4=>, 5=>, 6=>invite email, 7=>invite email, 8=>ticket id, 9=>, 10=>invite email
    */
    public function record_system_log( $tfa, $action_type, $action_tag, $to )
    {

        $tfa = $this->db->escape_str($tfa);
        $action_type = $this->db->escape_str($action_type);
        $action_tag = $this->db->escape_str($action_tag);
        $to = $this->db->escape_str($to);
        /************************************ table comment start ******************************************
        actor_type..................action_type.................action_tag......................actioncomment.......................to..............................................function position
         *0=>super admin            0=>admin invite             inviteA                         invite admin                        invite email address                            admin/Users.php -- 197
         *                          1=>admin approve            approveA                        approve admin                       approve admin username                          admin/Users.php -- 124
         *                          2=>admin decline            declineA                        decline admin                       decline admin usename                           admin/Users.php -- 126
         *                          3=>site backup              backupD                         backup database                                                                     admin/Export.php -- 34
         *0, 1(=>admin)             4=>login                    loginA                          login admin                                                                         admin/Auth.php -- 233
         *                          5=>logout                   logoutA                         logout admin                                                                        admin/Auth.php -- 409
         *                          6=>profile change           profileA                        change profile                                                                      admin/Profile.php -- 33
         *                          7=>change password          passwordA                       change password                                                                     admin/Profile.php -- 76
         *                          8=>2FA enable               2faEA                           2FA enabled                                                                         admin/Profile.php -- 224
         *                          9=>2FA disabled             2faDA                           2FA disabled                                                                        admin/Profile.php -- 238
         *                          10=>2FA reset               2faRA                           2FA reset                                                                           admin/Profile.php -- 205
         *                          11=>approve wireInstruction approveW                        approve wire instruction            ci_trader_banks`s reference_id                  admin/Account.php -- 73
         *                          12=>decline wireInstruction declineW                        decline wire instruction            ci_trader_banks`s reference_id                  admin/Account.php -- 75
         *                          13=>import wallet address   importWA                        import wallet address               digital asset type (BTC, BCH, ETH, LTC, XRP)    admin/Currency.php -- 53
         *                          14=>delete one wallet       deleteWA                        delete one wallet address           wallet address                                  admin/Currency.php -- 103
         *                          15=>delete wire instruction deleteW                         delete wire instruction             ci_trader_banks`s reference_id                  admin/Account.php -- 92
         *
         *                          20=>create new ticket       createAT                        create new ticket                   ticket id (ci_tickets`s ticket_id)              admin/Ticket.php -- 564
         *                          21=>approve ticket          approveAT                       approve Tikcet                      ticket_id                                       admin/Ticket.php -- 109
         *                          22=>decline ticket          declineAT                       decline ticket                      ticket_id                                       admin/Ticket.php -- 146
         *                          23=>complete ticket         completeAT                      complete ticket                     ticket_id                                       admin/Ticket.php -- 206
         *                          24=>cancel ticket           cancelAT                        cancel ticket                       ticket_id                                       admin/Ticket.php -- 241
         *                          25=>add commment            addAC                           add comment                         ticket_id                                       admin/Ticket.php -- 582
         *
         *                          30=>invite user             inviteAU                        invite user                         invite user`s email address                     admin/Traders.php -- 70
         *                          31=>approve user            approveAU                       approve user                        user`s username                                 admin/Traders.php -- 289
         *                          32=>declinet user           declineAU                       decline user                        user`s username                                 admin/Traders.php -- 290
         *                          33=>invite influencer       inviteAI                        invite influencer                   invite influencer`s email address               admin/Traders.php -- 72
         *                          34=>approve influencer      approveAI                       approve influencer                  influecner`s username                           admin/Traders.php -- 293
         *                          35=>decline influencer      declineAI                       decline influencer                  influencer`s username                           admin/Traders.php -- 296
         *
         * 2(user), 3(influencer)   40=>login                   loginU                          login user                                                                          Home.php -- 122
         *                          41=>logout                  logoutU                         logout user                                                                         Home.php -- 169
         *                          42=>chnage profile          profileU                        change profile                                                                      front/Trader.php -- 310
         *                          43=>change password         passwordU                       change password                                                                     front/Trader.php -- 62
         *                          44=>add bank                addUW                           add wire instruction                reference_id                                    front/Trader.php -- 339
         *                          45=>delete bank             deleteUW                        delete bank instrucion              reference_id                                    front/Trader.php -- 365
         *                          46=>add wallet              addUDW                          add wallet address                  wallet address                                  front/Trader.php -- 398
         *                          47=>delete wallet           deleteUDW                       delete wallet address               wallet address                                  front/Trader.php -- 416
         *                          48=>invite user             inviteUU                        invite user                         invite user`s email address
         *                          49=>invite influencer       inviteUI                        invite influencer                   invite influencer`s email address
         *
         * 2=>user                  60=>create ticket           createUT                        create ticket                       ticket_id                                       front/Ticket.php -- 101
         *                          61=>approve ticket          approveUT                       approve ticket                      ticket_id                                       front/Ticket.php -- 271
         *                          62=>decline ticket          declineUT                       decline ticket                      ticket_id                                       front/Ticket.php -- 305
         *                          63=>resubmi ticket          resubmitUT                      resubmit ticket                     ticket_id                                       front/Ticket.php -- 98
         *                          64=>cancel ticket           cancelUT                        cancel ticket                       ticket_id                                       front/Ticket.php -- 243
         *                          65=>add comment             addUC                           add comment                         ticket_id                                       front/Ticket.php -- 271
         *                          66=>approve created by admin approveUIT                     approved ticket created via admin    ticket_id                                      front/Ticket.php -- 289
         ************************************ table comment end ********************************************/

        date_default_timezone_set('UTC');

        $actor_type = null;
        $actor_id = null;
        if ( isset($_SESSION["is_admin_login"]) ) {                                                // super admin
            $actor_type = 0;
            $actor_id = $_SESSION["did"];
        } else if ( isset($_SESSION["is_user_login"]) ) {                                          // admin(manager)
            $actor_type = 1;
            $actor_id = $_SESSION["did"];
        } else if ( isset($_SESSION["is_customer_login"]) && isset($_SESSION["is_influencer"]) && $_SESSION["is_influencer"] == "0" ) { // trader
            $actor_type = 2;
            $actor_id = $_SESSION["customer_id"];
        } else if ( isset($_SESSION["is_customer_login"]) && isset($_SESSION["is_influencer"]) && $_SESSION["is_influencer"] == "1"  ) { // influencer
            $actor_type = 3;
            $actor_id = $_SESSION["customer_id"];
        }

        $data = array(
            "logtime" => date("Y-m-d H:i:s"),
            "actor_type" => $actor_type,
            "actor_id" => $actor_id,
            "tfa" => $tfa,
            "action_type" => $action_type,
            "action_tag" => $action_tag,
            "to" => $to
        );

        $this->db->insert("ci_system_logs", $data);

        // send log info to https://papertrailapp.com server
        $aar = array("super admin", "admin", "trader", "influencer");
        $logs_commentes = unserialize(LOGAS_COMMENTS);
//        $message = date("Y-m-d H:i:s").", actor type: ".$aar[intval($actor_type)].", actor id: ".$actor_id.", action type: ".$logs_commentes[intval($action_type)].", to: ".$to;
        //$this->send_remote_syslog($message);

        return 1;

    }

    /**
     * send log info via socket communication
     * @param $message: log message
    */
    public function send_remote_syslog($message, $component = "Log", $program = "Digital Surety")
    {

        $message = $this->db->escape_str($message);
        $component = $this->db->escape_str($component);
        $program = $this->db->escape_str($program);

        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
        socket_sendto($sock, $message, strlen($message), 0, "logs7.papertrailapp.com", 24773);
        socket_close($sock);
    }

    /**
     * get system log according to filter patameters
     * @param $actor_type: 0=>backend(super admin or admin), 1=>frontend (trader or influencer)
     * @param $year: year
     * @param $month: month
     * @param $day: day
     * @param $actor_username: actor_username string
     * @param $action_tag: action_tag string
     * @param $to: to string
     * @param $page: currnet page number
    */
    public function get_system_logs($actor_type, $year, $month, $day, $actor_username, $action_tag, $to, $page)
    {

        $actor_type = $this->db->escape_str($actor_type);
        $year = $this->db->escape_str($year);
        $month = $this->db->escape_str($month);
        $day = $this->db->escape_str($day);
        $actor_username = $this->db->escape_str($actor_username);
        $action_tag = $this->db->escape_str($action_tag);
        $to = $this->db->escape_str($to);
        $page = $this->db->escape_str($page);

        $ret = array();
        $actor_tb = "tbl_user";
        $where = " where (a.actor_type=0 or a.actor_type=1 ) ";
        $limit = 15;
        $offset = $limit * intval(( $page - 1 ));
        $lquery = " limit $limit offset $offset ";
        if ( $page == null ) $lquery = ""; // to export csv

        $where .= " and year(a.logtime)='$year' ";
        if ( $month != "0" ) $where .= " and month(a.logtime)='$month' ";
        if ( $day != "0" ) $where .= " and day(a.logtime)='$day' ";
        if ( $actor_username != "" ) $where .= " and b.username like '%$actor_username%' ";
        if ( $action_tag != "" ) $where .= " and a.action_tag like '%$action_tag%' ";
        if ( $to != "" ) $where .= " and a.to like '%$to%' ";

        $sql = "select count(*) as cnt from ci_system_logs a 
                left join $actor_tb b on(a.actor_id=b.id) 
                $where";
        $query = $this->db->query($sql);
        $ret["cnt"] = $query->row()->cnt;

        $sql = "select a.*, 
                      b.username as actor_username, 
                      b.email as actor_email, 
                      concat(b.lastname, ' ', b.firstname ) as actor_fullname 
                from ci_system_logs a 
                left join $actor_tb b on(a.actor_id=b.id) 
                $where 
                order by a.logtime desc 
                $lquery ";
        $query = $this->db->query($sql);

        if ( $page == null ) return $query;

        $ret["logs"] = $query->result_array();
        return $ret;
    }

}