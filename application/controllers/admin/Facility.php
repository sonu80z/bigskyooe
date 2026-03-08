<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 1:58 AM
 */

class Facility extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('admin/facility_model', 'facility_model');
        $this->load->model('admin/division_model', 'division_model');
        $this->load->library('data_cache');
    }

    /**
     *  go to facility add page
     * @param $id: facility's primary key
     * 2019-06-27 by hmc
    */
    public function add( $id=null )
    {
        $data['title'] = 'Add Facility';
        $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
        $data['id'] = $id;
        if ( $id != null ) {
            $data['facility'] = $this->facility_model->get_facility_via_id($id);
            $data['stations'] = $this->facility_model->get_stations_via_facility($id);
            $data['subdivisions'] = $this->division_model->get_division_via_parent($data['facility']['division']);
            $data['regions'] = $this->division_model->get_division_via_parent($data['facility']['subdivision']);
        }
        $data['view'] = 'admin/facility/add';
        $data['page_js'] = array('facility.js');
        $this->load->view('layout', $data);
    }

    /**
     * create & update facility
     * @param $id: facility's primary key
     * 2019-06-27 by hmc
    */
    public function create( $id = null ){
        $data = array(
            'facility_name' => $this->input->post('facility_name'),
            'is_active' => $this->input->post('is_active'),
            'facility_type' => $this->input->post('facility_type'),
            'facility_NPI' => $this->input->post('facility_NPI'),
            'admin_name' => $this->input->post('admin_name'),
            'is_pcc' => $this->input->post('is_pcc'),
            'is_pf' => $this->input->post('is_pf'),
            'is_ts' => $this->input->post('is_ts'),
            'is_urad' => $this->input->post('is_urad'),
            'is_ramsoft' => $this->input->post('is_ramsoft'),
            'address1' => $this->input->post('address1'),
            'address2' => $this->input->post('address2'),
            'address_city' => $this->input->post('address_city'),
            'address_state' => $this->input->post('address_state'),
            'address_zip' => $this->input->post('address_zip'),
            'phone' => $this->input->post('phone'),
            'fax1' => $this->input->post('fax1'),
            'fax2' => $this->input->post('fax2'),
            'fax3' => $this->input->post('fax3'),
            'fax4' => $this->input->post('fax4'),
            'email' => $this->input->post('email'),
            'email_order' => $this->input->post('email_order'),
            'hospise' => $this->input->post('hospise'),
            'main_state' => $this->input->post('main_state'),
            'billing_contact' => $this->input->post('billing_contact'),
            'billing_phone' => $this->input->post('billing_phone'),
            'billing_fax' => $this->input->post('billing_fax'),
            'billing_req' => $this->input->post('billing_req'),
            'billing_aa_num' => $this->input->post('billing_aa_num'),
            'division' => $this->input->post('af_divisions'),
            'subdivision' => $this->input->post('af_subdivisions'),
            'region' => $this->input->post('af_regions'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $data = $this->security->xss_clean($data);

        // Server-side validation for mandatory fields
        $required = array('facility_name','facility_type','address1','address_city','address_state','address_zip','phone','fax1');
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->session->set_flashdata('msg', 'Error: ' . ucfirst(str_replace('_',' ',$field)) . ' is required.');
                redirect(base_url('admin/facility/add/' . $id));
                return;
            }
        }

        if ( isset($id) && $id != "" && $id != null ) { // update facility
            $this->facility_model->edit_facility($id, $data);
            $this->session->set_flashdata('msg', 'Facility has been updated successfully!');
        } else {                                        // create new facility
            $id = $this->facility_model->create_facility($data);
            $this->session->set_flashdata('msg', 'Facility has been added successfully!');
        }
        // add & update stations
        $this->facility_model->edit_stations($id, $this->input->post("stations"));

        // Invalidate facility cache so other pages see fresh data
        $this->data_cache->invalidate_facilities();

        redirect(base_url('admin/facility/lists'));
    }

    // go to facility lists page
    public function lists(){
        $data['title'] = 'Facilities List';
        $data['facilities'] = $this->facility_model->get_all_facilites();
        $data['view'] = 'admin/facility/list';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }

    /**
     * delete facility and related stations
     * @param $facility: facility's primary key
     * 2019-06-27 by hmc
    */
    public function del_facility ( $facility = null )
    {
        $this->facility_model->delete_facility( $facility );
        $this->data_cache->invalidate_facilities();
        $this->session->set_flashdata('msg', 'Facility has been removed successfully!');
        redirect(base_url('admin/facility/lists'));
    }

    /**
     * get facility info via primary key
     * 2019-06-25 by hmc
    */
    public function get_facility_info()
    {
        $facility = $this->input->post("facility", TRUE);
        
        // Log the request for debugging
        log_message('debug', 'get_facility_info called - Facility ID: ' . var_export($facility, true) . ' - User Session: ' . var_export($this->session->userdata('did'), true));
        
        if(empty($facility) || !is_numeric($facility)) {
            log_message('error', 'get_facility_info - Invalid facility ID provided: ' . var_export($facility, true));
            res_write(array('status' => 0, 'message' => 'Invalid facility ID'));
            return;
        }
        
        try {
            $res = $this->facility_model->get_facility_info($facility);
            $stations = $this->facility_model->get_facility_stations($facility);

            $res_data = array(
                'status' => 0
            );

            if( $res ) {
                // Build combined fax from fax1-fax4; fall back to legacy 'fax' column if all are empty
                $fax_numbers = array();
                if (!empty($res['fax1'])) $fax_numbers[] = $res['fax1'];
                if (!empty($res['fax2'])) $fax_numbers[] = $res['fax2'];
                if (!empty($res['fax3'])) $fax_numbers[] = $res['fax3'];
                if (!empty($res['fax4'])) $fax_numbers[] = $res['fax4'];

                if (empty($fax_numbers) && !empty($res['fax'])) {
                    // Legacy facility — only old single fax column was populated
                    $fax_numbers[] = $res['fax'];
                }

                $res['fax'] = implode(', ', $fax_numbers);
                
                $res_data = array(
                    'status' => 1,
                    'info'=>$res,
                    'stations' => $stations
                );
                
                log_message('debug', 'get_facility_info - Success for facility ID: ' . $facility);
            } else {
                log_message('warning', 'get_facility_info - No data found for facility ID: ' . $facility);
            }

            res_write($res_data);
            
        } catch (Exception $e) {
            log_message('error', 'get_facility_info - Exception: ' . $e->getMessage() . ' - Facility ID: ' . $facility);
            res_write(array('status' => 0, 'message' => 'Database error'));
        }
    }

    /**
     * get division list via parent division id
     * 2019-06-25 by hmc
     */
    public function get_division_via_parent()
    {
        $division = $this->input->post("division", TRUE);
        $res = $this->division_model->get_division_via_parent($division);

        $res_data = array(
            'status' => 0
        );

        if( $res ) {
            $res_data = array(
                'status' => 1,
                'list'=>$res
            );
        }
        res_write($res_data);
    }

    /**
     * Create facility quickly from order form
     * 2026-02-18 by system
     */
    public function create_quick()
    {
        if (!$this->input->is_ajax_request()) {
            res_write(array('status' => 0, 'message' => 'Invalid request'));
            return;
        }

        $data = array(
            'facility_name' => $this->input->post('facility_name', TRUE),
            'facility_type' => $this->input->post('facility_type', TRUE),
            'address1'      => $this->input->post('address1', TRUE),
            'address_city'  => $this->input->post('address_city', TRUE),
            'address_state' => $this->input->post('address_state', TRUE),
            'address_zip'   => $this->input->post('address_zip', TRUE),
            'phone'         => $this->input->post('phone', TRUE),
            'fax1'          => $this->input->post('fax', TRUE),
            'is_active'     => 1,
            'main_state'    => $this->input->post('address_state', TRUE),
            'created_at'    => date('Y-m-d H:i:s')
        );

        // Validate required fields
        if (empty($data['facility_name']) || empty($data['facility_type']) ||
            empty($data['address1'])      || empty($data['address_city'])  ||
            empty($data['address_state']) || empty($data['address_zip'])   ||
            empty($data['phone'])         || empty($data['fax1'])) {
            res_write(array('status' => 0, 'message' => 'All required fields must be filled'));
            return;
        }

        $data['facility_name'] = trim($data['facility_name']);
        $existing = $this->facility_model->get_facility_by_name($data['facility_name']);
        if (!empty($existing) && !empty($existing['id'])) {
            res_write(array(
                'status' => 1,
                'message' => 'Facility already exists',
                'facility_id' => $existing['id']
            ));
            return;
        }

        // Clean data
        $data = $this->security->xss_clean($data);

        // Create facility
        $facility_id = $this->facility_model->create_facility($data);

        if ($facility_id) {
            $this->data_cache->invalidate_facilities();
            res_write(array(
                'status' => 1,
                'message' => 'Facility created successfully',
                'facility_id' => $facility_id
            ));
        } else {
            res_write(array('status' => 0, 'message' => 'Error creating facility'));
        }
    }
}
