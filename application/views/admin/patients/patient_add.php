<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?= $title; ?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/patients'); ?>" class="btn btn-default">Patient LIST</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body my-form-body a_u_a_top_dv">
                <div class="alert alert-warning alert-dismissible g_none_dis" id="a_admin_add_alert">
                    <button type="button" class="close" id="a_add_admin_alert_close_btn" aria-hidden="true">×</button>
                    <?= validation_errors(); ?>
                    <div></div>
                </div>
                <?php echo form_open(base_url('admin/patients/create'), 'class="form-horizontal"'); ?>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">First Name * </label>
                        <input type="text" name="a_u_a_first_name" class="form-control" id="a_u_a_first_name" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Last Name * </label>
                        <input type="text" name="a_u_a_last_name" class="form-control" id="a_u_a_last_name" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Patient MRN * </label>
                        <input type="text" name="a_u_a_patient_mrn" class="form-control" id="a_u_a_patient_mrn" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">SS NO * </label>
                        <input type="text" name="a_u_a_ss_no" class="form-control" id="a_u_a_ss_no" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Secondary ID </label>
                        <input type="text" name="a_u_a_secondary_id" class="form-control" id="a_u_a_secondary_id" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Suffix</label>
                        <input type="text" name="a_u_a_suffix" class="form-control" id="a_u_a_suffix"/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">DOB * </label>
                        <input type="text" name="a_u_a_dob" class="form-control" id="a_u_a_dob" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Gender * </label>
                        <select id="a_u_a_gender" name="a_u_a_gender" class="form-control">
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">HB </label>
                        <select id="a_u_a_hb" name="a_u_a_hb" class="form-control">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">HB_Institution </label>
                        <?php $hb_facilities = $this->db->query("SELECT facility_name FROM `tbl_facility` WHERE facility_type = 'HOME_BOUND'")->result_array();?>
                        <select id="a_u_a_hb_institution" name="a_u_a_hb_institution" class="form-control">
                            <?php foreach ($hb_facilities as $facility):?>
                            <option value="<?php echo $facility["facility_name"]?>"><?php $facility["facility_name"]?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">MI </label>
                        <input type="text" name="a_u_a_mi" class="form-control" id="a_u_a_mi"/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">NH</label>
                        <select id="a_u_a_nh" name="a_u_a_nh" class="form-control">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <?php $nh_facilities = $this->db->query("SELECT id, facility_name FROM `tbl_facility` WHERE facility_type = 'NURSING HOME'")->result_array();?>
                        <label class="control-label">NH_Institution </label>
                        <select id="a_u_a_nh_institution" name="a_u_a_nh_institution" class="form-control">
                            <?php foreach ($nh_facilities as $facility):?>
                                <option value="<?php echo $facility["id"]?>"><?php echo $facility["facility_name"]?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Lab </label>
                        <select id="a_u_a_lab" name="a_u_a_lab" class="form-control">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <?php $lab_facilities = $this->db->query("SELECT id, facility_name FROM `tbl_facility` WHERE facility_type = 'LAB'")->result_array();?>
                        <label class="control-label">Lab Pro </label>
                        <select id="a_u_a_lab_pro" name="a_u_a_lab_pro" class="form-control">
                            <?php foreach ($lab_facilities as $facility):?>
                                <option value="<?php echo $facility["id"]?>"><?php echo $facility["facility_name"]?></option>
                            <?php endforeach;?>
                        </select>

                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Address1 *</label>
                        <input type="text" name="a_u_a_address1" class="form-control" id="a_u_a_address1" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Address2 </label>
                        <input type="text" name="a_u_a_address2" class="form-control" id="a_u_a_address2"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">City *</label>
                        <input type="text" name="a_u_a_city" class="form-control" id="a_u_a_city" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">State *</label>
                        <input type="text" name="a_u_a_state" class="form-control" id="a_u_a_state" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Patient Zip *</label>
                        <input type="text" name="a_u_a_patient_zip" class="form-control" id="a_u_a_patient_zip" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Email </label>
                        <input type="text" name="a_u_a_email" class="form-control" id="a_u_a_email"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Phone *</label>
                        <input type="text" name="a_u_a_phone" class="form-control" id="a_u_a_phone"
                               required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Secondary Phone *</label>
                        <input type="text" name="a_u_a_secondary_phone" class="form-control" id="a_u_a_secondary_phone" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Remark</label>
                        <input type="text" name="a_u_a_remark" class="form-control" id="a_u_a_remark"/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Medical Alerts</label>
                        <input type="text" name="a_u_a_medical_alerts" class="form-control" id="a_u_a_medical_alerts"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Creation Date *</label>
                        <input type="text" name="a_u_a_creation_date" class="form-control" id="a_u_a_creation_date"
                               required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Last Order Date *</label>
                        <input type="text" name="a_u_a_last_order_date" class="form-control" id="a_u_a_last_order_date" required/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Last Insurance Date</label>
                        <input type="text" name="a_u_a_last_insurance_date" class="form-control" id="a_u_a_last_insurance_date"/>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Category Desc</label>
                        <select id="a_u_a_category_desc"  name="a_u_a_category_desc" class="form-control">
                            <option value="Self">Self</option>
                            <option value="HMO">HMO</option>
                            <option value="Medicaid">Medicaid</option>
                            <option value="Medicare">Medicare</option>
                            <option value="PPO">PPO</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">Provider ID</label>
                        <select id="a_u_a_provider_id" name="a_u_a_provider_id" class="form-control">
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Referring Last Name *</label>
                        <select id="a_u_a_referring_last_name" name="a_u_a_referring_last_name" class="form-control">
                            <option value="Y">Alex</option>
                            <option value="N">John</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Referring first Name *</label>
                        <select id="a_u_a_referring_first_name" name="a_u_a_referring_first_name" class="form-control">
                            <option value="Y">Cam</option>
                            <option value="N">Pill</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Deceased</label>
                        <select id="a_u_a_decade" name="a_u_a_decade" class="form-control">
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Patient Name 2</label>
                        <input type="text" name="a_u_a_patient_name2" class="form-control" id="a_u_a_patient_name2"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 g_txt_right" id="a_add_user" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                    </div>
                    <div class="col-md-12 g_txt_right">
                        <button type="button" class="btn btn-info a_add_user_btn">Add Patient</button>
                        <a href="#" onclick="window.location.reload()" class="btn btn-danger">Reset</a>
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
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("#a_u_a_trackgps").change(function () {
        if (this.checked) {
            console.log('here1');
            $('#input-deviceid').modal('show');
        }
    });
    $("#a_u_a_dispatch").change(function () {
        if (this.checked) {
            $('#permitted_state').css("display", "block");
        } else {
            $('#permitted_state').css("display", "none");
        }
    })
    $(window).load(function()
    {
        var phones = [{ "mask": "(###) ###-####"}];
        $('#a_u_a_phone').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        $('#a_u_a_secondary_phone').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    });

</script>
