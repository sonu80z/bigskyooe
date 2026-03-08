<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/division/lists'); ?>" class="btn btn-default">DIVISION LIST</a>
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
                <div class="ad_type ad_type_0">
                    <?php echo form_open(base_url('admin/division/create/0'), 'class="form-horizontal"');  ?>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Division Name * </label>
                            <input type="text" name="name" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">GPS location * </label>
                            <input type="text" name="gps_location" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Division manager * </label>
                            <input type="text" name="division_manager" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Address  * </label>
                            <input type="text" name="address" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Phone Number  * </label>
                            <input type="text" name="phone" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Fax </label>
                            <input type="text" name="fax" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                            <input type="submit" name="submit" value="Add Division" class="btn btn-info">
                            <a href="#" onclick="window.location.reload()" class="btn btn-danger" >Reset</a>
                        </div>
                    </div>
                    <?php echo form_close( ); ?>
                </div>
                <div class="ad_type ad_type_1 g_none_dis">
                    <?php echo form_open(base_url('admin/division/create/1'), 'class="form-horizontal"');  ?>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Division</label>
                            <select name="parent" class="form-control" required>
                                <?php
                                foreach ( $divisions as $row ) {
                                    echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Division Name * </label>
                            <input type="text" name="name" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Division manager * </label>
                            <input type="text" name="division_manager" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Address  * </label>
                            <input type="text" name="address" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Phone Number  * </label>
                            <input type="text" name="phone" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Fax </label>
                            <input type="text" name="fax" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                            <input type="submit" name="submit" value="Add Subdivision" class="btn btn-info">
                            <a href="#" onclick="window.location.reload()" class="btn btn-danger" >Reset</a>
                        </div>
                    </div>
                    <?php echo form_close( ); ?>
                </div>
                <div class="ad_type ad_type_2 g_none_dis">
                    <?php echo form_open(base_url('admin/division/create/2'), 'class="form-horizontal"');  ?>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Subivision</label>
                            <select name="parent" class="form-control" required>
                                <?php
                                foreach ( $subdivisions as $row ) {
                                    echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Region Name * </label>
                            <input type="text" name="name" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Division manager * </label>
                            <input type="text" name="division_manager" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Address  * </label>
                            <input type="text" name="address" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Phone Number  * </label>
                            <input type="text" name="phone" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3">
                            <label class="control-label">Fax </label>
                            <input type="text" name="fax" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                            <input type="submit" name="submit" value="Add Region" class="btn btn-info">
                            <a href="#" onclick="window.location.reload()" class="btn btn-danger" >Reset</a>
                        </div>
                    </div>
                    <?php echo form_close( ); ?>
                </div>
            </div>
        </div>
    </div>
</section>