<?php
/**
 * Created by PhpStorm.
 * User: hk201
 * Date: 1/6/2019
 * Time: 4:37 AM
 */

class ListItem extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('admin/lists_model', 'lists_model');
    }

    // go to facility add page
    public function add(){
        $data['title'] = 'Add List/Item';
        $data['view'] = 'admin/lists/add';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }

    // go to facility lists page
    public function lists(){
        $data['title'] = 'Lists List';
        $data['lists'] = array(); // Empty array, data loaded via AJAX
        $data['view'] = 'admin/lists/list';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }
    
    // Create/Add new list item
    public function create(){
        $data = array(
            'name' => $this->input->post('name'),
            'value' => $this->input->post('value'),
            'code' => $this->input->post('code'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $data = $this->security->xss_clean($data);
        $this->lists_model->create_list($data);
        $this->session->set_flashdata('msg', 'List item has been added successfully!');
        redirect(base_url('admin/listitem/lists'));
    }
    
    // AJAX endpoint for DataTables
    public function get_lists_ajax(){
        $lists = $this->lists_model->get_all_lists();
        echo json_encode(array('data' => $lists));
    }

    // Edit list item page
    public function edit($id = 0){
        $id = intval($id);
        $list = $this->lists_model->get_list($id);
        if(empty($list)){
            $this->session->set_flashdata('msg', 'List item not found');
            redirect(base_url('admin/listitem/lists'));
        }
        $data['title'] = 'Edit List/Item';
        $data['list'] = $list;
        $data['view'] = 'admin/lists/edit';
        $data['page_js'] = array();
        $this->load->view('layout', $data);
    }

    // Update list item
    public function update($id = 0){
        $id = intval($id);
        $update_data = array(
            'name' => $this->input->post('name'),
            'value' => $this->input->post('value'),
            'code' => $this->input->post('code')
        );
        $update_data = $this->security->xss_clean($update_data);
        $this->lists_model->update_list($id, $update_data);
        $this->session->set_flashdata('msg', 'List item has been updated successfully!');
        redirect(base_url('admin/listitem/lists'));
    }

    // Delete list item
    public function delete($id = 0){
        $id = intval($id);
        $this->lists_model->delete_list($id);
        $this->session->set_flashdata('msg', 'List item has been deleted successfully!');
        redirect(base_url('admin/listitem/lists'));
    }
}