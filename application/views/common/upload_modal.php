<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="uploadModalLabel"><span class="glyphicon glyphicon-upload"></span>上传新文件</h4>
      </div>
      <div class="modal-body">
        <div class="row" id="modalUploadBody" style="display:none;">
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
        <div class="row" id="modalPassBody">
            <div class="col-lg-12">
                <table class="table table-striped table-hover ">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>文件名</th>
                      <th>积分</th>
                    </tr>
                  </thead>
                  <tbody id="tablePassListBody">
                    <tr class="success">
                      <td>1</td>
                      <td>2013高等数学期末考试试卷</td>
                      <td>
                        <select>
                            <option value="0" selected>0积分</option>
                            <option value="1">1积分</option>
                            <option value="2">2积分</option>
                            <option value="4">4积分</option>
                            <option value="8">8积分</option>
                        </select>
                      </td>
                    </tr>

                    <tr class="danger">
                      <td>1</td>
                      <td>2013高等数学期末考试试卷</td>
                      <td>上传失败</td>
                    </tr>
                  </tbody>
                </table> 
            </div>
            <br>
            <div class="col-lg-7">
              * 其他用户下载你的文件：<br>
                你得到的积分 = (1 + "文件积分")
            </div>
            <div class="col-lg-4 text-right">
              <button class="btn btn-primary btn-sm">提交</button>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->