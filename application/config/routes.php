<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



// 入库模块
$route['ruku/view'] = 'Ruku/view'; // 页面
$route['ruku/data'] = 'Ruku/data'; // 数据 
$route['ruku/add'] = 'Ruku/add'; // 入库添加
$route['ruku/chaxun'] = 'Ruku/chaxun';// 查询页面
$route['ruku/chaxun/data'] = 'Ruku/chaxun_data'; //查询数据
$route['ruku/chaxun/ly'] = 'Ruku/chaxun_ly'; // 领用
$route['ruku/chaxun/plly'] = 'Ruku/chaxun_plly'; // 批量领用
$route['ruku/chaxun/plbf'] = 'Ruku/chaxun_plbf'; // 批量报废
$route['ruku/chaxun/dayin'] = 'Ruku/chaxun_dy'; // 打印页面
$route['ruku/cxly'] = 'Ruku/chaxun_cxly'; // 撤销领用

$route['ruku/xiugai'] = 'Ruku/xiugai'; // 修改数据
$route['ruku/cxrk'] = 'Ruku/cxrk'; // 撤销入库


// 仓库
$route['Storehouse/zsgc'] = "Storehouse/zsgc";
$route['storehouse/zsgc'] = "Storehouse/data"; // 数据
$route['storehouse/add'] = "Storehouse/add"; // 添加
$route['storehouse/edit'] = "Storehouse/edit"; // 修改

// 获取数据库表
$route['get/sqlserver'] = 'Huoqu/get_tb_name'; // 获取所有的数据库表


// 星期五删除
$route['shipin/login'] = 'Shipin/login'; //视频登录
$route['shipin/login/sign_in'] = "Shipin/sign_in"; // 登录判断
$route['shipin/index'] = "Shipin/index"; // 首页
$route['shipin/data'] = "Shipin/data"; //视频数据
$route['shipin/video/page'] = "Shipin/vidoe_page"; // 视频详情页
$route['shipin/video/(:any)'] = "Shipin/video"; // 加载视频

$route['shipin/user/add'] = "Shipin/user_add"; //用户添加
$route['shipin/user/add_a'] = "Shipin/user_add_a"; //用户添加
$route['shipin/user/data'] = "Shipin/user_data"; //用户数据

$route['shipin/upload/img'] = "Shipin/update_img"; // img 上传
$route['shipin/upload/video'] = "Shipin/update_video"; // video 上传