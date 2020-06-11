<?php
    class Basic_model extends CI_Model{

        /**
         * Basic_model constructor.
         */
        public function __construct()
        {
            $this->load->database();
            $this->load->dbforge();
        }


        public function add($dbname, $array)
        {
            $this->db->insert($dbname, $array);
            $data = $this->db->insert_id();
            if (!empty($data)){
                return $data;
            }else{
                return true;
            }

        }


        // 新建表
        public function add_field($dbname)
        {
            $sql = "CREATE TABLE  if not exists {$dbname} 
                (
                id bigint primary key AUTO_INCREMENT,
                fraction  varchar(10),
                created_time  timestamp
                )";

            $data = $this->db->query($sql);

           if ($data) {
               return true;
           } else {
               return false;
           }
            // 返回sql语句
//            return $this->db->last_query();

        }

        // 添加字段
        public function add_column($dbname, $field, $type)
        {
            $sql = "alter table {$dbname} ADD {$field} {$type} ";
            $data = $this->db->query($sql);

            if ($data) {
                return true;
            } else {
                return false;
            }
        }

        // 删除字段
        public function dle_column($dbname, $field)
        {
            $sql = "alter table {$dbname}  DROP COLUMN {$field}";
            $data = $this->db->query($sql);
            if ($data) {
                return true;
            } else {
                return false;
            }

        }

        // 获取网格人员管理
        public function get_tables($where)
        {
                $this->db->from('changsha_wg_type');
                $this->db->where($where);
                $this->db->join('changsha_user','changsha_user.id=changsha_wg_type.userid');
       

                $query =$this->db->get();



//            return $this->db->last_query();
            return $query->result_array();
        }

        // 获取网格人员接收
        public function get_user_jieshou($where, $limit = false)
        {
            $this->db->from('changsha_jindurenyuan');
            $this->db->where($where);
            if ($limit != false) {
                # code...
                $this->db->limit($limit[0],$limit[1]);

            }
            $this->db->join('changsha_user', 'changsha_user.id=changsha_jindurenyuan.user_id');
            $this->db->join('changsha_guanlianxinxi', 'changsha_guanlianxinxi.user_id=changsha_user.id');

            $query =$this->db->get();



//            return $this->db->last_query();
            return $query->result_array();
        }

        // 公用join多表联查
        public function get_join($dbname, $join = false, $where = false ,$limit = false, $desc = false, $td = false)
        {
            $this->db->from($dbname);
            if ($td !== false){
                $this->db->select($td);
            }


            if ($where !== false){
                $this->db->where($where);
            }

            if ($limit !== false)
            {
                $this->db->limit($limit[0],$limit[1]);
            }

            if ($desc !== false)
            {
                $this->db->order_by($desc, 'DESC');
            }

            if ($join !== false){
                foreach ($join as $key=>$value){
                    $this->db->join($key, $value);
                }
            }

            $query =$this->db->get();
            return $query->result_array();

        }

        public function get_where($dbname, $array = '', $limit = false, $desc = false, $td = false)
        {


            if ($td !== false){
                $this->db->select($td);
            }

            if ($array !== '' )
            {
               $this->db->where($array);
            }

            if ($limit !== false)
            {
                $this->db->limit($limit[0],$limit[1]);
            }

            if ($desc !== false)
            {
                $this->db->order_by($desc, 'DESC');
            }

            $data = $this->db->get($dbname);


            return $data->result_array();
        }


        public function update($dbname, $value, $where = '')
        {
            if ($where == '')
            {
                return $this->db->update($dbname, $value);
            }
            return $this->db->update($dbname, $value, $where);
        }



        public function delete($dbname, $where)
        {
            return $this->db->delete($dbname, $where);
        }

        //批量插入
        public function add_batch($dbname, $array)
        {
            //格式
            // $data=array(array('title'=>'My title','name'=>'My Name','date'=>'My date'),array('title'=>'Another title','name'=>'Another Name','date'=>'Another date'));
            //$this->db->insert_batch('mytable',$data);

            return $this->db->insert_batch($dbname, $array);

        }

        // 批量修改
        public function xiugai_batech($dbname, $array, $where)
        {
         /*   $data = array(
                array(
                    'title' => 'My title' ,
                    'name' => 'My Name 2' ,
                    'date' => 'My date 2'
                ),
                array(
                    'title' => 'Another title' ,
                    'name' => 'Another Name 2' ,
                    'date' => 'Another date 2'
                ));

            $this->db->update_batch('mytable', $data, 'title'); */

            return $this->db->update_batch($dbname, $array, $where);
        }


        //栏目
        public function lm_get($id='')
        {
            if ($id == '') {
                $data =  $this->get_where('pg_lm', array('sjlm'=> null));
            }
            else{
                $data = $this->get_where('pg_lm', array('sjlm'=> $id));
            }
            foreach ($data as &$val)
            {
                $val['xj'] = $this->lm_get($val['id']);
            }

            return $data;

        }


        // 测试--select
        public function ceshi($sql){

            $data = $this->db->query($sql);

            if ($data) {
                return $data->result_array();
            } else {
                return false;
            }
        }

        // 测试--INSERT
        public function ceshi2($sql){
            $data = $this->db->query($sql);

            return $data;

        }


        // 统计
        public function count($dbname,$array = ''){
//            if ($array !== '' )
//            {
//                $this->db->where($array);
//            }


            $data =  $this->db->count_all($dbname);
            $this->db->where($array);

//            return $data;
            return $this->db->last_query();
        }

    }
