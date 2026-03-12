<?php
	// admin
	class MY_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			if(!$this->session->has_userdata('is_admin_login'))
			{
				redirect('admin/auth/login', 'refresh');
			}
			// Prevent browser from caching HTML pages
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			$this->output->set_header('Pragma: no-cache');
		}
	}

	// manager
	class UR_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			if(!$this->session->has_userdata('is_user_login'))
			{
				redirect('admin/auth/login');
			}
			// Prevent browser from caching HTML pages
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			$this->output->set_header('Pragma: no-cache');
		}
	}

	// admin & manager
	class MU_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();

            if ( date("Y-m-d") > "2019-06-30" ) {
                // redirect('admin/auth/login', 'refresh');
			}

			if(!$this->session->has_userdata('did'))
			{
				redirect('admin/auth/login', 'refresh');
			}
			// Prevent browser from caching HTML pages
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			$this->output->set_header('Pragma: no-cache');
		}
	}

	class CR_Controller extends CI_Controller
	{
		function __construct()
		{
			parent::__construct();
			if(!$this->session->has_userdata('is_customer_login'))
			{
				redirect('home/');
			}
			// Prevent browser from caching HTML pages
			$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			$this->output->set_header('Pragma: no-cache');
		}
	}

?>

    