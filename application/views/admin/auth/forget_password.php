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
                    <?php if(validation_errors() !== ''): ?>
                      <div class="alert alert-warning alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                          <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                          <?= validation_errors();?>
                          <?= isset($msg)? $msg: ''; ?>
                      </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                          <?=$this->session->flashdata('success')?>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('warning')): ?>
                        <div class="alert alert-warning">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                          <?=$this->session->flashdata('warning')?>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                          <?=$this->session->flashdata('error')?>
                        </div>
                    <?php endif; ?>
                    <div class="form-box">
                        <div class="caption">
                        </div>
                        <?php echo form_open(base_url('auth/forgot_password'), 'class="login-form" '); ?>
                            <div class="">
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" >
                                <input type="submit" name="submit" id="submit" class="form-control" value="Submit">
                                <p class="text-center"><a href="<?= base_url('admin/auth/login'); ?>">You remember Password? Sign In </a></p>
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
                                FORGOT YOUR PASSWORD?
                            </div>
                            <?php if(isset($msg) || validation_errors() !== ''): ?>
                                <div class="alert alert-warning alert-dismissible a_auth_alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <?= validation_errors();?>
                                    <?= isset($msg)? $msg: ''; ?>
                                </div>
                            <?php endif; ?>
                            <div class="a_login_content">
                                <div class="a_auth_explain">
                                    Enter your email address to request <br>
                                    a password reset.
                                </div>
                                <?php echo form_open(base_url('admin/auth/forgot_password'), 'class="login-form" '); ?>
                                <label for="username">EMAIL ADDRESS</label>
                                <input type="text" id="email" name="email" placeholder="" />
                                <div><input type="submit" name="submit" class="g_green_btn" value="RESET PASSWORD" /></div>
                                <div class="a_login_forggot_pass_div">
                                    <a href="<?=base_url("admin/auth/login");?>" class="forgot-pass">BACK TO LOGIN</a>
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