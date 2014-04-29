<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="uploadModalLabel"><span class="glyphicon glyphicon-upload"></span>上传新文件</h4>
      </div>
      <div class="modal-body">
        <div class="row text-center">
            <div class="col-lg-12"  id="uploadStatus" style="display:none;">
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-10 col-lg-offset-2"  id="uploadForm">
             <input type="file" name="newFileUpload" id="newFileUpload">
             <br>
             <button type="button" id="modalUploadBtn" class="btn btn-success btn-sm" data-dismiss="modal">上传</button>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-lg-12" id="uploadLoading" style="display:none;">
                <img src="/static/image/loading.gif">
                <br>
                正在上传中...
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

$("#modalUploadBtn").click(function(event) {
    /* Act on the event */
    if($("#newFileUpload").val()=="") {
      $("#uploadStatus").show();
      $("#uploadStatus").attr('class', 'col-lg-12 alert alert-warning');
      $("#uploadStatus").html("请选择文件");
      return false;
    }
    $("#uploadForm").hide();
    $("#uploadLoading").show();
    $("#uploadStatus").hide();

    $.ajaxFileUpload
    (
      {
        url:'/welcome/do_upload',
        secureuri:false,
        fileElementId:'newFileUpload',
        dataType: 'json',
        success: function (data, status)
        {
          $("#uploadLoading").hide();
          if(typeof(data.error) != 'undefined')
          {
            if(data.error != '')
            {
              $("#uploadStatus").show();
              $("#uploadStatus").attr('class', 'col-lg-12 alert alert-danger');
              $("#uploadStatus").html("出错了:"+data.error);
              $("#uploadForm").show();
            }else
            {
              $("#uploadStatus").attr('class',"col-lg-12");
              $("#uploadStatus").html("<span class='text text-success'>上传成功，增加2点积分</span>");
              $("#uploadStatus").show();
              $("#newFileUpload").val("");
              $("#uploadForm").show();
            }
          }
        },
        error: function (data, status, e)
        {
          $("#uploadLoading").hide();
          $("#uploadStatus").show();
          $("#uploadStatus").attr('class', 'col-lg-12 alert alert-danger');
          $("#uploadStatus").html("出错了:"+e);
          $("#uploadForm").show();
        }
      }
    );
    return false;
  });