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
<style type="text/css">
	.textbox{
		height: 20px;
		margin:0;
		padding: 0 2px;
		box-sizing:content-box;
	}
</style>
<body class="easyui-layout">
	<table id="Utable" style="height:100% width=100% "></table>
	<div id='tool1' style="padding:7px;">
		<div>
			<a class="easyui-linkbutton" iconCls = "icon-add" plain="true" onclick="Mth1.add1();">添加</a>
			<a class="easyui-linkbutton" iconCls = "icon-edit" plain="true" id="xg1" onclick="Mth1.xg1();">修改状态</a>
			<a class="easyui-linkbutton" iconCls = "icon-remove" plain="true"
			onclick="Mth1.remove1();">删除</a>
			<a class="easyui-linkbutton" iconCls = "icon-back" plain="true" onclick="Mth1.resetpwd();">重置密码</a>

		</div>
		<div>
			<form id='s_form'>
			<span>
				<label>姓名</label>
				<input type="text" name="susername" id='susername'  class="textbox" style="width:110px;">
			</span>
			<span style="margin-left:10px">
				<label>学院</label>
				<input id="s_college" name="s_college" style="width:110px;">
			</span>
			<span style="margin-left:10px">
				<label>部门</label>
				<input id="Udept1" editable='false' style="width:110px">
			</span>
			<span style="margin-left:10px">
				<a  class="easyui-linkbutton" iconCls = "icon-search"  onclick="Mth1.search1();">查询</a>
			</span>
			<span style="margin-left:10px">
				<a  class="easyui-linkbutton" iconCls = "icon-search" onclick="Mth1.getall();">查看全部</a>
			</span>
			</form>
		</div>
	</div>
