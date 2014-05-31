<div class="panel panel-success">
	<div class="panel-heading">修改个人信息</div>
	<div class="panel-body">
<div class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-lg-1 control-label">姓名</label>
      <div class="col-lg-10">
        <input class="form-control" disabled value="<?php echo $user_name;?>">
      </div>
    </div>

    <div class="form-group">
      <label for="inputNickname" class="col-lg-1 control-label">昵称</label>
      <div class="col-lg-10">
        <input class="form-control" id="icuNickname" name="nickname" placeholder="昵称" value="<?php echo $user_nickname;?>">
      </div>
    </div>

    <div class="form-group">
      <label for="inputNickname" class="col-lg-1 control-label">头像</label>
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
      			<input type="radio" class="inputFace" <?php if($index==$user_face) echo 'checked';?> name="faceInput" value="<?php echo $index;?>">
      		</div>
      		<?php }?>
      	</div>

      	<?php }?>
      </div>
    </div>

    <div class="form-group">
      <label for="inputEmail" class="col-lg-1 control-label">Email</label>
      <div class="col-lg-10">
        <input class="form-control" id="icuEmail" name="email" placeholder="Email" type="text" value="<?php echo $user_email;?>">
      </div>
    </div>

    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <button  type="submit" id="cuUpBtn" class="btn btn-success">提交</button>
        &nbsp;&nbsp;&nbsp;<span class="text-danger" id="cuAlert"></span>
      </div>
    </div>
  </fieldset>
</div>

	</div>
</div>