
	
	<h2>余料领用单</h2>
	<hr>
	<div>
		<table class="layui-table">
			<thead>
				<tr>
			      <th>序号</th>
			      <th>客户名称</th>
			      <th>产品名称</th>
			      <th>余料米数</th>
			      <th>余料数量</th>
			      <th>单位</th>
			      <th>仓位</th>
			      <th>单号</th>
			    </tr> 
			</thead>
		<?php
		
			$i =0;
		 foreach($list as $value){
		 	$i++;

		 	$txt = "<tr><td>{$i}</td><td>". $value['khmc']."</td>".
		 		   "<td>". $value['cpmc']."</td>".
		 		   "<td>". $value['ylms']."</td>".
		 		   "<td>". $value['ylsl']."</td>".
		 		   "<td>". $value['dw']."</td>".
		 		   "<td>". $value['cw']."</td>".
		 		   "<td>". $value['lybz']."</td></tr>";	
		 	echo $txt;

		 }
		 	
		?>
		</table>
	</div>

	<script type="text/javascript">
	
		layui.use(['form', 'table', 'layer'], function(){
			var layer = layui.layer;
			printpage();

			function printpage()
		    {
		      window.print()
		    }

		    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
			parent.layer.close(index); //再执行关闭  

		});
		
	</script>
