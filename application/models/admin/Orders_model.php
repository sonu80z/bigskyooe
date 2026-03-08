<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/8/2019
 * Time: 12:24 PM
 */

class Orders_model extends CI_Model
{

    public function add_order($data) {
        $this->db->insert('tbl_orderdetail',$data);
        $order_id = $this->db->insert_id();
        return $order_id;
    }
    public function update_order($data, $where) {
        $this->db->where($where)->update('tbl_orderdetail',$data);
        return true;
    }
    
    /**
     * Batch update multiple orders at once (eliminates N+1 in mark_multiple_completed)
     * @param array $data Update data
     * @param array $ids Array of order IDs
     * @return bool
     */
    public function update_orders_batch($data, $ids) {
        if(empty($ids)) return false;
        $this->db->where_in('id', $ids);
        $this->db->update('tbl_orderdetail', $data);
        return true;
    }

    /**
     * get all orders - optimized to select only columns needed for list display
     * @return array
     */
    public function get_all_orders($order_by = ''){
        $cols = 'id, status, kind, is_canceled, created_at, date_of_service, patientmr, dob, '
              . 'lastname, firstname, orderingentity, orderedroom, dispatch_technologist, '
              . 'orderedby, procedurelist, attachment, orderedstate, ptradio';
        if($order_by ==''){
            $sql = "SELECT {$cols} FROM tbl_orderdetail";
        }else{
            $sql = "SELECT {$cols} FROM tbl_orderdetail WHERE 1=1 ORDER BY {$order_by}";
        }
        return $this->db->query($sql)->result_array();
    }

    /**
     * Batch fetch the latest history timestamp for each order in one query.
     * Eliminates the N+1 per-row query in list.php "Time in Status" column.
     * @param array $order_ids Array of order IDs
     * @return array keyed by order_id => latest created_at timestamp
     */
    public function get_latest_history_timestamps($order_ids = array()){
        if(empty($order_ids)) return array();
        
        // Use a single query with GROUP BY instead of N individual queries
        $ids = array_map('intval', $order_ids);
        $ids_str = implode(',', $ids);
        $sql = "SELECT order_id, MAX(created_at) as latest_at 
                FROM tbl_order_history 
                WHERE order_id IN ({$ids_str}) 
                GROUP BY order_id";
        $results = $this->db->query($sql)->result_array();
        
        $lookup = array();
        foreach($results as $row){
            $lookup[$row['order_id']] = $row['latest_at'];
        }
        return $lookup;
    }

    /**
     * Get orders with JOIN to facility/user to eliminate N+1 queries
     * Used by Dashboard and Order index for display
     * @param string $order_by
     * @param int|null $tech_id
     * @return array
     */
    public function get_orders_with_joins($order_by = 'o.created_at desc', $tech_id = null){
        $this->db->select('o.*, f.facility_name, CONCAT(u.firstname, " ", u.lastname) as creator_name');
        $this->db->from('tbl_orderdetail o');
        $this->db->join('tbl_facility f', 'f.id = o.orderingentity', 'left');
        $this->db->join('tbl_user u', 'u.id = o.order_creator', 'left');
        
        if(!empty($tech_id)){
            $this->db->where('o.dispatch_technologist_id', $tech_id);
        }
        
        if(!empty($order_by)){
            $this->db->order_by($order_by);
        }
        
        return $this->db->get()->result_array();
    }

