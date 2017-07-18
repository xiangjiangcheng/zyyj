<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/locale/easyui-lang-zh_CN.js" ></script>
<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
<script src="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
<!-- <script type="text/javascript" src="http://localhost/zyyj//source/js/comm/easyui-extend-rcm.js" ></script> -->
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/icon.css" />
<script type="text/javascript">
	//解析路径  -- 向江城
	//页面引入外部js的时候  使用
	var Student_getAllStudent_url = '<?php echo U("Home/Student/Student_getAllStudent","","");?>';
	var Student_addStudent_url = '<?php echo U("Home/Student/Student_addStudent","","");?>';
	var Student_getStudentById_url = '<?php echo U("Home/Student/Student_getStudentById","","");?>';
	var Student_updateStudent_url = '<?php echo U("Home/Student/Student_updateStudent","","");?>';
	var Student_deleteStudent_url = '<?php echo U("Home/Student/Student_deleteStudent","","");?>';
	var Student_updatePwdStudent_url = '<?php echo U("Home/Student/Student_updatePwdStudent","","");?>';
	var Student_getClassByGradeId_url = '<?php echo U("Home/Student/Student_getClassByGradeId","","");?>';
	var Student_getClassByGradeAndMajorId_url = '<?php echo U("Home/Student/Student_getClassByGradeAndMajorId","","");?>';
	var Student_getMajorByCollegeId_url = '<?php echo U("Home/Student/Student_getMajorByCollegeId","","");?>';
	var Student_getStudentGraAndColl_url = '<?php echo U("Home/Student/Student_getStudentGraAndColl","","");?>';
	var Student_import_url = '<?php echo U("Home/Student/Student_import","","");?>';
	var Student_getAllSearchData_url = '<?php echo U("Home/Student/Student_getAllSearchData","","");?>';
	
	
</script>


</head>
<body>
<style type="text/css">
	body{
		background:url(http://localhost/zyyj/source/images/bg.jpg) no-repeat 0 0;
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
				<img style="width:90px;height:110px;float:left" src="http://localhost/zyyj/source/images/logo.gif">
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
		var site="http://localhost/zyyj/";
		
		if (!$('#manager').validatebox('isValid')) { 
			$('#manager').focus();
		} else if (!$('#password').validatebox('isValid')) {
			$('#password').focus();
		} else {
			$.ajax({ 
				url : "http://localhost/zyyj/index.php/Home/Login/Login_login", 
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
						location.href = 'http://localhost/zyyj/index.php/Home/Login/Login_menu'; 
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
            var site="http://localhost/zyyj/";
		
		if (!$('#manager').validatebox('isValid')) { 
			$('#manager').focus();
		} else if (!$('#password').validatebox('isValid')) {
			$('#password').focus();
		} else {
			$.ajax({ 
				url : "http://localhost/zyyj/index.php/Home/Login/Login_login", 
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
						location.href = 'http://localhost/zyyj/index.php/Home/Login/Login_menu'; 
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