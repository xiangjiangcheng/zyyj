<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="http://localhost/zyyj/source/js/Programme_Programme_list.js" ></script>
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/source/css/Class_Class_list.css" />

    <!-- <h2>方案管理</h2> -->
    <!-- <div style="margin:20px 0;"></div> -->
    <table id="pro_datagrid" class="easyui-datagrid" style="width:auto;"
        url="http://localhost/zyyj/index.php/Home/Programme/Programme_list_show" toolbar="#programme_tb"
        title="全部方案" iconCls="icon-save" singleSelect="true"
        rownumbers="true" pagination="true">
        <div id="programme_tb" style="padding:3px">
            <!-- <span>专业:</span>
            <input id="s_major" name="s_major" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getMajor"
            valueField="major_id" textField="name" >
            </input>     
            <span>部门:</span>
            <input id="s_department" name="s_department" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getDepartment"
            valueField="department_id" textField="name" >
            </input>
            <span>年级:</span>
            <input id="s_grade" name="s_grade" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Class/Class_getGrade"
            valueField="grade_id" textField="name" >
            </input>       
            <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">查找</a>
            <a href="#" class="easyui-linkbutton" plain="true" onclick="searchAll()">查找全部</a>
            <br> -->
            <a href="#" class="easyui-linkbutton" iconcls="icon-add" onclick="newProgramme()"
            plain="true">添加</a>
            <a href="#" class="easyui-linkbutton" iconcls="icon-edit"
                onclick="editProgramme('http://localhost/zyyj/index.php/Home/Programme/Programme_getInfo')" plain="true">修改</a>
            <a href="#" class="easyui-linkbutton" onclick="delProgramme('http://localhost/zyyj/index.php/Home/Programme/Programme_delete')" iconcls="icon-remove" plain="true">删除</a>
        </div>
        <thead>
            <tr>
                <th field="name" width="25%" align="center">方案名称</th>
                <!-- <th field="dname" width="20%" >关卡数量</th> -->
                <th field="createdate" width="25%" align="center" >更新日期</th>
                <th field="score" width="25%" align="center" >总分</th>
                <th field="comment" width="25%" align="center" >备注</th>
                <th field="programme_id" style="display: none;" width="" align="center"></th>
                <!-- <th field="handle" width="150">操作</th> -->
            </tr>
        </thead>
    </table>
 
<div id="programme_dlg" class="easyui-dialog" style="width: 400px; height: 280px; padding: 10px 20px;"
       closed="true" buttons="#programme_dlg-buttons"> 
       <div class="ftitle"> 
           方案信息 
       </div> 
       <form id="programme_fm" method="post"> 
       <div class="fitem"> 
           <label> 
               方案名称</label>
           <input name="programme_name" id="programme_name" class="easyui-validatebox" required="true" style="width:140px"/>
       </div> 
       <div class="fitem"> 
           <label> 
               总分 
           </label>
           <input name="programme_score" id="programme_score" class="easyui-numberbox" required="true" style="width:140px"/>
       </div> 
       <div class="fitem"> 
           <label> 
               关卡数量</label> 
           <input name="programme_cnum" id="programme_cnum" class="easyui-numberbox" required="true" style="width:140px"/>
       </div> 
       <div id="using" class="fitem"> 
           <label> 
               已使用人数</label> 
           <input name="programme_user" id="programme_user" class="easyui-validatebox" readonly="true" style="width:140px"/> 
       </div>       
       <div class="fitem"> 
           <label> 
               备注</label> 
           <input name="programme_comment" id="programme_comment" class="easyui-vlidatebox" style="width:140px"/> 
       </div> 
       <!-- <input type="hidden" name="action" id="hidtype" />  -->
       <input type="hidden" name="program_edit_id" id="program_edit_id" /> 
       </form> 
   </div>
<div id="programme_dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveProgramme('http://localhost/zyyj/index.php/Home/Programme/Programme_add')" iconcls="icon-save">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#programme_dlg').dialog('close')"
            iconcls="icon-cancel">取消</a>
</div>
<!-- 
</body> -->

<script type="text/javascript" charset="UTF-8">
 $(function(){
    $("#pro_datagrid").datagrid({
        fit : true,
        fitColumns : true,
        striped : true,
        pageSize:20,
        onLoadSuccess:function(){
            $("#pro_datagrid").datagrid('hideColumn', 'programme_id');
    // $('#xzCfgTable').datagrid('hideColumn','id');
        }
    });

    // $('#s_major').combobox({
    //         // url:"getClose.do",
    //         // editable:false,
    //         // panelWidth:130,
    //         // width:130,
    //         onSelect:function(record){
    //                 cascade('http://localhost/zyyj/index.php/Home/Class/Class_getGrade','s_major','s_grade');
    //         }
    // });
    // $('#major').combobox({
    //         // url:"getClose.do",
    //         // editable:false,
    //         // panelWidth:130,
    //         // width:130,
    //         onSelect:function(record){
    //                 cascade('http://localhost/zyyj/index.php/Home/Class/Class_getGrade','major','grade');
    //         }
    // });

 })
</script>
<!-- </html> -->