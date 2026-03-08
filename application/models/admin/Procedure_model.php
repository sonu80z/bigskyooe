<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 4:39 AM
 */

class Procedure_model extends CI_Model
{
    /**
     * get facilities lists
     * @return $ret: lists
     */
    public function get_all_procedures(){
        $this->db->order_by('description', 'ASC');
        return $this->db->get("tbl_proceduremanagment")->result_array();
    }

    /**
     * get procedure list via modality
     * @param $modality: modality
     * @return $res: procedure list
     * 2019-06-26 by hmc
    */
    public function get_procedures_via_modality($modality)
    {
        return $this->db->get_where("tbl_proceduremanagment", array("modality"=>$modality))->result_array();
    }

    /**
     * get procedure info via primary key
     * @param $id: primary key
     * @return $res: procedure info
     * 2019-06-26 by hmc
    */
    public function get_procedure_info_via_id($id)
    {
        return $this->db->get_where("tbl_proceduremanagment", array("id"=>$id))->row_array();
    }

    /**
     * add procedure
     * @param $data: add info
     * @return $res: 1=>success, 0=>failed
     * 2019-06-27 by hmc
    */
    public function create_procedure( $data )
    {
        return $this->db->insert("tbl_proceduremanagment", $data);
    }

    /**
     * update procedure
     * @param $id: procedure's primarykey
     * @param $data: update info
     * @return $res: 1=>success, 0=>failed
     * 2019-06-27 by hmc
     */
    public function edit_procedure($id, $data )
    {
        $this->db->where(array("id"=>$id));
        return $this->db->update("tbl_proceduremanagment", $data);
    }

    /**
     * delete procedure
     * @param $id: procedure's primary key
     * 2019-06-27 by hmc
    */
    public function del_procedure( $id )
    {
        return $this->db->delete("tbl_proceduremanagment", array("id"=>$id));
    }

    /**
     * get procedure info via primary key
     * @param $id: procedure's priamry key
     * @return $res: procedure info
     * 2019-06-27 by hmc
    */
    public function get_procedure_via_id( $id )
    {
        return $this->db->get_where("tbl_proceduremanagment", array("id"=>$id))->row_array();
    }

    public function get_procedure_name($id) {
        $this->db->select('description');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_proceduremanagment');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_symptom_name($id) {
        $this->db->select('value');
        $this->db->where(array(
            'id' => $id,
            'name' => 'icd'
        ));
        $query = $this->db->get('tbl_lists');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /**
     * Search procedures by CPT code or description for autocomplete
     * @param string $term Search term
     * @param int $limit Maximum results to return
     * @return array
     */
    public function search_procedures($term, $limit = 20){
        $this->db->like('cpt_code', $term);
        $this->db->or_like('description', $term);
        $this->db->order_by('cpt_code', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get('tbl_proceduremanagment');
        return $query->result_array();
    }
}