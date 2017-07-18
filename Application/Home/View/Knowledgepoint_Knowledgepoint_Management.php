<table id="Kp_dg"></table>

<div id="Kp_Toolbar">
	<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newKnowledgepoint()">添加</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-large-shapes" plain="true" onclick="selectExcel()">导入</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editKnowledgepoint()">编辑</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyKnowledgepoint()">删除</a>
	<a href='#' class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="refreshKnowledgepointList()">刷新</a>
	<br>
	<span>科目:</span>
	<input id="s_course" name="s_course" style="width:100px" class="easyui-combobox"
		data-options="    
			valueField: 'course_id',
			textField: 'name',
			editable: false,
			url: '__SITE__index.php/Home/Knowledgepoint/Kp_get_course_options',    
			onSelect: function(rec){
				var url = '__SITE__index.php/Home/Knowledgepoint/Kp_get_chapter_options?c_id='+rec.course_id;
				$('#s_chapter').combobox('clear');
				$('#s_chapter').combobox('reload', url);
			}"
		>
	</input>
	<span>章节:</span>
	<input id="s_chapter" name="s_chapter" style="width:100px" class="easyui-combobox"
		data-options="
			valueField:'chapter_id',
			textField:'name',
			editable: false,
			url: '__SITE__index.php/Home/Knowledgepoint/Kp_get_null_option'"
		>
	</input>
	<span>要求:</span>
	<input id="s_require" name="s_require" style="width:100px" class="easyui-combobox"
		url="__SITE__index.php/Home/Knowledgepoint/Kp_get_require_id_options"
		valueField="require_id" textField="name" editable="false">
	</input>
	<span>名称:</span>
	<input id="s_name" name="s_name" style="width:100px" class="easyui-textbox">
	<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="Knowledgepoint_doSearch()">查看</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="Knowledgepoint_SearchAll()">显示全部</a>
</div>

<!-- 新建和编辑用的对话框、表单 -->
<div id="Kp_dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
	closed="true" buttons="#Kp_dlg-buttons">
	<form id="Kp_fm" method="post">
		<div class="fitem">
			<label>所属的科目:</label>
			<input id="bc" class="easyui-combobox" name="course_name"
				data-options="    
					valueField: 'course_id',
					textField: 'name',
					editable: false,
					url: '__SITE__index.php/Home/Knowledgepoint/Kp_get_course_options',    
					onSelect: function(rec){
						var url = '__SITE__index.php/Home/Knowledgepoint/Kp_get_chapter_options?c_id='+rec.course_id;
						$('#bch').combobox('clear');
						$('#bch').combobox('reload', url);
					},
					required: true"
				/>
		</div>
		<div class="fitem">
			<label>所属的章节:</label>
			<input id="bch" class="easyui-combobox" name="chapter_name"
				data-options="
					valueField:'chapter_id',
					textField:'name',
					editable:false,
					required:true"
				/>
		</div>
		<div class="fitem">
			<label>知识点要求:</label>
			<input id="rquire" class="easyui-combobox" name="require_name"
				data-options="valueField:'require_id',textField:'name',url:'__SITE__index.php/Home/Knowledgepoint/Kp_get_require_id_options'"
				required="true" editable="false" style="float:right"/>
		</div>
		<div class="fitem">
			<label>知识点名称:</label>
			<input name="name" class="easyui-validatebox" required="true"/>
		</div>
		<div class="fitem">
			<label>知识点备注:</label>
			<input name="comment" required="true"/>
		</div>
		<input type="hidden" name="know_id" id="know_id"/>
		<input type="hidden" name="Kp_op" id="Kp_op" value=""/>
	</form>
</div>

<div id="Kp_del" class="easyui-dialog" closed="true" buttons="#Kp_del-buttons" style="width:300px;height:155px">
	<label>删除知识点的时候会同时删除知识点下属的题目，确定要删除该知识点吗？</label>
