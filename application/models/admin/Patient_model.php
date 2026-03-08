<?php
/**
 * Created by PhpStorm.
 * patient: longja
 * Date: 2019/11/24
 * Time: 03:05
 */
class Patient_model extends CI_Model
{
    public function add_patient($data){
        $this->db->insert('tbl_patient', $data);
        return true;
    }

    public function edit_patient($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('tbl_patient', $data);
        return true;
    }

    //---------------------------------------------------
    // get all patients for server-side datatable processing (ajax based)
    public function get_all_patients(){

        return $this->db->get('tbl_patient')->result_array();
    }

    /**
     * get patients via rolw
     * @param $role: patient's role string
     * @return patients list
     * 2019-06-25 via hmc
     */
    public function get_patients_via_role ( $role )
    {
        $query = $this->db->get_where("tbl_patient", array("role"=>$role));
        return $query->result_array();
    }

    /**
     * confirm whether same patientname exist already or now
     * @param $patientname: patientname that confirm
     * @return $ret: 1=> exist already, 0=> not exist same patientname
     * 2018:09:13 by hmc
     */
    public function confirm_admin_patientname($patientname)
    {
        $patientname = $this->db->escape_str($patientname);

        $query = $this->db->get_where('tbl_patient', array('patientname'=>$patientname));
        if ($query->num_rows() == 0){
            return false;
        }
        return true;
    }

    /**
     * get patient info
     * @param $patient: patient's primary key
     * @return patient info
     * 2019-06-25 via hmc
     */
    public function get_patient_info( $id )
    {
        return $this->db->get_where("tbl_patient", array("id"=>$id))->row_array();
    }
    public function import_patient_data($data){
        $res = $this->db->insert_batch('tbl_patient', $data);
        if($res){
            return TRUE;
        } else{
            return FALSE;
        }
    }
}
