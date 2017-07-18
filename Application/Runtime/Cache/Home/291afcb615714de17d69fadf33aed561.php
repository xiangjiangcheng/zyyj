<?php if (!defined('THINK_PATH')) exit();?><table id="Chapter_dg"></table>

<div id="Chapter_Toolbar">
	<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newChapter()">添加</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editChapter()">编辑</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyChapter()">删除</a>
	<a href='#' class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="refreshChapterList()">刷新</a>
	<br>
	<span>科目:</span>
	<input id="Ch_s_course" name="Ch_s_course" style="width:100px" class="easyui-combobox"
		url="http://localhost/zyyj/index.php/Home/Chapter/Chapter_get_course_options"
		valueField="course_id" textField="name" editable="false">
	</input>
	<span>名称:</span>
	<input id="Ch_s_name" name="Ch_s_name" style="width:100px" class="easyui-textbox">
	<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="Chapter_doSearch()">查看</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="Chapter_SearchAll()">显示全部</a>
</div>

<!-- 新建和编辑用的对话框、表单 -->
<div id="Chapter_dlg" class="easyui-dialog" style="width:380px;height:280px;padding:10px 20px"
	closed="true" buttons="#Chapter_dlg-buttons">
	<form id="Chapter_fm" method="post">
		<br>
		<div class="fitem">
			<label>所属科目:</label>
			<input class="easyui-combobox" name="course_name"
				data-options="valueField:'course_id',textField:'name',url:'http://localhost/zyyj/index.php/Home/Chapter/Chapter_get_course_options'" 
				required="true" editable="false"/>
		</div>
		<br>
		<div class="fitem">
			<label>章节名称:</label>
			<input name="name" class="easyui-validatebox" required="true"/>
		</div>
		<br>
		<div class="fitem">
			<label>章节备注:</label>
			<input name="comment" required="true"/>
		</div>
		<input type="hidden" name="chapter_id" id="chapter_id"/>
		<input type="hidden" name="Chapter_op" id="Chapter_op" value=""/>
	</form>
</div>

<div id="Chapter_del" class="easyui-dialog" closed="true" buttons="#Chapter_del-buttons" style="width:300px;height:155px">
	<label>删除章节的时候会同时删除章节下属的知识点，确定要删除该章节吗？</label>
</div>
<!--
<div id="Chapter_upload" class="easyui-dialog" closed="true" buttons="#Chapter_upload-buttons" style="width:300px;height:210px">
	<div class="fitem">
		<form id="upload_fm" enctype="multipart/form-data" method="post">
		<br>
		<div class="fiten">
			<label>模板:</label>
			<a href="http://localhost/zyyj/source/章节导入模板.xlsx"><font color="blue">下载</font></a>
		</div>
		<br>
		<div class="fitem">
			<label>所属的科目:</label>
			<input id="file_Ch_bc" class="easyui-combobox" name="course_name"
				data-options="valueField:'course_id',textField:'name',url:'http://localhost/zyyj/index.php/Home/Chapter/Chapter_get_course_options'" 
				required="true" editable="false"/>
		</div>
		<br>
		<div class="fitem">
			<input type="file" name="upfile" id="upfile">
		</div>
		</form>
	</div>
</div>
-->
<!-- 按钮组 -->
<div id="Chapter_dlg-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveChapter()">保存</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Chapter_dlg').dialog('close')">取消</a>
</div>
<div id="Chapter_del-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveChapter()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Chapter_del').dialog('close')">取消</a>
</div>
<div id="Chapter_upload-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="uploadFile()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Chapter_upload').dialog('close')">取消</a>
</div>
<script type="text/javascript">
$('#Chapter_dg').datagrid({
    url:'http://localhost/zyyj/index.php/Home/Chapter/Chapter_get_chapter',
    columns:[[
		{field:'name',title:'章节名称',width:'33%'},
		{field:'course_name',title:'所属科目',width:'33%'},
		{field:'comment',title:'章节备注',width:'33%'}
    ]],
	onLoadSuccess:function(data){
		if(data.rows=="null"){
			Chapter_SearchAll();
			$.messager.alert("提示信息", "没有符合搜索条件的章节！");
		}
	},
	rownumbers:"true",
	pagination:"true",
	pageSize:"20",
	fit:"true",
	fitColumns:"true",
	singleSelect:"true",
	toolbar:$('#Chapter_Toolbar')
});

