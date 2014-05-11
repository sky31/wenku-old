<div class="row" id="bannerSearch">
	<div class="col-lg-3 text-center">
		<img src="/static/image/logo.png" height="70">
	</div>
	<div class="col-lg-8 list-search-div">
		<div class="row form-inline">
			<div class="col-lg-12">
				<div class="row m-serach-row">
					<div class="col-lg-7 m-search-row-input" >
						<div class="form-group" id="m-input-search-div">
						 	<input class="form-control" name="test" id="inputSearch" type="text">
						</div>
						 			
					</div>
					<div class="col-lg-2 m-search-row-btn">
						 <button type="button" id="mainSearchBtn" class="btn btn-success">搜索</button>
					</div>
				</div>
			</div>
			<br>
			<div class="col-lg-12">
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" value="all" checked>
						全部
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" value="doc">
						DOC
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" value="ppt">
						PPT
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" value="pdf">
						PDF
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" value="xls">
						XLS
					</label>
				</div>
			</div>

		</div><!-- row -->
	</div>
</div>
<div class="row">
	<div class="col-lg-3">
		<div class="list-group">
			<a class="list-group-item <?php if($cur_catalog=='all') echo 'active';?>" href="/lists">全部</a>
			<?php
				foreach($catalog_array as $key=>$value) {
					$active = '';
					if($key == $cur_catalog ) $active = 'active';
					echo '<a class="list-group-item '.$active.'" href="/lists/'.$key.'">'.$value.'</a>'."\n";
				}
			?>	
		</div>
	</div>
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12 m-s-nums">
				找到相关文档 <?php echo $count;?> 篇
			</div>
			<div class="col-lg-12">
				<div class="list-group">
				<?php
					foreach($list as $m) {
				?>
  					<div class="list-group-item">
    					<h4 class="list-group-item-heading">
    						<img src="/static/image/<?php echo substr($m['extension'], 0, 3); ?>.png" height="22">
    						&nbsp;<a href="/view/<?php echo $m['fid'];?>"><?php echo $m['fname'];?></a>
    					</h4>
    					<p class="list-group-item-text"><?php echo $m['intro'];?></p>
    					<p class="list-group-info"><?php echo date('Y-m-d', $m['up_date']);?> | <?php  echo $m['pages']===NULL? '正在处理':'共'.$m['pages'].'页';?> | <?php echo $m['down_times'];?>次下载 | <?php echo $m['jf'];?>积分 | <a href="/lists/<?php echo $m['catalog'];?>"><?php echo $catalog_array[$m['catalog']];?></a> | 贡献者：<?php echo $m['nickname'];?></p>
  					</div>
  				<?php }?>
				</div>
			</div>
			<div class="col-lg-12">
				<?php echo $pagination;?>
			</div>
		</div>

	</div>
</div>
