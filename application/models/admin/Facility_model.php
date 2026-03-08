<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 2:02 AM
 */

class Facility_model extends CI_Model
{
    /**
     * get facilities lists
     * @return $ret: lists
    */
    public function get_all_facilites(){
        $sql ='SELECT *
              FROM tbl_facility
              ORDER BY facility_name ASC';
        return $this->db->query($sql)->result_array();
    }

    /**
     * get facility info via primary key
     * @param $id: primary key
     * @return $ret: facility info
     * 2019-06-25 by hmc
    */
    public function get_facility_info ( $id )
    {
        return $this->db->get_where("tbl_facility", array("id"=>$id))->row_array();
    }

    /**
     * get facility info via name
     * @param $name: facility name
     * @return $ret: facility info
     */
    public function get_facility_by_name($name)
    {
        $name = trim((string)$name);
        if ($name === '') {
            return array();
        }
        $this->db->where('LOWER(facility_name)', strtolower($name));
        return $this->db->get('tbl_facility')->row_array();
    }

    /**
     * get station list via facility primary key
     * @param $facility: facility primary key
     * @return $ret: facility info
     * 2019-06-25 by hmc
     */
    public function get_facility_stations ( $facility )
    {
        return $this->db->get_where("tblstations", array("facId"=>$facility))->result_array();
    }

    /**
     * create facility
     * @param $data: create facility info
     * @return $res: inserted primary key
     * 2019-06-27 by hmc
    */
    public function create_facility($data)
    {
        $this->db->insert("tbl_facility", $data);
        return $this->db->insert_id();
    }

    /**
     * update facility infi
     * @param $id: facility's primary key
     * @param $dataL facility's update info
     * @return $res: 1=>success, 0=>failed
     * 2019-06-27 by hmc
    */
    public function edit_facility($id, $data)
    {
        $this->db->where(array("id"=>$id));
        return $this->db->update("tbl_facility", $data);
    }

    /**
     * add & update facility's stations info
     * @param $facility: facility's primary key
     * @param $str: station list's string info
     * @return $res: 1=>success, 0=>failed
     * 2019=-6-27 by hmc
    */
    public function edit_stations($facility, $str)
    {
        $data = array();
        if ( $str == "" ) return;
        $arr = explode("###", $str);
        foreach ( $arr as $row )
        {
            $arr1 = explode("&&&", $row);
            $val = array(
                "facId" => $facility,
                "StationName" => $arr1[0],
                "StationPhone" => $arr1[1],
                "StationFax" => $arr1[2]
            );
            array_push( $data, $val);
        }
        // remove old facility's station info
        $this->db->delete("tblstations", array("facId"=>$facility));
        // add new & update station's info
        return $this->db->insert_batch("tblstations", $data);
    }

    /**
     * delete facility and relates stations
     * @param $id: facility's primary key
     * @return $res: 1=>success, 0=>failed
     * 2019-06-27 by hmc
    */
    public function delete_facility( $id )
    {
        $this->db->delete("tbl_facility", array("id"=>$id));
        $this->db->delete("tblstations", array("facId"=>$id));
        return 1;
    }

    /**
     * get facility info
     * @param $id: facility's primary key
     * @return $res: facility info
     * 2019-06-27 by hmc
    */
    public function get_facility_via_id( $id )
    {
        return $this->db->get_where("tbl_facility", array("id"=>$id))->row_array();
    }

    /**
     * get stations list via facility
     * @param $facility: facility's primary key
     * @return $res: stations list
     * 2019-06-27 by hmc
     */
    public function get_stations_via_facility( $facility )
    {
        return $this->db->get_where("tblstations", array("facId"=>$facility))->result_array();
    }
}