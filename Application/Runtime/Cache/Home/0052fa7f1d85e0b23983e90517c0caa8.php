<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="http://localhost/zyyj/source/js/Class_Class_list.js" ></script>
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/source/css/Class_Class_list.css" />

    <!-- <h2>班级管理</h2> -->
    <!-- <div style="margin:20px 0;"></div> -->
    <table id="c_datagrid" class="easyui-datagrid" style="width:auto;"
        url="http://localhost/zyyj/index.php/Home/Class/Class_list_show" toolbar="#tb"
        title="全部班级" iconCls="icon-save" singleSelect="true"
        rownumbers="true" pagination="true">
        <div id="tb" style="padding:3px">
            <span>专业:</span>
            <input id="s_major" name="s_major" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getMajor"
            valueField="major_id" textField="name" >
            </input>  
            <span>年级:</span>
            <input id="s_grade" name="s_grade" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getGrade"
            valueField="grade_id" textField="name" >
            </input>       
            <a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="doClassSearch()">查找</a>
            <a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="searchAll()">查找全部</a>
            <br>
            <a href="#" class="easyui-linkbutton" iconcls="icon-add" onclick="newClass()"
            plain="true">添加</a>
            <a href="#" class="easyui-linkbutton" iconcls="icon-edit"
                onclick="editClass('http://localhost/zyyj/index.php/Home/Class/Class_getInfo')" plain="true">修改</a>
            <a href="#" class="easyui-linkbutton" onclick="delClass('http://localhost/zyyj/index.php/Home/Class/Class_delete')" iconcls="icon-remove" plain="true">删除</a>
        </div>
        <thead>
            <tr>
                <th field="cname" width="25%" align="center">名称</th>
                <th field="gname" width="25%" align="center">年级</th>
                <th field="mname" width="25%" align="center">专业</th>
                <th field="ccomment" width="25%" align="center">备注</th>
                <th field="cid" style="display: none;" width="" align="center"></th>
                <!-- <th field="handle" width="150">操作</th> -->
            </tr>
        </thead>
    </table>
 
<div id="dlg" class="easyui-dialog" style="width: 400px; height: 280px; padding: 10px 20px;"
       closed="true" buttons="#class_dlg-buttons"> 
       <div class="ftitle"> 
           班级信息 
       </div> 
       <form id="fm" method="post"> 
       <div class="fitem"> 
           <label> 
               专业</label> 
           <input id="major" name="major" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getMajor"
            valueField="major_id" textField="name" required="true" >
            </input>
       </div> 
       <div class="fitem"> 
           <label> 
               年级 
           </label> 
           <input id="grade" name="grade" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getGrade"
            valueField="grade_id" textField="name" required="true" >
            </input>
       </div>  
       <div class="fitem"> 
           <label> 
               名称</label> 
           <input name="name" id="name" class="easyui-validatebox" required="true" style="width:100px"/> 
       </div>       
       <div class="fitem"> 
           <label> 
               备注</label> 
           <input name="comment" id="comment" class="easyui-vlidatebox" style="width:100px"/> 
       </div> 
       <!-- <input type="hidden" name="action" id="hidtype" />  -->
       <input type="hidden" name="edit_id" id="edit_id" /> 
       </form> 
   </div>
<div id="class_dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveClass('http://localhost/zyyj/index.php/Home/Class/Class_add')" iconcls="icon-save">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close')"
            iconcls="icon-cancel">取消</a>
</div>
<!-- 
</body> -->

<script type="text/javascript" charset="UTF-8">
 $(function(){
    $("#c_datagrid").datagrid({
        fit : true,
        fitColumns : true,
        striped : true,
        pageSize:20,
        onLoadSuccess:function(){
            $("#c_datagrid").datagrid('hideColumn', 'cid');
        }
    });

 })
</script>
<!-- </html> -->