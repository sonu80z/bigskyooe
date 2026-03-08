

<header class="main-header">
    <!-- Logo -->
    <a href="<?= base_url('admin/dashboard');?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
          <img src="<?= base_url()?>public/dist/img/slogo.png" class="c_slogo_img" />
            <!--          <b>HI</b>-->
      </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
          <img src="<?= base_url()?>public/dist/img/csm-logo.png" class="c_logo_img" />
            <!--          <b>Food</b> To Room-->
      </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php if($this->session->has_userdata('is_admin_login')) { ?>
                <li class="user user-menu"><a href="#">MD MGT</a></li>
                <li class="user user-menu"><a href="#">Alert MGT</a></li>
                    <li class="user user-menu"><a href="#">Reports</a></li>
                <?php } ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        $profile_image = base_url()."public/dist/img/user2-160x160.jpg";
                        if ( isset($_SESSION["profile_image"]) && $_SESSION["profile_image"] != "" ) $profile_image =  base_url()."uploads/profiles/".$_SESSION["profile_image"];
                        ?>
                        <img src="<?= $profile_image; ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?= ucwords($this->session->userdata('username')); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $profile_image; ?>" class="img-circle" alt="User Image">

                            <p>
                                <?=$_SESSION["full_name"];?>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="<?= site_url('admin/auth/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                            <div class="pull-left">
                                <a href="<?= base_url('admin/profile'); ?>" class="btn btn-default btn-flat">profile</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
 