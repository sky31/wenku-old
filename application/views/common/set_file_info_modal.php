<div class="modal fade" id="setFileInfoModal" tabindex="-1" role="dialog" aria-labelledby="setFileInfoModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="setFileInfoModalLabel">
					<span class="glyphicon glyphicon-setting"></span>
					<span >设置文件信息</span>
				</h4>
			</div>
			<div class="modal-body m-m-b">
				<div class="row">
					<div class="col-log-12">
					<form id="smForm" class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<label class="col-lg-2 control-label">文件名</label>
								<div class="col-lg-9">
									<input id="smInputFileName" class="form-control" disabled value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">积分</label>
								<div class="col-lg-9" id="smInputJFDiv">
									
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">类型</label>
								<div class="col-lg-9" id="smInputCatalogDiv">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">简介</label>
								<div class="col-lg-9" id="smInputIntroDiv">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label"></label>
								<div class="col-lg-8">
									<span id="smUpBtnDiv"></span>
									<span id="smBtnAlert"></span>
								</div>
							</div>
						</fieldset>
					</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>