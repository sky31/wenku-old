<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="uploadModalLabel"><span class="glyphicon glyphicon-upload"></span>上传新文件</h4>
      </div>
      <div class="modal-body">
        

        <div class="row" id="modalUploadBody">
            <div class="col-lg-12 text-center">
                <div id="showResult"></div>
            </div>
            <div class="col-lg-6 m-up-ts">
                * 允许上传文件的格式包括：pdf, doc, docx, xls, xlsx, ppt, pptx。<br>
                * 单个文件大小最大30M。<br>
                * 按住CTRL键可以选择多个文件上传。<br>
                * 成功上传一个文件获得 3 积分
            </div>
            <div class="col-lg-6">
              <div class="row">
                  <div class="col-lg-12">
                    <input id="file_upload" name="file_upload" type="file" multiple="true">
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12">
                      <span class="text-success" id="uploadResultShow"></span><br/>
                      <span class="text-danger" id="uploadErrorShow"></span>
                  </div>
              </div>
            </div>
        </div>
        <div class="row" id="modalPassBody" style="display:none;">
            <div class="col-lg-12 text-center">
              设置文件下载积分
            </div>
            <br>
            <div class="col-lg-12">
              <form id="jfForm">
                <table class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>文件名</th>
                      <th>上传状态</th>
                      <th>下载积分</th>
                      <th>分类</th>
                    </tr>
                  </thead>
                  <tbody id="tablePassListBody">
                  </tbody>
                </table> 
              </form>
            </div>
            <br>
            <div class="col-lg-7">
              * 其他用户下载你的文件：<br>
              &nbsp;&nbsp;你得到的积分 = (1 + "文件积分")<br>
              * 本着共享的精神，推荐 文件积分=0
            </div>
            <div class="col-lg-4 text-right">
              <button id="submitJf" class="btn btn-primary btn-sm">提交</button>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
var C_DOC_SESS_ID = '<?php echo session_id();?>';
</script>