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
                <div class="block" >
                    <select name="ao_kind" class="form-control" style="float: left;">
                        <option value="1">Nursing, Rehab, and Assisted Living Facilities</option>
                        <option value="2">Primary Care Clinics/Private Clinics/Chiropractors/Physical Therapists</option>
                        <option value="3">Home Bound</option>
                        <option value="3">Contract</option>
                        <!-- <option value="1">Nursing Home</option>
                        <option value="2">Correctional Facility</option>
                        <option value="3">Home Bound</option>
                        <option value="3">Contract</option> -->
                    </select>
                </div>
                <?php echo form_open(base_url('admin/order/create'), 'class="form-horizontal", style="margin-top:30px" autocomplete="off"');  ?>

                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-sm btn-primary patient_search">Search</button>
                            <button type="button" class="btn btn-success patient_search_result" style="display: none"></button>
                            <label for="employee_chk" style="margin-left: 10px">employee &nbsp;</label><input type="checkbox" name="employee_chk" id="employee_chk">
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Last Name *</label>
                            <input type="text" name="ao_last_name" class="form-control" required />
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">First Name *</label>
                            <input type="text" name="ao_first_name" class="form-control" required />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Middle Name</label>
                            <input type="text" name="ao_middle_name" class="form-control"/>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Suffix (Jr, Sr, II)</label>
                            <input type="text" name="ao_suffix_name" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Patient MR</label>
                            <input type="text" name="ao_patient_mr" class="form-control" placeholder="Leave blank to auto-generate" />
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">DOB (MM/DD/YYYY) *</label>
                            <input name="ao_dom" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" inputmode="numeric" required>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Patient SSN</label>
                            <input type="text" name="ao_patient_ssn" class="form-control" />
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Sex *</label>
                            <select name="ao_sex" class="form-control" required>
                                <option value="">Select</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="Other">Other</option>
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
                                foreach ( $facilities as $row ) {
                                    echo '<option value="'.$row["id"].'">'.$row["facility_name"].'</option>';
                                }
                                ?>
                                <option value="9999">Not in list</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Ordered By</label>
                            <input type="text" name="ao_ordered_by" class="form-control" />
                        </div>
                        <div class="col-sm-6">
                            <div>&nbsp;</div>
                            <input type="checkbox" name="ao_ordered_asr1" id="ao_asr1" />&nbsp;<label class="control-label" for="ao_asr1">STAT</label>&nbsp;&nbsp;
                            <input type="checkbox" name="ao_ordered_asr2" id="ao_asr2" />&nbsp;<label class="control-label" for="ao_asr2">ASAP</label>&nbsp;&nbsp;
                            <input type="checkbox" name="ao_ordered_asr3" id="ao_asr3" />&nbsp;<label class="control-label" for="ao_asr3">ROUTINE</label>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Station</label>
                            <select name="ao_ordered_station" class="form-control">
                                <option value="0">Select</option>
                                <option value="first">first</option>
                                <option value="second">second</option>
                                <option value="third">third</option>
                            </select>
                        </div>
                        <div class="col-sm-3 required-item">
                            <label class="control-label">Room *</label>
                            <input type="text" name="ao_ordered_room" class="form-control" required />
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">City</label>
                            <input type="text" name="ao_ordered_city" class="form-control" id="ao_ordered_city" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Address</label>
                            <input type="text" name="ao_ordered_address" class="form-control" autocomplete="off">
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
                            <input type="text" name="ao_ordered_state" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Zip</label>
                            <input type="text" name="ao_ordered_zip" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Phone</label>
                            <input type="text" name="ao_ordered_phone" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Fax</label>
                            <input type="text" name="ao_ordered_fax" class="form-control"/>
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
                                    echo '<option value="'.$row["id"].'">'.$row["facility_name"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Order Status</label>
                            <select name="ao_service_status" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">Ready</option>
                                <option value="2">TBD</option>
                                <option value="3">Address Verified</option>
                            </select>
<!--                            <label class="control-label">Ordered By</label>-->
<!--                            <input type="text" name="ao_ordered_by" class="form-control" />-->
                        </div>
<!--                        <div class="col-sm-6">-->
<!--                            <div>&nbsp;</div>-->
<!--                            <input type="checkbox" name="ao_asr" id="ao_asr1" />&nbsp;<label class="control-label" for="ao_asr1">STAT</label>&nbsp;&nbsp;-->
<!--                            <input type="checkbox" name="ao_asr" id="ao_asr2" />&nbsp;<label class="control-label" for="ao_asr2">ASAP</label>&nbsp;&nbsp;-->
<!--                            <input type="checkbox" name="ao_asr" id="ao_asr3" />&nbsp;<label class="control-label" for="ao_asr3">ROUTINE</label>&nbsp;&nbsp;-->
<!--                        </div>-->
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">Station</label>
                            <select name="ao_service_station" class="form-control">
                                <option value="0">Select</option>
                                <option value="1">first</option>
                                <option value="2">second</option>
                                <option value="3">third</option>

                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Room </label>
                            <input type="text" name="ao_service_room" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="control-label">Address</label>
                            <input type="text" name="ao_service_address" value="" class="form-control" />
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label">City</label>
                            <input type="text" name="ao_service_city" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="control-label">State</label>
                            <input type="text" name="ao_service_state" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Zip</label>
                            <input type="text" name="ao_service_zip" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Phone</label>
                            <input type="text" name="ao_service_phone" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">Fax</label>
                            <input type="text" name="ao_service_fax" class="form-control" />
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
                                echo '<option value="'.$row["id"].'" data-phone="'.$row["phone"].'" data-fax="'.$row["fax"].'" data-NPI="'.$row["NPI"].'">'.$row["lastname"].' '.$row["firstname"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Phone</label>
                        <input type="text" name="ao_dr_phone" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Fax</label>
                        <input type="text" name="ao_dr_fax" class="form-control" />
                    </div>
                        <div class="col-sm-3">
                            <label class="control-label">NPI</label>
                            <input type="text" name="ao_dr_NPI" class="form-control" />
                        </div>
                </div>
                </div>
                <div class="ao_section">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Procedure Type : </label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_pt_radio" id="ao_pt_radio1" value="X-RAY" /> &nbsp; <label for="ao_pt_radio1">X-RAY</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_pt_radio" id="ao_pt_radio2" value="US" /> &nbsp; <label for="ao_pt_radio2">US</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_pt_radio" id="ao_pt_radio3" value="EKG" /> &nbsp; <label for="ao_pt_radio3">EKG</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_pt_radio" id="ao_pt_radio4" value="ECHO" /> &nbsp; <label for="ao_pt_radio4">ECHO</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_pt_radio" id="ao_pt_radio5" value="LINE PLACEMENT"/> &nbsp; <label for="ao_pt_radio5">LINE PLACEMENT</label>
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
                            <input type="text" class="form-control cpt-autocomplete" placeholder="Type CPT code or procedure" required />
                            <input type="hidden" name="ao_procedure_list" class="procedure-id" value="" />
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label">&nbsp;</label> <br>
                            &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_plrn" id="ao_plrn1" value="L" /> &nbsp; <label for="ao_plrn1">L</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_plrn" id="ao_plrn2" value="R" /> &nbsp; <label for="ao_plrn2">R</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_plrn" id="ao_plrn3" value="BI" /> &nbsp; <label for="ao_plrn3">BI </label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="ao_plrn" id="ao_plrn4" value="CD Requested" /> &nbsp; <label for="ao_plrn4">CD Requested </label> &nbsp;&nbsp;&nbsp;
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
                            <input type="text" name="ao_reason_for_exam" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">History</label>
                            <input type="text" name="ao_history" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="control-label">Reason Exams are being done portably</label>
                            <select name="ao_portable_reason" class="form-control ao_portable_reason">
                                <option value="">Select</option>
                                <?php
                                foreach($reason_photoble as $key => $info){
                                    ?>
                                    <option value="<?php echo $info['value'];?>"><?php echo $info['value'];?></option>
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
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
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
                                        ?>
                                        <option value="<?php echo $info['name'];?>"><?php echo $info['name'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Medicare #</label>
                                <input type="text" name="ao_medicare" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Medicaid #</label>
                                <input type="text" name="ao_medicaid" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">State</label>
                                <input type="text" name="ao_state" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Insurance Company (Payer)</label>
                                <select name="ao_company" class="form-control">
                                    <option value="">Select</option>
                                    <?php
                                    // Use new payers table, fallback to insurance_companies if not available
                                    $payer_list = isset($payers) && !empty($payers) ? $payers : $insurance_companies;
                                    foreach($payer_list as $key =>$info){
                                        ?>
                                        <option value="<?php echo $info['name'];?>"><?php echo $info['name'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Policy #</label>
                                <input type="text" name="ao_policy" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Group #</label>
                                <input type="text" name="ao_group" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">HMO Name/Contract</label>
                                <input type="text" name="ao_contract" class="form-control"  />
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
                                <input type="text" name="responsible_party" class="form-control" />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Relationship</label>
                                <select name="ao_relationship" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach($lists as $key =>$list_info){
                                        ?>
                                        <option value="<?php echo $list_info['value'];?>"><?php echo $list_info['value'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #1</label>
                                <input type="text" name="address1" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Address #2</label>
                                <input type="text" name="address2" class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="control-label">Phone #:</label>
                                <input type="text" name="party_phone" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">City</label>
                                <input type="text" name="party_city" class="form-control"  />
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">State</label>
                                <select name="ao_party_state" class="form-control">
                                    <option value="0">Select</option>
                                    <?php
                                    foreach($states as $key =>$state_info){
                                        ?>
                                        <option value="<?php echo $state_info['fldSt'];?>"><?php echo $state_info['fldState'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Zip</label>
                                <input type="text" name="party_zip" class="form-control"  />
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
    function updateFormForOrderType() {
        var orderType = $("select[name='ao_kind']").val();
        var roomLabel = $("label[for='ao_ordered_room']");
        if (!roomLabel.length) {
            roomLabel = $("label.control-label").filter(function(){
                return $(this).text().trim().indexOf('Room') === 0;
            }).first();
        }
        var roomInput = $("input[name='ao_ordered_room']");
        if (orderType === '1') {
            if (roomInput.length) roomInput.prop('required', true);
            if (roomLabel.length) roomLabel.html("Room <span style='color:red'>*</span>");
        } else {
            if (roomInput.length) roomInput.prop('required', false);
            if (roomLabel.length) roomLabel.text('Room');
        }
    }
    $(function() {
        updateFormForOrderType();
        $("select[name='ao_kind']").on('change', updateFormForOrderType);
    });
    $(document).ready(function(){
        $("select[name='ao_service_dr']").change(function () {
            var ordering_physician = jQuery(this).val();
            // Autofill phone, fax, NPI if not 'Not In List'
            if (ordering_physician && ordering_physician != 1111) {
                var selected = $(this).find('option:selected');
                var phone = selected.data('phone') || '';
                var fax = selected.data('fax') || '';
                var npi = selected.data('npi') || '';
                $("input[name='ao_dr_phone']").val(phone);
                $("input[name='ao_dr_fax']").val(fax);
                $("input[name='ao_dr_NPI']").val(npi);
            } else if(ordering_physician == 1111) {
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
                // Clear fields if Not In List
                $("input[name='ao_dr_phone']").val('');
                $("input[name='ao_dr_fax']").val('');
                $("input[name='ao_dr_NPI']").val('');
            } else {
                // Clear fields if nothing selected
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
                    console.log("data:", res);
                    var obj = JSON.parse(res);
                    if(obj.status== 1) {
                        console.log("obj:", obj);
                        var data = obj.data;
                        var html = '<option value="'+data.id+'" data-phone="'+(data.phone||'')+'" data-fax="'+(data.fax||'')+'" data-NPI="'+(data.NPI||'')+'">'+data.firstname+' '+data.lastname+'</option>';
                        $("select[name='ao_service_dr']").append(html);
                        $("select[name='ao_service_dr']").val(data.id).change();
                        user_dlg.close();
                    } else {
                        add_user_dlg.find(".a_admin_add_alert").removeClass("g_none_dis");
                        add_user_dlg.find(".a_admin_add_alert").children("div").html(obj.msg || 'Unable to add physician.');
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