</div>

<div id="Kp_upload" class="easyui-dialog" closed="true" buttons="#Kp_upload-buttons" style="width:330px;height:230px">
	<div class="fitem">
		<form id="upload_fm" enctype="multipart/form-data" method="post">
		<br>
		<div class="fiten">
			<label>模板:</label>
			<a href="__SITE__source/知识点导入模板.xlsx"><font color="blue">下载</font></a>
		</div>
		<br>
		<div class="fitem">
			<label>所属的科目:</label>
			<input id="file_bc" class="easyui-combobox" name="file_bc"
				data-options="
					valueField: 'course_id',
					textField: 'name',
					editable: false,
					url: '__SITE__index.php/Home/Knowledgepoint/Kp_get_course_options',    
					onSelect: function(rec){
						var url = '__SITE__index.php/Home/Knowledgepoint/Kp_get_chapter_options?c_id='+rec.course_id;
						$('#file_chapter').combobox('clear');
						$('#file_chapter').combobox('reload', url);
					},
					required: true
				"
			/>
		</div>
		<div>
			<span>所属的章节:</span>
			<input id="file_chapter" class="easyui-combobox" name="file_chapter" 
				data-options="
				valueField:'chapter_id',
				textField:'name',
				editable: false,
				required: true,
				url: '__SITE__index.php/Home/Knowledgepoint/Kp_get_null_option'"
			>
			</input>
		<div/>
		<div class="fitem">
			<label>知识点要求:</label>
			<input id="file_rquire" class="easyui-combobox" name="file_rquire"
				data-options="valueField:'require_id',textField:'name',url:'__SITE__index.php/Home/Knowledgepoint/Kp_get_require_id_options'"
				required="true" editable="false" style="float:right"/>
		</div>
		<br>
		<div class="fitem">
			<input type="file" name="upfile" id="upfile">
		</div>
		</form>
	</div>
</div>

<!-- 按钮组 -->
<div id="Kp_dlg-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveKnowledgepoint()">保存</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Kp_dlg').dialog('close')">取消</a>
</div>
<div id="Kp_del-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveKnowledgepoint()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Kp_del').dialog('close')">取消</a>
</div>
<div id="Kp_upload-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="uploadFile()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Kp_upload').dialog('close')">取消</a>
</div>
<script type="text/javascript">
$('#Kp_dg').datagrid({
    url:'__SITE__index.php/Home/Knowledgepoint/Knowledgepoint_get_knowledgepoint',
    columns:[[
		{field:'name',title:'知识点名称',width:'25%'},
		{field:'course_name',title:'所属科目',width:'15%'},
		{field:'chapter_name',title:'所属章节',width:'15%'},
		{field:'require_name',title:'要求',width:'19%'},
		{field:'comment',title:'知识点备注',width:'25%'}
    ]],
	onLoadSuccess:function(data){
		if(data.rows=="null"){
			Knowledgepoint_SearchAll();
			$.messager.alert("提示信息", "没有符合搜索条件的知识点！");
		}
	},
	rownumbers:"true",
	pagination:"true",
	pageSize:"20",
	fit:"true",
	fitColumns:"true",
	singleSelect:"true",
	toolbar:$('#Kp_Toolbar')
});

//搜索
function Knowledgepoint_doSearch(){
	var S_course_id= $('#s_course').combobox('getValue');
	var S_require_id= $('#s_require').combobox('getValue');
	var S_chapter_id= $('#s_chapter').combobox('getValue');
	var S_name= $('#s_name').textbox('getValue');
	$('#Kp_dg').datagrid('load',{
		S_course : S_course_id,
		S_require : S_require_id,
		S_chapter : S_chapter_id,
		S_name : S_name
	});
}

//清空搜索框
function Searchbox_clear(){
	$('#s_course').combobox('clear');
	$('#s_chapter').combobox('clear');
	$('#s_require').combobox('clear');
	$('#s_name').textbox('clear');
}

