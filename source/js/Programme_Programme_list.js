 var url;
 var type;
//新增方案
 function newProgramme() {
    // alert("newuser");
     $("#programme_dlg").dialog("open").dialog('setTitle', '添加方案');;
     $("#programme_fm").form("clear");
     $("#programme_cnum").numberbox({  
                    readonly:false  
     }); 
     $("#programme_score").numberbox({  
        readonly:false  
     });
     $("#using").css("display","none");
     
 }
//修改方案
 function editProgramme(url) {
     var row = $("#pro_datagrid").datagrid("getSelected");
     if(row == null){
        alert("请先选择行");
     }
     if (row) {
         $("#programme_dlg").dialog("open").dialog('setTitle', '修改方案');
          $("#using").css("display","block");
         // alert(row.programme_id);
         $.post(url, {
             id: row.programme_id
         }, function(result) {
            // alert(result['user']);
            var user = parseInt(result['user']);
            $("#programme_name").val(result['others'][0].name);            
            // $("#programme_score").val(result['others'][0].score);
            $('#programme_score').numberbox('setValue', result['others'][0].score);
            $("#programme_comment").val(result['others'][0].comment);
            // $("#programme_cnum").val(result['c_num']);
            $('#programme_cnum').numberbox('setValue', result['c_num']);
            $("#programme_user").val(result['user']);
            $("#program_edit_id").val(result['others'][0].programme_id);
            // alert($("#program_edit_id").val());
            // $("#comment").val(result['other'].ccomment);
            if(user==0){
                $("#programme_cnum").numberbox({  
                    readonly:true  
                }); 
                $("#programme_score").numberbox({  
                    readonly:false  
                }); 
                // $('#programme_cnum').numberbox('disabled', false);
                // $('#programme_score').numberbox('disabled', false);
                // $("#programme_cnum").removeAttr("disabled");
                // $("#programme_score").removeAttr("disabled");
            }else{
                $("#programme_cnum").numberbox({  
                    readonly:true  
                });
                $("#programme_score").numberbox({  
                    readonly:true  
                });  
                // $('#programme_cnum').numberbox('disabled', true);
                // $('#programme_score').numberbox('disabled', true);
                // $("#programme_cnum").attr("disabled","true");
                // $("#programme_score").attr("disabled","true");
            }  
         }, 'json');
     }
 }
//保存
 function saveProgramme(url) {
     // var id = $('#edit_id').val();
     // alert($("#program_edit_id").val())
     $("#programme_fm").form("submit", {
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
                 $("#programme_dlg").dialog("close");
                 $("#pro_datagrid").datagrid("load");
                 if ($('#tabs').tabs('exists', '关卡管理')) {
                        $('#tabs').tabs('close','关卡管理');
                        // $('#tabs').tabs('select', '关卡管理');
                 }
                 // } else {
                    $('#tabs').tabs('add', {
                            title : '关卡管理',
                            //iconCls : node.iconCls,
                            closable : true,
                            href : "http://localhost/zyyj/index.php/Home/Checkpoint/Checkpoint_list",
                    });
                    
                // }
             }else if(result == "2"){
                 $.messager.alert("提示信息","存在方案同名，添加失败");
             }else if(result == "3"){
                 $.messager.alert("提示信息","操作成功");
                 $("#programme_dlg").dialog("close");
                 $("#pro_datagrid").datagrid("load");
             } else {
                 $.messager.alert("提示信息", "操作失败");
             }
         }
     });
 }

//删除方案按钮事件
 function delProgramme(url) {
     var row = $('#pro_datagrid').datagrid('getSelected');
     if(row == null){
        alert("请先选择行");
     }
     // alert(row.cid);
     if (row) {
         $.messager.confirm('警告', '确认删除吗？', function(r) {
             if (r) {
                 $.post(url, {
                     id: row.programme_id
                 }, function(result) {
                    // alert(result);
                     if (result == "1") {
                         // alert("success");
                         $.messager.alert("提示信息", "操作成功");
                         $('#pro_datagrid').datagrid('reload'); // reload the user data  
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