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
		$("#uploadModal").modal("show");
	});
});
