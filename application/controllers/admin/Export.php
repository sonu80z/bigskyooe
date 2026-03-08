<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Export extends MU_Controller {

		public function __construct(){
			parent::__construct();
        	$this->load->helper('download');
        	$this->load->library('zip');
            $this->load->model('admin/log_model', 'log_model');
            $this->load->model('admin/orders_model', 'orders_model');

        }

		public function index(){
			$data['title'] = 'Export Database';
			$data['view'] = 'admin/export/db_export';
			$data['page_js'] = array();
			$this->load->view('layout', $data);
		}

		public function dbexport(){

			$this->load->dbutil();
			$db_format = array(
				'ignore' => array($this->ignore_directories),
				'format'=> 'zip',
				'filename'=> 'my_db_backup.sql',
				'add_insert' => TRUE,
				'newline' => "\n"
			);
			$backup = & $this->dbutil->backup($db_format);
			$dbname = 'backup-on-'.date('Y-m-d').'.zip';
			$save = 'uploads/db_backup/'.$dbname;
			write_file($save, $backup);

            //record logs
            $this->log_model->record_system_log( '', 3, 'backupD', '' );

			force_download($dbname, $backup);
		}

		/**
		 * manage system logs
		 * 2018-09-28 by hmc
		*/
        public function logs(){

        	$actor_type = $this->input->post("al_actor_type", TRUE); if ( !isset($actor_type) ) $actor_type = 0;
            $year = $this->input->post("al_year", TRUE); if ( !isset($year) ) $year = date("Y");
            $month = $this->input->post("al_month", TRUE); if ( !isset($month) ) $month = date("m");
            $day = $this->input->post("al_day", TRUE); if ( !isset($day) ) $day = date("d");
            $actor_username = $this->input->post("al_actor_username", TRUE);
            $action_tag = $this->input->post("al_action_tag", TRUE);
            $to = $this->input->post("al_to", TRUE);
            $page = $this->input->post("al_page", TRUE);  if ( !isset($page) ) $page = 1;
            $limit = 15;

            $ret = $this->log_model->get_system_logs($actor_type, $year, $month, $day, $actor_username, $action_tag, $to, $page);
            $data['logs'] = $ret["logs"];

            $data['actor_type'] = $actor_type;
            $data['year'] = $year;
            $data['month'] = $month;
            $data['day'] = $day;
            $data['actor_username'] = $actor_username;
            $data['action_tag'] = $action_tag;
            $data['to'] = $to;
            $data['cnt'] = $ret["cnt"];
            $data['page'] = $page;
            $data['limit'] = $limit;

            $data['title'] = 'System Logs';
            $data['view'] = 'admin/export/logs';
            $data['page_js'] = array('system.js');
            $this->load->view('layout', $data);
        }

        /**
		 * export filtered logs as csv file
        */
        public function export_logs_as_csv()
		{
            $actor_type = $this->input->get("al_actor_type");
            $year = $this->input->get("al_year");
            $month = $this->input->get("al_month");
            $day = $this->input->get("al_day");
            $actor_username = $this->input->get("al_actor_username");
            $action_tag = $this->input->get("al_action_tag");
            $to = $this->input->get("al_to");

            $this->load->dbutil();
            $this->load->helper('file');
            $this->load->helper('download');
            $query = $this->log_model->get_system_logs($actor_type, $year, $month, $day, $actor_username, $action_tag, $to, null);
            $delimiter = ",";
            $newline = "\r\n";
            $data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
            force_download('logs'.date('Y_m_d_H_i_s').'.csv', $data);
		}

		/**
         * go to mapit page
		*/
		public function mapit()
        {
            $data['title'] = 'Map It';
            $data['view'] = 'admin/export/mapit';
            $data['page_js'] = array();
            $data['load_google_maps'] = true; // Load Google Maps JS only on this page
            $sql = "SELECT id, orderedaddress FROM tbl_orderdetail where 1=1 ";
            $sql .= " and dispatch_submit_user_id = 0";
            $order_list = $this->db->query($sql)->result_array();
            $order_list = $this->adjust_order_list($order_list);
            $data['orders'] = $order_list;
            $this->load->view('layout', $data);
        }
        public function get_order_list()
        {
            $order_list = array();
            $order_types = $this->input->post('order_types');
            if(!empty($order_types)){
                $sql = "SELECT orderedaddress FROM tbl_orderdetail where 1=1";
                $sql_condition = array();
                if(in_array('undispatch', $order_types)){
                    $sql_condition[] = "dispatch_submit_user_id = 0";
                }
                if(in_array('dispatch', $order_types)){
                    $sql_condition[] = "dispatch_submit_user_id > 0";
                }
                if(in_array('completed', $order_types)){
                    $sql_condition[] = "mark_complete = 1";
                }
                if(count($sql_condition) > 0){
                    $sql_condition_str = implode(" or ", $sql_condition);
                    $sql .= ' and ('.$sql_condition_str.')';
                    $order_list = $this->db->query($sql)->result_array();
                }else{

                }
            }
            $order_list = $this->adjust_order_list($order_list);
            echo json_encode($order_list); die;
        }
        private function adjust_order_list($order_list){
            $apiKey = 'AIzaSyDNSD8o2CyNEWb73m62IUL9i7T4i9TF3rM';
            
            // PERFORMANCE: Use file-based cache for geocode results
            // This prevents repeated API calls for the same addresses
            $CI =& get_instance();
            if(!isset($CI->cache)) {
                $CI->load->driver('cache', array('adapter' => 'file'));
            }
            
            foreach($order_list as $key => $info){
                $address = isset($info['orderedaddress']) ? trim($info['orderedaddress']) : '';
                
                // Skip if address is empty
                if(empty($address)){
                    $order_list[$key]['lat'] = null;
                    $order_list[$key]['lon'] = null;
                    continue;
                }
                
                // Check cache first (cache geocode results for 24 hours)
                $cache_key = 'geocode_' . md5($address);
                $cached = $CI->cache->file->get($cache_key);
                if($cached !== FALSE) {
                    $order_list[$key]['lat'] = $cached['lat'];
                    $order_list[$key]['lon'] = $cached['lon'];
                    continue;
                }
                
                $prepAddr = str_replace(' ','+',$address);
                $geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false&key='.$apiKey);
                
                if($geocode === false){
                    $order_list[$key]['lat'] = null;
                    $order_list[$key]['lon'] = null;
                    continue;
                }
                
                $output = json_decode($geocode);
                
                if(isset($output->results[0]->geometry->location)){
                    $latitude = $output->results[0]->geometry->location->lat;
                    $longitude = $output->results[0]->geometry->location->lng;
                    $order_list[$key]['lat'] = $latitude;
                    $order_list[$key]['lon'] = $longitude;
                    
                    // Cache the geocode result for 24 hours
                    $CI->cache->file->save($cache_key, array('lat' => $latitude, 'lon' => $longitude), 86400);
                } else {
                    $order_list[$key]['lat'] = null;
                    $order_list[$key]['lon'] = null;
                }
            }
            return $order_list;
        }
	}

?>		