<?php if (!defined('THINK_PATH')) exit();?></script>
<body>
	<table id="ConditionList">
		
	</table>
	<div id="ConditionListTool" style="padding:0px">
	<form id="practiceform" class='chenform'>
			<span style="margin-left:10px">
			<span>学号:</span>
			<input type="text" name="stu_id" notcombobox='true' class="easyui-numberbox" style="width:110px;">
		</span>
		<span style="margin-left:10px">
			<span>姓名：</span>
			<input type="text" name="name"  notcombobox='true' class="textbox" style="width:110px;">
		</span>
		<span style="margin-left:20px">
		<div style="display:none">
			<span>学院:</span>
			<input id="p_college" name="college_id" style="width:100px" class="easyui-combobox"
			url="http://localhost/zyyj/index.php/Home/Tao/Tao_GetCollege"
			valueField="college_id" textField="name" editable='false'>
			</input>
		</div>
			
			<span>专业:</span>
			<input  name="major_id" style="width:100px" class="easyui-combobox"
			url="http://localhost/zyyj/index.php/Home/Tao/Tao_GetMajor"
			valueField="major_id" textField="name" editable='false'>
			</input>
			<span>年级:</span>
			<input name="grade_id" style="width:100px" class="easyui-combobox"
			url="http://localhost/zyyj/index.php/Home/Tao/Tao_GetGrade"
			valueField="grade_id" textField="name" editable='false'>
			</input>
			<span>班级:</span>
			<input name="class_id" style="width:100px" class="easyui-combobox"
			url="http://localhost/zyyj/index.php/Home/Tao/Tao_GetClass"
			valueField="class_id" textField="name" editable='false'>
			</input>
			<span>科目:</span>
			<input name="course_id" style="width:100px" class="easyui-combobox"
			url="http://localhost/zyyj/index.php/Home/Tao/Tao_GetCourse"
			valueField="course_id" textField="name" editable='false'>
			</input>
			<a  class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="paramsearch_practice();">查询</a>
			<a  id='reset-btn' class="easyui-linkbutton" iconCls="icon-search" plain="true">查询全部</a>
		</span>
	</form>
	</div>
	<script type="text/javascript">
		var url = "<?php echo U('Condition_getdata');?>";
		var paramsearch_practice = function(){
			$('#ConditionList').datagrid('load',$('#practiceform').serializeJson(2));
		}
		var searchall_practice = function(){
			$('input[name]').val('');
			$('#ConditionList').loadgrid(2,'学生练习情况',$('#ConditionListTool'));
		}
	</script>
	<script type="text/javascript" src='http://localhost/zyyj/source/js/Condition.js'></script>
	<script type="text/javascript">
	$(function(){
			var reload = function(){
				$('input[editable]').combobox('setValue','');
			}
			searchall_practice();
			$('#reset-btn').click(function(event) {
				reload();
				searchall_practice();
			});
		})
	</script>
</body>