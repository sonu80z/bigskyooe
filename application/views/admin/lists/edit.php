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
                <?php echo form_open(base_url('admin/listitem/update/'.$list['id']), 'class="form-horizontal"');  ?>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">List Name * </label>
                        <select name="name" class="form-control">
                            <option selected="selected" disabled>Select</option>
                            <?php
                            $categories = array(
                                'division' => 'Division',
                                'exception' => 'Exception',
                                'icd' => 'ICD Code 10',
                                'insurance_company' => 'Insurance Company',
                                'insurance_type' => 'Insurance Type',
                                'modality' => 'Modality',
                                'pcategory' => 'Procedure Category',
                                'radiologist' => 'Radiologist',
                                'relationship' => 'Relationship',
                                'Lab' => 'Lab'
                            );
                            foreach($categories as $cat => $label) {
                                $selected = (isset($list['name']) && $list['name'] == $cat) ? 'selected' : '';
                                echo '<option value="'.htmlspecialchars($cat).'" '.$selected.'>'.htmlspecialchars($label).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Value * </label>
                        <input type="text" name="value" class="form-control" required value="<?= htmlspecialchars($list['value']); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label class="control-label">Code/NPI/NOTE * </label>
                        <input type="text" name="code" class="form-control" required value="<?= htmlspecialchars($list['code']); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3 g_txt_right">
                        <input type="submit" name="submit" value="Update" class="btn btn-info">
                        <a href="<?= base_url('admin/listitem/lists'); ?>" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
                <?php echo form_close( ); ?>
            </div>
        </div>
    </div>
</section>
