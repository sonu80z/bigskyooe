<?php
$cur_tab = $this->uri->segment(2)==''?'dashboard': $this->uri->segment(2);
$cur_tab3 = $this->uri->segment(3);
$cur_tab4 = $this->uri->segment(4);
?>
  <aside class="main-sidebar">
    <section class="sidebar">
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
        <!-- users management -->
        <ul class="sidebar-menu">
            <li id="users" class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i> <span>User Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/users'); ?>"><i class="fa fa-list-ul"></i>All Users</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/users/add'); ?>"><i class="fa fa-user-plus"></i>Add User</a></li>
                </ul>
            </li>
        </ul>
        <!-- patient management -->
        <ul class="sidebar-menu">
            <li id="users" class="treeview">
                <a href="#">
                    <i class="fa fa-hospital-o"></i> <span>Patient Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/patients'); ?>"><i class="fa fa-list-ul"></i>All patients</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/patients/add'); ?>"><i class="fa fa-user-plus"></i>Add patients</a></li>
                </ul>
            </li>
        </ul>
        <!-- users management -->
        <ul class="sidebar-menu">
            <li id="order" class="treeview">
                <a href="#">
                    <i class="fa  fa-send-o"></i> <span>Order Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="user_all"><a href="<?= base_url('admin/order'); ?>"><i class="fa fa-list-ul"></i>All Orders</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/order/add'); ?>"><i class="fa fa-plus"></i>Add Order</a></li>

                </ul>
            </li>
        </ul>
        <!-- map it out-->
        <ul class="sidebar-menu">
            <li id="order" class="treeview">
                <a href="<?= base_url('admin/export/mapit'); ?>">
                    <i class="fa  fa-map-marker"></i> <span>Map It </span>
                </a>
            </li>
        </ul>
        <!-- Division management -->
        <ul class="sidebar-menu">
            <li id="division" class="treeview">
                <a href="#">
                    <i class="fa fa-share-alt"></i> <span>Division Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/division/lists'); ?>"><i class="fa fa-list-ul"></i>All Divisions</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/division/add'); ?>"><i class="fa fa-plus"></i>Add Division</a></li>
                </ul>
            </li>
        </ul>
        <!-- Facility management -->
        <ul class="sidebar-menu">
            <li id="facility" class="treeview">
                <a href="#">
                    <i class="fa  fa-gears"></i> <span>Facility Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/facility/lists'); ?>"><i class="fa fa-list-ul"></i>All Facilitys</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/facility/add'); ?>"><i class="fa fa-plus"></i>Add Facility</a></li>
                </ul>
            </li>
        </ul>
        <!-- procedure management -->
        <ul class="sidebar-menu">
            <li id="procedure" class="treeview">
                <a href="#">
                    <i class="fa fa-sort-amount-desc"></i> <span>Procedure Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/procedure/lists'); ?>"><i class="fa fa-list-ul"></i>All Procedures</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/procedure/add'); ?>"><i class="fa fa-plus"></i>Add Procedure</a></li>
                </ul>
            </li>
        </ul>
        <!-- inventory management -->
        <ul class="sidebar-menu">
            <li id="users" class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Inventory Management</span>
                </a>
            </li>
        </ul>
        <!-- List management -->
        <ul class="sidebar-menu">
            <li id="listitem" class="treeview">
                <a href="#">
                    <i class="fa fa-th-list"></i> <span>List Management</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id=""><a href="<?= base_url('admin/listitem/lists'); ?>"><i class="fa fa-list-ul"></i>All Lists</a></li>
                    <li id="user_add"><a href="<?= base_url('admin/listitem/add'); ?>"><i class="fa fa-plus"></i>Add List</a></li>
                </ul>
            </li>
        </ul>
        <!-- report management -->
        <ul class="sidebar-menu">
            <li id="user_add"><a href="<?= base_url('admin/order/manage'); ?>"><i class="fa fa-gear"></i>Manage order screens</a></li>
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
        <!-- App Management -->
        <ul class="sidebar-menu">
            <li id="export" class="treeview">
                <a href="#">
                  <i class="fa fa-windows"></i> <span>System Management</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">

                    <li class=""><a href="<?= base_url('admin/export'); ?>"><i class="fa fa-database"></i> Database Backup </a></li>
                    <li class=""><a href="<?= base_url('admin/export/logs'); ?>"><i class="fa fa-legal"></i> System Logs </a></li>
                </ul>
              </li>
          </ul>
        <ul class="sidebar-menu">
            <li id="profile" class="treeview">
                <a href="#">
                    <i class="fa fa-user-secret"></i> <span>Admin Profile</span>
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
  </aside>
<script>
  if ( "<?= $cur_tab3 ?>" == "users" ) {
      jQuery("#users").addClass("active").addClass("a_bl_red"); 
  } else if ( "<?= $cur_tab?>" == "currency" ) {
      jQuery("#account").addClass("active").addClass("a_bl_red");
  } else {
      $("#<?= $cur_tab ?>").addClass('active').addClass("a_bl_red");
  }
</script>
