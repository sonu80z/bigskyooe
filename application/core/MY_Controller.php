<?php
	// admin
	class MY_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
            date_default_timezone_set('UTC');
			if(!$this->session->has_userdata('is_admin_login'))
			{
				redirect('admin/auth/login', 'refresh');
			}
		}
	}

	// manager
	class UR_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
            date_default_timezone_set('UTC');
			if(!$this->session->has_userdata('is_user_login'))
			{
				redirect('admin/auth/login');
			}
		}
	}

	// admin & manager
	class MU_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
            date_default_timezone_set('UTC');

            if ( date("Y-m-d") > "2019-06-30" ) {
                // redirect('admin/auth/login', 'refresh');
			}

			if(!$this->session->has_userdata('did'))
			{
				redirect('admin/auth/login', 'refresh');
			}
		}
	}

	class CR_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
            date_default_timezone_set('UTC');
			if(!$this->session->has_userdata('is_customer_login'))
			{
				redirect('home/');
			}
		}
	}

?>

    