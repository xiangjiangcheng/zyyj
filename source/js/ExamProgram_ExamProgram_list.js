 var url;
 var type;
//新增方案
 function newExamProgram() {
     $("#examprogram_dlg").dialog("open").dialog('setTitle', '添加方案');;
     $("#examprogram_fm").form("clear");
     $("#examprogram_cnum").val("3");
     $("#examprogram_name").removeAttr("disabled");
     
     // $("#using").css("display","none");
     
 }
//修改方案
 function editExamProgram(url) {
     var row = $("#examprogram_datagrid").datagrid("getSelected");
     if(row == null){
        alert("请先选择行");
     }
     $("#examprogram_cnum").val("3");
     if (row) {
         $("#examprogram_dlg").dialog("open").dialog('setTitle', '修改方案');
          // $("#using").css("display","block");
         // alert(row.program_id);
         $.post(url, {
             id: row.program_id
         }, function(result) {
            var user = parseInt(result['user']);
            $("#examprogram_name").val(result['others'][0].name);            
            
            $("#exampro_edit_id").val(result['others'][0].program_id);
            if(user==0){
                // $("#examprogram_cnum").numberbox({  
                //     readonly:false  
                // }); 
                // $("#programme_score").numberbox({  
                //     readonly:false  
                // }); 
                // $('#programme_cnum').numberbox('disabled', false);
                // $('#programme_score').numberbox('disabled', false);
                $("#examprogram_name").removeAttr("disabled");
                // $("#programme_score").removeAttr("disabled");
            }else{
                // $("#examprogram_cnum").numberbox({  
                //     readonly:true  
                // });
                // $("#programme_score").numberbox({  
                //     readonly:true  
                // });  
                // $('#programme_cnum').numberbox('disabled', true);
                // $('#programme_score').numberbox('disabled', true);
                $("#examprogram_name").attr("disabled","true");
                // $("#programme_score").attr("disabled","true");
            }  
         }, 'json');
     }
 }
//保存
 function saveExamProgram(url) {
     // var id = $('#edit_id').val();
     // alert($("#program_edit_id").val())
     $("#examprogram_fm").form("submit", {
         url: url,
         // id: id,
         onsubmit: function() {
             return $(this).form("validate");
         },
         success: function(result) {
                // alert(result);
                // var a = $.trim('result');
                // var b = "1";
             if (result == "1") {
                 $.messager.alert("提示信息", "操作成功");
                 $("#examprogram_dlg").dialog("close");
                 $("#examprogram_datagrid").datagrid("load");
                 if ($('#tabs').tabs('exists', '等级管理')) {
                        $('#tabs').tabs('close','等级管理');
                        // $('#tabs').tabs('select', '关卡管理');
                 }
                 // } else {
                $('#tabs').tabs('add', {
                        title : '等级管理',
                        //iconCls : node.iconCls,
                        closable : true,
                        href : "http://localhost/zyyj/index.php/Home/ExamLevel/ExamLevel_list",
                });
                    
                // }
             }else if(result == "2"){
                 $.messager.alert("提示信息","存在方案同名，操作失败");
             }else if(result == "3"){
                 $.messager.alert("提示信息","操作成功");
                 $("#examprogram_dlg").dialog("close");
                 $("#examprogram_datagrid").datagrid("load");
             } else {
                 $.messager.alert("提示信息", "操作失败");
             }
         }
     });
 }

//删除方案按钮事件
 function delExamProgram(url) {
     var row = $('#examprogram_datagrid').datagrid('getSelected');
     if(row == null){
        alert("请先选择行");
     }
     // alert(row.cid);
     if (row) {
         $.messager.confirm('警告', '确认删除吗？', function(r) {
             if (r) {
                 $.post(url, {
                     id: row.program_id
                 }, function(result) {
                    // alert(result);
                     if (result == "1") {
                         // alert("success");
                         $.messager.alert("提示信息", "操作成功");
                         $('#examprogram_datagrid').datagrid('reload'); // reload the user data  
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

//  $(function(){
//     $.post("/xxx/xx/?action=xxx",function(data){
//             $('#aaa').html(data);  //data为返回的拼好的下拉内容
//     });
// })

//级联下拉框（id:选中项的id，sid:需要级联的下拉框id）
function cascade(url,id,sid){
    var mid = '#'+id;
    var gid = '#'+sid;
    // alert('mid:'+mid);
    var value = $(mid).combobox('getValue');
    // alert('value:   '+value);
    var url = url+'/id/'+value;
    // alert(url);
    // $(gid).val() = "";  
    $(gid).combobox('clear');
    $(gid).combobox('reload', url);
    //  $.post(url, {
    //                  id: value
    //              }, function(result) {
    //                 $('#cc').combobox('reload', result);
    //                 // $(gid).combobox.loadData(result);
    //                 // $(gid).combobox("clear") 
    //                 // alert(result);
    //                 // if (result == "1") {
    //                 //      // alert("success");
    //                 //      $('#tt').datagrid('reload'); // reload the user data  
    //                 // } else {

    //                 // }
    // }, 'json');
}