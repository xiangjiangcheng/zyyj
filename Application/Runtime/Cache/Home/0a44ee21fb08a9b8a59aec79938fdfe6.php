<?php if (!defined('THINK_PATH')) exit();?><div>
	<div style="float:left;border-style:solid; border-width:1px; border-color:#00CCFF;height: 420px;width: 180px;overflow :auto" >
		<ul id="all_menu" ></ul>
	</div>
	<div style="float:left; border-style:solid; border-width:1px; border-color:#00CCFF;height: 420px;width: 800px;margin-left: 5px">
		<div style="float: top">
			<input type="button" value="增加菜单" id="add" onclick="addTreeItem()"></input>
			<input type="button" value="提交菜单" id="dosubmit" onclick="do_submit()"></input>
			<input type="button" value="删除菜单" id="delete" onclick="do_delete()"></input>
		</div>
		<div style="float: top;margin-top: 30px">
			菜单编号：<input type="text" style="margin-top: 7px" name="bumenNum" id="bumenNum" readonly="true"></input><br/>
			上级菜单：<input type="text" style="margin-top: 7px" name="nid" id="nid" readonly="true"></input><br/>
			菜单名称：<input type="text" style="margin-top: 7px" name="meun_text" id="menu_text"></input><br/>
			菜单 url：<input type="text" style="margin-top: 7px" name="url" id="url"></input><br/>
			<input type="hidden" id="node_id" name="node_id"></input>
		</div>
	</div>
