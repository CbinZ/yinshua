<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storehouse extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('basic_model');
        $this->load->library('form_validation');
        $this->load->library('Tool');

       
//        $this->load->library('session');
        session_start();
        
    }

    public function zsgc()
    {
        $this->load->view("storehouse/view.html");
    }

    public function data()
    {
        $data = array(
            "code"=>0,
            "msg" => "",
            "count" => "",

            );
        $page = !empty($_GET['page']) ? $_GET['page'] : 1 ;
        $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;

        $pages = ($page - 1) * $limit;
        $get_where = "";
        
        if (!empty($_GET['data']['name'])) {
           $get_where .= " and (storehouse_name like '%{$_GET['data']['name']}%')";
        }

        if (!empty($_GET['data']['name_sc'])) {
           $get_where .= " and (storehouse_name_sc like '%{$_GET['data']['name_sc']}%')";
        }

        if (!empty($_GET['data']['building'])) {
           $get_where .= " and (storehouse_name like '%{$_GET['data']['building']}%')";
        }

        if (!empty($_GET['data']['room'])) {
           $get_where .= " and (storehouse_name_sc like '%{$_GET['data']['room']}%')";
        }
        
        if (!empty($_GET['data']['desc'])) {
           $get_where .= " and (storehouse_name_sc like '%{$_GET['data']['desc']}%')";
        }

        $data_val = $this->basic_model->ceshi("
                select 
                id,
                storehouse_name, 
                storehouse_name_sc, 
                storehouse_building, 
                storehouse_room, 
                storehouse_desc, 
                storehouse_createtime 
                from t_storehouse
                where isUse = '0' {$get_where} order by storehouse_createtime desc limit {$pages}, $limit
            ");

        $data_count = $this->basic_model->ceshi("
                select count(id) as shumu from t_storehouse where isUse = '0' {$get_where}
            ");

        foreach ($data_val as $value) {
            $data['data'][] = array(
                "id" => $value['id'],
                "storehouse_name" => $value['storehouse_name'],
                "storehouse_name_sc" => $value['storehouse_name_sc'],
                "storehouse_building" => $value['storehouse_building'],
                "storehouse_room" => $value['storehouse_room'],
                "storehouse_desc" => $value['storehouse_desc'],
                "storehouse_createtime" => $value['storehouse_createtime']
                );
        }
        $data['count'] = $data_count[0]['shumu'];
        echo json_encode($data);
    }

    public function add()
    {
        $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
        $time = date("Y-m-d H:i:s");
        $data = array(
            "storehouse_name" => $_POST['data']['name'],
            "storehouse_name_sc" => $_POST['data']['name_sc'],
            "storehouse_building" => $_POST['data']['building'],
            "storehouse_room" => $_POST['data']['room'],
            "storehouse_desc" => $_POST['data']['desc'],
            "storehouse_createtime" => $time,
            "isUse"=> 0
            );

        if ($this->basic_model->add("t_storehouse", $data)) {
            $obj['errcode'] = 0;
            $obj['errmsg'] = "成功";
        }

        echo json_encode($obj);
    }

    public function edit()
    {
       $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
        $time = date("Y-m-d H:i:s");
        if (!empty($_POST['data']) && isset($_POST['data'])) {
            $data = array(
            "storehouse_name" => $_POST['data']['name'],
            "storehouse_name_sc" => $_POST['data']['name_sc'],
            "storehouse_building" => $_POST['data']['building'],
            "storehouse_room" => $_POST['data']['room'],
            "storehouse_desc" => $_POST['data']['desc']
            );

            if ($this->basic_model->update("t_storehouse", $data, array('id'=>$_POST['data']['id']))) {
                $obj['errcode'] = 0;
                $obj['errmsg'] = "成功";
            }

        }
        
        echo json_encode($obj); 
    }

    public function del()
    {
       $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
        $time = date("Y-m-d H:i:s");
        if (!empty($_POST['data']) && isset($_POST['data'])) {
           if ($this->basic_model->update("t_storehouse", array("isUse"=>1), array('id'=>$_POST['data']))) {
                $obj['errcode'] = 0;
                $obj['errmsg'] = "成功";
            }  
        }
        echo json_encode($obj); 
    }

}
