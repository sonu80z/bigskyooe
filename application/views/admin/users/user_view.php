<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
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
                <?= validation_errors();?>
                <div></div>
            </div>
            <?php 
                // Detect if this is a physician-only entry
                $is_physician = (strpos($user["username"], 'phys_') === 0);
            ?>
            <?php echo form_open(base_url('admin/users/edit'.$user["id"]), 'class="form-horizontal"');  ?>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label">First Name * </label>
                    <input type="text" name="a_u_a_firstname" class="form-control" id="a_u_a_firstname" value="<?=$user["firstname"];?>" required disabled/>
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Last Name * </label>
                    <input type="text" name="a_u_a_lastname" class="form-control" id="a_u_a_lastname" value="<?=$user["lastname"];?>" required disabled />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label">Prefix </label>
                    <input type="text" name="a_u_a_prefix" class="form-control" id="a_u_a_prefix" value="<?=$user["prefix"];?>" disabled/>
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Suffix </label>
                    <input type="text" name="a_u_a_suffix" class="form-control" id="a_u_a_suffix" value="<?=$user["suffix"];?>" disabled />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label class="control-label">Main Mobile No</label>
                    <input type="text" name="a_u_a_main_mobile_no" class="form-control" id="a_u_a_main_mobile_no" value="<?=$user["mainphone"];?>" disabled/>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Mobile No</label>
                    <input type="text" name="a_u_a_mobile_no" class="form-control" id="a_u_a_mobile_no" value="<?=$user["phone"];?>" disabled/>
                </div>
                <div class="col-sm-4">
                    <label class="control-label">Fax</label>
                    <input type="text" name="a_u_a_fax" class="form-control" id="a_u_a_fax" value="<?=$user["fax"];?>" disabled/>
                </div>
            </div>
            
            <!-- User Account Fields -->
            <div id="user_account_fields" <?php if ($is_physician) echo 'style="display: none;"'; ?>>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label">User Name * </label>
                  <input type="text" name="a_u_a_username" class="form-control" id="a_u_a_username" value="<?=$user["username"];?>" required disabled/>
                </div>
                  <div class="col-sm-6">
                      <label class="control-label">Email * </label>
                      <input type="email" name="a_u_a_email" class="form-control" id="a_u_a_email" value="<?=$user["email"];?>" required disabled/>
                  </div>
              </div>
            <!--<div class="form-group">
              <div class="col-sm-6">
                  <label class="control-label">Password * </label>
                <input type="password" name="a_u_a_password" class="form-control" id="a_u_a_password" required />
              </div>
                <div class="col-sm-6">
                    <label class="control-label">Retype Password *</label>
                    <input type="password" name="a_u_a_rpassword" class="form-control" id="a_u_a_rpassword" required />
                </div>
            </div> -->
            <div class="form-group">
                <div class="col-sm-3">
                      <label class="control-label">Role *</label>
                      <select name="a_u_a_role" class="form-control" disabled>
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
                      <select name="a_u_a_state" class="form-control" disabled>
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
                  <div class="col-sm-6">
                      <br>
                      <input type="checkbox" name="a_u_a_trackgps" id="a_u_a_trackgps" disabled /> &nbsp;
                      <label for="a_u_a_trackgps" class="control-label">Track GPS</label>
                  </div>
              </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <label class="control-label">Divisions </label>
                    <select name="af_divisions" class="form-control" disabled>
                        <option value="0">Select Division</option>
                        <?php
                        foreach ( $divisions as $row ) {
                            if ( $user['division'] == $row['id'] ) {
                                echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
                            } else {
                                echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label class="control-label">Subdivisions</label>
                    <select name="af_subdivisions" class="form-control" disabled>
                        <option value="0">Select Subdivision</option>
                        <?php
                            foreach ( $subdivisions as $row ) {
                                if ( $user['subdivision'] == $row['id'] ) {
                                    echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
                                } else {
                                    echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                                }
                            }
                        ?>
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
                <div class="col-sm-6">
                    <input type="checkbox" name="a_u_a_dispatch" id="a_u_a_dispatch" disabled <?php if($user['acn_dispatch']==1) echo "checked";?>/> &nbsp;
                    <label for="a_u_a_dispatch" class="control-label">Permitted to dispatch?</label>
                </div>
                <div class="col-sm-6">
                    <input type="checkbox" name="a_u_a_change_pwd" id="a_u_a_change_pwd" disabled <?php if($user['pwchange']==1) echo "checked";?> /> &nbsp;
                    <label for="a_u_a_change_pwd" class="control-label">Force Password Change at Next Login </label>
                </div>
            </div>
            </div>
            
            <!-- Ordering Physician Only Fields -->
            <div id="physician_only_fields" <?php if (!$is_physician) echo 'style="display: none;"'; ?>>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label">Physician's NPI Number </label>
                    <input type="text" name="a_u_a_npi" class="form-control" id="a_u_a_npi" value="<?=$user["NPI"];?>" disabled/>
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Facility </label>
                    <select id="a_u_a_facility" name="a_u_a_facility[]" class="form-control" multiple data-live-search="true" disabled>
                        <?php
                        $user_facilities = explode("=", $user["facility"]);
                        for ( $i = 0; $i < count($facilities); $i ++ ) {
                            $selected = in_array($facilities[$i]["id"], $user_facilities) ? 'selected' : '';
                            echo '<option value="'.$facilities[$i]["id"].'" '.$selected.'>'.$facilities[$i]["facility_name"].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label">Email</label>
                    <input type="email" name="a_u_a_email_physician" class="form-control" id="a_u_a_email_physician" value="<?=$user["email"];?>" disabled/>
                </div>
                <div class="col-sm-6">
                    <label class="control-label">Main State *</label>
                    <select name="a_u_a_state_physician" class="form-control" disabled>
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
            <?php echo form_close( ); ?>
      </div>
    </div>
  </div>
</section>
