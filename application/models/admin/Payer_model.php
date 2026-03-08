<?php
/**
 * Payer Model
 * Manages payer/insurance company data
 * Separated from tbllists for better organization
 */

class Payer_model extends CI_Model
{
    /**
     * Get all payers
     * @return array
     */
    public function get_all(){
        $sql = 'SELECT * FROM tbl_payer ORDER BY name ASC';
        return $this->db->query($sql)->result_array();
    }

    /**
     * Get payer by ID
     * @param int $id
     * @return array
     */
    public function get_by_id($id){
        $sql = 'SELECT * FROM tbl_payer WHERE id = ?';
        return $this->db->query($sql, array($id))->row_array();
    }

    /**
     * Add new payer
     * @param array $data
     * @return bool
     */
    public function add($data){
        return $this->db->insert('tbl_payer', $data);
    }

    /**
     * Update payer
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('tbl_payer', $data);
    }

    /**
     * Delete payer
     * @param int $id
     * @return bool
     */
    public function delete($id){
        $this->db->where('id', $id);
        return $this->db->delete('tbl_payer');
    }

    /**
     * Get active payers only
     * @return array
     */
    public function get_active(){
        $sql = 'SELECT * FROM tbl_payer WHERE status = 1 ORDER BY name ASC';
        return $this->db->query($sql)->result_array();
    }
}
