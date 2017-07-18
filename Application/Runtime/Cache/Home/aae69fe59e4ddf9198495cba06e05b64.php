<?php if (!defined('THINK_PATH')) exit();?></head>
<style type="text/css">
	.Gtitle{
		font-size: 15px;
	    font-weight: bold;
	    padding: 5px 0;
	    margin-bottom: 12px;
	    border-bottom: 1px solid #ccc;
	}
</style>
<body class="easyui-layout" >
	<table id="Gtable" style="width:'100%';height:'100%' padding:0px"
		data-options="iconCls:'icon-search'"
		title="年级信息"></table>
	<div id='Gtool' style="padding:7px;">
		<div>
			<a class="easyui-linkbutton" iconCls = "icon-add" plain="true" onclick="Mth.add();">添加</a>
			<a class="easyui-linkbutton" iconCls = "icon-edit" plain="true" id="xg" onclick="Mth.xg();">修改</a>
			<a class="easyui-linkbutton" iconCls = "icon-save" plain="true" id="save" onclick="Mth.save();" style="display:none;">保存</a>
			<a class="easyui-linkbutton" iconCls = "icon-undo" plain="true" id="rollback" onclick="Mth.rollback();" 
			style="display:none;">撤销修改</a>
			<a class="easyui-linkbutton" iconCls = "icon-remove" plain="true"
			onclick="Mth.remove();">删除</a>
			
		</div>
		<form id='G_form'>
			<div >
				查询年级信息:<input type="text" name="gradename"  id='gradename' class="textbox" style="width:110px;">
				<a  class="easyui-linkbutton" iconCls = "icon-search" onclick="Mth.search();">查询</a>
				<a class="easyui-linkbutton" iconCls = 'icon-search'  onclick="Mth.Sall();">查看全部</a>
			</div>

		</form>

	</div>
	<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/source/css/Grade_Manage.css" />
	<div id="AddGrade"  title="添加年级" data-options="iconCls:'icon-save'"  closed="true" style="padding:5px;width:380px;height:250px;">
		<h3 class="Gtitle">添加年级</h3>
		<form id="NewGrade">
			<div class="Gitem">
				 <label> 
               名称</label> 
				<input class="easyui-validatebox textbox" type="text" name="Ggradename" id ="Ggradename"   required="true"/>
				</div>	
			<div class="Gitem">
				<label>备注  </label>
				<input class="easyui-validatebox textbox" type="text" name="Gcomment" id="Gcomment" />
			</div>	
		</form>
	</div>
