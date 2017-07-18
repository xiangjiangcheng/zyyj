<include file="./Application/Home/View/Header.php" />
</head>
<style type="text/css">
	$('#tabs').tabs({ 
		fit : true, 
		border : false,
	});
	
	#left{
		width:600px;
		height:80px;
		position:relative;
		float:left;
	}
	#left .logo{
		padding:9px 8px;
		position:relative;
		float:left;
		margin-left:50px;
	}
	#left .logo img{
		width:50px;
		height:50px;
	}
	#left .title{
		color:#fff;
		font-size:30px;
		font-family:'楷体';
		line-height:70px;
		margin-left:10px;
		position:relative;
		float:left;
	}
	.logout { 
		width:200px;
		float:right; 
		padding:45px 15px 0 0; 
		color:#fff;
	}
	a {
		color:#fff; 
		text-decoration:none;
	} 
	a:hover { 
		text-decoration:underline;
	}
</style>
<body class="easyui-layout">
		<div data-options="region:'north',title:'header',
split:true,noheader:true" style="height:80px;background-color: #0099FF">
				<div id="left">
					<div class="logo"><img src="__SITE__source/images/logo.png" /></div>
					<div class="title">成都中医药大学药方记忆后台管理系统</div>
				</div>
				<div class="logout"> 您好，<?php echo session('realname');?> | <a href="__SITE__index.php/Home/Login/Login_loginout">
		退出</a>
				</div>
	</div>
	<div data-options="region:'west',title:'导航',split:true,iconCls:'icon-world'" style="width:180px;padding:10px;">
	<ul id="left_menu"></ul>
</div> 
	<div region="center" style="padding:5px;background:#eee;">
		<div id="tabs">
		<div title="起始页" iconCls="icon-house" style="padding:0 10px;display:block;">
			欢迎来到后台管理系统！
		</div>
	</div>
	</div>
	<div region="south" title="" split="true" style="height:50px;">
		<div align="center">©2016-2017 成都信息工程大学. Powered by PHP and EasyUI.</div>
	</div>
</body>
<script type="text/javascript">
	$(function () {
		// 初始化菜单
		$('#left_menu').tree({
			url : '__SITE__index.php/Home/Login/Login_get_menu',
			lines : true,
			onLoadSuccess : function (node, data) { //当数据加载成功时触发。
				if (data) {
					$(data).each(function (index, value) {
						if (this.state == "closed") {
							$('#left_menu').tree('expand');
						}
					});
				}
			},
			onClick : function (node) { 
				if (node.url) {
					if ($('#tabs').tabs('exists', node.text)) {
						$('#tabs').tabs('select', node.text);
					} else {
						$('#tabs').tabs('add', {
							title : node.text,
							//iconCls : node.iconCls,
							closable : true,
							href : "__SITE__index.php/Home/"+node.url,
						});
					}
				}
			}
		});
		
		$('#tabs').tabs({
			fit : true,
			border : false,
		});
	
	});
</script>
</html>
