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
                <?php if ( isset($is_email_sent) ) {  if ( $is_email_sent == "1" ) { ?>
                    <div class="f_forgot_pass_email_sent_div">
                        <div>We sent you an email with a link to reset your password. <i class="material-icons">clear</i></div>
                    </div>
                <?php } } ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="a_login_logo_img">
                            <img src="<?=base_url();?>public/dist/img/full_logo.png" class="a_auth_logo_img" />
                        </div>
                        <div class="a_login_div">
                            <div class="a_login_header">
                                ADMINISTRATOR LOGIN
                            </div>
                            <?php if(isset($msg) || validation_errors() !== ''): ?>
                                <div class="alert alert-warning alert-dismissible a_auth_alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <?= validation_errors();?>
                                    <?= isset($msg)? $msg: ''; ?>
                                </div>
                            <?php endif; ?>
                            <div class="a_login_content">
                                <?php echo form_open(base_url('admin/auth/login'), 'class="login-form" '); ?>
                                <label for="username">USERNAME</label>
                                <input type="text" id="username" name="username" placeholder="" />
                                <label for="password">PASSWORD</label>
                                <input type="Password" id="password" name="password" placeholder="" />
                                <div><input type="submit" name="submit" class="g_green_btn" value="LOGIN" /></div>
                                <div class="a_login_forggot_pass_div">
                                    <a href="<?=base_url("admin/auth/forgot_password");?>" class="forgot-pass">FORGOT YOUR PASSWORD?</a>
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