<style type="text/css">
	.sitem{
		margin-left: 10px;
	}
	.inbox{
		padding: 5px;
		margin-left: 35px;
	}
</style>
<link rel="stylesheet" href="__SITE__source/css/Grade_Manage.css">
<table id='Es_table' style="padding:0px"></table>
<div id='Es_div'>
	<form id='Es_form' name='Es_form'>
		<div style="margin:5px 5px">
			<span class="sitem">
				<label>学号</label>
				<input type="text" name="es_account" id='es_account' class="easyui-numberbox" style="width:100px">
			</span>
			<span class="sitem">
				<label>姓名</label>
				<input type="text" name="es_stuname"  id="es_stuname" class="textbox" style="width:100px;">
			</span>
			<span class="sitem">
				<label>状态</label>
				<select name='es_status' id='es_status' class="easyui-combobox" style="width:100px;">
					<option value="3">全部</option>
					<option value="1">不合格</option>
					<option value="2">合格</option>
				</select>
			</span>
			<span class="sitem">
				<label>方案</label>
				<input id="es_program" name="es_program" style="width:100px" class="easyui-combobox"
				 url="__SITE__index.php/Home/Tao/Tao_GetProgram"
				 valueField="program_id" textField="name" >
				</input>  
			</span>
			<span class="sitem">
				<label>等级</label>
				<input id="es_level" name='es_level' class='easyui-combobox' url="__SITE__index.php/Home/Tao/Tao_GetLevel" style="width:100px" valueField="level_id" textField="name"> 
			</span>
			
		</div >
		<div style="margin:5px 5px">
			<!--<span class="sitem">
				<label >学院</label>
			     <input id="es_college" name="es_college" style="width:100px" class="easyui-combobox"
			      url="__SITE__index.php/Home/Tao/Tao_GetCollege"
			      valueField="college_id" textField="name" >
			     </input>  
			</span>
			-->
			<span  class="sitem">
				<label >专业</label>
				<input id="es_major" name="es_major" style="width:100px" class="easyui-combobox"
				url="__SITE__index.php/Home/Tao/Tao_GetMajor"
				valueField="major_id" textField="name" >
				</input> 
			</span>
			<span class="sitem">
				<label>年级</label>
					<input id="es_grade" name="es_grade" style="width:100px" class="easyui-combobox"
					url="__SITE__index.php/Home/Tao/Tao_GetGrade"
					valueField="grade_id" textField="name" >
					</input>  
			</span>
			<span class="sitem">
				<label>班级</label>
					<input id="es_class" name="es_class" style="width:100px" class="easyui-combobox"
					url="__SITE__index.php/Home/Tao/Tao_GetClass"
					valueField="class_id" textField="name" >
					</input>
			</span>
			<span class="sitem">
				<label >科目</label>
			     <input id="es_course" name="es_course" style="width:100px" class="easyui-combobox"
			      url="__SITE__index.php/Home/Tao/Tao_GetCourse"
			      valueField="course_id" textField="name" >
			     </input>  
			</span>
			<span style="margin-left:20px">
				<a  class="easyui-linkbutton" iconCls="icon-search" plain="true"  onclick="Es_Search();">查询</a>
			</span>
			<span style="margin-left:10px">
				<a  class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="Es_AllSearch();">查看全部</a>
			</span>
			<span style="margin-left:15px">
				<a  class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="Es_getexcel();">导出当前成绩单</a>
			</span>
		</div>
	</form>
