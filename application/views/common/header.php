<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>湘大文库</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/static/css/flatly/bootstrap.css" media="screen">
		<link rel="stylesheet" href="/static/css/doc.style.css?v=1">
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a href="/" class="navbar-brand">湘大文库</a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-main">
					<ul class="nav navbar-nav">
						<li>
							<a href="/">首页</a>
						</li>
						<li>
							<a href="/list">文库</a>
						</li>
						<li>
							<a href="/home">个人中心</a>
						</li>
					</ul>
					
					<form class="navbar-form navbar-right">
		      			<input class="form-control col-md-4" id="loginUser" placeholder="学号/工号/邮箱" type="text">
		      			<input class="form-control col-md-4" id="loginPass" placeholder="密码" type="password">
		      			<button class="btn btn-warning" >登录</button><button class="btn btn-danger" >注册</button>
		    		</form>
					
		    		<!--登录后显示
					<ul class="nav navbar-nav navbar-right">
						<li><a href="http://builtwithbootstrap.com/">杜草</a></li>
						<li><a href="https://wrapbootstrap.com/?ref=bsw">操作</a></li>
					</ul>
					-->
				</div><!-- navbar-header -->
			</div><!-- container -->
		</div><!-- navbar -->
		<div class="container">