<script type="text/javascript">
	//加载添加框
	$(function(){
		$('#AddGrade').dialog({
			buttons:[{
				text:'添加',
				iconCls:'icon-add',
				handler:function(){
					addGrade();
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#AddGrade').dialog('close');
				}
			}]
		});
	});
	//添加年级时的提示信息
	$('#Ggradename').validatebox({ 
		required : true, 
		missingMessage : '请输入年级名称',
		invalidMessage : '年级名称不能为空',
	});
	//添加年级时执行
	function addGrade(){
		if ($('#NewGrade').form("validate")){ 
			$.ajax({
				url:'<?php echo U('StuManage_Grade_Add');?>',
				type:'POST',
				data:{
					gradename:$('#Ggradename').val(),
					comment:$('#Gcomment').val(),
				},
				beforeSend : function () { 
					$.messager.progress({
						text : '正在添加...',
					}); 
				},
				success:function(data){
					$.messager.progress('close'); 
					$('#Gtable').datagrid('reload');
					$('#Gtable').datagrid('unselectAll');
					if(data.success){
						$.messager.alert('提示',data.msg,'info');
						$('#AddGrade').dialog('close');
					}else{
						$.messager.alert('提示',data.msg,'info');
					}
				}
			});
		}
	}
		//操作方法的集合
		Mth = {
			editRow:undefined,
			Sall:function(){
				$("#G_form").form('reset');
				$('#Gtable').datagrid('load',{});
			},
			add:function(){
				$("#NewGrade").form("clear");
				$('#AddGrade').dialog('open');
			},
			search:function(){
				$('#Gtable').datagrid('load',{
					name:$.trim($('input[name=gradename]').val()),
				});
			},
			xg:function(){
				var rows = $('#Gtable').datagrid('getSelections');
				var index = $('#Gtable').datagrid('getRowIndex',rows[0]);
				if(rows.length==1){
					if (this.editRow!=index){
			    		$('#rollback,#save').show();
			    		$('#xg').hide();
						$('#Gtable').datagrid('endEdit', this.editRow);
					}
					if(this.editRow==undefined){
						$('#rollback,#save').show();
						$('#xg').hide();
						$('#Gtable').datagrid('beginEdit', index);
						this.editRow = index;
					}
				}else{
					$.messager.alert('警告','修改必须或只能选择一行！','warning');
				}
			},
			save:function(){
				$('#save,#rollback').hide();
				$('#xg').show();
				$('#Gtable').datagrid('endEdit', this.editRow);
			},
			rollback:function(){
				$('#rollback').hide();
				$('#save').hide();
				$('#xg').show();
				$('#Gtable').datagrid('rejectChanges');
				Mth.editRow = undefined;
			},
			remove:function(){
				var rows = $('#Gtable').datagrid('getSelections');
				if(rows.length>0){
					$.messager.confirm('确定操作','删除之后将不可恢复，是否确认？',
					function(flag){
						if(flag){
							var id=rows[0].grade_id;
							$.ajax({
								url:'<?php echo U('StuManage_Grade_Delete');?>',
								type:'POST',
								data:{
									grade_id:id,
								},
								beforeSend:function(){
									$('#Gtable').datagrid('loading');
								},
								success:function(data){
									$.messager.progress('close'); 
									if(data.success==true){
										$('#Gtable').datagrid('loaded');
										//刷当前前页
										$('#Gtable').datagrid('load');
										$('#Gtable').datagrid('unselectAll');
										$.messager.alert('提示','删除成功！','info');
									}else{
										alert('删除失败,请重新尝试！');
									}

								}
							});
						}
					});
				}else{
					$.messager.alert('提示','请选择要删除的记录！','info');
				}
			},
		};
		//设置数据表格
		$(function(){
			var lastIndex;
			$('#Gtable').datagrid({
		    height: '100%',
		    url:'<?php echo U('StuManage_Gradelist');?>',
		    method: 'GET',
		    striped: true,
		    fitColumns: true,
		    singleSelect: true,
		    rownumbers: false,
		    pagination: true,
		    nowrap: false,
		    fit:true,
		    showFooter: true,
		    pageNumber:1,
		    loadMsg: "正在努力为您加载数据",
		    toolbar: '#Gtool',
		    columns:[[
		        {
		        	field: 'grade_id', 
		        	title: '年级编号', 
		        	width: '25%', 
		        	align: 'center',
		        	checkbox:true, 
		        },
		        {
		        	field: 'name', 
		        	title: '年级名称', 
		        	width: '50%', 
		        	align: 'center',
		    		editor:{
		        		type:'text'
		        	}
		    	},
		        {
		        	field: 'comment',
		        	title: '备注', 
		        	width: '50%', 
		        	align: 'center', 	
		        	editor:{
		        		type:'text'
		        	}
		        },
		    ]],
		    onDblClickRow:function(rowIndex){
				if (Mth.editRow==undefined){
			    		$('#rollback,#save').show();
			    		$('#xg').hide();
						$('#Gtable').datagrid('beginEdit', rowIndex);
						Mth.editRow = rowIndex;
					}
					if(Mth.editRow!=rowIndex){
						$('#xg').hide();
						$('#rollback,#save').show();
						$('#Gtable').datagrid('endEdit', Mth.editRow);
						$('#Gtable').datagrid('beginEdit', rowIndex);
						Mth.editRow = rowIndex;
				}
			},
			onAfterEdit:function(rowIndex,rowData,changes){
			    var updated = $('#Gtable').datagrid('getChanges','updated');
			    //更新操作数据验证
			    if(updated.length>0){
				    $.ajax({
				    	url:'<?php echo U('StuManage_Grade_Amend');?>',
						type:'POST',
						data:{
							grade_id:updated[0].grade_id,
							name:updated[0].name,
							comment:updated[0].comment,
							},
						beforeSend:function(){
							$('#Gtable').datagrid('loading');
						},
						success:function(data){
							$.messager.progress('close'); 
							$('#Gtable').datagrid('loaded');
								//刷当前前页
							$('#Gtable').datagrid('load');
							$('#Gtable').datagrid('unselectAll');
							if(data.success){
								$.messager.alert('提示','修改成功！','info');
							}else{
								$.messager.alert('提示',data.msg,'info');
							}
							$('#save,#rollback').hide();
							$('#xg').show();
						}
				    });
			    }
			    Mth.editRow = undefined;
			},
		    onLoadError: function (){
		    	$.messager.alert('提示','数据加载出现错误','info');
		    },
			});
		});
		//设置分页控件
		var p=$("#Gtable").datagrid('getPager');
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