<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 2:02 AM
 */

class Insurance_type_model extends CI_Model
{

    public function get_all(){
        $sql ='SELECT * FROM tbl_insurance_type';
        return $this->db->query($sql)->result_array();
    }
}