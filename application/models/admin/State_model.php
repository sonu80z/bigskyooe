<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 2:02 AM
 */

class State_model extends CI_Model
{
    /**
     * get facilities lists
     * @return $ret: lists
    */
    public function get_all_states(){
        $sql ='SELECT * FROM tblstates';
        return $this->db->query($sql)->result_array();
    }

    public function get_active_states()
    {
        $condition =  array("active"=>1);
        $data = $this->db->get_where("tblstates", $condition)->result_array();
        return $data;
    }
    public function get_state_info($fldSt)
    {
        if(empty($fldSt)) return array();

        $condition =  array("fldSt"=>$fldSt);
        $rslt = $this->db->get_where("tblstates", $condition)->result_array();
        $data = array();
        if(count($rslt)>0) {
            $data = $rslt[0];
        }
        return $data;
    }
}