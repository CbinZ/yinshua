<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruku extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('basic_model');
        $this->load->library('form_validation');
        $this->load->library('Tool');


//        $this->load->library('session');

    }


    public function view()
    {

        $this->load->view('inc/head.html');
        $this->load->view('ruku/ruku.html');
        $this->load->view('inc/foot.html');

    }

    public function data()
    {
        header("Content-Type: text/html;charowet=utf-8");

        $data = array(
            "code"=>0,
            "msg" => "",
            "count" => "",

            );

        $i = 1;
        if (isset($_GET['scdh']) && !empty($_GET['scdh'])) {
            # code...

            $db = $this->tool->pod_sqlserver();
            $dh = "SC".$_GET['scdh'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            $pages = ($page - 1) * $limit;
            /*$sql="SELECT * 
            from proProductionDetail as a, 
            CustomerShortName as b 
            where ParentId=(SELECT SingleId FROM proProduction where SingleNo='".$dh."') and b.CustomerId = a.CustomerId";*/

            $sql = "SELECT
            proProductionDetail.ProductNo,
            proProductionDetail.ProductName,
            proProductionDetail.ProductSpecs,
            proProductionDetail.SoQty,
            cusCustomer.CustomerShortName,
            sysUnit.UnitName,
            cmProduct.ProductLength,
            cmProduct.Custom7
            FROM proProductionDetail 
            INNER JOIN proProduction ON proProductionDetail.ParentId = proProduction.SingleId
            INNER JOIN cusCustomer ON cusCustomer.CustomerId = proProductionDetail.CustomerId
            INNER JOIN sysUnit ON sysUnit.UnitId = proProductionDetail.QUnit
            INNER JOIN cmProduct ON cmProduct.SingleId = proProductionDetail.ProductId
            where proProduction.SingleNo = '{$dh}'
            
            ";
            

            $res = $db->query($sql);
            $row = $res->fetchAll(PDO::FETCH_ASSOC);

            $mysql_data = $this->basic_model->get_where("t_rk", array('scdh'=>$dh));
            $scdh_arr = array();
            foreach ($mysql_data as $key=>$value_mysql) {
                    # code...
                $scdh_arr[$key] = $value_mysql['cpbh'];
            }

            $data['count'] = count($row);


            //var_dump($row);
            
            $chunk_arr = array_chunk($row, $limit);
            $page_ye = $page - 1;
            
            if (empty($row)) {
                # code...
                exit(json_encode($data));
            }
            foreach ($chunk_arr[$page_ye] as $value) {
                # code...
                $ruku_sate = 0;
                $ly = 0;
                if (in_array($value['ProductNo'], $scdh_arr)) {
                    # code...
                    // continue;
                    $arr_key = array_search($value['ProductNo'], $scdh_arr);
                    $ruku_sate = 1;
                    $ly = $mysql_data[$arr_key]['ly'];
                    $data['data'][]= array(
                        'xh'=>$i,
                        'scdh' => $_GET['scdh'],
                        'khmc'=> $mysql_data[$arr_key]['khmc'],
                        'cpbh' => $mysql_data[$arr_key]['cpbh'],
                        'cpmc' => $mysql_data[$arr_key]['cpmc'],
                        'cpgg' => $mysql_data[$arr_key]['cpgg'],
                        'ystb' => ceil($mysql_data[$arr_key]['ystb']),

                        'ylms' => $mysql_data[$arr_key]['ylms'],
                        'pbsl' => $mysql_data[$arr_key]['pbms'],
                        'ylsl' => $mysql_data[$arr_key]['ylsl'],
                        'dw' => $mysql_data[$arr_key]['dw'],
                        'cw' => $mysql_data[$arr_key]['cw'],
                        'ruku_sate' => $ruku_sate,
                        'ly' => $ly  
                        );
                    
                }else{
                    $mo = 0;
                    if (!empty($value['Custom7'])) {
                    # code...
                        $arr = explode("*", $value['Custom7']);
                        $mo = $arr[0]* $arr[1];
                    }


                    $data['data'][]= array(
                        'xh'=>$i,
                        'scdh' => $_GET['scdh'],
                        'khmc'=> $value['CustomerShortName'],
                        'cpbh' => $value['ProductNo'],
                        'cpmc' => $value['ProductName'],
                        'cpgg' => $value['ProductSpecs'],
                        'ystb' => ceil($value['ProductLength']),
                        'ystb_val' => $value['ProductLength'],
                        'ylms' => "",
                        'pbsl' => $mo,
                        'pbsl_val' => $value['Custom7'],
                        'ylsl' => 0,
                        'dw' => $value['UnitName'],
                        'cw' => "",
                        'ruku_sate' => $ruku_sate,
                        'ly' => $ly   
                        );
                }


                $i++; 
            }

        }

        echo json_encode($data);
    }

    public function add()
    {
        $data = array(
            'errcode'=> 999,
            'errmsg' => "操作失败，请联系管理员"
            );

        if (isset($_POST) && !empty($_POST)) {
            # code...
            $ylsl = 0;

            // $ylsl = 1000 * $_POST['data']['pbsl'] *   $_POST['data']['ylms']/ $_POST['data']['ystb_val'];
            $ylsl = ceil($ylsl);
            $rk_arr = array(
                'scdh'=>"SC".$_POST['data']['scdh'],
                'khmc'=>$_POST['data']['khmc'],
                'cpbh'=>$_POST['data']['cpbh'],
                'cpmc'=>$_POST['data']['cpmc'],
                'cpgg'=>$_POST['data']['cpgg'],
                'ystb'=>$_POST['data']['ystb_val'],
                'ylms'=>$_POST['data']['ylms'],
                'pbms'=>$_POST['data']['pbsl'],
                'ylsl'=>$_POST['data']['ylsl'],
                'dw'=>$_POST['data']['dw'],
                'cw'=>$_POST['data']['cw'],
                'rksj'=>date("Y-m-d H:i:s")
                );

            if ($this->basic_model->add('t_rk', $rk_arr)) {
                # code...
             $data = array(
                'errcode'=> 0,
                'errmsg' => "成功"
                );
         }
     }

     echo json_encode($data);
 }

 public function chaxun()
 {
    $this->load->view('inc/head.html');
    $this->load->view('ruku/chaxun.html');
    $this->load->view('inc/foot.html');
}

public function chaxun_data()
{
    header("Content-Type: text/html;charowet=utf-8");

    $data = array(
        "code"=>0,
        "msg" => "",
        "count" => "",

        ); 

    $page = $_GET['page'];
    $limit = $_GET['limit'];

    $pages = ($page - 1) * $limit;
    $i = 1;
    
   // if (!empty($_GET['data']['cpbh']) || !empty($_GET['data']['cpmc']) || !empty($_GET['data']['khmc'])  ) {
                # code...
    $where = "";

    if (!empty($_GET['data']['cpbh'])) {
                    # code...
        $where .= "(cpbh = '{$_GET['data']['cpbh']}')";
    }

    if (!empty($_GET['data']['cpmc'])) {
                    # code...
        if (!empty($where)) {
                        # code...
            $where .= " and ";
        }
        $where .= "(cpmc like '%{$_GET['data']['cpmc']}%')";
    }

    if (!empty($_GET['data']['khmc'])) {
                    # code...
        if (!empty($where)) {
                        # code...
            $where .= " and ";
        }
        $where .= "(khmc like '%{$_GET['data']['khmc']}%')";
    }


    if (!empty($_GET['data']['ly'])) {
          if (!empty($where)) {
            $where .= " and ";
            }
        if ($_GET['data']['ly'] == 1) {
            $where .= "(ly = 1)";
        }else{
        $where .= "(ly is null)";
        }
    }

    if (!empty($_GET['order'])){
        if (!empty($where)) {
                        # code...
            $where .= " and ";
        }

        if ($_GET['order'] == 'desc') {
            $where .= " 1='1' order by rksj asc ";
        }else{
            $where .= " 1='1' order by rksj desc ";   
        }
    }
    $rk_arr = $this->basic_model->get_where("t_rk", $where );


    $data['count'] = count($rk_arr);
    $chunk_arr = array_chunk($rk_arr, $limit);

    $page_ye = $page - 1;

    if (empty($rk_arr)) {
        exit(json_encode($data));
    }

    $totalRow = 0;
    foreach($rk_arr as $value_rk){
        $totalRow += $value_rk['ylsl'];

    }

    $data['totalRow'] = array(
        'ylsl'=>$totalRow 
        );
    foreach ($chunk_arr[$page_ye] as  $value) {

                    # code...

                   /* if (!empty($value['pbms'])) {
                        # code...
                        $arr = explode("*", $value['pgms']);
                        $mo = $arr[0]* $arr[1];
                    }*/

                    if (!empty($value['rksj'])) {
                        $kl =  date_diff(date_create($value['rksj']),date_create(date("Y-m-d")));
                    }

                    //var_dump($kl->format('%R%a 天'));
                    $data['data'][]= array(
                        'xh'=>$i,
                        'scdh' => $value['scdh'],
                        'khmc'=> $value['khmc'],
                        'cpbh' => $value['cpbh'],
                        'cpmc' => $value['cpmc'],
                        'cpgg' => $value['cpgg'],
                        'ystb' => ceil($value['ystb']),                   
                        'ylms' => $value['ylms'],
                        'pbsl' => $value['pbms'],
                        'ylsl' => $value['ylsl'],
                        'dw' => $value['dw'],
                        'cw' => $value['cw'], 
                        'kl' =>  $kl->format('%R%a 天'),
                        'ly' => $value['ly'],
                        'lydh' => $value['lybz']
                        );


                    $i++; 
                }

          //  }


                echo json_encode($data);
            }

            public function xiugai()
            {
                $obj = array(
                    'errcode'=> 999,
                    'errmsg' => "操作失败，请联系管理员"
                    );

                if (isset($_POST) && !empty($_POST)) {

                    $data = $_POST['data'];
                    $ylsl = 0;


                    // $ylsl = 1000 * $data['pbms'] * $data['yxml']/ $data['ystb'];
                    $ylsl = ceil($ylsl);
                    $value_arr = array(
                        'ylms'=>$data['ylms'],
                        'pbms'=>$data['pbms'],
                        'ylsl' => $data['ylsl'],
                        'cw'=>$data['cw'],
                        );

                    if ($this->basic_model->update('t_rk', $value_arr, array('cpbh'=> $data['cpbh']))) {
                        $obj['errcode'] = 0;
                        $obj['errmsg'] = "成功";
                    }
                }

                echo json_encode($obj);
            }

            public function cxrk()
            {
             $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
             if (isset($_POST) && !empty($_POST)) {


                if ($this->basic_model->delete('t_rk', array('cpbh'=> $_POST['cpbh']))) {
                    $obj['errcode'] = 0;
                    $obj['errmsg'] = "成功";
                }
            }

            echo json_encode($obj);

        }

        public function chaxun_ly()
        {
            $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
            if (isset($_POST) && !empty($_POST)) {


                if ($this->basic_model->update('t_rk', array('ly'=> 1, 'lybz'=> $_POST['lybz']), array('cpbh'=>$_POST['cpbh']))) {
                  $obj['errcode'] = 0;
                    $obj['errmsg'] = "成功";
                }
            }

            echo json_encode($obj);
        }

        public function chaxun_plly()
        {
            $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
            if (isset($_POST) && !empty($_POST)) {

                // var_dump($_POST);

                $data = array();
                foreach($_POST['data'] as $value){
                    $data[] = array(
                        'cpbh'=>$value['cpbh'],
                        'ly' =>  1,
                        'lybz' => $_POST['lybz']
                        );
                }

                if ($this->basic_model->xiugai_batech('t_rk', $data, 'cpbh')) {
                    # code...
                    $obj['errcode'] = 0;
                    $obj['errmsg'] = "成功";
                }

            }

            echo json_encode($obj);
        }

        public function chaxun_plbf()
        {
            $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
            if (isset($_POST) && !empty($_POST)) {
                $where = "";

                foreach($_POST['data'] as $value){
                    if (!empty($where)) {
                        $where .= ",";
                    }

                    $where .= "'{$value['cpbh']}'";
                }

                if (!empty($where)) {
                    if ($this->basic_model->delete('t_rk',"cpbh in ({$where})")) {
                             $obj['errcode'] = 0;
                             $obj['errmsg'] = "成功";
                        }    
                }
            }

            echo json_encode($obj);
        }

        public function chaxun_dy()
        {
            $cpbh = $_GET['cpbh'];
            // var_dump($cpbh);
            $data['list'] = $this->basic_model->get_where("t_rk", " cpbh in ('{$cpbh}')");
            $this->load->view('inc/head.html');
            $this->load->view('ruku/dayin.php', $data);
            $this->load->view('inc/foot.html');
        }

        public function chaxun_cxly()
        {
            $obj = array(
                'errcode'=> 999,
                'errmsg' => "操作失败，请联系管理员"
                );
             if (isset($_POST) && !empty($_POST)) {


                if ($this->basic_model->update('t_rk', array('ly'=> null ), array('cpbh'=>$_POST['cpbh']))) {
                    $obj['errcode'] = 0;
                    $obj['errmsg'] = "成功";
                }
            }

            echo json_encode($obj);
        }
    }