<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-body">
                <div class="col-md-6">
                    <h4><i class="fa fa-pencil"></i> &nbsp; <?=$title;?></h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?= base_url('users/profile'); ?>" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> &nbsp;Manager Profile</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                </div>
                <?php
                $username = $user_info["username"];
                $secret = $user_info["auty_key"];
                $url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".
                    "otpauth://totp/DigitalSurety:".$username."?secret=".$secret."&issuer=DigitalSurety";
                $txt = "<img src='".base_url()."public /dist/img/uncheck.png' />2FA Disabled";
                $etxt = "Enter password to enable 2FA";
                if ( $user_info["is_2fa"] == "1" ) {
                    $txt = "<img src='".base_url()."public /dist/img/check.png' />2FA Enabled";
                    $etxt = "Enter password to see QR or disable 2FA";
                }
                ?>

                <div class="col-md-9 f_customer_dash_right">
                    <div class="f_c_dl_cnt f_c_d_cp_ctn_dv">

                        <div class="row">
                            <div class="col-md-12 f_2fa_state_txt"><?=$txt;?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 f_2fa_detail_txt"><?=$etxt;?></div>
                            <div class="col-md-12 g_none_dis f_confirm_dv">
                                <input type="password" id="f_2fa_confirm_password" placeholder="Enter login password" />
                                <input type="button" class="btn btn-primary btn-xs" id="f_2fa_pass_confirm_btn" state="<?=$user_info["is_2fa"];?>" value="confirm">
                                <input type="button" class="btn btn-danger btn-xs" id="f_2fa_pass_cancel_btn" value="close">
                            </div>
                        </div>
                        <div class="f_2fa_action_dv g_none_dis">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="checkbox" id="f_customer_2fa_enable" <?php if ($customer["is_2fa"]=="1") echo "checked";?> />
                                    <label for="f_customer_2fa_enable">Enable 2FA when login</label>
                                </div>
                            </div>
<!--                            <div class="row">-->
<!--                                <div class="col-sm-12 col-md-4">-->
<!--                                    --><?php
//                                    if ( $user_info["is_2fa"] == "1" ) {
//                                        echo '<img src="'.$url.'" />';
//                                    }
//                                    ?>
<!--                                </div>-->
<!--                                <div class="col-sm-12 col-md-8 c_2fa_key">-->
<!--                                    --><?php
//                                    if ( $user_info["is_2fa"] == "1" ) {
//                                        echo $secret;
//                                    }
//                                    ?>
<!--                                </div>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg g_none_dis" id="c_2fa_enable_modal_btn" data-toggle="modal" data-target="#enable_modal">Open Modal</button>

<!-- Modal -->
<div id="enable_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm c_2fa_modal">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ENABLE 2FA</h4>
            </div>
            <div class="modal-body">
                <p>Please verify correct installation by entering 2FA code here and enable</p>
                <img src="<?=$url;?>" />
                <input type="password" class="g_ipt" id="c_2fa_enable_confirm_digit" placeholder="6 digit " />
            </div>
            <div class="modal-footer">
                <button type="button" id="c_2fa_confirm_submit_btn" class="btn btn-primary">Enable 2FA</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<button type="button" class="btn btn-info btn-lg g_none_dis" id="c_2fa_disable_modal_btn" data-toggle="modal" data-target="#disable_modal">Open Modal</button>
<div id="disable_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm c_2fa_modal">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DISABLE 2FA</h4>
            </div>
            <div class="modal-body">
                <!--                <p>Please disable 2FA</p>-->
                <img src="<?=$url;?>" />
                <p class="f_2fa_disable_secret_key"><?=$user_info["auty_key"];?></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="c_2fa_disable_submit_btn" class="btn btn-primary">Disable 2FA</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>