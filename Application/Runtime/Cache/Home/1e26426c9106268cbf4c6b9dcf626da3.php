<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="http://localhost/zyyj/source/js/Checkpoint_Checkpoint_list.js" ></script>
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/source/css/Class_Class_list.css" />

    <!-- <h2>方案管理</h2> -->
    <!-- <div style="margin:20px 0;"></div> -->
    <table id="checkpoint_datagrid" class="easyui-datagrid" style="width:auto;"
        url="http://localhost/zyyj/index.php/Home/Checkpoint/Checkpoint_list_show" toolbar="#checkpoint_tb"
        title="全部关卡" iconCls="icon-save" singleSelect="true"
        rownumbers="true" pagination="true">
        <div id="checkpoint_tb" style="padding:3px">
            <span>方案名:</span>
            <input id="checkpoint_programme" name="checkpoint_programme" style="width:100px" class="easyui-combobox"
            url="http://localhost/zyyj/index.php/Home/Checkpoint/Checkpoint_getProgramme"
            valueField="programme_id" textField="name" >
            </input>     
            
            <a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="doCheckpointSearch()">查找</a>
            <!-- <a href="#" class="easyui-linkbutton" plain="true" onclick="searchAll()">查找全部</a> -->
            <br>
            <div id="optools" style="height: 5%">
                <a href="#" id="checkpoint_add" class="easyui-linkbutton" iconcls="icon-add" onclick="newCheckpoint()"
                plain="true">添加</a>
                <a href="#" id="checkpoint_update" class="easyui-linkbutton" iconcls="icon-edit"
                    onclick="editCheckpoint('http://localhost/zyyj/index.php/Home/Checkpoint/Checkpoint_getInfo')" plain="true">修改</a>
                <a href="#" id="checkpoint_delete" class="easyui-linkbutton" onclick="delCheckpoint('http://localhost/zyyj/index.php/Home/Checkpoint/Checkpoint_delete')" iconcls="icon-remove" plain="true">删除</a>
            </div>
        </div>
        <thead>
            <tr>
                <th rowspan="2" field="name" width="20%" align="center">关卡名称</th>                
                <th rowspan="2" field="limit_time" width="20%" align="center" >限制时间</th>
                <th rowspan="2" field="total_score" width="20%" align="center" >总分</th>
                <th rowspan="2" field="pass_score" width="20%" align="center" >通关分数</th>
                <th colspan="3" field="comment" width="20%" align="center" >题目数</th>
                <!-- <th field="dname" width="16%" >到达该关卡人数</th> -->
                <th rowspan="2" field="checkpoint_id" style="display: none;" width="" align="center"></th>

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
 
<div id="checkpoint_dlg" class="easyui-dialog" style="width: 400px; height: 280px; padding: 10px 20px;"
       closed="true" buttons="#checkpoint_dlg-buttons"> 
       <div class="ftitle"> 
           关卡信息 
       </div> 
       <form id="checkpoint_fm" method="post"> 
         <div class="fitem"> 
             <label> 
                 关卡名称</label>
             <input name="checkpoint_name" id="checkpoint_name" class="easyui-validatebox" required="true" style="width:100px"/>
         </div> 
         <div class="fitem"> 
             <label> 
                 总分 
             </label>
             <input name="checkpoint_tscore" id="checkpoint_tscore" class="easyui-numberbox" required="true" style="width:100px" readonly="true" />
         </div> 
         <div class="fitem"> 
             <label> 
                 通关分数</label> 
             <input name="checkpoint_pscore" id="checkpoint_pscore" class="easyui-numberbox" required="true" style="width:100px" />
         </div>
         <div class="fitem"> 
         <label>题目设置</label>
             难
             <input name="edit_checkpoint_difficult" id="edit_checkpoint_difficult" class="easyui-numberbox" required="true" style="width:30px" />
             中 
             <input name="edit_checkpoint_middle" id="edit_checkpoint_middle" class="easyui-numberbox" required="true" style="width:30px"/>
             易 
             <input name="edit_checkpoint_easy" id="edit_checkpoint_easy" class="easyui-numberbox" required="true" style="width:30px"/>
         </div>  
         <div class="fitem"> 
             <label> 
                 限制时间</label> 
             <input name="checkpoint_limit_time" id="checkpoint_limit_time" class="easyui-numberbox" required="true" style="width:100px"/> 分钟
         </div>       
         <div class="fitem" id="arrpeo"> 
             <label> 
                 已到达人数</label> 
             <input name="checkpoint_arrpeo" id="checkpoint_arrpeo" disabled="true " class="easyui-vlidatebox" style="width:100px"/> 
         </div> 
         <!-- <input type="hidden" name="action" id="hidtype" />  -->
         <input type="hidden" name="checkpoint_edit_id" id="checkpoint_edit_id" value="" />
         <input type="hidden" name="programme_edit_id" id="programme_edit_id" value="" />
         <input type="hidden" name="total_checkpoint" id="total_checkpoint" value="" />
         <input type="hidden" name="programmecount" id="programmecount" value="" />
       </form> 
   </div>
