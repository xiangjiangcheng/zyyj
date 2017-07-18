 var url;
 var type;
//新增班级
 function newClass() {
    // alert("newuser");
     $("#dlg").dialog("open").dialog('setTitle', '添加班级');;
     $("#fm").form("clear");
     // url = "UserManage.aspx";
     // document.getElementById("hidtype").value = "submit";
 }
//修改班级
 function editClass(url) {
    // alert('editClass');
     // var mid = '#'+id;
     // var gid = '#'+sid;
     var row = $("#c_datagrid").datagrid("getSelected");
     if(row == null){
        alert("请先选择行");
     }
     // var url = url+'/id/'+row.cid;
     if (row) {
         $("#dlg").dialog("open").dialog('setTitle', '修改班级');

         // $("#fm").form("load", row);
         // url = url;

         $.post(url, {
             id: row.cid
         }, function(result) {
            // alert(result[0].cname);
            $("#name").val(result[0].cname);            
            $("#edit_id").val(result[0].cid);
            $("#comment").val(result[0].ccomment);
            $('#major').combobox('select', result[0].mid);
            $('#grade').combobox('select', result[0].gid);
            // $('#department').combobox('select', result[0].did);
            // alert(result);
            // $("#fm").form("load", result);
             // if (result == "1") {
             //     // alert("success");
             //     $.messager.alert("提示信息", "操作成功");
             //     $('#tt').datagrid('reload'); // reload the user data  
             // } else {
             //     $.messager.alert("提示信息", "操作失败");
             //     $.messager.show({ // show error message  
             //         title: 'Error',
             //         msg: result.errorMsg
             //     });
             // }
         }, 'json');
     }
 }
//保存
 function saveClass(url) {
     // var id = $('#edit_id').val();
     $("#fm").form("submit", {
         url: url,
         // id: id,
         onsubmit: function() {
             return $(this).form("validate");
         },
         success: function(result) {
                // alert(result);
                // var a = $.trim('result');
                // var b = "1";
             if (result == '1') {
                 $.messager.alert("提示信息", "操作成功");
                 $("#dlg").dialog("close");
                 $("#c_datagrid").datagrid("load");
             } else if (result == '2') {
                 $.messager.alert("提示信息", "存在相同班级，操作失败");
             } else {
                 $.messager.alert("提示信息", "操作失败");
             }
         }
     });
 }

//搜索按钮事件
 function doClassSearch() {
    var major = $('#s_major').combobox('getValue');
    var grade = $('#s_grade').combobox('getValue');
    // if(major == ''){
    //     grade = $('#s_grade').combobox('getText');
    // }else{
        // grade = $('#s_grade').combobox('getValue');
    // }
    
    // var department = $('#s_department').combobox('getValue');
     // $.post(url, {
     //                 major: major,
     //                 grade: grade,
     //                 department: department
     //             }, function(result) {
                                       
     //             }, 'json');
     $('#c_datagrid').datagrid('loadData', { total: 0, rows: [] });
     $('#c_datagrid').datagrid('load',{
        // url:url,
            major: major,
            grade: grade,
        
    });
 }
 //查询全部按钮
function searchAll(){
    $data = "";
    $('#s_major').combobox('clear');
    $('#s_grade').combobox('clear');
    // $('#s_grade').combobox('loadData', {});
    // $('#s_department').combobox('clear');
    $('#c_datagrid').datagrid('load',{
        // url:url,
            major: '',
            grade: '',
        
    });
}
//删除班级按钮事件
 function delClass(url) {
     var row = $('#c_datagrid').datagrid('getSelected');
     if(row == null){
        alert("请先选择行");
     }
     // alert(row.cid);
     if (row) {
         $.messager.confirm('警告', '删除此班级将会删除挂靠在此班级上的所有学生，确认删除吗？', function(r) {
             if (r) {
                 $.post(url, {
                     id: row.cid
                 }, function(result) {
                    // alert(result);
                     if (result == "1") {
                         // alert("success");
                         $.messager.alert("提示信息", "操作成功");
                         $('#c_datagrid').datagrid('reload'); // reload the user data  
                     } else {
                         $.messager.alert("提示信息", "操作失败");
                         $.messager.show({ // show error message  
                             title: 'Error',
                             msg: result.errorMsg
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