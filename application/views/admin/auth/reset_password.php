<!DOCTYPE html>
<html lang="en">
    <head>
          <title><?=isset($title)?$title:'Login - AdminLite' ?></title>
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
                            RESET PASSWORD
                        </div>
                        <?php if(isset($msg) || validation_errors() !== ''): ?>
                            <div class="alert alert-warning alert-dismissible a_auth_alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?= validation_errors();?>
                                <?= isset($msg)? $msg: ''; ?>
                            </div>
                        <?php endif; ?>
                        <div class="a_login_content">
                            <?php echo form_open(base_url('admin/auth/reset_password_proc/'.$email), 'class="login-form" '); ?>
                            <label for="username">NEW PASSWORD</label>
                            <input type="password" id="password" name="password" placeholder="" />
                            <label for="password">CONFIRM NEW PASSWORD</label>
                            <input type="Password" id="confirm_password" name="confirm_password" placeholder="" />
                            <div><input type="submit" name="submit" class="g_green_btn" value="RESET PASSWORD" /></div>
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