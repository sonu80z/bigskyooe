<?php
$order_detail = (isset($order) ? $order : array());
?>
<!-- inputMask loaded in layout.php -->

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
    <div class="row">
        <div class="col-md-12">
            <div class="box-body my-form-body ao_cnt_dv" style="padding: 0 20px">
                <?php echo form_open(base_url('admin/order/create'), 'class="form-horizontal", style="margin-top:0" autocomplete="off"');  ?>
                <div class="form-group" style="margin:0">
                    <div class="col-md-12">
                        <select name="ao_kind" class="form-control pull-left" style="max-width: 170px">
                            <?php
                            $kind_list = array(
                                '1'=>'Nursing Home',
                                '2'=>'Correctional Facility',
                                '3'=>'Home Bound',
                                '4'=>'Contract'
                            );
                            foreach($kind_list as $key => $text) {
                                $selected = "";
                                if(isset($order_detail['kind']) && $order_detail['kind'] == $key){
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
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary patient_search">Search</button>
                            <button type="button" class="btn btn-success patient_search_result" style="display: none"></button>
                            <label for="employee_chk" style="margin-left: 10px">employee &nbsp;</label><input type="checkbox" name="employee_chk" id="employee_chk">
                        </div>
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
                </div>
                <div class="ao_section" id="order_entity">
                    <div class="form-group">
                        <div class="col-sm-3 required-item">
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
                        <div class="col-sm-6">
                            <label class="control-label"> </label>
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
                            foreach($data_list as $key => $text) {
                                $selected = "";
                                if(isset($order_detail['ptradio']) && $order_detail['ptradio'] == $key){
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
                    <div class="form-group">
                        <div class="col-sm-2 required-item">
                            <label class="control-label">Procedure #1 *</label>
                            <?php
                            $procedureText = '';
                            if(isset($order_detail['procedurelist'])) {
                                foreach ($procedures as $row) {
                                    if($order_detail['procedurelist'] == $row['id']) {
                                        $procedureText = $row['cpt_code'] . ' - ' . $row['description'];
                                        break;
                                    }
                                }
                            }
                            ?>
                            <input type="text" class="form-control cpt-autocomplete" placeholder="Type CPT code or procedure" value="<?php echo $procedureText; ?>" required />
                            <input type="hidden" name="ao_procedure_list" class="procedure-id" value="<?php echo isset($order_detail['procedurelist']) ? $order_detail['procedurelist'] : ''; ?>" />
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
                                if(isset($order_detail['plrn']) && $order_detail['plrn'] == $key){
                                    $selected = "checked";
                                }
                                ?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="ao_plrn" id="ao_plrn<?php echo $indexer; ?>" value="<?php echo $key; ?>" <?php echo $selected; ?>/> &nbsp; <label for="ao_plrn<?php echo $indexer; ?>"><?php echo $text; ?></label>
                                <?php
                                $indexer++;
                            }
                            ?>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">Symptom 1</label>
                            <input type="text" name="ao_symptom_1" class="form-control icd10-autocomplete" placeholder="Type ICD10 code or description" />
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">Symptom 2</label>
                            <input type="text" name="ao_symptom_2" class="form-control icd10-autocomplete" placeholder="Type ICD10 code or description" />
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label">Symptom 3</label>
                            <input type="text" name="ao_symptom_3" class="form-control icd10-autocomplete" placeholder="Type ICD10 code or description" />
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
                                    foreach($data_list as $key => $text) {
                                        $selected = "";
                                        if(isset($order_detail['ioa']) && $order_detail['ioa'] == $key){
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
                                <label class="control-label">Insurance Type</label>
                                <select name="ao_insurance_type" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach($insurance_types as $key =>$info){
                                        $selected = "";
                                        if(isset($order_detail['insurancetype']) && $order_detail['insurancetype'] == $info['name']){
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $info['name'];?>" <?php echo $selected; ?>><?php echo $info['name'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Medicare #</label>
                                <input type="text" name="ao_medicare" class="form-control" value="<?php echo get_data_field($order_detail, 'medicare'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Medicaid #</label>
                                <input type="text" name="ao_medicaid" class="form-control" value="<?php echo get_data_field($order_detail, 'medicaid'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">State</label>
                                <input type="text" name="ao_state" class="form-control" value="<?php echo get_data_field($order_detail, 'state'); ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Insurance Company</label>
                                <select name="ao_company" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    foreach($insurance_companies as $key =>$info){
                                        $selected = "";
                                        if(isset($order_detail['insurancecompany']) && $order_detail['insurancecompany'] == $info['name']){
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $info['name'];?>" <?php echo $selected; ?>><?php echo $info['name'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <input type="text" name="ao_policy" class="form-control" value="<?php echo get_data_field($order_detail, 'policy'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <input type="text" name="ao_group" class="form-control" value="<?php echo get_data_field($order_detail, 'group'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <input type="text" name="ao_contract" class="form-control" value="<?php echo get_data_field($order_detail, 'contract'); ?>"/>
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
                                <input type="text" name="responsible_party" class="form-control" value="<?php echo get_data_field($order_detail, 'responsible_party'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Relationship</label>
                                <select name="ao_relationship" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach($lists as $key =>$list_info){
                                        $selected = "";
                                        if(isset($order_detail['relationship']) && $order_detail['relationship'] == $list_info['value']){
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
                                <input type="text" name="address1" class="form-control" value="<?php echo get_data_field($order_detail, 'address1'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #2</label>
                                <input type="text" name="address2" class="form-control" value="<?php echo get_data_field($order_detail, 'address2'); ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Phone #:</label>
                                <input type="text" name="party_phone" class="form-control" value="<?php echo get_data_field($order_detail, 'partyphone'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">City</label>
                                <input type="text" name="party_city" class="form-control" value="<?php echo get_data_field($order_detail, 'partycity'); ?>"/>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">State</label>
                                <select name="party_state" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach($states as $key =>$state_info) {
                                        $selected = "";
                                        if(isset($order_detail['partystate']) && $order_detail['partystate'] == $state_info['fldSt']){
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
                                <input type="text" name="party_zip" class="form-control" value="<?php echo get_data_field($order_detail, 'partyzip'); ?>"/>
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
                <div class="form-group">
                    <div class="col-md-12 g_txt_right" id="a_add_user" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                    </div>
                    <div class="col-md-12 g_txt_right">
                        <button type="submit" class="btn btn-sm btn-info a_add_order_btn">Submit</button>
                        <button type="submit" class="btn btn-sm btn-primary a_add_order_btn">Submit & Start New Nursing Home Order</button>
                        <button type="submit" class="btn btn-sm btn-success a_add_order_btn">Submit & Create New Order for Same Patient</button>
                        <a href="#" onclick="window.location.reload()" class="btn btn-sm btn-danger" >Reset</a>
                    </div>
                </div>
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
    var user_dlg;
    $(document).ready(function(){
        $("select[name='ao_service_dr']").change(function () {
            var ordering_physician = jQuery(this).val();
            console.log("ordering_physician:", ordering_physician);
            if (ordering_physician == 1111) {
                var html = $(".add-user-dlg-container").html();
                user_dlg = show_dialog("Add Ordering Physician", html, "xlarge", false);
                setTimeout(function(){
                    // multi select
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
                    name:"a_u_a_npi",
                    msg:"Physician's NPI number is required"
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

            var form = add_user_dlg.find("form");
            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: form.serialize(),
                success: function(res)
                {
                    var obj = JSON.parse(res);
                    if(obj.status == 1) {
                        var data = obj.data;
                        var html = '<option value="'+data.id+'" data-phone="'+(data.phone||'')+'" data-fax="'+(data.fax||'')+'" data-NPI="'+(data.NPI||'')+'">'+data.firstname+' '+data.lastname+'</option>';
                        $("select[name='ao_service_dr']").append(html);
                        $("select[name='ao_service_dr']").val(data.id).change();
                        user_dlg.close();
                    } else {
                        add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                        add_user_dlg.find(".a_admin_add_alert").children("div").html(obj.msg || "Error creating physician");
                    }
                }
            });
        });

        //test
        //$("select[name='ao_service_dr']").val(1111).change();

        // Initialize ICD10 autocomplete for symptom fields
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

        // Initialize CPT autocomplete for procedure fields
        $('.cpt-autocomplete').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: BASE_URL + 'admin/order/search_procedures',
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
                $(this).siblings('.procedure-id').val(ui.item.id);
                return false;
            }
        });
    });
</script>