<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/demo/demo.css" />
<script type="text/javascript" src="http://localhost/zyyj/source/js/student_list.js"></script>
<!-- url='<?php echo U("Home/Student/Student_getAllStudent2","","");?>' -->
<!-- url="http://localhost/zyyj/index.php/Home/Student/Student_getAllStudent2" -->
<style type="text/css">
	.sitem{
		margin-left: 10px;
	}
</style>
<!-- 存放后台传过来的学院id -->
<input id="college_id" name="college_id" value=<?php echo ($data["college_id"]); ?> type="hidden"> 
<table id="student" title="学生管理" iconCls="icon-save">

</table>
<div id="student_tool" style="padding:5px;">
	<div style="margin-bottom:5px;">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="student_tool.add();">添加</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="student_tool.edit();">修改</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="student_tool.remove();">删除</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="student_tool.updatePwd();">重置密码</a>
		<!--  <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" id="save" onclick="student_tool.reload();">刷新</a>-->
		<a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" id="redo" onclick="student_tool.redo();">取消选中</a>	
		<a href="#" class="easyui-linkbutton" iconCls="icon-large-shapes" plain="true" id="toimport" onclick="student_tool.import();">导入</a>
		<!-- <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" id="save" onclick="student_tool.reload();">导出</a> -->	
	</div>
	<!--<div style="padding:0 0 0 7px;color:#333;">
		学生姓名：<input id="name_find" name="name_find" style="width:110px">
		学号：<input id="account_find" name="account_find" style="width:130px">
		性别：<select class="easyui-combobox" name="gender_find" id="gender_find" style="width:60px">
				<option value="-1" >不选</option>
				<option value="0" >女</option>
				<option value="1" >男</option>
			</select>
		学院：<input id="college_id_find" name="college_id_find" style="width: 120px;">
		专业：<input id="major_id_find" name="major_id_find" style="width: 140px;">
		年级：<input id="grade_id_find" name="grade_id_find" style="width: 80px;">
		班级：<input id="class_id_find" name="class_id_find" style="width: 100px;">
		<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="student_tool.search();">查询</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="student_tool.reload();">查询全部</a>
	</div>-->
	<div style="padding:0 0 0 7px;color:#333;">
			<span class="">
				<label>姓名:</label>
				<input id="name_find" name="name_find" style="width:120px">
			</span>
			<span class="sitem">
				<label>学号:</label>
				<input id="account_find" name="account_find" style="width:120px">
			</span>
			<span class="sitem">
				<label>性别:</label>
				<select class="easyui-combobox" name="gender_find" id="gender_find" style="width:120px">
					<option value="-1" >不选</option>
					<option value="0" >女</option>
					<option value="1" >男</option>
				</select>
			</span>
			
	</div >
	<div style="padding:0 0 0 7px;color:#333;">
			<span class="" id="college_id_find_span">
				<label>学院:</label>
				<input id="college_id_find" name="college_id_find" style="width: 120px;">
			</span>
			<span class="sitem" id="major_id_find_span">
				<label>专业:</label>
				<input id="major_id_find" name="major_id_find" style="width: 120px;">
			</span>
			<span class="sitem">
				<label>年级:</label>
				<input id="grade_id_find" name='grade_id_find'   style="width:120px"> 
			</span>
			<span class="sitem">
				<label>班级:</label>
				<input id="class_id_find" name="class_id_find" style="width: 120px;">
			</span>
			<span style="margin-left:5px">
				<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="student_tool.search();">查询</a>
			</span>
			<span style="margin-left:5px">
				<a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="student_tool.reload();">查询全部</a>
			</span>
	</div >
</div>
<!-- 添加表单 -->
<form id="student_add" style="margin:0;padding:5px 0 0 25px;color:#333;">
	<p>姓名：<input type="text" name="name" class="textbox" style="width:160px;"></p>
	<p>学号：<input type="text" name="account" class="textbox" style="width:160px;"></p>
	<p>邮箱：<input type="text" name="email" class="textbox" style="width:160px;"></p>
	<p>电话：<input type="text" name="phone" class="textbox" style="width:160px;"></p>
	<p>性别：<input id="gender_add" name="gender_add" style="width: 160px;" ></p>
	<p id="college_id_add_p">学院：<input id="college_id_add" name="college_id_add" style="width: 160px;" ></p>
	<p>专业：<input id="major_id_add" name="major_id_add" style="width: 160px;" >
	<p>年级：<input id="grade_id_add" name="grade_id_add" style="width: 160px;" >
	<p>班级：<input id="class_id_add" name="class_id_add" style="width: 160px;" >
			
</form>
<!-- 编辑表单 -->
<form id="student_edit" style="margin:0;padding:5px 0 0 25px;color:#333;">
	<input type="hidden" id="stu_id" name="stu_id" class="textbox" style="width:160px;"> 
	<p>姓名：<input type="text" name="name_edit" class="textbox" style="width:160px;"></p>
	<p>学号：<input type="text" name="account_edit" class="textbox" disabled="true" style="width:160px;"><label style="color: red;font-size: 12px;">*学号不可修改</label></p>
	<p>邮箱：<input type="text" name="email_edit" class="textbox" style="width:160px;"></p>
	<p>电话：<input type="text" name="phone_edit" class="textbox" style="width:160px;"></p>
	<p>性别：<input id="gender_edit" name="gender_edit" style="width: 160px;" ></p>
	<p id="college_id_edit_p">学院：<input id="college_id_edit" name="college_id_edit" style="width: 160px;" ></p>
	<p>专业：<input id="major_id_edit" name="major_id_edit" style="width: 160px;" >
	<p>年级：<input id="grade_id_edit" name="major_id_edit" style="width: 160px;" >
	<p>班级：<input id="class_id_edit" name="class_id_edit" style="width: 160px;" >
</form>

<!-- 导入表单 -->
<form id="student_import" name="student_import" enctype="multipart/form-data" method="post" style="margin:0;padding:5px 0 0 25px;color:#333;">
	<input id="college_id_panduanimport" name="college_pandhuanimport" value=<?php echo ($data["college_id"]); ?> type="hidden"> 
	<p>模板：<a href="http://localhost/zyyj/source/学生导入模板.xlsx"><font color="blue">点击下载</font></a></p>
	<p>学院：<input id="college_id_import" name="college_id_import" style="width: 160px;" ></p>
	<p>专业：<input id="major_id_import" name="major_id_import" style="width: 160px;" >
	<p>年级：<input id="grade_id_import" name="grade_id_import" style="width: 160px;" >
	<p>班级：<input id="class_id_import" name="class_id_import" style="width: 160px;" >
	<p>导入文件：<input type="file" name="import" id="import"/></p>
</form>