(function($) {
    //json序列化表单数据
    $.fn.serializeJson = function(type) {
        var serializeObj = { type: type };
        $(this.serializeArray()).each(function() {
            serializeObj[this.name] = this.value;
        });
        return serializeObj;
    };
    //加载所有
    $.fn.loadall = function(type, title,toolbar) {
        $obj = this;
        $obj.loadgrid({ type: type }, title, toolbar);
    };
    //数据网格基本
    $.fn.loadgrid = function(type, title, toolbar) {
        var obj = this;
        var tool = '#'+toolbar.attr('id');
        var param = {type:type};
        obj.datagrid({
            width: '100%',
            height: '100%',
            iconCls: 'icon-search',
            method: 'POST',
            singleSelect: true,
            rownumbers: true,
            loadMsg: '正在努力为您加载数据',
            fit: true,
            striped: true,
            fitColumns: true,
            showFooter: true,
            remoteSort: false,
            pagination: true,
            url: url,
            queryParams: param,
            title: title,
            toolbar: tool,
            columns: [
                [
                    { field: 'account', title: '学号', width: '8%' },
                    { field: 'name', title: '姓名', width: '8%' },
                    { 
                        field: 'is_now', 
                        title: '状态', 
                        width: '5%' ,
                        formatter:function(value){
                            return value==1?'在校':'离校';
                        }
                    },
                    {
                        field: 'gender', 
                        title: '性别', 
                        width: '5%' ,
                        formatter:function(value){
                            return value==0?'女':'男';
                        }
                    },
                    { field: 'college', title: '学院', width: '8%' },
                    { field: 'major', title: '专业', width: '8%' },
                    { field: 'grade', title: '年级', width: '8%' },
                    { field: 'class', title: '班级', width: '8%' }, 
                    { field: 'course', title: '科目', width: '8%' },
                    { field: 'programme', title: '方案', width: '8%' },
                    { field: 'checkpoint', title: '关卡', width: '8%' },
                    { field: 'score', title: '得分', width: '5%' },
                    { field: 'full', title: '满分', width: '5%' },

                ]
            ],

        });
        var p = obj.datagrid('getPager');
        $(p).pagination({
            pageSize: 20, //每页显示的大小
            pageList: [10, 20, 30],
            beforePageText: '第', //页数文本框前显示的汉字
            afterPageText: '页 共{pages}页',
            displayMsg: '当前显示{from}-{to}条记录 共{total}条记录',
        });
    }
})(jQuery);
