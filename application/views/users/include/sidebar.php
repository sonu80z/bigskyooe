<?php 
$cur_tab = $this->uri->segment(2)==''?'dashboard': $this->uri->segment(2);  
?>  

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
        <div class="user-panel a_left_top_dv">
            <div class="pull-left image">
                <img src="<?= $profile_image; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= ucwords($this->session->userdata('name')); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> <?= ucwords($this->session->userdata('full_name')); ?></a>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li id="dashboard">
                <a href="<?= base_url('admin/dashboard'); ?>">
                    <i class="fa fa-dashboard"></i><span>Dashboard</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
        </ul>
        <!-- Order Management -->
        <ul class="sidebar-menu">
            <li id="order" class="treeview">
                <a href="#">
                    <i class="fa fa-send-o"></i> <span>Order Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/order'); ?>"><i class="fa fa-list-ul"></i>All Orders</a></li>
                </ul>
            </li>
        </ul>
        <!-- Report  -->
        <ul class="sidebar-menu">
            <li id="profile" class="treeview">
                <a href="#">
                    <i class="fa fa-comments"></i> <span>Report</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url(); ?>"><i class="fa fa-circle-thin"></i>Tech Report</a></li>
                    <li id=""><a href="<?= base_url(); ?>"><i class="fa fa-circle-thin"></i>Dispatch Report</a></li>
                    <li><a href="<?= base_url(); ?>"><i class="fa fa-circle-thin"></i>Facility QA Report</a></li>
                    <li><a href="<?= base_url(); ?>"><i class="fa fa-circle-thin"></i>Master Report</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li id="mapit">
                <a href="<?= base_url('admin/export/mapit'); ?>">
                    <i class="fa fa-map-marker"></i><span>Map It</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li id="profile" class="treeview">
                <a href="#">
                    <i class="fa fa-user-secret"></i> <span>User Profile</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/profile'); ?>"><i class="fa fa-pencil-square-o"></i>My Profile</a></li>
                    <li id=""><a href="<?= base_url('admin/profile/change_pwd'); ?>"><i class="fa fa-key"></i>Change Password</a></li>
                    <li><a href="<?= base_url('admin/profile/authy_setting'); ?>"><i class="fa fa-share-alt"></i>2FA</a></li>
                </ul>
            </li>
        </ul>

        <ul class="sidebar-menu">
            <li id="dashboard" class="treeview">
                <a href="<?= base_url('admin/auth/logout'); ?>">
                    <i class="fa fa-sign-out"></i> <span>Logout</span>
                    <span class="pull-right-container">
              </span>
                </a>
            </li>
        </ul>


    </section>
    <!-- /.sidebar -->
  </aside>

  
<script>
  if ( "<?= $cur_tab3 ?? '' ?>" == "mapit" ) {
      jQuery("#mapit").addClass("active").addClass("a_bl_red");
  } else {
      $("#<?= $cur_tab ?>").addClass('active').addClass("a_bl_red");
  }

</script>
