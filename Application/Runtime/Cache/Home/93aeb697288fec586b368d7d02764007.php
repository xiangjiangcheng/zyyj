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
<script type="text/javascript" src='/zyyj/source/js/ExtendValidate.js'></script>
	<script type="text/javascript">
		$('#myself-form').form({
			onSubmit:function(){
				return $('#myself-form').form('validate');
			},
			success:function(data){
				data = $.parseJSON(data);
				if(data.success == 1){
					$.messager.alert('提示','修改成功');
					$('.logout').html('您好, '+data.realname+' | <a href="<?php echo U("Login/Login_loginout");?>">退出</a>');	
				}else{
					$.messager.alert('提示','修改失败');
				}
			}
		});
		$(function(){
			$("option[value='<?php echo ($data['gender']); ?>'").attr('selected', 'true');
		})
	</script>
	<div class="form-box">
		<h3>个人信息</h3>
		<form id="myself-form" action="<?php echo U('Myself_self_handle');?>" method="post">
			<input type="text" id="idinput" name="id" value="<?php echo ($data['user_id']); ?>" />
			<div class="input-box">
				<div class="prebox">账号</div>
				<input class="easyui-validatebox textbox" type="text" name="name" value="<?php echo ($data['name']); ?>" disabled="true" />
			</div>
			<div class="input-box">
				<div class="prebox">姓名</div>
				<input class="easyui-validatebox textbox" type="text" name="realname" required="true" value="<?php echo ($data['realname']); ?>"/>
			</div>
			<div class="input-box">
				<div class="prebox">性别</div>
				<select class="easyui-validatebox textbox" name="gender" data-options="required:true">
					<option value=1 >男</option>
					<option value=0 >女</option>
				</select>
			</div>
			<div class="input-box">
				<div class="prebox">部门</div>
				<input class="easyui-validatebox textbox" type="text" name="department" value="<?php echo ($data['department']); ?>" disabled="true" />
			</div>
			<div class="input-box">
				<div class="prebox">邮箱</div>
				<input class="easyui-validatebox textbox" type="text" name="email" required='true'  validType='email' value="<?php echo ($data['email']); ?>"/>
			</div>
			<div class="input-box">
				<div class="prebox">电话</div>
				<input class="easyui-validatebox textbox" type="text" name="phone" data-options="required:true,validType:'mobile'" value="<?php echo ($data['phone']); ?>"/>
			</div>
			<button type="submit" class="easyui-linkbutton">提交</button>
		</form>
	</div>
	<link rel="stylesheet" href="/zyyj/source/css/Myself.css">
</body>