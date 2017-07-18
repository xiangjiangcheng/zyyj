<table id="Mdg" class="easyui-datagrid" style="width:100%;height:100%"
	url='__SITE__index.php/Home/Major/Major_get_majors'
	toolbar="#Mtoolbar"
	rownumbers="true"
	fitColumns="true" 
	fit="true"
	singleSelect="true"
	pagination="true"
	pageSize="20"
	title="专业管理"
	iconCls="icon-save"
	>
	<thead>
		<tr>
			<th field="name" width="28%">专业名称</th>
			<th field="comment" width="70%">专业备注</th>
		</tr>
	</thead>
</table>
<div id="Mtoolbar">
	<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMajor()">添加</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMajor()">编辑</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMajor()">删除</a>
	<a href='#' class="easyui-linkbutton" iconCls = "icon-reload" plain="true" onclick="refreshMajorList()">刷新</a>
</div>
<!-- 新建专业和编辑专业用的表单 -->
<div id="Mdlg" class="easyui-dialog" style="width:320px;height:200px;padding:10px 20px"
	closed="true" buttons="#Mdlg-buttons">
	<form id="Mfm" method="post">
		<div class="fitem">
			<label>专业名称:</label>
			<input name="name" class="easyui-validatebox" required="true"/>
		</div>
		<br>
		<div class="fitem">
			<label>专业备注:</label>
			<input name="comment"/>
		</div>
		<input type="hidden" name="major_id" id="major_id"/>
		<input type="hidden" name="op" id="op" value=""/>
	</form>
</div>
<!-- 删除专业用的对话框 -->
<div id="Mdel" class="easyui-dialog" closed="true" buttons="#Mdel-buttons" style="width:300px;height:155px">
	<label>删除专业的同时会删除该专业下的所有挂靠班级，确定要继续删除该专业？</label>
</div>

<!-- 按钮组 -->
<div id="Mdel-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMajor()">确定</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Mdel').dialog('close')">取消</a>
</div>
<div id="Mdlg-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveMajor()">保存</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#Mdlg').dialog('close')">取消</a>
</div>

<script type="text/javascript">
	//新建专业
	function newMajor(){
		$('#Mdlg').dialog('open').dialog('setTitle','添加专业');
		$('#Mfm').form('clear');
		document.getElementById("op").value="add";
	}
	//编辑专业信息
	function editMajor(){
		var row = $('#Mdg').datagrid('getSelected');
		if (row){
			$('#Mdlg').dialog('open').dialog('setTitle','编辑专业信息');
			$('#Mfm').form('load',row);
			document.getElementById("op").value="edit";
		}else{
			$.messager.alert("提示信息", "请先选择行再进行修改！");
		}
	}
	//保存添加、修改的数据
	function saveMajor(){
		$('#Mfm').form('submit',{
			url: '__SITE__index.php/Home/Major/Major_save_major',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				var result = eval('('+result+')');
				if(result==0 || result==false){
					$.messager.alert("提示信息", "操作失败!");
				} else if(result==2){
					$.messager.alert("提示信息", "该专业已存在！");
				} else {
					$.messager.alert("提示信息", "操作成功");
					$('#Mdlg').dialog('close');
					$('#Mdel').dialog('close');
					$('#Mdg').datagrid('reload');
				}
			}
		});
	}
	//删除专业按钮
	function destroyMajor(){
		var row = $('#Mdg').datagrid('getSelected');
		if (row){
			$('#Mdel').dialog('open').dialog('setTitle','删除专业');
			$('#Mfm').form('load',row);
			document.getElementById("op").value="del";
		}else{
			$.messager.alert("提示信息", "请先选择行再进行修改！");
		}
	}
	
	//刷新列表
	function refreshMajorList(){
		$('#Mdg').datagrid('clearSelections');
		$('#Mdg').datagrid('reload');
	}
	</script>
</body>
