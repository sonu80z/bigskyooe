<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('admin/listitem/lists'); ?>" class="btn btn-default">LISTS LIST</a>
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
                <?php echo form_open(base_url('admin/listitem/create'), 'class="form-horizontal"');  ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">List Name * </label>
                        <select name="name" class="form-control">
                            <option selected="selected" disabled>Select</option>
                            <option value="division">Division</option>
                            <option value="exception">Exception</option>
                            <option value="icd">ICD Code 10</option>
                            <option value="insurance">Insurance</option>
                            <option value="modality">Modality</option>
                            <option value="pcategory">Procedure Category</option>
                            <option value="radiologist">Radiologist</option>
                            <option value="relationship">Relationship</option>
                            <option value="Lab">Lab</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Value * </label>
                        <input type="text" name="value" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Code/NPI/NOTE * </label>
                        <input type="text" name="code" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right" id="a_add_user" style="display:none;">
                        <input type="submit" name="submit" value="Add User" class="btn btn-info g_none_dis">
                    </div>
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                        <input type="submit" name="submit" value="Create" class="btn btn-info">
                        <a href="#" onclick="window.location.reload()" class="btn btn-danger" >Reset</a>
                    </div>
                </div>
                <?php echo form_close( ); ?>
            </div>
        </div>
    </div>
</section>