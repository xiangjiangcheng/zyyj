 var url;
 var type;
//新增关卡
 function newExamLevel() {
    // alert("newuser");
     var exam_level_programme = $('#program_edit_id').val();
     var total_exam_level = $('#total_exam_level').val(); //关卡总数
     // alert("beforeOpen:::"+exam_level_programme);
     $("#exam_level_dlg").dialog("open").dialog('setTitle', '添加关卡');
     
     // alert("afterOpen:::"+$('#programme_edit_id').val());
     $("#exam_level_fm").form("clear");

     $('#total_exam_level').val(total_exam_level);
     $('#exam_level_name').val('第'+(parseInt(total_exam_level)+1)+'关'); 
     $('#program_edit_id').val(exam_level_programme); 
     $('#exam_level_name').attr("readonly","true"); //设置文本框只读
     $("#arrpeo").css("display","none");
     // url = "UserManage.aspx";
     // document.getElementById("hidtype").value = "submit";
 }
//修改关卡
 function editExamLevel(url) {
     var row = $("#exam_level_datagrid").datagrid("getSelected");
     if(row == null){
        alert("请先选择行");
     }
     if (row) {
         $("#exam_level_dlg").dialog("open").dialog('setTitle', '修改关卡');
          // $("#using").css("display","block");
         // alert(row.programme_id);
         $.post(url, {
             id: row.level_id
         }, function(result) {
            // alert(result['user']);
            var user = parseInt(result['editable']);
            // var editable = result['arrp'];
            $("#exam_level_name").val(result['others'][0].name);        
            // $("#exam_level_tscore").val(result['others'][0].total_score);
            // $("#exam_level_pscore").val(result['others'][0].pass_score);
            // $("#edit_exam_level_difficult").val(result['others'][0].c_difficult);
            // $("#edit_exam_level_middle").val(result['others'][0].c_middle);
            // $("#edit_exam_level_easy").val(result['others'][0].c_easy);
            // $("#exam_level_limit_time").val(result['others'][0].limit_time);
            $("#exam_level_arrpeo").val(result['arrp']);
            $("#exam_level_edit_id").val(result['others'][0].level_id);

            $('#exam_level_tscore').numberbox('setValue', result['others'][0].total_score);
            $('#exam_level_pscore').numberbox('setValue', result['others'][0].pass_score);
            $('#edit_exam_level_difficult').numberbox('setValue', result['others'][0].c_difficult);
            $('#edit_exam_level_middle').numberbox('setValue', result['others'][0].c_middle);
            $('#edit_exam_level_easy').numberbox('setValue', result['others'][0].c_easy);
            $('#exam_level_limit_time').numberbox('setValue', result['others'][0].limit_time);
            // $("#comment").val(result['other'].ccomment);
            if(user==0){
                // $("#exam_level_cnum").removeAttr("disabled");
                var _form = document.getElementById("exam_level_dlg");
                //获取该表单下的所有input标签
                var _inputs = _form.getElementsByTagName("input");
                for(var i = 0; i < _inputs.length; i++){
                    $("#"+_inputs[i].id).removeAttr("readonly");
                }
                $("#arrpeo").css("display","none");
            }else{
                // alert(user);
                var _form = document.getElementById("exam_level_dlg");
                //获取该表单下的所有input标签
                var _inputs = _form.getElementsByTagName("input");
                for(var i = 0; i < _inputs.length; i++){
                    $("#"+_inputs[i].id).attr("readonly","true");
                }
                $("#arrpeo").css("display","block");
            }
             $('#exam_level_name').attr("readonly","true");  
             countExamTotalScore();
         }, 'json');
     }
 }
//保存
 function saveExamLevel(url) {
     // var id = $('#edit_id').val();
      // alert($('#programme_edit_id').val());
      // alert(url);
    var flag = checkExamDataLegal();
    if(!flag){
        return false;
    }else{
     $("#exam_level_fm").form("submit", {
         url: url,
         // id: id,
         onsubmit: function() {
             // alert("submit");
             return $(this).form("validate");
         },
         success: function(result) {
                // alert(result);
                // var a = $.trim('result');
                // var b = "1";
             if (result) {
                 $.messager.alert("提示信息", "操作成功");
                 $("#exam_level_dlg").dialog("close");
                 $("#exam_level_datagrid").datagrid("load");
             } else {
                 $.messager.alert("提示信息", "操作失败");
             }
         }
     });
    }
 }

//搜索按钮事件
 function doExamLevelSearch() {
    var exam_level_programme = $('#exam_level_programme').combobox('getValue');
    // $('#programme_edit_id').val(exam_level_programme);
    var _form = document.getElementById("exam_level_dlg");
    var _inputs = _form.getElementsByTagName("input");
    for(var i = 0; i < _inputs.length; i++){
        $("#"+_inputs[i].id).removeAttr("readonly");
    }
    // $("#arrpeo").css("display","none");
     $('#exam_level_datagrid').datagrid('loadData', { total: 0, rows: [] });
     $('#exam_level_datagrid').datagrid('load',{
        // url:url,
            exam_level_programme: exam_level_programme
        
    });
 }

//删除关卡按钮事件
 function delExamLevel(url) {
     var row = $('#exam_level_datagrid').datagrid('getSelected');
     var pid = $('#programme_edit_id').val();
     if(row == null){
        alert("请先选择行");
     }
     // alert(row.cid);
     if (row) {
         $.messager.confirm('警告', '确认删除吗？', function(r) {
             if (r) {
                 $.post(url, {
                     id: row.exam_level_id,
                     pid: pid
                 }, function(result) {
                    // alert(result);
                     if (result == "1") {
                         // alert("success");
                         $.messager.alert("提示信息", "操作成功");
                         $('#exam_level_datagrid').datagrid('reload'); // reload the user data  
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
function checkExamDataLegal(){
    var checkpoint_pscore = parseInt($("#exam_level_pscore").val());  //及格分
    var checkpoint_tscore = parseInt($('#exam_level_tscore').val());  //总分
    if (checkpoint_tscore < checkpoint_pscore) {
        $.messager.alert("提示信息", "通过分数不能大于总分");
        return false;
    }
    return true;
}

 //计算题目总分（每题1分）
function countExamTotalScore(){
    var cd = parseInt($("#edit_exam_level_difficult").val()); //难题数
    var cm = parseInt($("#edit_exam_level_middle").val()); //中题数
    var ce = parseInt($("#edit_exam_level_easy").val()); //易题数
    var total = ce+cm+cd;
    $('#exam_level_tscore').numberbox('setValue', total);    //总分
}

