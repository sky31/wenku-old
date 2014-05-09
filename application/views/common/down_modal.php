<div class="modal fade" id="downModal" tabindex="-1" role="dialog" aria-labelledby="downModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="downModalLabel">
					<span class="glyphicon glyphicon-download"></span>
					<span class="fileName"></span>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 text-center">
						<p>文件大小：<span class="fileSize"></span></p>
						<p>所需积分：<span class="fileJF"></span></p>
						<p>你当前的积分：<span class="userJF"></span></p>
						<p class="haveDown text-success" style="display:none;"></p>
						<p><button id="downFileBtn" class="btn btn-success">立即下载</button></p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>