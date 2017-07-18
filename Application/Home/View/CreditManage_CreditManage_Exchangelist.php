<script type="text/javascript">
	var exlisturl = '{:U('CreditManage/CreditManage_GetList')}';
      var classurl =  '__SITE__index.php/Home/Tao/Tao_GetClass';
</script>
<script type="text/javascript" src="__SITE__source/js/Exchangelist.js"></script>
<table id="Etable" style="padding:0px" iconCls='icon-search'></table>

<div id="Ex_tool" style="padding:0px">
      <form id='Eform'>
      	<span style="margin-left:10px">
                  <label>学号</label>
      		<input type="text" name="ex_account" id='ex_account' class="easyui-numberbox" style="width:110px;">
      		<!--<a  class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="E_IDSearch();">查找</a>-->
      	</span>
      	<span style="margin-left:10px">
                  <label>姓名</label>
      		<input type="text" name="ex_stuname"  id="ex_stuname" class="textbox" style="width:110px;">
      		<!--<a  class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="E_MSearch();">查找</a>-->
      	</span>
      	<span style="margin-left:10px">
      		<!-- <span>学院:</span>
                        <input id="ex_college" name="ex_college" style="width:100px" class="easyui-combobox"
                        url="__SITE__index.php/Home/Tao/Tao_GetCollege"
                        valueField="college_id" textField="name" >
                        </input>  
                  -->
                  <span>专业:</span>
                        <input id="ex_major" name="ex_major" style="width:100px" class="easyui-combobox"
                        url="__SITE__index.php/Home/Tao/Tao_GetMajor"
                        valueField="major_id" textField="name" >
                        </input> 
                  <span>年级:</span>
                        <input id="ex_grade" name="ex_grade" style="width:100px" class="easyui-combobox"
                        url="__SITE__index.php/Home/Tao/Tao_GetGrade"
                        valueField="grade_id" textField="name" >
                        </input>  
                  <span>班级:</span>
                        <input id="ex_class" name="ex_class" style="width:100px" class="easyui-combobox"
                        url="__SITE__index.php/Home/Tao/Tao_GetClass"
                        valueField="class_id" textField="name" >
                        </input> 
                  <a  class="easyui-linkbutton" iconCls="icon-search"  onclick="E_FindSearch();" style="margin-left:10px">查询</a>
                  <a class="easyui-linkbutton"  iconCls='icon-search' onclick="E_AllSearch();" style="margin-left:10px">查看全部</a>
      	</span>
      </form>
</div>
