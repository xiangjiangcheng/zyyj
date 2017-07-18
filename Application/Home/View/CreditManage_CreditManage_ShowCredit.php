
<link rel="stylesheet" href="__SITE__source/css/Grade_Manage.css">
<script type="text/javascript">
      var classurl =  '__SITE__index.php/Home/Tao/Tao_GetClass';
	var geturl='{:U('CreditManage_GetInfo')}';
      var getexcel = '__SITE__index.php/Home/CreditManage/CreditManage_GetInfo?';
</script>
<script type="text/javascript" src="__SITE__source/js/CreditManage_CreditManage.js" ></script>
<!--这里是积分排行榜-->
<table id="Ctable" style="padding:0px"
	iconCls='icon-search'>
</table>
<div id="Cr_tool" style="padding:0px">
      <form id="Cr_form">
      	<span style="margin-left:10px">
                  <label>学号:</label>
      		<input type="text" name="sc_account" id='sc_account' class="easyui-numberbox" style="width:110px;">
      	</span>
      	<span style="margin-left:10px">
                  <label>姓名:</label>
      		<input type="text" name="sc_stuname"  id="sc_stuname" class="textbox" style="width:110px;">
      	</span>
      
      	<span style="margin-left:10px">
      		<!--    <span>学院:</span>
                  <input id="sc_college" name="sc_college" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetCollege"
                  valueField="college_id" textField="name" >
                  </input> -->
                  <span>专业:</span>
                  <input id="sc_major" name="sc_major" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetMajor"
                  valueField="major_id" textField="name" >
                  </input> 
                  <span>年级:</span>
                  <input id="sc_grade" name="sc_grade" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetGrade"
                  valueField="grade_id" textField="name" >
                  </input>  
                  <span>班级:</span>
                  <input id="sc_class" name="sc_class" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetClass"
                  valueField="class_id" textField="name" >
                  </input> 
                  <a  class="easyui-linkbutton" iconCls="icon-search"  onclick="FindSearch();" style="margin-left:10px">查询</a>
                  <a class="easyui-linkbutton"  iconCls='icon-search' onclick="AllSearch();" style="margin-left:10px">查看全部</a>
                  <a class="easyui-linkbutton" iconCls='icon-save' onclick="GetDld();" style="margin-left:10px">导出当前积分榜</a>
      	</span>
      </form>
</div>

