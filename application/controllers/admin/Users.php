<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MU_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('GoogleAuthenticator');
        $this->load->model('admin/user_model', 'user_model');
        $this->load->model('admin/config_model', 'config_model');
        $this->load->model('admin/auth_model', 'auth_model');
        $this->load->model('admin/facility_model', 'facility_model');
        $this->load->model('admin/division_model', 'division_model');
        $this->load->model('admin/log_model', 'log_model');
        $this->load->model('admin/state_model', 'state_model');
        $this->load->library('datatable'); // loaded my custom serverside datatable library
        $this->load->library('data_cache');
    }

    public function index()
    {
        $users = $this->user_model->get_all_users();
        
        // Build state lookup map to eliminate N+1 queries
        $all_states = $this->state_model->get_all_states();
        $state_lookup = array();
        foreach($all_states as $st) {
            $state_lookup[$st['fldSt']] = $st;
        }
        
        foreach($users as $key => $user_info){
            // Use lookup instead of individual DB query per user
            $users[$key]['state_info'] = isset($state_lookup[$user_info['mainstate']]) 
                ? $state_lookup[$user_info['mainstate']] 
                : array();
        }
        $data['users'] = $users;
        $data['states'] = $this->state_model->get_active_states();
        $data['title'] = 'Users List';
        $data['view'] = 'admin/users/user_list';
        $data['page_js'] = array('users.js');
        $data['page_plugins'] = array('bootstrap-select', 'jquery-form');
        // $this->import_bulk();
        $this->load->view('layout', $data);
    }

    public function add()
    {
        $data['title'] = 'Add New User';
        $data['facilities'] = $this->facility_model->get_all_facilites();
        $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
        $data['states'] = $this->state_model->get_all_states();
        $data['view'] = 'admin/users/user_add';
        $data['page_js'] = array('users.js');
        $data['page_plugins'] = array('bootstrap-select', 'jquery-form');
        $this->load->view('layout', $data);
    }

    public function edit($id = 0)
    {
        $id = $this->security->xss_clean($id);
        $user = $this->user_model->get_user_info($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect(base_url('admin/users'));
            return;
        }
        $data['user'] = $user;
        $data['facilities'] = $this->facility_model->get_all_facilites();
        $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
        $data['subdivisions'] = $this->division_model->get_division_via_parent($user['division']);
        $data['regions'] = $this->division_model->get_division_via_parent($user['region']);
        $data['states'] = $this->state_model->get_all_states();
        $data['title'] = "Edit User";
        $data['view'] = 'admin/users/user_edit';
        $data['page_js'] = array('users.js');
        $data['page_plugins'] = array('bootstrap-select', 'jquery-form');
        $this->load->view('layout', $data);
    }

    public function view($id = 0)
    {
        $id = $this->security->xss_clean($id);
        $user = $this->user_model->get_user_info($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect(base_url('admin/users'));
            return;
        }
        $data['user'] = $user;
        $data['facilities'] = $this->facility_model->get_all_facilites();
        $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
        $data['subdivisions'] = $this->division_model->get_division_via_parent($user['division']);
        $data['regions'] = $this->division_model->get_division_via_parent($user['region']);
        $data['states'] = $this->state_model->get_active_states();
        $data['title'] = "View User";
        $data['view'] = 'admin/users/user_view';
        $data['page_js'] = array('users.js');
        $data['page_plugins'] = array('bootstrap-select', 'jquery-form');
        $this->load->view('layout', $data);
    }

    public function confirm_admin_username()
    {
        $username = $this->input->post('username');
        $duplicate = $this->user_model->confirm_admin_username($username);

        $output_data = array(
            'status'=>0,
            'msg'=>''
        );
        if($duplicate){
            $output_data = array(
                'status'=>1,
                'msg'=>'Username already exists.'
            );
        }
        echo json_encode($output_data); die;

    }

    public function create()
    {
        $add_type = $this->input->post('add_type_value');
        $add_type = !empty($add_type) ? $add_type : 'user';
        
        // Handle Physician Only submission
        if ($add_type === 'physician') {
            return $this->create_physician_only();
        }
        
        // Handle Regular User Account submission
        $username = $this->input->post('a_u_a_username');
        if ($this->user_model->confirm_admin_username($username)) {
            if (isset($_POST['is_ajax'])) {
                $output_data = array(
                    'status' => 0,
                    'msg' => 'Username already exists. Please choose a different username.'
                );
                echo json_encode($output_data);
                die;
            }
            $this->session->set_flashdata('error', 'Username already exists. Please choose a different username.');
            redirect(base_url('admin/users/add'));
            return;
        }
        $isadmin = 0;
        if (in_array((int)$this->input->post('a_u_a_role'), array(1, 2))) {
            $isadmin = 1;
        }
        $pwchange = 0;
        if ($this->input->post('a_u_a_change_pwd') == 'on') {
            $pwchange = 1;
        }
        $dispatch = 0;
        if ($this->input->post('a_u_a_dispatch') == 'on') {
            $dispatch = 1;
        }
        $also_technologist = ($this->input->post('a_u_a_also_technologist') == 'on') ? 1 : 0;
        $facilitiesarr = $this->input->post('a_u_a_facility');
        $permittedstatearr = $this->input->post('a_u_a_permitted_state');
        if (empty($facilitiesarr) || count($facilitiesarr) < 2){
            $temp = $this->input->post('a_u_a_facility');
            if(empty($temp)){
                $facilitiesarr_string = "";
            }else{
                $facilitiesarr_string = $temp[0];
            }
        } else{
            $facilitiesarr_string = join("=", $facilitiesarr);
        }
        if (empty($permittedstatearr) || count($permittedstatearr) < 2){
            $temp = $this->input->post('a_u_a_permitted_state');
            if(empty($temp)){
                $permittedstatearr_string = "";
            }else{
                $permittedstatearr_string = $temp[0];
            }
        }else {
            $permittedstatearr_string = join("=", $permittedstatearr);
        }
        $gaobj = new GoogleAuthenticator();
        $secret = $gaobj->createSecret();
        $data = array(
            'username' => $this->input->post('a_u_a_username'),
            'firstname' => $this->input->post('a_u_a_firstname'),
            'lastname' => $this->input->post('a_u_a_lastname'),
            'email' => $this->input->post('a_u_a_email'),
            'mainphone' => $this->input->post('a_u_a_main_mobile_no'),
            'phone' => $this->input->post('a_u_a_mobile_no'),
            'role' => $this->input->post('a_u_a_role'),
            'NPI' => $this->input->post('a_u_a_npi'),
            'facility' => $facilitiesarr_string,
            'email_pref' => 0,
            'sms_pref' => 0,
            'mainstate' => $this->input->post('a_u_a_state'),
            'secondarystate' => $this->input->post('a_u_a_secondary_state'),
            'permittedstate' => $permittedstatearr_string,
            'permittedtodispatch' => $this->input->post('a_u_a_dispatch'),
            'pwchange' => $pwchange,
            'fax' => $this->input->post('a_u_a_fax'),
            'prefix' => $this->input->post('a_u_a_prefix'),
            'suffix' => $this->input->post('a_u_a_suffix'),
            'division' => $this->input->post('af_divisions'),
            'subdivision' => $this->input->post('af_subdivisions'),
            'region' => $this->input->post('af_regions'),
            'zone' => $this->input->post('af_zone'),
            'deviceid' => $this->input->post('a_u_a_deviceid'),
            'acn_dispatch' => $dispatch,
            'also_technologist' => $also_technologist,
            'is_admin' => $isadmin,
            'password' => password_hash($this->input->post('a_u_a_password'), PASSWORD_BCRYPT),
            'auty_key' => $secret,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $data = $this->security->xss_clean($data);
        $result = $this->user_model->add_user($data);
        if ($result) {
            $this->data_cache->invalidate_users();
            if(isset($_POST['is_ajax'])){
                $data['id'] = $result;
                $output_data = array(
                    'status'=>1,
                    'msg'=>'Manager has been added successfully!',
                    'data'=>$data
                );
                echo json_encode($output_data); die;
            }else{
                $this->session->set_flashdata('msg', 'Manager has been added successfully!');
                redirect(base_url('admin/users'));
            }
        } else {
            if (isset($_POST['is_ajax'])) {
                $output_data = array(
                    'status' => 0,
                    'msg' => 'Unable to add user. Username may already exist.'
                );
                echo json_encode($output_data);
                die;
            }
            $this->session->set_flashdata('error', 'Unable to add user. Username may already exist.');
            redirect(base_url('admin/users/add'));
        }
    }

    /**
     * Create Physician Only (without user account)
     */
    public function create_physician_only()
    {
        $firstname = $this->input->post('a_u_a_firstname');
        $lastname = $this->input->post('a_u_a_lastname');
        $npi = $this->input->post('a_u_a_npi');
        $mainphone = $this->input->post('a_u_a_main_mobile_no');
        $phone = $this->input->post('a_u_a_mobile_no');
        $fax = $this->input->post('a_u_a_fax');
        // Support both field names (add-user page uses _physician suffix, inline dialog uses plain)
        $email = $this->input->post('a_u_a_email_physician');
        if (empty($email)) {
            $email = $this->input->post('a_u_a_email');
        }
        $mainstate = $this->input->post('a_u_a_state_physician');
        if (empty($mainstate)) {
            $mainstate = $this->input->post('a_u_a_state');
        }
        $prefix = $this->input->post('a_u_a_prefix');
        $suffix = $this->input->post('a_u_a_suffix');
        
        $facilitiesarr = $this->input->post('a_u_a_facility');
        if (empty($facilitiesarr) || count($facilitiesarr) < 2) {
            $temp = $this->input->post('a_u_a_facility');
            if (empty($temp)) {
                $facilitiesarr_string = "";
            } else {
                $facilitiesarr_string = $temp[0];
            }
        } else {
            $facilitiesarr_string = join("=", $facilitiesarr);
        }
        
        // Generate default username for physician-only entry
        // Format: phys_[NPI] or phys_[LastName][FirstInitial][timestamp]
        $default_username = 'phys_' . strtolower(substr($lastname, 0, 3)) . strtolower($firstname[0]) . time();
        if (!empty($npi)) {
            $default_username = 'phys_' . preg_replace('/[^0-9]/', '', $npi);
        }
        
        // Create physician record with auto-generated username
        $data = array(
            'username' => $default_username, // Auto-generated username for system use only
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'mainphone' => $mainphone,
            'phone' => $phone,
            'role' => 7, // Ordering Physician role
            'NPI' => $npi,
            'facility' => $facilitiesarr_string,
            'email_pref' => 0,
            'sms_pref' => 0,
            'mainstate' => $mainstate,
            'secondarystate' => '',
            'permittedstate' => '',
            'permittedtodispatch' => 0,
            'pwchange' => 0,
            'fax' => $fax,
            'prefix' => $prefix,
            'suffix' => $suffix,
            'division' => '',
            'subdivision' => '',
            'region' => '',
            'zone' => '',
            'deviceid' => '',
            'acn_dispatch' => 0,
            'is_admin' => 0,
            'password' => '', // No password for physician-only
            'auty_key' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        
        $data = $this->security->xss_clean($data);
        $result = $this->user_model->add_user($data);
        
        if ($result) {
            $this->data_cache->invalidate_users();
            if (isset($_POST['is_ajax'])) {
                $data['id'] = $result;
                $output_data = array(
                    'status' => 1,
                    'msg' => 'Ordering Physician has been added successfully!',
                    'data' => $data
                );
                echo json_encode($output_data);
                die;
            }
            $this->session->set_flashdata('msg', 'Ordering Physician has been added successfully!');
            redirect(base_url('admin/users'));
        } else {
            if (isset($_POST['is_ajax'])) {
                $output_data = array(
                    'status' => 0,
                    'msg' => 'Unable to add physician.'
                );
                echo json_encode($output_data);
                die;
            }
            $this->session->set_flashdata('error', 'Unable to add physician.');
            redirect(base_url('admin/users/add'));
        }
    }

    public function update($id = 0)
    {
        $id = $this->security->xss_clean($id);
        $user = $this->user_model->get_user_info($id);
        if (empty($user)) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect(base_url('admin/users'));
            return;
        }
        // Detect ordering physician entries
        $is_physician = ((int)$user['role'] === 7);

        $this->form_validation->set_rules('a_u_a_firstname', 'Firstname', 'trim|required');
        $this->form_validation->set_rules('a_u_a_lastname', 'Lastname', 'trim|required');
        if (!$is_physician) {
            $this->form_validation->set_rules('a_u_a_username', 'Username', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $user;
            $data['facilities'] = $this->facility_model->get_all_facilites();
            $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
            $data['subdivisions'] = $this->division_model->get_division_via_parent($user['division']);
            $data['regions'] = $this->division_model->get_division_via_parent($user['region']);
            $data['states'] = $this->state_model->get_all_states();
            $data['title'] = "Edit User";
            $data['view'] = 'admin/users/user_edit';
            $data['page_js'] = array('users.js');
            $data['page_plugins'] = array('bootstrap-select', 'jquery-form');
            $this->load->view('layout', $data);
        } else {
            // For physician entries, use physician-specific fields
            $role = $is_physician ? 7 : $this->input->post('a_u_a_role');
            $email = $is_physician ? $this->input->post('a_u_a_email_physician') : $this->input->post('a_u_a_email');
            $mainstate = $is_physician ? $this->input->post('a_u_a_state_physician') : $this->input->post('a_u_a_state');

            $isadmin = 0;
            if (in_array((int)$role, array(1, 2))) {
                $isadmin = 1;
            }
            $pwchange = 0;
            if ($this->input->post('a_u_a_change_pwd') == 'on') {
                $pwchange = 1;
            }
            $dispatch = 0;
            if ($this->input->post('a_u_a_dispatch') == 'on') {
                $dispatch = 1;
            }
            $also_technologist = ($this->input->post('a_u_a_also_technologist') == 'on') ? 1 : 0;
            $facilitiesarr = $this->input->post('a_u_a_facility');
            $permittedstatearr = $this->input->post('a_u_a_permitted_state');
            if (empty($facilitiesarr) || !is_array($facilitiesarr) || count($facilitiesarr) < 2){
                $temp = $this->input->post('a_u_a_facility');
                if(empty($temp)){
                    $facilitiesarr_string = "";
                } else if(is_array($temp)){
                    $facilitiesarr_string = $temp[0];
                } else {
                    $facilitiesarr_string = $temp;
                }
            } else{
                $facilitiesarr_string = join("=", $facilitiesarr);
            }
            if (empty($permittedstatearr) || !is_array($permittedstatearr) || count($permittedstatearr) < 2){
                $temp = $this->input->post('a_u_a_permitted_state');
                if(empty($temp)){
                    $permittedstatearr_string = "";
                } else if(is_array($temp)){
                    $permittedstatearr_string = $temp[0];
                } else {
                    $permittedstatearr_string = $temp;
                }
            }else {
                $permittedstatearr_string = join("=", $permittedstatearr);
            }

//            for ($i = 0; $i < count($facilitiesarr); $i++) {
//
//            }
            $data = array(
                'username' => $is_physician ? $user['username'] : $this->input->post('a_u_a_username'),
                'firstname' => $this->input->post('a_u_a_firstname'),
                'lastname' => $this->input->post('a_u_a_lastname'),
                'email' => $email,
                'mainphone' => $this->input->post('a_u_a_main_mobile_no'),
                'phone' => $this->input->post('a_u_a_mobile_no'),
                'role' => $role,
                'NPI' => (preg_match('/^X+$/', $this->input->post('a_u_a_npi')) || $this->input->post('a_u_a_npi') === '') ? $user['NPI'] : $this->input->post('a_u_a_npi'),
                'facility' => $facilitiesarr_string,
                'email_pref' => 0,
                'sms_pref' => 0,
                'mainstate' => $mainstate,
                'secondarystate' => $is_physician ? '' : $this->input->post('a_u_a_secondary_state'),
                'permittedstate' => $permittedstatearr_string,
                'permittedtodispatch' => $dispatch,
                'pwchange' => $pwchange,
                'fax' => $this->input->post('a_u_a_fax'),
                'prefix' => $this->input->post('a_u_a_prefix'),
                'suffix' => $this->input->post('a_u_a_suffix'),
                'division' => $this->input->post('af_divisions'),
                'subdivision' => $this->input->post('af_subdivisions'),
                'region' => $this->input->post('af_regions'),
                'zone' => $this->input->post('af_zone'),
                'deviceid' => $this->input->post('a_u_a_deviceid'),
                'acn_dispatch' => $dispatch,
                'also_technologist' => $also_technologist,
                'is_admin' => $isadmin,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            /*if (!isset($user['kyc_manager']) || $user['kyc_manager'] == '' || $user['kyc_manager'] == 0){
                $data['kyc_manager'] = $_SESSION['admin_id'];
                $data['kyc_at'] = date('Y-m-d H:i:s');
            }*/
            $data = $this->security->xss_clean($data);
            $result = $this->user_model->edit_user($data, $id);
            /*
                        //record logs
                        if ( $this->input->post('is_active', TRUE) == "1" )       // admin approve
                            $this->log_model->record_system_log( '', 1, 'approveA', $this->input->post('a_u_a_username', TRUE) );
                        else if ( $this->input->post('is_active', TRUE) == "2" )  // admin decline
                            $this->log_model->record_system_log( '', 2, 'declineA', $this->input->post('a_u_a_username', TRUE) );*/

            if ($result) {
                /*if ( $this->input->post('is_active', TRUE) == "1" && $user['is_active'] != "1") { // trader`s account approved
                    $_SESSION["iemail"] = $this->input->post('a_u_a_email');
                    redirect("admin/email/send_account_approve_mail_for_admin/0"); // parameter 0: user
                } else {
                    $this->session->set_flashdata('msg', 'Manager info has been updated successfully!');
                    redirect(base_url('admin/users'));
                }*/
                $this->data_cache->invalidate_users();
                $this->session->set_flashdata('msg', 'User has been updated successfully!');
                redirect(base_url('admin/users'));
            }
        }
    }

    public function del($id = 0)
    {
        $id = $this->security->xss_clean($id);
        $this->db->delete('tbl_user', array('id' => $id));
        $this->data_cache->invalidate_users();
        $this->session->set_flashdata('msg', 'Deleted successfully!');
        redirect(base_url('admin/users'));
    }

    /**
     * get user info
     * 2019-06-25 by hmc
     */
    public function get_user_info()
    {
        $user = $this->input->post("user", TRUE);
        $res = $this->user_model->get_user_info($user);

        $res_data = array(
            'status' => 0
        );

        if ($res) {
            $res_data = array(
                'status' => 1,
                'info' => $res
            );
        }

        res_write($res_data);
    }
    
    public function import_bulk()
    {
    
    $file_path = 'f:/BSI Ordering.csv';

    if (!file_exists($file_path)) {
        // Try alternative path
        $file_path = 'F:/BSI Ordering.csv';
        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'File not found: f:/BSI Ordering.csv or F:/BSI Ordering.csv');
            return;
        }
    }

    if (($file = fopen($file_path, "r")) === FALSE) {
        $this->session->set_flashdata('error', 'Unable to open file: ' . $file_path);
        return;
    }

    $header = fgetcsv($file); // skip header
    
    // Track all records and their status
    $import_results = [
        'total'     => 0,
        'success'   => 0,
        'failed'    => 0,
        'skipped'   => 0,
        'records'   => []
    ];

    $row_number = 1; // Start from 1 (header is row 0)

    while (($row = fgetcsv($file)) !== FALSE) {
        $row_number++;
        
        $firstname   = trim($row[0] ?? '');
        $lastname    = trim($row[1] ?? '');
        $username    = trim($row[2] ?? '');
        $passwordCsv = trim($row[3] ?? '');
        $email       = trim($row[4] ?? '');
        $role        = (int)($row[5] ?? 7);
        $npi         = trim($row[6] ?? '');
        $phone       = trim($row[7] ?? '');
        $mainstate   = trim($row[8] ?? '');
        $pwchange    = (int)($row[9] ?? 0);
        $status      = trim($row[10] ?? 'Enabled');
        $fax         = trim($row[11] ?? '');

        $import_results['total']++;
        $record_status = [
            'row_number'    => $row_number,
            'username'      => $username,
            'firstname'     => $firstname,
            'lastname'      => $lastname,
            'email'         => $email,
            'status'        => 'PENDING',
            'message'       => ''
        ];

        // Check if username is empty
        if (empty($username)) {
            $record_status['status'] = 'SKIPPED';
            $record_status['message'] = 'Username is empty';
            $import_results['skipped']++;
            $import_results['records'][] = $record_status;
            continue;
        }

        // Trim and clean username to remove extra spaces
        $username = trim($username);

        // Skip strict email validation during import
        // Email format will be validated during create/update if needed

        // Safety: only allow valid roles
        if (!in_array($role, [1,2,3,4,5,6,7,8])) {
            $role = 7;
        }

        // If password column already has bcrypt hash → use it
        if (!empty($passwordCsv) && strpos($passwordCsv, '$2y$') === 0) {
            $password = $passwordCsv;
        } else {
            $password = password_hash('DefaultPassword123', PASSWORD_BCRYPT);
        }

        $data = [
            'username'     => $username,
            'firstname'    => $firstname,
            'lastname'     => $lastname,
            'email'        => $email,
            'mainphone'    => $phone,
            'phone'        => $phone,
            'role'         => $role,
            'NPI'          => $npi,
            'mainstate'    => $mainstate,
            'pwchange'     => $pwchange,
            'fax'          => $fax,
            'status'       => $status,
            'email_pref'   => 0,
            'sms_pref'     => 0,
            'is_admin'     => ($role == 1 || $role == 2) ? 1 : 0,
            'facility'     => '',
            'secondarystate' => '',
            'permittedstate' => '',
            'permittedtodispatch' => 0,
            'division'     => 0,
            'subdivision'  => 0,
            'region'       => 0,
            'zone'         => 0,
            'acn_dispatch' => 0,
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        $data = $this->security->xss_clean($data);

        try {
            // Check for existing user
            $existing_user = $this->user_model->get_user_by_username($username);

            if ($existing_user) {
                // User already exists - UPDATE the record
                $result = $this->user_model->edit_user($data, $existing_user['id']);
                if ($result) {
                    $record_status['status'] = 'SUCCESS (UPDATED)';
                    $record_status['message'] = 'User updated successfully';
                    $import_results['success']++;
                } else {
                    $record_status['status'] = 'FAILED';
                    $record_status['message'] = 'Failed to update user in database';
                    $import_results['failed']++;
                }
            } else {
                // New user - CREATE the record
                $data['password']   = $password;
                $data['auty_key']   = (new GoogleAuthenticator())->createSecret();
                $data['created_at'] = date('Y-m-d H:i:s');

                $result = $this->user_model->add_user($data);
                if ($result) {
                    $record_status['status'] = 'SUCCESS (NEW)';
                    $record_status['message'] = 'User created successfully (ID: ' . $result . ')';
                    $import_results['success']++;
                } else {
                    $record_status['status'] = 'FAILED';
                    $record_status['message'] = 'Failed to add user to database';
                    $import_results['failed']++;
                }
            }
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
            
            // Check if it's a duplicate key error
            if (strpos($error_msg, 'Duplicate entry') !== false || strpos($error_msg, 'fldUserName') !== false) {
                $record_status['status'] = 'SKIPPED';
                $record_status['message'] = 'Username duplicate in database - already exists';
                $import_results['skipped']++;
            } else {
                $record_status['status'] = 'FAILED';
                $record_status['message'] = 'Exception: ' . $error_msg;
                $import_results['failed']++;
            }
        }
        
        $import_results['records'][] = $record_status;
    }

    fclose($file);
    
    // Invalidate user caches after bulk import
    $this->data_cache->invalidate_users();
    
    // Store results in session and redirect to results page
    $this->session->set_userdata('import_results', $import_results);
    redirect(base_url('admin/users/import_results'));
    }
    
    public function import_results()
    {
        $import_results = $this->session->userdata('import_results');
        
        if (!$import_results) {
            $this->session->set_flashdata('error', 'No import results found.');
            redirect(base_url('admin/users'));
            return;
        }
        
        $data['import_results'] = $import_results;
        $data['title'] = 'Import Results';
        $data['view'] = 'admin/users/import_results';
        $data['page_js'] = array();
        
        $this->load->view('layout', $data);
        
        // Clear the session data after displaying
        $this->session->unset_userdata('import_results');
    }
}


?>
