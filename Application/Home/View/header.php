<html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="__SITE__easyui/jquery.min.js"></script>
<script type="text/javascript" src="__SITE__easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="__SITE__easyui/locale/easyui-lang-zh_CN.js" ></script>
<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
<script src="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
<!-- <script type="text/javascript" src="__SITE__/source/js/comm/easyui-extend-rcm.js" ></script> -->
<link rel="stylesheet" type="text/css" href="__SITE__easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="__SITE__easyui/themes/icon.css" />
<script type="text/javascript">
	//解析路径  -- 向江城
	//页面引入外部js的时候  使用
	var Student_getAllStudent_url = '{:U("Home/Student/Student_getAllStudent","","")}';
	var Student_addStudent_url = '{:U("Home/Student/Student_addStudent","","")}';
	var Student_getStudentById_url = '{:U("Home/Student/Student_getStudentById","","")}';
	var Student_updateStudent_url = '{:U("Home/Student/Student_updateStudent","","")}';
	var Student_deleteStudent_url = '{:U("Home/Student/Student_deleteStudent","","")}';
	var Student_updatePwdStudent_url = '{:U("Home/Student/Student_updatePwdStudent","","")}';
	var Student_getClassByGradeId_url = '{:U("Home/Student/Student_getClassByGradeId","","")}';
	var Student_getClassByGradeAndMajorId_url = '{:U("Home/Student/Student_getClassByGradeAndMajorId","","")}';
	var Student_getMajorByCollegeId_url = '{:U("Home/Student/Student_getMajorByCollegeId","","")}';
	var Student_getStudentGraAndColl_url = '{:U("Home/Student/Student_getStudentGraAndColl","","")}';
	var Student_import_url = '{:U("Home/Student/Student_import","","")}';
	var Student_getAllSearchData_url = '{:U("Home/Student/Student_getAllSearchData","","")}';
	
	
</script>

