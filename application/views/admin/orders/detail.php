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
.ao_box .bootstrap-select > .dropdown-toggle {
    height: 34px;
    padding: 6px 12px;
    line-height: 1.42857143;
}
.insurance-company-dv .form-control{
    padding: 0px 0px !important;
}
.insurance-company-dv  .dropdown-toggle {
    height: 26px !important;
    padding: 0px 6px !important;
}
.insurance-company-dv  .dropdown-toggle:focus{
    outline: none !important;
}
</style>
<script>
// Room field is not mandatory
$(function () {
    // No special logic needed for room field
});
</script>
<script>
$(function () {
    $('#add-attachment-row').on('click', function () {
        var $row = $('.order-attachment-row.new-upload').first().clone();
        $row.find('select').val('');
        $row.find('input[type="file"]').val('');
        $row.find('.remove-attachment-row').show();
        $('#order-attachments-container').append($row);
    });

    $(document).on('click', '.remove-attachment-row', function () {
        $(this).closest('.order-attachment-row').remove();
    });

    // Handle delete checkbox for existing attachments
    $(document).on('change', '.delete-attachment-checkbox', function () {
        var $row = $(this).closest('.order-attachment-row');
        var isChecked = $(this).is(':checked');
        
        if (isChecked) {
            $row.css('opacity', '0.5');
            $row.find('select, input[type="file"]').prop('disabled', true);
            $row.find('.order-attachment-replace').val('');
        } else {
            $row.css('opacity', '1');
            $row.find('select').prop('disabled', false);
            $row.find('input[type="file"]').prop('disabled', false);
        }
    });

    $('form').on('submit', function () {
        var valid = true;
        
        // Validate DOB
        var dobValue = $('input[name="ao_dom"]').val();
        if (dobValue) {
            var dobParts = dobValue.split('/');
            if (dobParts.length === 3) {
                var dobDate = new Date(dobParts[2], dobParts[0] - 1, dobParts[1]);
                var minDate = new Date(1753, 0, 1); // January 1, 1753
                var maxDate = new Date(); // Current date
                
                if (dobDate < minDate || dobDate > maxDate) {
                    alert('Date of Birth must be between 01/01/1753 and current date.');
                    $('input[name="ao_dom"]').closest('.col-sm-3').addClass('has-error');
                    valid = false;
                } else {
                    $('input[name="ao_dom"]').closest('.col-sm-3').removeClass('has-error');
                }
            }
        }
        
        $('.order-attachment-row.new-upload').each(function () {
            var $row = $(this);
            var fileVal = $row.find('.order-attachment-file').val();
            var typeVal = $row.find('.order-attachment-type').val();
            $row.find('.order-attachment-type').closest('.col-sm-4, .col-sm-3').removeClass('has-error');
            $row.find('.order-attachment-file').closest('.col-sm-6, .col-sm-5').removeClass('has-error');
            if (fileVal && !typeVal) {
                $row.find('.order-attachment-type').closest('.col-sm-4, .col-sm-3').addClass('has-error');
                valid = false;
            }
        });
        if (!valid) {
            if ($('.order-attachment-row.new-upload .has-error').length > 0) {
                alert('Please select a document type for each uploaded file.');
            }
        }
        return valid;
    });
});
</script>

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
                            <?php elseif(!empty($order_detail['creator'])): ?>
                                <br><small>by User ID: <?php echo htmlspecialchars($order_detail['creator']); ?></small>
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
                <?php echo form_open_multipart($submit_url, 'class="form-horizontal", style="margin-top:0" autocomplete="off"');  ?>
                
                <!-- Hidden field to track redirect location -->
                <input type="hidden" name="redirect_from" value="<?php echo isset($from) ? htmlspecialchars($from, ENT_QUOTES, 'UTF-8') : ''; ?>" />
                
                <div class="form-group" style="margin:0">
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
                        
                    </div>
                </div>
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary patient_search">Search</button>
                            <button type="button" class="btn btn-success patient_search_result" style="display: none"></button>
                            <label for="employee_chk" style="margin-left: 10px">employee &nbsp;</label><input type="checkbox" name="employee_chk" id="employee_chk">
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Last Name *</label>
                            <input type="text" name="ao_last_name" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'lastname'); ?>" required />
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">First Name *</label>
                            <input type="text" name="ao_first_name" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'firstname'); ?>" required />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Middle Name</label>
                            <input type="text" name="ao_middle_name" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'middlename'); ?>"/>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Suffix (Jr, Sr, II)</label>
                            <input type="text" name="ao_suffix_name" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'suffixname'); ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Patient MR</label>
                            <input type="text" name="ao_patient_mr" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientmr'); ?>" placeholder="Leave blank to auto-generate" />
                            <label class="label-desc">Leave blank to auto-generate, or enter Patient Identifier</label>
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">DOB (MM/DD/YYYY) *</label>
                            <?php
                            $dob = get_order_value($order_detail, $prefill, 'dob');
                            if(!empty($dob)){
                                if (strpos($dob, '-') !== false) {
                                    $dtime = DateTime::createFromFormat("Y-m-d H:i:s", $dob." 00:00:00");
                                    if ($dtime) {
                                        $timestamp = $dtime->getTimestamp();
                                        $dob = date("m/d/Y", $timestamp);
                                    }
                                }
                            }
                            ?>
                            <input name="ao_dom" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" inputmode="numeric" value="<?php echo $dob; ?>" required>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Patient SSN</label>
                            <input type="text" name="ao_patient_ssn" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'patientssn'); ?>"  data-inputmask="'mask': '999-99-9999'"/>
                            <label class="label-desc">Optional</label>
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
                                $gender_value = get_order_value($order_detail, $prefill, 'gender');
                                foreach($data_list as $key => $text){
                                    $selected = "";
                                    if(!empty($gender_value) && $gender_value == $key){
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
                                <option value="9999" style="color: blue;" >Not in list</option>
                                <?php
                                $orderingentity = get_order_value($order_detail, $prefill, 'orderingentity');
                                // Sort facilities alphabetically by name
                                $sorted_facilities = $facilities;
                                usort($sorted_facilities, function($a, $b) {
                                    return strcmp($a['facility_name'], $b['facility_name']);
                                });
                                foreach ( $sorted_facilities as $row ) {
                                    $selected = "";
                                    if(!empty($orderingentity) && $orderingentity == $row["id"]) {
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
                            <label class="control-label">STAT</label><br>
                            <?php
                            $stat_checked = (isset($order_detail['asr']) && $order_detail['asr'] == 'STAT') ? 'checked' : '';
                            ?>
                            <input type="checkbox" name="ao_ordered_stat" id="ao_stat" value="STAT" <?php echo $stat_checked; ?> />
                            <label class="control-label" for="ao_stat" style="font-weight: normal; margin-left: 5px;">Mark as STAT</label>
                            <input type="hidden" name="ao_ordered_asr" id="ao_ordered_asr_hidden" value="<?php echo (isset($order_detail['asr']) && !empty($order_detail['asr'])) ? htmlspecialchars($order_detail['asr'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Room</label>
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
                                // Sort facilities alphabetically by name
                                $sorted_facilities_2 = $facilities;
                                usort($sorted_facilities_2, function($a, $b) {
                                    return strcmp($a['facility_name'], $b['facility_name']);
                                });
                                foreach ( $sorted_facilities_2 as $row ) {
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
                            <input type="text" name="ao_date_of_service" class="form-control ao_date_of_service" 
                                   data-inputmask-alias="datetime" 
                                   data-inputmask-inputformat="mm/dd/yyyy" 
                                   data-inputmask-placeholder="mm/dd/yyyy" 
                                   value="<?php 
                                   if(isset($order_detail['date_of_service']) && !empty($order_detail['date_of_service'])){
                                       echo date('m/d/Y', strtotime($order_detail['date_of_service']));
                                   } else {
                                       echo date('m/d/Y');
                                   }
                                   ?>" required />
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
                            $indexer = 1;
                            $current_procedure_type = isset($order_detail['ptradio']) ? trim($order_detail['ptradio']) : '';
                            foreach($data_list as $key => $text) {
                                $selected = "";
                                if(!empty($current_procedure_type) && $current_procedure_type === $key){
                                    $selected = "checked";
                                }
                                ?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="ao_pt_radio" id="ao_pt_radio<?php echo $indexer; ?>" value="<?php echo $key; ?>" <?php echo $selected; ?>/> &nbsp; <label for="ao_pt_radio<?php echo $indexer; ?>"><?php echo $text; ?></label>
                                <?php
                                $indexer++;
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
                    if(empty($order_detail)){ // when add
                        ?>
                        <div class="form-group">
                            <div class="col-sm-2 required-item">
                                <label class="control-label">Procedure #1 *</label>
                                <input type="text" class="form-control cpt-autocomplete" placeholder="Type CPT code or procedure" required />
                                <input type="hidden" name="ao_procedure_list[0]" class="procedure-id" value="" />
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
                                    if(isset($order_detail['plrn']) && trim($order_detail['plrn']) === $key){
                                        $selected = "checked";
                                    }
                                    ?>
                                    &nbsp;&nbsp;&nbsp;<label><input type="radio" class="ao_plrn" name="ao_plrn[0]" value="<?php echo $key; ?>" <?php echo $selected; ?>/>&nbsp; <?php echo $text; ?></label>
                                    <?php
                                    $indexer++;
                                }
                                ?>
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Symptom 1</label>
                                <input type="text" name="ao_symptom_1[0]" class="form-control icd10-autocomplete ao_symptom_1" placeholder="Type ICD10 code or description" />
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Symptom 2</label>
                                <input type="text" name="ao_symptom_2[0]" class="form-control icd10-autocomplete ao_symptom_2" placeholder="Type ICD10 code or description" />
                            </div>
                            <div class="col-sm-2">
                                <label class="control-label">Symptom 3</label>
                                <input type="text" name="ao_symptom_3[0]" class="form-control icd10-autocomplete ao_symptom_3" placeholder="Type ICD10 code or description" />
                            </div>
                            <div class="col-sm-1">
                                <label class="control-label">&nbsp;</label>
                                <div class="block">
                                    <a href="javascript:void(0);" class="btn-del-procedure" title="Remove"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="ao_add_procedure_dv" num="1">
                                <i class="fa fa-fw fa-plus"></i> &nbsp; Add Procedure
                            </div>
                        </div>
                        <?php
                    }else{ //when update
                        $procedurelist = json_decode($order_detail['procedurelist'], true);
                        $plrn = json_decode($order_detail['plrn'], true);
                        $symptom1 = json_decode($order_detail['symptom1'], true);
                        $symptom2 = json_decode($order_detail['symptom2'], true);
                        $symptom3 = json_decode($order_detail['symptom3'], true);
                        
                        // Ensure arrays are valid
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
                            
                            // Get procedure label from procedure ID
                            $procedureLabel = '';
                            $procedureFound = false;
                            if(isset($procedures) && is_array($procedures)) {
                                foreach($procedures as $proc) {
                                    if($proc['id'] == $procedureValue) {
                                        $procedureLabel = $proc['cpt_code'] . ' - ' . $proc['description'];
                                        $procedureFound = true;
                                        break;
                                    }
                                }
                            }
                            
                            // If procedure not found in list, show ID as fallback
                            if(!$procedureFound && !empty($procedureValue)) {
                                $procedureLabel = 'ID: ' . $procedureValue;
                            }
                            ?>
                            <div class="form-group">
                                <div class="col-sm-2 required-item">
                                    <label class="control-label">Procedure #<?php echo $i+1; ?> *</label>
                                    <input type="text" class="form-control cpt-autocomplete" placeholder="Type CPT code or procedure" value="<?php echo htmlspecialchars($procedureLabel); ?>" required />
                                    <input type="hidden" name="ao_procedure_list[<?php echo $i; ?>]" class="procedure-id" value="<?php echo htmlspecialchars($procedureValue); ?>" />
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
                                        if(!empty($plrnValue) && trim($plrnValue) === $key){
                                            $selected = "checked";
                                        }
                                        ?>
                                        &nbsp;&nbsp;&nbsp;<label><input type="radio" class="ao_plrn" name="ao_plrn[<?php echo $i; ?>]" value="<?php echo $key; ?>" <?php echo $selected; ?>/>&nbsp; <?php echo $text; ?></label>
                                        <?php
                                        $indexer++;
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Symptom 1</label>
                                    <input type="text" name="ao_symptom_1[<?php echo $i; ?>]" class="form-control icd10-autocomplete ao_symptom_1" placeholder="Type ICD10 code or description" value="<?php echo htmlspecialchars(isset($symptom1Value) ? trim($symptom1Value) : ''); ?>" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Symptom 2</label>
                                    <input type="text" name="ao_symptom_2[<?php echo $i; ?>]" class="form-control icd10-autocomplete ao_symptom_2" placeholder="Type ICD10 code or description" value="<?php echo htmlspecialchars(isset($symptom2Value) ? trim($symptom2Value) : ''); ?>" />
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label">Symptom 3</label>
                                    <input type="text" name="ao_symptom_3[<?php echo $i; ?>]" class="form-control icd10-autocomplete ao_symptom_3" placeholder="Type ICD10 code or description" value="<?php echo htmlspecialchars(isset($symptom3Value) ? trim($symptom3Value) : ''); ?>" />
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
                        <div class="form-group text-center">
                            <div class="ao_add_procedure_dv" num="<?php echo $i; ?>">
                                <i class="fa fa-fw fa-plus"></i> &nbsp; Add Procedure
                            </div>
                        </div>
                        <?php
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
                </div>
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
                                <select name="ao_ioa" class="form-control ao_sub_item">
                                    <option value="0">Select</option>
                                    <?php
                                    $data_list = array(
                                        '1'=>'Yes',
                                        '2'=>'No'
                                    );
                                    $ioa_value = get_order_value($order_detail, $prefill, 'ioa');
                                    foreach($data_list as $key => $text) {
                                        $selected = "";
                                        if(!empty($ioa_value) && $ioa_value == $key){
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
                            <div class="col-sm-12">
                                <h4>Primary Insurance</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 insurance-company-dv">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <select name="ao_company" class="form-control selectpicker" data-live-search="true" data-size="8">
                                    <option value="">Select</option>
                                    <?php
                                    $insurancecompany_value = htmlspecialchars_decode(trim(get_order_value($order_detail, $prefill, 'insurancecompany')));
                                    $payer_list = !empty($payers) ? $payers : (!empty($insurance_companies) ? $insurance_companies : array());
                                    $found = false;
                                    foreach($payer_list as $info){
                                        $name = trim($info['name']);
                                        $sel = (!empty($insurancecompany_value) && strcasecmp($insurancecompany_value, $name) === 0) ? 'selected' : '';
                                        if($sel) $found = true;
                                        echo '<option value="'.htmlspecialchars($name).'" '.$sel.'>'.htmlspecialchars($name).'</option>';
                                    }
                                    if(!$found && !empty($insurancecompany_value))
                                        echo '<option value="'.htmlspecialchars($insurancecompany_value).'" selected>'.htmlspecialchars($insurancecompany_value).'</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <input type="text" name="ao_policy" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'policy'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <input type="text" name="ao_group" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'group'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <input type="text" name="ao_contract" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'contract'); ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <h4>Secondary Insurance</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 insurance-company-dv">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <select name="ao_company2" class="form-control selectpicker" data-live-search="true" data-size="8">
                                    <option value="">Select</option>
                                    <?php
                                    $ic2_value = htmlspecialchars_decode(trim(get_order_value($order_detail, $prefill, 'insurancecompany2')));
                                    $found2 = false;
                                    foreach($payer_list as $info){
                                        $name = trim($info['name']);
                                        $sel = (!empty($ic2_value) && strcasecmp($ic2_value, $name) === 0) ? 'selected' : '';
                                        if($sel) $found2 = true;
                                        echo '<option value="'.htmlspecialchars($name).'" '.$sel.'>'.htmlspecialchars($name).'</option>';
                                    }
                                    if(!$found2 && !empty($ic2_value))
                                        echo '<option value="'.htmlspecialchars($ic2_value).'" selected>'.htmlspecialchars($ic2_value).'</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <input type="text" name="ao_policy2" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'policy2'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <input type="text" name="ao_group2" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'group2'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <input type="text" name="ao_contract2" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'contract2'); ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <h4>Tertiary Insurance</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 insurance-company-dv">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <select name="ao_company3" class="form-control selectpicker" data-live-search="true" data-size="8">
                                    <option value="">Select</option>
                                    <?php
                                    $ic3_value = htmlspecialchars_decode(trim(get_order_value($order_detail, $prefill, 'insurancecompany3')));
                                    $found3 = false;
                                    foreach($payer_list as $info){
                                        $name = trim($info['name']);
                                        $sel = (!empty($ic3_value) && strcasecmp($ic3_value, $name) === 0) ? 'selected' : '';
                                        if($sel) $found3 = true;
                                        echo '<option value="'.htmlspecialchars($name).'" '.$sel.'>'.htmlspecialchars($name).'</option>';
                                    }
                                    if(!$found3 && !empty($ic3_value))
                                        echo '<option value="'.htmlspecialchars($ic3_value).'" selected>'.htmlspecialchars($ic3_value).'</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <input type="text" name="ao_policy3" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'policy3'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <input type="text" name="ao_group3" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'group3'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <input type="text" name="ao_contract3" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'contract3'); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <input type="text" name="responsible_party" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'responsible_party'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Relationship</label>
                                <select name="ao_relationship" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    $relationship_value = get_order_value($order_detail, $prefill, 'relationship');
                                    foreach($lists as $key =>$list_info){
                                        $selected = "";
                                        if(!empty($relationship_value) && $relationship_value == $list_info['value']){
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $list_info['value'];?>" <?php echo $selected; ?>><?php echo $list_info['value'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #1</label>
                                <input type="text" name="address1" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'address1'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #2</label>
                                <input type="text" name="address2" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'address2'); ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Phone #:</label>
                                <input type="text" name="party_phone" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'partyphone'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">City</label>
                                <input type="text" name="party_city" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'partycity'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">State</label>
                                <select name="party_state" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    $partystate_value = get_order_value($order_detail, $prefill, 'partystate');
                                    foreach($states as $key =>$state_info) {
                                        $selected = "";
                                        if(!empty($partystate_value) && $partystate_value == $state_info['fldSt']){
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $state_info['fldSt'];?>" <?php echo $selected; ?>><?php echo $state_info['fldState'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Zip</label>
                                <input type="text" name="party_zip" class="form-control" value="<?php echo get_order_value($order_detail, $prefill, 'partyzip'); ?>"/>
                            </div>
                        </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Electronic Signature <span style="color:red"></span></label>
                            <div>
                                <input type="checkbox" name="ao_electronic_signature" id="ao_electronic_signature" <?php echo (!empty($order_detail['electronic_signature'])) ? 'checked' : ''; ?>>
                                <label for="ao_electronic_signature">I certify this order electronically</label>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label g_txt_center">
                                This Patient would find it physically and/or psychologically taxing because of advanced age and/or physical limitations to
                                receive an X-Ray, Ultrasound, ECHO or EKG outside this location.
                                This test is medically necessary for the diagnosis and treatment of this patient.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="box box-primary ao_box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dispatch (Optional)</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body ao_section" style="display: block;">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Dispatch Date/Time</label>
                                <input type="text" class="form-control dispatch_datetime_inline" name="dispatch_datetime_inline" readonly
                                       value="<?php 
                                       if(!empty($order_detail['dispatch_datetime'])) {
                                           echo date('m/d/Y H:i', $order_detail['dispatch_datetime']);
                                       }
                                       ?>" placeholder="Leave blank to dispatch later" />
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Technologist</label>
                                <select name="dispatch_technologist_inline" class="form-control">
                                    <option value="">Select Technologist</option>
                                    <?php
                                    if(isset($technologist) && is_array($technologist)) {
                                        foreach($technologist as $tech) {
                                            $selected = "";
                                            if(!empty($order_detail['dispatch_technologist_id']) && $order_detail['dispatch_technologist_id'] == $tech['id']) {
                                                $selected = "selected";
                                            }
                                            echo '<option value="'.$tech['id'].'" '.$selected.'>'.$tech['lastname'].' '.$tech['firstname'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label-note">Note: If you fill in dispatch information, the order will be automatically dispatched when submitted.</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary ao_box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Attachment</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body ao_section" style="display: block;">
                        <?php
                        // Initialize attachment variables
                        $existing_attachments = array();
                        
                        if (isset($order_detail['attachment']) && !empty($order_detail['attachment'])) {
                            $attachment_data = $order_detail['attachment'];
                            
                            // Handle string data
                            if (is_string($attachment_data)) {
                                $attachment_data = trim($attachment_data);
                                
                                if (!empty($attachment_data)) {
                                    // Try to decode as JSON
                                    $decoded = json_decode($attachment_data, true);
                                    
                                    // Check if JSON decode was successful and returned an array
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                                        $existing_attachments = $decoded;
                                    } else {
                                        // Legacy single file (plain filename, not JSON)
                                        $existing_attachments[] = array(
                                            'type' => 'Legacy Attachment',
                                            'file' => $attachment_data,
                                            'original_name' => basename($attachment_data),
                                            'uploaded_at' => ''
                                        );
                                    }
                                }
                            } elseif (is_array($attachment_data)) {
                                // Already decoded (shouldn't happen, but handle it)
                                $existing_attachments = $attachment_data;
                            }
                        }
                        
                        $attachment_types = array(
                            'Signed Order Form',
                            'Unsigned Order Form',
                            'Prior Report',
                            'Face Sheet',
                            'Signed Records Release',
                            'Insurance Card'
                        );
                        ?>
                        <div id="order-attachments-container">
                            <?php if (!empty($existing_attachments)): ?>
                                <?php foreach ($existing_attachments as $att):
                                    // Extract attachment data with proper fallbacks
                                    $att_type = isset($att['type']) ? trim($att['type']) : 'Document';
                                    $att_file = isset($att['file']) ? trim($att['file']) : '';
                                    $att_original_name = isset($att['original_name']) ? trim($att['original_name']) : basename($att_file);
                                    $att_uploaded_at = isset($att['uploaded_at']) ? $att['uploaded_at'] : '';
                                    
                                    // Skip if no file
                                    if (empty($att_file)) { 
                                        continue; 
                                    }
                                ?>
                                    <div class="form-group order-attachment-row existing-attachment">
                                        <div class="col-sm-3">
                                            <label class="control-label">Document Type</label>
                                            <select name="existing_attachment_types[]" class="form-control order-attachment-type">
                                                <?php foreach ($attachment_types as $type): ?>
                                                    <option value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($type === $att_type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?></option>
                                                <?php endforeach; ?>
                                                <?php if (!in_array($att_type, $attachment_types, true)): ?>
                                                    <option value="<?php echo htmlspecialchars($att_type, ENT_QUOTES, 'UTF-8'); ?>" selected><?php echo htmlspecialchars($att_type, ENT_QUOTES, 'UTF-8'); ?></option>
                                                <?php endif; ?>
                                            </select>
                                            <input type="hidden" name="existing_attachment_files[]" value="<?php echo htmlspecialchars($att_file, ENT_QUOTES, 'UTF-8'); ?>" />
                                        </div>
                                        <div class="col-sm-5">
                                            <label class="control-label">Current File</label>
                                            <div style="padding-top: 7px;">
                                                <a href="<?php echo base_url('uploads/order_attachments/' . $att_file); ?>" target="_blank" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-file-pdf-o"></i> View PDF
                                                </a>
                                                <span class="text-muted" style="margin-left: 10px;"><?php echo htmlspecialchars($att_original_name, ENT_QUOTES, 'UTF-8'); ?></span>
                                                <?php if (!empty($att_uploaded_at)): ?>
                                                    <small class="text-muted" style="display: block; margin-top: 3px;">(Uploaded: <?php echo date('m/d/Y g:i A', strtotime($att_uploaded_at)); ?>)</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Replace File (Optional)</label>
                                            <input type="file" name="replace_attachments[]" class="form-control order-attachment-replace" accept=".pdf" />
                                            <small class="text-muted">Leave empty to keep current file</small>
                                        </div>
                                        <div class="col-sm-1" style="margin-top: 26px;">
                                            <label>
                                                <input type="checkbox" name="delete_attachments[]" value="<?php echo htmlspecialchars($att_file, ENT_QUOTES, 'UTF-8'); ?>" class="delete-attachment-checkbox" />
                                                Delete
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="form-group order-attachment-row new-upload">
                                <div class="col-sm-4">
                                    <label class="control-label">Document Type</label>
                                    <select name="order_attachment_types[]" class="form-control order-attachment-type">
                                        <option value="">Select Document Type</option>
                                        <?php foreach ($attachment_types as $type): ?>
                                            <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Upload PDF</label>
                                    <input type="file" name="order_attachments[]" class="form-control order-attachment-file" accept=".pdf" />
                                    <small class="text-muted">Only PDF files are allowed</small>
                                </div>
                                <div class="col-sm-2" style="margin-top: 26px;">
                                    <button type="button" class="btn btn-xs btn-danger remove-attachment-row" style="display:none;">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-xs btn-default" id="add-attachment-row">
                                    <i class="fa fa-plus"></i> Add Another Document
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if(empty($order_detail)) { //add order
                    ?>
                    <div class="form-group">
                        <div class="col-md-12 g_txt_right" id="a_add_user" style="display:none;">
                            <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                        </div>
                        <div class="col-md-12 g_txt_right">
                            <button type="submit" class="btn btn-sm btn-info a_add_order_btn">Submit</button>
                            <button type="submit" class="btn btn-sm btn-primary a_add_order_btn" name="submit_action" value="new_facility">Submit & Start New Order for Same Facility</button>
                            <button type="submit" class="btn btn-sm btn-success a_add_order_btn" name="submit_action" value="same_patient">Submit & Create New Order for Same Patient</button>
                            <a href="#" onclick="window.location.reload()" class="btn btn-sm btn-danger" >Reset</a>
                        </div>
                    </div>
                    <?php
                } else { //update order
                    ?>
                    <div class="form-group">
                        <div class="col-md-12 g_txt_right">
                            <button type="submit" class="btn btn-sm btn-info a_add_order_btn">Update</button>
                            <button type="submit" class="btn btn-sm btn-primary a_add_order_btn">Update & Start New Nursing Home Order</button>
                            <button type="submit" class="btn btn-sm btn-success a_add_order_btn">Update & Create New Order for Same Patient</button>
                            <a href="#" onclick="window.location.reload()" class="btn btn-sm btn-danger" >Reload</a>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <?php echo form_close( ); ?>
            </div>
        </div>

        <!-- show search results modal-->
        <div id="patient_search_result_modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" style="color: red">Search Results</h4>
                    </div>
                    <div class="search-modal-body">
                        <table id="order_search_tb" class="table table-bordered a_user_list_tb">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Suffix</th>
                                <th>Patient MR</th>
                                <th>DOB</th>
                                <th>Patient SSN</th>
                                <th>Gender</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="visibility-hidden">
    <div class="add-user-dlg-container">
        <div class="add-user-dlg" style="font-size: 13px; text-align: left">
            <div class="box-body a_u_a_top_dv" style="padding-top: 0">
                <div class="alert alert-warning alert-dismissible g_none_dis a_admin_add_alert">
                    <button type="button" class="close" id="a_add_admin_alert_close_btn" aria-hidden="true">×</button>
                    <?= validation_errors(); ?>
                    <div></div>
                </div>
                <?php echo form_open(base_url('admin/users/create'), 'class="form-horizontal"'); ?>
                <input type="hidden" name="add_type_value" value="physician" />

                <!-- Add Type Selection -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Add as: *</label>
                        <div style="margin-top: 10px;">
                            <label style="margin-right: 30px;">
                                <input type="radio" name="dlg_add_type" class="dlg_add_type" value="physician" checked />
                                Ordering Physician Only
                            </label>
                            <label>
                                <input type="radio" name="dlg_add_type" class="dlg_add_type" value="user" />
                                User Account
                            </label>
                        </div>
                    </div>
                </div>
                <hr style="margin: 10px 0 15px;">

                <!-- Common Fields -->
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">First Name * </label>
                        <input type="text" name="a_u_a_firstname" class="form-control" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Last Name * </label>
                        <input type="text" name="a_u_a_lastname" class="form-control" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Prefix </label>
                        <input type="text" name="a_u_a_prefix" class="form-control"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Suffix </label>
                        <input type="text" name="a_u_a_suffix" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Physician's NPI Number *</label>
                        <input type="text" name="a_u_a_npi" class="form-control" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Facility</label>
                        <select name="a_u_a_facility[]" class="form-control a_u_a_facility" multiple data-live-search="true">
                            <?php
                            for ($i = 0; $i < count($facilities); $i++) {
                                echo '<option value="' . $facilities[$i]["id"] . '">' . $facilities[$i]["facility_name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                        <label class="control-label">Main Mobile No</label>
                        <input type="text" name="a_u_a_main_mobile_no" class="form-control a_u_a_main_mobile_no"/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Mobile No</label>
                        <input type="text" name="a_u_a_mobile_no" class="form-control a_u_a_mobile_no"/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Fax</label>
                        <input type="text" name="a_u_a_fax" class="form-control"/>
                    </div>
                </div>

                <!-- Physician Only Fields -->
                <div class="dlg-physician-fields">
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Email</label>
                        <input type="email" name="a_u_a_email" class="form-control"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Main State</label>
                        <select name="a_u_a_state" class="form-control">
                            <?php
                            foreach($states as $key => $info){
                                ?>
                                <option value="<?php echo $info['fldSt']; ?>"><?php echo $info['fldState']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                </div>

                <!-- User Account Fields -->
                <div class="dlg-user-fields" style="display: none;">
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">User Name * </label>
                        <input type="text" name="a_u_a_username" class="form-control a_u_a_username"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Email * </label>
                        <input type="email" name="a_u_a_email_user" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Password * </label>
                        <input type="password" name="a_u_a_password" class="form-control a_u_a_password"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Retype Password *</label>
                        <input type="password" name="a_u_a_rpassword" class="form-control a_u_a_rpassword"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Role</label>
                        <select name="a_u_a_role" class="form-control">
                            <option value="7" selected>Ordering Physician</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Main State</label>
                        <select name="a_u_a_state_user" class="form-control">
                            <?php
                            foreach($states as $key => $info){
                                ?>
                                <option value="<?php echo $info['fldSt']; ?>"><?php echo $info['fldState']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Secondary State</label>
                        <select name="a_u_a_secondary_state" class="form-control">
                            <?php
                            foreach($states as $key => $info){
                                ?>
                                <option value="<?php echo $info['fldSt']; ?>"><?php echo $info['fldState']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <br>
                        <label class="control-label"><input type="checkbox" name="a_u_a_change_pwd"/> &nbsp;Force Password Change</label>
                    </div>
                </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 g_txt_right">
                        <input type="hidden" name="is_ajax" class="is_ajax" value="1"/>
                        <button type="button" class="btn btn-info a_add_user_btn">Add Physician</button>
                        <a href="javascript:void(0)" class="btn btn-danger btn-close-user-dlg">Cancel</a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Facility Dialog -->
<div class="visibility-hidden">
<div class="add-facility-dlg-container">
    <div class="add-facility-dlg" style="font-size: 13px; text-align: left">
        <div style="padding: 5px 10px 10px">
            <div class="alert alert-warning alert-dismissible g_none_dis add-facility-alert" style="margin-bottom: 12px">
                <button type="button" class="close" aria-hidden="true">×</button>
                <div></div>
            </div>
            <!-- Row 1: Facility Name | Facility Type -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-xs-12 col-sm-6">
                    <label class="control-label" style="font-weight:600">Facility Name <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_name" class="form-control input-sm" placeholder="Facility name" required/>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <label class="control-label" style="font-weight:600">Facility Type <span style="color:red">*</span></label>
                    <select name="quick_facility_type" class="form-control input-sm" required>
                        <option value="" disabled selected>— Select Type —</option>
                        <option value="NURSING HOME">NURSING HOME</option>
                        <option value="HOME BOUND">HOME BOUND</option>
                        <option value="CORRECTIONAL FACILITY">CORRECTIONAL FACILITY</option>
                        <option value="LAB">LAB</option>
                    </select>
                </div>
            </div>
            <!-- Row 2: Street Address -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-xs-12">
                    <label class="control-label" style="font-weight:600">Street Address <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_address" class="form-control input-sm" placeholder="Street address" required/>
                </div>
            </div>
            <!-- Row 3: City | State | Zip -->
            <div class="row" style="margin-bottom: 10px">
                <div class="col-xs-12 col-sm-5">
                    <label class="control-label" style="font-weight:600">City <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_city" class="form-control input-sm" placeholder="City" required/>
                </div>
                <div class="col-xs-6 col-sm-4">
                    <label class="control-label" style="font-weight:600">State <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_state" class="form-control input-sm" placeholder="State" required/>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <label class="control-label" style="font-weight:600">Zip <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_zip" class="form-control input-sm" placeholder="Zip" required/>
                </div>
            </div>
            <!-- Row 4: Phone | Fax -->
            <div class="row" style="margin-bottom: 14px">
                <div class="col-xs-12 col-sm-6">
                    <label class="control-label" style="font-weight:600">Phone Number <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_phone" class="form-control input-sm" placeholder="Phone number" required/>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <label class="control-label" style="font-weight:600">Fax Number <span style="color:red">*</span></label>
                    <input type="text" name="quick_facility_fax" class="form-control input-sm" placeholder="Fax number" required/>
                </div>
            </div>
            <!-- Buttons -->
            <div class="row">
                <div class="col-xs-12" style="text-align:right">
                    <button type="button" class="btn btn-sm btn-info add-facility-btn"><i class="fa fa-plus"></i> Add Facility</button>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-close-facility-dlg">Cancel</a>
                </div>
            </div>
        </div>
    </div>
 </div>
</div>

<!-- Modal -->
<div id="input-deviceid" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Please input device id</h4>
            </div>
            <div class="modal-body">
                <label class="control-label">Device Id </label>
                <input type="text" name="a_u_a_deviceid" class="form-control" id="a_u_a_deviceid"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    /*for service dr part*/
    var user_dlg;
    $(document).ready(function(){
        // Initialize selectpicker for insurance company dropdowns
        $('select.selectpicker').selectpicker('render');

        // Initialize datepicker for Date of Service field with calendar popup
        $('.ao_date_of_service').datepicker({
            dateFormat: 'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            minDate: 0 // Can't select past dates
        });
        
        // Initialize datetimepicker for inline dispatch
        $('.dispatch_datetime_inline').datetimepicker({
            dateFormat: 'mm/dd/yy',
            timeFormat: 'HH:mm',
            changeMonth: true,
            changeYear: true
        });

        // Dispatch validation: if one field is filled, require the other
        $('form.form-horizontal').on('submit', function(e) {
            var dt = $.trim($('.dispatch_datetime_inline').val());
            var tech = $.trim($('select[name="dispatch_technologist_inline"]').val());
            if(dt && !tech) {
                alert('Please select a Technologist for dispatch.');
                $('select[name="dispatch_technologist_inline"]').focus();
                e.preventDefault();
                return false;
            }
            if(!dt && tech) {
                alert('Please select a Dispatch Date/Time.');
                $('.dispatch_datetime_inline').focus();
                e.preventDefault();
                return false;
            }
        });
        
        // Initialize hidden field based on checkbox state on page load
        function initStatField() {
            var isChecked = $('#ao_stat').is(':checked');
            $('#ao_ordered_asr_hidden').val(isChecked ? 'STAT' : '');
        }
        
        // Handle STAT checkbox to update hidden field
        $('#ao_stat').on('change', function() {
            if ($(this).is(':checked')) {
                $('#ao_ordered_asr_hidden').val('STAT');
            } else {
                $('#ao_ordered_asr_hidden').val('');
            }
        });
        
        // Ensure hidden field is properly set when form loads
        initStatField();


        
        $("select[name='ao_service_dr']").change(function () {
            var ordering_physician = jQuery(this).val();
            console.log("ordering_physician:", ordering_physician);
            if (ordering_physician && ordering_physician != 1111) {
                var selected = $(this).find('option:selected');
                var phone = selected.data('phone') || '';
                var fax = selected.data('fax') || '';
                var npi = selected.data('npi') || '';
                $("input[name='ao_dr_phone']").val(phone);
                $("input[name='ao_dr_fax']").val(fax);
                $("input[name='ao_dr_NPI']").val(npi);
            } else if (ordering_physician == 1111) {
                var html = $(".add-user-dlg-container").html();
                user_dlg = show_dialog("Add Ordering Physician", html, "xlarge", false);
                setTimeout(function(){
                    $(".jconfirm select.a_u_a_facility").selectpicker();

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
                $("input[name='ao_dr_phone']").val('');
                $("input[name='ao_dr_fax']").val('');
                $("input[name='ao_dr_NPI']").val('');
            } else {
                $("input[name='ao_dr_phone']").val('');
                $("input[name='ao_dr_fax']").val('');
                $("input[name='ao_dr_NPI']").val('');
            }
        });
        $("body").on("click", ".btn-close-user-dlg", function(){
            var cur_service_dr = $("select[name='ao_service_dr']").val();
            if(cur_service_dr == 1111){
                $("select[name='ao_service_dr']").val("");
            }
            user_dlg.close();
        });

        // Toggle between Physician Only and User Account in dialog
        $("body").on("change", ".jconfirm .dlg_add_type", function(){
            var mode = $(this).val();
            var dlg = $(this).closest(".add-user-dlg");
            if(mode === 'user'){
                dlg.find("[name='add_type_value']").val('user');
                dlg.find(".dlg-physician-fields").hide();
                dlg.find(".dlg-user-fields").show();
                dlg.find(".a_add_user_btn").text("Add User");
            } else {
                dlg.find("[name='add_type_value']").val('physician');
                dlg.find(".dlg-physician-fields").show();
                dlg.find(".dlg-user-fields").hide();
                dlg.find(".a_add_user_btn").text("Add Physician");
            }
        });

        $("body").on("click", ".jconfirm .a_add_user_btn", function () {
            var add_user_dlg = $(this).closest(".add-user-dlg");
            var mode = add_user_dlg.find("[name='add_type_value']").val();

            // Common required fields
            var required_fields = [
                { name:"a_u_a_firstname", msg:"First name is required" },
                { name:"a_u_a_lastname", msg:"Last name is required" },
                { name:"a_u_a_npi", msg:"Physician's NPI number is required" }
            ];

            // Additional required fields for user account mode
            if(mode === 'user'){
                required_fields.push(
                    { name:"a_u_a_username", msg:"User name is required" },
                    { name:"a_u_a_email_user", msg:"Email is required" },
                    { name:"a_u_a_password", msg:"Password is required" },
                    { name:"a_u_a_rpassword", msg:"Retype password is required" }
                );
            }

            for(var i=0; i<required_fields.length; i++){
                var field = required_fields[i];
                if(add_user_dlg.find("[name='"+field['name']+"']").val() == ""){
                    add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                    add_user_dlg.find(".a_admin_add_alert").children("div").html(field['msg']);
                    return false;
                }
            }

            if(mode === 'user'){
                // Check password match
                if(add_user_dlg.find(".a_u_a_password").val() != add_user_dlg.find(".a_u_a_rpassword").val()){
                    add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                    add_user_dlg.find(".a_admin_add_alert").children("div").html("Passwords do not match");
                    add_user_dlg.find(".a_u_a_rpassword").val("").focus();
                    return false;
                }
                // Check username duplicate
                var username = add_user_dlg.find("[name='a_u_a_username']").val();
                var params = { "username": username };
                var url = BASE_URL + 'admin/users/confirm_admin_username';
                jQuery.post(url, params, function(res) {
                    res = JSON.parse(res);
                    if(res.status == "1") {
                        add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                        add_user_dlg.find(".a_admin_add_alert").children("div").html("Same username already exists");
                        add_user_dlg.find(".a_u_a_username").val("").focus();
                        return false;
                    } else {
                        // Copy email_user to email field for backend
                        var emailVal = add_user_dlg.find("[name='a_u_a_email_user']").val();
                        add_user_dlg.find("[name='a_u_a_email']").val(emailVal);
                        // Copy state_user to state field
                        var stateVal = add_user_dlg.find("[name='a_u_a_state_user']").val();
                        add_user_dlg.find("[name='a_u_a_state']").val(stateVal);
                        submitDlgForm(add_user_dlg);
                    }
                });
            } else {
                submitDlgForm(add_user_dlg);
            }
        });

        function submitDlgForm(add_user_dlg){
            var form = add_user_dlg.find("form");
            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: form.serialize(),
                success: function(res)
                {
                    console.log("data:", res);
                    var obj = JSON.parse(res);
                    if(obj.status== 1) {
                        console.log("obj:", obj);
                        var data = obj.data;
                        var html = '<option value="'+data.id+'" data-phone="'+(data.phone||'')+'" data-fax="'+(data.fax||'')+'" data-NPI="'+(data.NPI||'')+'">'+data.lastname+' '+data.firstname+'</option>';
                        $("select[name='ao_service_dr']").append(html);
                        $("select[name='ao_service_dr']").val(data.id).change();
                        user_dlg.close();
                    } else {
                        add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                        add_user_dlg.find(".a_admin_add_alert").children("div").html(obj.msg || 'Unable to add physician.');
                    }
                }
            });
        }

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
<script>

        // Initialize ICD10 autocomplete for symptom fields
        $(document).ready(function() {
            $('.icd10-autocomplete').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: BASE_URL + 'admin/order/search_icd10',
                        dataType: 'json',
                        data: { term: request.term },
                        success: function(data) {
                            response(data);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $(this).val(ui.item.value);
                    return false;
                }
            });

            // Re-initialize autocomplete for dynamically added procedure rows
            $(document).on('focus', '.icd10-autocomplete:not(.ui-autocomplete-input)', function() {
                $(this).autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: BASE_URL + 'admin/order/search_icd10',
                            dataType: 'json',
                            data: { term: request.term },
                            success: function(data) {
                                response(data);
                            },
                            error: function() {
                                response([]);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        $(this).val(ui.item.value);
                        return false;
                    }
                });
            });

            // Initialize CPT autocomplete for procedure fields
            $('.cpt-autocomplete').autocomplete({
                source: function(request, response) {
                    console.log('CPT autocomplete called with term:', request.term);
                    $.ajax({
                        url: BASE_URL + 'admin/order/search_procedures',
                        dataType: 'json',
                        data: { term: request.term },
                        success: function(data) {
                            console.log('CPT autocomplete response:', data);
                            response(data);
                        },
                        error: function(xhr, status, error) {
                            console.error('CPT autocomplete error:', status, error);
                            console.error('Response:', xhr.responseText);
                            response([]);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    console.log('CPT selected:', ui.item);
                    $(this).val(ui.item.value);
                    $(this).siblings('.procedure-id').val(ui.item.id);
                    return false;
                }
            });

            // Re-initialize CPT autocomplete for dynamically added procedure rows
            $(document).on('focus', '.cpt-autocomplete:not(.ui-autocomplete-input)', function() {
                console.log('Reinitializing CPT autocomplete for dynamic row');
                $(this).autocomplete({
                    source: function(request, response) {
                        console.log('CPT autocomplete (dynamic) called with term:', request.term);
                        $.ajax({
                            url: BASE_URL + 'admin/order/search_procedures',
                            dataType: 'json',
                            data: { term: request.term },
                            success: function(data) {
                                console.log('CPT autocomplete (dynamic) response:', data);
                                response(data);
                            },
                            error: function(xhr, status, error) {
                                console.error('CPT autocomplete (dynamic) error:', status, error);
                                response([]);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        console.log('CPT (dynamic) selected:', ui.item);
                        $(this).val(ui.item.value);
                        $(this).siblings('.procedure-id').val(ui.item.id);
                        return false;
                    }
                });
            });
        });

</script>
