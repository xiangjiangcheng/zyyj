$(function () {
	$.extend($.fn.validatebox.defaults.rules, {
		comboGender: {
	        validator: function (value, param) {//param为默认值
	        	alert(value+'|'+param);
	        	//校验是否为空
	            return value.length >= param;
	        },
	        message: '请选择性别'
	    }
	});
	
	//获取存放后台传过来的学院id
	var pub_college_id = $('#college_id').val();
//	var pub_college_id = '';
	//根据pub_college_id 是否有值判断隐藏或显示哪些功能
	if (pub_college_id != '') {
		//1.隐藏学院查询条件
		$('#college_id_find_span').hide();
		$('#major_id_find_span').attr("class","");
		//2.隐藏添加表单中的学院
//		$('#college_id_add_p').hide();
		//3.隐藏修改表单中的学院
//		$('#college_id_edit_p').hide();
		
	} else {
		pub_college_id = '';
		//1.显示学院查询条件
		$('#college_id_find_span').show();
		//2.显示添加表单中的学院
		$('#college_id_add_p').show();
		//3.显示修改表单中的学院
		$('#college_id_edit_p').show();
	}
	
	//加载该页面时  先加载相关数据 并填充
	$.ajax({
		url : ''+Student_getAllSearchData_url,
		type : 'post',
		dataType : 'json',
		data : {
		},
		success : function (data, response, status) {
//			$.messager.progress('close');
			//填充查询条件项
			$('#class_id_find').combobox({
				data : data.class,
				valueField:'class_id',
				textField:'name',
				editable: false, //是否可以编辑
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].indexOf(q) >= 0;
				},
				//下拉框选中事件
				onSelect: function (n,o) {
					//获取年级   若年级为空，提示先选年级 才能选班级
					//获取班级id
					var grade_id_find_value = ''+$('input[name="grade_id_find"]').val();
					if (grade_id_find_value == '') {
						//班级值置空
						$('#class_id_find').combobox('setValue', '');
//						alert("请先选择年级项!");
						$.messager.alert("操作提示", "请先选择年级项！","warning");
					} else {
//						alert("ok!");
					}
				},
			});
			
			$('#major_id_find').combobox({
				data : data.major,
				valueField:'major_id',
				textField:'name',
				editable: false, //是否可以编辑
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].indexOf(q) >= 0;
				},
			});
			$('#grade_id_find').combobox({
				data : data.grade,
				valueField:'grade_id',
				textField:'name',
				editable: false, //是否可以编辑
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].indexOf(q) >= 0;
				},
			});
			$('#college_id_find').combobox({
				data : data.college,
				valueField:'college_id',
				textField:'name',
				editable: false, //是否可以编辑
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].indexOf(q) >= 0;
				},
			});
			$('#name_find').combobox({
				data : data.rows,
				valueField:'stu_id',
				textField:'name',
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].indexOf(q) >= 0;
				},
			});
			$('#account_find').combobox({
				data : data.rows,
				valueField:'stu_id',
				textField:'account',
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].indexOf(q) >= 0;
				},
			});
			
			//填充添加框中下拉框信息
			$('#gender_add').combobox({
				data : [{"gender_id":"0","text":"女"},{"gender_id":"1","text":"男"}],
				valueField:'gender_id',
				textField:'text',
				editable: false, //是否可以编辑
				required:true,
				missingMessage: '请选择性别',
//				validType:['comboGender',1],
			});
			$('#college_id_add').combobox({
				data : data.college,
				valueField:'college_id',
				textField:'name',
				editable: false, //是否可以编辑
//				required:true,
				onSelect: function () {
					/**
					 * 获取该学院 下面专业的值
					 */
                    var college_id_add = $('#college_id_add').combobox('getValue');
                    $.ajax({
                        type: "POST",
                        url: Student_getMajorByCollegeId_url,
                        cache: false,
                        dataType: "json",
                        data : {
                        	college_id : college_id_add,
                        },
                        beforeSend : function () {
							$.messager.progress({
								text : '正在加载专业中...',
							});
						},
                        success: function (data) {
                        	$.messager.progress('close');
                        	//重新填充 专业 combobox 数据
                        	if (data.major == "" || data.major == "[]") {
                        		$.messager.alert("操作提示", "该学院下面没有相关专业！","warning");
                        	}
                        	$("#major_id_add").combobox('clear');
                        	$("#major_id_add").combobox("loadData", data.major);
                        	
                        }
                    });
                }
			});
			if (pub_college_id != '') {
				//设置学院的值
//				$('#college_id_add').combobox('setValue', pub_college_id);
//				$('#college_id_add').combobox('disable');   //不可用
				//获取该学院下的专业
				$.ajax({
                    type: "POST",
                    url: Student_getMajorByCollegeId_url,
                    cache: false,
                    dataType: "json",
                    data : {
                    	college_id : pub_college_id,
                    },
                    success: function (data) {
                    	$('#major_id_add').combobox({
        					data : data.major,
        					valueField:'major_id',
        					textField:'name',
        					editable: false, //是否可以编辑
        					//下拉框选中事件
        					onSelect: function (n,o) {
        						//年级当前值设置为空    班级数据彻底清空
        						$("#grade_id_add").combobox("setValue", '');
    							$("#class_id_add").combobox('clear');
    							$("#class_id_add").combobox("loadData", '');
        						//获取学院   若学院为空，提示先选学院 才能选专业
        						//获取学院id
        						var college_id_add_value = ''+$('input[name="college_id_add"]').val();
        						if (college_id_add_value == '') {
        							//值 置空
        							$('#major_id_add').combobox('setValue', '');
        							$.messager.alert("操作提示", "请先选择学院项！","warning");
        						} else {
//        							alert("ok!");
        						}
        					},
        				});
                    }
                });
				
			} else {
				$('#major_id_add').combobox({
					data : '',
					url : '',
					valueField:'major_id',
					textField:'name',
					editable: false, //是否可以编辑
					//下拉框选中事件
					onSelect: function (n,o) {
						//获取学院   若学院为空，提示先选学院 才能选专业
						//获取学院id
						var college_id_add_value = ''+$('input[name="college_id_add"]').val();
						if (college_id_add_value == '') {
							//值 置空
							$('#major_id_add').combobox('setValue', '');
//							alert("请先选择学院项!");
							$.messager.alert("操作提示", "请先选择学院项！","warning");
						} else {
//							alert("ok!");
						}
					},
				});
			}
			$('#grade_id_add').combobox({
				data : data.grade,
				valueField:'grade_id',
				textField:'name',
				editable: false, //是否可以编辑
//				onHidePanel
				onSelect: function () {
					
					//获取专业id  先选择专业  才能选择年级
					var major_id_add_value = ''+$('input[name="major_id_add"]').val();
					if (major_id_add_value == '') {
						//年级值置空
						$('#grade_id_add').combobox('setValue', '');
						$.messager.alert("操作提示", "请先选择专业项！","warning");
					} else {
						/**
						 * 获取该年级 下面班级的值
						 */
//	                    $("#class_id_add").combobox("setValue", '');
//						debugger
	                    var grade_id_add = $('#grade_id_add').combobox('getValue');
	                    var major_id_add = $('#major_id_add').combobox('getValue');
	                    var url = Student_getClassByGradeAndMajorId_url+'?grade_id='+grade_id_add;
//	                    $("#class_id_add").combobox('clear');
//	                    $("#class_id_add").combobox('reload', url);
	                    
	                    $.ajax({
	                        type: "POST",
	                        url: Student_getClassByGradeAndMajorId_url,
	                        cache: false,
	                        dataType: "json",
	                        data : {
	                        	grade_id : grade_id_add,
	                        	major_id : major_id_add,
	                        },
	                        beforeSend : function () {
								$.messager.progress({
									text : '正在加载班级中...',
								});
							},
	                        success: function (data) {
	                        	$.messager.progress('close');
	                        	//重新填充 班级 combobox 数据
	                        	if (data.class == "" || data.class == "[]") {
	                        		$.messager.alert("操作提示", "没有找到符合条件的班级信息！","warning");
	                        		//重置年级
	                        		$("#grade_id_add").combobox("setValue", '');
	                        	}
	                        	$("#class_id_add").combobox('clear');
	                        	$("#class_id_add").combobox("loadData", data.class);
	                        	
	                        }
	                    });
					}
					
                }
			});
			
			
			$('#class_id_add').combobox({
//				data : '',
				url : ''+Student_getClassByGradeAndMajorId_url,
				valueField:'class_id',
				textField:'name',
				editable: false, //是否可以编辑
				required:true,
				//下拉框选中事件
				onSelect: function (n,o) {
					//获取年级   若年级为空，提示先选年级 才能选班级
					//获取班级id
					var grade_id_add_value = ''+$('input[name="grade_id_add"]').val();
					if (grade_id_add_value == '') {
						//班级值置空
						$('#class_id_add').combobox('setValue', '');
						$.messager.alert("操作提示", "请先选择年级项！","warning");
					} else {
//						alert("ok!");
					}
				},
			});
		}
	});
	
	$('#student').datagrid({
		url : ''+Student_getAllStudent_url,
		fit : true,
		fitColumns : true,
		striped : true,
		rownumbers : true,
		border : false,
		pagination : true,
		pageSize : 20,
		pageList : [10, 20, 30, 40, 50],
		pageNumber : 1,
		sortName : 'name',
		sortOrder : 'ASC',
		//remoteSort : false,//关闭服务器排序  启动当前排序
		toolbar : '#student_tool',
		columns : [[
			{
				field : 'stu_id',
				title : '学生id',
				width : 100,
				checkbox : true,
			},
			{
				field : 'name',
				title : '姓名',
				width : 60,
				sortable : true,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'gender',
				title : '性别',
				width : 30,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
				formatter : function (value, row, index) {
//					console.log(value);
					return (value==1)?'男':'女'; //三元运算符   显示对应的数据
				},
			},
			{
				field : 'account',
				title : '学号',
				width : 100,
				sortable : true,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'college_name',
				title : '学院',
				width : 100,
				sortable : true,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'major_name',
				title : '专业',
				width : 100,
				sortable : true,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'grade_name',
				title : '年级',
				width : 60,
				sortable : true,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'class_name',
				title : '班级',
				width : 60,
				sortable : true,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'email',
				title : '邮箱',
				width : 100,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
			{
				field : 'phone',
				title : '电话',
				width : 80,
				align : 'center',//整列居中
//				halign : 'center',//标题居中  
			},
		]],
		queryParams : {
			college_id : pub_college_id,
		},
		//数据表格 - 当数据载入成功时触发
		onLoadSuccess : function(data) {
			console.log(data);
			
			
			
			
			
		}
	});
	$('#student_add').dialog({
		width : 350,
		title : '新增管理',
		modal : true,
		closed : true,
		iconCls : 'icon-add',
		buttons : [{
			text : '提交',
			iconCls : 'icon-add',
			handler : function () {
				if ($('#student_add').form('validate')) {
					$.ajax({
						url : ''+Student_addStudent_url,
						type : 'post',
						dataType : 'json',
						data : {
							name : $('input[name="name"]').val(),
							account : $('input[name="account"]').val(),
							password : $('input[name="password"]').val(),
							email : $('input[name="email"]').val(),
							phone : $('input[name="phone"]').val(),
							class_id : $('#class_id_add').combobox('getValue'),
							grade_id : $('#grade_id_add').combobox('getValue'),
							major_id : $('#major_id_add').combobox('getValue'),
							college_id : $('#college_id_add').combobox('getValue'),
							gender : $('#gender_add').combobox('getValue'),
							status : '1',
						},
						beforeSend : function () {
							$.messager.progress({
								text : '正在新增中...',
							});
						},
						success : function (data, response, status) {
							$.messager.progress('close');
//							console.log(data[0]);
//							console.log(data.code);
							if (data.code == 1) {
								$.messager.show({
									title : '提示',
									msg : '新增学生成功！',
								});
								$('#student_add').dialog('close').form('reset');
								//重置(清空)所有的查询条件
								$('#gender_find').combobox('setValue', '-1');
								$('#name_find').combobox('setValue', '');
								$('#account_find').combobox('setValue', '');
//								$('#college_id_find').combobox('setValue', '');
								$('#major_id_find').combobox('setValue', '');
								$('#grade_id_find').combobox('setValue', '');
								$('#class_id_find').combobox('setValue', '');
								$('#student').datagrid('load',{college_id : pub_college_id});
							} else if (data.code == 0) {
								$.messager.alert('新增失败！', data.message+'！请重试！', 'warning');
							} else {
								$.messager.alert('新增失败！', '未知错误导致失败，请重试！', 'warning');
							}
						}
					});
				}
			},
		},{
			text : '取消',
			iconCls : 'icon-redo',
			handler : function () {
				$('#student_add').dialog('close').form('reset');
			},
		}],
	});
	
	$('#student_edit').dialog({
		width : 350,
		title : '修改管理',
		modal : true,
		closed : true,
		iconCls : 'icon-edit',
		buttons : [{
			text : '提交',
			iconCls : 'icon-edit',
			handler : function () {
				//点击提交处理事件
				if ($('#student_edit').form('validate')) {
					//获取
					var college_id = $('#college_id_edit').combobox('getValue');
					var major_id_edit = $('#major_id_edit').combobox('getValue');
					var grade_id = $('#grade_id_edit').combobox('getValue');
					//获取gender:性别
					var gender_edit_value = $(".selector_gender_edit").val();
					//获取class_id:班级
					var class_id = $('#class_id_edit').combobox('getValue');
					if (pub_college_id != '') {
						if (major_id_edit != '') {
							if (grade_id == '' || class_id == '') {
								$.messager.alert('提示：', '已选择专业，年级和班级两项为必填！', 'warning');
								return;
							}
						}
					} else {
						if (college_id != '') {
							if (major_id_edit == '' || grade_id == '' || class_id == '') {
								$.messager.alert('提示：', '已选择学院，专业，年级和班级三项为必填！', 'warning');
								return;
							}
						}
					}
					
					$.ajax({
						url : ''+Student_updateStudent_url,
						type : 'post',
						dataType : 'json',
						data : {
//							stu_id : $('input[name="stu_id"]').val(),
							stu_id : $('#stu_id').val(),
							name : $('input[name="name_edit"]').val(),
							account : $('input[name="account_edit"]').val(),
							password : $('input[name="password_edit"]').val(),
							email : $('input[name="email_edit"]').val(),
							phone : $('input[name="phone_edit"]').val(),
							class_id : $('#class_id_edit').combobox('getValue'),
							grade_id : $('#grade_id_edit').combobox('getValue'),
							major_id : $('#major_id_edit').combobox('getValue'),
							college_id : $('#college_id_edit').combobox('getValue'),
							gender : $('#gender_edit').combobox('getValue'),
							status : '1',
						},
						beforeSend : function () {
							$.messager.progress({
								text : '正在修改中...',
							});
						},
						success : function (data, response, status) {
							$.messager.progress('close');
							
							if (data.code == 1) {
								$.messager.show({
									title : '提示',
									msg : '修改学生成功！',
								});
								$('#student_edit').dialog('close').form('reset');
								$('#student').datagrid('reload');
							} else {
								$.messager.alert('提示', '修改学生失败', 'warning');
							}
						}
					});
				}
			},
		},{
			text : '取消',
			iconCls : 'icon-redo',
			handler : function () {
				$('#student_edit').dialog('close').form('reset');
			},
		}],
	});
	
	$('#student_import').dialog({
		width : 350,
		title : '学生导入',
		modal : true,
		closed : true,
		iconCls : 'icon-large-shapes',
		buttons : [{
			text : '导入',
			iconCls : 'icon-large-shapes',
			handler : function () {
				if ($('#student_import').form('validate')) {
//					var file = $('#import').val(); 
					var str = document.getElementById("import").value;
					if (str.length == 0) {
						$.messager.alert('操作提示！', '请选择导入文件！', 'warning');
						return;
					}
					$("#student_import").form("submit", {
						url : ''+Student_import_url,
						beforeSend : function () {
							$.messager.progress({
								text : '正在导入中...',
							});
						},
						success : function (data, response, status) {
							$.messager.progress('close');
//							console.log(data[0].reaccount);
//							console.log($.parseJSON(data).reaccount);
//							var obj = $.parseJSON(data);
							if ($.parseJSON(data).msg != '') {
								$.messager.alert('导入失败！', '所有数据无法导入！'+$.parseJSON(data).msg, 'warning');
								return;
							}
							if ($.parseJSON(data).reaccount != ''){
								$.messager.alert('导入失败！', '导入文件中第'+$.parseJSON(data).reaccount+'条数据学生信息已存在！导致所有数据无法导入！', 'warning');
							} else {
								if (data) {
									$.messager.show({
										title : '提示',
										msg : "成功导入"+$.parseJSON(data).count+"条数据！！",
									});
									$('#student_import').dialog('close').form('reset');
									//重置(清空)所有的查询条件
									$('#gender_find').combobox('setValue', '-1');
									$('#name_find').combobox('setValue', '');
									$('#account_find').combobox('setValue', '');
//									$('#college_id_find').combobox('setValue', '');
									$('#major_id_find').combobox('setValue', '');
									$('#grade_id_find').combobox('setValue', '');
									$('#class_id_find').combobox('setValue', '');
									$('#student').datagrid('load',{college_id : pub_college_id});
//									$('#student').datagrid('reload');
								} else {
									$.messager.alert('导入失败！', '未知错误导致失败，请重试！', 'warning');
								}
							}
						}
					});
				} else {
					//设置焦点
					$('#class_id_import').focus();
					$.messager.alert('操作提示！', '所有项为必选项！！', 'warning');
					return;
				}
			},
		},{
			text : '取消',
			iconCls : 'icon-redo',
			handler : function () {
				$('#student_import').dialog('close').form('reset');
			},
		}],
	});
	//字段校验
	//添加学生表单
	//学生姓名
	$('input[name="name"]').validatebox({
		required : true,
		validType : 'length[2,20]',
		missingMessage : '请输入学生姓名',
		invalidMessage : '学生姓名在 2-20 位',
	});
	//学生学号
	$('input[name="account"]').validatebox({
		required : true,
		validType : 'length[2,20]',
		missingMessage : '请输入学生学号',
		invalidMessage : '学生学号在 10-20 位',
	});
	//学生密码
	$('input[name="password"]').validatebox({
		required : true,
		validType : 'length[6,30]',
		missingMessage : '请输入学生密码',
		invalidMessage : '学生密码在 6-30 位',
	});
	//学生邮箱
	$('input[name="email"]').validatebox({
		required : false,
		validType : 'email',
		missingMessage : '请输入学生邮箱',
		invalidMessage : '邮箱不合理',
	});
	//学生电话
	$('input[name="phone"]').validatebox({
		required : false,
		validType : 'length[11,11]',
		missingMessage : '请输入学生电话',
		invalidMessage : '联系电话为11位',
	});
	
	//修改学生表单
	//学生姓名 - 修改
	$('input[name="name_edit"]').validatebox({
		required : true,
		validType : 'length[2,20]',
		missingMessage : '请输入学生姓名',
		invalidMessage : '学生姓名在 2-20 位',
	});
	//学生密码  - 修改 
	$('input[name="password_edit"]').validatebox({
		required : true,
		validType : 'length[6,30]',
		missingMessage : '请输入学生密码',
		invalidMessage : '学生密码在 6-30 位',
	});
	//学生邮箱  - 修改
	$('input[name="email_edit"]').validatebox({
		required : false,
		validType : 'email',
		missingMessage : '请输入学生邮箱',
		invalidMessage : '邮箱不合理',
	});
	//学生电话  -  修改
	$('input[name="phone_edit"]').validatebox({
		required : false,
		validType : 'length[11,11]',
		missingMessage : '请输入学生电话',
		invalidMessage : '联系电话为11位',
	});
	
	student_tool = {
		add : function () {
			if (pub_college_id != '') {
				//设置学院的值
				$('#college_id_add').combobox('setValue', pub_college_id);
				$('#college_id_add').combobox('disable');   //不可用
			}
			//显示新增表单
			$('#student_add').dialog('open');
			//设置焦点
			$('input[name="name"]').focus();
		},
		edit : function () {
			var rows = $('#student').datagrid('getSelections');
			if (rows.length > 1) {
				$.messager.alert('警告操作！', '编辑记录只能选定一条数据！', 'warning');
			} else if (rows.length == 1) {
				$.ajax({
					url : ''+Student_getStudentById_url,
					type : 'post',
					data : {
						stu_id : rows[0].stu_id,
					},
					beforeSend : function () {
						$.messager.progress({
							text : '正在获取中...',
						});
					},
					success : function (data, response, status) {
//						debugger
						$.messager.progress('close');
						var obj = $.parseJSON(data);
						console.log(obj.college);
						if (data) {
							//填充修改框中下拉框信息
							$('#gender_edit').combobox({
								data : [{"gender_id":"0","text":"女"},{"gender_id":"1","text":"男"}],
								valueField:'gender_id',
								textField:'text',
								editable: false, //是否可以编辑
								required:true,
							});
							$('#college_id_edit').combobox({
								data : obj.college,
								valueField:'college_id',
								textField:'name',
								editable: false, //是否可以编辑
//								required:true,
								onSelect: function () {
									/**
									 * 获取该学院 下面专业的值
									 */
				                    var college_id_edit = $('#college_id_edit').combobox('getValue');
				                    $.ajax({
				                        type: "POST",
				                        url: Student_getMajorByCollegeId_url,
				                        cache: false,
				                        dataType: "json",
				                        data : {
				                        	college_id : college_id_edit,
				                        },
				                        beforeSend : function () {
											$.messager.progress({
												text : '正在加载专业中...',
											});
										},
				                        success: function (data) {
				                        	$.messager.progress('close');
				                        	console.log(data.major);
//				                        	var obj = $.parseJSON(data);
				                        	//重新填充 专业 combobox 数据
				                        	if (data.major == "" || data.major == "[]") {
//				                        		$.messager.alert('该学院下面没有专业！', data.message+'请重试！', 'warning');
				                        		$.messager.alert("操作提示", "该学院下面没有相关专业！","warning");
				                        	}
				                        	$("#major_id_edit").combobox('clear');
				                        	$("#major_id_edit").combobox("loadData", data.major);
				                        	
				                        }
				                    });
				                }
							});
							if (pub_college_id != '') {
								//设置学院的值
								$('#college_id_edit').combobox('setValue', pub_college_id);
								$('#college_id_edit').combobox('disable');   //不可用
								//获取该学院下的专业
								$.ajax({
				                    type: "POST",
				                    url: Student_getMajorByCollegeId_url,
				                    cache: false,
				                    dataType: "json",
				                    data : {
				                    	college_id : pub_college_id,
				                    },
				                    success: function (data) {
				                    	$('#major_id_edit').combobox({
				        					data : data.major,
				        					valueField:'major_id',
				        					textField:'name',
				        					editable: false, //是否可以编辑
				        					//下拉框选中事件
				        					onSelect: function (n,o) {
				        						//年级当前值设置为空    班级数据彻底清空
				        						$("#grade_id_edit").combobox("setValue", '');
				    							$("#class_id_edit").combobox('clear');
				    							$("#class_id_edit").combobox("loadData", '');
				        					},
				        				});
				                    }
				                });
							} else {
								$('#major_id_edit').combobox({
									data : '',
									url : ''+Student_getMajorByCollegeId_url,
									valueField:'major_id',
									textField:'name',
									editable: false, //是否可以编辑
									//下拉框选中事件
									onSelect: function (n,o) {
										//获取学院   若学院为空，提示先选学院 才能选专业
										//获取学院id
										var college_id_edit_value = ''+$('input[name="college_id_edit"]').val();
										if (college_id_edit_value == '') {
											//值 置空
											$('#major_id_edit').combobox('setValue', '');
											$.messager.alert("操作提示", "请先选择学院项！","warning");
										} else {
//											alert("ok!");
										}
									},
								});
							}
							
							$('#grade_id_edit').combobox({
								data : obj.grade,
								valueField:'grade_id',
								textField:'name',
								editable: false, //是否可以编辑
//								onHidePanel
								onSelect: function () {
									//获取专业id  先选择专业  才能选择年级
									var major_id_edit_value = ''+$('input[name="major_id_edit"]').val();
									if (major_id_edit_value == '') {
										//年级值置空
										$('#grade_id_edit').combobox('setValue', '');
										$.messager.alert("操作提示", "请先选择专业项！","warning");
									} else {
										/**
										 * 获取该年级 下面班级的值
										 */
//					                    $("#class_id_add").combobox("setValue", '');
//										debugger
					                    var grade_id_edit = $('#grade_id_edit').combobox('getValue');
					                    var major_id_edit = $('#major_id_edit').combobox('getValue');
					                    var url = Student_getClassByGradeAndMajorId_url+'?grade_id='+grade_id_edit;
//					                    $("#class_id_edit").combobox('clear');
//					                    $("#class_id_edit").combobox('reload', url);
					                    
					                    $.ajax({
					                        type: "POST",
					                        url: Student_getClassByGradeAndMajorId_url,
					                        cache: false,
					                        dataType: "json",
					                        data : {
					                        	grade_id : grade_id_edit,
					                        	major_id : major_id_edit,
					                        },
					                        beforeSend : function () {
												$.messager.progress({
													text : '正在加载班级中...',
												});
											},
					                        success: function (data) {
					                        	$.messager.progress('close');
					                        	//重新填充 班级 combobox 数据
					                        	if (data.class == "" || data.class == "[]") {
					                        		$.messager.alert("操作提示", "没有找到符合条件的班级信息！","warning");
					                        		//重置年级
					                        		$("#grade_id_edit").combobox("setValue", '');
					                        	}
					                        	$("#class_id_edit").combobox('clear');
					                        	$("#class_id_edit").combobox("loadData", data.class);
					                        	
					                        }
					                    });
									}
									
				                }
							});
							
							
							$('#class_id_edit').combobox({
//								data : '',
								url : ''+Student_getClassByGradeAndMajorId_url,
								valueField:'class_id',
								textField:'name',
								editable: false, //是否可以编辑
//								required:true,
								//下拉框选中事件
								onSelect: function (n,o) {
									//获取年级   若年级为空，提示先选年级 才能选班级
									//获取班级id
									var grade_id_edit_value = ''+$('input[name="grade_id_edit"]').val();
									if (grade_id_edit_value == '') {
										//班级值置空
										$('#class_id_edit').combobox('setValue', '');
										$.messager.alert("操作提示", "请先选择年级项！","warning");
									} else {
//										alert("ok!");
									}
								},
							});
							
							
							var rs = obj.rs[0];
//							console.log(obj.rs[0].stu_id);
							//设置性别
							if (rs.gender == 0) {
								$('#gender_edit').combobox('setValue', '0');
							} else {
								$('#gender_edit').combobox('setValue', '1');
							}
							$('#student_edit').form('load', {
								stu_id : obj.rs[0].stu_id,
								name_edit : rs.name,
								account_edit : rs.account,
//								password_edit : rs.password,
								email_edit : rs.email,
								phone_edit : rs.phone,
							}).dialog('open');
						} else {
							$.messager.alert('获取失败！', '未知错误导致失败，请重试！', 'warning');
						}
					}
				});
			} else if (rows.length == 0) {
				$.messager.alert('警告操作！', '编辑记录至少选定一条数据！', 'warning');
			}
		},
		updatePwd : function () {
			var rows = $('#student').datagrid('getSelections');
			if (rows.length > 0) {
				$.messager.confirm('确定操作', '您确定要重置所选记录的登录密码吗？重置后密码为123456', function (flag) {
					if (flag) {
						var ids = [];
						for (var i = 0; i < rows.length; i ++) {
							ids.push(rows[i].stu_id);
						}
						//console.log(ids.join(','));
						$.ajax({
							type : 'POST',
							url : ''+Student_updatePwdStudent_url,//重置url
							dataType : 'json',
							data : {
								ids : ids.join(','),
							},
							beforeSend : function () {
								$('#student').datagrid('loading');
							},
							success : function (data) {
								console.log(data);
								if (data) {
									$('#student').datagrid('loaded');
									$('#student').datagrid('load');
									$('#student').datagrid('unselectAll');
									$.messager.show({
										title : '提示',
										msg : data.message,
									});
								}
							},
						});
					}
				});
			} else {
				$.messager.alert('提示', '请选择要重置密码的记录(可以多条)！', 'info');
			}
		},
		remove : function () {
			var rows = $('#student').datagrid('getSelections');
			if (rows.length > 0) {
				$.messager.confirm('确定操作', '您确定要删除所选的记录吗？', function (flag) {
					if (flag) {
						var ids = [];
						for (var i = 0; i < rows.length; i ++) {
							ids.push(rows[i].stu_id);
						}
						//console.log(ids.join(','));
						$.ajax({
							type : 'POST',
							url : ''+Student_deleteStudent_url,
							dataType : 'json',
							data : {
								ids : ids.join(','),
							},
							beforeSend : function () {
								$('#student').datagrid('loading');
							},
							success : function (data) {
								console.log(data);
								if (data) {
									$('#student').datagrid('loaded');
									$('#student').datagrid('load');
									$('#student').datagrid('unselectAll');
									$.messager.show({
										title : '提示',
										msg : data.rs + '个用户被删除成功！',
									});
								}
							},
						});
					}
				});
			} else {
				$.messager.alert('提示', '请选择要删除的记录(可以多条)！', 'info');
			}
		},
		reload : function () {
			//刷新   查看全部
			//重置(清空)所有的查询条件
			$('#gender_find').combobox('setValue', '-1');
			$('#name_find').combobox('setValue', '');
			$('#account_find').combobox('setValue', '');
//			$('#college_id_find').combobox('setValue', '');
			$('#major_id_find').combobox('setValue', '');
			$('#grade_id_find').combobox('setValue', '');
			$('#class_id_find').combobox('setValue', '');
			$('#student').datagrid('load',{college_id : pub_college_id});
		},
		redo : function () {
			//取消选中
			$('#student').datagrid('unselectAll');
		},
		search : function () {
			//查询
//			$('#student').datagrid('reload');
			//获取gender  
			var gender_find_value = ''+$('input[name="gender_find"]').val();
			//如果 为不选，则赋值为空
			if (gender_find_value == '-1') {
				gender_find_value = '';
			}
			//获取
			//发送其他查询条件
			if (pub_college_id == '') {
				pub_college_id = $('#college_id_find').combobox('getValue');
			}
			$('#student').datagrid('load', {
				name : $('#name_find').combobox('getText'),
				account : $('#account_find').combobox('getText'),
				gender : gender_find_value,
				class_id : $('#class_id_find').combobox('getValue'),
				grade_id : $('#grade_id_find').combobox('getValue'),
				major_id : $('#major_id_find').combobox('getValue'),
//				college_id : $('#college_id_find').combobox('getValue'),
				college_id : pub_college_id,
			});
		},
		import : function () {
			
			$.ajax({
				url : ''+Student_getStudentGraAndColl_url,
				type : 'post',
				data : {
				},
				beforeSend : function () {
					$.messager.progress({
						text : '正在获取相关数据中...',
					});
				},
				success : function (data, response, status) {
					$.messager.progress('close');
					var obj = $.parseJSON(data);
					console.log(obj.college);
					if (data) {
						//填充导入框中下拉框信息
						$('#college_id_import').combobox({
							data : obj.college,
							valueField:'college_id',
							textField:'name',
							editable: false, //是否可以编辑
//							required:true,
							onSelect: function () {
								/**
								 * 获取该学院 下面专业的值
								 */
			                    var college_id_import = $('#college_id_import').combobox('getValue');
			                    $.ajax({
			                        type: "POST",
			                        url: Student_getMajorByCollegeId_url,
			                        cache: false,
			                        dataType: "json",
			                        data : {
			                        	college_id : college_id_import,
			                        },
			                        beforeSend : function () {
										$.messager.progress({
											text : '正在加载专业中...',
										});
									},
			                        success: function (data) {
			                        	$.messager.progress('close');
			                        	console.log(data.major);
//			                        	var obj = $.parseJSON(data);
			                        	//重新填充 专业 combobox 数据
			                        	if (data.major == "" || data.major == "[]") {
//			                        		$.messager.alert('该学院下面没有专业！', data.message+'请重试！', 'warning');
			                        		$.messager.alert("操作提示", "该学院下面没有相关专业！","warning");
			                        	}
			                        	$("#major_id_import").combobox('clear');
			                        	$("#major_id_import").combobox("loadData", data.major);
			                        	
			                        }
			                    });
			                }
						});
						if (pub_college_id != '') {
							//设置学院的值
							$('#college_id_import').combobox('setValue', pub_college_id);
							$('#college_id_import').combobox('disable');   //不可用
							
							//获取该学院下的专业
							$.ajax({
			                    type: "POST",
			                    url: Student_getMajorByCollegeId_url,
			                    cache: false,
			                    dataType: "json",
			                    data : {
			                    	college_id : pub_college_id,
			                    },
			                    success: function (data) {
			                    	$('#major_id_import').combobox({
			        					data : data.major,
			        					valueField:'major_id',
			        					textField:'name',
			        					editable: false, //是否可以编辑
			        					//下拉框选中事件
			        					onSelect: function (n,o) {
			        					},
			        				});
			                    }
			                });
						}else {
							$('#major_id_import').combobox({
								data : '',
								url : ''+Student_getMajorByCollegeId_url,
								valueField:'major_id',
								textField:'name',
								editable: false, //是否可以编辑
								//下拉框选中事件
								onSelect: function (n,o) {
									//获取学院   若学院为空，提示先选学院 才能选专业
									//获取学院id
									var college_id_import_value = ''+$('input[name="college_id_import"]').val();
									if (college_id_import_value == '') {
										//值 置空
										$('#major_id_import').combobox('setValue', '');
										$.messager.alert("操作提示", "请先选择学院项！","warning");
									} else {
//										alert("ok!");
									}
								},
							});
						}
						$('#grade_id_import').combobox({
							data : obj.grade,
							valueField:'grade_id',
							textField:'name',
							editable: false, //是否可以编辑
//							onHidePanel
							onSelect: function () {
								//获取专业id  先选择专业  才能选择年级
								var major_id_import_value = ''+$('input[name="major_id_import"]').val();
								if (major_id_import_value == '') {
									//年级值置空
									$('#grade_id_import').combobox('setValue', '');
									$.messager.alert("操作提示", "请先选择专业项！","warning");
								} else {
									/**
									 * 获取该年级 下面班级的值
									 */
//				                    $("#class_id_add").combobox("setValue", '');
//									debugger
				                    var grade_id_import = $('#grade_id_import').combobox('getValue');
				                    var major_id_import = $('#major_id_import').combobox('getValue');
				                    var url = Student_getClassByGradeAndMajorId_url+'?grade_id='+grade_id_import;
//				                    $("#class_id_import").combobox('clear');
//				                    $("#class_id_import").combobox('reload', url);
				                    
				                    $.ajax({
				                        type: "POST",
				                        url: Student_getClassByGradeAndMajorId_url,
				                        cache: false,
				                        dataType: "json",
				                        data : {
				                        	grade_id : grade_id_import,
				                        	major_id : major_id_import,
				                        },
				                        beforeSend : function () {
											$.messager.progress({
												text : '正在加载班级中...',
											});
										},
				                        success: function (data) {
				                        	$.messager.progress('close');
				                        	//重新填充 班级 combobox 数据
				                        	if (data.class == "" || data.class == "[]") {
				                        		$.messager.alert("操作提示", "没有找到符合条件的班级信息！","warning");
				                        		//重置年级
				                        		$("#grade_id_import").combobox("setValue", '');
				                        	}
				                        	$("#class_id_import").combobox('clear');
				                        	$("#class_id_import").combobox("loadData", data.class);
				                        	
				                        }
				                    });
								}
								
			                }
						});
						
						$('#class_id_import').combobox({
//							data : '',
							url : ''+Student_getClassByGradeAndMajorId_url,
							valueField:'class_id',
							textField:'name',
							editable: false, //是否可以编辑
							required:true,
							//下拉框选中事件
							onSelect: function (n,o) {
								//获取年级   若年级为空，提示先选年级 才能选班级
								//获取班级id
								var grade_id_import_value = ''+$('input[name="grade_id_edit"]').val();
								if (grade_id_import_value == '') {
									//班级值置空
									$('#class_id_import').combobox('setValue', '');
									$.messager.alert("操作提示", "请先选择年级项！","warning");
								} else {
//									alert("ok!");
								}
							},
						});
					} else {
						$.messager.alert('获取失败！', '未知错误导致失败，请重试！', 'warning');
					}
				}
			});
			//显示导入表单
			$('#student_import').dialog('open');
		},
	};
	
});

function getGrade() {
	alert("和送佛企欧风荷藕");
}