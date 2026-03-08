<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller {
    public function __construct(){
        parent::__construct();

    }
    public function index(){
        print_r('test');
    }
    //-------------------------------------------------------------------------
    public function db_patch(){
        $sql_list=array();
        $qry_list = array();
        $sql_list[]="DROP TABLE `tbl_orderdetail`";

        $sql_list[]="CREATE TABLE `tbl_insurance_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sql_list[]="CREATE TABLE `tbl_insurance_company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        foreach($sql_list as $sql){
            try{
                $qry_list[] = $this->db->query($sql);
            }catch (Exception $e){
                $qry_list[] = false;
            }
        }
        print_r($qry_list);
    }
    public function file_patch(){
        unlink(APPPATH."controllers/admin/Order.php");
        unlink(APPPATH."views/admin/orders/add.php");
        unlink(APPPATH."views/admin/orders/list.php");
        unlink(FCPATH."public/custom/js/back/order.js");
    }
}

?>
