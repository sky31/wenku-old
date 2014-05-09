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
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						个人中心
					</div>
					<div class="panel-body">
						<div class="media">
							<a class="pull-left" href="#">
								<img class="media-object" src="/static/image/face/<?php echo $user_face;?>.jpg" width="70">
							</a>
							<div class="media-body">
								<h4 class="media-heading"><?php echo $user_nickname;?></h4>
								积分：<?php echo $user_jf; ?><br>
								文档数：<?php echo $user_doc_count; ?>
							</div>
						</div>
						<br/>
						<div class="form">
							<button id="openUploadModal" class="btn btn-warning form-control">
								<span class="glyphicon glyphicon-upload"></span> 上传我的文档
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="list-group">
					<a class="list-group-item <?php if($action=='index') echo 'active'; ?>" href="/">我的文档<span class="badge">12</span></a>
					<a class="list-group-item <?php if($action=='collection') echo 'active'; ?>" href="/home/collection">我的收藏<span class="badge">12</span></a>
					<a class="list-group-item <?php if($action=='edit') echo 'active'; ?>" href="/home/edit">修改资料</a>
					<a class="list-group-item <?php if($action=='cpass') echo 'cpass'; ?>" href="/home/cpass">修改密码</a>
					<a class="list-group-item" href="/home/logout">退出登录</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9">
		