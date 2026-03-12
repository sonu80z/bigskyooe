<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Patients extends MU_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/patient_model', 'patient_model');
        $this->load->model('admin/config_model', 'config_model');
        $this->load->model('admin/auth_model', 'auth_model');
        $this->load->model('admin/facility_model', 'facility_model');
        $this->load->library('data_cache');
    }

    public function index()
    {
        $data['patients'] = $this->patient_model->get_all_patients();
        // Pre-load facility lookup to eliminate N+1 queries in the view
        $data['facility_lookup'] = $this->data_cache->get_facilities_lookup();
        $data['title'] = 'Patients List';
        $data['page_js'] = array();
        $data['view'] = 'admin/patients/patient_list';
        $this->load->view('layout', $data);
    }
    public function add()
    {
        $this->load->model('admin/division_model', 'division_model');
        $data['title'] = 'Add New Patient';
        $data['facilities'] = $this->data_cache->get_facilities();
        $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
        $data['page_js'] = array();
        $data['view'] = 'admin/patients/patient_add';
        $this->load->view('layout', $data);
    }
    public function create()
    {
        $isadmin = 0;
        if ($this->input->post('a_u_a_role') == 1) {
            $isadmin = 1;
        }
//        $pwchange = 0;
//        if ($this->input->post('a_u_a_change_pwd') == 'on') {
//            $pwchange = 1;
//        }
//        $dispatch = 0;
//        if ($this->input->post('a_u_a_dispatch') == 'on') {
//            $dispatch = 1;
//        }
//        $facilitiesarr = $this->input->post('a_u_a_facility');
//        for ($i = 0; $i < count($facilitiesarr); $i++) {
//
//        }
        //log_message('error', $this->input->post('a_u_a_facility'));
        $gaobj = new GoogleAuthenticator();
        $secret = $gaobj->createSecret();
        $data = array(
            'PATIENT_MRN' => $this->input->post('a_u_a_patient_mrn'),
            'SS_NO' => $this->input->post('a_u_a_ss_no'),
            'SECONDARY_ID' => $this->input->post('a_u_a_secondary_id'),
            'LAST_NAME' => $this->input->post('a_u_a_last_name'),
            'FIRST_NAME' => $this->input->post('a_u_a_first_name'),
            'MI' => $this->input->post('a_u_a_mi'),
            'SUFFIX' => $this->input->post('a_u_a_suffix'),
            'DOB' => $this->input->post('a_u_a_dob'),
            'GENDER' => $this->input->post('a_u_a_gender'),
            'HB' => $this->input->post('a_u_a_hb'),
            'HB_INSTITUTION' => $this->input->post('a_u_a_hb_institution'),
            'NH' => $this->input->post('a_u_a_nh'),
            'NH_INSTITUTION' => $this->input->post('a_u_a_nh_institution'),
            'LAB' => $this->input->post('a_u_a_lab'),
            'LAB_PRO' => $this->input->post('a_u_a_lab_pro'),
            'ADDRESS1' => $this->input->post('a_u_a_address1'),
            'ADDRESS2' => $this->input->post('a_u_a_address2'),
            'CITY' => $this->input->post('a_u_a_city'),
            'STATE' => $this->input->post('a_u_a_state'),
            'PATIENT_ZIP' => $this->input->post('a_u_a_patient_zip'),
            'PHONE' => $this->input->post('a_u_a_phone'),
            'SECONDARY_PHONE' => $this->input->post('a_u_a_secondary_phone'),
            'EMAIL' => $this->input->post('a_u_a_email'),
            'CREATION_DATE' => $this->input->post('a_u_a_creation_date'),
            'LAST_ORDER_DATE' => $this->input->post('a_u_a_last_order_date'),
            'LAST_INSURANCE_DATE' => $this->input->post('a_u_a_last_insurance_date'),
            'REMARKS' => $this->input->post('a_u_a_remarks'),
            'MEDICAL_ALERTS' => $this->input->post('a_u_a_medical_alerts'),
            'CATEGORY_DESC' => $this->input->post('a_u_a_category_desc'),
            'PROVIDER_ID' => $this->input->post('a_u_a_provider_id'),
            'REFERRING_LAST_NAME' => $this->input->post('a_u_a_referring_last_name'),
            'REFERRING_FIRST_NAME' => $this->input->post('a_u_a_referring_first_name'),
            'PATIENT_NAME2' => $this->input->post('a_u_a_patient_name2'),
            'DECEASED' => $this->input->post('a_u_a_deceased'),
        );
        $data = $this->security->xss_clean($data);
        $result = $this->patient_model->add_patient($data);
        if ($result) {
            $this->session->set_flashdata('msg', 'A Patient has been added successfully!');
            redirect(base_url('admin/patients'));
        }
    }

    public function edit($id = 0)
    {
        $id = $this->security->xss_clean($id);
        $data['patient'] = $this->patient_model->get_patient_info($id);;
        $data['title'] = "Edit Patient";
        $data['page_js'] = array();
        $data['view'] = 'admin/patients/patient_edit';
        $this->load->view('layout', $data);
    }

    public function import_xls() {
        // Lazy-load PHPExcel only when importing
        require_once APPPATH.'third_party/phpexcel/PHPExcel.php';
        if ($this->input->post('submit')){
            $path = FCPATH.'uploads/';
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_space'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('uploadFile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
            if(empty($error)){
                if (!empty($data['upload_data']['file_name'])) {
                    $import_xls_file = $data['upload_data']['file_name'];
                } else {
                    $import_xls_file = 0;
                }
                $inputFileName = $path . $import_xls_file;

                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $flag = true;
                    $i=2;
                    foreach ($allDataInSheet as $value) {
                        if($flag){
                            $flag =false;
                            continue;
                        }
                        $inserdata[$i]['PATIENT_MRN'] = $value['A'];
                        $inserdata[$i]['SS_NO'] = $value['B'];
                        $inserdata[$i]['SECONDARY_ID'] = $value['C'];
                        $inserdata[$i]['LAST_NAME'] = $value['D'];
                        $inserdata[$i]['FIRST_NAME'] = $value['E'];
                        $inserdata[$i]['MI'] = $value['F'];
                        $inserdata[$i]['SUFFIX'] = $value['G'];
                        $inserdata[$i]['DOB'] = $value['H'];
                        $inserdata[$i]['GENDER'] = $value['I'];
                        $inserdata[$i]['HB'] = $value['J'];
                        $inserdata[$i]['HB_INSTITUTION'] = $value['K'];
                        $inserdata[$i]['NH'] = $value['L'];
                        $inserdata[$i]['NH_INSTITUTION'] = $value['M'];
                        $inserdata[$i]['LAB'] = $value['N'];
                        $inserdata[$i]['LAB_PRO'] = $value['O'];
                        $inserdata[$i]['ADDRESS1'] = $value['P'];
                        $inserdata[$i]['ADDRESS2'] = $value['Q'];
                        $inserdata[$i]['CITY'] = $value['R'];
                        $inserdata[$i]['STATE'] = $value['S'];
                        $inserdata[$i]['PATIENT_ZIP'] = $value['T'];
                        $inserdata[$i]['PHONE'] = $value['U'];
                        $inserdata[$i]['SECONDARY_PHONE'] = $value['V'];
                        $inserdata[$i]['EMAIL'] = $value['W'];
                        $inserdata[$i]['CREATION_DATE'] = $value['X'];
                        $inserdata[$i]['LAST_ORDER_DATE'] = $value['Y'];
                        $inserdata[$i]['LAST_INSURANCE_DATE'] = $value['Z'];
                        $inserdata[$i]['REMARKS'] = $value['AA'];
                        $inserdata[$i]['MEDICAL_ALERTS'] = $value['AB'];
                        $inserdata[$i]['CATEGORY_DESC'] = $value['AC'];
                        $inserdata[$i]['PROVIDER_ID'] = $value['AD'];
                        $inserdata[$i]['REFERRING_LAST_NAME'] = $value['AE'];
                        $inserdata[$i]['REFERRING_FIRST_NAME'] = $value['AF'];
                        $inserdata[$i]['PATIENT_NAME2'] = $value['AG'];
                        $inserdata[$i]['DECEASED'] = $value['AH'];
                        $i++;
                    }
                    $result = $this->patient_model->import_patient_data($inserdata);
                    if($result){
                        $this->session->set_flashdata('msg', 'Manager has been added successfully!');
                        redirect(base_url('admin/patients'));
                    }else{
                        echo "ERROR !";
                    }

                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' .$e->getMessage());
                }
            }else{
                echo $error['error'];
            }
        }
    }
    public function update($id = 0){
        //$id = $this->security->xss_clean($id);
        $data = array(
            'PATIENT_MRN' => $this->input->post('a_u_a_patient_mrn'),
            'SS_NO' => $this->input->post('a_u_a_ss_no'),
            'SECONDARY_ID' => $this->input->post('a_u_a_secondary_id'),
            'LAST_NAME' => $this->input->post('a_u_a_last_name'),
            'FIRST_NAME' => $this->input->post('a_u_a_first_name'),
            'MI' => $this->input->post('a_u_a_mi'),
            'SUFFIX' => $this->input->post('a_u_a_suffix'),
            'DOB' => $this->input->post('a_u_a_dob'),
            'GENDER' => $this->input->post('a_u_a_gender'),
            'HB' => $this->input->post('a_u_a_hb'),
            'HB_INSTITUTION' => $this->input->post('a_u_a_hb_institution'),
            'NH' => $this->input->post('a_u_a_nh'),
            'NH_INSTITUTION' => $this->input->post('a_u_a_nh_institution'),
            'LAB' => $this->input->post('a_u_a_lab'),
            'LAB_PRO' => $this->input->post('a_u_a_lab_pro'),
            'ADDRESS1' => $this->input->post('a_u_a_address1'),
            'ADDRESS2' => $this->input->post('a_u_a_address2'),
            'CITY' => $this->input->post('a_u_a_city'),
            'STATE' => $this->input->post('a_u_a_state'),
            'PATIENT_ZIP' => $this->input->post('a_u_a_patient_zip'),
            'PHONE' => $this->input->post('a_u_a_phone'),
            'SECONDARY_PHONE' => $this->input->post('a_u_a_secondary_phone'),
            'EMAIL' => $this->input->post('a_u_a_email'),
            'CREATION_DATE' => $this->input->post('a_u_a_creation_date'),
            'LAST_ORDER_DATE' => $this->input->post('a_u_a_last_order_date'),
            'LAST_INSURANCE_DATE' => $this->input->post('a_u_a_last_insurance_date'),
            'REMARKS' => $this->input->post('a_u_a_remarks'),
            'MEDICAL_ALERTS' => $this->input->post('a_u_a_medical_alerts'),
            'CATEGORY_DESC' => $this->input->post('a_u_a_category_desc'),
            'PROVIDER_ID' => $this->input->post('a_u_a_provider_id'),
            'REFERRING_LAST_NAME' => $this->input->post('a_u_a_referring_last_name'),
            'REFERRING_FIRST_NAME' => $this->input->post('a_u_a_referring_first_name'),
            'PATIENT_NAME2' => $this->input->post('a_u_a_patient_name2'),
            'DECEASED' => $this->input->post('a_u_a_deceased'),
        );
        $data = $this->security->xss_clean($data);
        $result = $this->patient_model->edit_patient($data, $id);
        if ($result){
            $this->session->set_flashdata('msg', 'Updated successfully!');
            redirect(base_url('admin/patients'));
        }
    }
    public function delete($id = 0)
    {
        $id = $this->security->xss_clean($id);
        $this->db->delete('tbl_patient', array('id' => $id));
        $this->session->set_flashdata('msg', 'Deleted successfully!');
        redirect(base_url('admin/patients'));
    }
}
