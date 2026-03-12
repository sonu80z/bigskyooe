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
                <?php 
                    // Detect if this is a physician-only entry
                    $is_physician = (strpos($user["username"], 'phys_') === 0);
                ?>
                <?php echo form_open(base_url('admin/users/update/' . $user["id"]), 'class="form-horizontal"'); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">First Name * </label>
                        <input type="text" name="a_u_a_firstname" class="form-control" id="a_u_a_firstname"
                               value="<?= $user["firstname"]; ?>" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Last Name * </label>
                        <input type="text" name="a_u_a_lastname" class="form-control" id="a_u_a_lastname"
                               value="<?= $user["lastname"]; ?>" required/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Prefix </label>
                        <input type="text" name="a_u_a_prefix" class="form-control" id="a_u_a_prefix"
                               value="<?= $user["prefix"]; ?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Suffix </label>
                        <input type="text" name="a_u_a_suffix" class="form-control" id="a_u_a_suffix"
                               value="<?= $user["suffix"]; ?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4">
                        <label class="control-label">Main Mobile No *</label>
                        <input type="text" name="a_u_a_main_mobile_no" class="form-control" id="a_u_a_main_mobile_no"
                               value="<?= $user["mainphone"]; ?>" required/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Mobile No</label>
                        <input type="text" name="a_u_a_mobile_no" class="form-control" id="a_u_a_mobile_no"
                               value="<?= $user["phone"]; ?>"/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Fax</label>
                        <input type="text" name="a_u_a_fax" class="form-control" id="a_u_a_fax"
                               value="<?= $user["fax"]; ?>"/>
                    </div>
                </div>

                <?php if (!$is_physician): ?>
                <div class="form-group" id="npi_field_row">
                    <div class="col-sm-6">
                        <label class="control-label">NPI</label>
                        <input type="text" name="a_u_a_npi" class="form-control" id="a_u_a_npi_user"
                               value="<?= $user["NPI"]; ?>"/>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- User Account Fields -->
                <div id="user_account_fields" <?php if ($is_physician) echo 'style="display: none;"'; ?>>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">User Name * </label>
                        <input type="text" name="a_u_a_username" class="form-control" id="a_u_a_username"
                               value="<?= $user["username"]; ?>" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Email</label>
                        <input type="email" name="a_u_a_email" class="form-control" id="a_u_a_email"
                               value="<?= $user["email"]; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Role *</label>
                        <select name="a_u_a_role" class="form-control">
                            <option value="1" <?php if ($user['role'] == 1) echo "selected"; ?>>Super Admin</option>
                            <option value="2" <?php if ($user['role'] == 2) echo "selected"; ?>>Admin</option>
                            <option value="3" <?php if ($user['role'] == 3) echo "selected"; ?>>Coder</option>
                            <option value="4" <?php if ($user['role'] == 4) echo "selected"; ?>>Dispatcher</option>
                            <option value="5" <?php if ($user['role'] == 5) echo "selected"; ?>>Staff</option>
                            <option value="6" <?php if ($user['role'] == 6) echo "selected"; ?>>Facility User</option>
                            <option value="7" <?php if ($user['role'] == 7) echo "selected"; ?>>Ordering Physician</option>
                            <option value="8" <?php if ($user['role'] == 8) echo "selected"; ?>>Technologist</option>                            
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Main State *</label>
                        <select name="a_u_a_state" class="form-control">
                            <?php
                            foreach($states as $key => $info){
                                $selected = "";
                                if($info['fldSt'] == $user['mainstate']){
                                    $selected = "selected";
                                }
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
                                if($info['fldSt'] == $user['secondarystate']){
                                    $selected = "selected";
                                }
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
                </div>
                
                <!-- Ordering Physician Only Fields -->
                <div id="physician_only_fields" <?php if (!$is_physician) echo 'style="display: none;"'; ?>>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Physician's NPI Number * </label>
                        <input type="text" name="a_u_a_npi" class="form-control" id="a_u_a_npi"
                               value="<?= $user["NPI"]; ?>" <?php if ($is_physician) echo 'required'; ?>/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Facility *</label>
                        <select id="a_u_a_facility" name="a_u_a_facility[]" class="form-control" multiple data-live-search="true" <?php if ($is_physician) echo 'required'; ?>>
                            <?php 
                            $user_facilities = explode("=", $user["facility"]);
                            foreach ($facilities as $facility): ?>
                                <option value="<?php echo $facility["id"]?>"
                                <?php if (in_array($facility["id"], $user_facilities)) echo "selected"; ?>>
                                    <?php echo $facility["facility_name"]?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Email </label>
                        <input type="email" name="a_u_a_email_physician" class="form-control" id="a_u_a_email_physician"
                               value="<?= $user["email"]; ?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Main State *</label>
                        <select name="a_u_a_state_physician" class="form-control" id="a_u_a_state_physician" <?php if ($is_physician) echo 'required'; ?>>
                            <?php
                            foreach($states as $key => $info){
                                $selected = "";
                                if($info['fldSt'] == $user['mainstate']){
                                    $selected = "selected";
                                }
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
                    <div class="col-md-12 g_txt_right" id="approve" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info">
                        <a href="<?= base_url(); ?>admin/traders/add" class="btn btn-danger">Reset</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 row">
                        <div class="col-md-6">
                            <input type="checkbox" name="a_u_a_dispatch" id="a_u_a_dispatch"<?php if ($user['permittedtodispatch'] == 1) echo "checked"; ?>/> &nbsp;
                            <label for="a_u_a_dispatch" class="control-label">Permitted to dispatch?</label>
                        </div>
                        <div class="col-md-6" id="permitted_state" style="display: none">
                            <label class="control-label">Permitted State</label>
                            <select name="a_u_a_permitted_state[]" id="a_u_a_permitted_state" class="form-control a_u_a_permitted_state" multiple data-live-search="true">
                                <?php 
                                $user_permitted_states = explode("=", $user["permittedstate"]);
                                if(isset($states) && is_array($states)) {
                                    foreach($states as $state) {
                                        $selected = in_array($state['fldSt'], $user_permitted_states) ? 'selected' : '';
                                        echo '<option value="'.$state['fldSt'].'" '.$selected.'>'.$state['fldState'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <input type="checkbox" name="a_u_a_change_pwd"
                               id="a_u_a_change_pwd" <?php if ($user['pwchange'] == 1) echo "checked"; ?> /> &nbsp;
                        <label for="a_u_a_change_pwd" class="control-label">Force Password Change at Next Login </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="checkbox" name="a_u_a_also_technologist" id="a_u_a_also_technologist" <?php if (!empty($user['also_technologist'])) echo 'checked'; ?>/> &nbsp;
                        <label for="a_u_a_also_technologist" class="control-label">Works as technologist as well <small class="text-muted">(user can be dispatched to orders)</small></label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 g_txt_right" id="a_add_user" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                    </div>
                    <div class="col-md-12 g_txt_right">
                        <button type="submit" class="btn btn-info">Update User</button>
                        <a href="<?= base_url('admin/users'); ?>" class="btn btn-danger">Cancel</a>
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
                                <input type="text" name="a_u_a_deviceid" class="form-control" id="a_u_a_deviceid"
                                       value="<?= $user["deviceid"]; ?>"/>
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
<script type="text/javascript">
    $(document).ready(function () {
        // Initialize selectpicker for facility and permitted state
        $("#a_u_a_facility").selectpicker();
        $(".a_u_a_permitted_state").selectpicker();
        
        // Show permitted state if dispatch checkbox is checked
        if ($('#a_u_a_dispatch').is(":checked")){
            $('#permitted_state').css("display", "block");
            $(".a_u_a_permitted_state").selectpicker('refresh');
        }
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
        } else {
            $('#permitted_state').css("display", "none");
        }
    });
    
    $(window).load(function()
    {
        var phones = [{ "mask": "(###) ###-####"}];
        $('#a_u_a_mobile_no').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        $('#a_u_a_main_mobile_no').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        $('#a_u_a_mobile_no_phys').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
        $('#a_u_a_main_mobile_no_phys').inputmask({
            mask: phones,
            greedy: false,
            definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
    });
</script>
