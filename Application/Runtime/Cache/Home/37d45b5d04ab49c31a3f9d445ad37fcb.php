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
<body class="easyui-layout">
<table id="Cdg" class="easyui-datagrid" style="width:100%;height:100%"
	url='http://localhost/zyyj/index.php/Home/College/College_get_colleges'
	toolbar="#Ctoolbar"
	rownumbers="true"
	fitColumns="true" 
	fit="true"
	singleSelect="true"
	pagination="true"
	pageSize="20"
	remoteSort="false">
	<thead>
		<tr>
			<th field="name" width="20">学院名称</th>
			<th field="comment" width="50">学院备注</th>
		</tr>
	</thead>
</table>
<div id="Ctoolbar">
	<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newCollege()">添加</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCollege()">编辑</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyCollege()">删除</a>
	<a href='#' class="easyui-linkbutton" iconCls = "icon-reload" plain="true" onclick="refreshCollegeList()">刷新</a>
</div>
<!-- 新建学院和编辑学院用的表单 -->
<div id="Cdlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
closed="true" buttons="#Cdlg-buttons">
	<div class="ftitle">填写学院信息</div>
	<form id="Cfm" method="post">
		<div class="fitem">
			<label>学院名称:</label>
			<input name="name" class="easyui-validatebox" required="true"/>
		</div>
		<div class="fitem">
			<label>学院备注:</label>
			<input name="comment" required="true"/>
		</div>
		<input type="hidden" name="college_id" id="college_id"/>
		<input type="hidden" name="Cop" id="Cop" value=""/>
	</form>
</div>
<!-- 删除学院用的表单 -->
<div id="Cdel" class="easyui-dialog" closed="true" buttons="#Cdel-buttons" style="width:300px;height:155px">
	<label>删除学院的同时会删除该学院下的所有专业以及挂靠班级，您确定要删除该学院吗？</label>
</div>

<div id="Cdel-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCollege()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Cdel').dialog('close')">取消</a>
</div>
<div id="Cdlg-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCollege()">保存</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Cdlg').dialog('close')">取消</a>
</div>

<script type="text/javascript">
	//新建学院
	function newCollege(){
		$('#Cdlg').dialog('open').dialog('setTitle','添加学院');
		$('#Cfm').form('clear');
		document.getElementById("Cop").value="add";
	}
	//编辑学院信息
	function editCollege(){
		var row = $('#Cdg').datagrid('getSelected');
		if (row){
			$('#Cdlg').dialog('open').dialog('setTitle','编辑学院信息');
			$('#Cfm').form('load',row);
			document.getElementById("Cop").value="edit";
		}else{
			$.messager.alert("提示信息", "请先选择行再进行修改！");
		}
	}
	//保存
	function saveCollege(){
		$('#Cfm').form('submit',{
			url: 'http://localhost/zyyj/index.php/Home/College/College_save_college',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				var result = eval('('+result+')');
				if (result==0 || result==false){
					$.messager.alert("提示信息", "操作失败！");
				} else if(result==2){
					$.messager.alert("提示信息", "该学院已存在！");
				} else {
					$('#Cdlg').dialog('close');
					$('#Cdel').dialog('close');
					$('#Cdg').datagrid('reload');// reload the user data
					$.messager.alert("提示信息", "操作成功");
				}
			}
		});
	}
	//删除学院
	function destroyCollege(){
		var row = $('#Cdg').datagrid('getSelected');
		if (row){
			$('#Cdel').dialog('open').dialog('setTitle','删除学院');
			$('#Cfm').form('load',row);
			document.getElementById("Cop").value="del";
		}else{
			$.messager.alert("提示信息", "请先选择行再进行修改！");
		}
	}

	//刷新列表
	function refreshCollegeList(){
		$('#Cdg').datagrid('clearSelections');
		$('#Cdg').datagrid('reload');
	}
</script>
</body>