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
	<div class="col-lg-12 text-center">
		<h2 class="text-success">注册成功！</h2>
		<p> 登录号：<?php echo $login_num;?> email：<?php echo $email; ?> <a href="/login">点此登录</a></p>
	</div>
</div>
