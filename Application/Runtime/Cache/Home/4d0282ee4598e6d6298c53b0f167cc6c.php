<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/locale/easyui-lang-zh_CN.js" ></script>
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/icon.css" />

</head>




</head>
<style type="text/css">
	$('#tabs').tabs({ 
		fit : true, 
		border : false,
	});
	.logo { 
		width:180px; 
		height:50px; 
		line-height:50px; 
		text-align:center; 
		font-size:40px; 
		font-weight:bold; 
		float:left; 
		color:#fff;
		padding:30px 15px 0 0; 
	}
	.logout { 
		float:right; 
		padding:30px 15px 0 0; 
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
		<div class="logo">后台管理</div>
		<div class="logout"> 您好，<?php echo session('realname');?> | <a href="http://localhost/zyyj/index.php/Home/index/Index_loginout">
退出</a>
</div>
	</div>
	<div region="west" split="true" title="菜单栏" style="width:180px;"></div>
	<div region="center" style="padding:5px;background:#eee;"></div>
	<div region="south" title="" split="true" style="height:100px;">
		<div align="center">©2016-2017 成都信息工程大学. Powered by PHP and EasyUI.</div>
	</div>
</body>
<script type="text/javascript">
	
</script>
</html>