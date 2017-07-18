<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 情况管理
 */
class ConditionController extends BaseController{
	/**
	 * 练习情况
	 */
	public function Condition_practice(){
		$this->display();
	}
	public function Condition_getdata(){
		//初始化分页信息
		$pagenum = isset($_POST['page']) ? intval($_POST['page']):1;
		$pagesize = isset($_POST['rows']) ? intval($_POST['rows']):20;

		//判断类型0考试1练习
		$map['zyyj_student_practice.type'] = I('type');
		$map['zyyj_college.college_id'] = session('college_id');
		//判断是否有按学号查询
		if(isset($_POST['stu_id'])&&!empty($_POST['stu_id'])){
			$map['zyyj_student.account'] = array('like',"%".I('stu_id')."%");
		}
		//判断是否有按姓名查询
		if(isset($_POST['name'])&&!empty($_POST['name'])){
			$map['zyyj_student.name'] = array('like',"%".I('name')."%");
		}
		//判断是否有筛选查询信息
		if(isset($_POST['major_id'])&&!empty($_POST['major_id'])){
			$map['zyyj_major.major_id'] = I('major_id');
		}
		if(isset($_POST['grade_id'])&&!empty($_POST['grade_id'])){
			$map['zyyj_grade.grade_id'] = I('grade_id');
		}
		if(isset($_POST['class_id'])&&!empty($_POST['class_id'])){
			$map['zyyj_class.class_id']  = I('class_id');
		}
		if(isset($_POST['course_id'])&&!empty($_POST['course_id'])){
			$map['zyyj_course.course_id']  = I('course_id');
		}
		$data = M('student_practice')->join('LEFT JOIN zyyj_student ON zyyj_student_practice.stu_id = zyyj_student.stu_id
			LEFT JOIN zyyj_class  ON zyyj_student.class_id = zyyj_class.class_id
			LEFT JOIN zyyj_grade  ON zyyj_class.grade_id = zyyj_grade.grade_id
			LEFT JOIN zyyj_major  ON zyyj_grade.major_id = zyyj_major.major_id
			LEFT JOIN zyyj_college  ON zyyj_major.college_id = zyyj_college.college_id
			LEFT JOIN zyyj_university  ON zyyj_college.university_id = zyyj_university.university_id
			LEFT JOIN zyyj_course ON zyyj_student_practice.course_id = zyyj_course.course_id
			LEFT JOIN zyyj_checkpoint ON zyyj_student_practice.checkpoint_id = zyyj_checkpoint.checkpoint_id
			LEFT JOIN zyyj_programme ON zyyj_student_practice.programme_id = zyyj_programme.programme_id')
		->where($map)
		->field('zyyj_student_practice.*,zyyj_student.name as name,zyyj_student.account as account,zyyj_student.gender as gender,zyyj_student.is_now as is_now,zyyj_class.name as class,zyyj_grade.name as grade,zyyj_major.name as major,zyyj_college.name as college,zyyj_university.name as university,zyyj_course.name as course,zyyj_checkpoint.name as checkpoint,zyyj_programme.name as programme,zyyj_programme.score as full')
		->limit(($pagenum-1)*$pagesize.','.$pagesize)
		->select();
		$snum = sizeof($data);
		if($snum ==0){
      			$jsonStr='{"total":'.$snum.',"rows":[]}';
     		}else{
        		$jsonStr='{"total":'.$snum.',"rows":'.json_encode($data).'}';
    	}
		echo $jsonStr;
	}
	/**
	 * 考试情况
	 */
	public function Condition_exam(){
		$this->display();
	}
} 
?>