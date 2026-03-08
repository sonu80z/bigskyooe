<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/facility/lists'); ?>" class="btn btn-default">FACILITY LIST</a>
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
                echo form_open(base_url('admin/facility/create/'.$id), 'class="form-horizontal"');  ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Facility Name * </label>
                        <input type="text" name="facility_name" class="form-control" value="<?=(isset($id)?$facility["facility_name"]:"")?>" required />
                    </div>
                    <div class="col-sm-3">
                        <br>
                        <input type="checkbox" name="is_active" value="1" <?php if(isset($id)) if($facility["is_active"]=="1") echo "checked";?> id="af_is_active" /> &nbsp;
                        <label for="af_is_active" class="control-label">Active Account</label>
                    </div>
                    <div class="col-sm-3">
                        <br>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#myModal">Station</button>
                        <?php
                        $str = '';
                        if ( isset($id) ) {
                            foreach ( $stations as $row ) {
                                if ( $str != "" ) $str .= "###";
                                $str .= $row["StationName"]."&&&".$row["StationPhone"]."&&&".$row["StationFax"];
                            }
                        }
                        ?>
                        <input type="hidden" name="stations" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Facility Type *</label>
                        <select name="facility_type" class="form-control" required>
                            <option value="" disabled <?=(isset($id) && !empty($facility["facility_type"])?"":"selected")?>>Select Facility Type</option>
                            <option value="NURSING HOME" <?=(isset($id)?(($facility["facility_type"]=="NURSING HOME")?"selected":""):"")?>>NURSING HOME</option>
                            <option value="HOME BOUND" <?=(isset($id)?(($facility["facility_type"]=="HOME BOUND")?"selected":""):"")?>>HOME BOUND</option>
                            <option value="CORRECTIONAL FACILITY" <?=(isset($id)?(($facility["facility_type"]=="CORRECTIONAL FACILITY")?"selected":""):"")?>>CORRECTIONAL FACILITY</option>
                            <option value="LAB" <?=(isset($id)?(($facility["facility_type"]=="LAB")?"selected":""):"")?>>LAB</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Facility NPI </label>
                        <input type="text" name="facility_NPI" class="form-control" value="<?=isset($id)?$facility["facility_NPI"]:""?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Administrators Name </label>
                        <input type="text" name="admin_name" class="form-control" value="<?=isset($id)?$facility["admin_name"]:""?>" />
                    </div>
                    <div class="col-sm-6">
                        <br>
                        <input type="checkbox" name="is_pcc" value="1" <?=(isset($id)?(($facility["is_pcc"]=="1")?"checked":""):"")?> id="af_is_pcc" /> &nbsp;
                        <label for="af_is_pcc" class="control-label">PCC</label> &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="is_pf" value="1" <?=(isset($id)?(($facility["is_pf"]=="1")?"checked":""):"")?> id="af_is_pf" /> &nbsp;
                        <label for="af_is_pf" class="control-label">PF</label> &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="is_ts" value="1" <?=(isset($id)?(($facility["is_ts"]=="1")?"checked":""):"")?> id="af_is_ts" /> &nbsp;
                        <label for="af_is_ts" class="control-label">TS</label> &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="is_urad" value="1" <?=(isset($id)?(($facility["is_urad"]=="1")?"checked":""):"")?> id="af_is_urad" /> &nbsp;
                        <label for="af_is_urad" class="control-label">URAD</label> &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="is_ramsoft" value="1" <?=(isset($id)?(($facility["is_ramsoft"]=="1")?"checked":""):"")?> id="af_is_ramsoft" /> &nbsp;
                        <label for="af_is_ramsoft" class="control-label">RamSoft</label> &nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Street Address 1 *</label>
                        <input type="text" name="address1" class="form-control" value="<?=isset($id)?$facility["address1"]:""?>" required />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Street Address 2 </label>
                        <input type="text" name="address2" class="form-control" value="<?=isset($id)?$facility["address2"]:""?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">City * </label>
                        <input type="text" name="address_city" class="form-control" value="<?=isset($id)?$facility["address_city"]:""?>" required />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">State * </label>
                        <input type="text" name="address_state" class="form-control" value="<?=isset($id)?$facility["address_state"]:""?>" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Zip Code * </label>
                        <input type="text" name="address_zip" class="form-control" value="<?=isset($id)?$facility["address_zip"]:""?>" required />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Phone Number *</label>
                        <input type="text" name="phone" class="form-control" value="<?=isset($id)?$facility["phone"]:""?>" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Fax 1 *</label>
                        <input type="text" name="fax1" class="form-control" value="<?=isset($id)?$facility["fax1"]:""?>" placeholder="e.g., 7472050743" required />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Fax 2 </label>
                        <input type="text" name="fax2" class="form-control" value="<?=isset($id)?$facility["fax2"]:""?>" placeholder="e.g., 9212050742" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Fax 3 </label>
                        <input type="text" name="fax3" class="form-control" value="<?=isset($id)?$facility["fax3"]:""?>" placeholder="e.g., 7472050742" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Fax 4 </label>
                        <input type="text" name="fax4" class="form-control" value="<?=isset($id)?$facility["fax4"]:""?>" placeholder="Optional" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label class="control-label">Email </label>
                        <input type="email" name="email" class="form-control" value="<?=isset($id)?$facility["email"]:""?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <input type="checkbox" name="email_order" value="1" <?=(isset($id)?(($facility["email_order"]=="1")?"checked":""):"")?> id="af_email_order" /> &nbsp;
                        <label for="af_email_order" class="control-label">Email Order</label>
                    </div>
                    <div class="col-sm-6">
                        <input type="checkbox" name="hospise" value="1" <?=(isset($id)?(($facility["hospise"]=="1")?"checked":""):"")?> id="af_hospise" /> &nbsp;
                        <label for="af_hospise" class="control-label">Hospice</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Main State *</label>
                        <select name="main_state" class="form-control">
                            <option value="MT" <?=(isset($id)?(($facility["main_state"]=="MT")?"selected":""):"")?>>MT</option>
                            <option value="WY" <?=(isset($id)?(($facility["main_state"]=="WY")?"selected":""):"")?>>WY</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Billing Contact Name </label>
                        <input type="text" name="billing_contact" class="form-control" value="<?=isset($id)?$facility["billing_contact"]:""?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Billing Phone </label>
                        <input type="text" name="billing_phone" class="form-control" value="<?=isset($id)?$facility["billing_phone"]:""?>" />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Billing fax  </label>
                        <input type="text" name="billing_fax" class="form-control" value="<?=isset($id)?$facility["billing_fax"]:""?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label">Billing Rep </label>
                        <input type="text" name="billing_req" class="form-control" value="<?=isset($id)?$facility["billing_req"]:""?>" />
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Billing Account  </label>
                        <input type="text" name="billing_aa_num" class="form-control" value="<?=isset($id)?$facility["billing_aa_num"]:""?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">
                        <label class="control-label">Divisions </label>
                        <select name="af_divisions" class="form-control">
                            <option value="0">Select Division</option>
                            <?php
                            foreach ( $divisions as $row ) {
                                if ( isset($id) && $facility["division"] == $row["id"] ) {
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
                        <select name="af_subdivisions" class="form-control" <?=isset($id)?"":"disabled";?>>
                            <option value="0">Select Subdivision</option>
                            <?php
                            if ( isset( $id ) ) {
                                foreach ( $subdivisions as $row ) {
                                    if ( isset($id) && $facility["subdivision"] == $row["id"] ) {
                                        echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
                                    } else {
                                        echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Regions</label>
                        <select name="af_regions" class="form-control" <?=isset($id)?"":"disabled";?>>
                            <option value="0">Select Region</option>
                            <?php
                            if ( isset( $id ) ) {
                                foreach ( $regions as $row ) {
                                    if ( isset($id) && $facility["region"] == $row["id"] ) {
                                        echo '<option value="'.$row["id"].'" selected>'.$row["name"].'</option>';
                                    } else {
                                        echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                                    }
                                }
                            }
                            ?>
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
                        <a href="<?=base_url();?>admin/traders/add" class="btn btn-danger" >Reset</a>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 g_txt_right" id="a_add_user" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                    </div>
                    <div class="col-md-12 g_txt_right">
                        <input type="submit" name="submit" value="Add Facility" class="btn btn-info">
                        <a href="#" onclick="window.location.reload()" class="btn btn-danger" >Reset</a>
                    </div>
                </div>
                <?php echo form_close( ); ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Facility Stations</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label class="control-label">Name</label>
                        <input type="text" name="af_s_name" class="form-control" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Phone</label>
                        <input type="text" name="af_s_phone" class="form-control" placeholder="(555)555555" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">Fax</label>
                        <input type="text" name="af_s_fax" class="form-control" placeholder="(555)555555" />
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label">&nbsp;</label> <br>
                        <input type="button" class="btn btn-sm btn-primary ad_s_item_add" value="Add" />
                    </div>
                </div>
                <div class="row"><div class="col-sm-12 ad_s_tlt">Existing Stations</div></div>
                <div class="row ad_s_cnt_th">
                    <div class="col-sm-3">Name</div>
                    <div class="col-sm-3">Phone</div>
                    <div class="col-sm-3">Fax</div>
                    <div class="col-sm-3">Action</div>
                </div>
                <div class="ad_s_cnt">
                    <?php
                    if ( isset( $id ) )
                    {
                        foreach ( $stations as $row ) {
                            echo '<div class="row ad_s_cnt_td">
                        <div class="col-sm-3">'.$row["StationName"].'</div>
                        <div class="col-sm-3">'.$row["StationPhone"].'</div>
                        <div class="col-sm-3">'.$row["StationFax"].'</div>
                        <div class="col-sm-3"><input type="button" class="btn btn-xs btn-danger ad_s_item_del" value="Del" /></div>
                    </div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
