<script type="text/javascript" src="__SITE__source/js/ExamLevel_ExamLevel_list.js" ></script>
<link rel="stylesheet" type="text/css" href="__SITE__source/css/Class_Class_list.css" />

    <!-- <h2>方案管理</h2> -->
    <!-- <div style="margin:20px 0;"></div> -->
    <table id="exam_level_datagrid" class="easyui-datagrid" style="width:auto;"
        url="__SITE__index.php/Home/ExamLevel/ExamLevel_list_show" toolbar="#exam_level_tb"
        title="全部等级" iconCls="icon-save" singleSelect="true"
        rownumbers="true" pagination="true">
        <div id="exam_level_tb" style="padding:3px">
            <span>方案名:</span>
            <input id="exam_level_programme" name="exam_level_programme" style="width:100px" class="easyui-combobox"
            url="__SITE__index.php/Home/ExamLevel/ExamLevel_getProgramme"
            valueField="program_id" textField="name" >
            </input>     
            
            <a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="doExamLevelSearch()">查找</a>
            <!-- <a href="#" class="easyui-linkbutton" plain="true" onclick="searchAll()">查找全部</a> -->
            <br>
            <div id="eltool" style="height: 5%">
                <!-- <a href="#" id="exam_level_add" class="easyui-linkbutton" iconcls="icon-add" onclick="newExamLevel()"
                plain="true">添加</a> -->
                <a href="#" id="exam_level_update" class="easyui-linkbutton" iconcls="icon-edit"
                    onclick="editExamLevel('__SITE__index.php/Home/ExamLevel/ExamLevel_getInfo')" plain="true">修改</a>
                <!-- <a href="#" id="exam_level_delete" class="easyui-linkbutton" onclick="delExamLevel('__SITE__index.php/Home/ExamLevel/ExamLevel_delete')" iconcls="icon-remove" plain="true">删除</a> -->
            </div>
        </div>
        <thead>
            <tr>
                <th rowspan="2" field="name" width="20%" align="center">等级名称</th>                
                <th rowspan="2" field="limit_time" width="20%" align="center" >限制时间</th>
                <th rowspan="2" field="total_score" width="20%" align="center" >总分</th>
                <th rowspan="2" field="pass_score" width="20%" align="center" >通关分数</th>
                <th colspan="3" field="comment" width="20%" align="center" >题目数</th>
                <!-- <th field="dname" width="16%" >到达该关卡人数</th> -->
                <th rowspan="2" field="level_id" style="display: none;" width="" align="center"></th>

                <!-- <th field="handle" width="150">操作</th> -->
            </tr>
            <tr>
                <th field="c_difficult" width="80" align="center">难</th>
                <th field="c_middle" width="80" align="center">中</th>
                <th field="c_easy" width="100" align="center">易</th>
                <!-- <th field="status" width="60" align="center">Stauts</th> -->
            </tr>
        </thead>
    </table>
 
<div id="exam_level_dlg" class="easyui-dialog" style="width: 400px; height: 280px; padding: 10px 20px;"
       closed="true" buttons="#exam_level_dlg-buttons"> 
       <div class="ftitle"> 
           等级信息 
       </div> 
       <form id="exam_level_fm" method="post"> 
         <div class="fitem"> 
             <label> 
                 等级名称</label>
             <input name="exam_level_name" id="exam_level_name" class="easyui-validatebox" required="true" style="width:100px"/>
         </div> 
         <div class="fitem"> 
             <label> 
                 总分 
             </label>
             <input name="exam_level_tscore" id="exam_level_tscore" class="easyui-numberbox" required="true" style="width:100px"/>
         </div> 
         <div class="fitem"> 
             <label> 
                 通关分数</label> 
             <input name="exam_level_pscore" id="exam_level_pscore" class="easyui-numberbox" required="true" style="width:100px"/>
         </div>
         <div class="fitem"> 
         <label>题目设置</label>
             难
             <input name="edit_exam_level_difficult" id="edit_exam_level_difficult" class="easyui-numberbox" required="true" style="width:30px"/>
             中 
             <input name="edit_exam_level_middle" id="edit_exam_level_middle" class="easyui-numberbox" required="true" style="width:30px"/>
             易 
             <input name="edit_exam_level_easy" id="edit_exam_level_easy" class="easyui-numberbox" required="true" style="width:30px"/>
         </div>  
         <div class="fitem"> 
             <label> 
                 限制时间</label> 
             <input name="exam_level_limit_time" id="exam_level_limit_time" class="easyui-numberbox" required="true" style="width:100px"/> 分钟
         </div>       
         <!-- <div class="fitem" id="arrpeo"> 
             <label> 
                 已到达人数</label> 
             <input name="exam_level_arrpeo" id="exam_level_arrpeo" disabled="true " class="easyui-vlidatebox" style="width:100px"/> 
         </div>  -->
         <!-- <input type="hidden" name="action" id="hidtype" />  -->
         <input type="hidden" name="exam_level_edit_id" id="exam_level_edit_id" value="" />
         <input type="hidden" name="program_edit_id" id="program_edit_id" value="" />
         <input type="hidden" name="total_exam_level" id="total_exam_level" value="" />
         <input type="hidden" name="programmecount" id="programmecount" value="" />
       </form> 
   </div>
<div id="exam_level_dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveExamLevel('__SITE__index.php/Home/ExamLevel/ExamLevel_add')" iconcls="icon-save">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#exam_level_dlg').dialog('close')"
            iconcls="icon-cancel">取消</a>
</div>
<!-- 
</body> -->

<script type="text/javascript" charset="UTF-8">
 $(function(){
    $("#exam_level_datagrid").datagrid({
        fit : true,
        fitColumns : true,
        striped : true,
        pageSize:20,
        onLoadSuccess:function(data){
            // alert($('#programme_edit_id').val())
            var exam_level_programme = $('#exam_level_programme').combobox('getValue');
            // alert(exam_level_programme);
            $("#exam_level_datagrid").datagrid('hideColumn', 'level_id');
            if(data['editable'] != 0){
                // $("#exam_level_add").hide();
                // $("#exam_level_delete").hide();
                $("#exam_level_update").hide();
                // $("#exam_level_add").css("display","none");
                // $("#exam_level_delete").css("display","none");
                // $("#exam_level_update").css("display","none");
                // $("#exam_level_update").text('查看');
                // alert(data['editable']);
            }else{
                // $("#exam_level_add").show();
                // $("#exam_level_delete").show();
                $("#exam_level_update").show();
                // $("#exam_level_add").css("display","inline-block");
                // $("#exam_level_delete").css("display","inline-block");
                // $("#exam_level_update").css("display","inline-block");
            }
            // alert(exam_level_programme);
            $('#programme_edit_id').val(exam_level_programme);
            $('#total_exam_level').val(data['total']);
            $('#programmecount').val(data['pcounts']);
            
        }
    });
    
    $('#exam_level_programme').combobox({
            
            onLoadSuccess: function (data) {
             if (data) {
                var total = parseInt($('#programmecount').val())-1;
                 // alert(data[total-1].programme_id);
                 $('#exam_level_programme').combobox('setValue',data[0].program_id);
             }
         }
    });
    $("#edit_exam_level_easy").numberbox({  
        "onChange":function(){  
            countExamTotalScore();
        }  
    });
    $("#edit_exam_level_middle").numberbox({  
        "onChange":function(){  
            countExamTotalScore();
        }  
    });
    $("#edit_exam_level_difficult").numberbox({  
        "onChange":function(){  
            countExamTotalScore();
        }  
    });

 })
</script>
<!-- </html> -->