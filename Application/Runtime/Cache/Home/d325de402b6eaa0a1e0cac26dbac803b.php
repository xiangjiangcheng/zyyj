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



<head/>

<body>
	<div class="form-box">
		<h3>密码修改</h3>
		<form id="updatepwd-form" action="<?php echo U('Myself_password_handle');?>" method="post">
			<input type="text" id="idinput" name="user_id" value="<?php echo ($data['user_id']); ?>" />
			<div class="input-box">
				<div class="prebox">姓名</div>
				<input class="easyui-validatebox textbox" type="text" name="realname" data-options="required:true" value="<?php echo ($data['realname']); ?>" disabled="true"/>
			</div>
			<div class="input-box">
				<div class="prebox">旧密码</div>
				<input class="easyui-validatebox textbox" id="oldpwd" type="password" name="password" data-options="required:true" validType="length[6,30]"/>
			</div>	
			<div class="input-box" >
				<div class="prebox">新密码</div>
				<input class="easyui-validatebox textbox" type="password" id='pwd' name="newpwd" data-options="required:true,validType:['length[6,30]','noequals[\'#oldpwd\']']"/>
			</div>
			<div class="input-box">
				<div class="prebox">确认密码</div>
				<input class="easyui-validatebox textbox" type="password" id='rpwd' name="renewpwd" data-options="required:true,validType:['length[6,30]','equals[\'#pwd\']']"/>
			</div>	
			<button type="submit" class="easyui-linkbutton">提交</button>	
		</form>
	</div>	
</div>
<link rel="stylesheet" href="/zyyj/source/css/Myself.css">
<script type="text/javascript" src='/zyyj/source/js/ExtendValidate.js'></script>
<script type="text/javascript">
	//表单提交
	$('#updatepwd-form').form({
		onSubmit:function(){
				return $('#updatepwd-form').form('validate');
			},
		success:function(data){
			data = $.parseJSON(data);
			if (data.status == 1) {
				$.messager.alert('提示','修改成功');
			}else{
				if (data.status == 0) {
					$.messager.alert('提示','密码错误!');
				}else{
					$.messager.alert('提示','新旧密码不能相同!');
				}
			}
		}
	});
</script>
</body>
</html>