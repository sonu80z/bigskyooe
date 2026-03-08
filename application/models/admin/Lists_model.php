<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 12:07 PM
 */

class Lists_model extends CI_Model
{
    /**
     * get facilities lists
     * @return $ret: lists
     */
    public function get_all_lists(){
        $sql ='SELECT *
              FROM tbl_lists';
        return $this->db->query($sql)->result_array();
    }
    public function get_all_lists_by_name($name){
        if($name == ''){
            $sql ="SELECT *
              FROM tbl_lists";
        }else{
            $sql ="SELECT *
              FROM tbl_lists where `name` = '".$name."'";
        }
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * Create/Insert new list item
     * @param $data: array of list data
     */
    public function create_list($data){
        return $this->db->insert('tbl_lists', $data);
    }

    /**
     * Get single list item by ID
     */
    public function get_list($id){
        return $this->db->where('id', $id)->get('tbl_lists')->row_array();
    }

    /**
     * Update a list item
     */
    public function update_list($id, $data){
        return $this->db->where('id', $id)->update('tbl_lists', $data);
    }

    /**
     * Delete a list item
     */
    public function delete_list($id){
        return $this->db->where('id', $id)->delete('tbl_lists');
    }
}