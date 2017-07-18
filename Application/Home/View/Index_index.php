<include file="./Application/Home/View/Header.php" />
</head>
<body>
<style type="text/css">
	body{
		background:url(__SITE__source/images/bg.jpg) no-repeat 0 0;
		overflow-x: hidden;
		overflow-y: hidden;
		height: 100%;
	}
	
	#login { 
		
	}
	p {
		height:22px;
		line-height:22px; 
		padding:4px 0 0 35px;
	}
	.textbox { 
		height:22px; 
		padding:0 2px;
	}

	.easyui-linkbutton { 
		padding:0 10px;
	}
	#btn { 
		text-align:center;
		padding:10px 10px;
	}
	.login{
		margin: 0 auto;
		width: 100%;
		padding:6px 0 0 0;
		position: absolute;
	}
	.loginbox{
		position: relative;
		width:380px;
		height:180px;
		position:relative;
		margin:0 auto;
		margin-top: 200px;
	}
</style>
<div class="login">
	<div class="loginbox">
		<div id="p" class="easyui-panel" align="center" title="后台登陆">
			<div id="login" style="float:left">
				<img style="width:90px;height:110px;float:left" src="__SITE__source/images/logo.gif">
				<p style="float:left">管理员帐号：<input type="text" id="manager" class="textbox"></p>
				<p style="float:left">管理员密码：<input type="password" id="password" class="textbox"></p>
			</div>
			<div id="btn">
				<input type="button" value="登录" name="btn" class="easyui-linkbutton" />
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	
	//管理员帐号
	$('#manager').validatebox({ 
		required : true, 
		missingMessage : '请输入管理员帐号',
		invalidMessage : '管理员帐号不得为空',
	});
	//管理员密码
	$('#password').validatebox({ 
		required : true, 
		validType : 'length[6,30]', 
		missingMessage : '请输入管理员密码',
		invalidMessage : '管理员密码在6-30位',
	});
	//加载页面时判断
	if (!$('#manager').validatebox('isValid')) { 
		$('#manager').focus();
	} else if (!$('#password').validatebox('isValid')) {
		$('#password').focus();
	}
	//登录按钮
	$('#btn input').click(function () { 
		var site="__SITE__";
		
		if (!$('#manager').validatebox('isValid')) { 
			$('#manager').focus();
		} else if (!$('#password').validatebox('isValid')) {
			$('#password').focus();
		} else {
			$.ajax({ 
				url : "__SITE__index.php/Home/Login/Login_login", 
				type : 'POST',
				data : {
					manager : $('#manager').val(), 
					password : $('#password').val(),
				},
				beforeSend : function () { 
					$.messager.progress({
						text : '正在尝试登录...',
					}); 
				},
				success : function(data, response, status){
					$.messager.progress('close'); 
					if (data.success==1) {
						//alert("登录成功");
						location.href = '__SITE__index.php/Home/Login/Login_menu'; 
					} else {
						$.messager.alert('登录失败！','用户名或密码错误！','warning',
						function () {
							$('#password').select();
						});
					}
				}
			});

		}
	});
	
</script>
 <script type="text/javascript">
 	document.onkeydown=function(event){ 
        var e = event || window.event || arguments.callee.caller.arguments[0];   
        if(e && e.keyCode==13){ // enter 键 
            var site="__SITE__";
		
		if (!$('#manager').validatebox('isValid')) { 
			$('#manager').focus();
		} else if (!$('#password').validatebox('isValid')) {
			$('#password').focus();
		} else {
			$.ajax({ 
				url : "__SITE__index.php/Home/Login/Login_login", 
				type : 'POST',
				data : {
					manager : $('#manager').val(), 
					password : $('#password').val(),
				},
				beforeSend : function () { 
					$.messager.progress({
						text : '正在尝试登录...',
					}); 
				},
				success : function(data, response, status){
					$.messager.progress('close'); 
					if (data.success==1) {
						//alert("登录成功");
						location.href = '__SITE__index.php/Home/Login/Login_menu'; 
					} else {
						$.messager.alert('登录失败！','用户名或密码错误！','warning',
						function () {
							$('#password').select();
						});
					}
				}
			});

		}      
        } 
    };
</script>
</body>
</html>