    public function get_orders_filtered($filters, $order_by = 'status asc, id desc', $tech_id = null){
        $cols = 'id, status, kind, is_canceled, created_at, date_of_service, patientmr, dob, '
              . 'lastname, firstname, middlename, orderingentity, orderedroom, dispatch_technologist, '
              . 'orderedby, procedurelist, attachment, orderedstate, ptradio, fldacsno1, servicedr, insurancecompany';
        $this->db->select($cols);
        $this->db->from('tbl_orderdetail');

        if (!empty($tech_id)) {
            $this->db->where('dispatch_technologist_id', $tech_id);
        }

        // Restrict to today's orders when requested
        if (!empty($filters['today_only'])) {
            $this->db->where('date_of_service', date('Y-m-d'));
        }

        if (!empty($filters['search_value'])) {
            $search_value = $filters['search_value'];
            $search_type = isset($filters['search_type']) ? $filters['search_type'] : '';
            if ($search_type === 'firstname') {
                $this->db->like('firstname', $search_value);
            } elseif ($search_type === 'dob') {
                $this->db->like('dob', $search_value);
            } else {
                $this->db->like('lastname', $search_value);
            }
        }

        if (!empty($filters['date_value'])) {
            $date_type = isset($filters['date_type']) ? $filters['date_type'] : 'order';
            if ($date_type === 'exam') {
                $this->db->where('date_of_service', $filters['date_value']);
            } else {
                $this->db->where('created_at >=', $filters['date_value'] . ' 00:00:00');
                $this->db->where('created_at <=', $filters['date_value'] . ' 23:59:59');
            }
        }

        // Date range filter (from/to) for orders list page
        if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
            $date_type = isset($filters['date_type']) ? $filters['date_type'] : 'order';
            $col = ($date_type === 'exam') ? 'date_of_service' : 'created_at';
            if (!empty($filters['date_from'])) {
                $from = $filters['date_from'];
                $this->db->where($col . ' >=', ($col === 'created_at') ? $from . ' 00:00:00' : $from);
            }
            if (!empty($filters['date_to'])) {
                $to = $filters['date_to'];
                $this->db->where($col . ' <=', ($col === 'created_at') ? $to . ' 23:59:59' : $to);
            }
        }

        // Patient name search (searches both lastname and firstname)
        if (!empty($filters['search_name'])) {
            $name = trim($filters['search_name']);
            if (strpos($name, ',') !== false) {
                $parts = array_map('trim', explode(',', $name, 2));
                if (!empty($parts[0])) $this->db->like('lastname', $parts[0]);
                if (!empty($parts[1])) $this->db->like('firstname', $parts[1]);
            } else {
                $this->db->group_start();
                $this->db->like('lastname', $name);
                $this->db->or_like('firstname', $name);
                $this->db->group_end();
            }
        }

