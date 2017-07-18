<?php if (!defined('THINK_PATH')) exit();?><body>
	<table id="examgrid">
		
	</table>
	<div id="examtool" style="padding:0px">
	<form id="examform" class="chenform">
		<span style="margin-left:10px">
			<span>学号:</span>
			<input type="text" name="stu_id"  class="easyui-numberbox" style="width:110px;">
		</span>
		<span style="margin-left:10px">
			<span>姓名：</span>
			<input type="text" name="name"   class="textbox" style="width:110px;">
		</span>
		<span style="margin-left:10px">
		<div style="display:none">
			<span>学院:</span>
			<input  name="college_id" style="width:100px" class="easyui-combobox"
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
			<a  class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="paramsearch();">查询</a>
			<a  id='reset-btn-exam' class="easyui-linkbutton" iconCls="icon-search" plain="true">查询全部</a>
		</span>
	</form>
		
	</div>
	<script type="text/javascript">
		var url = "<?php echo U('Condition_getdata');?>";
	</script>
	<script type="text/javascript" src='http://localhost/zyyj/source/js/Condition.js'></script>
	<script type="text/javascript">
		var paramsearch = function(){
			$('#examgrid').datagrid('load',$('#examform').serializeJson(1));
		}
		var searchall = function(){
			$('#examgrid').loadgrid(1,'学生考试情况',$('#examtool'));
		}
		$(function(){
			var reload = function(){
				$('input[editable]').combobox('setValue','');
			}
			searchall();
			$('#reset-btn-exam').click(function(event) {
				reload();
				searchall();
			});
		})
	</script>
</body>