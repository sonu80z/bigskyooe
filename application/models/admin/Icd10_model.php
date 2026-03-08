<?php
/**
 * ICD10 Model
 * Manages ICD10 diagnosis codes (symptoms)
 */

class Icd10_model extends CI_Model
{
    /**
     * Get all ICD10 codes with descriptions
     * @return array
     */
    public function get_all(){
        $sql = 'SELECT * FROM tbl_icd10 ORDER BY description ASC';
        return $this->db->query($sql)->result_array();
    }

    /**
     * Get ICD10 code by ID
     * @param int $id
     * @return array
     */
    public function get_by_id($id){
        $sql = 'SELECT * FROM tbl_icd10 WHERE id = ?';
        return $this->db->query($sql, array($id))->row_array();
    }

    /**
     * Get ICD10 code by code
     * @param string $code
     * @return array
     */
    public function get_by_code($code){
        $sql = 'SELECT * FROM tbl_icd10 WHERE code = ?';
        return $this->db->query($sql, array($code))->row_array();
    }

    /**
     * Add new ICD10 code
     * @param array $data
     * @return bool
     */
    public function add($data){
        return $this->db->insert('tbl_icd10', $data);
    }

    /**
     * Update ICD10 code
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('tbl_icd10', $data);
    }

    /**
     * Delete ICD10 code
     * @param int $id
     * @return bool
     */
    public function delete($id){
        $this->db->where('id', $id);
        return $this->db->delete('tbl_icd10');
    }

    /**
     * Search ICD10 codes by code or description for autocomplete
     * @param string $term Search term
     * @param int $limit Maximum results to return
     * @return array
     */
    public function search_icd10($term, $limit = 20){
        $this->db->like('code', $term);
        $this->db->or_like('description', $term);
        $this->db->order_by('code', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get('tbl_icd10');
        return $query->result_array();
    }
}
