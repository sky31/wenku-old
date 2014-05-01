<div class="row" id="bannerSearch">
	<div class="col-lg-3 text-center">
		<img src="/static/image/logo.png" height="70">
	</div>
	<div class="col-lg-8 list-search-div">
		<div class="row">
		<form class="form-inline">
			<div class="col-lg-12">
			
				<div class="row m-serach-row">
          <div class="col-lg-7 m-search-row-input" >
            <div class="form-group" id="m-input-search-div">
              <input class="form-control" name="test" id="inputSearch" type="text">
            </div>
                  
          </div>
          <div class="col-lg-3 m-search-row-btn">
             <button type="button" class="btn btn-success">搜索</button>
          </div>
        </div>
			</div>
			<br>
			<div class="col-lg-12">
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						全部
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
						DOC
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
						PPT
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
						PDF
					</label>
				</div>
				<div class="radio">
					<label>
						<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
						XLS
					</label>
				</div>
			</div>
		</form>
		</div><!-- row -->
	</div>
</div>
<div class="row">
	<div class="col-lg-8">

<form class="form-horizontal" action="" method="post" name="registerForm">
  <fieldset>
    <legend class="text-center">校园用户接入</legend>
    <div class="form-group">
      <label class="col-lg-2 control-label">帐号</label>
      <div class="col-lg-10">
      	<input class="form-control" disabled value="<?php echo $reg_num;?>">
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label">姓名</label>
      <div class="col-lg-10">
        <input class="form-control" disabled value="<?php echo $reg_name;?>">
      </div>
    </div>

    <div class="form-group">
      <label for="inputNickname" class="col-lg-2 control-label">昵称</label>
      <div class="col-lg-10">
        <input class="form-control" id="inputNickname" name="nickname" placeholder="昵称" value="<?php echo $reg_name;?>">
      </div>
    </div>

    <div class="form-group">
      <label for="inputNickname" class="col-lg-2 control-label">头像</label>
      <div class="col-lg-10">
      	<?php
      	for($i=1; $i<=4; $i++) {
      	?>
      	<div class="row text-center">
      		<?php for($j=1;$j<=6;$j++) {
      			if($i>=3) {
      				$index = 'g'.(($i-3)*6 + $j);
      			}
      			else $index = 'b'.(($i-1)*6 + $j);
      			
      		?>
      		<div class="col-lg-2">
      			<img src="/static/image/face/<?php echo $index;?>.jpg" height="72" >
      			<br/>
      			<input type="radio" class="inputFace" name="face" value="<?php echo $index;?>">
      		</div>
      		<?php }?>
      	</div>

      	<?php }?>
      </div>
    </div>

    <div class="form-group">
      <label for="inputEmail" class="col-lg-2 control-label">Email</label>
      <div class="col-lg-10">
        <input class="form-control" id="inputEmail" name="email" placeholder="Email" type="text">
      </div>
    </div>

    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button  type="submit" id="accessBtn" class="btn btn-primary">接入</button>
        &nbsp;&nbsp;&nbsp;<span class="text-danger" id="accessAlert"></span>
      </div>
    </div>
  </fieldset>
</form>

	</div>
</div>