</div>
<!--
<div id='getexcel' title="导出成绩单" data-options="iconCls:'icon-save'" closed="true" style="padding:5px;width:400px;height:380px;">
	<div class="form-box1">
		<h3 class="Gtitle">填写成绩单信息</h3>
		<form id="socrelist" style="margin: 0; padding: 6px 60px;">
			<div class="inbox">
				<label class="Mtitle">选择学院</label>
				<input id="getcollege" name='getcollege' required="true" editable='false' style="width:100px" required="true"> 
			</div>
			<div class="inbox">
				<label class="Mtitle">选择专业</label>
				<input id="getmajor" name='getmajor' required="true" editable='false' style="width:100px" > 
			</div>
			<div class="inbox">
				<label class="Mtitle">选择年级</label>
				<input id="getgrade" name='getgrade' required="true" editable='false' style="width:100px" > 
			</div>
			<div class="inbox">
				<label class="Mtitle">选择班级</label>
				<input id="getclass" name='getclass'  editable='false' style="width:100px" > 
			</div>
			<div class="inbox">
				<label class='Mtitle'>选择科目</label>
				<input id="getcourse" name='getcourse' required="true" editable='false' style="width:100px" required="true"> 
			</div>
			<div class="inbox">
				<label class='Mtitle'>选择方案</label>
				<input id="getprogram" name='getprogram' required="true" editable='false' style="width:100px" required="true"> 
			</div>
			<div class="inbox">
				<label class='Mtitle'>选择等级</label>
				<input id="getlevel" name='getlevel' required="true"  style="width:100px"> 
			</div>
		</form>
	</div>
