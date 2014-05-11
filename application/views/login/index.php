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
	<div class="col-lg-6">
    <br>
    <div class="form-horizontal">
      <fieldset>
        <legend class="text-center">用户登录</legend>
        <div class="form-group">
          <label class="col-lg-2 control-label">帐号</label>
          <div class="col-lg-10">
            <input type="text" class="form-control" id="lpInputNums" placeholder="学号/工号" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 control-label">密码</label>
          <div class="col-lg-10">
            <input type="password" class="form-control" id="lpInputPass" placeholder="教务管理系统密码" value="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-2">
            <button  type="submit" id="lpLoginBtn" class="btn btn-danger">登录</button>
            &nbsp;&nbsp;&nbsp;<span class="text-danger" id="lpAccessAlert"></span>
          </div>
        </div>
      </fieldset>
    </div>
	</div>
  <div class="col-lg-5 col-lg-offset-1">
    <div class="row">
        <div class="col-lg-12 "><h3 class="m-h3">湘大文库 · 登录说明</h3></div>
    </div>
    <div class="row">
        <div class="col-lg-12 m-l-intro">
            <br>
            <p>* 湘大文库系统使用<code>教务管理系统</code>帐号密码接入，学生使用学号，教师使用职工号。</p>
            <p>* 首次接入系统获得20点积分，上传一个文件获得2点积分</p>
            <p>* 其他用户每次下载你上传的文件你至少可获得2积分</p>
        </div>
    </div>
  </div>
</div>
