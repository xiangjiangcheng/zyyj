$('#ex_class').combobox({
	onSelect:function(){
		var gid = $('#ex_grade').combobox('getValue');
		if(!gid){
			$.messager.alert('提示','请先选择年级','info');
			$('#ex_class').combobox('clear');
		}
	}
});
$('#ex_grade').combobox({
	onSelect:function(){
		var gid = $('#ex_grade').combobox('getValue');
		//var mid = $('#st_major').combobox('getValue');
		//+'&major_id='+mid)
		$('#ex_class').combobox({
    		url:classurl+'?grade_id='+gid,
    		valueField:'class_id',
			textField:'name',
		});
	}
});

//按筛选条件查询
function E_FindSearch(){
	$('#Etable').datagrid('load',{
		account:$.trim($('input[name=ex_account]').val()),
		stuname:$.trim($('input[name=ex_stuname]').val()),
		//collegeid:$.trim($('#ex_college').combobox('getValue')),
		majorid:$.trim($('#ex_major').combobox('getValue')),
		gradeid:$.trim($('#ex_grade').combobox('getValue')),
		classid:$.trim($('#ex_class').combobox('getValue')),
	});
}

function  E_Clearform(){
	$('#ex_major').combobox('clear');
	//
	//$('#ex_college').combobox('clear');
	//$('#ex_grade').combobox('clear');
	//$('#ex_class').combobox('clear');
}
//查询全部
function E_AllSearch(){
	$('#Eform').form('reset');
	E_Clearform();
	$('#Etable').datagrid('load',{});
}
//刷新
function E_Reload(){
	$('#Etable').datagrid('reload');
}


//加载数据表
$(function(){
	$('#Etable').datagrid({
		width:'100%',
		title:'积分兑换记录表',
		iconCls:'icon-search',
		url:exlisturl,
		method: 'POST',
		singleSelect:true,
		rownumbers:true,
		fit:true,
		fitColumns: true,
		remoteSort: false,
		pagination:true,
		loadMsg: '正在努力为您加载数据',
		toolbar: '#Ex_tool',
		columns:[[
			{
				field:'account',
				title:'学号',
				width:'10%',
				algin:'center',
			},
			{
				field:'stuname',
				title:'姓名',
				width:'10%',
			},
			{
				field:'stusex',
				title:'性别',
				width:'5%',
				formatter:function(value){
					if(value==1){
						return '男';
					}else{
						return '女';
					}
				}
			},
			{
				field:'needscore',
				title:'花费积分',
				width:'5%',
			},
			{
				field:'getscore',
				title:'得到学分',
				width:'5%',
			},
			{
				field:'time',
				title:'兑换时间',
				width:'10%',
			},
			{
				field:'class_name',
				title:'班级',
				width:'10%',
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
				width:'10%',
				algin:'center',
			},
			{
				field:'college_name',
				title:'学院',
				width:'10%',
				algin:'center',
			},
		]],
	});
	//设置分页控件
	var p=$("#Etable").datagrid('getPager');
	$(p).pagination({
         pageSize:20,     //每页显示的大小
         pageList:[10,20,30],
         beforePageText:'第',  //页数文本框前显示的汉字
         afterPageText:'页 共{pages}页',
         displayMsg:'当前显示{from}-{to}条记录 共{total}条记录',
	 });
});

