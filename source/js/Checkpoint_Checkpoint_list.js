 var url;
 var type;
//新增关卡
 function newCheckpoint() {
    // alert("newuser");
     var checkpoint_programme = $('#programme_edit_id').val();
     var total_checkpoint = $('#total_checkpoint').val(); //关卡总数
     // alert("beforeOpen:::"+checkpoint_programme);
     $("#checkpoint_dlg").dialog("open").dialog('setTitle', '添加关卡');
     
     // alert("afterOpen:::"+$('#programme_edit_id').val());
     $("#checkpoint_fm").form("clear");

     $('#total_checkpoint').val(total_checkpoint);
     $('#checkpoint_name').val('第'+(parseInt(total_checkpoint)+1)+'关'); 
     $('#programme_edit_id').val(checkpoint_programme); 
     $('#checkpoint_name').attr("readonly","true"); //设置文本框只读
     // $('#checkpoint_tscore').attr("readonly","true");
     $("#arrpeo").css("display","none");
     // url = "UserManage.aspx";
     // document.getElementById("hidtype").value = "submit";
 }
//修改关卡
 function editCheckpoint(url) {
     var row = $("#checkpoint_datagrid").datagrid("getSelected");
     if(row == null){
        alert("请先选择行");
     }
     if (row) {
         $("#checkpoint_dlg").dialog("open").dialog('setTitle', '修改关卡');
          // $("#using").css("display","block");
         // alert(row.programme_id);
         $.post(url, {
             id: row.checkpoint_id
         }, function(result) {
            // alert(result['user']);
            var user = parseInt(result['editable']);
            // var editable = result['arrp'];
            $("#checkpoint_name").val(result['others'][0].name);        
            // $("#checkpoint_tscore").val(result['others'][0].total_score);
            // $("#checkpoint_pscore").val(result['others'][0].pass_score);
            // $("#edit_checkpoint_difficult").val(result['others'][0].c_difficult);
            // $("#edit_checkpoint_middle").val(result['others'][0].c_middle);
            // $("#edit_checkpoint_easy").val(result['others'][0].c_easy);
            // $("#checkpoint_limit_time").val(result['others'][0].limit_time);
            $("#checkpoint_arrpeo").val(result['arrp']);
            $("#checkpoint_edit_id").val(result['others'][0].checkpoint_id);

            $('#checkpoint_tscore').numberbox('setValue', result['others'][0].total_score);
            $('#checkpoint_pscore').numberbox('setValue', result['others'][0].pass_score);
            $('#edit_checkpoint_difficult').numberbox('setValue', result['others'][0].c_difficult);
            $('#edit_checkpoint_middle').numberbox('setValue', result['others'][0].c_middle);
            $('#edit_checkpoint_easy').numberbox('setValue', result['others'][0].c_easy);
            $('#checkpoint_limit_time').numberbox('setValue', result['others'][0].limit_time);
            // $("#comment").val(result['other'].ccomment);
            if(user==0){
                // $("#checkpoint_cnum").removeAttr("disabled");
                var _form = document.getElementById("checkpoint_dlg");
                //获取该表单下的所有input标签
                var _inputs = _form.getElementsByTagName("input");
                for(var i = 0; i < _inputs.length; i++){
                    $("#"+_inputs[i].id).removeAttr("readonly");
                }
                $("#arrpeo").css("display","none");
            }else{
                // alert(user);
                var _form = document.getElementById("checkpoint_dlg");
                //获取该表单下的所有input标签
                var _inputs = _form.getElementsByTagName("input");
                for(var i = 0; i < _inputs.length; i++){
                    $("#"+_inputs[i].id).attr("readonly","true");
                }
                $("#arrpeo").css("display","block");
            }
             $('#checkpoint_name').attr("readonly","true");  
             countTotalScore();
         }, 'json');
     }
 }
//保存
 function saveCheckpoint(url) {
     // var id = $('#edit_id').val();
      // alert($('#programme_edit_id').val());
      // alert(url);
    var flag = checkDataLegal();
    if(!flag){
        return false;
    }else{
        $("#checkpoint_fm").form("submit", {
            url: url,
            onsubmit: function() {
             
                 return $(this).form("validate");
            },
            success: function(result) {
                 if (result) {
                     $.messager.alert("提示信息", "操作成功");
                     $("#checkpoint_dlg").dialog("close");
                     $("#checkpoint_datagrid").datagrid("load");
                 } else {
                     $.messager.alert("提示信息", "操作失败");
                 }
            }
        });
    }
     
 }

//搜索按钮事件
 function doCheckpointSearch() {
    var checkpoint_programme = $('#checkpoint_programme').combobox('getValue');
    // $('#programme_edit_id').val(checkpoint_programme);
    var _form = document.getElementById("checkpoint_dlg");
    var _inputs = _form.getElementsByTagName("input");
    for(var i = 0; i < _inputs.length; i++){
        $("#"+_inputs[i].id).removeAttr("readonly");
    }
    // $("#arrpeo").css("display","none");
     $('#checkpoint_datagrid').datagrid('loadData', { total: 0, rows: [] });
     $('#checkpoint_datagrid').datagrid('load',{
        // url:url,
            checkpoint_programme: checkpoint_programme
        
    });
 }

//删除关卡按钮事件
 function delCheckpoint(url) {
     var row = $('#checkpoint_datagrid').datagrid('getSelected');
     var pid = $('#programme_edit_id').val();
     if(row == null){
        alert("请先选择行");
     }
     // alert(row.cid);
     if (row) {
         $.messager.confirm('警告', '确认删除吗？', function(r) {
             if (r) {
                 $.post(url, {
                     id: row.checkpoint_id,
                     pid: pid
                 }, function(result) {
                    // alert(result);
                     if (result == "1") {
                         // alert("success");
                         $.messager.alert("提示信息", "操作成功");
                         $('#checkpoint_datagrid').datagrid('reload'); // reload the user data  
                     } else {
                         $.messager.alert("提示信息", "操作失败");
                         $.messager.show({ // show error message  
                             title: '错误',
                             msg: "存在已使用该方案的学生，无法删除"
                         });
                     }
                 }, 'json');
             }
         });
     }
 }

//检测通过分数是否超过总分
function checkDataLegal(){
    var checkpoint_pscore = parseInt($("#checkpoint_pscore").numberbox('getValue'));  //及格分
    var checkpoint_tscore = parseInt($('#checkpoint_tscore').numberbox('getValue')); //总分

    if (checkpoint_tscore < checkpoint_pscore) {
        $.messager.alert("提示信息", "通过分数不能大于总分");
        return false;
    }
    return true;
}

 //计算题目总分（每题1分）
function countTotalScore(){
    var cd = parseInt($("#edit_checkpoint_difficult").val()); //难题数
    var cm = parseInt($("#edit_checkpoint_middle").val()); //中题数
    var ce = parseInt($("#edit_checkpoint_easy").val()); //易题数
    var total = ce+cm+cd;
    $('#checkpoint_tscore').numberbox('setValue', total);    //总分
}