<link rel="stylesheet" href="http://localhost/zyyj/source/css/Grade_Manage.css">
<div id='addUser' data-options="iconCls:'icon-save'" closed="true" style="padding:5px;width:410px;height:350px;">
	<div class="form-box2">
		<h3 class="Gtitle">填写用户信息</h3>
		<form id="NewUser">
			<div class="Gitem">
				<label class="Mtitle">角色</label>
				<input id='Upost' required="true" editalbe='false' style="width:143px">
			</div>
			<div class="Gitem">
				<label class="Mtitle">学院</label>
				<input id="Ucollege" required="true" editable='false' style="width:143px">
			</div>
			<div class="Gitem">
				<label class="Mtitle">部门</label>
				<input id="Udept" required="true" editable='false' style="width:143px">
			</div>
			<div class="Gitem">
				<div class="Mtitle">用户名</div>
				<input style="width:143px" type="text" class="easyui-validatebox textbox" type="text" name="username" id="username" onblur ="Isset();" validType="englishCheckSub"  data-options="required:true">
				<!--<a id="attention" style="color:red" ></a>-->
			</div>
			<div class="Gitem">
				<div class="Mtitle">密码</div>
				<input type="password" class="easyui-validatebox textbox" type="text" name="password" id="password" required="true" style="width:143px">
			</div>
			<div class="Gitem">
				<div class="Mtitle">确认密码</div>
				<input type="password" class="easyui-validatebox textbox" type="text" name="repassword" id="repassword" required="true" validType="equals['#password']" style="width:143px">
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	//加载按部门搜搜索的commbox
	$('#s_college').combobox({
		url:'http://localhost/zyyj/index.php/Home/Tao/Tao_GetCollege',
		valueField:"college_id" ,
		textField:"name",
		onSelect:function(rec){
			$('#Udept1').combobox({
				url:'<?php echo U('SysUserManage_Dpt_GetDlist');?>'+'?college_id='+$('#s_college').combobox('getValue'),
				valueField:'department_id',
				extField:'name',
			});
		}
	});
	$('#Udept1').combobox({
		url:'<?php echo U('SysUserManage_Dpt_GetDlist');?>',
		valueField:'department_id',
		textField:'name',
		onSelect:function(){
			if($('#s_college').combobox("getValue")==''){
				$.messager.alert('提示','请先选择学院','info');
				$('#Udept1').combobox("clear");
			}
			/*$('#Utable').datagrid('load',{
				deptid:$.trim($('#Udept1').combobox('getValue')),
				user:$.trim($('#susername').val()),
			});*/
		}
	});
	$(function(){
	//加载添加用户信息的dialog
		$('#addUser').dialog({
			buttons:[{
				text:'保存',
				iconCls:'icon-save',
				handler:function(){
					Mth1.insert1();
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#addUser').dialog('close');
					$('#attention').html("");
				}
			}]
		});
	});
	//验证账号格式
	$('#username').validatebox({ 
		required : true, 
		missingMessage : '请输入用户帐号',
		invalidMessage :'输入账号无效',
	});
	//验证密码格式
	$('#password').validatebox({ 
		required : true, 
		validType : 'length[6,30]', 
		missingMessage : '请输入密码密码',
		invalidMessage : '密码由6到30位的数字、字母、非特殊符号组成',
	});
	//验证密码格式
	$('#repassword').validatebox({ 
		required : true, 
		missingMessage : '请确认密码',
	});
	//自定义表单验证内容
	$.extend($.fn.validatebox.defaults.rules, {
		equals: {
			validator: function(value,param){
				return value == $(param[0]).val();
			},
			message: '两次密码输入不一致'
		},
		englishCheckSub : {
	    	validator : function(value) {
				return /^[a-zA-Z0-9]+$/.test(value);
			},
			message : "账号只能包括英文字母、数字"
  		 },
	});
	Mth1 ={
		loadbox:function(){
			$('#Upost').combobox({
			    url:'<?php echo U('SysUserManage_GetPostInfo');?>',
			    valueField:'post_id',
			    textField:'name',
			});
			$('#Udept').combobox({
				url:'http://localhost/zyyj/index.php/Home/SysUserManage/SysUserManage_GetbDlist',
				valueField:'department_id',
				textField:'name',
				onSelect:function(record){
					var cid= $('#Ucollege').combobox('getValue');
					if(cid==''){
						$.messager.alert('提示',"请先选择学院",'info');
						$('#Udept').combobox('clear');
					}
				}
			});
			$('#Ucollege').combobox({
				url:'http://localhost/zyyj/index.php/Home/Tao/Tao_GetCollege',
				valueField:'college_id',
				textField:'name',
				onSelect:function(record){
					$('#Udept').combobox({
						url:'http://localhost/zyyj/index.php/Home/SysUserManage/SysUserManage_GetbDlist?cid='+record.college_id,
						valueField:'department_id',
						textField:'name',
					});
					$('#Udept').combobox('reload');
				}
			});
		},
		insert1:function(){
			if ($('#NewUser').form('validate')){ 
				if($('#Udept').combobox('getValue')==-1){
					$.messager.alert('提示','请选择用户所在部门','info');
				}else{
					$.ajax({
						url:'<?php echo U('SysUserManage_User_Insert');?>',
						type:'POST',
						data:{
							post_id:$('#Upost').combobox('getValue'),
							department_id:$('#Udept').combobox('getValue'),
							username:$.trim($('#username').val()),
							password:$('#password').val()
						},
						success:function(data){
							if(data.success==true){
							$('#att').hide();
							$.messager.alert('提示','添加成功！','info');
							$('#addUser').dialog('close');
							$('#Utable').datagrid('reload');
						}else{
							$.messager.alert('提示','添加失败,该用户已存在,请重试！','info');
						}
						},
					});
				}
			}
		},
		add1:function(){
			Mth1.loadbox();
			$("#NewUser").form("clear");
			$('#addUser').dialog('setTitle','添加用户').dialog('open');
		},
		//查看全部
		getall:function(){
			$('#s_form').form('reset');
			$('#Udept1').combobox('clear');
			$('#Utable').datagrid('load',{});
		},
		//查找
		search1:function(){
			var dptid = $.trim($('#Udept1').combobox('getValue'));
			if(dptid==-1){
				dptid = '';
			}
			$('#Utable').datagrid('load',{
				user:$.trim($('input[name=susername]').val()),
				deptid:dptid,
				college_id:$('#s_college').combobox("getValue"),
			});
		},
		xg1:function(){
			var rows = $('#Utable').datagrid('getSelections');
			var msg;
			if(rows.length>0){
				if(rows[0].status==1){
					msg = '是否确认禁用该账号';
				}else{
					msg = '是否启用该账号';
				}
				$.messager.confirm('确定操作',msg,function(flag){
					if(flag){
						$.post('<?php echo U('SysUserManage_SetStatus');?>',{user_id:rows[0].user_id,status:rows[0].status},function(data){
								$.messager.alert('提示','修改成功！','info');
								$('#Utable').datagrid('load');
								$('#Utable').datagrid('unselectAll');
						});
					}
				});
			}else{
				$.messager.alert('警告','修改必须或只能选择一行！','warning');
			}
		},
		remove1:function(){
			var rows = $('#Utable').datagrid('getSelections');
			if(rows.length>0){
				$.messager.confirm('确定操作','删除之后将不可恢复，是否确认？',
				function(flag){
					if(flag){
						var id=rows[0].user_id;
						$.ajax({
							url:'<?php echo U('SysUserManage_User_delete');?>',
							type:'POST',
							data:{
								user_id:id,
							},
							beforeSend:function(){
								$('#Utable').datagrid('loading');
							},
							success:function(data){
								$.messager.progress('close'); 
								if(data.success){
									$('#Utable').datagrid('loaded');
									//刷当前前页
									$('#Utable').datagrid('load');
									$('#Utable').datagrid('unselectAll');
									$.messager.alert('提示','删除成功！','info');
								}else{
									$.messager.alert('提示','删除失败,请重试','info');
								}
							}
						});
					}
				});
			}else{
				$.messager.alert('提示','请选择要删除的记录！','info');
			}
		},
		resetpwd:function(){
			var rows = $('#Utable').datagrid('getSelections');
			if(rows.length>0){
				$.messager.confirm('确定操作','是否将该用户的密码重置为:123456？',
					function(flag){
						if(flag){
							var id=rows[0].user_id;
							$.ajax({
							url:'<?php echo U('SysUserManage_User_Resetpwd');?>',
							type:'POST',
							data:{
								user_id:id,
							},
							beforeSend:function(){
								$('#Utable').datagrid('loading');
							},
							success:function(data){
								$.messager.progress('close'); 
								if(data.success==true){
									$('#Utable').datagrid('loaded');
									//刷当前前页
									$('#Utable').datagrid('load');
									$('#Utable').datagrid('unselectAll');
									$.messager.alert('提示','重置成功,新的密码为:123456！','info');
								}else{
									alert('重置失败,请重新尝试！');
								}
							}
						  });
						}
					});
			}else{
				$.messager.alert('提示','请选择要重置密码的用户！','info');
			}
		}
	};
	$(function(){
		$('#Utable').datagrid({
			width:'100%',
			title:'用户列表',
			iconCls:'icon-search',
			url:'<?php echo U('SysManage_GetUserInfo');?>',
			singleSelect:true,
			rownumbers:true,
			fit:true,
			striped: true,
			fitColumns: true,
			showFooter: true,
			remoteSort: false,
			pagination:true,
			queryParams:{},
			loadMsg: '正在努力为您加载数据',
			toolbar: '#tool1',
			columns:[[
		       {
		       		field: 'user_id', 
		       		title: '用户编号',
		       		width: '5%', 
		       		align: 'left' ,
		       		checkbox:true,
	
		       },
		       { 	field: 'name', 
		       		title: '用户名', 
		       		width: '15%', 
		       		align: 'left',
		       		sortable:true,
		       		sortOrder: 'asc',
		       	},
		       	{ 	field: 'realname', 
		       		title: '姓名', 
		       		width: '12%', 
		       		align: 'left',
		       		sortable:true,
		       		sortOrder: 'asc',
		       	},
		       	{ 	field: 'gender', 
		       		title: '性别', 
		       		width: '10%', 
		       		align: 'left',
		       		formatter:function(value,rowData,rowIndex){
		       			if(value==0){
		       				return "女";
		       			}else{
		       				return "男";
		       			}
		       		}
		       	},
		       { 	field: 'dptname', 
		       		title: '部门名称', 
		       		width: '15%', 
		       		align: 'left'
		       	},
				{ 	field: 'phone',
					title: '电话', 
					width: '15%', 
					align: 'left'
				},
				{ 	field:'email',
					title: '邮箱 ', 
					width:'15%', 
					align:'left'
				},
				{ 	field: 'status', 
					title:'状态', 
					width:'5%', 
					align:'left',
					formatter:function(value,rowData,rowIndex){
						if(value==1){
							return '启用';
						}else{
							return '禁用';
						}
					}
				},
			]],
		});
	});
	//设置分页控件
		var p=$("#Utable").datagrid('getPager');
		$(p).pagination({
             pageSize:20,     //每页显示的大小
             pageList:[10,20,30],
             beforePageText:'第',  //页数文本框前显示的汉字
             afterPageText:'页 共{pages}页',
             displayMsg:'当前显示{from}-{to}条记录 共{total}条记录',
        });
</script>
</body>
</html>