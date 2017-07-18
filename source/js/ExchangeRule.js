$('#J_Rulename').validatebox({ 
	required : true, 
	missingMessage : '请输入规则名称',
	invalidMessage : '规则名称不能为空',
});
$('#J_NeedScore').numberbox({ 
	required : true, 
	missingMessage : '请输入兑换所需积分',
	invalidMessage : '兑换所需积分不能为空',
});
$('#J_GetCredit').numberbox({ 
	required : true, 
	missingMessage : '请输入兑换所得学分',
	invalidMessage : '兑换所得学分不能为空',
});
function Loadbox(){
	$('#J_NeedScore').numberbox({
	    min:1,
	    precision:1
	});
	$('#J_GetCredit').numberbox({
	    min:0,
	    precision:1
	});
}
//添加规则
function JRule_add(){
	Loadbox();
	$('#J_Rulename').removeAttr("disabled");
	$("#J_NeedScore").numberbox({disabled:false});
	$("#J_GetCredit").numberbox({disabled:false});
	$("#J_AddRule").dialog("open").dialog('setTitle', '添加规则');
	$("#J_RuleEdit").form("clear");
	$("#isuse").hide();
	document.getElementById("J_type").value="add";
}
//修改规则
function JRule_edit(){
	var getrow = $('#Jtable').datagrid('getSelections');
	if(getrow.length>0){
		Loadbox();
		$("#J_AddRule").dialog("open").dialog('setTitle', '修改规则');
		$("#isuse").show();
		document.getElementById("J_type").value="edit";
		//赋值
		$('#J_RuleEdit').form('load',{J_Rulename:getrow[0].name,J_NeedScore:getrow[0].score,
		J_GetCredit:getrow[0].integral,J_Status:getrow[0].status});
		document.getElementById("JRule_id").value=getrow[0].rule_id;
		if(getrow[0].status==1){
			$("#J_Rulename").attr("disabled","disabled"); 
			$("#J_NeedScore").numberbox({disabled:true});
			$("#J_GetCredit").numberbox({disabled:true});
			$("#J_Status").attr("disabled","disabled"); 
		}else{
			$('#J_Rulename').removeAttr("disabled");
			$("#J_NeedScore").numberbox({disabled:false});
			$("#J_GetCredit").numberbox({disabled:false});
			$('#J_Status').removeAttr("disabled");
		}
	}else{
		$.messager.alert('警告','修改必须或只能选择一行！','warning');
	}
}
function JRule_Update(){
	if($('#J_RuleEdit').form("validate")){
		$.ajax({
			url:Updturl,
			type:'post',
			data:{
				ruleid:$('#JRule_id').val(),
				rulename:$('#J_Rulename').val(),
				needscore:$('#J_NeedScore').val(),
				getcredit:$('#J_GetCredit').val(),
				rulestatus: $("#J_Status option:selected").val(),
				type:$('#J_type').val(),
			},
			beforeSend:function(){ 
				$.messager.progress({
					text : '正在保存...',
				}); 
			},
			success:function(data){
				$.messager.progress('close'); 
				$.messager.alert("提示信息", data.msg);
				if(data.success){
					$('#J_AddRule').dialog('close');
					$('#Jtable').datagrid('reload');
				}
			},
		});
	}
}
//加载添加框的工具栏
$(function(){
	$('#J_AddRule').dialog({
		buttons:[{
			text:'保存',
			iconCls:'icon-save',
			handler:function(){
				JRule_Update();
			}
		},{
			text:'取消',
			iconCls:'icon-cancel',
			handler:function(){
				$('#J_AddRule').dialog('close');
			}
		}]
	});
});
//刷新表
function JRule_reload(){
	$('#Jtable').datagrid('reload');
}
//删除规则
function JRule_delete(url){
	var rows = $('#Jtable').datagrid('getSelections');
	if(rows.length>0){
		var status = rows[0].status;
		var id = rows[0].rule_id;
		if(status==1){
			$.messager.alert('提示','不能删除正在使用的规则','info');
		}else{
			$.messager.confirm('确定操作','删除之后将不可恢复，是否确认？',
				function(flag){
					if(flag){
						$.post(url,{rule_id:id,status:status,},
							function(data){
								if(data.success){
									$('#Jtable').datagrid('loaded');
									$('#Jtable').datagrid('load');
									$('#Jtable').datagrid('unselectAll');
									$.messager.alert('提示','删除成功！','info');
								}else{
									$.messager.alert('提示','删除失败,不能删除已经使用过的规则！','info');
								}},
						'json');
					}
				});
		}
	}
}
//添加规则

//加载积分兑换规则的datagrid
$(function(){
	$('#Jtable').datagrid({
		width:'100%',
		height:'100%',
		title:'积分兑换规则',
		iconCls:'icon-search',
		url:JErule,
		method: 'POST',
		singleSelect:true,
		rownumbers:true,
		loadMsg: '正在努力为您加载数据',
		fit:true,
		fitColumns: true,
		remoteSort: false,
		pagination:true,
		toolbar: '#Jtool',
		columns:[[
			{
				field:'rule_id',
				title:'规则编号',
				width:'5%',
				algin:'center',
				checkbox:true,
			},
			{
				field:'name',
				title:'规则名称',
				width:'20%',
				algin:'center',
			},
			{
				field:'createdate',
				title:'更新时间',
				width:'30%',
				algin:'center',
			},
			{
				field:'score',
				title:'兑换所需积分',
				width:'10%',
				algin:'center',
			},
			{
				field:'integral',
				title:'兑换所得学分',
				width:'10%',
				algin:'center',
			},
			{
				field:'status',
				title:'使用状态',
				width:'10%',
				algin:'center',
				formatter:function(value){
					if(value==1){
						return '启用';
					}else{
						return '禁用';
					}
				}			
			},
		]],

	});
	//设置分页控件
	var p=$("#Jtable").datagrid('getPager');
	$(p).pagination({
         pageSize:10,     //每页显示的大小
         pageList:[10,20,30],
         beforePageText:'第',  //页数文本框前显示的汉字
         afterPageText:'页 共{pages}页',
         displayMsg:'当前显示{from}-{to}条记录 共{total}条记录',
	 });
});