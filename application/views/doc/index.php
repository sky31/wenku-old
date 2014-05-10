

	<div class="page-header text-center" id="banner">
		<img src="/static/image/logo.png">
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="m-index-search form-inline">
				
					<!--
					<input class="form-control" id="inputSearch" type="text">
					<button type="submit" class="btn btn-success">搜索</button>
					-->
					<div class="row">
						<div class="col-lg-8 col-lg-offset-3">
							<div class="row m-serach-row">
								<div class="col-lg-9 m-search-row-input" >
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
						<div class="col-lg-9 col-lg-offset-3">
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
	</div>
	<br><br><br>
	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">新上传</h3>
				</div>
				<ul class="panel-body list-group">
					<?php
						foreach($new_file_list as $m) {
					?>
					<li class="list-group-item">
						<span class="pull-right"> <?php echo $m['nickname'];?> 上传</span>
						<a href="/view/<?php echo $m['fid'];?>" target="_blank"><?php echo $m['fname'];?></a>
					</li>
					<?php }?>
				</ul>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">周下载排行</h3>
				</div>
				<ul class="panel-body list-group">
					<?php
						foreach($week_list as $m){
					?>
					<li class="list-group-item">
						<span class="badge"><?php echo $m['ranks'];?></span>
						<a href="/view/<?php echo $m['fid'];?>" target="_blank"><?php echo $m['fname'];?></a>
					</li>
					<?php
						}
					?>
				</ul>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">月下载排行</h3>
				</div>
				<ul class="panel-body list-group">
					<?php
						foreach($month_list as $m){
					?>
					<li class="list-group-item">
						<span class="badge"><?php echo $m['ranks'];?></span>
						<a href="/view/<?php echo $m['fid'];?>" target="_blank"><?php echo $m['fname'];?></a>
					</li>
					<?php
						}
					?>
				</ul>
			</div>
		</div>
	</div>
	