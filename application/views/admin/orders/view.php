<?php
$order_detail = (isset($order) ? $order : array());
$prefill = (isset($prefill) && is_array($prefill)) ? $prefill : array();

function get_order_value($order_detail, $prefill, $key, $default = '') {
    if (!empty($order_detail)) {
        return get_data_field($order_detail, $key, $default);
    }
    return isset($prefill[$key]) ? $prefill[$key] : $default;
}
$submit_url = base_url('admin/order/create');
if(!empty($order_detail)){
    $submit_url = base_url('admin/order/update/'.$order_detail['id']);
}
?>
<!-- inputMask loaded in layout.php -->

<style>
    .visibility-hidden{
        visibility: hidden;
        height: 0 !important;
        width: 0;
        opacity: 0;
        position: fixed;
        left: 0;
        top: 0;
    }
    .box{
        margin-bottom: 0;
    }
    .ao_section{
        background:none;
    }
    .table-bordered, .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td{
        border: 1px solid #d2d6de;
    }
    .required-item .control-label{
        color: inherit !important;
    }
    .btn-del-procedure{
        display: none;
    }
    .my-form-body .form-control {
        background: #fff !important;
    }
    .my-form-body select {
        /* for Firefox */
        -moz-appearance: none;
        /* for Chrome */
        -webkit-appearance: none;
    }
    .my-form-body select::-ms-expand {
        display: none;
    }
    button.btn.clear{
        display: none;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(!empty($order_detail)): ?>
    <!-- Edit History Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div class="row">
                        <?php if(!empty($order_detail['created_at'])): ?>
                        <div class="col-sm-4">
                            <strong><i class="fa fa-calendar"></i> Created:</strong> 
                            <?php echo date('m/d/Y h:i A', strtotime($order_detail['created_at'])); ?>
                            <?php if(!empty($order_detail['creator_name'])): ?>
                                <br><small>by <?php echo htmlspecialchars($order_detail['creator_name']); ?></small>
                            <?php elseif(!empty($order_detail['order_creator'])): ?>
                                <br><small>by User ID: <?php echo htmlspecialchars($order_detail['order_creator']); ?></small>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($order_detail['updated_at'])): ?>
                        <div class="col-sm-4">
                            <strong><i class="fa fa-edit"></i> Last Updated:</strong> 
                            <?php echo date('m/d/Y h:i A', strtotime($order_detail['updated_at'])); ?>
                            <?php if(!empty($order_detail['editor_name'])): ?>
                                <br><small>by <?php echo htmlspecialchars($order_detail['editor_name']); ?></small>
                            <?php elseif(!empty($order_detail['order_editor'])): ?>
                                <br><small>by User ID: <?php echo htmlspecialchars($order_detail['order_editor']); ?></small>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($order_detail['id'])): ?>
                        <div class="col-sm-4">
                            <strong><i class="fa fa-barcode"></i> Order ID:</strong> 
                            <?php echo htmlspecialchars($order_detail['id']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="box-body my-form-body ao_cnt_dv" style="padding: 0 20px">
                <?php echo form_open($submit_url, 'class="form-horizontal", style="margin-top:0" autocomplete="off"');  ?>
                <div class="form-group visibility-hidden" style="margin:0">
                    <div class="col-md-12">
                        <?php
                        $kind_list = array(
                            '1'=>'Nursing, Rehab, and Assisted Living Facilities',
                            '2'=>'Primary Care Clinics/Private Clinics/Chiropractors/Physical Therapists',
                            '3'=>'Home Bound'
                        );
                        $kind_value = !empty($order_detail) ? get_data_field($order_detail, 'kind', '1') : '1';
                        $kind_label = isset($kind_list[$kind_value]) ? $kind_list[$kind_value] : $kind_list['1'];
                        ?>
                        <input type="hidden" name="ao_kind" value="<?php echo htmlspecialchars($kind_value, ENT_QUOTES, 'UTF-8'); ?>" />
                        <p class="form-control-static" style="margin-top: 7px;">
                            <strong>Order Type:</strong> <?php echo htmlspecialchars($kind_label, ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                </div>
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Last Name *</label>
                            <input type="text" name="ao_last_name" class="form-control" value="<?php echo get_data_field($order_detail, 'lastname'); ?>" required />
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">First Name *</label>
                            <input type="text" name="ao_first_name" class="form-control" value="<?php echo get_data_field($order_detail, 'firstname'); ?>" required />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Middle Name</label>
                            <input type="text" name="ao_middle_name" class="form-control" value="<?php echo get_data_field($order_detail, 'middlename'); ?>"/>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Suffix (Jr, Sr, II)</label>
                            <input type="text" name="ao_suffix_name" class="form-control" value="<?php echo get_data_field($order_detail, 'suffixname'); ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Patient MR *</label>
                            <input type="text" name="ao_patient_mr" class="form-control" value="<?php echo get_data_field($order_detail, 'patientmr', substr(time(), 0, 7).generateNumericCode(11-7)); ?>" required />
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">DOB (MM/DD/YYYY) *</label>
                            <?php
                            $dob = get_data_field($order_detail, 'dob');
                            if(!empty($dob)){
                                $dtime = DateTime::createFromFormat("Y-m-d H:i:s", $dob." 00:00:00");
                                $timestamp = $dtime->getTimestamp();
                                $dob = date("m/d/Y", $timestamp);
                            }
                            ?>
                            <input name="ao_dom" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" inputmode="numeric" value="<?php echo $dob; ?>" required>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Patient SSN</label>
                            <input type="text" name="ao_patient_ssn" class="form-control" value="<?php echo get_data_field($order_detail, 'patientssn'); ?>"/>
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Sex *</label>
                            <select name="ao_sex" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                $data_list = array(
                                    'M'=>'Male',
                                    'F'=>'Female',
                                    'Other'=>'Other'
                                );
                                foreach($data_list as $key => $text){
                                    $selected = "";
                                    if(isset($order_detail['gender']) && $order_detail['gender'] == $key){
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Patient Address Information -->
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Patient Address</label>
                            <input type="text" name="ao_patient_address" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientaddress'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Patient City</label>
                            <input type="text" name="ao_patient_city" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientcity'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Patient State</label>
                            <input type="text" name="ao_patient_state" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientstate'); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Patient Zip</label>
                            <input type="text" name="ao_patient_zip" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientzip'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Patient Phone</label>
                            <input type="text" name="ao_patient_phone" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientphone'); ?>" />
                        </div>
                    </div>
                </div>
                <div class="ao_section" id="order_entity">
                    <div class="form-group">
                        <div class="col-sm-6 required-item">
                            <label class="control-label">Ordering Entity *</label>
                            <select name="ao_ordering_facility" class="form-control" required>
                                <option value="">Select</option>
                                <?php
                                $orderingentity = get_data_field($order_detail, 'orderingentity');
                                foreach ( $facilities as $row ) {
                                    $selected = "";
                                    if(isset($order_detail['orderingentity']) && $order_detail['orderingentity'] == $row["id"]) {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$row["id"].'" '.$selected.'>'.$row["facility_name"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Ordered By</label>
                            <input type="text" name="ao_ordered_by" class="form-control" value="<?php echo get_data_field($order_detail, 'orderedby'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">ASR </label>
                            <!--<input type="checkbox" name="ao_ordered_asr1" id="ao_asr1" />&nbsp;<label class="control-label" for="ao_asr1">STAT</label>&nbsp;&nbsp;
                            <input type="checkbox" name="ao_ordered_asr2" id="ao_asr2" />&nbsp;<label class="control-label" for="ao_asr2">ASAP</label>&nbsp;&nbsp;
                            <input type="checkbox" name="ao_ordered_asr3" id="ao_asr3" />&nbsp;<label class="control-label" for="ao_asr3">ROUTINE</label>&nbsp;&nbsp;-->
                            <select name="ao_ordered_asr" class="form-control">
                                <option value="">Select</option>
                                <?php
                                $data_list = array(
                                    'STAT'=>'STAT',
                                    'ASAP'=>'ASAP',
                                    'ROUTINE'=>'ROUTINE'
                                );
                                foreach($data_list as $key => $text){
                                    $selected = "";
                                    if(isset($order_detail['asr']) && $order_detail['asr'] == $key) {
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Station</label>
                            <select name="ao_ordered_station" class="form-control">
                                <option value="0">Select</option>
                                <?php
                                $data_list = array(
                                    'first'=>'first',
                                    'second'=>'second',
                                    'third'=>'third'
                                );
                                foreach($data_list as $key => $text) {
                                    $selected = "";
                                    if(isset($order_detail['orderedstation']) && $order_detail['orderedstation'] == $key){
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Room </label>
                            <input type="text" name="ao_ordered_room" class="form-control" value="<?php echo get_data_field($order_detail, 'orderedroom'); ?>" />
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">City</label>
                            <input type="text" name="ao_ordered_city" class="form-control" id="ao_ordered_city" autocomplete="off" value="<?php echo get_data_field($order_detail, 'orderedcity'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Address</label>
                            <input type="text" name="ao_ordered_address" class="form-control" autocomplete="off" value="<?php echo get_data_field($order_detail, 'orderedaddress'); ?>">
                        </div>
                        <div class="col-sm-6 ">
                            <div style="margin-top: 26px">
                                <button type="button" class="btn btn-xs btn-default clear"><i class="fa fa-refresh"></i> Clear Address</button>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">State</label>
                            <input type="text" name="ao_ordered_state" class="form-control" value="<?php echo get_data_field($order_detail, 'orderedstate'); ?>"/>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Zip</label>
                            <input type="text" name="ao_ordered_zip" class="form-control" value="<?php echo get_data_field($order_detail, 'orderedzip'); ?>"/>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Phone</label>
                            <input type="text" name="ao_ordered_phone" class="form-control" value="<?php echo get_data_field($order_detail, 'orderedphone'); ?>"/>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Fax</label>
                            <input type="text" name="ao_ordered_fax" class="form-control" value="<?php echo get_data_field($order_detail, 'orderedfax'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="ao_section" id="service_location" style="display: none">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Private Home</label>
                            <select name="ao_service_facility" class="form-control">
                                <option value="0">Select</option>
                                <?php
                                foreach ( $facilities as $row ) {
                                    $selected = "";
                                    if(isset($order_detail['orderedstation']) && $order_detail['orderedstation'] == $row["id"]){
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$row["id"].'" '.$selected.'>'.$row["facility_name"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Order Status</label>
                            <select name="ao_service_status" class="form-control">
                                <option value="0">Select</option>
                                <?php
                                $data_list = array(
                                    '1'=>'Ready',
                                    '2'=>'TBD',
                                    '3'=>'Address Verified'
                                );
                                foreach($data_list as $key => $text) {
                                    $selected = "";
                                    if(isset($order_detail['servicestatus']) && $order_detail['servicestatus'] == $key){
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Station</label>
                            <select name="ao_service_station" class="form-control">
                                <option value="0">Select</option>
                                <?php
                                $data_list = array(
                                    'first'=>'first',
                                    'second'=>'second',
                                    'third'=>'third'
                                );
                                foreach($data_list as $key => $text) {
                                    $selected = "";
                                    if(isset($order_detail['servicestation']) && $order_detail['servicestation'] == $key){
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Room </label>
                            <input type="text" name="ao_service_room" class="form-control" value="<?php echo get_data_field($order_detail, 'serviceroom'); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Address</label>
                            <input type="text" name="ao_service_address" class="form-control" value="<?php echo get_data_field($order_detail, 'serviceaddress'); ?>" />
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">City</label>
                            <input type="text" name="ao_service_city" class="form-control" value="<?php echo get_data_field($order_detail, 'servicecity'); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">State</label>
                            <input type="text" name="ao_service_state" class="form-control" value="<?php echo get_data_field($order_detail, 'servicestate'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Zip</label>
                            <input type="text" name="ao_service_zip" class="form-control" value="<?php echo get_data_field($order_detail, 'servicezip'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Phone</label>
                            <input type="text" name="ao_service_phone" class="form-control" value="<?php echo get_data_field($order_detail, 'servicephone'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Fax</label>
                            <input type="text" name="ao_service_fax" class="form-control" value="<?php echo get_data_field($order_detail, 'servicefax'); ?>" />
                        </div>
                    </div>
                </div>
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Ordering Dr *</label>
                            <select name="ao_service_dr" class="form-control" required>
                                <option value="">Select</option>
                                <option value="1111" style="color: blue;" >Not In List</option>
                                <?php
                                foreach ( $orderingphysician as $row ) {
                                    $selected = "";
                                    if(isset($order_detail['servicedr']) && $order_detail['servicedr'] == $row['id']) {
                                        $selected = "selected";
                                    }
                                    echo '<option '.$selected.' value="'.$row["id"].'" data-phone="'.$row["phone"].'" data-fax="'.$row["fax"].'" data-NPI="'.$row["NPI"].'">'.$row["lastname"].' '.$row["firstname"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Phone</label>
                            <input type="text" name="ao_dr_phone" class="form-control" value="<?php echo get_data_field($order_detail, 'drphone'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Fax</label>
                            <input type="text" name="ao_dr_fax" class="form-control" value="<?php echo get_data_field($order_detail, 'drfax'); ?>" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">NPI</label>
                            <input type="text" name="ao_dr_NPI" class="form-control" value="<?php echo get_data_field($order_detail, 'drnpi'); ?>" />
                        </div>
                    </div>
                </div>
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Date of Service *</label>
                            <?php
                            $date_of_service = get_data_field($order_detail, 'date_of_service');
                            if(!empty($date_of_service)){
                                $dtime = DateTime::createFromFormat("Y-m-d", $date_of_service);
                                if($dtime){
                                    $date_of_service = $dtime->format('m/d/Y');
                                }
                            } else {
                                $date_of_service = '';
                            }
                            ?>
                            <input name="ao_date_of_service" class="form-control" value="<?php echo $date_of_service; ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Procedure Type : </label>
                            <?php
                            $data_list = array(
                                'X-RAY'=>'X-RAY',
                                'US'=>'US',
                                'EKG'=>'EKG',
                                'ECHO'=>'ECHO',
                                'LINE PLACEMENT'=>'LINE PLACEMENT'
                            );
                            foreach($data_list as $key => $text) {
                                $selected = "";
                                if(isset($order_detail['ptradio']) && $order_detail['ptradio'] == $key){
                                    ?>
                                    <span> <?php echo $data_list[$key]; ?></span>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="col-sm-12">
                            <label class="control-label g_txt_left">If you need to place an order for this patient for another procedure type,
                                please complete this order and select the add & create new order for same patient button at the bottom of the screen.
                            </label>
                        </div>
                    </div>

                    <?php

                        $procedurelist = json_decode($order_detail['procedurelist'], true);
                        $plrn = json_decode($order_detail['plrn'], true);
                        $symptom1 = json_decode($order_detail['symptom1'], true);
                        $symptom2 = json_decode($order_detail['symptom2'], true);
                        $symptom3 = json_decode($order_detail['symptom3'], true);
                        
                        // Ensure arrays are properly initialized
                        if (!is_array($procedurelist)) $procedurelist = array();
                        if (!is_array($plrn)) $plrn = array();
                        if (!is_array($symptom1)) $symptom1 = array();
                        if (!is_array($symptom2)) $symptom2 = array();
                        if (!is_array($symptom3)) $symptom3 = array();
                        
                        $i = 0;
                        foreach($procedurelist as $kk => $procedureValue){
                            $plrnValue = isset($plrn[$kk]) ? $plrn[$kk] : '';
                            $symptom1Value = isset($symptom1[$kk]) ? $symptom1[$kk] : '';
                            $symptom2Value = isset($symptom2[$kk]) ? $symptom2[$kk] : '';
                            $symptom3Value = isset($symptom3[$kk]) ? $symptom3[$kk] : '';
                            ?>
                            <div class="form-group">
                                <div class="col-sm-2 required-item">
                                    <label class="control-label">Procedure #<?php echo $i+1; ?> *</label>
                                    <select name="ao_procedure_list[<?php echo $i; ?>]" class="form-control ao_procedure_list" required>
                                        <option value="">Select</option>
                                        <option value="1111" style="color: blue;" >Not In List</option>
                                        <?php
                                        foreach ( $procedures as $row ) {
                                            $selected = "";
                                            if($procedureValue == $row['id']) {
                                                $selected = "selected";
                                            }
                                            echo '<option value="'.$row["id"].'" '.$selected.'>'.$row["description"].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label">&nbsp;</label> <br>
                                    <?php
                                    $data_list = array(
                                        'L'=>'L',
                                        'R'=>'R',
                                        'BI'=>'BI',
                                        'CD Requested'=>'CD Requested'
                                    );
                                    $indexer = 1;
                                    foreach($data_list as $key => $text) {
                                        $selected = "";
                                        if($plrnValue == $key){
                                            $selected = "checked";
                                        }
                                        ?>
                                        &nbsp;&nbsp;&nbsp;<label><input type="radio" class="ao_plrn" name="ao_plrn[<?php echo $i; ?>]" value="<?php echo $key; ?>" <?php echo $selected; ?> readonly disabled/>&nbsp; <?php echo $text; ?></label>
                                        <?php
                                        $indexer++;
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Symptom</label>
                                    <input type="text" class="form-control" value="<?php echo $symptom1Value; ?>" readonly />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control" value="<?php echo $symptom2Value; ?>" readonly />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">&nbsp;</label>
                                    <input type="text" class="form-control" value="<?php echo $symptom3Value; ?>" readonly />
                                </div>
                                <div class="col-sm-1">
                                    <label class="control-label">&nbsp;</label>
                                    <div class="block">
                                        <a href="javascript:void(0);" class="btn-del-procedure" title="Remove"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }

                    ?>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Order Notes</label>
                            <input type="text" name="ao_reason_for_exam" class="form-control" value="<?php echo get_data_field($order_detail, 'exam'); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">History</label>
                            <input type="text" name="ao_history" class="form-control" value="<?php echo get_data_field($order_detail, 'history'); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Medical Necessity Statement</label>
                            <div class="alert alert-info" style="margin-top: 10px;">
                                <strong>Note:</strong> This patient would find it physically and/or psychologically taxing 
                                because of advanced age and/or physical limitations to receive an X-RAY outside this location. 
                                This test is medically necessary for the diagnosis and treatment of this patient.
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Reason Exams are being done portably</label>
                            <select name="ao_portable_reason" class="form-control ao_portable_reason">
                                <option value="">Select</option>
                                <?php
                                foreach($reason_photoble as $key => $info) {
                                    $selected = "";
                                    if(isset($order_detail['reason']) && $order_detail['reason'] == $info['value']){
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $info['value'];?>" <?php echo $selected; ?>><?php echo $info['value'];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Insurance Section -->
                <div class="box box-primary ao_box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Insurance</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body ao_section" style="display: block;">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label g_txt_left">Is this Resident currently a Medicare Skilled PPS, or Part A Patient? OR ARE YOU TAKING MEDICARE NOTES ON THIS PATIENT?</label>
                                <?php
                                $ioa_map = array('1'=>'Yes','2'=>'No');
                                $ioa_val = isset($order_detail['ioa']) ? $order_detail['ioa'] : '';
                                ?>
                                <p class="form-control-static"><?php echo isset($ioa_map[$ioa_val]) ? $ioa_map[$ioa_val] : '-'; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12"><h4>Primary Insurance</h4></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'insurancecompany')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'policy')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'group')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'contract')) ?: '-'; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12"><h4>Secondary Insurance</h4></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'insurancecompany2')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'policy2')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'group2')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'contract2')) ?: '-'; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12"><h4>Tertiary Insurance</h4></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'insurancecompany3')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'policy3')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'group3')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'contract3')) ?: '-'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Insurance Section -->

                <!-- Responsible Party Section -->
                <div class="box box-primary ao_box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Responsible Party</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body ao_section" style="display: block;">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Responsible Party</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'responsible_party')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Relationship</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'relationship')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #1</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'address1')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #2</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'address2')) ?: '-'; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Phone #:</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'partyphone')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">City</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'partycity')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">State</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'partystate')) ?: '-'; ?></p>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Zip</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'partyzip')) ?: '-'; ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Electronic Signature</label>
                                <div>
                                    <?php if (!empty($order_detail['electronic_signature'])): ?>
                                        <span style="color:green"><i class="fa fa-check"></i> Signed</span>
                                    <?php else: ?>
                                        <span style="color:red"><i class="fa fa-times"></i> Not Signed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Responsible Party Section -->

                <!-- Medical Necessity Statement -->
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label" style="text-align:center; display:block;">
                                This Patient would find it physically and/or psychologically taxing because of advanced age and/or physical limitations to
                                receive an X-Ray, Ultrasound, ECHO or EKG outside this location.
                                This test is medically necessary for the diagnosis and treatment of this patient.
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Dispatch Section -->
                <div class="box box-primary ao_box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dispatch</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body ao_section" style="display: block;">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Dispatch Date/Time</label>
                                <p class="form-control-static"><?php
                                    if(!empty($order_detail['dispatch_datetime'])) {
                                        echo date('m/d/Y H:i', $order_detail['dispatch_datetime']);
                                    } else {
                                        echo '-';
                                    }
                                ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Technologist</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(get_data_field($order_detail, 'dispatch_technologist')) ?: '-'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Dispatch Section -->

                <?php
                $attachments = array();
                if (!empty($order_detail['attachment'])) {
                    $decoded = json_decode($order_detail['attachment'], true);
                    if (is_array($decoded)) {
                        $attachments = $decoded;
                    } else {
                        $attachments[] = array(
                            'type' => 'Legacy Attachment',
                            'file' => $order_detail['attachment']
                        );
                    }
                }
                ?>
                <?php if(!empty($attachments)): ?>
                <div class="box box-primary ao_box">
                    <div class="box-body ao_section" style="display: block;">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label">Order Attachments</label>
                                <ul class="list-unstyled" style="margin-top: 8px;">
                                    <?php foreach ($attachments as $att):
                                        $att_type = isset($att['type']) ? $att['type'] : 'Document';
                                        $att_file = isset($att['file']) ? $att['file'] : '';
                                        if ($att_file === '') { continue; }
                                    ?>
                                        <li style="margin-bottom: 6px;">
                                            <span class="label label-default"><?php echo htmlspecialchars($att_type, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <a href="<?php echo base_url('uploads/order_attachments/'.$att_file); ?>" target="_blank" class="btn btn-xs btn-primary" style="margin-left: 8px;">
                                                <i class="fa fa-file-pdf-o"></i> View PDF
                                            </a>
                                            <span class="text-muted" style="margin-left: 8px;"><?php echo htmlspecialchars($att_file, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php echo form_close( ); ?>
            </div>
        </div>
    </div>

    <?php
    if(isset($order_detail) && isset($order_detail['order_track_history'])){
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-body with-border" style="margin-bottom: 0; box-shadow: none">
                    <div class="col-md-6 a_page_top_title" style="font-size: 22px">
                        Order History
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box-body my-form-body ao_cnt_dv" style="padding: 0 20px">
                    <table class="table table-bordered" id="order-history-table" width="100%">
                        <thead>
                        <tr>
                            <th width="30%">Date</th>
                            <th width="30%">Action</th>
                            <th>User</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $order_track_history = $order_detail['order_track_history'];
                        foreach($order_track_history as $key => $history_info){
                            ?>
                            <tr>
                                <td><?php echo $history_info['created_at']; ?></td>
                                <td><?php echo ucfirst($history_info['action']); ?></td>
                                <td><?php echo $history_info['user_name']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</section>

<!-- DataTables loaded in layout.php -->

<script>
    jQuery('#order-history-table').DataTable(
        {
            'responsive': true
        }
    );
</script>

<script>
    /*for service dr part*/
    var user_dlg;
    $(document).ready(function(){
        $("select[name='ao_service_dr']").change(function () {
            var ordering_physician = jQuery(this).val();
            console.log("ordering_physician:", ordering_physician);
            if (ordering_physician == 1111) {
                var html = $(".add-user-dlg-container").html();
                user_dlg = show_dialog("Add User", html, "xlarge", false);
                setTimeout(function(){
                    // multi select
                    $(".jconfirm select.a_u_a_facility").selectpicker();
                    $(".jconfirm select.a_u_a_permitted_state").selectpicker();

                    var phones = [{ "mask": "(###) ###-####"}];
                    $('.jconfirm .a_u_a_mobile_no').inputmask({
                        mask: phones,
                        greedy: false,
                        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
                    $('.jconfirm .a_u_a_main_mobile_no').inputmask({
                        mask: phones,
                        greedy: false,
                        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
                }, 200);
            }
        });
        $("body").on("click", ".btn-close-user-dlg", function(){
            var cur_service_dr = $("select[name='ao_service_dr']").val();
            if(cur_service_dr == 1111){
                $("select[name='ao_service_dr']").val("");
            }
            user_dlg.close();
        });
        $("body").on("change", ".jconfirm .a_u_a_trackgps", function () {
            if (this.checked) {
                console.log('here1');
                $('#input-deviceid').modal('show');
            }
        });
        $("body").on("change", ".jconfirm .a_u_a_dispatch", function () {
            if (this.checked) {
                $('.jconfirm .permitted_state').css("display", "block");
                console.log('checked!');
            } else {
                $('.jconfirm .permitted_state').css("display", "none");
            }
        });
        $("body").on("click", ".jconfirm .a_add_user_btn", function () {
            var add_user_dlg = $(this).closest(".add-user-dlg");
            var required_fields = [
                {
                    name:"a_u_a_firstname",
                    msg:"First name is required"
                },
                {
                    name:"a_u_a_lastname",
                    msg:"Last name is required"
                },
                {
                    name:"a_u_a_username",
                    msg:"User name is required"
                },
                {
                    name:"a_u_a_email",
                    msg:"Email is required"
                },
                {
                    name:"a_u_a_npi",
                    msg:"Physician's NPI number is required"
                },
                {
                    name:"a_u_a_main_mobile_no",
                    msg:"Main mobile no is required"
                },
                {
                    name:"a_u_a_mobile_no",
                    msg:"Mobile no is required"
                },
                {
                    name:"a_u_a_fax",
                    msg:"Fax is required"
                },
                {
                    name:"a_u_a_password",
                    msg:"Password is required"
                },
                {
                    name:"a_u_a_rpassword",
                    msg:"Retype password is required"
                }
            ];
            for(var i=0; i<required_fields.length; i++){
                var field = required_fields[i];
                if(add_user_dlg.find("[name='"+field['name']+"']").val() == ""){
                    add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                    add_user_dlg.find(".a_admin_add_alert").children("div").html(field['msg']);
                    return false;
                }
            }

            // firstly confirm whether username is duplicate or not
            var username = add_user_dlg.find("[name='a_u_a_username']").val();
            console.log(username);
            var params = {
                "username" : username
            };
            var url = BASE_URL + 'admin/users/confirm_admin_username';
            jQuery.post(url, params, function(res) {
                console.log("res", res);
                res = JSON.parse(res);
                if(res.status == "1") {
                    add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                    add_user_dlg.find(".a_admin_add_alert").children("div").html("Same username already exist");
                    add_user_dlg.find(".a_u_a_username").val("").focus();
                    return false;
                } else {
                    if (add_user_dlg.find(".a_u_a_password").val() != add_user_dlg.find(".a_u_a_rpassword").val() ) {
                        add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                        add_user_dlg.find(".a_admin_add_alert").children("div").html("Password do not match");
                        add_user_dlg.find(".a_u_a_rpassword").val("").focus();
                        return false;
                    }
                    add_user_dlg.find(".a_u_a_deviceid").val($("#a_u_a_deviceid").val());
                    var form = add_user_dlg.find("form");
                    $.ajax({
                        type: "POST",
                        url: form.attr("action"),
                        data: form.serialize(), // serializes the form's elements.
                        success: function(res)
                        {
                            console.log("data:", res); // show response from the php script.
                            var obj = JSON.parse(res);
                            if(obj.status== 1) {
                                console.log("obj:", obj);
                                var data = obj.data;
                                var html = '<option value="'+data.id+'" data-phone="'+data.phone+'" data-fax="'+data.fax+'" data-NPI="'+data.NPI+'">'+data.firstname+' '+data.lastname+'</option>';
                                console.log("html", html);
                                $("select[name='ao_service_dr']").append(html);
                                $("select[name='ao_service_dr']").val(data.id).change();
                                user_dlg.close();
                            }
                        }
                    });
                }
            });
        });

        //test
        //$("select[name='ao_service_dr']").val(1111).change();
    });
</script>

<?php
if(!empty($order_detail)){
    ?>
    <script>
        $(document).ready(function(){
            setTimeout(function(){
                $("select[name='ao_kind']").change();
                jQuery("select[name='ao_ordering_facility']").trigger('change');
                $("select[name='ao_service_facility']").change();

                $(".ao_procedure_list").each(function(){
                    $(this).trigger('change');
                });
            }, 50);
            setTimeout(function(){
                $(".my-form-body .form-control").attr('readonly',true).attr("disabled", "disabled");
                $(".my-form-body input").attr('readonly',true).attr("disabled", "disabled");
            }, 200);
        });

        function station_loaded_callback(){
            if($("select[name='ao_ordered_station']").attr('data-loaded') != 1){
                $("select[name='ao_ordered_station']").val('<?php echo get_data_field($order_detail, 'orderedstation'); ?>');
                $("select[name='ao_ordered_station']").attr('data-loaded', 1)
            }
            if($("select[name='ao_service_station']").attr('data-loaded') != 1){
                $("select[name='ao_service_station']").val('<?php echo get_data_field($order_detail, 'servicestation'); ?>');
                $("select[name='ao_service_station']").attr('data-loaded', 1)
            }
        }

        function procedure_updated_callback(ths){
            if(ths.closest(".form-group").find("select.ao_symptom_1").attr('data-loaded') != 1){
                ths.closest(".form-group").find("select.ao_symptom_1").val( ths.closest(".form-group").find("select.ao_symptom_1").attr('data-val'));
                ths.closest(".form-group").find("select.ao_symptom_1").attr('data-loaded', 1);
            }
            if(ths.closest(".form-group").find("select.ao_symptom_2").attr('data-loaded') != 1){
                ths.closest(".form-group").find("select.ao_symptom_2").val( ths.closest(".form-group").find("select.ao_symptom_2").attr('data-val'));
                ths.closest(".form-group").find("select.ao_symptom_2").attr('data-loaded', 1);
            }
            if(ths.closest(".form-group").find("select.ao_symptom_3").attr('data-loaded') != 1){
                ths.closest(".form-group").find("select.ao_symptom_3").val( ths.closest(".form-group").find("select.ao_symptom_3").attr('data-val'));
                ths.closest(".form-group").find("select.ao_symptom_3").attr('data-loaded', 1);
            }
        }
    </script>
    <?php
}
?>