//显示全部知识点
function Knowledgepoint_SearchAll(){
	$('#Kp_dg').datagrid('load',{});
	var url = '__SITE__index.php/Home/Knowledgepoint/Kp_get_null_option';
//	$('#s_chapter').combobox('clear');
	Searchbox_clear();
	$('#s_chapter').combobox('reload', url);
}

//刷新列表
function refreshKnowledgepointList(){
	$('#Kp_dg').datagrid('clearSelections');
	$('#Kp_dg').datagrid('reload');
	Searchbox_clear();
	clear_chapter();
	var url = '__SITE__index.php/Home/Knowledgepoint/Kp_get_null_option';
	$('#s_chapter').combobox('clear');
	$('#s_chapter').combobox('reload', url);
}

//清空章节下拉框里面的选项
function clear_chapter(){
	$('#s_chapter').combobox('setValues',['请先选科目']);
	$('#s_chapter').combobox('clear');
}

//新建知识点
function newKnowledgepoint(){
	$('#Kp_dlg').dialog('open').dialog('setTitle','新建知识点');
	$('#Kp_fm').form('clear');
	document.getElementById("Kp_op").value="add";
}

//编辑知识点信息
function editKnowledgepoint(){
	var row = $('#Kp_dg').datagrid('getSelected');
	if (row){
		$('#Kp_dlg').dialog('open').dialog('setTitle','编辑知识点');
		$('#Kp_fm').form('load',row);
		document.getElementById("Kp_op").value="edit";
	}else{
		$.messager.alert("提示信息", "请先选择行再进行修改！");
	}
}

//删除知识点
function destroyKnowledgepoint(){
	var row = $('#Kp_dg').datagrid('getSelected');
	if (row){
		$('#Kp_del').dialog('open').dialog('setTitle','删除知识点');
		$('#Kp_fm').form('load',row);
		document.getElementById("Kp_op").value="del";
	}else{
		$.messager.alert("提示信息", "请先选择行再进行修改！");
	}
}

//保存知识点信息
function saveKnowledgepoint(){
	$('#Kp_fm').form('submit',{
		url: '__SITE__index.php/Home/Knowledgepoint/Knowledgepoint_save',
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if(result==0 || result==false){
				$.messager.alert("提示信息", "操作失败!");
			} else if(result==2){
				$.messager.alert("提示信息", "该知识点已存在！");
			} else if(result==1) {
				$.messager.alert("提示信息", "操作成功");
				$('#Kp_dlg').dialog('close');
				$('#Kp_del').dialog('close');
				$('#Kp_dg').datagrid('reload');
			}
		}
	});
}

//打开excel选择器
function selectExcel(){
	$('#Kp_upload').dialog('open').dialog('setTitle','批量导入知识点');
	$('#upload_fm').form('clear');
	var file_clear = document.getElementById('upfile');
	file_clear.outerHTML=file_clear.outerHTML;
}

//上传文件
function uploadFile(){
	$('#upload_fm').form('submit',{
		url: '__SITE__index.php/Home/Knowledgepoint/upload_excel',
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var arr_result = result.split(" ");
			//取得result
			result=arr_result[0][1];
			//取得错误的行号
			rows=arr_result[1];
			if(rows!=null){
				rows=rows.replace(",","");
				rows=rows.replace('"',"");
			}
			if(result==1){
				$.messager.alert("提示信息", "导入成功!");
				$('#Kp_upload').dialog('close');
				$('#Kp_dg').datagrid('reload');
			} else if(result==2){
				$.messager.alert("提示信息", "导入成功，但是行号为"+rows+"的数据导入失败，这些行的知识点名称已存在或者为空。");
				$('#Kp_upload').dialog('close');
				$('#Kp_dg').datagrid('reload');
			} else {
				$.messager.alert("提示信息", "导入失败。");
			}
		}
	});
}
</script>
</body>