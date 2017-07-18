
/*//按学号模糊查询
function S_IDSearch(){
	if($('input[name=st_account]').val()!=""){
		$('#Stable').datagrid('load',{
			account:$.trim($('input[name=st_account]').val()),
		});
	}
}*/
//按名字模糊查询
/*function S_MSearch(){
	if($('input[name=st_stuname]').val()!=""){
		$('#Stable').datagrid('load',{
			stuname:$.trim($('input[name=st_stuname]').val()),
		});
	}
}*/
$('#st_class').combobox({
	onSelect:function(){
		var gid = $('#st_grade').combobox('getValue');
		if(!gid){
			$.messager.alert('提示','请先选择年级','info');
			$('#st_class').combobox('clear');
		}
	}
});
$('#st_grade').combobox({
	onSelect:function(){
		var gid = $('#st_grade').combobox('getValue');
		//var mid = $('#st_major').combobox('getValue');
		//+'&major_id='+mid)
		$('#st_class').combobox({
    		url:classurl+'?grade_id='+gid,
    		valueField:'class_id',
			textField:'name',
		});
	}
});
//按筛选条件查询
function S_FindSearch(){
	$('#Stable').datagrid('load',{
		//collegeid:$.trim($('#st_college').combobox('getValue')),
		account:$.trim($('input[name=st_account]').val()),
		stuname:$.trim($('input[name=st_stuname]').val()),
		majorid:$.trim($('#st_major').combobox('getValue')),
		gradeid:$.trim($('#st_grade').combobox('getValue')),
		classid:$.trim($('#st_class').combobox('getValue')),
	});
}
function S_Clearform(){
	$('#st_major').combobox('clear');
	//$('#st_college').combobox('clear');
	$('#st_grade').combobox('clear');
	$('#st_class').combobox('clear');
}
//查询全部
function S_AllSearch(){
	S_Clearform();
	$('#St_form').form('reset');
	$('#Stable').datagrid('load',{});
}
//刷新
function S_Reload(){
	$('#Stable').datagrid('reload');
}
$(function(){
	$('#Stable').datagrid({
		width:'100%',
		height:'100%',
		title:'学生闯关情况',
		iconCls:'icon-search',
		url:stageurl,
		method: 'POST',
		singleSelect:true,
		rownumbers:true,
		loadMsg: '正在努力为您加载数据',
		fit:true,
		fitColumns: true,
		remoteSort: false,
		pagination:true,
		toolbar:'#St_tool',
		columns:[[
			{
				field:'account',
				title:'学号',
				idth:'10%',
				algin:'center',
			},
			{
				field:'stuname',
				title:'姓名',
				width:'10%',
				algin:'center',
			},
			{
				field:'class_name',
				title:'班级',
				width:'15%',
				algin:'center',
			},
			{
				field:'grade_name',
				title:'年级',
				width:'10%',
				algin:'center',
			},
			{
				field:'major_name',
				title:'专业',
				width:'15%',
				algin:'center',
			},
			{
				field:'college_name',
				title:'学院',
				width:'10%',
				algin:'center',
			},
			/*{
				field:'couname',
				title:'科目',
				width:'10%',
				algin:'center',
			},*/
			{
				field:'status',
				title:'状态',
				width:'10%',
				algin:'center',
				formatter:function(value){
					if(value==1){
						return '已过关';
					}else if(value==2){
						return '未过关';
					}else{
						return '未练习';
					}
				},
			},
			{
				field:'score',
				title:'分数',
				width:'10%',
				algin:'center',
			},
			{
				field:'pname',
				title:'使用方案',
				width:'10%',
				algin:'center',
			}
		]],
	});
	var p=$("#Stable").datagrid('getPager');
	$(p).pagination({
         pageSize:10,     //每页显示的大小
         pageList:[10,20,30],
         beforePageText:'第',  //页数文本框前显示的汉字
         afterPageText:'页 共{pages}页',
         displayMsg:'当前显示{from}-{to}条记录 共{total}条记录',
	 });
});