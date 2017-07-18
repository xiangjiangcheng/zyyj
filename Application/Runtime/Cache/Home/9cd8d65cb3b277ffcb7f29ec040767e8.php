<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/locale/easyui-lang-zh_CN.js" ></script>
<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
<script src="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
<!-- <script type="text/javascript" src="http://localhost/zyyj//source/js/comm/easyui-extend-rcm.js" ></script> -->
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/icon.css" />
<script type="text/javascript">
	//解析路径  -- 向江城
	//页面引入外部js的时候  使用
	var Student_getAllStudent_url = '<?php echo U("Home/Student/Student_getAllStudent","","");?>';
	var Student_addStudent_url = '<?php echo U("Home/Student/Student_addStudent","","");?>';
	var Student_getStudentById_url = '<?php echo U("Home/Student/Student_getStudentById","","");?>';
	var Student_updateStudent_url = '<?php echo U("Home/Student/Student_updateStudent","","");?>';
	var Student_deleteStudent_url = '<?php echo U("Home/Student/Student_deleteStudent","","");?>';
	var Student_updatePwdStudent_url = '<?php echo U("Home/Student/Student_updatePwdStudent","","");?>';
	var Student_getClassByGradeId_url = '<?php echo U("Home/Student/Student_getClassByGradeId","","");?>';
	var Student_getClassByGradeAndMajorId_url = '<?php echo U("Home/Student/Student_getClassByGradeAndMajorId","","");?>';
	var Student_getMajorByCollegeId_url = '<?php echo U("Home/Student/Student_getMajorByCollegeId","","");?>';
	var Student_getStudentGraAndColl_url = '<?php echo U("Home/Student/Student_getStudentGraAndColl","","");?>';
	var Student_import_url = '<?php echo U("Home/Student/Student_import","","");?>';
	var Student_getAllSearchData_url = '<?php echo U("Home/Student/Student_getAllSearchData","","");?>';
	
	
</script>


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
					<div class="logo"><img src="http://localhost/zyyj/source/images/logo.png" /></div>
					<div class="title">成都中医药大学药方记忆后台管理系统</div>
				</div>
				<div class="logout"> 您好，<?php echo session('realname');?> | <a href="http://localhost/zyyj/index.php/Home/Login/Login_loginout">
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
			url : 'http://localhost/zyyj/index.php/Home/Login/Login_get_menu',
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
							href : "http://localhost/zyyj/index.php/Home/"+node.url,
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