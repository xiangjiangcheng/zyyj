
<table id='Stable' style="padding:100px"></table>
<div id="St_tool" style="padding:0px">
      <form id='St_form'>
      	<span style="margin-left:10px">
                  <label>学号</label>
      		<input type="text" name="st_account" id='st_account' class="easyui-numberbox" style="width:110px;">
      	</span>
      	<span style="margin-left:10px">
                  <label>姓名</label>
      		<input type="text" name="st_stuname"  id="st_stuname" class="textbox" style="width:110px;">
      	</span>
      	<span style="margin-left:10px">
      		<!--   <span>学院:</span>
                  <input id="st_college" name="st_college" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetCollege"
                  valueField="college_id" textField="name" >
                  </input>  -->
                  <!-- <span>科目:</span>
                  <input id="st_course" name="st_course" style="width:100px" class="easyui-combobox"
                        url="__SITE__index.php/Home/Tao/Tao_GetCourse"
                        valueField="course_id" textField="name" ></input>   -->
                  <span>专业:</span>
                  <input id="st_major" name="st_major" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetMajor"
                  valueField="major_id" textField="name">
                  </input> 
                  <span>年级:</span>
                  <input id="st_grade" name="st_grade" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetGrade"
                  valueField="grade_id" textField="name" >
                  </input>  
                  <span>班级:</span>
                  <input id="st_class" name="st_class" style="width:100px" class="easyui-combobox"
                  url="__SITE__index.php/Home/Tao/Tao_GetClass"
                  valueField="class_id" textField="name" >
                  </input> 
                  <a  class="easyui-linkbutton" iconCls="icon-search"  onclick="S_FindSearch();">查询</a>
                  <a class="easyui-linkbutton"  iconCls='icon-search' onclick="S_AllSearch();">查看全部</a>
      	</span>
      </form>
</div>
<script type="text/javascript">
      var stageurl = "{:U('CreditManage/CreditManage_Getstage')}";
      var classurl =  '__SITE__index.php/Home/Tao/Tao_GetClass';
</script>
<script type="text/javascript" src="__SITE__source/js/Showstage.js" ></script>
