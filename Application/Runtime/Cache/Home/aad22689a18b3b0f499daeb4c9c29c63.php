<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="http://localhost/zyyj/source/js/ExamProgram_ExamProgram_list.js" ></script>
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/source/css/Class_Class_list.css" />

    <!-- <h2>方案管理</h2> -->
    <!-- <div style="margin:20px 0;"></div> -->
    <table id="examprogram_datagrid" class="easyui-datagrid" style="width:auto;"
        url="http://localhost/zyyj/index.php/Home/ExamProgram/ExamProgram_list_show" toolbar="#examprogram_tb"
        title="全部方案" iconCls="icon-save" singleSelect="true"
        rownumbers="true" pagination="true">
        <div id="examprogram_tb" style="padding:3px">
            <a href="#" class="easyui-linkbutton" iconcls="icon-add" onclick="newExamProgram()"
            plain="true">添加</a>
            <a href="#" class="easyui-linkbutton" iconcls="icon-edit"
                onclick="editExamProgram('http://localhost/zyyj/index.php/Home/ExamProgram/ExamProgram_getInfo')" plain="true">修改</a>
            <a href="#" class="easyui-linkbutton" onclick="delExamProgram('http://localhost/zyyj/index.php/Home/ExamProgram/ExamProgram_delete')" iconcls="icon-remove" plain="true">删除</a>
        </div>
        <thead>
            <tr>
                <th field="name" width="33%" align="center">方案名称</th>
                <!-- <th field="dname" width="20%" >关卡数量</th> -->
                <th field="createdate" width="33%" align="center">更新日期</th>
                <th field="level_num" width="33%" align="center">等级数</th>
                <!-- <th field="comment" width="25%" align="center" >备注</th> -->
                <th field="program_id" style="display: none;" width="" align="center"></th>
                <!-- <th field="handle" width="150">操作</th> -->
            </tr>
        </thead>
    </table>
 
<div id="examprogram_dlg" class="easyui-dialog" style="width: 400px; height: 280px; padding: 10px 20px;"
       closed="true" buttons="#examprogram_dlg-buttons"> 
       <div class="ftitle"> 
           方案信息 
       </div> 
       <form id="examprogram_fm" method="post"> 
       <div class="fitem"> 
           <label> 
               方案名称</label>
           <input name="examprogram_name" id="examprogram_name" class="easyui-validatebox" required="true" style="width:140px"/>
       </div> 
       <!-- <div class="fitem"> 
           <label> 
               总分 
           </label>
           <input name="programme_score" id="programme_score" class="easyui-numberbox" required="true" style="width:140px"/>
       </div>  -->
       <div class="fitem"> 
           <label> 
               等级数量</label> 
           <input name="examprogram_cnum" id="examprogram_cnum" class="easyui-validatebox" readonly="true" value="3" required="true" style="width:140px"/>
       </div> 
       <!-- <div id="using" class="fitem"> 
           <label> 
               已进行考试人数</label> 
           <input name="programme_user" id="programme_user" class="easyui-validatebox" readonly="true" style="width:140px"/> 
       </div>   -->     
       <!-- <div class="fitem"> 
           <label> 
               备注</label> 
           <input name="programme_comment" id="programme_comment" class="easyui-vlidatebox" style="width:140px"/> 
       </div>  -->
       <!-- <input type="hidden" name="action" id="hidtype" />  -->
       <input type="hidden" name="exampro_edit_id" id="exampro_edit_id" /> 
       </form> 
   </div>
<div id="examprogram_dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveExamProgram('http://localhost/zyyj/index.php/Home/ExamProgram/ExamProgram_add')" iconcls="icon-save">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#examprogram_dlg').dialog('close')"
            iconcls="icon-cancel">取消</a>
</div>
<!-- 
</body> -->

<script type="text/javascript" charset="UTF-8">
 $(function(){
    $("#examprogram_datagrid").datagrid({
        fit : true,
        fitColumns : true,
        striped : true,
        pageSize:20,
        onLoadSuccess:function(){
            $("#examprogram_datagrid").datagrid('hideColumn', 'program_id');
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