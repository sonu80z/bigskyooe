<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Order extends MU_Controller{

    public function __construct()
    {
        parent::__construct();
        // Load only essential models in constructor
        $this->load->model('admin/orders_model', 'orders_model');
        $this->load->model('admin/order_history_model', 'order_history_model');
        $this->load->model('admin/user_model', 'user_model');
        $this->load->library('data_cache');
        $this->load->library('datatable');
    }
    
    /**
     * Lazy-load models only when needed
     */
    private function load_form_models()
    {
        $this->load->model('admin/facility_model', 'facility_model');
        $this->load->model('admin/procedure_model', 'procedure_model');
        $this->load->model('admin/lists_model', 'lists_model');
        $this->load->model('admin/state_model', 'state_model');
        $this->load->model('admin/insurance_type_model', 'insurance_type_model');
        $this->load->model('admin/insurance_company_model', 'insurance_company_model');
        $this->load->model('admin/icd10_model', 'icd10_model');
        $this->load->model('admin/payer_model', 'payer_model');
        $this->load->model('admin/division_model', 'division_model');
    }

    // -------------------------
    // Helper: generate accession
    // -------------------------
    private function generate_accession_number($prefix = 'FR')
    {
        // Create a short unique alphanumeric string (7 chars) and prefix it
        $unique = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 7));
        return $prefix . $unique;
    }

    private function format_name_caret($first, $last)
    {
        $first = trim((string)$first);
        $last = trim((string)$last);
        if ($first === '' && $last === '') {
            return '';
        }
        return $last . '^' . $first;
    }

    private function format_name_caret_from_string($name)
    {
        $name = trim((string)$name);
        if ($name === '') {
            return '';
        }
        if (strpos($name, ',') !== false) {
            $parts = array_map('trim', explode(',', $name, 2));
            $last = $parts[0];
            $first = isset($parts[1]) ? $parts[1] : '';
            return $this->format_name_caret($first, $last);
        }
        $parts = preg_split('/\s+/', $name);
        if (count($parts) === 1) {
            return $this->format_name_caret('', $parts[0]);
        }
        $last = array_pop($parts);
        $first = implode(' ', $parts);
        return $this->format_name_caret($first, $last);
    }

    private function normalize_sex($sex)
    {
        $sex = trim((string)$sex);
        if ($sex === '') {
            return '';
        }
        $lower = strtolower($sex);
        if ($lower[0] === 'f') {
            return 'F';
        }
        if ($lower[0] === 'm') {
            return 'M';
        }
        return 'O';
    }

    private function decode_order_attachments($attachment_field)
    {
        if (empty($attachment_field)) {
            return array();
        }
        $decoded = json_decode($attachment_field, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array(
            array(
                'type' => 'Legacy Attachment',
                'file' => (string)$attachment_field,
                'original_name' => (string)$attachment_field,
                'uploaded_at' => ''
            )
        );
    }

    /**
     * Format patient name as "Last Name, First Name Middle Initial"
     */
    private function format_patient_name($lastname, $firstname, $middlename = '')
    {
        $lastname = trim((string)$lastname);
        $firstname = trim((string)$firstname);
        $middlename = trim((string)$middlename);
        
        $name = $lastname;
        
        if (!empty($firstname)) {
            $name .= ', ' . $firstname;
        }
        
        if (!empty($middlename)) {
            // Get first letter of middle name
            $middle_initial = strtoupper(substr($middlename, 0, 1));
            $name .= ' ' . $middle_initial;
        }
        
        return htmlspecialchars($name);
    }

    private function upload_order_attachments($types, $files)
    {
        $result = array(
            'attachments' => array(),
            'error' => ''
        );

        if (empty($files) || !isset($files['name']) || !is_array($files['name'])) {
            return $result;
        }

        $upload_path = './uploads/order_attachments/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $name = isset($files['name'][$i]) ? $files['name'][$i] : '';
            if ($name === '') {
                continue;
            }

            $type = isset($types[$i]) ? trim((string)$types[$i]) : '';
            if ($type === '') {
                $result['error'] = 'Please select a document type for each uploaded file.';
                return $result;
            }

            $file_field = 'order_attachment_file';
            $_FILES[$file_field] = array(
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            );

            $config = array(
                'upload_path' => $upload_path,
                'allowed_types' => 'pdf',
                'max_size' => 10240,
                'file_name' => 'order_' . time() . '_' . uniqid() . '_' . $i
            );

            $this->load->library('upload');
            $this->upload->initialize($config, true);

            if ($this->upload->do_upload($file_field)) {
                $upload_data = $this->upload->data();
                $result['attachments'][] = array(
                    'type' => $type,
                    'file' => $upload_data['file_name'],
                    'original_name' => $name,
                    'uploaded_at' => date('Y-m-d H:i:s')
                );
            } else {
                $result['error'] = $this->upload->display_errors('', '');
                return $result;
            }
        }

        return $result;
    }

    // -------------------------
    // Helper: send SMS (Clickatell)
    // -------------------------
    private function send_sms_clickatell($to, $msg)
    {
        // Normalize phone number: keep digits only
        $to = preg_replace('/\D+/', '', $to);
        if (empty($to) || empty($msg)) {
            log_message('error', "Clickatell SMS error: missing to or msg (to: {$to})");
            return false;
        }

        // Clickatell HTTP API endpoint
        $base_url = "https://api.clickatell.com/http/sendmsg";

        // API credentials -- keep these secure (consider moving to config)
        $params = [
            'user'    => 'fastrad',
            'password'=> 'F@stR@d2024!',
            'api_id'  => '3291606',
            'to'      => '1'.$to,
            'text'    => $msg,
            'from'    => '12197060361',
            'mo'      => 1
        ];

        $url = $base_url . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // TEMPORARY: disable SSL verification to avoid "unable to get local issuer certificate"
        // For production, install CA bundle on server and set this true.
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            log_message('error', "Clickatell SMS error: {$err}");
            return false;
        }

        log_message('info', "Clickatell SMS response (HTTP {$http_code}): {$response}");
        // You can parse response content here to decide true/false depending on Clickatell output.
        return true;
    }

    // -------------------------
    // rest of controller functions (index, add, create, edit, update, view ...) remain unchanged
    // I'll keep them as before (omitted for brevity in this snippet)
    // -------------------------

    public function index()
    {
        // Prevent browser caching to ensure flashdata works correctly
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        
        // Orders now loaded via AJAX (ajax_orders endpoint) for faster page load
        // Use cached reference data instead of fresh DB queries
        $data['facilities'] = $this->data_cache->get_facilities();
        $data['technologist'] = $this->data_cache->get_technologists();
        $data['procedures'] = $this->data_cache->get_procedures();
        $data['divisions'] = $this->data_cache->get_divisions(0);
        $data['states'] = $this->data_cache->get_active_states();
        $data['title'] = 'Order List';
        $data['view'] = 'admin/orders/list';
        $data['page_js'] = array('order.js', 'system.js');
        $data['page_plugins'] = array('datetimepicker');
        $this->load->view('layout', $data);
    }

    /**
     * AJAX endpoint for orders DataTable - returns JSON
     */
    public function ajax_orders()
    {
        $user_id = $this->session->userdata('did');
        $user_info = !empty($user_id) ? $this->user_model->get_user_info($user_id) : null;
        $is_technologist = (!empty($user_info) && isset($user_info['role']) && (int)$user_info['role'] === 8);
        $tech_id = $is_technologist ? $user_id : null;

        // Read all advanced search params
        $time = $this->input->get('adv_time', true) ?: 'all';
        $order_types_raw = $this->input->get('adv_order_types', true) ?: '';
        $date_from_raw = trim($this->input->get('adv_date_from', true));
        $date_to_raw = trim($this->input->get('adv_date_to', true));

        // Convert mm/dd/yyyy to Y-m-d for DB query
        $date_from = '';
        $date_to = '';
        if (!empty($date_from_raw)) {
            $dt = DateTime::createFromFormat('m/d/Y', $date_from_raw);
            if ($dt) $date_from = $dt->format('Y-m-d');
        }
        if (!empty($date_to_raw)) {
            $dt = DateTime::createFromFormat('m/d/Y', $date_to_raw);
            if ($dt) $date_to = $dt->format('Y-m-d');
        }

        $filters = array(
            'search_name'   => trim($this->input->get('adv_search_name', true)),
            'patient_id'    => trim($this->input->get('adv_patient_id', true)),
            'search_dob'    => trim($this->input->get('adv_search_dob', true)),
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'date_type'     => $this->input->get('adv_date_type', true) ?: 'order',
            'facility_id'   => $this->input->get('adv_facility', true),
            'division'      => $this->input->get('adv_division', true),
            'order_types'   => array_filter(explode(',', $order_types_raw)),
            'state'         => $this->input->get('adv_state', true),
            'modality'      => $this->input->get('adv_modality', true),
            'status'        => $this->input->get('adv_status', true),
            'today_only'    => ($time === 'today'),
        );

        $orders = $this->orders_model->get_orders_filtered($filters, 'created_at desc', $tech_id);

        // Batch-fetch latest history timestamps for "Time in Status" column
        $order_ids = array_column($orders, 'id');
        $history_ts = $this->orders_model->get_latest_history_timestamps($order_ids);

        // Build lookup arrays
        $facilities_lookup = array();
        foreach ($this->data_cache->get_facilities() as $facility) {
            $facilities_lookup[$facility['id']] = $facility['facility_name'];
        }
        $procedure_lookup = array();
        foreach ($this->data_cache->get_procedures() as $proc) {
            $procedure_lookup[$proc['id']] = $proc['cpt_code'] . ' - ' . $proc['description'];
        }

        $rows = array();
        $base_url = base_url();
        $current_time = time();

        foreach ($orders as $row) {
            $order_status = intval($row['status']);
            $order_status_info = get_order_status($order_status);
            $date = !empty($row['created_at']) ? date('m/d/Y', strtotime($row['created_at'])) : '';
            $schedule_date = !empty($row['date_of_service']) ? date('m/d/Y', strtotime($row['date_of_service'])) : '';
            $facility_name = !empty($row['orderingentity']) && isset($facilities_lookup[$row['orderingentity']])
                ? $facilities_lookup[$row['orderingentity']] : '';
            $tech_name = !empty($row['dispatch_technologist']) ? $row['dispatch_technologist'] : '';
            $order_id = $row['id'];
            $is_canceled = intval($row['is_canceled']) === 1;

            // Time in Status calculation
            $time_display = '';
            $time_html = '';
            if (isset($history_ts[$order_id])) {
                // DB stores UTC; parse as UTC so diff vs time() is correct
                $tz_utc = new DateTimeZone('UTC');
                $tz_mst = new DateTimeZone('America/Denver');
                $changed_dt = new DateTime($history_ts[$order_id], $tz_utc);
                $changed_time = $changed_dt->getTimestamp();
                $minutes = round(($current_time - $changed_time) / 60);
                if ($minutes < 60) {
                    $elapsed = $minutes . ' min';
                } elseif ($minutes < 1440) {
                    $elapsed = round($minutes / 60, 1) . ' hrs';
                } else {
                    $elapsed = round($minutes / 1440, 1) . ' days';
                }
                $changed_dt->setTimezone($tz_mst);
                $status_datetime = $changed_dt->format('m/d/Y h:i A');
                $time_display =  $elapsed ;
                if ($order_status == 20) {
                    if ($minutes > 120) {
                        $time_html = '<span style="color:red;font-weight:bold;">' . $time_display . '</span>';
                    } elseif ($minutes > 60) {
                        $time_html = '<span style="color:orange;font-weight:bold;">' . $time_display . '</span>';
                    } else {
                        $time_html = $time_display;
                    }
                } else {
                    $time_html = $time_display;
                }
                
            }

            // Decode procedure list
            $procedure_display = '';
            if (!empty($row['procedurelist'])) {
                $proc_ids = json_decode($row['procedurelist'], true);
                if (is_array($proc_ids) && !empty($proc_ids)) {
                    $names = array();
                    foreach ($proc_ids as $pid) {
                        if (!empty($pid)) {
                            $names[] = isset($procedure_lookup[$pid]) ? $procedure_lookup[$pid] : 'ID: ' . $pid;
                        }
                    }
                    $procedure_display = implode(', ', $names);
                } else {
                    $procedure_display = $row['procedurelist'];
                }
            }

            // Build action buttons
            if ($is_canceled) {
                $actions = '<a title="View" class="btn btn-xs btn-primary" href="' . $base_url . 'admin/order/view/' . $order_id . '"><i class="fa fa-eye"></i></a>';
            } else {
                $att_button = '';
                if (!empty($row['attachment'])) {
                    $decoded = json_decode($row['attachment'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $first_file = isset($decoded[0]['file']) ? $decoded[0]['file'] : '';
                        if (count($decoded) === 1 && $first_file !== '') {
                            $att_button = '<a title="View PDF" class="btn btn-xs btn-primary" href="' . $base_url . 'uploads/order_attachments/' . htmlspecialchars($first_file) . '" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
                        } else {
                            $att_button = '<a title="View Attachments" class="btn btn-xs btn-primary" href="' . $base_url . 'admin/order/view/' . $order_id . '"><i class="fa fa-paperclip"></i></a>';
                        }
                    } else {
                        $att_button = '<a title="View PDF" class="btn btn-xs btn-primary" href="' . $base_url . 'uploads/order_attachments/' . htmlspecialchars($row['attachment']) . '" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
                    }
                }
                $actions =
                    '<a title="Edit" class="btn btn-xs btn-primary" href="' . $base_url . 'admin/order/edit/' . $order_id . '"><i class="fa fa-pencil-square-o"></i></a>' .
                    '<a title="View" class="btn btn-xs btn-primary" href="' . $base_url . 'admin/order/view/' . $order_id . '"><i class="fa fa-eye"></i></a>' .
                    $att_button .
                    '<a title="Submit for Reading" class="submit-reading-btn btn btn-xs btn-warning" data-id="' . $order_id . '" href="javascript:void(0);"><i class="fa fa-file-text"></i></a>' .
                    '<a title="View Report" class="btn btn-xs btn-warning" href="' . $base_url . 'admin/order/view_report/' . $order_id . '" target="_blank"><i class="fa fa-file-text-o"></i></a>' .
                    '<a title="View Timeline" class="view-timeline-btn btn btn-xs btn-info" data-id="' . $order_id . '" href="javascript:void(0);"><i class="fa fa-history"></i></a>' .
                    '<a title="Workflow" class="btn btn-xs btn-primary" href="http://18.221.194.47:3000/" target="_blank"><i class="fa fa-gears"></i></a>' .
                    '<a title="Dispatch" class="dispatch-btn btn btn-xs btn-primary" data-id="' . $order_id . '" data-order-id="' . $order_id . '"><i class="fa fa-car"></i></a>' .
                    '<a title="Mark Completed" class="mark-btn btn btn-xs btn-primary" data-id="' . $order_id . '"><i class="fa fa-check"></i></a>' .
                    '<a title="Send to HL7" class="btn btn-xs btn-primary" href="javascript:void(0);"><i class="fa fa-send"></i></a>' .
                    '<a title="Add Note" class="note-btn btn btn-xs btn-primary" data-id="' . $order_id . '"><i class="fa fa-file-o"></i></a>' .
                    '<a title="Cancel" class="cancel-btn btn btn-xs btn-primary" data-id="' . $order_id . '"><i class="fa fa-times"></i></a>' .
                    '<a title="Print" class="btn btn-xs btn-primary" href="' . $base_url . 'admin/dashboard/print/' . $order_id . '"><i class="fa fa-print"></i></a>';
            }

            $rows[] = array(
                'DT_RowAttr' => array(
                    'style' => 'background:' . $order_status_info['color'],
                    'did' => $order_id,
                    'data-kind' => intval($row['kind']),
                    'data-status' => $order_status
                ),
                '<input type="checkbox" class="order-checkbox" value="' . $order_id . '" />',
                htmlspecialchars($order_status_info['text']),
                $time_html,
                $date,
                $schedule_date,
                htmlspecialchars($row['patientmr']),
                $this->format_patient_name($row['lastname'], $row['firstname'], isset($row['middlename']) ? $row['middlename'] : ''),
                htmlspecialchars($facility_name),
                htmlspecialchars($tech_name),
                htmlspecialchars($procedure_display),
                $actions,
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('data' => $rows)));
    }

    // ... other methods unchanged (manage, add, create, get_search_result, edit, update, view) ...
    // For full file these methods remain exactly as in your original; only dispatch() is changed below.

        public function manage()
        {
            $data['title'] = 'Manage order screens';
            $data['view'] = 'admin/orders/manage';
            $data['page_js'] = array();
            $this->load->view('layout', $data);
        }

        public function add()
        {
            $this->load_form_models();
            $data['title'] = 'Add Order';
            $data['facilities'] = $this->data_cache->get_facilities();
            $data['orderingphysician'] = $this->data_cache->get_ordering_physicians();
            $data['procedures'] = $this->data_cache->get_procedures();
            $data['lists'] = $this->data_cache->get_lists_by_name("relationship");
            $data['reason_photoble'] = $this->data_cache->get_lists_by_name("R4P");
            $data['states'] = $this->data_cache->get_states();
            $data['insurance_types'] = $this->data_cache->get_insurance_types();
            $data['insurance_companies'] = $this->data_cache->get_insurance_companies();
            $data['technologist'] = $this->data_cache->get_technologists();
            
            // ICD10 codes are now loaded via AJAX autocomplete - don't load full table
            $data['icd10_codes'] = array();
            
            // Load payers for insurance dropdown
            $data['payers'] = $this->data_cache->get_payers();

            $data['divisions'] = $this->data_cache->get_divisions(0);
            
            // Pass the 'from' parameter to the view
            $data['from'] = $this->input->get('from');

            $prefill = array();
            $facility_id = $this->input->get('facility_id');
            if (!empty($facility_id)) {
                $prefill['orderingentity'] = $facility_id;
            }

            $source_order_id = $this->input->get('source_order_id');
            if (!empty($source_order_id)) {
                $source_order = $this->orders_model->get_order_detail($source_order_id);
                if (!empty($source_order)) {
                    $prefill['lastname'] = $source_order['lastname'];
                    $prefill['firstname'] = $source_order['firstname'];
                    $prefill['middlename'] = $source_order['middlename'];
                    $prefill['suffixname'] = $source_order['suffixname'];
                    $prefill['patientmr'] = $source_order['patientmr'];
                    $prefill['patientssn'] = $source_order['patientssn'];
                    $prefill['gender'] = $source_order['gender'];
                    $prefill['patientaddress'] = isset($source_order['patientaddress']) ? $source_order['patientaddress'] : '';
                    $prefill['patientcity'] = isset($source_order['patientcity']) ? $source_order['patientcity'] : '';
                    $prefill['patientstate'] = isset($source_order['patientstate']) ? $source_order['patientstate'] : '';
                    $prefill['patientzip'] = isset($source_order['patientzip']) ? $source_order['patientzip'] : '';
                    $prefill['patientphone'] = isset($source_order['patientphone']) ? $source_order['patientphone'] : '';

                    if (!empty($source_order['dob'])) {
                        $dtime = DateTime::createFromFormat("Y-m-d H:i:s", $source_order['dob']." 00:00:00");
                        if ($dtime) {
                            $prefill['dob'] = date("m/d/Y", $dtime->getTimestamp());
                        }
                    }

                    $prefill['ioa'] = $source_order['ioa'];
                    $prefill['insurancetype'] = $source_order['insurancetype'];
                    $prefill['medicare'] = $source_order['medicare'];
                    $prefill['medicaid'] = $source_order['medicaid'];
                    $prefill['state'] = $source_order['state'];
                    $prefill['insurancecompany'] = $source_order['insurancecompany'];
                    $prefill['policy'] = $source_order['policy'];
                    $prefill['group'] = $source_order['group'];
                    $prefill['contract'] = $source_order['contract'];
                    $prefill['insurancecompany2'] = $source_order['insurancecompany2'];
                    $prefill['policy2'] = $source_order['policy2'];
                    $prefill['group2'] = $source_order['group2'];
                    $prefill['contract2'] = $source_order['contract2'];
                    $prefill['insurancecompany3'] = $source_order['insurancecompany3'];
                    $prefill['policy3'] = $source_order['policy3'];
                    $prefill['group3'] = $source_order['group3'];
                    $prefill['contract3'] = $source_order['contract3'];

                    $prefill['responsible_party'] = $source_order['responsible_party'];
                    $prefill['relationship'] = $source_order['relationship'];
                    $prefill['address1'] = $source_order['address1'];
                    $prefill['address2'] = $source_order['address2'];
                    $prefill['partyphone'] = $source_order['partyphone'];
                    $prefill['partycity'] = $source_order['partycity'];
                    $prefill['partystate'] = $source_order['partystate'];
                    $prefill['partyzip'] = $source_order['partyzip'];
                }
            }

            $data['prefill'] = $prefill;

            $data['view'] = 'admin/orders/detail';
            $data['page_js'] = array('order.js');
            $data['page_plugins'] = array('bootstrap-select', 'datetimepicker');
            $this->load->view('layout', $data);
        }

        public function create()
        {
            if ($this->input->method() !== 'post') {
                redirect('admin/order/add');
                return;
            }
            $this->load_form_models();
            $this->load->library('GoogleAuthenticator');
            $session_data = $this->session->get_userdata('did');
            $user_id = $session_data['did'];

            $gaobj = new GoogleAuthenticator();
            $secret = $gaobj->createSecret();
            $ao_dom = $this->input->post('ao_dom');
            $dtime = DateTime::createFromFormat("m/d/Y H:i:s", $ao_dom." 00:00:00");
            
            // Validate DOB
            if(!$dtime){
                $this->session->set_flashdata('error_msg', 'Invalid Date of Birth format. Use MM/DD/YYYY format.');
                redirect('admin/order/add');
                return;
            }
            
            $timestamp = $dtime->getTimestamp();
            $min_date = DateTime::createFromFormat("Y-m-d", "1753-01-01")->getTimestamp();
            $max_date = time(); // Current date/time
            
            if($timestamp < $min_date || $timestamp > $max_date){
                $this->session->set_flashdata('error_msg', 'Date of Birth must be between 01/01/1753 and current date.');
                redirect('admin/order/add');
                return;
            }
            
            $dob = date("Y-m-d", $timestamp);
            
            // Process Date of Service
            $ao_date_of_service = $this->input->post('ao_date_of_service');
            $date_of_service = null;
            if(!empty($ao_date_of_service)){
                $dtime_dos = DateTime::createFromFormat("m/d/Y", $ao_date_of_service);
                if($dtime_dos){
                    $date_of_service = $dtime_dos->format('Y-m-d');
                }
            }
            
            $data = array(
                'order_creator'=>$user_id,
                'order_editor'=>$user_id,
                'kind'=>$this->input->post('ao_kind'),
                'middlename' => $this->input->post('ao_middle_name'),
                'firstname' => $this->input->post('ao_first_name'),
                'lastname' => $this->input->post('ao_last_name'),
                'suffixname' => $this->input->post('ao_suffix_name'),
                'patientmr' => $this->input->post('ao_patient_mr'),
                'dob' => $dob,
                'patientssn' => $this->input->post('ao_patient_ssn'),
                'gender' => $this->input->post('ao_sex'),
                'patientaddress' => $this->input->post('ao_patient_address'),
                'patientcity' => $this->input->post('ao_patient_city'),
                'patientstate' => $this->input->post('ao_patient_state'),
                'patientzip' => $this->input->post('ao_patient_zip'),
                'patientphone' => $this->input->post('ao_patient_phone'),
                'orderingentity' => $this->input->post('ao_ordering_facility'),
                'orderedby' => $this->input->post('ao_ordered_by'),
                'asr' => $this->input->post('ao_ordered_asr'),
                'orderedstation' => $this->input->post('ao_ordered_station'),
                'orderedroom' => $this->input->post('ao_ordered_room'),
                'orderedcity' => $this->input->post('ao_ordered_city'),
                'orderedaddress' => $this->input->post('ao_ordered_address'),
                'orderedstate' => $this->input->post('ao_ordered_state'),
                'orderedzip' => $this->input->post('ao_ordered_zip'),
                'orderedphone' => $this->input->post('ao_ordered_phone'),
                'orderedfax' => $this->input->post('ao_ordered_fax'),
                'servicefacility' => $this->input->post('ao_service_facility'),
                'servicestatus' => $this->input->post('ao_service_status'),
                'servicestation' => $this->input->post('ao_service_station'),
                'serviceroom' => $this->input->post('ao_service_room'),
                'serviceaddress' => $this->input->post('ao_service_address'),
                'servicecity' => $this->input->post('ao_service_city'),
                'servicezip' => $this->input->post('ao_service_zip'),
                'servicephone' => $this->input->post('ao_service_phone'),
                'servicefax' => $this->input->post('ao_service_fax'),
                'servicedr' => $this->input->post('ao_service_dr'),
                'drphone' => $this->input->post('ao_dr_phone'),
                'drfax' => $this->input->post('ao_dr_fax'),
                'drnpi' => $this->input->post('ao_dr_NPI'),
                'ptradio' => $this->input->post('ao_pt_radio'),
                'procedurelist' => json_encode($this->input->post('ao_procedure_list')),
                'plrn' => json_encode($this->input->post('ao_plrn')),
                'symptom1' => json_encode($this->input->post('ao_symptom_1')),
                'symptom2' => json_encode($this->input->post('ao_symptom_2')),
                'symptom3' => json_encode($this->input->post('ao_symptom_3')),
                'exam' => $this->input->post('ao_reason_for_exam'),
                'history' => $this->input->post('ao_history'),
                'reason' => $this->input->post('ao_portable_reason'),
                'ioa' => $this->input->post('ao_ioa'),
                'insurance' => $this->input->post('ao_bill_insurance'),
                'facility' => $this->input->post('ao_bill_facility'),
                'medicare' => $this->input->post('ao_medicare'),
                'medicaid' => $this->input->post('ao_medicaid'),
                'state' => $this->input->post('ao_state'),
                'insurancecompany' => $this->input->post('ao_company'),
                'policy' => $this->input->post('ao_policy'),
                'group' => $this->input->post('ao_group'),
                'contract' => $this->input->post('ao_contract'),
                'insurancecompany2' => $this->input->post('ao_company2'),
                'policy2' => $this->input->post('ao_policy2'),
                'group2' => $this->input->post('ao_group2'),
                'contract2' => $this->input->post('ao_contract2'),
                'insurancecompany3' => $this->input->post('ao_company3'),
                'policy3' => $this->input->post('ao_policy3'),
                'group3' => $this->input->post('ao_group3'),
                'contract3' => $this->input->post('ao_contract3'),
                'party' => $this->input->post('ao_party'),
                'insurancetype' => $this->input->post('ao_insurance_type'),
                'responsible_party' => $this->input->post('responsible_party'),
                'relationship' => $this->input->post('ao_relationship'),
                'address1' => $this->input->post('address1'),
                'address2' => $this->input->post('address2'),
                'partyphone' => $this->input->post('party_phone'),
                'partycity' => $this->input->post('party_city'),
                'partystate' => $this->input->post('party_state'),
                'partyzip' => $this->input->post('party_zip'),
                'date_of_service' => $date_of_service,
//                'auty_key' => $secret,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'order_revision'=>'',
                'electronic_signature' => $this->input->post('ao_electronic_signature'),
                'status' => 5
            );

            // Set status based on priority (undispatched by default)
            $priority = strtoupper(trim($data['asr']));
            if($priority === 'STAT'){
                $data['status'] = 1; // N-STAT
            }elseif($priority === 'ASAP'){
                $data['status'] = 3; // N-ASAP
            }else{
                $data['status'] = 5; // N-Routine
            }

            // Auto-generate Patient MR if blank or 'na'
            if(empty($data['patientmr']) || strtolower($data['patientmr']) == 'na'){
                $data['patientmr'] = generate_patient_mr();
            }
            
            $attachments_result = $this->upload_order_attachments(
                $this->input->post('order_attachment_types'),
                isset($_FILES['order_attachments']) ? $_FILES['order_attachments'] : array()
            );
            if (!empty($attachments_result['error'])) {
                $this->session->set_flashdata('error_msg', $attachments_result['error']);
                redirect('admin/order/add');
                return;
            }
            if (!empty($attachments_result['attachments'])) {
                $data['attachment'] = json_encode($attachments_result['attachments']);
            }
            
            // Handle inline dispatch if filled
            $dispatch_datetime_inline = trim($this->input->post('dispatch_datetime_inline'));
            $dispatch_technologist_inline = trim($this->input->post('dispatch_technologist_inline'));
            
            // Only process dispatch if BOTH fields are filled (trim whitespace first)
            if(!empty($dispatch_datetime_inline) && !empty($dispatch_technologist_inline)) {
                // Convert datetime to timestamp
                $dtime_dispatch = DateTime::createFromFormat("m/d/Y H:i", $dispatch_datetime_inline);
                if(!$dtime_dispatch) {
                    $this->session->set_flashdata('error_msg', 'Invalid Dispatch Date/Time format. Use MM/DD/YYYY HH:MM format.');
                    redirect('admin/order/add');
                    return;
                }
                
                $dispatch_timestamp = $dtime_dispatch->getTimestamp();
                
                // Get technologist info
                $technologist_info = $this->user_model->get_user_info($dispatch_technologist_inline);
                if(empty($technologist_info)) {
                    $this->session->set_flashdata('error_msg', 'Invalid Technologist selected.');
                    redirect('admin/order/add');
                    return;
                }
                
                // Add dispatch data to main data array
                $data['dispatch_datetime'] = $dispatch_timestamp;
                $data['dispatch_technologist_id'] = $dispatch_technologist_inline;
                $data['dispatch_technologist'] = $technologist_info['lastname'].' '.$technologist_info['firstname'];
                $data['dispatch_submit_user_id'] = $user_id;
                $data['dispatch_submit_user_name'] = $session_data['username'];
                // Update status to dispatched variant (D-* with same priority)
                if($data['status'] === 1) {
                    $data['status'] = 10; // D-STAT (Dispatched with STAT priority)
                } elseif($data['status'] === 3) {
                    $data['status'] = 11; // D-ASAP (Dispatched with ASAP priority)
                } else {
                    $data['status'] = 12; // D-Routine (Dispatched with Routine priority)
                }
            } else {
                // Set status to Undispatched if no dispatch info provided
                $data['status'] = 7;
                
                // Reset dispatch fields to null if not both filled
                $data['dispatch_datetime'] = null;
                $data['dispatch_technologist_id'] = null;
                $data['dispatch_technologist'] = null;
                $data['dispatch_submit_user_id'] = null;
                $data['dispatch_submit_user_name'] = null;
            }
            
            $data = $this->security->xss_clean($data);
            $result = $this->orders_model->add_order($data);
            if ($result) {
                $order_id = $result;
                $order_detail = $this->orders_model->get_order_detail($order_id);

                // Generate worklist text files and send SMS if inline dispatch was provided
                if (!empty($dispatch_datetime_inline) && !empty($dispatch_technologist_inline)) {
                    // Generate accession number if missing (required for worklist files)
                    if (empty($order_detail['fldacsno1'])) {
                        // Try detect prefix: if orderingentity resolves to a facility name containing 'fastrad' use FR else BS
                        $acs_prefix = 'FR';
                        if (!empty($order_detail['orderingentity'])) {
                            $fac = null;
                            if (is_numeric($order_detail['orderingentity'])) {
                                if (method_exists($this->facility_model, 'get_facility')) {
                                    $fac = $this->facility_model->get_facility($order_detail['orderingentity']);
                                } elseif (method_exists($this->facility_model, 'get_facility_by_id')) {
                                    $fac = $this->facility_model->get_facility_by_id($order_detail['orderingentity']);
                                } elseif (method_exists($this->facility_model, 'get_facility_via_id')) {
                                    $fac = $this->facility_model->get_facility_via_id($order_detail['orderingentity']);
                                }
                                if (!empty($fac) && is_array($fac)) {
                                    $fac_name = isset($fac['facility_name']) ? $fac['facility_name'] : (isset($fac['name']) ? $fac['name'] : '');
                                    if (stripos($fac_name, 'fastrad') !== false) $acs_prefix = 'BS';
                                }
                            }
                        }
                        $accession_number = $this->generate_accession_number($acs_prefix);
                        $this->orders_model->update_order(['fldacsno1' => $accession_number], ['id' => $order_id]);
                        $order_detail['fldacsno1'] = $accession_number;
                    }
                    
                    // Generate patient MR if missing or 'na'
                    if (empty($order_detail['patientmr']) || strtolower($order_detail['patientmr']) == 'na') {
                        $patient_mr = generate_patient_mr();
                        $this->orders_model->update_order(['patientmr' => $patient_mr], ['id' => $order_id]);
                        $order_detail['patientmr'] = $patient_mr;
                    }
                    
                    $this->generate_worklist_files($order_detail, $dispatch_timestamp);
                    $this->send_dispatch_sms($order_detail, $technologist_info);
                }

                $order_history_data = array(
                    'order_id'=>$order_id,
                    'action'=>'create',
                    'old_data'=>'',
                    'new_data'=>serialize($order_detail),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'user_id'=>$user_id,
                    'user_name'=>$session_data['username']
                );
                $this->order_history_model->add_order_history($order_history_data);
                
                // Log separate dispatch action if inline dispatch was used
                if (!empty($dispatch_datetime_inline) && !empty($dispatch_technologist_inline)) {
                    $dispatch_history_data = array(
                        'order_id'=>$order_id,
                        'action'=>'dispatched',
                        'old_data'=>serialize($order_detail),
                        'new_data'=>serialize($order_detail),
                        'created_at'=>date('Y-m-d H:i:s'),
                        'user_id'=>$user_id,
                        'user_name'=>$session_data['username']
                    );
                    $this->order_history_model->add_order_history($dispatch_history_data);
                }
                
                $this->session->set_flashdata('success_msg', 'Order has been added successfully!');

                $submit_action = $this->input->post('submit_action');
                if ($submit_action === 'new_facility') {
                    $facility_id = $this->input->post('ao_ordering_facility');
                    $facility_param = !empty($facility_id) ? '?facility_id=' . urlencode($facility_id) : '';
                    redirect(base_url('admin/order/add' . $facility_param));
                    return;
                }
                if ($submit_action === 'same_patient') {
                    redirect(base_url('admin/order/add?source_order_id=' . urlencode($order_id)));
                    return;
                }
                
                // Redirect based on where user came from
                $from = $this->input->post('redirect_from');
                if($from === 'dashboard') {
                    redirect(base_url('admin/dashboard'));
                } else {
                    redirect(base_url('admin/order'));
                }
            }
        }

        public function get_search_result(){
            $filters = array(
                'lastname' => $this->input->post('lastname', TRUE),
                'dob' => $this->input->post('dob', TRUE),
                'patientmr' => $this->input->post('patientmr', TRUE)
            );
            $res = $this->orders_model->get_search_result($filters);

            $res_data = array(
                'status' => 0
            );

            if( $res ) {
                $res_data = array(
                    'status' => 1,
                    'list'=>$res,
                );
            }

            res_write($res_data);
        }

        public function edit($id)
        {
            $this->load_form_models();
            $data['title'] = 'Edit Order';
            $data['facilities'] = $this->data_cache->get_facilities();
            $data['orderingphysician'] = $this->data_cache->get_ordering_physicians();
            $data['procedures'] = $this->data_cache->get_procedures();
            $data['lists'] = $this->data_cache->get_lists_by_name("relationship");
            $data['reason_photoble'] = $this->data_cache->get_lists_by_name("R4P");
            $data['states'] = $this->data_cache->get_states();
            $data['insurance_types'] = $this->data_cache->get_insurance_types();
            $data['insurance_companies'] = $this->data_cache->get_insurance_companies();
            $data['divisions'] = $this->data_cache->get_divisions(0);
            $data['technologist'] = $this->data_cache->get_technologists();
            
            // ICD10 codes loaded via AJAX autocomplete - don't load full table
            $data['icd10_codes'] = array();
            
            // Load payers for insurance dropdown
            $data['payers'] = $this->data_cache->get_payers();

            $order_detail = $this->orders_model->get_order_detail($id);
            if(empty($order_detail)){
                $this->session->set_flashdata('msg', 'Order does not exist!');
                redirect(base_url('admin/order'));
                return;
            }
            
            // Load creator and editor user information
            if(!empty($order_detail['creator'])){
                $creator = $this->user_model->get_user_info($order_detail['creator']);
                if(!empty($creator)){
                    $order_detail['creator_name'] = trim($creator['firstname'] . ' ' . $creator['lastname']);
                }
            }
            if(!empty($order_detail['order_editor'])){
                $editor = $this->user_model->get_user_info($order_detail['order_editor']);
                if(!empty($editor)){
                    $order_detail['editor_name'] = trim($editor['firstname'] . ' ' . $editor['lastname']);
                }
            }
            
            $data['order'] = $order_detail;
            $data['view'] = 'admin/orders/detail';
            $data['page_js'] = array('order.js');
            $data['page_plugins'] = array('bootstrap-select', 'datetimepicker');
            $this->load->view('layout', $data);
        }

        public function update($id)
        {
            $this->load_form_models();
            $old_order_detail = $this->orders_model->get_order_detail($id);
            if(empty($old_order_detail)){
                $this->session->set_flashdata('msg', 'Order does not exist!');
                redirect(base_url('admin/order'));
                return;
            }

            $session_data = $this->session->get_userdata('did');
            $user_id = $session_data['did'];

            $this->load->library('GoogleAuthenticator');
            $gaobj = new GoogleAuthenticator();
            $secret = $gaobj->createSecret();
            $ao_dom = $this->input->post('ao_dom');
            $dtime = DateTime::createFromFormat("m/d/Y H:i:s", $ao_dom." 00:00:00");
            
            // Validate DOB
            if(!$dtime){
                $this->session->set_flashdata('error_msg', 'Invalid Date of Birth format. Use MM/DD/YYYY format.');
                redirect('admin/order/edit/'.$id);
                return;
            }
            
            $timestamp = $dtime->getTimestamp();
            $min_date = DateTime::createFromFormat("Y-m-d", "1753-01-01")->getTimestamp();
            $max_date = time(); // Current date/time
            
            if($timestamp < $min_date || $timestamp > $max_date){
                $this->session->set_flashdata('error_msg', 'Date of Birth must be between 01/01/1753 and current date.');
                redirect('admin/order/edit/'.$id);
                return;
            }
            
            $dob = date("Y-m-d", $timestamp);
            
            // Process Date of Service
            $ao_date_of_service = $this->input->post('ao_date_of_service');
            $date_of_service = null;
            if(!empty($ao_date_of_service)){
                $dtime_dos = DateTime::createFromFormat("m/d/Y", $ao_date_of_service);
                if($dtime_dos){
                    $date_of_service = $dtime_dos->format('Y-m-d');
                }
            }
            
            $data = array(
                'order_editor'=>$user_id,
                'kind'=>$this->input->post('ao_kind'),
                'middlename' => $this->input->post('ao_middle_name'),
                'firstname' => $this->input->post('ao_first_name'),
                'lastname' => $this->input->post('ao_last_name'),
                'suffixname' => $this->input->post('ao_suffix_name'),
                'patientmr' => $this->input->post('ao_patient_mr'),
                'dob' => $dob,
                'patientssn' => $this->input->post('ao_patient_ssn'),
                'gender' => $this->input->post('ao_sex'),
                'patientaddress' => $this->input->post('ao_patient_address'),
                'patientcity' => $this->input->post('ao_patient_city'),
                'patientstate' => $this->input->post('ao_patient_state'),
                'patientzip' => $this->input->post('ao_patient_zip'),
                'patientphone' => $this->input->post('ao_patient_phone'),
                'orderingentity' => $this->input->post('ao_ordering_facility'),
                'orderedby' => $this->input->post('ao_ordered_by'),
                'asr' => $this->input->post('ao_ordered_asr'),
                'orderedstation' => $this->input->post('ao_ordered_station'),
                'orderedroom' => $this->input->post('ao_ordered_room'),
                'orderedcity' => $this->input->post('ao_ordered_city'),
                'orderedaddress' => $this->input->post('ao_ordered_address'),
                'orderedstate' => $this->input->post('ao_ordered_state'),
                'orderedzip' => $this->input->post('ao_ordered_zip'),
                'orderedphone' => $this->input->post('ao_ordered_phone'),
                'orderedfax' => $this->input->post('ao_ordered_fax'),
                'servicefacility' => $this->input->post('ao_service_facility'),
                'servicestatus' => $this->input->post('ao_service_status'),
                'servicestation' => $this->input->post('ao_service_station'),
                'serviceroom' => $this->input->post('ao_service_room'),
                'serviceaddress' => $this->input->post('ao_service_address'),
                'servicecity' => $this->input->post('ao_service_city'),
                'servicezip' => $this->input->post('ao_service_zip'),
                'servicephone' => $this->input->post('ao_service_phone'),
                'servicefax' => $this->input->post('ao_service_fax'),
                'servicedr' => $this->input->post('ao_service_dr'),
                'drphone' => $this->input->post('ao_dr_phone'),
                'drfax' => $this->input->post('ao_dr_fax'),
                'drnpi' => $this->input->post('ao_dr_NPI'),
                'ptradio' => $this->input->post('ao_pt_radio'),
                'procedurelist' => json_encode($this->input->post('ao_procedure_list')),
                'plrn' => json_encode($this->input->post('ao_plrn')),
                'symptom1' => json_encode($this->input->post('ao_symptom_1')),
                'symptom2' => json_encode($this->input->post('ao_symptom_2')),
                'symptom3' => json_encode($this->input->post('ao_symptom_3')),
                'exam' => $this->input->post('ao_reason_for_exam'),
                'history' => $this->input->post('ao_history'),
                'reason' => $this->input->post('ao_portable_reason'),
                'ioa' => $this->input->post('ao_ioa'),
                'insurance' => $this->input->post('ao_bill_insurance'),
                'facility' => $this->input->post('ao_bill_facility'),
                'medicare' => $this->input->post('ao_medicare'),
                'medicaid' => $this->input->post('ao_medicaid'),
                'state' => $this->input->post('ao_state'),
                'insurancecompany' => $this->input->post('ao_company'),
                'policy' => $this->input->post('ao_policy'),
                'group' => $this->input->post('ao_group'),
                'contract' => $this->input->post('ao_contract'),
                'insurancecompany2' => $this->input->post('ao_company2'),
                'policy2' => $this->input->post('ao_policy2'),
                'group2' => $this->input->post('ao_group2'),
                'contract2' => $this->input->post('ao_contract2'),
                'insurancecompany3' => $this->input->post('ao_company3'),
                'policy3' => $this->input->post('ao_policy3'),
                'group3' => $this->input->post('ao_group3'),
                'contract3' => $this->input->post('ao_contract3'),
                'party' => $this->input->post('ao_party'),
                'insurancetype' => $this->input->post('ao_insurance_type'),
                'responsible_party' => $this->input->post('responsible_party'),
                'relationship' => $this->input->post('ao_relationship'),
                'address1' => $this->input->post('address1'),
                'address2' => $this->input->post('address2'),
                'partyphone' => $this->input->post('party_phone'),
                'partycity' => $this->input->post('party_city'),
                'partystate' => $this->input->post('party_state'),
                'partyzip' => $this->input->post('party_zip'),
                'date_of_service' => $date_of_service,
                'updated_at' => date('Y-m-d H:i:s'),
                'electronic_signature' => $this->input->post('ao_electronic_signature'),
               /* 'order_revision'=>serialize($old_order_detail)*/
            );
            
            // Debug log for field verification
            log_message('debug', 'Order Update - ID: '.$id.' - Ordering Entity: '.var_export($data['orderingentity'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Ordered By: '.var_export($data['orderedby'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - ASR (STAT): '.var_export($data['asr'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Room: '.var_export($data['orderedroom'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - City: '.var_export($data['orderedcity'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Address: '.var_export($data['orderedaddress'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - State: '.var_export($data['orderedstate'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Zip: '.var_export($data['orderedzip'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Phone: '.var_export($data['orderedphone'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Fax: '.var_export($data['orderedfax'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Insurance Company: '.var_export($data['insurancecompany'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Insurance Company 2: '.var_export($data['insurancecompany2'], true));
            log_message('debug', 'Order Update - ID: '.$id.' - Insurance Company 3: '.var_export($data['insurancecompany3'], true));

            $current_status = intval(get_data_field($old_order_detail, 'status', 0));
            if($current_status < 10){
                $priority = strtoupper(trim($data['asr']));
                if($priority === 'STAT'){
                    $data['status'] = 1;
                }elseif($priority === 'ASAP'){
                    $data['status'] = 3;
                }else{
                    $data['status'] = 5;
                }
            }

            // Auto-generate Patient MR if blank or 'na'
            if(empty($data['patientmr']) || strtolower($data['patientmr']) == 'na'){
                $data['patientmr'] = generate_patient_mr();
            }

            $existing_attachments = $this->decode_order_attachments(isset($old_order_detail['attachment']) ? $old_order_detail['attachment'] : '');
            
            // Handle deleting and updating existing attachments
            $delete_files = $this->input->post('delete_attachments');
            $existing_files = $this->input->post('existing_attachment_files');
            $existing_types = $this->input->post('existing_attachment_types');
            $replace_files = isset($_FILES['replace_attachments']) ? $_FILES['replace_attachments'] : array();
            
            $updated_attachments = array();
            
            if (!empty($existing_files) && is_array($existing_files)) {
                foreach ($existing_files as $index => $existing_file) {
                    // Skip if marked for deletion
                    if (!empty($delete_files) && in_array($existing_file, $delete_files)) {
                        // Delete the file from server
                        $file_path = './uploads/order_attachments/' . $existing_file;
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                        continue;
                    }
                    
                    // Find the attachment in existing array
                    $att_data = null;
                    foreach ($existing_attachments as $existing_att) {
                        if ($existing_att['file'] === $existing_file) {
                            $att_data = $existing_att;
                            break;
                        }
                    }
                    
                    if ($att_data) {
                        // Update document type if changed
                        if (isset($existing_types[$index])) {
                            $att_data['type'] = $existing_types[$index];
                        }
                        
                        // Check if replacement file uploaded
                        if (!empty($replace_files['name'][$index])) {
                            // Upload new file
                            $config['upload_path'] = './uploads/order_attachments/';
                            $config['allowed_types'] = 'pdf';
                            $config['max_size'] = 10240;
                            $config['file_name'] = 'order_' . time() . '_' . uniqid() . '_' . $index;
                            
                            $this->load->library('upload', $config);
                            
                            $_FILES['single_file']['name'] = $replace_files['name'][$index];
                            $_FILES['single_file']['type'] = $replace_files['type'][$index];
                            $_FILES['single_file']['tmp_name'] = $replace_files['tmp_name'][$index];
                            $_FILES['single_file']['error'] = $replace_files['error'][$index];
                            $_FILES['single_file']['size'] = $replace_files['size'][$index];
                            
                            if ($this->upload->do_upload('single_file')) {
                                $upload_data = $this->upload->data();
                                
                                // Delete old file
                                $old_file_path = './uploads/order_attachments/' . $existing_file;
                                if (file_exists($old_file_path)) {
                                    unlink($old_file_path);
                                }
                                
                                // Update attachment data
                                $att_data['file'] = $upload_data['file_name'];
                                $att_data['original_name'] = $replace_files['name'][$index];
                                $att_data['uploaded_at'] = date('Y-m-d H:i:s');
                            }
                        }
                        
                        $updated_attachments[] = $att_data;
                    }
                }
            }
            
            // Handle new attachment uploads
            $attachments_result = $this->upload_order_attachments(
                $this->input->post('order_attachment_types'),
                isset($_FILES['order_attachments']) ? $_FILES['order_attachments'] : array()
            );
            if (!empty($attachments_result['error'])) {
                $this->session->set_flashdata('error_msg', $attachments_result['error']);
                redirect('admin/order/edit/'.$id);
                return;
            }
            if (!empty($attachments_result['attachments'])) {
                $updated_attachments = array_merge($updated_attachments, $attachments_result['attachments']);
            }
            
            // Save updated attachments
            if (!empty($updated_attachments)) {
                $data['attachment'] = json_encode($updated_attachments);
            } else {
                $data['attachment'] = '';
            }
            
            // Handle inline dispatch if filled
            $dispatch_datetime_inline = trim($this->input->post('dispatch_datetime_inline'));
            $dispatch_technologist_inline = trim($this->input->post('dispatch_technologist_inline'));
            
            // Only process dispatch if BOTH fields are filled (trim whitespace first)
            if(!empty($dispatch_datetime_inline) && !empty($dispatch_technologist_inline)) {
                // Convert datetime to timestamp
                $dtime_dispatch = DateTime::createFromFormat("m/d/Y H:i", $dispatch_datetime_inline);
                if(!$dtime_dispatch) {
                    $this->session->set_flashdata('error_msg', 'Invalid Dispatch Date/Time format. Use MM/DD/YYYY HH:MM format.');
                    redirect('admin/order/edit/'.$id);
                    return;
                }
                
                $dispatch_timestamp = $dtime_dispatch->getTimestamp();
                
                // Get technologist info
                $technologist_info = $this->user_model->get_user_info($dispatch_technologist_inline);
                if(empty($technologist_info)) {
                    $this->session->set_flashdata('error_msg', 'Invalid Technologist selected.');
                    redirect('admin/order/edit/'.$id);
                    return;
                }
                
                // Add dispatch data to main data array
                $data['dispatch_datetime'] = $dispatch_timestamp;
                $data['dispatch_technologist_id'] = $dispatch_technologist_inline;
                $data['dispatch_technologist'] = $technologist_info['lastname'].' '.$technologist_info['firstname'];
                $data['dispatch_submit_user_id'] = $user_id;
                $data['dispatch_submit_user_name'] = $session_data['username'];
                // Update status to dispatched variant based on current priority
                $current_status = intval($data['status'] ?? 5);
                if($current_status === 1) {
                    $data['status'] = 10; // D-STAT (Dispatched with STAT priority)
                } elseif($current_status === 3) {
                    $data['status'] = 11; // D-ASAP (Dispatched with ASAP priority)
                } else {
                    $data['status'] = 12; // D-Routine (Dispatched with Routine priority)
                }
            } else if(!empty($old_order_detail['dispatch_datetime']) && empty($dispatch_datetime_inline)) {
                // If dispatch was previously set but now removed, reset to undispatched status
                $data['dispatch_datetime'] = null;
                $data['dispatch_technologist_id'] = null;
                $data['dispatch_technologist'] = null;
                $data['dispatch_submit_user_id'] = null;
                $data['dispatch_submit_user_name'] = null;
                // Reset status to non-dispatched version
                $current_status = intval($data['status'] ?? 12);
                if($current_status === 10) {
                    $data['status'] = 1; // Back to N-STAT
                } elseif($current_status === 11) {
                    $data['status'] = 3; // Back to N-ASAP
                } else {
                    $data['status'] = 7; // Undispatched
                }
            }

            $data = $this->security->xss_clean($data);
            
            // Debug log after XSS clean to verify data isn't stripped
            log_message('debug', 'Order Update (After XSS) - ID: '.$id.' - Ordering Entity: '.var_export($data['orderingentity'], true));
            log_message('debug', 'Order Update (After XSS) - ID: '.$id.' - Phone: '.var_export($data['orderedphone'], true));
            
            $result = $this->orders_model->update_order($data, array('id'=>$id));
            
            log_message('debug', 'Order Update Result - ID: '.$id.' - Success: '.var_export($result, true));
            
            if ($result) {
                $order_detail = $this->orders_model->get_order_detail($id);
                
                // Debug log final saved values
                log_message('debug', 'Order Final Saved - ID: '.$id.' - Ordering Entity: '.var_export($order_detail['orderingentity'], true));
                log_message('debug', 'Order Final Saved - ID: '.$id.' - Ordered By: '.var_export($order_detail['orderedby'], true));
                log_message('debug', 'Order Final Saved - ID: '.$id.' - Phone: '.var_export($order_detail['orderedphone'], true));

                $order_history_data = array(
                    'order_id'=>$id,
                    'action'=>'edit',
                    'old_data'=>serialize($old_order_detail),
                    'new_data'=>serialize($order_detail),
                    'created_at'=>date('Y-m-d H:i:s'),
                    'user_id'=>$user_id,
                    'user_name'=>$session_data['username']
                );
                $this->order_history_model->add_order_history($order_history_data);
                
                // Log separate dispatch action if inline dispatch was used
                if (!empty($dispatch_datetime_inline) && !empty($dispatch_technologist_inline)) {
                    $dispatch_history_data = array(
                        'order_id'=>$id,
                        'action'=>'dispatched',
                        'old_data'=>serialize($old_order_detail),
                        'new_data'=>serialize($order_detail),
                        'created_at'=>date('Y-m-d H:i:s'),
                        'user_id'=>$user_id,
                        'user_name'=>$session_data['username']
                    );
                    $this->order_history_model->add_order_history($dispatch_history_data);
                }
                
                $this->session->set_flashdata('success_msg', 'Order has been updated successfully!');
                //console.log("afd");
                redirect(base_url('admin/order'));
            }
        }
        public function view($id) {
            $session_data = $this->session->get_userdata('did');
            $user_id = $session_data['did'];
            $order_detail = $this->orders_model->get_order_detail($id, true);
            if(empty($order_detail)){
                $this->session->set_flashdata('msg', 'Order does not exist!');
                redirect(base_url('admin/order'));
            }else{
                $this->load_form_models();
                $data['title'] = 'View Order';
                $data['facilities'] = $this->data_cache->get_facilities();
                $data['orderingphysician'] = $this->data_cache->get_ordering_physicians();
                $data['procedures'] = $this->data_cache->get_procedures();
                $data['lists'] = $this->data_cache->get_lists_by_name("relationship");
                $data['reason_photoble'] = $this->data_cache->get_lists_by_name("R4P");
                $data['states'] = $this->data_cache->get_states();
                $data['insurance_types'] = $this->data_cache->get_insurance_types();
                $data['insurance_companies'] = $this->data_cache->get_insurance_companies();
                $data['divisions'] = $this->data_cache->get_divisions(0);
                
                // ICD10 codes loaded via AJAX autocomplete - don't load full table
                $data['icd10_codes'] = array();
                
                // Load payers for insurance dropdown
                $data['payers'] = $this->data_cache->get_payers();

                //print_r($order_detail); die;
                $data['order'] = $order_detail;
                $data['view'] = 'admin/orders/view';
                $data['page_js'] = array('order.js');
                $data['page_plugins'] = array('bootstrap-select', 'datetimepicker');
                $this->load->view('layout', $data);
            }
        }
    public function dispatch() {
        $this->load_form_models();
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];
        $order_id = $this->input->post('order_id');
        $order_detail_old = $this->orders_model->get_order_detail($order_id);
        if(empty($order_detail_old)){
            output_error('Order does not exist');
            return;
        }
        $where = array('id'=>$order_id);
        $dispatch_datetime = $this->input->post('date_time');
        $dtime = DateTime::createFromFormat("m/d/Y H:i:s", $dispatch_datetime.":00");
        $dispatch_datetimestamp = $dtime->getTimestamp();
        $dispatch_technologist_id = $this->input->post('technologist');
        $technologist_info = $this->user_model->get_user_info($dispatch_technologist_id);
        if(empty($technologist_info)){
            output_error('Technologist does not exist');
            return;
        }

        $update_data = array(
            'dispatch_datetime'=>$dispatch_datetimestamp,
            'dispatch_technologist_id'=>$dispatch_technologist_id,
            'dispatch_technologist'=>$technologist_info['lastname'].' '.$technologist_info['firstname'],
            'dispatch_submit_user_id'=>$user_id,
            'dispatch_submit_user_name'=>$session_data['username']
        );

        if(intval($order_detail_old['status']) === 1 || intval($order_detail_old['status']) === 0){
            $update_data['status'] = 10;
        }elseif(intval($order_detail_old['status']) === 3){
            $update_data['status'] = 11;
        }elseif(intval($order_detail_old['status']) === 5){
            $update_data['status'] = 12;
        }
        // Update order with dispatch info
        $this->orders_model->update_order($update_data, $where);

        // Re-fetch order details after update
        $order_detail = $this->orders_model->get_order_detail($order_id);

        // 1) Generate accession number if missing (default to BS prefix)
        if (empty($order_detail['fldacsno1'])) {
            // Try detect prefix: if orderingentity resolves to a facility name containing 'fastrad' use FR else BS
            $acs_prefix = 'FR';
            if (!empty($order_detail['orderingentity'])) {
                $fac = null;
                // best-effort: attempt to fetch facility by id (method may differ in your model)
                if (is_numeric($order_detail['orderingentity'])) {
                    if (method_exists($this->facility_model, 'get_facility')) {
                        $fac = $this->facility_model->get_facility($order_detail['orderingentity']);
                    } elseif (method_exists($this->facility_model, 'get_facility_by_id')) {
                        $fac = $this->facility_model->get_facility_by_id($order_detail['orderingentity']);
                    } elseif (method_exists($this->facility_model, 'get_facility_via_id')) {
                        $fac = $this->facility_model->get_facility_via_id($order_detail['orderingentity']);
                    } elseif (method_exists($this->facility_model, 'get_facility_details')) {
                        $fac = $this->facility_model->get_facility_details($order_detail['orderingentity']);
                    }
                    if (!empty($fac) && is_array($fac)) {
                        $fac_name = isset($fac['facility_name']) ? $fac['facility_name'] : (isset($fac['name']) ? $fac['name'] : '');
                        if (stripos($fac_name, 'fastrad') !== false) $acs_prefix = 'BS';
                    }
                }
            }
            // Always default to BS unless facility indicates otherwise
            $accession_number = $this->generate_accession_number($acs_prefix);
            $this->orders_model->update_order(['fldacsno1' => $accession_number], ['id' => $order_id]);
            $order_detail['fldacsno1'] = $accession_number;
        }

        // 2) Generate patient MR (PID) if missing or 'na'
        if (empty($order_detail['patientmr']) || strtolower($order_detail['patientmr']) == 'na') {
            $patient_mr = substr(time(), 0, 7) . rand(1000, 9999); // ensure uniqueness reasonably
            $this->orders_model->update_order(['patientmr' => $patient_mr], ['id' => $order_id]);
            $order_detail['patientmr'] = $patient_mr;
        }

        // Build worklist text files (one per procedure) and send SMS to technologist
        $this->generate_worklist_files($order_detail, $dispatch_datetimestamp);
        $dispatch_message = $this->send_dispatch_sms($order_detail, $technologist_info);

        // Save order history
        $order_detail_after = $this->orders_model->get_order_detail($order_id);
        $order_history_data = array(
            'order_id'=>$order_id,
            'action'=>'dispatched',
            'old_data'=>serialize($order_detail_old),
            'new_data'=>serialize($order_detail_after),
            'created_at'=>date('Y-m-d H:i:s'),
            'user_id'=>$user_id,
            'user_name'=>$session_data['username']
        );
        $this->order_history_model->add_order_history($order_history_data);

        // Return message in JSON response for AJAX toast
        output_data(1, $dispatch_message);
    }

    // ... rest of methods (mark_completed, detail, ajax_get_order_notes, update_note, delete_note, cancel, del) remain unchanged ...

    /**
     * Build and send the dispatch SMS to the technologist.
     * Called from dispatch() and from inline-dispatch on the add/edit forms.
     * Returns a human-readable status message.
     */
    private function send_dispatch_sms($order_detail, $technologist_info)
    {
        $order_id  = isset($order_detail['id']) ? $order_detail['id'] : '';
        $firstName = isset($order_detail['firstname']) ? $order_detail['firstname'] : '';
        $lastName  = isset($order_detail['lastname'])  ? $order_detail['lastname']  : '';
        $acs       = isset($order_detail['fldacsno1']) ? $order_detail['fldacsno1'] : '';
        $dob       = !empty($order_detail['dob']) ? date('m/d/Y', strtotime($order_detail['dob'])) : '';

        // Ordering Physician
        $ordering = '';
        if (!empty($order_detail['orderedby'])) {
            if (is_numeric($order_detail['orderedby'])) {
                $op = $this->user_model->get_user_info($order_detail['orderedby']);
                $ordering = !empty($op) ? trim($op['lastname'].', '.$op['firstname']) : $order_detail['orderedby'];
            } else {
                $ordering = $order_detail['orderedby'];
            }
        }

        // Facility name
        $facility = '';
        if (!empty($order_detail['orderingentity'])) {
            if (is_numeric($order_detail['orderingentity'])) {
                $f = null;
                if      (method_exists($this->facility_model, 'get_facility'))         $f = $this->facility_model->get_facility($order_detail['orderingentity']);
                elseif  (method_exists($this->facility_model, 'get_facility_by_id'))   $f = $this->facility_model->get_facility_by_id($order_detail['orderingentity']);
                elseif  (method_exists($this->facility_model, 'get_facility_via_id'))  $f = $this->facility_model->get_facility_via_id($order_detail['orderingentity']);
                if (!empty($f) && is_array($f)) {
                    $facility = isset($f['facility_name']) ? $f['facility_name'] : (isset($f['name']) ? $f['name'] : '');
                }
            } else {
                $facility = $order_detail['orderingentity'];
            }
        }
        if (empty($facility) && !empty($order_detail['servicefacility'])) $facility = $order_detail['servicefacility'];

        $addr1    = !empty($order_detail['orderedaddress']) ? $order_detail['orderedaddress'] : (isset($order_detail['serviceaddress']) ? $order_detail['serviceaddress'] : '');
        $addrCity = !empty($order_detail['orderedcity'])    ? $order_detail['orderedcity']    : (isset($order_detail['servicecity'])    ? $order_detail['servicecity']    : '');
        $privAddr1 = isset($order_detail['address1'])   ? $order_detail['address1']   : '';
        $privCity  = isset($order_detail['partycity'])  ? $order_detail['partycity']  : '';
        $pid       = isset($order_detail['patientmr'])  ? $order_detail['patientmr']  : '';
        $medicare  = isset($order_detail['medicare'])   ? $order_detail['medicare']   : '';
        $room      = !empty($order_detail['orderedroom']) ? $order_detail['orderedroom'] : (isset($order_detail['serviceroom']) ? $order_detail['serviceroom'] : '');

        // Resolve procedures for Exams line
        $procedure_items = [];
        if (!empty($order_detail['procedurelist'])) {
            $decoded = json_decode($order_detail['procedurelist'], true);
            $ids = is_array($decoded) ? $decoded : [$order_detail['procedurelist']];
            foreach ($ids as $proc_id) {
                if (empty($proc_id)) continue;
                $proc_info = null;
                if (is_numeric($proc_id)) {
                    if      (method_exists($this->procedure_model, 'get_procedure_info_via_id')) $proc_info = $this->procedure_model->get_procedure_info_via_id($proc_id);
                    elseif  (method_exists($this->procedure_model, 'get_procedure_via_id'))     $proc_info = $this->procedure_model->get_procedure_via_id($proc_id);
                }
                $desc = '';
                if (!empty($proc_info) && is_array($proc_info)) {
                    $desc = isset($proc_info['description']) ? $proc_info['description'] : (isset($proc_info['procedure_name']) ? $proc_info['procedure_name'] : (isset($proc_info['name']) ? $proc_info['name'] : ''));
                }
                $procedure_items[] = $desc ?: (string)$proc_id;
            }
        }
        $exams = implode(', ', $procedure_items);

        // Build message
        $msg  = trim("{$firstName} {$lastName}");
        $msg .= "\nASC: {$acs}";
        $msg .= "\nDOB: {$dob}";
        $msg .= "\nOrdering Physician: {$ordering}";
        if (!empty($order_detail['asr'])) $msg .= "\n*** ".strtoupper($order_detail['asr'])." ***";
        $msg .= "\nFacility: {$facility}";
        $msg .= "\n{$addr1}, {$addrCity}";
        $msg .= "\n{$privAddr1}, {$privCity}";
        $msg .= "\nPID: {$pid}";
        $msg .= "\nM#: {$medicare}";
        $msg .= "\nRM: {$room}";
        $msg .= "\nExams: {$exams}";
        if (!empty($order_detail['plrn'])) {
            $plrn_data = json_decode($order_detail['plrn'], true);
            $has_cd = false;
            if (is_array($plrn_data)) {
                foreach ($plrn_data as $pv) { if (!empty($pv) && stripos($pv, 'CD') !== false) { $has_cd = true; break; } }
            } elseif (is_string($order_detail['plrn']) && stripos($order_detail['plrn'], 'CD') !== false) {
                $has_cd = true;
            }
            if ($has_cd) $msg .= "\n*** CD REQUESTED ***";
        }

        // Choose best phone
        $techPhone = '';
        if (!empty($technologist_info['phone']))          $techPhone = $technologist_info['phone'];
        elseif (!empty($technologist_info['mobile']))     $techPhone = $technologist_info['mobile'];
        elseif (!empty($order_detail['servicephone']))    $techPhone = $order_detail['servicephone'];
        elseif (!empty($order_detail['orderedphone']))    $techPhone = $order_detail['orderedphone'];

        // Send and return status message
        if (!empty($techPhone)) {
            $ok = $this->send_sms_clickatell($techPhone, $msg);
            if ($ok) {
                log_message('info', 'SMS sent successfully for order '.$order_id.' to '.$techPhone);
                return 'Order has been dispatched successfully and SMS sent!';
            } else {
                log_message('error', 'SMS failed for order '.$order_id.' to '.$techPhone);
                return 'Order dispatched, but SMS failed!';
            }
        }
        log_message('error', 'No phone found to send SMS for order '.$order_id);
        return 'Order dispatched, but no phone number to send SMS!';
    }

    /**
     * Generate DMWL worklist .txt files for all procedures on an order.
     * Called from dispatch() and from inline-dispatch on the add/edit forms.
     */
    private function generate_worklist_files($order_detail, $dispatch_datetimestamp)
    {
        // Resolve procedures
        $procedure_items = [];
        if (!empty($order_detail['procedurelist'])) {
            $decoded = json_decode($order_detail['procedurelist'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $proc_id) {
                    if (empty($proc_id)) continue;
                    $proc_info = null;
                    if (is_numeric($proc_id)) {
                        if (method_exists($this->procedure_model, 'get_procedure_info_via_id')) {
                            $proc_info = $this->procedure_model->get_procedure_info_via_id($proc_id);
                        } elseif (method_exists($this->procedure_model, 'get_procedure_via_id')) {
                            $proc_info = $this->procedure_model->get_procedure_via_id($proc_id);
                        }
                    }
                    $cpt = ''; $desc = '';
                    if (!empty($proc_info) && is_array($proc_info)) {
                        $cpt  = isset($proc_info['cpt_code'])     ? $proc_info['cpt_code']     : '';
                        $desc = isset($proc_info['description'])  ? $proc_info['description']  : '';
                        if ($desc === '' && isset($proc_info['procedure_name'])) $desc = $proc_info['procedure_name'];
                        if ($desc === '' && isset($proc_info['name']))           $desc = $proc_info['name'];
                    }
                    if ($desc === '') $desc = (string)$proc_id;
                    if ($cpt  === '') $cpt  = is_string($proc_id) ? $proc_id : '';
                    $procedure_items[] = ['id' => $proc_id, 'cpt' => $cpt, 'desc' => $desc];
                }
            } else {
                $procedure_items[] = [
                    'id'  => $order_detail['procedurelist'],
                    'cpt' => (string)$order_detail['procedurelist'],
                    'desc'=> (string)$order_detail['procedurelist'],
                ];
            }
        }
        if (empty($procedure_items)) return;

        // Resolve facility name
        $facility = '';
        if (!empty($order_detail['orderingentity'])) {
            if (is_numeric($order_detail['orderingentity'])) {
                $f = null;
                if      (method_exists($this->facility_model, 'get_facility'))         $f = $this->facility_model->get_facility($order_detail['orderingentity']);
                elseif  (method_exists($this->facility_model, 'get_facility_by_id'))   $f = $this->facility_model->get_facility_by_id($order_detail['orderingentity']);
                elseif  (method_exists($this->facility_model, 'get_facility_via_id'))  $f = $this->facility_model->get_facility_via_id($order_detail['orderingentity']);
                elseif  (method_exists($this->facility_model, 'get_facility_details')) $f = $this->facility_model->get_facility_details($order_detail['orderingentity']);
                if (!empty($f) && is_array($f)) {
                    $facility = isset($f['facility_name']) ? $f['facility_name'] : (isset($f['name']) ? $f['name'] : '');
                }
            } else {
                $facility = $order_detail['orderingentity'];
            }
        }
        if (empty($facility)) {
            $facility = !empty($order_detail['servicefacility']) ? $order_detail['servicefacility'] : '';
        }

        $sex          = $this->normalize_sex(isset($order_detail['gender']) ? $order_detail['gender'] : '');
        $patient_name = $this->format_name_caret(
            isset($order_detail['firstname']) ? $order_detail['firstname'] : '',
            isset($order_detail['lastname'])  ? $order_detail['lastname']  : ''
        );
        $dob_worklist    = !empty($order_detail['dob']) ? date('Y-m-d', strtotime($order_detail['dob'])) : '';
        $date_of_service = !empty($order_detail['date_of_service'])
            ? $order_detail['date_of_service']
            : date('Y-m-d', $dispatch_datetimestamp);

        // Resolve ICD-10 symptom descriptions
        $symptom_desc = '';
        if (!empty($order_detail['symptom1'])) {
            $symptom_decoded = json_decode($order_detail['symptom1'], true);
            if (is_array($symptom_decoded)) {
                $symptom_names = [];
                foreach ($symptom_decoded as $symptom_id) {
                    if (empty($symptom_id)) continue;
                    $icd = $this->icd10_model->get_by_id($symptom_id);
                    if (!empty($icd) && isset($icd['description'])) {
                        $symptom_names[] = $icd['description'];
                    }
                }
                $symptom_desc = implode(', ', $symptom_names);
            } else {
                $symptom_desc = (string)$order_detail['symptom1'];
            }
        }

        $history_text    = isset($order_detail['history']) ? trim((string)$order_detail['history']) : '';
        $exam_text       = isset($order_detail['exam'])    ? trim((string)$order_detail['exam'])    : '';
        $patient_history = $history_text !== '' ? $history_text : $exam_text;
        if ($symptom_desc !== '') {
            $patient_history = $history_text === '' ? $symptom_desc : ($history_text . ' - ' . $symptom_desc);
        }

        // Resolve ordering physician
        $ordering_caret = '';
        if (!empty($order_detail['orderedby'])) {
            if (is_numeric($order_detail['orderedby'])) {
                $op = $this->user_model->get_user_info($order_detail['orderedby']);
                if (!empty($op)) $ordering_caret = $this->format_name_caret($op['firstname'], $op['lastname']);
            } else {
                $ordering_caret = $this->format_name_caret_from_string($order_detail['orderedby']);
            }
        }
        if ($ordering_caret === '' && !empty($order_detail['servicedr'])) {
            if (is_numeric($order_detail['servicedr'])) {
                $op = $this->user_model->get_user_info($order_detail['servicedr']);
                if (!empty($op)) $ordering_caret = $this->format_name_caret($op['firstname'], $op['lastname']);
            } else {
                $ordering_caret = $this->format_name_caret_from_string($order_detail['servicedr']);
            }
        }

        $accession_number = isset($order_detail['fldacsno1'])  ? $order_detail['fldacsno1']  : '';
        $patient_id       = isset($order_detail['patientmr'])  ? $order_detail['patientmr']  : '';
        $worklist_dir     = 'C:\\DMWL';
        if (!is_dir($worklist_dir)) {
            if (!mkdir($worklist_dir, 0777, true) && !is_dir($worklist_dir)) {
                log_message('error', 'Failed to create DMWL directory: ' . $worklist_dir);
            }
        }

        foreach ($procedure_items as $idx => $proc) {
            $cpt      = isset($proc['cpt'])  ? $proc['cpt']  : '';
            $desc     = isset($proc['desc']) ? $proc['desc'] : '';
            $cpt_safe = preg_replace('/[^A-Za-z0-9_-]+/', '', $cpt);
            if ($cpt_safe === '') $cpt_safe = 'PROC' . ($idx + 1);

            $file_name = $accession_number . '_' . $cpt_safe . '_' . ($idx + 1) . '.txt';
            $file_path = rtrim($worklist_dir, '\\') . '\\' . $file_name;

            $lines = [
                '[worklist1]',
                'Patient ID = '                       . $patient_id,
                'Patient Name = '                     . $patient_name,
                "Patient's Sex = "                    . $sex,
                'Date of Birth = '                    . $dob_worklist,
                'Additional Patient History = '       . $patient_history,
                'Institution Name = '                 . $facility,
                'Accession Number = '                 . $accession_number,
                "Referring Physician's Name = "       . $ordering_caret,
                "Requesting Physician's Name = "      . $ordering_caret,
                'Admitting Diagnoses Description = '  . $patient_history,
                'Requested Procedure ID = '           . $cpt,
                'Requested Procedure Description = '  . $desc,
                'Requested Procedure Priority = High',
                'Scheduled AE Station = Mobile',
                'Modality = DX',
                'Scheduled Start Date = '             . $date_of_service,
                'Scheduled Start Time = 00:00',
                'Scheduled Procedure ID = '           . $cpt,
                'Scheduled Procedure Description = '  . $desc,
            ];

            $payload = implode(PHP_EOL, $lines) . PHP_EOL;
            if (!@file_put_contents($file_path, $payload)) {
                log_message('error', 'Failed to write worklist file: ' . $file_path);
            }
        }
    }

    public function mark_completed() {
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];
        $order_id = $this->input->post('order_id');
        $order_detail_old = $this->orders_model->get_order_detail($order_id);
        if(empty($order_detail_old)){
            output_error('Order does not exist');
            return;
        }
        $where = array('id'=>$order_id);
        $datetime = $this->input->post('date_time');
        $dtime = DateTime::createFromFormat("m/d/Y H:i:s", $datetime.":00");
        $datetimestamp = $dtime->getTimestamp();
        $update_data = array(
            'mark_complete'=>1,
            'mark_datetime'=>$datetimestamp,
            'mark_submit_user_id'=>$user_id,
            'mark_submit_user_name'=>$session_data['username'],
            'status'=>100  // Set status to 100 (Marked as EOS)
        );
        $this->orders_model->update_order($update_data, $where);
        $order_detail = $this->orders_model->get_order_detail($order_id);
        $order_history_data = array(
            'order_id'=>$order_id,
            'action'=>'Marked complete',
            'old_data'=>serialize($order_detail_old),
            'new_data'=>serialize($order_detail),
            'created_at'=>date('Y-m-d H:i:s'),
            'user_id'=>$user_id,
            'user_name'=>$session_data['username']
        );
        $this->order_history_model->add_order_history($order_history_data);
        output_data(1, 'Marked completed successfully!');
    }

    public function mark_multiple_completed() {
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];
        $order_ids = $this->input->post('order_ids');
        
        if(empty($order_ids) || !is_array($order_ids)) {
            output_error('No orders selected');
            return;
        }
        
        $datetime = $this->input->post('date_time');
        if(empty($datetime)) {
            $datetime = date('m/d/Y H:i');
        }
        $dtime = DateTime::createFromFormat("m/d/Y H:i:s", $datetime.":00");
        $datetimestamp = $dtime->getTimestamp();
        
        // PERFORMANCE: Fetch all orders in one query instead of N+1
        $old_orders = $this->orders_model->get_orders_by_ids($order_ids);
        
        // Filter to only valid order IDs
        $valid_ids = array_keys($old_orders);
        if(empty($valid_ids)) {
            output_error('No valid orders found');
            return;
        }
        
        // PERFORMANCE: Single batch update instead of N individual updates
        $update_data = array(
            'mark_complete'=>1,
            'mark_datetime'=>$datetimestamp,
            'mark_submit_user_id'=>$user_id,
            'mark_submit_user_name'=>$session_data['username'],
            'status'=>100  // Set status to 100 (Marked as EOS)
        );
        $this->orders_model->update_orders_batch($update_data, $valid_ids);
        
        // Fetch all updated orders in one query
        $new_orders = $this->orders_model->get_orders_by_ids($valid_ids);
        
        // Batch insert order history
        $history_batch = array();
        $now = date('Y-m-d H:i:s');
        foreach($valid_ids as $oid) {
            $history_batch[] = array(
                'order_id'=>$oid,
                'action'=>'Marked complete (bulk)',
                'old_data'=>serialize(isset($old_orders[$oid]) ? $old_orders[$oid] : array()),
                'new_data'=>serialize(isset($new_orders[$oid]) ? $new_orders[$oid] : array()),
                'created_at'=>$now,
                'user_id'=>$user_id,
                'user_name'=>$session_data['username']
            );
        }
        if(!empty($history_batch)) {
            $this->db->insert_batch('tbl_order_history', $history_batch);
        }
        
        $success_count = count($valid_ids);
        $this->session->set_flashdata('success_msg', $success_count.' order(s) marked completed successfully!');
        output_data(1, $success_count.' order(s) marked completed');
    }

    // ... other methods unchanged ...
    public function detail() {
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];
        $order_id = $this->input->post('order_id');
        $order_detail = $this->orders_model->get_order_detail($order_id, true);
        output_data($order_detail);
    }

    public function ajax_get_order_notes($order_id){
        $note_list=$this->orders_model->get_order_notes($order_id);
        if(empty($note_list)) $note_list=array();
        $record_list=array();
        if(count($note_list)>0){
            foreach ($note_list as $key => $row){
                $record=array();
                $record[]= $key + 1;
                $record[] = '<div class="td-note">'.$row['note_text'].'</div>';
                
                // Add attachment column
                $attachment_html = '';
                if(!empty($row['attachment'])) {
                    $attachment_html = '<a href="'.base_url('uploads/note_attachments/'.$row['attachment']).'" target="_blank" class="btn btn-xs btn-danger"><i class="fa fa-file-pdf-o"></i> View PDF</a>';
                }
                $record[] = $attachment_html;
                
                $record[]=$row['user_name'];
                $dtime = DateTime::createFromFormat("Y-m-d H:i:s", $row['created_at']);
                $time_slot_stamp = $dtime->getTimestamp();
                $record[]='<span class="hidden">'.$time_slot_stamp.'</span>'.date("m/d/Y H:i:s", $time_slot_stamp);

                $html='<div class="">';
                $html.='<a href="javascript:void(0);" data-id="'.$row['id'].'" data-attachment="'.htmlspecialchars($row['attachment'] ?? '', ENT_QUOTES).'" class="ajax-note-edit-btn icon-btn btn btn-primary btn-xs tooltips margin-right-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="fa fa-edit fa fa-white"></i></a>';
                $html.='<a href="javascript:void(0);" data-id="'.$row['id'].'" class="ajax-note-del-btn icon-btn btn btn-danger btn-xs tooltips margin-right-5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-times fa fa-white"></i></a>';
                $html.='</div>';
                $record[]=$html;
                $record_list[]=$record;
            }
        }
        $table_data=array('data'=>$record_list);
        echo json_encode($table_data);
    }

    public function update_note(){
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];

        $order_id = (int) $this->input->post('order_id');
        $note_id = (int) $this->input->post('note_id');
        $note_text = $this->input->post('note_text');
        $remove_attachment = (int) $this->input->post('remove_attachment');
        
        $attachment_filename = '';
        
        // Handle file upload
        if(!empty($_FILES['note_attachment']['name'])) {
            $config['upload_path'] = './uploads/note_attachments/';
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 10240; // 10MB
            $config['encrypt_name'] = TRUE;
            
            // Create directory if it doesn't exist
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('note_attachment')) {
                $upload_data = $this->upload->data();
                $attachment_filename = $upload_data['file_name'];
            } else {
                $error = array('error' => $this->upload->display_errors());
                echo json_encode(array('status' => '0', 'message' => 'File upload failed: ' . $this->upload->display_errors()));
                return;
            }
        }
        
        if($note_id > 0){ //update note
            $condition = array('id'=>$note_id);
            $update_data = array(
                'note_text'=>$note_text,
                'created_at'=>date("Y-m-d H:i:s", time())
            );
            
            // Handle attachment
            if($attachment_filename) {
                // Delete old attachment if exists
                $old_note = $this->db->where('id', $note_id)->get('tbl_order_notes')->row_array();
                if($old_note && !empty($old_note['attachment'])) {
                    $old_file = './uploads/note_attachments/' . $old_note['attachment'];
                    if(file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $update_data['attachment'] = $attachment_filename;
            } elseif($remove_attachment == 1) {
                // Remove attachment
                $old_note = $this->db->where('id', $note_id)->get('tbl_order_notes')->row_array();
                if($old_note && !empty($old_note['attachment'])) {
                    $old_file = './uploads/note_attachments/' . $old_note['attachment'];
                    if(file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $update_data['attachment'] = '';
            }
            
            $this->db->where($condition)->update("tbl_order_notes", $update_data);
        }else{ //add note
            $update_data = array(
                'order_id'=>$order_id,
                'note_text'=>$note_text,
                'user_id'=>$user_id,
                'user_name'=>$session_data['username'],
                'attachment'=>$attachment_filename,
                'created_at'=>date("Y-m-d H:i:s", time())
            );
            $this->db->insert("tbl_order_notes", $update_data);
        }
        $order_history_data = array(
            'order_id'=>$order_id,
            'action'=>'note added',
            'old_data'=>'',
            'new_data'=>'',
            'created_at'=>date('Y-m-d H:i:s'),
            'user_id'=>$user_id,
            'user_name'=>$session_data['username']
        );
        $this->order_history_model->add_order_history($order_history_data);
        output_data($update_data);
    }

    public function delete_note(){
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];

        $order_id = (int) $this->input->post('order_id');
        $note_id = (int) $this->input->post('note_id');
        
        // Get note details to delete attachment file
        $note = $this->db->where('id', $note_id)->get('tbl_order_notes')->row_array();
        if($note && !empty($note['attachment'])) {
            $attachment_file = './uploads/note_attachments/' . $note['attachment'];
            if(file_exists($attachment_file)) {
                unlink($attachment_file);
            }
        }
        
        $condition = array('id'=>$note_id);
        $this->db->where($condition)->delete("tbl_order_notes");
        $order_history_data = array(
            'order_id'=>$order_id,
            'action'=>'note deleted',
            'old_data'=>'',
            'new_data'=>'',
            'created_at'=>date('Y-m-d H:i:s'),
            'user_id'=>$user_id,
            'user_name'=>$session_data['username']
        );
        $this->order_history_model->add_order_history($order_history_data);
        output_data($order_history_data);
    }

    public function cancel() {
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];
        $order_id = $this->input->post('order_id');
        $order_detail_old = $this->orders_model->get_order_detail($order_id);
        if(empty($order_detail_old)){
            output_error('Order does not exist');
            return;
        }
        $where = array('id'=>$order_id);
        $reason_for_cancel = $this->input->post('reason_for_cancel');
        $reschedule = $this->input->post('reschedule');
        $datetime = $this->input->post('date_time');
        $dtime = DateTime::createFromFormat("m/d/Y H:i:s", $datetime.":00");
        $datetimestamp = $dtime->getTimestamp();
        $update_data = array(
            'is_canceled'=>1,
            'cancel_datetime'=>$datetimestamp,
            'reason_for_cancel'=>$reason_for_cancel,
            'reschedule'=>$reschedule,
            'cancel_submit_user_id'=>$user_id,
            'cancel_submit_user_name'=>$session_data['username']
        );

        $update_data['status'] = 999;

        $this->orders_model->update_order($update_data, $where);
        $order_detail = $this->orders_model->get_order_detail($order_id);
        $order_history_data = array(
            'order_id'=>$order_id,
            'action'=>'order canceled',
            'old_data'=>serialize($order_detail_old),
            'new_data'=>serialize($order_detail),
            'created_at'=>date('Y-m-d H:i:s'),
            'user_id'=>$user_id,
            'user_name'=>$session_data['username']
        );
        $this->order_history_model->add_order_history($order_history_data);
        output_data(1, 'Order has been canceled successfully!');
    }
    public function del($id = 0)
    {
        $id = intval($this->security->xss_clean($id));
        if(empty($id)){
            $this->session->set_flashdata('error_msg', 'Invalid order ID');
            redirect(base_url('admin/order'));
            return;
        }
        
        $order_detail = $this->orders_model->get_order_detail($id);
        if(empty($order_detail)){
            $this->session->set_flashdata('error_msg', 'Order does not exist');
            redirect(base_url('admin/order'));
            return;
        }
        
        // Log history before deletion
        $session_data = $this->session->get_userdata('did');
        $user_id = $session_data['did'];
        $order_history_data = array(
            'order_id' => $id,
            'action' => 'deleted',
            'old_data' => serialize($order_detail),
            'new_data' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'user_name' => $session_data['username']
        );
        $this->order_history_model->add_order_history($order_history_data);
        
        $this->db->delete('tbl_orderdetail', array('id' => $id));
        $this->session->set_flashdata('success_msg', 'Order has been deleted successfully!');
        redirect(base_url('admin/order'));
    }
    
    public function clear_msg_flag()
    {
        $flag = $this->input->post('flag');
        if($flag){
            $this->session->unset_userdata($flag);
            $ci_vars = $this->session->userdata('__ci_vars');
            if(is_array($ci_vars) && array_key_exists($flag, $ci_vars)){
                unset($ci_vars[$flag]);
                $this->session->set_userdata('__ci_vars', $ci_vars);
            }
        }
    }

    public function import_bulk_orders()
    {
        $file_path = 'F:/BSMI Ordering Providers (1) (1).csv'; // Update to your actual CSV path

        if (file_exists($file_path)) {
            $file = fopen($file_path, "r");
            $header = fgetcsv($file); // Read header row
            $session_data = $this->session->get_userdata('did');
            $user_id = $session_data['did'];
            while (($row = fgetcsv($file)) !== FALSE) {
                $name = isset($row[1]) ? $row[1] : '';
                $firstname = '';
                $lastname = '';
                if (strpos($name, ',') !== false) {
                    list($lastname, $firstname) = array_map('trim', explode(',', $name, 2));
                } else {
                    $firstname = trim($name);
                }

                $data = array(
                'order_creator' => $user_id,
                'order_editor' => $user_id,
                'kind' => '', // Not in CSV
                'middlename' => '', // Not in CSV
                // Split Name (format: Lastname, Firstname)
                'firstname' => $firstname,
                'lastname' => $lastname,
                'suffixname' => '', // Not in CSV
                'patientmr' => '', // Not in CSV
                'dob' => '', // Not in CSV
                'patientssn' => '', // Not in CSV
                'gender' => '', // Not in CSV
                'orderingentity' => $row[2], // Location
                'orderedby' => '', // Not in CSV
                'asr' => '', // Not in CSV
                'orderedstation' => '', // Not in CSV
                'orderedroom' => '', // Not in CSV
                'orderedcity' => '', // Not in CSV
                'orderedaddress' => '', // Not in CSV
                'orderedstate' => '', // Not in CSV
                'orderedzip' => '', // Not in CSV
                'orderedphone' => $row[3], // Phone
                'orderedfax' => $row[4], // Fax
                'servicefacility' => '', // Not in CSV
                'servicestatus' => '', // Not in CSV
                // ... set all other fields to '' or null ...
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'order_revision' => '',
                'electronic_signature' => '',
                'status' => 5,
            );

                // Status logic
                $priority = strtoupper(trim($data['asr']));
                if ($priority === 'STAT') {
                    $data['status'] = 1;
                } elseif ($priority === 'ASAP') {
                    $data['status'] = 3;
                } else {
                    $data['status'] = 5;
                }

                if (strtolower($data['patientmr']) == 'na') {
                    $data['patientmr'] = substr(time(), 0, 7) . rand(1000, 9999);
                }

                $data = $this->security->xss_clean($data);
                $result = $this->orders_model->add_order($data);
                if ($result) {
                    $order_id = $result;
                    $order_detail = $this->orders_model->get_order_detail($order_id);
                    $order_history_data = array(
                        'order_id' => $order_id,
                        'action' => 'create',
                        'old_data' => '',
                        'new_data' => serialize($order_detail),
                        'created_at' => date('Y-m-d H:i:s'),
                        'user_id' => $user_id,
                        'user_name' => isset($session_data['username']) ? $session_data['username'] : ''
                    );
                    $this->order_history_model->add_order_history($order_history_data);
                }
            }
            fclose($file);
            $this->session->set_flashdata('success', 'Bulk order import successful!');
        } else {
            $this->session->set_flashdata('error', 'File not found: ' . $file_path);
        }
    }

    /**
     * AJAX endpoint for ICD10 autocomplete search
     * Returns JSON formatted for jQuery UI autocomplete
     */
    public function search_icd10()
    {
        header('Content-Type: application/json');
        
        $term = $this->input->get('term', TRUE);
        
        if(empty($term)){
            echo json_encode([]);
            return;
        }
        
        try {
            // Ensure icd10_model is loaded
            if (!isset($this->icd10_model)) {
                $this->load->model('admin/icd10_model', 'icd10_model');
            }
            // Search ICD10 codes by code or description
            $results = $this->icd10_model->search_icd10($term);
            
            $formatted = [];
            foreach($results as $row){
                $formatted[] = [
                    'id' => $row['id'],
                    'value' => $row['code'] . ' - ' . $row['description'],
                    'label' => $row['code'] . ' - ' . $row['description'],
                    'code' => $row['code'],
                    'description' => $row['description']
                ];
            }
            
            echo json_encode($formatted);
        } catch (Exception $e) {
            log_message('error', 'ICD10 search error: ' . $e->getMessage());
            echo json_encode([]);
        }
    }

    /**
     * AJAX endpoint for Procedure/CPT code autocomplete search
     * Returns JSON formatted for jQuery UI autocomplete
     */
    public function search_procedures()
    {
        header('Content-Type: application/json');
        
        $term = $this->input->get('term', TRUE);
        
        if(empty($term)){
            echo json_encode([]);
            return;
        }
        
        try {
            // Ensure procedure_model is loaded
            if (!isset($this->procedure_model)) {
                $this->load->model('admin/procedure_model', 'procedure_model');
            }
            // Search procedures by CPT code or description
            $results = $this->procedure_model->search_procedures($term);
            
            $formatted = [];
            foreach($results as $row){
                $cpt = isset($row['cpt_code']) ? $row['cpt_code'] : '';
                $formatted[] = [
                    'id' => $row['id'],
                    'value' => $cpt . ' - ' . $row['description'],
                    'label' => $cpt . ' - ' . $row['description'],
                    'cpt_code' => $cpt,
                    'description' => $row['description']
                ];
            }
            
            echo json_encode($formatted);
        } catch (Exception $e) {
            log_message('error', 'Procedure search error: ' . $e->getMessage());
            echo json_encode([]);
        }
    }

    /**
     * Submit order for reading workflow
     */
    public function submit_for_reading()
    {
        try {
            $order_id = $this->input->post('order_id');
            $remarks = $this->input->post('remarks', TRUE);
            $user_id = $this->session->userdata('did');
            
            if(empty($order_id)) {
                echo json_encode(array('status' => '0', 'message' => 'Invalid order ID'));
                return;
            }
            
            // Get current order details
            $order = $this->orders_model->get_order_detail($order_id);
            if(empty($order)) {
                echo json_encode(array('status' => '0', 'message' => 'Order not found'));
                return;
            }
            
            // Update order status to "Submitted for Reading" (20)
            $update_result = $this->orders_model->update_order(
                array('status' => 20),
                array('id' => $order_id)
            );
            
            if(!$update_result) {
                echo json_encode(array('status' => '0', 'message' => 'Failed to update order status'));
                return;
            }
            
            // Log to order history
            $order_detail_after = $this->orders_model->get_order_detail($order_id);
            $session_data = $this->session->userdata();
            
            $history_data = array(
                'order_id' => $order_id,
                'action' => 'Submitted for Reading',
                'old_data' => serialize($order),
                'new_data' => serialize($order_detail_after),
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $user_id,
                'user_name' => isset($session_data['username']) ? $session_data['username'] : 'Unknown'
            );
            
            if(!empty($remarks)) {
                $history_data['remarks'] = $remarks;
            }
            
            $this->order_history_model->add_order_history($history_data);
            
            echo json_encode(array('status' => '1', 'message' => 'Order submitted for reading successfully'));
            
        } catch (Exception $e) {
            log_message('error', 'Submit for reading error: ' . $e->getMessage());
            echo json_encode(array('status' => '0', 'message' => 'An error occurred. Please try again.'));
        }
    }

    /**
     * Get status history timeline for an order
     */
    public function get_status_history($order_id = null)
    {
        try {
            if(empty($order_id)) {
                $order_id = $this->input->get('order_id');
            }
            
            if(empty($order_id)) {
                echo json_encode(array('status' => '0', 'message' => 'Invalid order ID'));
                return;
            }
            
            // Get order history
            $this->db->select('*');
            $this->db->from('tbl_order_history');
            $this->db->where('order_id', $order_id);
            $this->db->order_by('created_at', 'DESC');
            $history = $this->db->get()->result_array();
            
            // Calculate time spent in each status
            // DB timestamps are stored in UTC; convert to MST for display
            $tz_utc = new DateTimeZone('UTC');
            $tz_mst = new DateTimeZone('America/Denver');
            $now_ts  = time(); // always UTC-based Unix timestamp
            $timeline = array();
            for($i = 0; $i < count($history); $i++) {
                $current = $history[$i];

                // Parse stored UTC timestamp
                $current_dt = new DateTime($current['created_at'], $tz_utc);
                $current_ts = $current_dt->getTimestamp();

                // History is ordered DESC, so:
                // - Row 0 (most recent): time since this status until now
                // - Row i>0 (older): time from this event until the newer event (history[i-1])
                if($i === 0) {
                    $diff_minutes = round(($now_ts - $current_ts) / 60);
                } else {
                    $prev_dt = new DateTime($history[$i - 1]['created_at'], $tz_utc);
                    $prev_ts = $prev_dt->getTimestamp();
                    $diff_minutes = round(($prev_ts - $current_ts) / 60);
                }

                // Convert to MST for display
                $current_dt->setTimezone($tz_mst);
                $current['created_at_display'] = $current_dt->format('m/d/Y h:i:s A');
                $current['time_in_status'] = $diff_minutes;
                $timeline[] = $current;
            }
            
            echo json_encode(array('status' => '1', 'history' => $timeline));
            
        } catch (Exception $e) {
            log_message('error', 'Get status history error: ' . $e->getMessage());
            echo json_encode(array('status' => '0', 'message' => 'Failed to retrieve history'));
        }
    }

    /**
     * Update order status with history logging
     */
    public function update_status()
    {
        try {
            $order_id = $this->input->post('order_id');
            $new_status = $this->input->post('status');
            $remarks = $this->input->post('remarks', TRUE);
            $user_id = $this->session->userdata('did');
            
            if(empty($order_id) || !isset($new_status)) {
                echo json_encode(array('status' => '0', 'message' => 'Invalid parameters'));
                return;
            }
            
            // Get current order
            $order_old = $this->orders_model->get_order_detail($order_id);
            if(empty($order_old)) {
                echo json_encode(array('status' => '0', 'message' => 'Order not found'));
                return;
            }
            
            // Update order status
            $update_result = $this->orders_model->update_order(
                array('status' => $new_status),
                array('id' => $order_id)
            );
            
            if(!$update_result) {
                echo json_encode(array('status' => '0', 'message' => 'Failed to update status'));
                return;
            }
            
            // Get status name
            $status_info = get_order_status($new_status);
            $status_name = isset($status_info['text']) ? $status_info['text'] : 'Status ' . $new_status;
            
            // Log to order history
            $order_new = $this->orders_model->get_order_detail($order_id);
            $session_data = $this->session->userdata();
            
            $history_data = array(
                'order_id' => $order_id,
                'action' => 'Status changed to: ' . $status_name,
                'old_data' => serialize($order_old),
                'new_data' => serialize($order_new),
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $user_id,
                'user_name' => isset($session_data['username']) ? $session_data['username'] : 'Unknown'
            );
            
            if(!empty($remarks)) {
                $history_data['remarks'] = $remarks;
            }
            
            $this->order_history_model->add_order_history($history_data);
            
            echo json_encode(array('status' => '1', 'message' => 'Status updated successfully'));
            
        } catch (Exception $e) {
            log_message('error', 'Update status error: ' . $e->getMessage());
            echo json_encode(array('status' => '0', 'message' => 'An error occurred. Please try again.'));
        }
    }

    /**
     * Get time order has been in current status (in minutes)
     */
    public function get_time_in_current_status($order_id)
    {
        try {
            // Get the most recent history record
            $this->db->select('created_at');
            $this->db->from('tbl_order_history');
            $this->db->where('order_id', $order_id);
            $this->db->order_by('created_at', 'DESC');
            $this->db->limit(1);
            $latest = $this->db->get()->row_array();
            
            if($latest) {
                $changed_time = strtotime($latest['created_at']);
                $current_time = time();
                $minutes = round(($current_time - $changed_time) / 60);
                return $minutes;
            }
            
            return null;
            
        } catch (Exception $e) {
            log_message('error', 'Get time in current status error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Check and view PDF report from s:\mreports
     */
    public function view_report($order_id)
    {
        // Path to reports directory
        $reports_path = 's:\\mreports\\';
        $pdf_file = $reports_path . $order_id . '.pdf';
        
        // Check if file exists
        if (file_exists($pdf_file)) {
            // Serve the PDF file
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="report_' . $order_id . '.pdf"');
            header('Content-Length: ' . filesize($pdf_file));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            
            // Clear output buffer
            ob_clean();
            flush();
            
            // Output the file
            readfile($pdf_file);
            exit;
        } else {
            // Report not available - show popup and close window
            echo '<html><head><title>Report Not Available</title></head><body>';
            echo '<script type="text/javascript">';
            echo 'alert("No signed report is Available");';
            echo 'window.close();';
            echo 'if(!window.closed) { window.history.back(); }';
            echo '</script>';
            echo '<p>No signed report is available. <a href="javascript:history.back()">Go Back</a></p>';
            echo '</body></html>';
            exit;
        }
    }

    /**
     * Export orders as CSV with filtered parameters
     */
    public function export_orders()
    {
        // Clear any existing output buffers and headers
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        $user_id = $this->session->userdata('did');
        $user_info = !empty($user_id) ? $this->user_model->get_user_info($user_id) : null;
        $is_technologist = (!empty($user_info) && isset($user_info['role']) && (int)$user_info['role'] === 8);
        $tech_id = $is_technologist ? $user_id : null;

        // Read all advanced search params
        $time = $this->input->get('adv_time', true) ?: 'all';
        $order_types_raw = $this->input->get('adv_order_types', true) ?: '';
        $date_from_raw = trim($this->input->get('adv_date_from', true));
        $date_to_raw = trim($this->input->get('adv_date_to', true));

        // Convert mm/dd/yyyy to Y-m-d for DB query
        $date_from = '';
        $date_to = '';
        if (!empty($date_from_raw)) {
            $dt = DateTime::createFromFormat('m/d/Y', $date_from_raw);
            if ($dt) $date_from = $dt->format('Y-m-d');
        }
        if (!empty($date_to_raw)) {
            $dt = DateTime::createFromFormat('m/d/Y', $date_to_raw);
            if ($dt) $date_to = $dt->format('Y-m-d');
        }

        $filters = array(
            'search_name'   => trim($this->input->get('adv_search_name', true)),
            'patient_id'    => trim($this->input->get('adv_patient_id', true)),
            'search_dob'    => trim($this->input->get('adv_search_dob', true)),
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'date_type'     => $this->input->get('adv_date_type', true) ?: 'order',
            'facility_id'   => $this->input->get('adv_facility', true),
            'division'      => $this->input->get('adv_division', true),
            'order_types'   => array_filter(explode(',', $order_types_raw)),
            'state'         => $this->input->get('adv_state', true),
            'modality'      => $this->input->get('adv_modality', true),
            'status'        => $this->input->get('adv_status', true),
            'today_only'    => ($time === 'today'),
        );

        $orders = $this->orders_model->get_orders_filtered($filters, 'created_at desc', $tech_id);

        // Build lookup arrays
        $facilities_lookup = array();
        foreach ($this->data_cache->get_facilities() as $facility) {
            $facilities_lookup[$facility['id']] = $facility['facility_name'];
        }
        $procedure_lookup = array();
        foreach ($this->data_cache->get_procedures() as $proc) {
            $procedure_lookup[$proc['id']] = $proc['cpt_code'] . ' - ' . $proc['description'];
        }

        // Define CSV headers
        $headers = array(
            'Patient_MR',
            'Accession',
            'Order_Date',
            'Schedule_Date',
            'Last_Name',
            'First_Name',
            'Facility',
            'Clinician',
            'Modality',
            'Tech',
            'Procedure',
            'Status',
            'Primary_Insurance',
            'Total_number_of_procedures'
        );

        // Start building CSV with BOM for proper Excel encoding
        $csv_content = "\xEF\xBB\xBF"; // UTF-8 BOM
        $csv_content .= implode(',', $headers) . "\n";

        // Add data rows
        foreach ($orders as $row) {
            $order_status = intval($row['status']);
            $order_status_info = get_order_status($order_status);
            
            $date = !empty($row['created_at']) ? date('m/d/Y', strtotime($row['created_at'])) : '';
            $schedule_date = !empty($row['date_of_service']) ? date('m/d/Y', strtotime($row['date_of_service'])) : '';
            $facility_name = !empty($row['orderingentity']) && isset($facilities_lookup[$row['orderingentity']])
                ? $facilities_lookup[$row['orderingentity']] : '';
            $tech_name = !empty($row['dispatch_technologist']) ? $row['dispatch_technologist'] : '';
            
            // Get accession number
            $accession = !empty($row['fldacsno1']) ? $row['fldacsno1'] : '';
            
            // Get clinician name
            $clinician = !empty($row['servicedr']) ? $row['servicedr'] : '';
            
            // Get modality
            $modality = !empty($row['ptradio']) ? $row['ptradio'] : '';
            
            // Get primary insurance
            $primary_insurance = !empty($row['insurancecompany']) ? $row['insurancecompany'] : '';
            
            // Count total procedures
            $total_procedures = 0;
            if (!empty($row['procedurelist'])) {
                $proc_ids = json_decode($row['procedurelist'], true);
                if (is_array($proc_ids)) {
                    $total_procedures = count(array_filter($proc_ids));
                }
            }
            
            // Decode procedure list
            $procedure_display = '';
            if (!empty($row['procedurelist'])) {
                $proc_ids = json_decode($row['procedurelist'], true);
                if (is_array($proc_ids) && !empty($proc_ids)) {
                    $names = array();
                    foreach ($proc_ids as $pid) {
                        if (!empty($pid)) {
                            $names[] = isset($procedure_lookup[$pid]) ? $procedure_lookup[$pid] : 'ID: ' . $pid;
                        }
                    }
                    $procedure_display = implode('; ', $names);
                } else {
                    $procedure_display = $row['procedurelist'];
                }
            }

            // Build row data
            $row_data = array(
                $row['patientmr'],
                $accession,
                $date,
                $schedule_date,
                $row['lastname'],
                $row['firstname'],
                $facility_name,
                $clinician,
                $modality,
                $tech_name,
                $procedure_display,
                $order_status_info['text'],
                $primary_insurance,
                $total_procedures
            );

            // Add row to CSV with proper escaping
            $csv_content .= implode(',', array_map(function($val) {
                $val = (string)$val;
                // Escape quotes and wrap in quotes if contains delimiter, quotes, or newlines
                if (strpos($val, ',') !== false || strpos($val, '"') !== false || strpos($val, "\n") !== false) {
                    $val = '"' . str_replace('"', '""', $val) . '"';
                }
                return $val;
            }, $row_data)) . "\n";
        }

        // Send as CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="orders_export_' . date('Y-m-d_H-i-s') . '.csv"');
        header('Content-Length: ' . strlen($csv_content));
        header('Pragma: no-cache');
        header('Expires: 0');
        echo $csv_content;
        exit;
    }
}
?>
