<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?= $title; ?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/users'); ?>" class="btn btn-default">USER LIST</a>
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
                <?php echo form_open(base_url('admin/users/create'), 'class="form-horizontal"'); ?>
                
                <!-- Add Type Selection -->
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Add as: *</label>
                        <div style="margin-top: 10px;">
                            <label style="margin-right: 30px;">
                                <input type="radio" name="add_type" id="add_type_user" value="user" checked /> 
                                User Account
                            </label>
                            <label>
                                <input type="radio" name="add_type" id="add_type_physician" value="physician" /> 
                                Ordering Physician Only
                            </label>
                        </div>
                    </div>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">First Name * </label>
                        <input type="text" name="a_u_a_firstname" class="form-control" id="a_u_a_firstname" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Last Name * </label>
                        <input type="text" name="a_u_a_lastname" class="form-control" id="a_u_a_lastname" required/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Prefix </label>
                        <input type="text" name="a_u_a_prefix" class="form-control" id="a_u_a_prefix"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Suffix </label>
                        <input type="text" name="a_u_a_suffix" class="form-control" id="a_u_a_suffix"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4">
                        <label class="control-label">Main Mobile No *</label>
                        <input type="text" name="a_u_a_main_mobile_no" class="form-control" id="a_u_a_main_mobile_no" required/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Mobile No</label>
                        <input type="text" name="a_u_a_mobile_no" class="form-control" id="a_u_a_mobile_no"/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Fax</label>
                        <input type="text" name="a_u_a_fax" class="form-control" id="a_u_a_fax"/>
                    </div>
                </div>
                
                <!-- User Account Fields -->
                <div id="user_account_fields">
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">User Name * </label>
                        <input type="text" name="a_u_a_username" class="form-control" id="a_u_a_username" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Email </label>
                        <input type="email" name="a_u_a_email" class="form-control" id="a_u_a_email"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Password * </label>
                        <input type="password" name="a_u_a_password" class="form-control" id="a_u_a_password" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Retype Password *</label>
                        <input type="password" name="a_u_a_rpassword" class="form-control" id="a_u_a_rpassword"
                               required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Role *</label>
                        <select name="a_u_a_role" class="form-control" id="a_u_a_role">
                            <option value="1">Super Admin</option>
                            <option value="2">Admin</option>
                            <option value="3">Coder</option>
                            <option value="4">Dispatcher</option>
                            <option value="5">Staff</option>
                            <option value="6">Facility User</option>
                            <option value="7">Ordering Physician</option>
                            <option value="8">Technologist</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Main State *</label>
                        <select name="a_u_a_state" class="form-control" id="a_u_a_state">
                            <?php
                            foreach($states as $key => $info){
                                $selected = "";
                                ?>
                                <option value="<?php echo $info['fldSt']; ?>" <?php echo $selected;?>><?php echo $info['fldState']; ?></option>
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
                                $selected = "";
                                ?>
                                <option value="<?php echo $info['fldSt']; ?>" <?php echo $selected;?>><?php echo $info['fldState']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <br>
                        <input type="checkbox" name="a_u_a_trackgps" id="a_u_a_trackgps"/> &nbsp;
                        <label for="a_u_a_trackgps" class="control-label">Track GPS</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 row">
                        <div class="col-md-6">
                            <input type="checkbox" name="a_u_a_dispatch" id="a_u_a_dispatch"/> &nbsp;
                            <label for="a_u_a_dispatch" class="control-label">Permitted to dispatch?</label>
                        </div>
                        <div class="col-md-6" id="permitted_state" style="display: none">
                            <label class="control-label">Permitted State</label>
                            <select name="a_u_a_permitted_state[]" id="a_u_a_permitted_state" class="form-control a_u_a_permitted_state" multiple data-live-search="true">
                                <?php
                                if(isset($states) && is_array($states)) {
                                    foreach($states as $state) {
                                        echo '<option value="'.$state['fldSt'].'">'.$state['fldState'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <input type="checkbox" name="a_u_a_change_pwd" id="a_u_a_change_pwd"/> &nbsp;
                        <label for="a_u_a_change_pwd" class="control-label">Force Password Change at Next Login </label>
                    </div>
                </div>
                </div>
                
                <!-- Ordering Physician Only Fields -->
                <div id="physician_only_fields" style="display: none;">
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Physician's NPI Number * </label>
                        <input type="text" name="a_u_a_npi" class="form-control" id="a_u_a_npi"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Facility *</label>
                        <select id="a_u_a_facility" name="a_u_a_facility[]" class="form-control" multiple data-live-search="true">
                            <?php
                            for ($i = 0; $i < count($facilities); $i++) {
                                echo '<option value="' . $facilities[$i]["id"] . '">' . $facilities[$i]["facility_name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Email </label>
                        <input type="email" name="a_u_a_email_physician" class="form-control" id="a_u_a_email_physician"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Main State *</label>
                        <select name="a_u_a_state_physician" class="form-control" id="a_u_a_state_physician">
                            <?php
                            foreach($states as $key => $info){
                                $selected = "";
                                ?>
                                <option value="<?php echo $info['fldSt']; ?>" <?php echo $selected;?>><?php echo $info['fldState']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                </div>
                

                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="checkbox" name="a_u_a_also_technologist" id="a_u_a_also_technologist"/> &nbsp;
                        <label for="a_u_a_also_technologist" class="control-label">Works as technologist as well <small class="text-muted">(user can be dispatched to orders)</small></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 g_txt_right">
                        <button type="button" class="btn btn-info a_add_user_btn" id="submit_user_btn">Add User</button>
                        <button type="button" class="btn btn-info a_add_physician_btn" id="submit_physician_btn" style="display: none;">Add Physician</button>
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
    $(document).ready(function () {
        // Initialize selectpicker for facility and permitted state
        $("#a_u_a_facility").selectpicker();
        $(".a_u_a_permitted_state").selectpicker();
        
        // Handle add type selection
        $('input[name="add_type"]').on('change', function() {
            var addType = $('input[name="add_type"]:checked').val();
            
            if (addType === 'physician') {
                // Show physician fields, hide user account fields
                $('#user_account_fields').hide();
                $('#physician_only_fields').show();
                $('#submit_user_btn').hide();
                $('#submit_physician_btn').show();
                
                // Remove required from user account fields
                $('#a_u_a_username, #a_u_a_password, #a_u_a_rpassword, #a_u_a_role, #a_u_a_state').removeAttr('required');
                
                // Add required to physician fields
                $('#a_u_a_npi').attr('required', 'required');
                $('#a_u_a_facility').attr('required', 'required');
                $('#a_u_a_state_physician').attr('required', 'required');
            } else {
                // Show user account fields, hide physician fields
                $('#user_account_fields').show();
                $('#physician_only_fields').hide();
                $('#submit_user_btn').show();
                $('#submit_physician_btn').hide();
                
                // Add required to user account fields
                $('#a_u_a_username, #a_u_a_password, #a_u_a_rpassword, #a_u_a_role, #a_u_a_state').attr('required', 'required');
                
                // Remove required from physician fields
                $('#a_u_a_npi, #a_u_a_facility, #a_u_a_state_physician').removeAttr('required');
            }
        });
        
        // Handle user account button click
        $('#submit_user_btn').on('click', function() {
            var required_fields = [
                {name: "a_u_a_firstname", msg: "First name is required"},
                {name: "a_u_a_lastname", msg: "Last name is required"},
                {name: "a_u_a_username", msg: "User name is required"},
                {name: "a_u_a_password", msg: "Password is required"},
                {name: "a_u_a_rpassword", msg: "Retype password is required"},
                {name: "a_u_a_role", msg: "Role is required"},
                {name: "a_u_a_state", msg: "Main state is required"},
                {name: "a_u_a_main_mobile_no", msg: "Main mobile no is required"},
            ];
            
            var isValid = true;
            for(var i = 0; i < required_fields.length; i++) {
                var field = required_fields[i];
                if($("[name='" + field['name'] + "']").val() == "") {
                    $("#a_admin_add_alert").removeClass("g_none_dis");
                    $("#a_admin_add_alert").children("div").html(field['msg']);
                    isValid = false;
                    break;
                }
            }
            
            if(!isValid) return false;
            
            // Don't add hidden field for user account - it will default to 'user' in controller
            $('form').submit();
        });
        
        // Handle physician button click
        $('#submit_physician_btn').on('click', function() {
            var required_fields = [
                {name: "a_u_a_firstname", msg: "First name is required"},
                {name: "a_u_a_lastname", msg: "Last name is required"},
                {name: "a_u_a_npi", msg: "Physician's NPI number is required"},
                {name: "a_u_a_main_mobile_no", msg: "Main mobile no is required"},
                {name: "a_u_a_state_physician", msg: "Main state is required"}
            ];
            
            var isValid = true;
            for(var i = 0; i < required_fields.length; i++) {
                var field = required_fields[i];
                if($("[name='" + field['name'] + "']").val() == "") {
                    $("#a_admin_add_alert").removeClass("g_none_dis");
                    $("#a_admin_add_alert").children("div").html(field['msg']);
                    isValid = false;
                    break;
                }
            }
            
            if(!isValid) return false;
            
            // Check facility is selected
            if($('#a_u_a_facility').val() == null || $('#a_u_a_facility').val().length == 0) {
                $("#a_admin_add_alert").removeClass("g_none_dis");
                $("#a_admin_add_alert").children("div").html("At least one facility is required");
                return false;
            }
            
            // Add a hidden input to indicate this is a physician-only submission
            $('form').append('<input type="hidden" name="add_type_value" value="physician">');
            $('form').submit();
        });
    });
    
    $("#a_u_a_trackgps").change(function () {
        if (this.checked) {
            console.log('here1');
            
            $('#input-deviceid').modal('show');
        }
    });
    
    $("#a_u_a_dispatch").change(function () {
        if (this.checked) {
            $('#permitted_state').css("display", "block");
            $(".a_u_a_permitted_state").selectpicker('refresh');
            console.log('checked!');
        } else {
            $('#permitted_state').css("display", "none");
        }
    });
    
    $(window).load(function()
    {
        var phones = [{ "mask": "(###) ###-####"}];
        $('#a_u_a_main_mobile_no').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    });
</script>
