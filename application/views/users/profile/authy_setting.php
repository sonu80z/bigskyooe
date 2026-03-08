<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body with-border">
                <div class="col-md-6 a_page_top_title">
                    <?=$title;?>
                </div>
                <div class="col-md-6 text-right">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!--
                <div class="box-body my-form-body g_border_dv">
                    <input type="checkbox" id="a_2fa_is_enable" <?php if ( $user_info["is_2fa"] == "1") echo "checked";?> is_admin="1" /> <label for="a_2fa_is_enable"> Enable 2FA when login</label>
                </div> <br><br><br>
                -->
            <div class="box-body my-form-body g_border_dv">
                <?php if(validation_errors() !== ''): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                        <?= validation_errors();?>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6 a_2fa_code_dv">
                        <?php
                        if ( $user_info["auty_key"] == "" || $user_info["auty_key"] == null ) {
                            echo '<div>Did not set 2FA code <br>
                                            Please click reset button to set 2FA code. 
                                        </div>';
                        } else {
                            echo '<img src="'.$qrurl.'" />';
                        }
                        ?>
                    </div>
                    <div class="col-md-6 a_2fa_btn_dv">
                        <?php
                        echo form_open(base_url('users/profile/set_2fa_key'), 'class="form-horizontal"');
                        if ( $user_info["is_2fa"] == "0" ) {
                            echo '<input type="text" name="code" class="a_2fa_code_ipt" placeholder="2FA CODE" required /> <br>
                                        <input type="submit" name="enable" class="btn btn-primary" value="2FA Enable" />';
                        } else if ( $user_info["is_2fa"] == "1" ) {
                            echo '<input type="submit" name="disable" class="btn btn-danger" value="2FA Disable" />';
                        }
                        echo form_close( );
                        echo form_open(base_url('users/profile/set_2fa_key'), 'class="form-horizontal"'); ?>
                        <br><input type="submit" name="reset" value="Reset" class="btn btn-info" />
                        <?php echo form_close( ); ?>
                    </div>
                </div>
                <br><br><br>
            </div>
        </div>
    </div>

</section>