<div id="checkpoint_dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveCheckpoint('http://localhost/zyyj/index.php/Home/Checkpoint/Checkpoint_add')" iconcls="icon-save">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#checkpoint_dlg').dialog('close')"
            iconcls="icon-cancel">取消</a>
</div>
<!-- 
</body> -->

<script type="text/javascript" charset="UTF-8">
 $(function(){
    $("#checkpoint_datagrid").datagrid({
        fit : true,
        fitColumns : true,
        striped : true,
        pageSize:20,
        onLoadSuccess:function(data){
            // alert($('#programme_edit_id').val())
            var checkpoint_programme = $('#checkpoint_programme').combobox('getValue');
            // alert(checkpoint_programme);
            $("#checkpoint_datagrid").datagrid('hideColumn', 'checkpoint_id');
            if(data['editable'] != 0){
                $("#checkpoint_add").hide();
                $("#checkpoint_delete").hide();
                $("#checkpoint_update").hide();
                // $("#checkpoint_add").css("display","none");
                // $("#checkpoint_delete").css("display","none");
                // $("#checkpoint_update").css("display","none");
                // $("#checkpoint_update").text('查看');
                // alert(data['editable']);
            }else{
                $("#checkpoint_add").show();
                $("#checkpoint_delete").show();
                $("#checkpoint_update").show();
                // $("#checkpoint_datagrid").datagrid({
                //     fit : true,
                //     fitColumns : true,
                //     striped : true,
                //     onLoadSuccess:function(data){
                //         return;
                //     }
                // });
                // $("#checkpoint_add").css("display","inline-block");
                // $("#checkpoint_delete").css("display","inline-block");
                // $("#checkpoint_update").css("display","inline-block");
            }
            // alert(checkpoint_programme);
            $('#programme_edit_id').val(checkpoint_programme);
            $('#total_checkpoint').val(data['total']);
            $('#programmecount').val(data['pcounts']);
            
        }
    });
    
    $('#checkpoint_programme').combobox({
            
            onLoadSuccess: function (data) {
             if (data) {
                var total = parseInt($('#programmecount').val())-1;
                 // alert(data[total-1].programme_id);
                 $('#checkpoint_programme').combobox('setValue',data[0].programme_id);
             }
         }
    });
    $("#edit_checkpoint_easy").numberbox({  
        "onChange":function(){  
            countTotalScore();
        }  
    });
    $("#edit_checkpoint_middle").numberbox({  
        "onChange":function(){  
            countTotalScore();
        }  
    });
    $("#edit_checkpoint_difficult").numberbox({  
        "onChange":function(){  
            countTotalScore();
        }  
    });

 })
</script>
<!-- </html> -->