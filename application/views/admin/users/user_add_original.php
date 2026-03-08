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
                        <label class="control-label">User Name * </label>
                        <input type="text" name="a_u_a_username" class="form-control" id="a_u_a_username" required/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Email * </label>
                        <input type="email" name="a_u_a_email" class="form-control" id="a_u_a_email" required/>
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
                    <div class="col-sm-6">
                        <label class="control-label">Physician's NPI Number </label>
                        <input type="text" name="a_u_a_npi" class="form-control" id="a_u_a_npi"/>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">
                        </label>
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
                    <div class="col-sm-4">
                        <label class="control-label">Main Mobile No </label>
                        <input type="text" name="a_u_a_main_mobile_no" class="form-control" id="a_u_a_main_mobile_no"
                               required/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Mobile No *</label>
                        <input type="text" name="a_u_a_mobile_no" class="form-control" id="a_u_a_mobile_no" required/>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Fax</label>
                        <input type="text" name="a_u_a_fax" class="form-control" id="a_u_a_fax"/>
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
                        <select name="a_u_a_role" class="form-control">
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
                        <select name="a_u_a_state" class="form-control">
                            <option value="1">Arizona</option>
                            <option value="2">California</option>
                            <option value="3">Colorado</option>
                            <option value="4">Utah</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Secondary State *</label>
                        <select name="a_u_a_secondary_state" class="form-control">
                            <option value="1">Arizona</option>
                            <option value="2">California</option>
                            <option value="3">Colorado</option>
                            <option value="4">Utah</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <br>
                        <input type="checkbox" name="a_u_a_trackgps" id="a_u_a_trackgps"/> &nbsp;
                        <label for="a_u_a_trackgps" class="control-label">Track GPS</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Divisiosn </label>
                        <select name="af_divisions" class="form-control">
                            <option value="0">Select Division</option>
                            <?php
                            foreach ($divisions as $row) {
                                echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Subdivisions</label>
                        <select name="af_subdivisions" class="form-control" disabled>
                            <option value="0">Select Subdivision</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Regions</label>
                        <select name="af_regions" class="form-control" disabled>
                            <option value="0">Select Region</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Zone</label>
                        <select name="af_zone" class="form-control" disabled>
                            <option value="0">Select Zone</option>
                        </select>
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
                            <input type="checkbox" name="a_u_a_dispatch" id="a_u_a_dispatch"/> &nbsp;
                            <label for="a_u_a_dispatch" class="control-label">Permitted to dispatch?</label>
                        </div>
                        <div class="col-md-6" id="permitted_state" style="display: none">
                            <label class="control-label">Permitted State</label>
                            <select name="a_u_a_permitted_state[]" id="a_u_a_permitted_state" class="form-control" multiple data-live-search="true">
                                <option value="1">Arizona</option>
                                <option value="2">California</option>
                                <option value="3">Colorado</option>
                                <option value="4">Utah</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <input type="checkbox" name="a_u_a_change_pwd" id="a_u_a_change_pwd"/> &nbsp;
                        <label for="a_u_a_change_pwd" class="control-label">Force Password Change at Next Login </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 g_txt_right" id="a_add_user" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                    </div>
                    <div class="col-md-12 g_txt_right">
                        <button type="button" class="btn btn-info a_add_user_btn">Add User</button>
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
    $("select[id='a_u_a_permitted_state']").selectpicker();
    $("#a_u_a_dispatch").change(function () {
        if (this.checked) {
            $('#permitted_state').css("display", "block");
            console.log('checked!');

        } else {
            $('#permitted_state').css("display", "none");
        }
    })
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
    });

</script>