//搜索
function Chapter_doSearch(){
	var S_course_id= $('#Ch_s_course').combobox('getValue');
	var S_name= $('#Ch_s_name').textbox('getValue');
	$('#Chapter_dg').datagrid('load',{
		S_course : S_course_id,
		S_name : S_name
	});
}

//清空搜索框
function Ch_Searchbox_clear(){
	$('#Ch_s_course').combobox('clear');
	$('#Ch_s_name').textbox('clear');
}

//显示全部章节
function Chapter_SearchAll(){
	$('#Chapter_dg').datagrid('load',{
	});
	Ch_Searchbox_clear();
}

//刷新列表
function refreshChapterList(){
	$('#Chapter_dg').datagrid('clearSelections');
	$('#Chapter_dg').datagrid('reload');
}

//新建章节
function newChapter(){
	$('#Chapter_dlg').dialog('open').dialog('setTitle','新建章节');
	$('#Chapter_fm').form('clear');
	document.getElementById("Chapter_op").value="add";
}

//编辑章节信息
function editChapter(){
	var row = $('#Chapter_dg').datagrid('getSelected');
	if (row){
		$('#Chapter_dlg').dialog('open').dialog('setTitle','编辑章节');
		$('#Chapter_fm').form('load',row);
		document.getElementById("Chapter_op").value="edit";
	}else{
		$.messager.alert("提示信息", "请先选择行再进行修改！");
	}
}

//删除章节
function destroyChapter(){
	var row = $('#Chapter_dg').datagrid('getSelected');
	if (row){
		$('#Chapter_del').dialog('open').dialog('setTitle','删除章节');
		$('#Chapter_fm').form('load',row);
		document.getElementById("Chapter_op").value="del";
	}else{
		$.messager.alert("提示信息", "请先选择行再进行修改！");
	}
}

//保存章节信息
function saveChapter(){
	$('#Chapter_fm').form('submit',{
		url: 'http://localhost/zyyj/index.php/Home/Chapter/Chapter_save',
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if(result==0 || result==false){
				$.messager.alert("提示信息", "操作失败!");
			} else if(result==2){
				$.messager.alert("提示信息", "该章节已存在！");
			} else if(result==1) {
				$.messager.alert("提示信息", "操作成功");
				$('#Chapter_dlg').dialog('close');
				$('#Chapter_del').dialog('close');
				$('#Chapter_dg').datagrid('reload');
			}
		}
	});
}

/*
//打开excel选择器
function selectExcel(){
	$('#Chapter_upload').dialog('open').dialog('setTitle','批量导入章节');
	$('#upload_fm').form('clear');
	var file_clear = document.getElementById('upfile');
	file_clear.outerHTML=file_clear.outerHTML;
}

//上传文件
function uploadFile(){
	$('#upload_fm').form('submit',{
		url: 'http://localhost/zyyj/index.php/Home/Chapter/upload_excel',
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var arr_result = result.split(" ");
			//取得result
			result=arr_result[0][1];
			//取得错误的行号
			rows=arr_result[1];
			rows=rows.replace(",","");
			rows=rows.replace('"',"");
			if(result==1){
				$.messager.alert("提示信息", "导入成功!");
				$('#Chapter_upload').dialog('close');
				$('#Chapter_dg').datagrid('reload');
			} else if(result==2){
				$.messager.alert("提示信息", "导入成功，但是行号为"+rows+"的数据导入失败，这些行的章节名称已存在或者为空。");
				$('#Chapter_upload').dialog('close');
				$('#Chapter_dg').datagrid('reload');
			} else {
				$.messager.alert("提示信息", "导入失败。");
			}
		}
	});
}
*/
</script>
</body>