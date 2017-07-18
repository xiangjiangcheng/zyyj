$(function () {
	//难度查询条件
	$('#searchlevel').combobox({
		url : '../Question/Question_get_level',
		valueField:'level_id',
		textField:'name',
		editable: true, //是否可以编辑
	});
	
	//全部知识点
	$('#searchknow').combobox({
		url : '../Question/Question_get_know',
		valueField:'know_id',
		textField:'name',
		editable: true, //是否可以编辑
		onSelect: function (rec) {
			var searchchapter_val = ''+$('input[name="searchchapter"]').val();
			if (searchchapter_val == ''||searchchapter_val=="") {
				$('#searchknow').combobox('setValue', '');
				$.messager.alert("操作提示", "请先选择章节！","warning");
			}
		}
	});
	//全部章节
	$('#searchchapter').combobox({
		url : '../Question/Question_get_chapter',
		valueField:'chapter_id',
		textField:'name',
		editable: true, //是否可以编辑
		onSelect: function (rec) {
			var chapter_id=$("#searchchapter").combobox('getValue');
			var searchcourse_val = ''+$('input[name="searchcourse"]').val();
			if (searchcourse_val == ''||searchcourse_val=="") {
				$('#searchchapter').combobox('setValue', '');
				$.messager.alert("操作提示", "请先选择科目！","warning");
			}else{
				$.ajax({
                type: "POST",
                url: '../Question/Question_get_know',
                cache: false,
                dataType: "json",
                data : {
                	chapter_id : chapter_id,
                },
                beforeSend : function () {
					$.messager.progress({
						text : '正在加载知识点中...',
					});
				},
                success: function (data) {
                	$.messager.progress('close');
                	if (data.know == "" || data.know == "[]"||data.know==null) {

                		$.messager.alert("操作提示", "没有找到符合条件的知识点！","warning");
                		$("#searchchapter").combobox("setValue", '');
                	}else{
                		$("#searchknow").combobox('clear');
                		$("#searchknow").combobox("loadData", data.know);
                	}
                	
                	
                }
            });
			}
		}
	});
	//科目查询条件
	$('#searchcourse').combobox({
		url : '../Question/Question_get_course',
		valueField:'course_id',
		textField:'name',
		editable: true, //是否可以编辑
		//下拉框选中事件
		onSelect: function (rec) {
			console.log(rec);
			var course_id=$("#searchcourse").combobox('getValue');
			$.ajax({
                type: "POST",
                url: '../Question/Question_get_chapter',
                cache: false,
                dataType: "json",
                data : {
                	course_id : course_id,
                },
                beforeSend : function () {
					$.messager.progress({
						text : '正在加载章节中...',
					});
				},
                success: function (data) {
                	$.messager.progress('close');
                	if (data.chapter == "" || data.chapter == "[]"||data.chapter==null) {

                		$.messager.alert("操作提示", "没有找到符合条件的章节！","warning");
                		$("#searchcourse").combobox("setValue", '');
                	}else{
                		$("#searchchapter").combobox('clear');
                		$("#searchchapter").combobox("loadData", data.chapter);
                	}
                	
                	
                }
            });
		},
	});
	//难度查询条件
	$('#addlevel').combobox({
		url : '../Question/Question_get_level',
		valueField:'level_id',
		textField:'name',
		editable: true, //是否可以编辑
	});
	//全部知识点
	$('#addofknow').combobox({
		url : '../Question/Question_get_know',
		valueField:'know_id',
		textField:'name',
		editable: true, //是否可以编辑
		onSelect: function (rec) {
			var addchapter_val = ''+$('input[name="addchapter"]').val();
			if (addchapter_val == ''||addchapter_val=="") {
				$('#addofknow').combobox('setValue', '');
				$.messager.alert("操作提示", "请先选择章节！","warning");
			}
		}
	});
	//全部章节
	$('#addchapter').combobox({
		url : '../Question/Question_get_chapter',
		valueField:'chapter_id',
		textField:'name',
		editable: true, //是否可以编辑
		onSelect: function (rec) {
			var chapter_id=$("#addchapter").combobox('getValue');
			var addcourse_val = ''+$('input[name="addcourse"]').val();
			if (addcourse_val == ''||addcourse_val=="") {
				$('#addchapter').combobox('setValue', '');
				$.messager.alert("操作提示", "请先选择科目！","warning");
			}else{
				$.ajax({
                type: "POST",
                url: '../Question/Question_get_know',
                cache: false,
                dataType: "json",
                data : {
                	chapter_id : chapter_id,
                },
                beforeSend : function () {
					$.messager.progress({
						text : '正在加载知识点中...',
					});
				},
                success: function (data) {
                	$.messager.progress('close');
                	if (data.know == "" || data.know == "[]"||data.know==null) {

                		$.messager.alert("操作提示", "没有找到符合条件的知识点！","warning");
                		$("#addchapter").combobox("setValue", '');
                	}else{
                		$("#addofknow").combobox('clear');
                		$("#addofknow").combobox("loadData", data.know);
                	}
                	
                	
                }
            });
			}
		}
	});
	//科目查询条件
	$('#addcourse').combobox({
		url : '../Question/Question_get_course',
		valueField:'course_id',
		textField:'name',
		editable: true, //是否可以编辑
		//下拉框选中事件
		onSelect: function (rec) {
			console.log(rec);
			var course_id=$("#addcourse").combobox('getValue');
			$.ajax({
                type: "POST",
                url: '../Question/Question_get_chapter',
                cache: false,
                dataType: "json",
                data : {
                	course_id : course_id,
                },
                beforeSend : function () {
					$.messager.progress({
						text : '正在加载章节中...',
					});
				},
                success: function (data) {
                	$.messager.progress('close');
                	if (data.chapter == "" || data.chapter == "[]"||data.chapter==null) {

                		$.messager.alert("操作提示", "没有找到符合条件的章节！","warning");
                		$("#addcourse").combobox("setValue", '');
                	}else{
                		$("#addchapter").combobox('clear');
                		$("#addchapter").combobox("loadData", data.chapter);
                	}
                	
                	
                }
            });
		},
	});
	//全部知识点
	$('#importknow').combobox({
		url : '../Question/Question_get_know',
		valueField:'know_id',
		textField:'name',
		editable: true, //是否可以编辑
		onSelect: function (rec) {
			var importchapter_val = ''+$('input[name="importchapter"]').val();
			if (importchapter_val == ''||importchapter_val=="") {
				$('#importknow').combobox('setValue', '');
				$.messager.alert("操作提示", "请先选择章节！","warning");
			}
		}
	});
	//全部章节
	$('#importchapter').combobox({
		url : '../Question/Question_get_chapter',
		valueField:'chapter_id',
		textField:'name',
		editable: true, //是否可以编辑
		onSelect: function (rec) {
			var chapter_id=$("#importchapter").combobox('getValue');
			var importcourse_val = ''+$('input[name="importcourse"]').val();
			if (importcourse_val == ''||importcourse_val=="") {
				$('#importchapter').combobox('setValue', '');
				$.messager.alert("操作提示", "请先选择科目！","warning");
			}else{
				$.ajax({
                type: "POST",
                url: '../Question/Question_get_know',
                cache: false,
                dataType: "json",
                data : {
                	chapter_id : chapter_id,
                },
                beforeSend : function () {
					$.messager.progress({
						text : '正在加载知识点中...',
					});
				},
                success: function (data) {
                	$.messager.progress('close');
                	if (data.know == "" || data.know == "[]"||data.know==null) {

                		$.messager.alert("操作提示", "没有找到符合条件的知识点！","warning");
                		$("#importchapter").combobox("setValue", '');
                	}else{
                		$("#importknow").combobox('clear');
                		$("#importknow").combobox("loadData", data.know);
                	}
                	
                	
                }
            });
			}
		}
	});
	//科目查询条件
	$('#importcourse').combobox({
		url : '../Question/Question_get_course',
		valueField:'course_id',
		textField:'name',
		editable: true, //是否可以编辑
		//下拉框选中事件
		onSelect: function (rec) {
			console.log(rec);
			var course_id=$("#importcourse").combobox('getValue');
			$.ajax({
                type: "POST",
                url: '../Question/Question_get_chapter',
                cache: false,
                dataType: "json",
                data : {
                	course_id : course_id,
                },
                beforeSend : function () {
					$.messager.progress({
						text : '正在加载章节中...',
					});
				},
                success: function (data) {
                	$.messager.progress('close');
                	if (data.chapter == "" || data.chapter == "[]"||data.chapter==null) {

                		$.messager.alert("操作提示", "没有找到符合条件的章节！","warning");
                		$("#importcourse").combobox("setValue", '');
                	}else{
                		$("#importchapter").combobox('clear');
                		$("#importchapter").combobox("loadData", data.chapter);
                	}
                	
                	
                }
            });
		},
	});
	//加载表格数据
	$('#question_list').datagrid({    
	    url:'../Question/Question_get_data',    
	    columns:[[    
	        {field:'question',title:'题目',width:350},    
	        {field:'level',title:'难度',width:50}, 
	        {field:'course',title:'科目',width:50}, 
	        {field:'chapter',title:'章节',width:100},  
	        {field:'ofknow',title:'知识点',width:150},   
	        {field:'rightanswer',title:'正确答案',width:200},
	        {field:'caozuo',title:'操作',width:80},
	    ]],
	    toolbar: [
	    	{iconCls: 'icon-add',text:'新增',handler: function(){
	    		document.getElementById('photo_row').hidden=true;
	    		$('#question_add').dialog({
     				modal:true
				});
	    	}},
	    	{iconCls: 'icon-edit',text:'修改',handler: function(){
	    		var row = $('#question_list').datagrid('getSelected');
	    		if(row==null){
	    			alert("请先选择行");return;
	    		}
	    		$.ajax({
					async : true,
					cache : false,
					type : 'get',
					url : '../Question/Question_get_detail',
					contentType: "application/json",
					data : {
						id:row.id,
					},
					dataType:'json',
					success : function(data, textStatus, jqXHR) {
						if(data.photo!=null&&data.photo!=""){
							document.getElementById('photo_row').hidden=false;
						}
						if(data.code == 1){
							document.getElementById("addquestion").value=data.question;
							document.getElementById("question_id").value=data.id;
							$("#addlevel").combobox('setValue',data.level_id);
							$("#addofknow").combobox('setValue',data.know_id);
							$("#addcourse").combobox('setValue',data.course_id);
							$("#addchapter").combobox('setValue',data.chapter_id);
							document.getElementById("addanswer1").value=data.answer1;
							document.getElementById("addanswer2").value=data.answer2;
							document.getElementById("addanswer3").value=data.answer3;
							document.getElementById("addanswer4").value=data.answer4;
							document.getElementById("addrightanswer").value=data.rightanswer;
							document.getElementById("addphoto").src='../../../source/'+data.photo;
							document.getElementById("addcomment").value=data.comment;
							$('#question_add').dialog({
	     						modal:true
							});		
						}else if(data.code==2){
							alert("题目不存在");
						}
					}
				});
	    	}},
			{iconCls: 'icon-remove',text:'删除',handler: function(){
				var row = $('#question_list').datagrid('getSelected');
	    		if(row==null){
	    			alert("请先选择行");return;
	    		}
	    		if(!confirm("确认删除?")){
		    		return;
		    	}
				$.ajax({
					async : true,
					cache : false,
					type : 'get',
					url : '../Question/Question_delete',
					contentType: "application/json",
					data : {
						id:row.id,
					},
					dataType:'json',
					success : function(data, textStatus, jqXHR) {
						if (data.code) {
				            $.messager.alert("提示信息", "删除成功");
				            $("#question_list").datagrid("load");
				        }else{
				            $.messager.alert("提示信息", "删除失败");
				        }
					}
				});	
			}},
			{iconCls: 'icon-redo',text:'取消选中',handler: function(){
				$("#question_list").datagrid("clearSelections");
			}},
			{iconCls: 'icon-large-shapes',text:'导入',handler: function(){
				$('#question_imp').dialog({
     				modal:true
				});
			}},
		],
		pagination:true,
		rownumbers:true,
		singleSelect:true,
		checkOnSelect:true,
		pagePosition:'bottom',
		pageNumber:1,
		pageSize:20,
		fit : true,
        fitColumns : true,
	});
});
//提交表单
function submit_form(){
	question=document.getElementById('addquestion').value;
	rightanswer=document.getElementById('addrightanswer').value;
	level=$("#addlevel").combobox('getValue');
	know=$("#addofknow").combobox('getValue');
	course=$("#addcourse").combobox('getValue');
	chapter=$("#addchapter").combobox('getValue');
	answer1=document.getElementById('addanswer1').value;
	if(question==null||question==""){
		alert("题目不能为空");return;
	}else if(answer1==null||answer1==""){
		alert("答案1不能为空");return;
	}else if(rightanswer==null||rightanswer==""){
		alert("正确答案不能为空");return;
	}else if(level==null||level==""){
		alert("题目难度不能为空");return;
	}else if(course==null||course==""){
		alert("题目科目不能为空");return;
	}else if(chapter==null||chapter==""){
		alert("题目章节不能为空");return;
	}else if(know==null||know==""){
		alert("所属知识点不能为空");return;
	}
	$("#add_question").form("submit", {
        url: '../Question/Question_add',
        success: function(result) {
            if (result) {
	            $.messager.alert("提示信息", "操作成功");
	            $("#question_add").dialog("close");
	            $("#question_list").datagrid("load");
	        }else{
	            $.messager.alert("提示信息", "操作失败");
	        }
	    }
	});
}
//提交导入
function sub_import(){
	know=$("#importknow").combobox('getValue');
	chapter=$("#importchapter").combobox('getValue');
	course=$("#importcourse").combobox('getValue');
	if(course==null||course==""){
		alert("题目类型不能为空");return;
	}else if(chapter==null||chapter==""){
		alert("所属章节不能为空");return;
	}else if(know==null||know==""){
		alert("所属知识点不能为空");return;
	}
	$("#imp_question").form("submit", {
        url: '../Question/Question_import',
        success: function(result) {
        	console.log("result"+result);
            if (result) {
            	alert("成功导入"+result+"条数据");
	            $("#question_imp").dialog("close");
	            $("#question_list").datagrid("load");
	        }else{
	            $.messager.alert("提示信息", "操作失败");
	        }
	    }
	});
}
//查看详情
function stepToDetail(id){
	$.ajax({
			async : true,
			cache : false,
			type : 'get',
			url : '../Question/Question_get_detail',
			contentType: "application/json",
			data : {
				id:id,
			},
			dataType:'json',
			success : function(data, textStatus, jqXHR) {
				if(data.code == 1){
					if(data.photo==null||data.photo==""){
						document.getElementById("look_photo").hidden=true;
					}else{
						document.getElementById("look_photo").hidden=false;
					}
					document.getElementById("question").value=data.question;
					document.getElementById("level").value=data.level;
					document.getElementById("ofknow").value=data.ofknow;
					document.getElementById("course").value=data.course;
					document.getElementById("chapter").value=data.chapter;
					document.getElementById("answer1").value=data.answer1;
					document.getElementById("answer2").value=data.answer2;
					document.getElementById("answer3").value=data.answer3;
					document.getElementById("answer4").value=data.answer4;
					document.getElementById("rightanswer").value=data.rightanswer;
					document.getElementById("photo").src='../../../Uploads/'+data.photo;
					document.getElementById("comment").value=data.comment;
					$('#question_detail').dialog({
 						modal:true
					});		
				}else if(data.code==2){
					alert("题目不存在");
				}
			}
		});	
}
//查询
function search(){
	level_id=$("#searchlevel").combobox('getValue');
	know_id=$("#searchknow").combobox('getValue');
	chapter_id=$("#searchchapter").combobox('getValue');
	course_id=$("#searchcourse").combobox('getValue');
	url='../Question/Question_get_data?search=1';
	if(level_id!=null&&level_id!=""){
		url=url+"&level_id="+level_id;
	}
	if(know_id!=null&&know_id!=""){
		url=url+"&know_id="+know_id;
	}
	if(chapter_id!=null&&chapter_id!=""){
		url=url+"&chapter_id="+chapter_id;
	}
	if(course_id!=null&&course_id!=""){
		url=url+"&course_id="+course_id;
	}
	$('#question_list').datagrid({    
	    url:url,    
	    columns:[[    
	        {field:'question',title:'题目',width:350},    
	        {field:'level',title:'难度',width:50}, 
	        {field:'course',title:'科目',width:50}, 
	        {field:'chapter',title:'章节',width:100},  
	        {field:'ofknow',title:'知识点',width:150},   
	        {field:'rightanswer',title:'正确答案',width:200},
	        {field:'caozuo',title:'操作',width:80},
	    ]],
	    toolbar: [
	    	{iconCls: 'icon-add',text:'新增',handler: function(){
	    		document.getElementById('photo_row').hidden=true;
	    		$('#question_add').dialog({
     				modal:true
				});
	    	}},
	    	{iconCls: 'icon-edit',text:'修改',handler: function(){
	    		var row = $('#question_list').datagrid('getSelected');
	    		if(row==null){
	    			alert("请先选择行");return;
	    		}
	    		$.ajax({
					async : true,
					cache : false,
					type : 'get',
					url : '../Question/Question_get_detail',
					contentType: "application/json",
					data : {
						id:row.id,
					},
					dataType:'json',
					success : function(data, textStatus, jqXHR) {
						if(data.photo!=null&&data.photo!=""){
							document.getElementById('photo_row').hidden=false;
						}
						console.log(data.level_id);
						if(data.code == 1){
							document.getElementById("addquestion").value=data.question;
							document.getElementById("question_id").value=data.id;
							$("#addlevel").combobox('setValue',data.level_id);
							$("#addofknow").combobox('setValue',data.know_id);
							$("#addchapter").combobox('setValue',data.chapter_id);
							$("#addcourse").combobox('setValue',data.course_id);
							document.getElementById("addanswer1").value=data.answer1;
							document.getElementById("addanswer2").value=data.answer2;
							document.getElementById("addanswer3").value=data.answer3;
							document.getElementById("addanswer4").value=data.answer4;
							document.getElementById("addrightanswer").value=data.rightanswer;
							document.getElementById("addphoto").src='__SITE__Uploads/'+data.photo;
							document.getElementById("addcomment").value=data.comment;
							$('#question_add').dialog({
	     						modal:true
							});		
						}else if(data.code==2){
							alert("题目不存在");
						}
					}
				});
	    	}},
			{iconCls: 'icon-remove',text:'删除',handler: function(){
				var row = $('#question_list').datagrid('getSelected');
	    		if(row==null){
	    			alert("请先选择行");return;
	    		}
	    		if(!confirm("确认删除?")){
		    		return;
		    	}
	    		console.log(row);
				$.ajax({
					async : true,
					cache : false,
					type : 'get',
					url : '../Question/Question_delete',
					contentType: "application/json",
					data : {
						id:row.id,
					},
					dataType:'json',
					success : function(data, textStatus, jqXHR) {
						if (data.code) {
				            $.messager.alert("提示信息", "删除成功");
				            $("#question_list").datagrid("load");
				        }else{
				            $.messager.alert("提示信息", "删除失败");
				        }
					}
				});	
			}},
			{iconCls: 'icon-redo',text:'取消选中',handler: function(){
				$("#question_list").datagrid("clearSelections");
			}},
			{iconCls: 'icon-large-shapes',text:'导入',handler: function(){
				$('#question_imp').dialog({
     				modal:true
				});
			}},
		],
		pagination:true,
		rownumbers:true,
		singleSelect:true,
		checkOnSelect:true,
		pagePosition:'bottom',
		pageNumber:1,
		pageSize:20,
		fit : true,
        fitColumns : true,
	});
}
//查询全部	
function searchall(){
	//去掉选中的值
	$('#searchlevel').combobox('clear'); 
	$('#searchknow').combobox('clear'); 
	$('#searchchapter').combobox('clear'); 
	$('#searchcourse').combobox('clear'); 
	//去掉文本框中显示的值
	document.getElementById("searchlevel").value="";
	document.getElementById("searchknow").value="";
	document.getElementById("searchchapter").value="";
	document.getElementById("searchcourse").value="";
	//调用查询方法，此处查询条件为空，所以查询的是所有数据
	search();
}


