<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 2:02 AM
 */

class Division_model extends CI_Model
{
    /**
     * get divisions lists
     * @return $ret: lists
    */
    public function get_all_divisions(){
        $sql ='SELECT *
              FROM tbl_divisions';
        return $this->db->query($sql)->result_array();
    }

    /**
     * get divisions lists via division type
     * @param $type: 0=>division, 1=>subdivision, 2=>regions
     * @return $ret: lists
     */
    public function get_all_divisions_via_type( $type ){
        return $this->db->get_where("tbl_divisions", array("type"=>$type))->result_array();
    }

    /**
     * create division
     * @param $data: division info
     * 2019-06-24 by hmc
    */
    public function add_division($data){
        $this->db->insert('tbl_divisions', $data);
        return true;
    }

    /**
     * edit division info
     * @param $data: update info
     * @param $id: primary key
     * @return $res: 1=>success, 0=>failed
     * 2019-06-26 by hmc
    */
    public function edit_division($data, $id)
    {
        $this->db->where(array("id"=>$id));
        return $this->db->update("tbl_divisions", $data);
    }

    /**
     * create division
     * @param $did: division primary key
     * 2019-06-24 by hmc
     */
    public function del_division($did) {
        $this->db->delete('tbl_divisions', array("id"=>$did));
        return true;
    }

    /**
     * get division info via id
     * @param $id: division primary key
     * @return $res: division info
     * 2019-06-25 by hmc
     */
    public function get_division_via_id( $id )
    {
        return $this->db->get_where("tbl_divisions", array("id"=>$id))->row_array();
    }

    /**
     * get subdivisions list via division primary key
     * @param $id: division's primary key
     * @return $res: subdivisions list
     * 2019-06-26 by hmc
    */
    public function get_subdivisions_via_divisionid ( $id )
    {
        return $this->db->get_where("tbl_divisions", array("parent"=>$id, "type"=>1))->result_array();
    }

    /**
     * get regions list via division primary key
     * @param $id: division's primary key
     * @return $res: regions list
     * 2019-06-26 by hmc
     */
    public function get_regions_via_divisionid ( $id )
    {
        $sql = "select a.*, b.name as sub_name, b.id as sub_id from tbl_divisions a 
                left join tbl_divisions b on (a.parent=b.id)
                where a.type=2 and a.parent in 
                (select id from tbl_divisions where `parent`='$id' and `type`=1)
                order by a.name";
        return $this->db->query($sql)->result_array();
    }


    /**
     * get division list via parent id
     * @param $parent: parent division primary key
     * @return $res: division list
     * 2019-06-25 by hmc
    */
    public function get_division_via_parent( $parent )
    {
        return $this->db->get_where("tbl_divisions", array("parent"=>$parent))->result_array();
    }


}