</div>
<script type="text/javascript">
	function addTreeItem(){
        var node=$("#all_menu").tree('getSelected');       
        var nodes = [];
        if(!node){
        	nodes.push({ "nid":0,"text":'填写菜单描述'});
        }else{
        	nodes.push({ "nid":node.id,"text":'填写菜单描述'});
        }
        if (node){
        	$('#all_menu').tree('append', { // 追加菜单
				parent:node.target,
				data:nodes
			});
        }else{
            $('#all_menu').tree('append', {
				parent:null, // 追加为根节点
				data:nodes,
			});
        } 	
    }
    function do_submit(){
    	var id=document.getElementById("bumenNum").value;
    	var nid=document.getElementById("node_id").value;
    	var text=document.getElementById("menu_text").value;
    	var url=document.getElementById("url").value;
    	if(id==null||id==""){
    		alert("请先选择菜单");return;
    	}
    	if(text==null||text==""){
    		alert("请输入菜单名");return;
    	}
    	$.ajax({
				async : true,
				cache : false,
				type : 'get',
				url : 'http://localhost/zyyj/index.php/Home/Menu/Menu_add',
				contentType: "application/json",
				data : {
					id:id,
					nid:nid,
					text:text,
					url:url
				},
				dataType:'json',
				success : function(data, textStatus, jqXHR) {
					if(data.code == 1){
						alert("增加成功");
						$('#all_menu').tree({ 
							url : 'http://localhost/zyyj/index.php/Home/Menu/Menu_get_menu', 
							lines : true,
							onLoadSuccess : function (node, data) {
								if (data) {
									$(data).each(function (index, value) {
										if (this.state == "closed") {
											$('#all_menu').tree('collapseAll', this.target); 
											$('#all_menu').tree('expandAll');
										}
									});
								}
							},
							onClick : function (node) {
								document.getElementById("menu_text").value=node.text;
								if(node.nid==0){
									document.getElementById("nid").value="#";
									document.getElementById("node_id").value=0;
								}else{
									document.getElementById("nid").value=$("#all_menu").tree('getParent',node.target).text;
									document.getElementById("node_id").value=node.nid;
								}
								if (node.id) {
									document.getElementById("bumenNum").value=node.id;	
								}else{
									document.getElementById("bumenNum").value="-1";
								}
								if(node.url){
									document.getElementById("url").value=node.url;
								}else{
									document.getElementById("url").value="#";
								}
							}
						});		
					}else if(data.code == 2){
						alert("修改成功");
						$('#all_menu').tree({ 
							url : 'http://localhost/zyyj/index.php/Home/Menu/Menu_get_menu', 
							lines : true,
							onLoadSuccess : function (node, data) {
								if (data) {
									$(data).each(function (index, value) {
										if (this.state == "closed") {
											$('#all_menu').tree('collapseAll', this.target); 
											$('#all_menu').tree('expandAll');
										}
									});
								}
							},
							onClick : function (node) {
								document.getElementById("menu_text").value=node.text;
								if(node.nid==0){
									document.getElementById("nid").value="#";
									document.getElementById("node_id").value=0;
								}else{
									document.getElementById("nid").value=$("#all_menu").tree('getParent',node.target).text;
									document.getElementById("node_id").value=node.nid;
								}
								if (node.id) {
									document.getElementById("bumenNum").value=node.id;	
								}else{
									document.getElementById("bumenNum").value="-1";
								}
								if(node.url){
									document.getElementById("url").value=node.url;
								}else{
									document.getElementById("url").value="#";
								}
							}
						});
					}else{
						alert("提交失败");
					}
				}
			});
    }
    function do_delete(){
    	if(!confirm("确认删除此菜单?")){
    		return;
    	}
    	var id=document.getElementById("bumenNum").value;
    	var nid=document.getElementById("node_id").value;
    	if(id==null||id==""){
    		alert("请先选择菜单");return;
    	}
    	$.ajax({
				async : true,
				cache : false,
				type : 'get',
				url : 'http://localhost/zyyj/index.php/Home/Menu/Menu_delete',
				contentType: "application/json",
				data : {
					id:id,
					nid:nid,
				},
				dataType:'json',
				success : function(data, textStatus, jqXHR) {
					if(data.code == 1){
						alert("删除成功");
						$('#all_menu').tree({ 
							url : 'http://localhost/zyyj/index.php/Home/Menu/Menu_get_menu', 
							lines : true,
							onLoadSuccess : function (node, data) {
								if (data) {
									$(data).each(function (index, value) {
										if (this.state == "closed") {
											$('#all_menu').tree('collapseAll', this.target); 
											$('#all_menu').tree('expandAll');
										}
									});
								}
							},
							onClick : function (node) {
								document.getElementById("menu_text").value=node.text;
								if(node.nid==0){
									document.getElementById("nid").value="#";
									document.getElementById("node_id").value=0;
								}else{
									document.getElementById("nid").value=$("#all_menu").tree('getParent',node.target).text;
									document.getElementById("node_id").value=node.nid;
								}
								if (node.id) {
									document.getElementById("bumenNum").value=node.id;	
								}else{
									document.getElementById("bumenNum").value="-1";
								}
								if(node.url){
									document.getElementById("url").value=node.url;
								}else{
									document.getElementById("url").value="#";
								}
							}
						});		
					}else if(data.code==2){
						alert("请先删除其子菜单");
					}
				}
			});
    }
	$(function () {
		$('#all_menu').tree({ 
			url : 'http://localhost/zyyj/index.php/Home/Menu/Menu_get_menu', 
			lines : true,
			onLoadSuccess : function (node, data) {
				if (data) {
					$(data).each(function (index, value) {
						if (this.state == "closed" ) {
							$('#all_menu').tree('collapseAll', this.target); //折叠所有的节点
							$('#all_menu').tree('expandAll'); //展开的时候去请求数据
						}
					});
				}
			},
			onClick : function (node) {
				document.getElementById("menu_text").value=node.text;
				if(node.nid==0){
					document.getElementById("nid").value="#";
					document.getElementById("node_id").value=0;
				}else{
					document.getElementById("nid").value=$("#all_menu").tree('getParent',node.target).text;
					document.getElementById("node_id").value=node.nid;
				}
				if (node.id) {
					document.getElementById("bumenNum").value=node.id;	
				}else{
					document.getElementById("bumenNum").value="-1";
				}
				if(node.url){
					document.getElementById("url").value=node.url;
				}else{
					document.getElementById("url").value="#";
				}
			}
		});
	});
	
</script>