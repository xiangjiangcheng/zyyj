<include file="./Application/Home/View/Header.php" />
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
	<table id="Dtable" style="height:100% "></table>
	<div id="Dtool" style="padding:7px">
		<a class="easyui-linkbutton" iconCls='icon-add' plain='true' id='add2' onclick="Obj.add2();">添加</a>
		<a class="easyui-linkbutton" iconCls='icon-edit' plain='true' id='xg2' onclick="Obj.xg2();">修改</a>
		<!--<a class="easyui-linkbutton" iconCls='icon-save' plain='true' id='save2' style="display:none" onclick="">保存</a>-->
		<a class="easyui-linkbutton" iconCls='icon-undo' plain='true' id='undo2' style="display:none" onclick="">取消选中</a>
		<a class="easyui-linkbutton" iconCls='icon-remove' plain='true' id='remove2' onclick="Obj.remove2();">删除</a>
		<a class="easyui-linkbutton" iconCls='icon-search' plain='true' onclick="Obj.Findall();">查看全部</a>
		<div style="margin-left:10px;">
			<form id='g_search'>
			<span>学院:</span>
			<input id="d_college" name="d_college" style="width:110px;" class="easyui-combobox"
			            url="__SITE__index.php/Home/Tao/Tao_GetCollege"
			            valueField="college_id" textField="name" >
			<span style="margin-left:10px">查询部门</span>
			<input type="text" name="sdname"  id="sdname" class="textbox" style="width:110px;">
			<a  class="easyui-linkbutton" iconCls = "icon-search" onclick="Obj.search2();">查询</a>
			</input>
			</form>
			
		</div>
	</div>
	<link rel="stylesheet" href="__SITE__source/css/Grade_Manage.css">
	<div id='AddDpt' title="添加部门" data-options="iconCls:'icon-save'" closed="true" style="padding:5px;width:400px;height:350px;">
		<div class="form-box1">
			<h3 class="Gtitle">填写部门信息</h3>
			<form id="NewDpt" style="margin: 0; padding: 6px 60px;" method="post">
				<div class="Gitem">
					<label class="Mtitle">学院名称</label>
					<input id="Dcollege" name='Dcollege' required="true" editable='false' style="width:143px">  
				</div>
				<div class="Gitem">
					<label class="Mtitle">上级部门</label>
					<input id="Pdpt" name='Pdpt' required="true" editable='false' style="width:143px">
				</div>
				<div class="Gitem">
					<label class="Mtitle">部门名称</label>
					<input id='dptname'  type="text" name="dptname" class="easyui-validatebox textbox" required="true" style='width: 143px'>
				</div>
				<div class="Gitem">
					<label class="Mtitle">备注</label>
					<input type="text" ' id='dptcomment' name="dptcomment" class="easyui-validatebox textbox"
					style='width: 143px'>
					<input type="hidden" name="Dtype" id="Dtype" value=""/>
					<input type="hidden" name="D_type_id" id='D_type_id' value="">
				</div>
			</form>
		</div>
	</div>
