<!DOCTYPE html>
<html lang="en">
	<head>
		  <title>BIG SKY IMAGING</title>
		  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		  <meta name="description" content="Big Sky Imaging Admin Panel" />

        <link rel="shortcut icon" href="<?= base_url();?>public/dist/img/hico.ico" type="image/x-icon">

        <!-- Core CSS (required on every page) -->
		<link rel="stylesheet" href="<?= base_url() ?>public/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= base_url() ?>public/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/dist/css/skins/skin-green.min.css">

        <!-- DataTables CSS (used on nearly every list page) -->
        <link rel="stylesheet" href="<?= base_url() ?>public/plugins/datatables/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/plugins/datatables/dataTables.bootstrap.css">

        <!-- Plugins loaded globally (used on most pages) -->
        <link rel="stylesheet" href="<?= base_url() ?>public/plugins/select2/select2.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/plugins/jquery-dialog/jquery-confirm.css">
        <link rel="stylesheet" href="<?= base_url() ?>public/custom/plugin/toast/toastr.min.css" />

        <!-- Per-page plugin CSS/JS (loaded only on pages that need them) -->
        <?php if(isset($page_plugins) && is_array($page_plugins)): ?>
            <?php if(in_array('bootstrap-select', $page_plugins)): ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
            <?php endif; ?>
            <?php if(in_array('datetimepicker', $page_plugins)): ?>
        <link rel="stylesheet" href="<?= base_url() ?>public/custom/plugin/jquery-datetimepicker/jquery-ui-timepicker-addon.min.css">
            <?php endif; ?>
        <?php endif; ?>

        <!-- Per-page CSS -->
        <?php if(isset($page_css) && is_array($page_css)): ?>
            <?php foreach($page_css as $css_file): ?>
        <link rel="stylesheet" href="<?= base_url() ?>public/<?= $css_file ?>?v=<?= ASSET_VERSION ?>">
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Custom CSS (always loaded, small files) -->
        <link rel="stylesheet" href="<?= base_url() ?>public/dist/css/style.css?v=<?= ASSET_VERSION ?>">
        <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/global.css?v=<?= ASSET_VERSION ?>" />
        <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/admin.css?v=<?= ASSET_VERSION ?>" />
        <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/custom.css?v=<?= ASSET_VERSION ?>" />
        <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/overrides.css?v=<?= ASSET_VERSION ?>" />

        <!-- jQuery (must be first JS) -->
        <script src="<?= base_url() ?>public/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- jQuery UI -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/flick/jquery-ui.css">

        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>jQuery.widget.bridge('uibutton', jQuery.ui.button);</script>

        <!-- Bootstrap JS -->
        <script src="<?= base_url() ?>public/bootstrap/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url() ?>public/dist/js/app.min.js"></script>

        <!-- DataTables JS (centralized - no longer loaded per view) -->
        <script src="<?= base_url() ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>public/plugins/datatables/dataTables.bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

        <!-- Core plugins (used on most pages) -->
        <script src="<?= base_url() ?>public/plugins/select2/select2.full.min.js"></script>
        <script src="<?= base_url() ?>public/plugins/jquery-dialog/jquery-confirm.js"></script>
        <script src="<?= base_url() ?>public/plugins/inputMask/dist/jquery.inputmask.js"></script>
        <script src="<?= base_url() ?>public/plugins/inputMask/dist/bindings/inputmask.binding.js"></script>

        <!-- Per-page plugin JS -->
        <?php if(isset($page_plugins) && is_array($page_plugins)): ?>
            <?php if(in_array('bootstrap-select', $page_plugins)): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
            <?php endif; ?>
            <?php if(in_array('datetimepicker', $page_plugins)): ?>
        <script src="<?= base_url() ?>public/custom/plugin/jquery-datetimepicker/jquery-ui-timepicker-addon.min.js"></script>
        <script src="<?= base_url() ?>public/custom/plugin/jquery-datetimepicker/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
            <?php endif; ?>
            <?php if(in_array('jquery-form', $page_plugins)): ?>
        <script src="<?= base_url() ?>public/dist/js/jquery-form/jquery-form.min.js"></script>
            <?php endif; ?>
            <?php if(in_array('flot', $page_plugins)): ?>
        <script src="<?= base_url() ?>public/plugins/flot/jquery.flot.min.js"></script>
        <script src="<?= base_url() ?>public/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="<?= base_url() ?>public/plugins/flot/jquery.flot.pie.min.js"></script>
        <script src="<?= base_url() ?>public/plugins/flot/jquery.flot.categories.min.js"></script>
            <?php endif; ?>
        <?php endif; ?>

        <!-- google map api - loaded only on pages that need it -->
        <?php if(isset($load_google_maps) && $load_google_maps): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM&libraries=geometry,drawing,places"></script>
        <?php endif; ?>

        <script src="<?= base_url() ?>public/custom/js/back/global_var.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/global_func.js?v=<?= ASSET_VERSION ?>"></script>

        <style>
            .jconfirm{z-index:999}
            .visibility-hidden{visibility:hidden!important;position:absolute!important;width:0;height:0;overflow:hidden}
        </style>

	</head>
	<body class="hold-transition skin-green sidebar-mini">
    <?php
    $is_admin = FALSE;
    if ( isset( $_SESSION["is_admin_login"] ) && $_SESSION["is_admin_login"] ) $is_admin = TRUE;
    echo '<script type="text/javascript">
                    var BASE_URL = "'.base_url().'";
                    var g_user_id = "'.htmlspecialchars($_SESSION["did"], ENT_QUOTES, 'UTF-8').'";
                    var g_user_name = "'.htmlspecialchars($_SESSION["username"], ENT_QUOTES, 'UTF-8').'";
                    var g_user_eamil = "'.htmlspecialchars($_SESSION["email"], ENT_QUOTES, 'UTF-8').'";
                    var g_user_fullname = "'.htmlspecialchars($_SESSION["full_name"], ENT_QUOTES, 'UTF-8').'";
                    var g_user_image = "'.htmlspecialchars($_SESSION["profile_image"], ENT_QUOTES, 'UTF-8').'";
                    var g_is_admin_login = "'.$is_admin.'";
            </script>';
    ?>

		<div class="wrapper" style="height: auto;">
            <div class="toast-wrapper">

            </div>
			<section id="container">
				<header class="header white-bg">
					<?php
                    if ( !isset($is_iframe) )
                        include('admin/include/navbar.php');
                    ?>
				</header>
				<aside>
                    <?php
                    if ( !isset($is_iframe) ) {
                        if ($this->session->userdata('is_admin_login')):
                            include('admin/include/admin_sidebar.php');
                        else:
                            include('users/include/sidebar.php');
                        endif;
                    }
                    ?>
				</aside>
				<section id="main-content">
					<div class="content-wrapper" style="min-height: 394px; padding:15px;">
						<?php $this->load->view($view);?>
					</div>
				</section>
                <?php if ( !isset($is_iframe) ) { ?>
				<footer class="main-footer">
                    <div class="a_main_footer_copyright">© 2025 BIG SKY IMAGING</div>
				</footer>
                <?php } ?>
			</section>
		</div>


    <style>
        .loading-overlayer{
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            background: rgba(68, 68, 68, 0.05);
            display: block;
            z-index: 10000000000;
        }
        .loading-overlayer .loading-wrapper{
            margin-top: calc(50vh - 40px);
            height: 50px;
            font-size: 60px;
            color: #337ab7;
        }
    </style>
    <div class="loading-overlayer" style="display: none;">
        <div class="loading-wrapper text-center">
            <i class="fa fa-spin fa-spinner"></i>
        </div>
    </div>

    <!-- custom script - loaded per page to reduce overhead -->
    <script src="<?= base_url() ?>public/custom/plugin/toast/toastr.min.js"></script>
    <script src="<?= base_url() ?>public/custom/js/back/common.js?v=<?= ASSET_VERSION ?>"></script>
    <?php if(isset($page_js) && is_array($page_js)): ?>
        <?php foreach($page_js as $js_file): ?>
        <script src="<?= base_url() ?>public/custom/js/back/<?= $js_file ?>?v=<?= ASSET_VERSION ?>"></script>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Fallback: load all JS if page_js not set (backward compatible) -->
        <script src="<?= base_url() ?>public/custom/js/back/config.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/users.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/procedure.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/system.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/order.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/division.js?v=<?= ASSET_VERSION ?>"></script>
        <script src="<?= base_url() ?>public/custom/js/back/facility.js?v=<?= ASSET_VERSION ?>"></script>
    <?php endif; ?>

    <script>
        $(document).ready(function(){
            // Clean up old toast localStorage entries from previous days
            var today = '<?php echo date('Ymd'); ?>';
            for (var i = 0; i < localStorage.length; i++) {
                var key = localStorage.key(i);
                if (key && key.startsWith('toast_shown_') && !key.endsWith('_' + today)) {
                    localStorage.removeItem(key);
                    i--; // Adjust index after removal
                }
            }
            
            <?php
            // Read flash messages and immediately destroy them so they only show once
            $flash_messages = array(
                'msg'         => array('type' => 'warning'),
                'success_msg' => array('type' => 'success'),
                'error_msg'   => array('type' => 'error'),
                'error'       => array('type' => 'error'),
                'success'     => array('type' => 'success'),
            );
            foreach($flash_messages as $key => $config){
                $val = $this->session->flashdata($key);
                if(!empty($val)){
                    // Immediately remove from session so it never shows again
                    $this->session->unset_userdata($key);
                    $ci_vars = $this->session->userdata('__ci_vars');
                    if(is_array($ci_vars) && isset($ci_vars[$key])){
                        unset($ci_vars[$key]);
                        $this->session->set_userdata('__ci_vars', $ci_vars);
                    }
            ?>
                    showToast("<?php echo addslashes($val); ?>", '<?php echo $config["type"]; ?>');
            <?php
                }
            }
            ?>
        });
    </script>

	</body>
</html>