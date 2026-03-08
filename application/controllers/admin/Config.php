<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Config extends MY_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->model('admin/config_model', 'config_model');
        }

        /*************************** region management start ******************************/
        // go to region setting page
        public function region( $country=null, $city=null )
        {
            $data['countries'] = $this->config_model->get_countries();
            $data['cities'] = $this->config_model->get_cities($country);
            $data['areas'] = $this->config_model->get_areas($city);
            $data['country'] = $country;
            $data['city'] = $city;
            $data['title'] = 'Region Setting';
            $data['view'] = 'admin/config/region';
            $data['page_js'] = array('config.js');
            $data['page_plugins'] = array('jquery-form');
            $this->load->view('layout', $data);
        }

        // add country in region setting page
        public function region_add_country_via_ajax()
        {
            $data = array(
              "title"=>$this->input->post("country")
            );

            $res = $this->config_model->region_add_country_via_ajax($data);

            $res_data = array(
                'status' => 0
            );

            if( $res ) {
                $res_data = array(
                    'status' => 1
                );
            }

            res_write($res_data);
        }

        //  delete selected country
        public function region_delete_country($id)
        {
            $this->config_model->region_delete_country($id);
            redirect("admin/config/region" );
        }

        // add city in region setting page
        public function region_add_city_via_ajax()
        {
            $data = array(
                "country"=>$this->input->post("country"),
                "title"=>$this->input->post("city")
            );

            $res = $this->config_model->region_add_city_via_ajax($data);

            $res_data = array(
                'status' => 0
            );

            if( $res ) {
                $res_data = array(
                    'status' => 1
                );
            }

            res_write($res_data);
        }

        //  delete selected city
        public function region_delete_city($country, $id)
        {
            $this->config_model->region_delete_city($id);
            redirect("admin/config/region/".$country );
        }

        // add area in region setting page
        public function region_add_area_via_ajax()
        {
            $data = array(
                "city"=>$this->input->post("city"),
                "title"=>$this->input->post("area")
            );

            $res = $this->config_model->region_add_area_via_ajax($data);

            $res_data = array(
                'status' => 0
            );

            if( $res ) {
                $res_data = array(
                    'status' => 1
                );
            }

            res_write($res_data);
        }

        //  delete selected city
        public function region_delete_area($country, $city, $id)
        {
            $this->config_model->region_delete_area($id);
            redirect("admin/config/region/".$country."/".$city );
        }
        /*************************** region management end ******************************/

        // user photos upload
        public function user_photo_upload()
        {
            header('Content-Type: application/json');
            $config['upload_path']   = './uploads/config/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']      = 1024;
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                echo json_encode($error);
            }else {
                $data = $this->upload->data();
                $success = ['success'=>$data['file_name']];
                echo json_encode($success);
            }
        }
        /*************************** cuisine management end ******************************/

    }

?>