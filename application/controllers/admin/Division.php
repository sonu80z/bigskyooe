<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 1:58 AM
 */

class Division extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('admin/division_model', 'division_model');
    }

    // go to facility add page
    public function add(){
        $data['title'] = 'Add Division';
        $data['divisions'] = $this->division_model->get_all_divisions_via_type(0);
        $data['subdivisions'] = $this->division_model->get_all_divisions_via_type(1);
        $data['view'] = 'admin/division/add';
        $data['page_js'] = array('division.js');
        $this->load->view('layout', $data);
    }

    // go to facility lists page
    public function lists(){
        $data['title'] = 'Divisions List';
        $data['facilities'] = $this->division_model->get_all_divisions_via_type(0);
        $data['view'] = 'admin/division/list';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }

    /**
     * go to division edit page
     * @param $id: top division's primary key
     * 2019-06-26 by hmc
    */
    public function edit ( $id=null )
    {
        if ( $this->input->post("submit") ) {
            $data = array(
                'gps_location' => $this->input->post('gps_location'),
                'division_manager' => $this->input->post('division_manager'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax')
            );
            $this->division_model->edit_division($data, $id);
            $this->session->set_flashdata('msg', 'New division has been updated successfully!');
        }
        $data['title'] = 'Divisions Edit';
        $data['division'] = $this->division_model->get_division_via_id( $id );
        $data['subdivisions'] = $this->division_model->get_subdivisions_via_divisionid( $id );
        $data['regions'] = $this->division_model->get_regions_via_divisionid( $id );
        $data['view'] = 'admin/division/edit';
        $data['page_js'] = array('division.js');
        $this->load->view('layout', $data);
    }

    /**
     * create new division
     * @param $type: 0=>division, 1=>subdivision, 2=>region
     * 2019-06-25 by hmc
    */
    public function create( $type ){
        $data = null;
        if ( $type == "0" ) {
            $data = array(
                'name' => $this->input->post('name'),
                'type' => $type,
                'gps_location' => $this->input->post('gps_location'),
                'division_manager' => $this->input->post('division_manager'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $result = $this->division_model->add_division($data);
            if($result){
                $this->session->set_flashdata('msg', 'New division has been added successfully!');
                redirect(base_url('admin/division/lists'));
            }
        } else if ( $type == "1" ) {
            $data = array(
                'name' => $this->input->post('name'),
                'type' => $type,
                'parent' => $this->input->post('parent'),
                'division_manager' => $this->input->post('division_manager'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $result = $this->division_model->add_division($data);
            if($result){
                $this->session->set_flashdata('msg', 'New subdivision has been added successfully!');
                redirect(base_url('admin/division/edit/'.$this->input->post('parent')));
            }
        } else if ( $type == "2" ) {
            $data = array(
                'name' => $this->input->post('name'),
                'type' => $type,
                'parent' => $this->input->post('sparent'),
                'division_manager' => $this->input->post('division_manager'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $result = $this->division_model->add_division($data);
            if($result){
                $this->session->set_flashdata('msg', 'New region has been added successfully!');
                redirect(base_url('admin/division/edit/'.$this->input->post('parent')));
            }
        }
    }

    public function edit_others($type){
        if ( $type == "1" ) {
            $id = $this->input->post('parent');
            $data = array(
                'name' => $this->input->post('name'),
                'type' => $type,
                'parent' => $this->input->post('parent'),
                'division_manager' => $this->input->post('division_manager'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $result = $this->division_model->edit_division($data, $id);
            if($result){
                $this->session->set_flashdata('msg', 'A subdivision has been updated successfully!');
                redirect(base_url('admin/division/edit/'.$this->input->post('parent')));
            }
        } else if ( $type == "2" ) {
            $id = $this->input->post('parent');
            $data = array(
                'name' => $this->input->post('name'),
                'type' => $type,
                'parent' => $this->input->post('sparent'),
                'division_manager' => $this->input->post('division_manager'),
                'address' => $this->input->post('address'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $result = $this->division_model->edit_division($data, $id);
            if($result){
                $this->session->set_flashdata('msg', 'A region has been updated successfully!');
                redirect(base_url('admin/division/edit/'.$this->input->post('parent')));
            }
        }
    }

    /**
     * delete division
     * @param $did: division's primary key
     * @param $parent: if division is sub division or regiorn then will be available, else null
    */
    public function del_division( $did, $parent=null ){
        $result = $this->division_model->del_division($did);
        $this->session->set_flashdata('msg', 'The division has been deleted successfully!');
        if ( $parent == null ) {
            redirect(base_url('admin/division/lists'));
        } else {
            redirect(base_url('admin/division/edit/'.$parent));
        }
    }
}
