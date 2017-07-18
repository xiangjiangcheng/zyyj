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
		title="系统参数信息"></table>
	<div id='Gtool' style="padding:7px;">
		<div>
			<a class="easyui-linkbutton" iconCls = "icon-edit" plain="true" id="xg" onclick="Mth.xg();">修改</a>
			<a class="easyui-linkbutton" iconCls = "icon-save" plain="true" id="save" onclick="Mth.save();" style="display:none;">保存</a>
			<a class="easyui-linkbutton" iconCls = "icon-undo" plain="true" id="rollback" onclick="Mth.rollback();" 
			style="display:none;">撤销修改</a>
		</div>

	</div>
<script type="text/javascript">
	//操作方法的集合
	Mth = {
		editRow:undefined,
		xg:function(){
			var rows = $('#Gtable').datagrid('getSelections');//得到选中的行
			var index = $('#Gtable').datagrid('getRowIndex',rows[0]);//返回指定行的索引
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
	};
	//设置数据表格
	$(function(){
		var lastIndex;
		$('#Gtable').datagrid({
	    height: '100%',
	    url:'<?php echo U('SystemManage_SystemParameter_list_show');?>',
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
	        	field: 'parameter_id', 
	        	title: '参数编号', 
	        	width: '25%', 
	        	align: 'center',
	        	checkbox:true, 
	        },
	        {
	        	field: 'introduction', 
	        	title: '功能', 
	        	width: '50%', 
	        	align: 'center',
	    	},
	        {
	        	field: 'value',
	        	title: '值', 
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
			    	url:'<?php echo U('SystemManage_SystemParameter_edit');?>',
					type:'POST',
					data:{
						parameter_id:updated[0].parameter_id,
						value:updated[0].value,
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