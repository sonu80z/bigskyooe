<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 4:37 AM
 */

class Procedure extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('admin/procedure_model', 'procedure_model');
        $this->load->library('data_cache');
    }

    /**
     * go to procedure add & edit page
     * @param $id: procedure's primary key
     * 2019-06-27 by hmc
    */
    public function add( $id = null ){
        $data['title'] = 'Add Procedure';
        $data['view'] = 'admin/procedure/add';
        $data['page_js'] = array('procedure.js');
        $data['page_plugins'] = array('bootstrap-select');
        $data['id'] = $id;
        if ( $id != null ) {
            $data['procedure'] = $this->procedure_model->get_procedure_via_id( $id );
        }
        $this->load->view('layout', $data);
    }

    /**
     * create & update procedure
     * @param $id: procedure's primary key
     * 2019-06-27 by hmc
     */
    public function create( $id = null ){
        $data = array(
            'cpt_code' => $this->input->post('cpt_code'),
            'description' => $this->input->post('description'),
            'modality' => $this->input->post('modality'),
            'category' => $this->input->post('category'),
            'symptoms' => $this->input->post('symptoms'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $data = $this->security->xss_clean($data);
        if ( isset($id) && $id != "" && $id != null ) { // update procedure
            $this->procedure_model->edit_procedure($id, $data);
            $this->session->set_flashdata('msg', 'Procedure has been updated successfully!');
        } else {                                        // create new procedure
            $this->procedure_model->create_procedure($data);
            $this->session->set_flashdata('msg', 'Procedure has been added successfully!');
        }

        // Invalidate procedure cache so other pages see fresh data
        $this->data_cache->invalidate_procedures();

        redirect(base_url('admin/procedure/lists'));
    }



    // go to facility lists page
    public function lists(){
        $data['title'] = 'Procedures List';
        $data['facilities'] = $this->procedure_model->get_all_procedures();
        $data['view'] = 'admin/procedure/list';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }

    /**
     * delete procedure
     * @param $id: procedure's primary key
     * 2019-06-27 by hmc
    */
    public function del_procedure( $id )
    {
        $id = $this->procedure_model->del_procedure( $id );
        $this->data_cache->invalidate_procedures();
        $this->session->set_flashdata('msg', 'Procedure has been removed successfully!');
        redirect(base_url('admin/procedure/lists'));
    }

    /**
     * get procedure info via modality
     * 2019-06-26 by hmc
     */
    public function get_procedures_via_modality()
    {
        $modality = $this->input->post("modality", TRUE);
        $res = $this->procedure_model->get_procedures_via_modality($modality);

        $res_data = array(
            'status' => 0
        );

        if( $res ) {
            $res_data = array(
                'status' => 1,
                'list'=>$res,
            );
        }

        res_write($res_data);
    }

    /**
     * get procedure info via id
     * 2019-06-26 by hmc
     */
    public function get_procedure_info_via_id()
    {
        $id = $this->input->post("id", TRUE);
        $res = $this->procedure_model->get_procedure_info_via_id($id);

        $res_data = array(
            'status' => 0
        );

        if( $res ) {
            $res_data = array(
                'status' => 1,
                'info'=>$res,
            );
        }

        res_write($res_data);
    }
}