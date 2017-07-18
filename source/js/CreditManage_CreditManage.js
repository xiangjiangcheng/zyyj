/*//按学号模糊查询
function IDSearch(){
	if($('input[name=sc_account]').val()!=""){
		$('#Ctable').datagrid('load',{
			account:$.trim($('input[name=sc_account]').val()),
		});
	}
}*/
/*//按名字模糊查询
function MSearch(){
	if($('input[name=sc_stuname]').val()!=""){
		$('#Ctable').datagrid('load',{
			stuname:$.trim($('input[name=sc_stuname]').val()),
		});
	}
}*/
$('#sc_class').combobox({
	onSelect:function(){
		var gid = $('#sc_grade').combobox('getValue');
		if(!gid){
			$.messager.alert('提示','请先选择年级','info');
			$('#sc_class').combobox('clear');
		}
	}
});
$('#sc_grade').combobox({
	onSelect:function(){
		var gid = $('#sc_grade').combobox('getValue');
		//var mid = $('#sc_major').combobox('getValue');
		//+'&major_id='+mid
		$('#sc_class').combobox({
    		url:classurl+'?grade_id='+gid,
    		valueField:'class_id',
			textField:'name',
		});
	}
});
//按筛选条件查询
function FindSearch(){
	$('#Ctable').datagrid('load',{
		//collegeid:$.trim($('#sc_college').combobox('getValue')),
		stuname:$.trim($('input[name=sc_stuname]').val()),
		account:$.trim($('input[name=sc_account]').val()),
		majorid:$.trim($('#sc_major').combobox('getValue')),
		gradeid:$.trim($('#sc_grade').combobox('getValue')),
		classid:$.trim($('#sc_class').combobox('getValue')),
	});
}
//清除表单查询数据
function Clearform(){
	$('#sc_major').combobox('clear');
	//$('#sc_college').combobox('clear');
	$('#sc_grade').combobox('clear');
	$('#sc_class').combobox('clear');
}
//查询全部
function AllSearch(){
	Clearform();
	$('#Cr_form').form('reset');
	$('#Ctable').datagrid('load',{});
}
//导出当前积分排行榜
function GetDld(){
	var stuname = $.trim($('input[name=sc_stuname]').val());
	var account = $.trim($('input[name=sc_account]').val());
	var majorid = $.trim($('#sc_major').combobox('getValue'));
	var gradeid = $.trim($('#sc_grade').combobox('getValue'));
	var classid = $.trim($('#sc_class').combobox('getValue'));
	var type = 'opt';
	window.open(getexcel+'stuname='+stuname+'&account='+
		account+'&majorid='+majorid+'&gradeid='+gradeid+'&classid='+classid+'&type='+type);
}

//刷新
function Reload(){
	$('#Ctable').datagrid('reload');
}
//记载积分排行榜表格
$(function(){
	$('#Ctable').datagrid({
		width:'100%',
		height:'100%',
		title:'积分排行榜',
		iconCls:'icon-search',
		url:geturl,
		method: 'POST',
		striped: true,
		singleSelect:true,
		rownumbers:true,
		loadMsg: '正在努力为您加载数据',
		fitColumns: true,
		remoteSort: false,
		pagination:true,
		toolbar: '#Cr_tool',
		columns:[[
			{
				field: 'stu_id',
		       	title:'数据标号',
		       	width:'5%', 
		       	algin:'center',
		       	checkbox:'true',
			},
			{
				field:'account',
				title:'学号',
				width:'10%',
				algin:'center',
			},
			{
				field:'stuname',
				title:'姓名',
				width:'15%',
				algin:'center',
			},
			{
				field:'tscore',
				title:'积分',
				width:'5%',
				algin:'center',
			},
			{
				field:'class_name',
				title:'班级',
				width:'8%',
				algin:'center',
			},
			{
				field:'grade_name',
				title:'年级',
				width:'20%',
				algin:'center',
			},
			{
				field:'major_name',
				title:'专业',
				width:'20%',
				algin:'center',
			},
			{
				field:'college_name',
				title:'学院',
				width:'20%',
				algin:'center',
			}
		]],
		onLoadSuccess:function(){

		},
	});
	//设置分页控件
	var p=$("#Ctable").datagrid('getPager');
	$(p).pagination({
         pageSize:10,     //每页显示的大小
         pageList:[10,20,30],
         beforePageText:'第',  //页数文本框前显示的汉字
         afterPageText:'页 共{pages}页',
         displayMsg:'当前显示{from}-{to}条记录 共{total}条记录',
	 	});
});