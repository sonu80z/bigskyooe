<?php defined('BASEPATH') OR exit('No direct script access allowed');

	use Dompdf\Dompdf;
	use Dompdf\Options;

	class Dashboard extends MU_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('admin/dashboard_model', 'dashboard_model');
            $this->load->model('admin/orders_model', 'orders_model');
            $this->load->model('admin/facility_model', 'facility_model');
			$this->load->model('admin/user_model', 'user_model');
			$this->load->model('dashboard_model');
			$this->load->model('admin/procedure_model', 'procedure_model');
			$this->load->model('admin/division_model', 'division_model');
			$this->load->model('admin/state_model', 'state_model');
			$this->load->library('data_cache');
		}

		public function index(){

			$data['title'] = 'Dashboard';
			$user_id = $this->session->userdata('did');
			$user_info = !empty($user_id) ? $this->user_model->get_user_info($user_id) : null;
			$is_technologist = (!empty($user_info) && isset($user_info['role']) && (int)$user_info['role'] === 8);

			// Orders now loaded via AJAX (ajax_orders endpoint) for faster page load
			$data['adv'] = array();
            $data['facilities'] = $this->data_cache->get_facilities();
			$data['technologist'] = $this->data_cache->get_technologists();
			$data['procedures'] = $this->data_cache->get_procedures();
			$data['divisions'] = $this->data_cache->get_divisions(0);
			$data['states'] = $this->data_cache->get_active_states();
			$data['view'] = 'admin/dashboard/dashboard1';
			$data['page_js'] = array('order.js', 'system.js', 'dashboard.js');
			$data['page_plugins'] = array('datetimepicker', 'flot');
			$this->load->view('layout', $data);
		}

		public function ajax_orders(){
			$user_id = $this->session->userdata('did');
			$user_info = !empty($user_id) ? $this->user_model->get_user_info($user_id) : null;
			$is_technologist = (!empty($user_info) && isset($user_info['role']) && (int)$user_info['role'] === 8);
			$tech_id = $is_technologist ? $user_id : null;

			// Read all advanced search params
			$time = $this->input->get('adv_time', true) ?: 'today';
			$order_types_raw = $this->input->get('adv_order_types', true) ?: '';
			$date_from_raw = trim($this->input->get('adv_date_from', true));
			$date_to_raw   = trim($this->input->get('adv_date_to', true));
			// Convert mm/dd/yyyy to yyyy-mm-dd for MySQL
			$date_from = (!empty($date_from_raw) && strpos($date_from_raw, '/') !== false)
				? date('Y-m-d', strtotime($date_from_raw)) : $date_from_raw;
			$date_to = (!empty($date_to_raw) && strpos($date_to_raw, '/') !== false)
				? date('Y-m-d', strtotime($date_to_raw)) : $date_to_raw;
			$filters = array(
				'search_name'   => trim($this->input->get('adv_search_name', true)),
				'search_dob'    => trim($this->input->get('adv_search_dob', true)),
				'date_from'     => $date_from,
				'date_to'       => $date_to,
				'date_type'     => $this->input->get('adv_date_type', true) ?: 'order',
				'facility_id'   => $this->input->get('adv_facility', true),
				'patient_id'    => trim($this->input->get('adv_patient_id', true)),
				'division'      => $this->input->get('adv_division', true),
				'order_types'   => array_filter(explode(',', $order_types_raw)),
				'state'         => $this->input->get('adv_state', true),
				'modality'      => $this->input->get('adv_modality', true),
				'status'        => $this->input->get('adv_status', true),
				'today_only'    => ($time === 'today'),
			);

			$orders = $this->orders_model->get_orders_filtered($filters, 'created_at desc', $tech_id);

			// Batch-fetch latest history timestamps for "Time in Status" column
			$order_ids = array_column($orders, 'id');
			$history_ts = $this->orders_model->get_latest_history_timestamps($order_ids);

			// Build lookup arrays
			$facilities = $this->data_cache->get_facilities();
			$facilities_lookup = array();
			foreach($facilities as $facility) {
				$facilities_lookup[$facility['id']] = $facility['facility_name'];
			}

			$procedures = $this->data_cache->get_procedures();
			$procedure_lookup = array();
			if (is_array($procedures)) {
				foreach ($procedures as $proc) {
					$procedure_lookup[$proc['id']] = $proc['cpt_code'] . ' - ' . $proc['description'];
				}
			}

			$rows = array();
			$base_url = base_url();
			$current_time = time();
			foreach ($orders as $row) {
				$order_status_info = get_order_status(intval($row['status']));
				$date = !empty($row['created_at']) ? date('m/d/Y', strtotime($row['created_at'])) : '';
				$schedule_date = !empty($row['date_of_service']) ? date('m/d/Y', strtotime($row['date_of_service'])) : '';
				$facility_name = !empty($row['orderingentity']) && isset($facilities_lookup[$row['orderingentity']])
					? $facilities_lookup[$row['orderingentity']] : '';
				$tech_name = !empty($row['dispatch_technologist']) ? $row['dispatch_technologist'] : '';
				$order_id = $row['id'];
				$is_canceled = intval($row['is_canceled']) === 1;

				// Time in Status calculation
				$time_html = '';
				if (isset($history_ts[$order_id])) {
					$changed_time = strtotime($history_ts[$order_id]);
					$minutes = round(($current_time - $changed_time) / 60);
					if ($minutes < 60) {
						$time_display = $minutes . ' min';
					} elseif ($minutes < 1440) {
						$time_display = round($minutes / 60, 1) . ' hrs';
					} else {
						$time_display = round($minutes / 1440, 1) . ' days';
					}
					$order_status_num = intval($row['status']);
					if ($order_status_num == 20) {
						if ($minutes > 120) {
							$time_html = '<span style="color:red;font-weight:bold;">' . $time_display . '</span>';
						} elseif ($minutes > 60) {
							$time_html = '<span style="color:orange;font-weight:bold;">' . $time_display . '</span>';
						} else {
							$time_html = $time_display;
						}
					} else {
						$time_html = $time_display;
					}
				}

				// Decode procedure list
				$procedure_display = '';
				if (!empty($row['procedurelist'])) {
					$proc_ids = json_decode($row['procedurelist'], true);
					if (is_array($proc_ids) && !empty($proc_ids)) {
						$names = array();
						foreach ($proc_ids as $pid) {
							if (!empty($pid)) {
								$names[] = isset($procedure_lookup[$pid]) ? $procedure_lookup[$pid] : 'ID: '.$pid;
							}
						}
						$procedure_display = implode(', ', $names);
					} else {
						$procedure_display = $row['procedurelist'];
					}
				}

				// Build action buttons
				if ($is_canceled) {
					$actions = '<a title="View" class="btn btn-xs btn-primary" href="'.$base_url.'admin/order/view/'.$order_id.'"><i class="fa fa-eye"></i></a>';
				} else {
					$att_button = '';
					if(!empty($row['attachment'])){
						$decoded = json_decode($row['attachment'], true);
						if (is_array($decoded) && !empty($decoded)) {
							$first_file = isset($decoded[0]['file']) ? $decoded[0]['file'] : '';
							if (count($decoded) === 1 && $first_file !== '') {
								$att_button = '<a title="View PDF" class="btn btn-xs btn-primary" href="'.$base_url.'uploads/order_attachments/'.htmlspecialchars($first_file).'" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
							} else {
								$att_button = '<a title="View Attachments" class="btn btn-xs btn-primary" href="'.$base_url.'admin/order/view/'.$order_id.'"><i class="fa fa-paperclip"></i></a>';
							}
						} else {
							$att_button = '<a title="View PDF" class="btn btn-xs btn-primary" href="'.$base_url.'uploads/order_attachments/'.htmlspecialchars($row['attachment']).'" target="_blank"><i class="fa fa-file-pdf-o"></i></a>';
						}
					}
					$actions =
						'<a title="Edit" class="btn btn-xs btn-primary" href="'.$base_url.'admin/order/edit/'.$order_id.'"><i class="fa fa-pencil-square-o"></i></a>' .
						'<a title="View" class="btn btn-xs btn-primary" href="'.$base_url.'admin/order/view/'.$order_id.'"><i class="fa fa-eye"></i></a>' .
						$att_button .
						'<a title="Submit for Reading" class="submit-reading-btn btn btn-xs btn-warning" data-id="'.$order_id.'" href="javascript:void(0);"><i class="fa fa-file-text"></i></a>' .
						'<a title="View Report" class="btn btn-xs btn-warning" href="'.$base_url.'admin/order/view_report/'.$order_id.'" target="_blank"><i class="fa fa-file-text-o"></i></a>' .
						'<a title="View Timeline" class="view-timeline-btn btn btn-xs btn-info" data-id="'.$order_id.'" href="javascript:void(0);"><i class="fa fa-history"></i></a>' .
						'<a title="Workflow" class="btn btn-xs btn-primary" href="http://18.221.194.47:3000/" target="_blank"><i class="fa fa-gears"></i></a>' .
						'<a title="Dispatch" class="dispatch-btn btn btn-xs btn-primary" data-id="'.$order_id.'" data-order-id="'.$order_id.'"><i class="fa fa-car"></i></a>' .
						'<a title="Mark Completed" class="mark-btn btn btn-xs btn-primary" data-id="'.$order_id.'"><i class="fa fa-check"></i></a>' .
						'<a title="Send to HL7" class="btn btn-xs btn-primary" href="#"><i class="fa fa-send"></i></a>' .
						'<a title="Add Note" class="note-btn btn btn-xs btn-primary" data-id="'.$order_id.'"><i class="fa fa-file-o"></i></a>' .
						'<a title="Cancel" class="cancel-btn btn btn-xs btn-primary" data-id="'.$order_id.'"><i class="fa fa-times"></i></a>' .
						'<a title="Print" class="btn btn-xs btn-primary" href="'.$base_url.'admin/dashboard/print/'.$order_id.'"><i class="fa fa-print"></i></a>';
				}

				$rows[] = array(
					'DT_RowAttr' => array(
						'style' => 'background:'.$order_status_info['color'],
						'did' => $order_id
					),
					'<input type="checkbox" class="order-checkbox" data-id="'.$order_id.'" />',
					htmlspecialchars($order_status_info['text']),
					$time_html,
					$date,
					$schedule_date,
					htmlspecialchars($row['patientmr']),
					htmlspecialchars($row['lastname'].' '.$row['firstname']),
					htmlspecialchars($facility_name),
					htmlspecialchars($tech_name),
					htmlspecialchars($procedure_display),
					$actions,
				);
			}

			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('data' => $rows)));
		}

		public function orderpdf(){

			$data['title'] = 'Dashboard';
			$data['view'] = 'admin/dashboard/orderpdf';
			$data['page_js'] = array();
			$this->load->view('layout', $data);
		}

		public function print($order_id) {
			$data['order'] = $this->orders_model->get_order_detail($order_id, true);
			$data['state'] = '';
			if(!empty($data['order']['orderedstate'])) {
				$data['state'] = $this->orders_model->get_state_name($data['order']['orderedstate']);
			}
			$data['drname'] = '';
			if(!empty($data['order']['servicedr'])) {
				$data['drname'] = $this->orders_model->get_dr_name($data['order']['servicedr']);
			}
			$data['facilityname'] = '';
			if(!empty($data['order']['orderingentity'])) {
				$data['facilityname'] = $this->orders_model->get_entity_name($data['order']['orderingentity']);
			}

			// PRE-LOAD all procedures and ICD10 codes to eliminate N+1 queries
			$all_procedures = $this->procedure_model->get_all_procedures();
			$proc_lookup = array();
			foreach($all_procedures as $p) {
				$proc_lookup[$p['id']] = $p;
			}
			
			// Build ICD10 lookup from tbl_lists (symptoms)
			$this->load->model('admin/lists_model', 'lists_model');
			$all_icd = $this->db->get_where('tbl_lists', array('name' => 'icd'))->result_array();
			$icd_lookup = array();
			foreach($all_icd as $icd) {
				$icd_lookup[$icd['id']] = $icd;
			}

			$data['procedurelist'] = array();
			$prlist = json_decode($data['order']['procedurelist'], true);
			$symptom1 = json_decode($data['order']['symptom1'], true);
			$symptom2 = json_decode($data['order']['symptom2'], true);
			$symptom3 = json_decode($data['order']['symptom3'], true);
			$plrn = json_decode($data['order']['plrn'], true);
			if(is_array($prlist) && sizeof($prlist) > 0) {
				foreach ($prlist as $key => $value) {
					// Use lookup instead of individual DB query
					$prcname = isset($proc_lookup[$value]) ? $proc_lookup[$value] : null;
					if($prcname && !empty($prcname['description'])) {
						$symps = '';
						if(is_array($symptom1) && sizeof($symptom1) > 0 && isset($symptom1[$key])) {
							$symp1 = isset($icd_lookup[$symptom1[$key]]) ? $icd_lookup[$symptom1[$key]] : null;
							if($symp1 && !empty($symp1['value'])) {
								$symps .= $symp1['value'];
							}
						}
						if(is_array($symptom2) && sizeof($symptom2) > 0 && isset($symptom2[$key])) {
							$symp2 = isset($icd_lookup[$symptom2[$key]]) ? $icd_lookup[$symptom2[$key]] : null;
							if($symp2 && !empty($symp2['value'])) {
								$symps .= ($symps ? ', ' : '') . $symp2['value'];
							}
						}
						if(is_array($symptom3) && sizeof($symptom3) > 0 && isset($symptom3[$key])) {
							$symp3 = isset($icd_lookup[$symptom3[$key]]) ? $icd_lookup[$symptom3[$key]] : null;
							if($symp3 && !empty($symp3['value'])) {
								$symps .= ($symps ? ', ' : '') . $symp3['value'];
							}
						}
						array_push($data['procedurelist'], array('procedure' => $prcname['description'], 'plrn' => ($plrn && sizeof($plrn) > 0 && isset($plrn[$key])) ? $plrn[$key] : '', 'symptom' => $symps));
					}
				}
			}
			
			$this->load->view('admin/dashboard/orderpdf', $data);

			$html = $this->output->get_output();

			$options = new Options();
			$options->set('isRemoteEnabled', true);
			$options->set('isHtml5ParserEnabled', true);
			$options->set('chroot', base_url().'public/');
			$dompdf = new Dompdf($options);

			$logoimage = base_url().'public/BLANK-REQUISITION-with-SONOGRAPHY.png';

			$html = str_replace('{logo-img}', $logoimage, $html);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render('browser');
			$dompdf->stream("Order_".$order_id."_".date("d-m-Y H:i:s"), array("Attachment" => 1));
		}
		
	}

?>	