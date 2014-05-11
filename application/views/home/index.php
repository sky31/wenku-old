<div class="panel panel-success">
	<div class="panel-heading">我的文档</div>
	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>文件名</th>
				<th>积分</th>
				<th>下载量</th>
				<th>发布时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php 

			foreach ($list as $m){
			?>
			<tr>
				<td><img src="/static/image/<?php echo substr($m['extension'], 0, 3);?>.png" height="24"></td>
				<td><a href="/view/<?php echo $m['fid'];?>" target="_blank"><?php echo $m['fname'].'.'.$m['extension'];?></a></td>
				<?php if($m['is_set']==1){?>
					<td><?php echo $m['jf'];?>积分</td>
					<td><?php echo $m['down_times'];?>次</td>
					<td><?php echo date('Y-m-d H:i:s', $m['up_date']);?></td>
				<?php }else{?>
					<td colspan="3" class="text-danger">未设置，点击编辑进行设置</td>
				<?php }?>
				<td><button target-data="<?php echo $m['fid'];?>" class="btn btn-default btn-sm setFileInfoBtn">编辑</button></td>
			</tr>
			<?php
				}
			?>
			<tr>
			</tr>
		</tbody>
	</table>
	<div class="panel-body m-ul-div">
		<?php echo $pagination;?>
	</div>
</div>