<script type="text/javascript">
	//提示信息
	$('#dptname').validatebox({ 
		required : true, 
		missingMessage : '请输入部门名称',
		invalidMessage : '部门名称不能为空',
	});

	$('#d_college').combobox({
		onSelect:function (rec) {
			$('#Dtable').datagrid('load',{
				college_id:$('#d_college').combobox("getValue"),
			});
		}
	});
	//加载添加框
	$(function(){
		$('#AddDpt').dialog({
			buttons:[{
				text:'添加',
				iconCls:'icon-add',
				handler:function(){
					Obj.insert2();
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#AddDpt').dialog('close');
				}
			}]
		});
	});
	//操作方法集合
	Obj = {
		editRow:undefined,
		Findall:function(){
			$('#g_search').form('clear');
			$('#Dtable').datagrid('load',{});
		},
		loadbox2:function(){
			//加载学院选择菜单
			$('#Dcollege').combobox({
			    url:'__SITE__index.php/Home/Tao/Tao_GetCollege',
			    valueField:'college_id',
			    textField:'name',
			    onSelect:function(record){
			    	$('#Pdpt').combobox({
			    		url:'{:U('SysUserManage_Dpt_GetDlist')}'+'?college_id='+$('#Dcollege').combobox('getValue'),
			    		valueField:'department_id',
						textField:'name',
			    	});
			    }
			});  
			//加载上级部门选择菜单
			$('#Pdpt').combobox({
				url:'{:U('SysUserManage_Dpt_GetDlist')}',
				valueField:'department_id',
				textField:'name',
				onSelect:function(record){
					var dname = $('#Dcollege').combobox('getValue');
					if(dname==''){
						$.messager.alert('提示',"请先选择学院",'info');
						$('#Pdpt').combobox('clear');
					}
				}
			});
		},
		insert2:function(){
			if($('#NewDpt').form("validate")){
				$.ajax({
					url:'{:U('SysUserManage_Dpt_Insert')}',
					type:'POST',
					data:{
						Dtype:$('#Dtype').val(),
						dpt_id:$('#D_type_id').val(),
						college_id:$('#Dcollege').combobox('getValue'),
						f_id:$('#Pdpt').combobox('getValue'),
						name:$('#dptname').val(),
						comment:$('#dptcomment').val(),
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
							$('#AddDpt').dialog('close');
							$('#Dtable').datagrid('reload');
						}
					}
				});
			}
		},
		add2:function(){
			Obj.loadbox2();
			$("#AddDpt").dialog("open").dialog('setTitle', '添加部门');
			$("#NewDpt").form("clear");
			document.getElementById("Dtype").value="add";
			$('#AddDpt').dialog('open');
		},
		search2:function(){
			$('#Dtable').datagrid('load',{
				dname:$.trim($('input[name=sdname]').val()),
				college_id:$('#d_college').combobox("getValue"),
			});
		},
		xg2:function(){
			document.getElementById("Dtype").value="edit";
			Obj.loadbox2();
			var getrow = $('#Dtable').datagrid('getSelections');
			if(getrow.length>0){
				 $("#AddDpt").dialog("open").dialog('setTitle', '修改部门信息');
				 $('#NewDpt').form('load',{dptname:getrow[0].name,dptcomment:getrow[0].comment,
				 Dcollege:getrow[0].college_id,Pdpt:getrow[0].parent_id});
				 document.getElementById("D_type_id").value=getrow[0].department_id;
			}else{
				$.messager.alert('警告','修改必须或只能选择一行！','warning');
			}
		},
		save2:function(){

		},
		remove2:function(){
			var Drows = $('#Dtable').datagrid('getSelections');
			if(Drows.length>0){
				$.messager.confirm('确定操作','删除之后将不可恢复，是否确认？',
					function(qr){
						if(qr){
							var Did=Drows[0].department_id;
							$.ajax({
								url:'{:U('SysUserManage_Dpt_delete')}',
								type:'POST',
								data:{
									department_id:Did,
								},
								beforeSend:function(){
									$.messager.progress({
										text : '正在删除...',
									}); 
								},
								success:function(data){
									$.messager.progress('close'); 
									if(data.success){
										$('#Dtable').datagrid('loaded');
										$('#Dtable').datagrid('load');
										$('#Dtable').datagrid('unselectAll');
										$.messager.alert('提示',data.msg,'info');
									}else{
										$.messager.alert('提示',data.msg,'info');
									}
								}
							});
						}
					});
			}else{
				$.messager.alert('提示','请选择要删除的记录！','info');
			}
		}
	};
	$(function(){
		$('#Dtable').datagrid({
			width:'100%',
			title:'部门信息表',
			iconCls:'icon-search',
			url:'{:U('SysUserManage_Dpt_GetInfo')}',
			method: 'POST',
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
			toolbar: '#Dtool',
			columns:[[
		       { 
		       		field: 'department_id',
		       		title:'部门编号',
		       		width:'5%', 
		       		algin:'center',
		       		checkbox:true,
		       },
		       {	field: 'name', 
		       		title: '部门名称', 
		       		width: '25%', 
		       		align: 'center',
		       		sortable:true,
		       		sortOrder: 'asc',
		       	},
		       	{ 	field: 'fname', 
		       		title: '上级部门名称', 
		       		width: '20%', 
		       		align: 'center',
		       	},
		       	{ 	field: 'cname', 
		       		title: '学院名称', 
		       		width: '20%', 
		       		align: 'center',
		       	},
		       	{
		       		field: 'comment',
		       		title: '备注',
		       		width: '30%',
		       		align: 'center',
		       	},
		    ]],
		});
	});
	//设置分页控件
	var p=$("#Dtable").datagrid('getPager');
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