</div>-->
<script type="text/javascript">
	function attetion(){
		alert('daw');
	}
	$(function(){
		$('#es_program').combobox({
			onSelect:function(record){
				$('#es_level').combobox({
					url:'__SITE__index.php/Home/Tao/Tao_GetLevel?programid='+record.program_id,
					valueField:'level_id',
					textField:'name',
				});
			}
		});

		$('#es_level').combobox({
			onSelect:function(record){
				var pid = $('#es_program').combobox('getValue');
				if(pid===''){
					$.messager.alert("提示信息", "请先选择方案!");
					$('#es_level').combobox('clear');
				}
			}
		});
	});
	/*function loadbox(){
		/*$('#getcollege').combobox({
		    url:'{:U('Tao/Tao_GetCollege')}',
		    valueField:'college_id',
		    textField:'name',
		});  
		$('#getmajor').combobox({
		    url:'{:U('Tao/Tao_GetMajor')}',
		    valueField:'major_id',
		    textField:'name',
		}); 
		$('#getgrade').combobox({
		    url:'{:U('Tao/Tao_GetGrade')}',
		    valueField:'grade_id',
		    textField:'name',
		});
		$('#getclass').combobox({
		    url:'{:U('Tao/Tao_GetClass')}',
		    valueField:'class_id',
		    textField:'name',
		});
		$('#getcourse').combobox({
		    url:'{:U('Tao/Tao_GetCourse')}',
		    valueField:'course_id',
		    textField:'name',
		});
		$('#getprogram').combobox({
		    url:'{:U('Tao/Tao_GetProgram')}',
		    valueField:'program_id',
		    textField:'name',
		    onSelect:function(record){
		    	$('#getlevel').combobox({
		    		url:'__SITE__index.php/Home/Tao/Tao_GetLevel?programid='+record.program_id,
		    		valueField:'level_id',
		    		textField:'name',
		    	});
		    }
		});
		$('#getlevel').combobox({
			valueField:'level_id',
			textField:'name',
		});
	}*/
	function Es_Search(){
		$('#Es_table').datagrid('load',$('#Es_form').serializeJson(0));
	}
	(function($){  
	    $.fn.serializeJson=function(){  
	        var serializeObj={};  
	        $(this.serializeArray()).each(function(){  
	            serializeObj[this.name]=this.value;  
	        });  
	        return serializeObj;  
	  	};
	 })(jQuery);
	 function Es_clearform(){
	 	$('#es_grade').combobox('clear');
		$('#es_class').combobox('clear');
		$('#es_major').combobox('clear');
		//$('#es_college').combobox('clear');
		$('#es_course').combobox('clear');
		$('#es_program').combobox('clear');
		$('#es_status').combobox('setValue',3);
		$('#es_level').combobox('clear');
		$('#Es_form').form('reset');
	 }
	//查询全部
	function Es_AllSearch(){
		Es_clearform();
		$('#Es_table').datagrid('load',{});
	}
	function Es_getexcel(){
		var stuname  = $('#es_stuname').val();
		var account  = $('#es_account').val();
		var status = $('#es_status').combobox('getValue');
	 	var gradeid = $('#es_grade').combobox('getValue');
		var classid = $('#es_class').combobox('getValue');
		var majorid = $('#es_major').combobox('getValue');
		//var collegeid= $('#getcollege').combobox('getValue');
		//'&college_id='+collegeid+
		var courseid = $('#es_course').combobox('getValue');
		var programid = $('#es_program').combobox('getValue');
		var levelid = $('#es_level').combobox('getValue');

		window.open('__SITE__index.php/Home/ExamScore/ExamScore_Getexcel?grade_id='+gradeid+'&class_id='+classid+'&major_id='+majorid+'&course_id='+courseid+'&program_id='+programid+'&level_id'+levelid+'&stuname='+stuname+'&account='+account+'&status='+status);
	}
	/*$(function(){
		$('#getexcel').dialog({
			buttons:[{
				text:'导出',
				iconCls:'icon-save',
				handler:function(){
					if($('#socrelist').form('validate')){
					 	var gradeid = $('#getgrade').combobox('getValue');
						var classid = $('#getclass').combobox('getValue');
						var majorid = $('#getmajor').combobox('getValue');
						//var collegeid= $('#getcollege').combobox('getValue');
						//'&college_id='+collegeid+
						var courseid = $('#getcourse').combobox('getValue');
						var programid = $('#getprogram').combobox('getValue');
						var levelid = $('#getlevel').combobox('getValue');
						window.open('__SITE__index.php/Home/ExamScore/ExamScore_Getexcel?grade_id='+gradeid+'&class_id='+classid+'&major_id='+majorid+'&course_id='+courseid+'&program_id='+programid+'&level_id'+levelid);
					}
				}
			},{
				text:'取消',
				iconCls:'icon-cancel',
				handler:function(){
					$('#getexcel').dialog('close');
				}
			}]
		});
	});*/

	$(function () {
		$('#Es_table').datagrid({
			width:'100%',
			title:'考试成绩记录表',
			iconCls:'icon-search',
			url:'{:U('ExamScore/ExamScore_GetScores')}',
			method: 'POST',
			singleSelect:true,
			rownumbers:true,
			fit:true,
			fitColumns: true,
			remoteSort: false,
			pagination:true,
			loadMsg: '正在努力为您加载数据',
			toolbar:'#Es_div',
			columns:[[
				{
					field:'account',
					title:'学号',
					width:'7%',
				},
				{
					field:'stuname',
					title:'姓名',
					width:'7%',
				},
				{
					field:'sex',
					title:'性别',
					width:'5%',
					formatter:function(value){
						if(value==1){
							return '男';
						}else{
							return '女';
						}
					},
				},
				{
					field:'getscore',
					title:'成绩',
					width:'7%',
				},
				{
					field:'status',
					title:'状态',
					width:'7%',
					formatter:function(value){
						if(value==0){
							return '缺考';
						}else if(value==1){
							return '不合格';
						}else if(value==2){
							return '合格';
						}
					},
					/*styler:function(value){
						if(value==0){
							return 'background-color:#ffee00;color:red;';
						}else if(value==1){
							return 'background-color:#6293BB;color:red;';
						}else if(value==2){
							return 'background-color:#6293BB;color:#fff;';
						}
					}*/
				},
				{
					field:'cname',
					title:'科目',
					width:'10%',
				},
				{
					field:'proname',
					title:'考试方案',
					width:'10%',
				},
				{
					field:'lname',
					title:'考试等级',
					width:'6%',
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
		var p=$("#Es_table").datagrid('getPager');
		$(p).pagination({
	         pageSize:20,     //每页显示的大小
	         pageList:[10,20,30],
	         beforePageText:'第',  //页数文本框前显示的汉字
	         afterPageText:'页 共{pages}页',
	         displayMsg:'当前显示{from}-{to}条记录 共{total}条记录',
		 });
	});
</script>
