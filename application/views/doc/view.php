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
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<p class="m-path"><a href="/">湘大文库</a> > <a href="/lists/<?php echo $file['catalog'];?>"><?php echo $catalog_name;?></a></p>
				<h3 class="m-lg-title">
					<img src="/static/image/ppt.png" height="28" > <?php echo $file['fname'];?>
					<button class="btn btn-success btn-sm pull-right">下载</button>
					<button class="btn btn-info btn-sm pull-right">收藏</button>
				</h3>
				<p class="m-path"><?php echo $file['view_times'];?>次阅读&nbsp;&nbsp;<?php echo $file['down_times'];?>次下载&nbsp;&nbsp; <?php echo $file['jf'];?>积分</p>
			</div>
		</div><!-- row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="reader-div">
					<object width="100%" height="100%" align="middle">
						<param name="loop" value="true">
						<param name="allowfullscreen" value="true">
						<param name="allowsearch" value="fales">
						<param name="wmode" value="opaque">
						<param name="bgcolor" value="#FFFFFF">
						<param name="allowscriptaccess" value="always">
						<param name="movie" value="/static/swf/reader.swf">
						<param name="flashvars" value="totalpages=<?php echo $file['pages'];?>&amp;docurl=/swf_page/?fid=<?php echo $file['fid'];?>">
						<embed loop="true" allowfullscreen="true" width="100%" height="100%" wmode="opaque" ver="10.0" errormessage="请下载最新的Flash播放器！" bgcolor="#FFFFFF" allowscriptaccess="always" align="middle" flashvars="totalpages=<?php echo $file['pages'];?>&amp;docurl=/swf_page/?fid=<?php echo $file['fid'];?>" src="/static/swf/reader.swf" name="chunleireader" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
					</object>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="row">
			<div class="col-lg-12 m-up">
				<button id="openUploadModal" class="btn btn-warning form-control"><span class="glyphicon glyphicon-upload"></span> 上传我的文档</button>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-success m-cp">
					<div class="panel-heading">
						<h3 class="panel-title">文档信息</h3>
					</div>
					<div class="panel-body">
						<div class="media">
							<a class="pull-left" href="#">
								<img class="media-object" src="/static/image/face/<?php echo $file['face'];?>.jpg" width="48">
							</a>
							<div class="media-body">
								<h4 class="media-heading"><?php echo $file['nickname'];?></h4>
								上传于 <?php echo date('Y-m-d', $file['up_date']);?>
							</div>
						</div>
						<hr>
						<p class="m-v-intro"><strong>文档简介：</strong>哈哈123456士大夫撒旦解放螺丝钉飞机离开盛大交房了空间</p>
					</div>
				</div>
				<div class="panel panel-success m-cp">
					<div class="panel-heading">相似文档
					</div>
					<ul class="panel-body list-group m-pd-lg">
						<li class="list-group-item">
							<a href="#">101我们很好阿斯蒂斯蒂芬散打</a>
						</li>
						<li class="list-group-item">
							<a href="#">as df saf df ds fsd</a>
						</li>
						<li class="list-group-item">
							<a href="#">sdf sdf sf sdf sfg </a>
						</li>
						<li class="list-group-item">
							<a href="#">as df saf df ds fsd</a>
						</li>
						<li class="list-group-item">
							<a href="#">as df saf df ds fsd</a>
						</li>
					</ul>
				</div>
			</div>
		</div><!--row-->
	</div>
</div>
