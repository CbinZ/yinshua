<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipin extends CI_Controller {
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


    public function login()
    {
    	$this->load->view('inc/head.html');
        $this->load->view('shipin/login.html');
        $this->load->view('inc/foot.html');
    }

    public function sign_in()
    {
    	$data = array(
            'errcode'=> 999,
            'errmsg' => "操作失败，请联系管理员"
        );

    	if ($user = $this->basic_model->get_where("sp_user", array('zhanhao'=>$_POST['data']['zhanhao']))) {
    		if ($user[0]['password'] == $_POST['data']['password']) {
    			$_SESSION['user'] = $user[0]['zhanhao'];

    			$data = array(
		            'errcode'=> 0,
		            'errmsg' => "成功"
		        );


    		}else {
    			$data['errmsg'] = "密码错误";
    		}
    	}else{
    		$data['errmsg'] = "查无此账号";
    	}

    	echo json_encode($data);
    }


    public function index()
    {
    	// if  (empty($_SESSION)){
    	// 	redirect('/shipin/login');
    	// 	exit;
     //    }

        $this->load->view('inc/head.html');
        $this->load->view('shipin/index.html');
        $this->load->view('inc/foot.html');

    }

    public function data()
    {

    	// if  (empty($_SESSION)){
    	// 	redirect('/shipin/login');
    	// 	exit;
     //    }
    	$data = array(
            'errcode'=> 999,
            'errmsg' => "操作失败，请联系管理员"
        );

    	if ($list = $this->basic_model->get_where("sp_vido")) {
    	
    		$data['errcode'] = 0;
    		$data['errmsg'] = "成功";

    		foreach ($list as  $value) {
    			$data['data'][] = array(
    					"id"=>$value['id'],
    					"img"=>base_url($value['img']),
    					"name"=>$value['name']
    				); 
    		}

    	}

        echo json_encode($data);
    }


    public function vidoe_page()
    {

    	// if  (empty($_SESSION)){
    	// 	redirect('/shipin/login');
    	// 	exit;
     //    }
		$_SESSION['token'] = md5("123456789abcdefg"); //做一个token 用于失效方案
		$sp_url = $this->basic_model->get_where("sp_vido", array('id'=>$_GET['id']));
    	$data['vurl'] ="/".$sp_url[0]['url'];

    	$this->load->view('inc/head.html');
        $this->load->view('shipin/video.html',$data);
        $this->load->view('inc/foot.html');
    }
    public function video()
    {
    	if  (empty($_SESSION)){
    		redirect('/shipin/login');
    		exit;
        }
    	$vid=$this->uri->segment(3,0);
		if  (empty($_SESSION)){
    		redirect('/shipin/login');
    		exit;
        }
    	$data = $this->basic_model->get_where("sp_vido",array("id"=>$vid)); //通过vid 获取 数据库存放的真实资源地址
        if(isset($_SESSION["token"])){  
            unset($_SESSION["token"]); //删除token，保证每次只能播放一次

           
        //页面直接输出视频
        //$filePath=base_url($data[0]['url']);
        $file=$data[0]['url'];
        //echo $filePath;
        //header("Location:" . $filePath);  
       	//$file = "resource/vidoe/shipin/cs.mp4";
/*        $size = filesize($file); 

	    header("Content-type: video/mp4"); 

	    header("Accept-Ranges: bytes"); 

	    if(isset($_SERVER['HTTP_RANGE'])){ 

	        header("HTTP/1.1 206 Partial Content"); 

	        list($name, $range) = explode("=", $_SERVER['HTTP_RANGE']); 

	        list($begin, $end) =explode("-", $range); 

	        if($end == 0){ 

	            $end = $size - 1; 

	        } 

	    }else { 

	        $begin = 0; $end = $size - 1; 

	    } 

	    header("Content-Length: " . ($end - $begin + 1)); 

	    header("Content-Disposition: filename=".basename($file)); 

	    header("Content-Range: bytes ".$begin."-".$end."/".$size); 

	    $fp = fopen($file, 'rb'); 

	    fseek($fp, $begin); 

	    while(!feof($fp)) { 

	        $p = min(1024, $end - $begin + 1); 

	        $begin += $p; 

	        echo fread($fp, $p); 

	    } 

	    fclose($fp); */

	    ini_set('memory_limit', '512M');
        header("Pragma: public");
        header("Expires: 0");
        header("Content-Type: application/octet-stream"); //文件mime类型
        //header("Content-Disposition: attachment; filename=video11.mp4;" ); //文件名$filename
        //header("Content-Length: 83995");  //文件大小$fsize
        ob_clean();
        flush();
        //ob_end_clean();
        @readfile($file);
    	}
    }

    public function user_add()
    {
    	$this->load->view('inc/head.html');
        $this->load->view('shipin/user.html');
        $this->load->view('inc/foot.html');

    }

    public function user_add_a()
    {
    	ini_set('max_execution_time', '0');
    	$data = array(
            'errcode'=> 999,
            'errmsg' => "操作失败，请联系管理员"
        );

        if (isset($_POST) && !empty($_POST)) {
        	$from_arr = json_decode($_POST['data'], true);
			// $video_type =  array_pop($from_arr);
        	$from_val = array(
        			"name"=>$from_arr['v_name'],
        			"img"=>$from_arr['img_url'],
        			"url"=>$from_arr['video_url'],
        			"created_t"=>date("Y-m-d H:i:s")
        		);

        


        	
        	if ($this->basic_model->add('sp_vido', $from_val)) {
                # code...
             $data = array(
                'errcode'=> 0,
                'errmsg' => "成功"
                );
         	}

        }

        echo json_encode($data);
    }

    public function user_data()
    {
    	$data = array(
        "code"=>0,
        "msg" => "",
        "count" => "",

        ); 

        $user_arr = $this->basic_model->get_where('sp_user', array("id !="=>1));

        foreach ($user_arr as $value) {
        	 $data['data'][]= array(
                       		"id"=>$value['id'],
                       		"user_name"=>$value['user_name'],
                       		"zhanhao"=>$value['zhanhao'],
                       		"password"=>$value['password'],
                       		"user_ytpe"=>$value['user_type']
                        );

        }

        echo json_encode($data);
    }

    public function update_img()
    {
    	 $data = array(
            'success' => false,
            'msg' => '上传失败!',
            'file_path' => ''
        );

    	$file = $this->file_up("resource/video/shipin/img");

        if (!empty($file['error'])) {
            $data['data'] = $file['error'];
        } else {
            $data['success'] = true;
            $data['msg'] = '上传成功';
//            $data['file_path'] = $file['upload_data']['full_path'];
            $data['file_path'] = substr($file['upload_data']['full_path'], strpos($file['upload_data']['full_path'], 'resource/video/shipin/img'));
            $data['data'] = $file;

        }

        echo json_encode($data);
    }

    public function update_video()
    {
    	$data = array(
            'success' => false,
            'msg' => '上传失败!',
            'file_path' => ''
        );

    	$file = $this->file_up("resource/video/shipin/v");

        if (!empty($file['error'])) {
            $data['data'] = $file['error'];
        } else {
            $data['success'] = true;
            $data['msg'] = '上传成功';
//            $data['file_path'] = $file['upload_data']['full_path'];
            $data['file_path'] = substr($file['upload_data']['full_path'], strpos($file['upload_data']['full_path'], 'resource/video/shipin/v'));
            $data['data'] = $file;

        }

        echo json_encode($data);
    }
    public function file_up($file_name = "")
    {
        $path = $file_name.'/'.date("Ymd",time());

        if (!file_exists($path)){ //判断目录是否存在 不存在就创建
            mkdir($path,0777,true);
        }

        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|png|gif|bmp|jpeg|mp3|wma|flac|mp4|avi|3gp';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file'))
        {
            $error = array('error' => $this->upload->display_errors());

            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            return $data;
        }
    }
}