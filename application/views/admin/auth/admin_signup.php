<!DOCTYPE html>
<html lang="en">
<head>
    <title>BIG SKY IMAGING</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="<?= base_url();?>public/dist/img/hico.ico" type="image/x-icon">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>public/front/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>public/dist/css/AdminLTE.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>public/dist/css/style.css">
    <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/global.css">
    <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/admin.css">
    <!-- jQuery 2.2.3 -->
    <script src="<?= base_url() ?>public/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?= base_url() ?>public/custom/js/back/auth.js"></script>

</head>
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
                        ADMIN ACCOUNT SETUP
                    </div>
                    <?php if(isset($msg)): ?>
                        <div class="alert alert-warning alert-dismissible a_auth_alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?= validation_errors();?>
                            <?= isset($msg)? $msg: ''; ?>
                        </div>
                    <?php endif; ?>
                    <div class="a_login_content a_admin_signup">
                        <?php echo form_open(base_url('admin/auth/admin_signup'), 'class="login-form" '); ?>
                        <label for="username">FIRSTNAME</label>
                        <input type="text" id="firstname" value="<?php if (isset($admin)) echo $admin["firstname"];?>" name="firstname" required />
                        <label for="username">LASTNAME</label>
                        <input type="text" id="lastname" value="<?php if (isset($admin)) echo $admin["lastname"];?>" name="lastname" required />
                        <label for="username">EMAIL ADDRESS</label>
                        <input type="email" id="email" name="email" value="<?=$invite_email;?>" readonly required />
                        <label for="username">USERNAME</label>
                        <input type="text" id="username" value="<?php if (isset($admin)) echo $admin["username"];?>" name="username" required />
                        <label for="phone">PHONE NUMBER</label>
                        <input type="text" id="phone" value="<?php if (isset($admin)) echo $admin["phone"];?>" name="phone" required />
                        <label for="username">PASSWORD</label>
                        <input type="password" id="password" value="<?php if (isset($admin)) echo $admin["password"];?>" name="password" required />
                        <label for="password">CONFIRM PASSWORD</label>
                        <input type="password" id="cpassword" value="" name="cpassword" required />
                        <input type="hidden" id="key" value="<?=$key;?>" name="key"/>
                        <div><input type="submit" name="submit" class="g_green_btn" value="SIGN UP" /></div>
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
