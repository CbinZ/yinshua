<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Huoqu extends CI_Controller {
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



    public function get_tb_name()
    {
    	$db = $this->tool->pod_sqlserver();

    	$sql  = "SELECT NAME FROM SYSOBJECTS WHERE TYPE='U'";
    	$sql  = "SELECT * FROM SYSCOLUMNS WHERE ID=OBJECT_ID('proProductionDetail' )";
    	$sql  = "select a.name tablename, b.name colName, c.name colType ,c.length colLength
			from sysobjects a inner join syscolumns b
			on a.id=b.id and a.xtype='U'
			inner join systypes c
			on b.xtype=c.xusertype

			where a.name='proProductionDetail'";

		 $sql = "select
				col.name as ColumnName,
				col.isnullable as IsNullable,
				col.length as DataLength,
				tp.name as DataType,
				ep.value as Descript,
				(
				    select count(*) from sys.sysobjects
				    where parent_obj=obj.id
				    and name=(
				        select top 1 name from sys.sysindexes ind
				        inner join sys.sysindexkeys indkey
				        on ind.indid=indkey.indid
				        and indkey.colid=col.colid
				        and indkey.id=obj.id
				        where ind.id=obj.id
				        and ind.name like 'PK_%'
				    )
				) as IsPrimaryKey
				from sys.sysobjects obj
				inner join sys.syscolumns col
				on obj.id = col.id
				left join sys.systypes tp
				on col.xtype=tp.xusertype
				left join sys.extended_properties ep
				on ep.major_id=obj.id
				and ep.minor_id=col.colid
				and ep.name='MS_Description'
				where obj.name='proProductionDetail'";

		  $sql = "select * from proProductionDetail ";
    	 $res = $db->query($sql);
         $row = $res->fetchAll(PDO::FETCH_ASSOC);
          var_dump($row);
         $tb_sql = "";
// SplitedChildProductionNo
          exit();
      
         foreach ($row as $value) {
         	# code...
         	$tb_sql .= "{$value['ColumnName']} ";
         	$tb_type = " {$value['DataType']}({$value['DataLength']})";

         	if ($value['DataType'] == 'bit') {
         		# code...
         		$tb_type = ' tinyint';
         	}

         	if ($value['DataType'] == 'datetime' ) {
         		# code...
         		if ($value['DataLength'] > 6) {
         			# code...
         			$tb_type = " {$value['DataType']}(6)";
         		}
         	}

         	if ($value['DataType'] == 'datetime2') {
         		# code...
         		$tb_type = ' datetime';
         	}

         	if ($value['DataType'] == 'datetimeoffset') {
         		# code...
         		$tb_type = ' datetime';
         	}

         	if ($value['DataType'] == 'money') {
         		# code...
         		$tb_type = ' float';
         	}
         	
         	if ($value['DataType'] == 'nchar') {
         		# code...
         		$tb_type = ' char';
         	}

          	if ($value['DataType'] == 'ntext') {
         		# code...
         		$tb_type = ' text';
         	}

          	if ($value['DataType'] == 'numeric') {
         		# code...
         		$tb_type = ' decimal';
         	}

          	if ($value['DataType'] == 'nvarchar') {
         		# code...
         		if ($value['DataLength'] ==  -1) {
         			$tb_type = " varchar(1000)";
         			
         		}elseif ($value['DataLength'] >= 1000) {
         			# code...
         			$tb_type = " varchar(255)";
         		}else{
         			$tb_type = " varchar({$value['DataLength']})";
         		}
         		
         	}

          	if ($value['DataType'] == 'real') {
         		# code...
         		$tb_type = ' float';
         	}

         	if ($value['DataType'] == 'smallmoney') {
         		# code...
         		$tb_type = ' float';
         	}

         	if ($value['DataType'] == 'uniqueidentifier') {
         		# code...
         		$tb_type = ' varchar(40)';
         		
         	}

         	if ($value['DataType'] == 'xml') {
         		# code...
         		$tb_type = ' text';
         	}
         	$tb_sql .= $tb_type;

         	$tb_key = "";
         	if ($value['IsPrimaryKey'] == 1) {
         		# code...
         		$tb_key = " primary key";
         	}
         	$tb_sql .= $tb_key;

         	$tb_null = ' NOT NULL';
         	if ($value['IsNullable'] == 1) {
         		# code...
         		$tb_null = ' NUll';
         	}

         	$tb_sql .= $tb_null.", ";

         	echo "{$value['ColumnName']}:{$value['DataLength']}<br>";
         }

         
         $tb_sql = substr($tb_sql, 0, -2);

        
         $mysql_add = "CREATE TABLE  if not exists  xxddp_proProductionDetail
                (
	              {$tb_sql}
                )";
         
         // var_dump($mysql_add);
         echo $mysql_add;

         var_dump($row);
         /*foreach ($row as $value) {
         	# code...
         	// base64_encode 可以转换 sqlserver 格式
         	echo base64_encode($value['SingleId'])."<br>";
         }*/
    }
}