        // DOB search — input is mm/dd/yyyy, DB stores Y-m-d, so convert before LIKE
        if (!empty($filters['search_dob'])) {
            $dob_input = trim($filters['search_dob']);
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob_input)) {
                $dt = DateTime::createFromFormat('m/d/Y', $dob_input);
                $dob_search = $dt ? $dt->format('Y-m-d') : $dob_input;
            } else {
                $dob_search = $dob_input;
            }
            $this->db->like('dob', $dob_search);
        }

        if (!empty($filters['facility_id'])) {
            $this->db->where('orderingentity', $filters['facility_id']);
        }

        if (!empty($filters['division'])) {
            $this->db->where('division_id', $filters['division']);
        }

        if (!empty($filters['patient_id'])) {
            $this->db->like('patientmr', $filters['patient_id']);
        }

        // Additional dashboard filters
        if (!empty($filters['order_types']) && is_array($filters['order_types'])) {
            $this->db->where_in('kind', $filters['order_types']);
        }
        if (!empty($filters['state'])) {
            $this->db->where('orderedstate', $filters['state']);
        }
        if (!empty($filters['modality'])) {
            $this->db->where('ptradio', $filters['modality']);
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $this->db->where('status', $filters['status']);
        }

        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }

        try {
            return $this->db->get()->result_array();
        } catch (Exception $e) {
            log_message('error', 'get_orders_filtered query failed: ' . $e->getMessage());
            return array();
        }
    }

    public function get_search_result($filters){
        $this->db->from('tbl_patient');

        $lastname = isset($filters['lastname']) ? trim($filters['lastname']) : '';
        $dob = isset($filters['dob']) ? trim($filters['dob']) : '';
        $patientmr = isset($filters['patientmr']) ? trim($filters['patientmr']) : '';

        if ($lastname !== '') {
            $this->db->like('LAST_NAME', $lastname);
        }

        if ($patientmr !== '') {
            $this->db->like('PATIENT_MRN', $patientmr);
        }

        if ($dob !== '') {
            $dob_alt = '';
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
                $dt = DateTime::createFromFormat('Y-m-d', $dob);
                if ($dt) {
                    $dob_alt = $dt->format('m/d/Y');
                }
            } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob)) {
                $dt = DateTime::createFromFormat('m/d/Y', $dob);
                if ($dt) {
                    $dob_alt = $dt->format('Y-m-d');
                }
            }

            $this->db->group_start();
            $this->db->like('DOB', $dob);
            if (!empty($dob_alt) && $dob_alt !== $dob) {
                $this->db->or_like('DOB', $dob_alt);
            }
            $this->db->group_end();
        }

        return $this->db->get()->result_array();
    }

    public function get_orders_for_technologist($tech_id, $order_by = 'created_at desc'){
        $cols = 'id, status, kind, is_canceled, created_at, date_of_service, patientmr, dob, '
              . 'lastname, firstname, orderingentity, orderedroom, dispatch_technologist, '
              . 'orderedby, procedurelist, attachment, orderedstate, ptradio';
        $this->db->select($cols);
        $this->db->from('tbl_orderdetail');
        $this->db->where('dispatch_technologist_id', $tech_id);
        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        return $this->db->get()->result_array();
    }

    public function get_order_detail($order_id, $with_history = false){
        if ($order_id === null || $order_id === '' || !isset($order_id)) {
            return array();
        }

        $query = $this->db->get_where('tbl_orderdetail', array('id' => $order_id));
        $list = $query->result_array();
        if(!empty($list)) {
            $order_detail = $list[0];
            if($with_history){
                $order_history = $this->get_order_history($order_id);
                $order_detail['order_track_history'] = $order_history;
            }
        }else{
            $order_detail = array();
        }
        return $order_detail;
    }
    
    /**
     * Get multiple order details at once (eliminates N+1 in mark_multiple_completed)
     * @param array $order_ids
     * @return array keyed by order ID
     */
    public function get_orders_by_ids($order_ids){
        if(empty($order_ids)) return array();
        $this->db->where_in('id', $order_ids);
        $results = $this->db->get('tbl_orderdetail')->result_array();
        $keyed = array();
        foreach($results as $row){
            $keyed[$row['id']] = $row;
        }
        return $keyed;
    }

    public function get_order_history($order_id){
        $history = $this->db->from("tbl_order_history")->where(array("order_id"=>$order_id))->order_by('id','asc')->get()->result_array();
        if(!empty($history)){
            foreach($history as $key => $row){
                $history[$key]['old_data'] = (!empty($row['old_data']) ? @unserialize($row['old_data']) : array());
                $history[$key]['new_data'] = (!empty($row['new_data']) ? @unserialize($row['new_data']) : array());
            }
        }
        return $history;
    }

    public function add_order_note($data){
        $this->db->insert('tbl_order_notes',$data);
        $order_id = $this->db->insert_id();
        return $order_id;
    }

    public function get_order_notes($order_id){
        $list = $this->db->from("tbl_order_notes")->where(array("order_id"=>$order_id))->order_by('id','asc')->get()->result_array();
        return $list;
    }

    /**
     * FIX: Single query instead of double query for state lookup
     */
    public function get_state_name($fldSt) {
        $query = $this->db->get_where('tblstates', array('fldSt' => $fldSt));
        if($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function get_dr_name($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_user');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_entity_name($id) {
        $this->db->select('facility_name');
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_facility');
        if($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function get_todays_orders($order_by = 'status asc, id desc', $tech_id = null) {
        $today = date('Y-m-d');
        $cols = 'id, status, kind, is_canceled, created_at, date_of_service, patientmr, dob, '
              . 'lastname, firstname, orderingentity, orderedroom, dispatch_technologist, '
              . 'orderedby, procedurelist, attachment, orderedstate, ptradio';
        $this->db->select($cols);
        $this->db->from('tbl_orderdetail');
        $this->db->where('date_of_service', $today);
        if (!empty($tech_id)) {
            $this->db->where('dispatch_technologist_id', $tech_id);
        }
        if(!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        return $this->db->get()->result_array();
    }
}










