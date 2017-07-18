<?php if (!defined('THINK_PATH')) exit();?><table id="Course_dg" class="easyui-treegrid" style="width:100%;height:100%"
		url="http://localhost/zyyj/index.php/Home/Course/Course_get_Courses"
		toolbar="#Course_toolbar"
		rownumbers="true"
		showFooter="true"
		fit="true"
		idField="course_id"
		treeField="name">
	<thead frozen="true">
		<tr>
			<th field="name" width="40%" align="left">科目名称</th>
		</tr>
	</thead>
	<thead>
		<tr>
			<th field="introduction" width="30%" align="left">科目说明</th>
			<th field="photo"  formatter="photo_formatter" width="14%" align="left">图片</th>
			<th field="tree_code" width="15%" align="left">级别</th>
		</tr>
	</thead>
</table>

<div id="Course_toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newCourse()">添加</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editCourse()">编辑</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyCourse()">删除</a>
		<a href='#' class="easyui-linkbutton" iconCls = "icon-reload" plain="true" onclick="refreshCourseList()">刷新</a>
</div>

<!-- 新建科目和编辑科目用的表单 -->
<div id="Course_dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
	closed="true" buttons="#Course_dlg-buttons" onclose="">
	<form id="Course_fm" method="post" enctype="multipart/form-data">
		<div class="fitem">
			<label>所属科目：</label>
			<input id="cc" class="easyui-combobox" name="_parentId"
				data-options="valueField:'parent_id',textField:'name',url:'http://localhost/zyyj/index.php/Home/Course/Course_get_parent_course'" 
				required="true" editable="false"/>
		</div>
		<div class="fitem">
			<label>科目名称：</label>
			<input name="name" class="easyui-validatebox" required="true"/>
		</div>
		<div class="fitem">
			<label>科目说明：</label>
			<input name="introduction" required="true"/>
		</div>
		<br>
		<div class="fitem">
			<label>上传图片：</label>
			<input id="imgfile" type="file" name="imgfile" src="">
		</div>
		<input type="hidden" name="course_id" id="course_id"/>
		<input type="hidden" name="tree_code" id="tree_code"/>
		<input type="hidden" name="op" id="Course_op" value=""/>
	</form>
</div>
	
<!-- 查看图片 -->
<div id="Img_dlg" class="easyui-dialog" style="width:280;height:200px;padding:10px 20px" closed="true">
	<div>
		<label>科目图片：</label>
		<img id="Course_img" src="" style="width:100px;height:100px" />
	</div>
</div>
		
<!-- 删除科目 -->
<div id="Course_del" class="easyui-dialog" closed="true" style="width:300px;height:155px" buttons="#Course_del-buttons">
		<label>删除科目会同时删除该科目以下的所有章节、知识点和题目，您确定要删除该科目吗？</label>
</div>

<!-- 按钮组 -->
<div id="Course_del-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCourse()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Course_del').dialog('close')">取消</a>
</div>
<div id="Course_dlg-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCourse()">保存</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Course_dlg').dialog('close')">取消</a>
</div>
</body>
<script type="text/javascript" language="javascript">
	//新建科目
	function newCourse(){
		$('#Course_dlg').dialog('open').dialog('setTitle','添加科目');
		$('#Course_fm').form('clear');
		document.getElementById("Course_op").value="add";
		clear_file();
	}
	
	//编辑科目
	function editCourse(){
		var row = $('#Course_dg').treegrid('getSelected');
		if (row){
			if(row._parentId==null){
				row._parentId=-1;
			}
			$('#Course_dlg').dialog('open').dialog('setTitle','编辑科目信息');
			$('#Course_fm').form('load',row);
			document.getElementById("Course_op").value="edit";
			clear_file();
		//	document.getElementById("Course_img").src=row.photo;
		//	alert(row.photo);
		}else{
			$.messager.alert("提示信息", "请先选择行再进行修改！");
		}
	}
	
	//删除科目
	function destroyCourse(){
		var row = $('#Course_dg').treegrid('getSelected');
				if (row){
					//由于删除科目只需要用到course的id，所以不需要判定也可以直接操作赋值成-1
					row._parentId=-1;
					$('#Course_del').dialog('open').dialog('setTitle','删除科目');
					$('#Course_fm').form('load',row);
					document.getElementById("Course_op").value="del";
				}else{
					$.messager.alert("提示信息", "请先选择行再进行删除！");
				}
	}
	
	//保存科目信息
	function saveCourse(){
		$('#Course_fm').form('submit', {
			url:'http://localhost/zyyj/index.php/Home/Course/Course_save_course',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(result){
				var result = eval('('+result+')');
				if(result==0){
					$.messager.alert("提示信息", "操作失败!");
				} else if(result==2){
					$.messager.alert("提示信息", "该科目已存在，操作失败!");
				} else if(result==3){
					$.messager.alert("提示信息", "父科目非法!");
				} else if(result==4){
					$.messager.alert("提示信息", "图片上传失败！");
				} else{
					$.messager.alert("提示信息", "操作成功!");
					clear_file();
					$('#Course_dlg').dialog('close');
					$('#Course_del').dialog('close');
					$('#Course_dg').treegrid('reload');
					$('#cc').combobox('reload');
				}
			}
		});
	}
	
	//刷新列表
	function refreshCourseList(){
		$('#Course_dg').datagrid('clearSelections');
		$('#Course_dg').treegrid('reload');
	}
	
	//查看图片
	function showImage(url){
		if(url!='http://localhost/zyyj/Uploads/null'){
			$('#Img_dlg').dialog('open').dialog('setTitle','查看图片');
			document.getElementById("Course_img").src=url;
		} else {
			$.messager.alert("提示信息", "图片路径不存在！");
		}
	}
	//将图片地址格式化为datagrid的formatter属性值
	function photo_formatter(val,row){
		val = "<button onclick=\"showImage('http://localhost/zyyj/Uploads/"+val+"')\">查看</button>";
		return val;
	}
	
	//清空文件选择器
	function clear_file(){
		var img_clear = document.getElementById('imgfile');
		img_clear.outerHTML=img_clear.outerHTML;
	}
</script>