<?php
/**
 * 各种选择菜单信息的获取控制器
 */
namespace Home\Controller;
use Think\Controller;
class TaoController {
	//获取学院信息
	public function Tao_GetCollege(){
		$College = M('College')->order("convert(name using gb2312) ASC")->field('name,college_id')->select();
		echo json_encode($College);
	}

	//获取专业信息
	public function Tao_GetMajor(){
		$college_id = session('college_id');
		$Major = M('Major')->where(array('college_id'=>$college_id))->order("convert(name using gb2312) ASC")->field('name,major_id')->select();
		echo json_encode($Major);
	}

	//获取年级
	public function Tao_GetGrade(){
		$Grade = M('Grade')->order("convert(name using gb2312) DESC")->field('name,grade_id')->select();
		echo json_encode($Grade);
	}
	//获取班级
	public function Tao_GetClass(){
		if(isset($_GET['grade_id']) && !empty($_GET['grade_id'])){
			$map['grade_id'] = $_GET['grade_id'];
		}
		/*if(isset($_GET['major_id']) && !empty($_GET['marjor_id'])){
			$map['major_id'] = $_GET['major_id'];
		}*/
		$college_id = session('college_id');
		$majorlist = M('Major')->where(array('college_id'=>$college_id))->field('major_id')->select();
		$list = array();
		for($i = 0;$i<sizeof($majorlist);++$i){
			$list[$i] = $majorlist[$i]['major_id'];
		}
		$map['major_id'] = array('in',$list);
		//print_r($map);
		$Class = M('Class')->where($map)->order("convert(name using gb2312) DESC")->field('name,class_id')->select();
		if(sizeof($Class)==0){
			$Class = '[{"name":"无","class_id":"-1"}]';
			echo $Class;
		}else{
			echo json_encode($Class);
		}
	}
	//获取科目
	public function Tao_GetCourse(){
		$Course = M('Course')->order("convert(name using gb2312) DESC")->field('name,course_id')->select();
		echo json_encode($Course);
	}
	//获取考试方案
	public function Tao_GetProgram(){
		$Program = M('Exam_program')->order('createdate desc')->field('name,program_id')->select();
		echo json_encode($Program);
	}
	//获取考试等级
	public function Tao_GetLevel(){
		$map = array();
		if(isset($_GET['programid'])&&!empty($_GET['programid'])){
			$map['program_id'] = $_GET['programid'];
		}
		$level = M('Exam_level')->where($map)->field('level_id,name')->select();
		echo json_encode($level);
	}
}
?>