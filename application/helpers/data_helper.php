<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// -----------------------------------------------------------------------------
    function getGroupyName($id){
    	
    	$CI = & get_instance();
    	return $CI->db->get_where('ci_user_groups', array('id' => $id))->row_array()['group_name'];
    }

function generateNumericCode($limit = 10){
    $code = '';
    for($i = 0; $i < $limit; $i++) {
        $code .= mt_rand(0, 9);
    }
    return $code;
}

function get_data_field($data, $field, $default = ''){
    if(isset($data[$field])){
        return $data[$field];
    }else{
        return $default;
    }
}

function output_data($data, $message = ""){
    $result = array(
        'status'=>'1',
        'api_result'=>$data,
        'message'=>$message
    );
    echo json_encode($result); die;
}
function output_error($message = "", $data = array()){
    $result = array(
        'status'=>'0',
        'message'=>$message,
        'api_result'=>$data
    );
    echo json_encode($result); die;
}
function get_order_status($status = ''){
    $data_list = array(
        0=>array('text'=>'New', 'color'=>'#ffffff'),
        1=>array('text'=>'N-STAT', 'color'=>'#fff8e1'),
        2=>array('text'=>'N-STAT-EMR', 'color'=>'#f3eced'),
        3=>array('text'=>'N-ASAP', 'color'=>'#ffebee'),
        4=>array('text'=>'N-ASAP-EMR', 'color'=>'#ffcdd2'),
        5=>array('text'=>'N-Routine', 'color'=>'#ffd180'),
        6=>array('text'=>'N-Routine-EMR', 'color'=>'#e57373'),
        10=>array('text'=>'D-Stat', 'color'=>'#fce4ec'),
        11=>array('text'=>'D-ASAP', 'color'=>'#f8bbd0'),
        12=>array('text'=>'D-Routine', 'color'=>'#f48fb1'),
        15=>array('text'=>'order accepted', 'color'=>'#f3e5f5'),
        16=>array('text'=>'order declined', 'color'=>'#e1bee7'),
        18=>array('text'=>'Delayed', 'color'=>'#ce93d8'),
        20=>array('text'=>'inroute', 'color'=>'#ede7f6'),
        21=>array('text'=>'arrived on site', 'color'=>'#d1c4e9'),
        25=>array('text'=>'startProceedure', 'color'=>'#b39ddb'),
        26=>array('text'=>'endProceedure', 'color'=>'#9575cd'),
        30=>array('text'=>'left site', 'color'=>'#e8eaf6'),
        35=>array('text'=>'inPACS', 'color'=>'#c5cae9'),
        40=>array('text'=>'sent to Radiologist', 'color'=>'#e3f2fd'),
        41=>array('text'=>'Radiologist requires more info', 'color'=>'#bbdefb'),
        42=>array('text'=>'Rad Consult reqested', 'color'=>'#90caf9'),
        45=>array('text'=>'Prelim received', 'color'=>'#64b5f6'),
        46=>array('text'=>'results received', 'color'=>'#42a5f5'),
        50=>array('text'=>'Results sent to fac', 'color'=>'#e0f7fa'),
        51=>array('text'=>'Results sent to EMR', 'color'=>'#b2ebf2'),
        52=>array('text'=>'Results sent to OP', 'color'=>'#80deea'),
        53=>array('text'=>'Results sent to Other', 'color'=>'#4dd0e1'),
        60=>array('text'=>'coded', 'color'=>'#e0f2f1'),
        70=>array('text'=>'sent to billing', 'color'=>'#b2dfdb'),
        71=>array('text'=>'ack from Billing', 'color'=>'#e8f5e9'),
        100=>array('text'=>'Marked as EOS', 'color'=>'#c8e6c9'),
        999=>array('text'=>'Cancelled', 'color'=>'#e0e0e0')
    );
    if($status != ''){
        $status = intval($status);
        if(isset($data_list[$status])){
            return $data_list[$status];
        }
    }
    return array('text'=>'', 'color'=>'#ffffff');
}

function generate_patient_mr()
{
    $CI = &get_instance();
    $current_year = date('Y');
    $prefix = 'BSI' . $current_year;
    
    // Get the highest sequence number for this year
    $result = $CI->db->select('patientmr')
                     ->like('patientmr', $prefix, 'after')
                     ->order_by('id', 'DESC')
                     ->limit(1)
                     ->get('tbl_orderdetail')
                     ->row_array();
    
    $next_sequence = 1;
    if (!empty($result) && !empty($result['patientmr'])) {
        // Extract the sequence number from the last MR (e.g., BSI20260001 -> 1)
        $last_mr = $result['patientmr'];
        $sequence_str = substr($last_mr, strlen($prefix));
        $next_sequence = intval($sequence_str) + 1;
    }
    
    // Format with leading zeros (4 digits)
    $new_mr = $prefix . str_pad($next_sequence, 4, '0', STR_PAD_LEFT);
    
    return $new_mr;
}



