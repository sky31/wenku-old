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
			}else if(data.ret==2){
				alert("密码错误");
			}else{
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

	$("#lpLoginBtn").click(function(event) {
		var reg = /^20[0-9]{8}$|^20[0-9]{10}$|^[0-9]{8}$/;
		if($("#lpInputNums").val()=="") {
			$("#lpInputNums").focus();
			$("#lpAccessAlert").html("帐号不能为空");
			return false;
		} else if(!reg.test($("#lpInputNums").val())) {
			$("#lpInputNums").focus();
			$("#lpAccessAlert").html("学号/工号格式错误");
			return false;
		}else if( $("#lpInputPass").val()=="" ){
			$("#lpInputPass").focus();
			$("#lpAccessAlert").html("密码不能为空");
			return false;
		}

		$.post('/login/post', {
			num : $("#lpInputNums").val(),
			pass : $("#lpInputPass").val(),
		}, function(data) {
			if(data.ret==0) {
				window.setTimeout('location.href="/home";', 500);
			} else if(data.ret==2){
				$("#lpAccessAlert").html("密码错误");
			}else{ //返回1，未接入系统帐号密码都错
				$("#m-access-info").html('<p class="text-center"><img src="/static/image/loading.gif"></p><p>这是你第一次登录文库系统，正在为你接入系统，需等待10秒左右……</p>');
				$('#linkInModal').modal('show');
				// 访问接入
				$.post('/access', {
					num : $("#lpInputNums").val(),
					pass : $("#lpInputPass").val(),
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
			$("#inputEmail").focus().val();
			$("#accessAlert").html("邮箱格式错误");
			return false;
		}

		$.post('/register', {
			'email': $("#inputEmail").val(),
			'nickname': $("#inputNickname").val(),
			'face':  $('input[name="faceInput"]:checked').val()
		}, function(data, textStatus, xhr) {
			if(data.ret==0){
				alert('注册成功');
				window.setTimeout("location.href='/home'", 1);
			} else {
				alert(data.info);
			}
		});

		return false;
	});
	$("#openUploadModal").click(function(event) {
		/* Act on the event */
		if(DOC_IS_LOGIN) {
			$("#uploadModal").modal("show");
			// 监听上传模态框是否关闭
			$('#uploadModal').on('hidden.bs.modal', function (e) {
				if(XtuDoc.isUpload) {
					window.setTimeout("location.href='/home';", 1);
				}
			});
		} else {
			$("#loginUser").focus();
			alert("请先登录");
		}
		return false;
	});

	window.XtuDoc = {};
	XtuDoc.uploadQueue = [];
	XtuDoc.lastJf      = 0;
	XtuDoc.isUpload    = false;
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
					XtuDoc.isUpload = true;
					//积分
					XtuDoc.lastJf +=2;
					html += "<tr>";
					html += "<td rowspan=\"2\">"+(i+1)+"</td><td style=\"max-width: 236px;\" rowspan=\"2\">"+data.info.fname+"</td><td class=\"text-success\">成功</td>";
					html += "<td><select name=\""+data.info.fid+"_jf\"><option value=\"0\" selected>0积分</option><option value=\"1\">1积分</option><option value=\"2\">2积分</option><option value=\"4\">4积分</option><option value=\"8\">8积分</option></select></td>";
					html += "<td><select class=\"catalog-select\" name=\""+data.info.fid+"_catalog\">"+XtuDoc.CATALOG_OPTION_STR+"</select></td>";
					html += "<tr><td colspan=\"3\"><textarea placeholder=\"输入文档简介...\" class=\"form-control\" name=\""+data.info.fid+"_intro\"></textarea></td></tr>";
				} else {
					html += "<tr class=\"danger\">";
					html += "<td >"+(i+1)+"</td><td >"+data.info.fname+"</td><td>失败</td>";
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


	XtuDoc.addCollectionFile = function(btn, fid) {
		if(DOC_IS_LOGIN){
			$.getJSON('/home/add_collection_file/'+fid, {}, function(json, textStatus) {
				/*optional stuff to do after success */
				if(json.ret==0) {
					$(btn).attr('class', 'btn btn-default btn-sm pull-right');
					$(btn).attr('disabled', 'disabled');
					$(btn).html('已收藏');
				}
				alert(json.info);
			});
		} else {
			alert("请先登录");
		}
		return false;
	}

	XtuDoc.sizeCalc = function(size) {
		var arr = ['Byte', 'KB', 'MB', 'GB'];
		var i = 0;
		size = parseInt(size);
		while(size>1024) {
			size = size/1024.00;
			i++;
		}
		return size.toFixed(1) + arr[i];
	}

	XtuDoc.downFileModal = function (fid) {
		if(DOC_IS_LOGIN){
			$.getJSON('/home/down_make_sure/'+fid, {}, function(json, textStatus) {
				if(json.ret==0) {
					$("#downModal .fileName").html(json.info.fileName);
					$("#downModal .fileSize").html( XtuDoc.sizeCalc(json.info.fileSize) );
					$("#downModal .fileJF").html(json.info.fileJF);
					$("#downModal .userJF").html(json.info.userJF);
					if(json.info.haveDown){
						$("#downModal .haveDown").html("该文件已经下载过，再次下载不扣除积分");
						$("#downModal .haveDown").show();
					}
					if(json.info.userJF<json.info.fileJF) {
						$("#downFileBtn").html('积分不足');
						$("#downFileBtn").attr('class', 'btn btn-default');
						$("#downFileBtn").attr("disabled", "disabled");
					} else {
						$("#downFileBtn").attr('class', 'btn btn-success');
						$("#downFileBtn").html('立即下载');
						$("#downFileBtn").removeAttr('disabled')
						$("#downFileBtn").click(function(event) {
							$("#downFileBtn").attr("disabled", "disabled");
							window.open('/down_file/'+fid);
							$("#downFileBtn").html('已下载');
							$("#downFileBtn").attr('class', 'btn btn-danger');
						});
					}
					$("#downModal").modal('show');
				} else {
					alert(json.info);
				}
			});
		} else {
			alert("请先登录");
		}
		return false;
	}

	if(typeof C_DOC_SESS_ID=='undefined') {
		C_DOC_SESS_ID = "1";
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
		all = $("textarea");
		for(i=0; i<all.length; i++){
			if( $(all[i]).val()=="" ) {
				$(all[i]).focus();
				alert("文件简介不能为空");
				return ;
			} else if($(all[i]).val().length>120) {
				$(all[i]).focus();
				alert("文件简介不能超过120个字符");
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

	$("#inputSearch").keyup(function(event) {
		/* Act on the event */
		if(event.keyCode==13) {
			$("#mainSearchBtn").click();
		}
	});

	$("#loginPass").keyup(function(event) {
		/* Act on the event */
		if(event.keyCode==13) {
			$("#topLogin").click();
		}
	});

	$("#mainSearchBtn").click(function(event) {
		var isStr = $("#inputSearch").val().replace(/(^\s*)|(\s*$)/g,"")
		if(isStr=="") {
			$("#inputSearch").focus();
			return false;
		}else if(isStr.length<2) {
			alert("搜索词不能小于两个字符");
			return false;
		}
		var href = "/search/"+$('input[name="optionsRadios"]:checked').val()+"/"+isStr;
		//alert(href);
		location.href=href;
		return false;
	});

	$(".setFileInfoBtn").click(function(event) {
		var fid = $(this).attr("target-data");
		$("#alertDiv").html(" ");
		$.getJSON('/home/get_file_info/'+fid,{
		}, function(json, textStatus) {
				/*optional stuff to do after success */
			if(json.ret==0) {
				var jf_array = [0, 1, 2, 4, 8];
				$("#smInputFileName").val(json.info.filename);
				html = "<select name=\""+fid+"_jf\" class=\"form-control\" id=\"smInputJF\">"
				for(var i in jf_array){
					html+="<option value=\""+jf_array[i]+"\" "
					if(i==json.info.jf) {
						html+="selected "
					}
					html+=">" + jf_array[i] + "积分</option>";
				}
				html +="</select>";
				$("#smInputJFDiv").html(html);
				html = "<select name=\""+fid+"_catalog\" class=\"form-control\" id=\"smInputCatalog\">";
				if(json.info.catalog=="") {
					html+="<option value=\"0\" selected=\"selected\">请选择...</a>"
				}
				for(var j in XtuDoc.catalogArray) {
					html+="<option value=\""+j+"\" "
					if(j==json.info.catalog) {
						html+="selected=\"selected\" "
					}
					html+=">" + XtuDoc.catalogArray[j] + "</option>";
				}
				html +="</select>";
				$("#smInputCatalogDiv").html(html);
				$("#smInputIntroDiv").html('<textarea id="smInputIntro" class="form-control" name="'+fid+'_intro" placeholder="输入文档简介...">'+json.info.intro+'</textarea>');
				$("#smUpBtnDiv").html("<button id=\"smUpBtn\"class=\"btn btn-success\">提交</button>");
				$("#smUpBtn").click(function(event) {
					if($("#smInputCatalog").val()==0){
						$("#smInputCatalog").focus();
						$("#smBtnAlert").html('<span class="m-l text-danger">没有设置分类</div>');
						return false;
					}
					if($("#smInputIntro").val()==""){
						$("#smInputIntro").focus();
						$("#smBtnAlert").html('<span class="m-l text-danger">简介不能为空</div>');
						return false;
					} else if($("#smInputIntro").val().length>120){
						$("#smInputIntro").focus();
						$("#smBtnAlert").html('<span class="m-l text-danger">简介不能超过120个字符</div>');
						return false;
					}
					$.post('/set_file_info', {
						'data': $("#smForm").serialize()
					}, function(data, textStatus, xhr) {
						/*optional stuff to do after success */
						$("#smBtnAlert").html('<span class="m-l text-success">设置成功</div>');
					});

					return false;
				});

				$("#setFileInfoModal").modal("show");
			}else {
				alert(json.info.msg);
			}
		});
		
		return false;
	});
});
