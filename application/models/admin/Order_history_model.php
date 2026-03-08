<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/8/2019
 * Time: 12:24 PM
 */

class Order_history_model extends CI_Model
{

    public function add_order_history($data){
        $this->db->insert('tbl_order_history',$data);
        return true;
    }

}