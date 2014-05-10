<div class="panel panel-success">
	<div class="panel-heading">收藏</div>
	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>文件名</th>
				<th>积分</th>
				<th>收藏时间</th>
				<th>发布者</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php 

			foreach ($list as $m){
			?>
			<tr>
				<td><img src="/static/image/<?php echo substr($m['extension'], 0, 3);?>.png" height="24"></td>
				<td><a href="/view/<?php echo $m['fid'];?>"><?php echo $m['fname'].'.'.$m['extension'];?></a></td>
				<td><?php echo $m['jf'];?>积分</td>
				<td><?php echo date('Y-m-d H:i:s', $m['cot']);?></td>
				<td><?php echo $m['nickname'];?></td>
				<td>
					<button class="btn btn-danger btn-sm">移除</button>
				</td>
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