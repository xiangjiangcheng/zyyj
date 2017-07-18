<div>
	<div style="float:left;border-style:solid; border-width:1px; border-color:#00CCFF;height: 420px;width: 300px" >
		<div align="center">角色选择</div>
		<ul id="role" ></ul>
	</div>
	<div style="float:left;border-style:solid; border-width:0px; border-color:#00CCFF;height: 420px;width: 50px" ></div>
	<div style="float:left;border-style:solid; border-width:1px; border-color:#00CCFF;height: 420px;width: 400px;overflow :auto" >
		<div align="center">菜单选择</div>
		<ul id="menu" ></ul>
	</div>
	
</div>
<div style="float:top;border-style:solid; border-width:0px; border-color:#00CCFF;height: 50px;width: 650px" >
		<div align="center"><button onclick="sub()">提交</button></div>
	</div>
<script type="text/javascript">
	function sub(){
		var post = $('#role').tree("getChecked");// 得到复选框打钩的节点
		var menu = $('#menu').tree("getChecked");
		var menu_id='';
		var arr=new Array();
		for(var i=0;i<menu.length;i++){
			arr.push(menu[i]['id']);
			if(arr.indexOf(menu[i]['nid'])==-1){
			 	arr.push(menu[i]['nid']);
			}
		}
		for(var i=0;i<arr.length;i++){
			menu_id=menu_id+""+arr[i]+";";
		}
		if(post==null||post==""){
			alert("请选择角色");
			return;
		}
		$.ajax({
				async : true,
				cache : false,
				type : 'get',
				url : '__SITE__index.php/Home/Menu/Menu_menu_to_post',
				contentType: "application/json",
				data : {
					post_id:post['0']['id'],
					menu_id:menu_id,
				},
				dataType:'json',
				success : function(data, textStatus, jqXHR) {
					if(data.code == 1){
						alert("提交成功");

					}else if(data.code==0){
						alert("提交失败");
					}
				}
			});

	}
	$(function () {
		$('#role').tree({ 
			url : '__SITE__index.php/Home/Menu/Menu_get_post', 
			checkbox:true,
			onLoadSuccess : function (node, data) {
				$('#role').unbind('click');
				if (data) {
					$(data).each(function (index, value) {
						$('#role').tree('expandAll');	
					});
				}
			},
			onSelect : function(node){
				var cknodes = $(this).tree("getChecked");
				for(var i = 0 ; i < cknodes.length ; i++){
					$(this).tree("uncheck", cknodes[i].target);
				}
				//再选中该节点
				$(this).tree("check", node.target);
				$('#menu').tree({ 
					url : '__SITE__index.php/Home/Menu/Menu_get_post_menu?post_id='+node.id, 
					checkbox:true,
					onLoadSuccess : function (node, data) {
						if (data) {
							$(data).each(function (index, value) {
								$('#menu').tree('expandAll');	
							});
						}
					},
				});
			},
		});
		$('#menu').tree({ 
			url : '__SITE__index.php/Home/Menu/Menu_get_menu', 
			checkbox:true,
			onLoadSuccess : function (node, data) {
				if (data) {
					$(data).each(function (index, value) {
						$('#menu').tree('expandAll');	
					});
				}
			},
		});
	});
	
</script>
