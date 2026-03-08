<!DOCTYPE html>
<html lang="en">
<head>
    <title>BIG SKY IMAGING</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="<?= base_url();?>public/dist/img/hico.ico" type="image/x-icon">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>public/dist/css/AdminLTE.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>public/dist/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/global.css">
    <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/admin.css">
    <!-- jQuery 2.2.3 -->
    <script src="<?= base_url() ?>public/plugins/jQuery/jquery-2.2.3.min.js"></script>

</head>
<!--
<body id="login-form">
<div class="container">
    <div class="row">

        <div class="col-md-4 col-md-offset-4 text-center">
            <div class="login-title">
                <img src="<?=base_url();?>public/dist/img/full_logo.png" class="sign_page_logo_img" />
            </div>
            <?php if(isset($msg) || validation_errors() !== ''): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                    <?= validation_errors();?>
                    <?= isset($msg)? $msg: ''; ?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('warning')): ?>
                <div class="alert alert-warning">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?=$this->session->flashdata('warning')?>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <?=$this->session->flashdata('success')?>
                </div>
            <?php endif; ?>
            <div class="form-box">
                <div class="caption">
                    <h4>Sign in to start your session</h4>
                </div>
                <?php echo form_open(base_url('admin/auth/google_authy/'.$user.'/'.$is_admin.'/'.$case), 'class="login-form" '); ?>
                <div class="input-group">
                    <label class="sign_page_label">Please enter token number</label>
                    <input type="password" name="token" class="form-control" placeholder="6 digit" >
                    <input type="submit" name="submit" id="submit" class="form-control" value="Submit">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
</body>
-->
<body class="auth_body">
<div class="container">
    <div class="a_auth_div">
        <div class="row">
            <div class="col-md-12">
                <div class="a_login_logo_img">
                    <img src="<?=base_url();?>public/dist/img/full_logo.png" class="a_auth_logo_img" />
                </div>
                <div class="a_login_div">
                    <div class="a_login_header">
                        2-FACTOR AUTHENTICATION
                    </div>
                    <?php if(isset($msg) || validation_errors() !== ''): ?>
                        <div class="alert alert-warning alert-dismissible a_auth_alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= validation_errors();?>
                            <?= isset($msg)? $msg: ''; ?>
                        </div>
                    <?php endif; ?>
                    <div class="a_login_content a_auth_2fa_login">
                        <div class="a_auth_explain">
                            Enter the 6-digit 2FA code generated <br>
                            by your authentication app.
                        </div>
                        <?php echo form_open(base_url('admin/auth/google_authy/'.$user.'/'.$is_admin.'/'.$case), 'class="login-form" '); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="username">2FA CODE</label>
                                <input type="text" id="token" name="token" placeholder="6 digit" />
                            </div>
                            <div class="col-md-6">
                                <div class="a_auth_2fa_submit_div"><input type="submit" name="submit" class="g_green_btn" value="SUBMIT" /></div>
                                <div class="a_login_forggot_pass_div">
                                    <a href="<?=base_url("admin/auth/no_2fa");?>" class="forgot-pass">DON’T HAVE YOUR <br> 2FA CODE?</a>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <div class="a_login_footer">
                    <img src="<?=base_url();?>public/dist/img/empty_footer_logo.png" class="sign_page_logo_img" />
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<!-- Bootstrap 3.3.6 -->
<script src="<?= base_url() ?>public/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url() ?>public/dist/js/app.min.js"></script>
</html>