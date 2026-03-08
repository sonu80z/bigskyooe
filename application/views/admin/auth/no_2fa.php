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
                        DON’T HAVE YOUR 2FA CODE?
                    </div>
                    <div class="a_login_content a_auth_no_2fa">
                        <div class="a_auth_explain">
                            Due to security reasons we are unable to reset <br>
                            your 2FA online. If you do not have access to <br>
                            your 2FA code please contact Digital Surety <br>
                            Support at <a href="support@digitalsurety.ch" style="color: blue; text-decoration: underline;">support@digitalsurety.ch</a> and a <br>
                            representative will assist you.
                        </div>
                        <div class="a_auth_no_2fa_back_login_div"><a href="<?=base_url("admin/auth/login");?>" class="g_green_btn">BACK TO LOGIN</a></div>
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