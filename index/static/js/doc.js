$(function(){
	$("#topLogin").click(function(event) {
		var reg = /^20[0-9]{8}$|^20[0-9]{10}$|^[0-9]{8}$/;
		if($("#loginUser").val()=="") {
			$("#loginUser").focus();
			return false;
		} else if(!reg.test($("#loginUser").val())) {
			$("#loginUser").focus();
			alert("学号/工号格式错误");
			return false;
		}else if( $("#loginPass").val()=="" ){
			$("#loginPass").focus();
			return false;
		}

		$.post('/login/post', {
			num : $("#loginUser").val(),
			pass : $("#loginPass").val(),
		}, function(data) {
			if(data.ret==0) {
				window.setTimeout('location.href=location.href', 500);
			} else {
				$("#m-access-info").html('<p class="text-center"><img src="/static/image/loading.gif"></p><p>这是你第一次登录文库系统，正在为你接入系统，需等待10秒左右……</p>');
				$('#linkInModal').modal('show');
				// 访问接入
				$.post('/access', {
					num : $("#loginUser").val(),
					pass : $("#loginPass").val(),
				}, function(data) {
					if(data.ret==0) {
						location.href="/register";
					} else {
						$("#m-access-info").html("接入出错：" + data.info);
					}
				});
			} // function
		}); //$.post
	});

	$("#accessBtn").click(function(event) {
		/* Act on the event */
		var radioArray = $(".inputFace");
		var findFlag = false;
		for(i=0;i<radioArray.length; i++) {
			if(radioArray[i].checked) {
				findFlag = true;
				break;
			}
		}
		if(!findFlag) {
			$("#accessAlert").html("必须选择头像");
			return false;
		}
		var isemail=/^\w+([-\.]\w+)*@\w+([\.-]\w+)*\.\w{2,4}$/;
		if(!isemail.test($("#inputEmail").val())) {
			$("#inputEmail").focus();
			$("#accessAlert").html("邮箱格式错误");
			return false;
		}
		return true;
	});
	$("#openUploadModal").click(function(event) {
		/* Act on the event */
		if(DOC_IS_LOGIN) {
			$("#uploadModal").modal("show");
		} else {
			$("#loginUser").focus();
			alert("请先登录");
		}
		return false;
	});

	var XtuDoc = {};
	XtuDoc.uploadQueue = [];
	XtuDoc.lastJf      = 0;
	XtuDoc.catalogArray = {"math":"数学", 'literatrue': ' 文学', 'law':'法学', 'english':'英语', 'foreign': '小语种', 'chemical':'化工', 'physical':'物理', 'histophilo':'哲史', 'political':'思想政治', 'ba':'工商管理', 'economic':'经济/经融', 'newsspread':'新闻/传播', 'advfilm':'广告/影视', 'art':'艺术/美学', 'music':'音乐', 'mechanics':'机械', 'material':'材料', 'civilbuild':'土木/建筑', 'computer':'计算机科学', 'electronic':'电子技术', 'notice':'通知公告', 'table':'表格', 'other': '其他'};
	XtuDoc.CATALOG_OPTION_STR = '';
	XtuDoc.init = function() {
		XtuDoc.CATALOG_OPTION_STR = '<option value="0" checked>请选择...</optin>';
		for(var key in XtuDoc.catalogArray) {
			//if(key == 'other') 
			//	XtuDoc.CATALOG_OPTION_STR += "<option value=\""+key+"\" selected >"+XtuDoc.catalogArray[key]+"</option>"
			//else
				XtuDoc.CATALOG_OPTION_STR += "<option value=\""+key+"\" >"+XtuDoc.catalogArray[key]+"</option>"
		}
	};
	window.setTimeout(XtuDoc.init, 1);

	XtuDoc.showJfList = function () {
			html = "";
			length  = XtuDoc.uploadQueue.length;
			XtuDoc.lastJf  = 0;
			for(i=0;i<length; i++) {
				data = XtuDoc.uploadQueue[i];
				if(data.ret==0) {
					//积分
					XtuDoc.lastJf +=2;
					html += "<tr>";
					html += "<td>"+(i+1)+"</td><td>"+data.info.fname+"</td><td class=\"text-success\">成功</td>";
					html += "<td><select name=\""+data.info.fid+"_jf\"><option value=\"0\" selected>0积分</option><option value=\"1\">1积分</option><option value=\"2\">2积分</option><option value=\"4\">4积分</option><option value=\"8\">8积分</option></select></td>";
					html += "<td><select class=\"catalog-select\" name=\""+data.info.fid+"_catalog\">"+XtuDoc.CATALOG_OPTION_STR+"</select></td>";
				} else {
					html += "<tr class=\"danger\">";
					html += "<td>"+(i+1)+"</td><td>"+data.info.fname+"</td><td class=\"text-danger\">失败</td>";
					html += "<td class=\"text-center\" colspan=\"2\">"+data.info.msg+"</td>";
				}
				html +="</tr>";
			}

			$('#modalUploadBody').hide();
			$('#modalPassBody').show();
			$('#tablePassListBody').html(html);
			XtuDoc.uploadQueue = [];
			return true;
	};

	XtuDoc.showResult = function(result){
		$('#modalUploadBody').show();
		$('#modalPassBody').hide();
		$('#showResult').html(result);
	}

	$('#file_upload').uploadify({
			'fileSizeLimit' : '30MB',
			'fileTypeExts' : '*.pptx; *.ppt; *.xlsx; *.xls; *.pdf; *.doc; *.docx',
			'buttonClass': 'm-up-btn',
			'buttonText' : '选择文件',
			formData : {DOC_SESS_ID:C_DOC_SESS_ID},
			'onUploadSuccess' : function(file, data, response){
				//alert(data);
				data = $.parseJSON(data);
				XtuDoc.uploadQueue.push(data);
				//alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
			},
			'onQueueComplete' : function(queueData) {
				XtuDoc.showJfList();
			},
			'onUploadError' : function(file, errorCode, errorMsg, errorString) {
				alert('The file ' + file.name + ' could not be uploaded: ' + errorString + '\n' + errorCode + '\n' + errorMsg);
			},
			'swf'      : '/static/swf/uploadify.swf',
			'uploader' : '/home/upload_file',
			
	});
	$('#submitJf').click(function(event) {
		/* Act on the event */
		var all = $("#jfForm .catalog-select");
		for(i=0; i<all.length; i++){
			if( $(all[i]).val()==0 ) {
				$(all[i]).focus();
				alert("还没有选择文件类型");
				return ;
			}
		}
		
		$.post('/set_file_info', {
			'data': $("#jfForm").serialize()
		}, function(data, textStatus, xhr) {
			/*optional stuff to do after success */
			if(XtuDoc.lastJf!=0) {
				XtuDoc.showResult('<span class="text-success">上传成功，获得 '+XtuDoc.lastJf+' 积分</span><br><br>');
			} else {
				XtuDoc.showResult('<span class="text-danger">全部上传失败，没有获得积分</span><br><br>');
			}
		});
	});
});
