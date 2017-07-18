<?php
	/**
	 * 考试成绩管理控制器
	 */
namespace Home\Controller;
use Think\Controller;
class ExamScoreController extends BaseController{
	/**
	 * [ExamScore_Showlist 渲染模班]
	 */
	public function ExamScore_Showlist(){
		$this->display();
	}
	private function getjoin(){
		$join = 'LEFT JOIN zyyj_exam_level ON zyyj_student_practice.checkpoint_id = zyyj_exam_level.level_id
			LEFT JOIN zyyj_exam_program ON zyyj_exam_program.program_id = zyyj_exam_level.program_id 
			LEFT JOIN zyyj_student  ON zyyj_student.stu_id  = zyyj_student_practice.stu_id 
			LEFT JOIN zyyj_class ON zyyj_class.class_id = zyyj_student.class_id 
			LEFT JOIN zyyj_grade ON zyyj_grade.grade_id =  zyyj_class.grade_id   
			LEFT JOIN zyyj_major ON zyyj_major.major_id = zyyj_grade.major_id  
			LEFT JOIN zyyj_college ON zyyj_college.college_id = zyyj_major.college_id
			LEFT JOIN zyyj_course ON zyyj_course.course_id = zyyj_student_practice.course_id';
		return $join;
	}

	private function getmap($get){
		
	}
	/**
	 * [ExamScore_GetScores 获取成绩列表]
	 */
	public function ExamScore_GetScores(){
		//初始化分页信息
		$pagenum = isset($_POST['page']) ? intval($_POST['page']):1;
		$pagesize = isset($_POST['rows']) ? intval($_POST['rows']):20;
		//判断是否有筛选查询信息
		if(isset($_POST['es_account'])&&!empty($_POST['es_account'])){
			$map['zyyj_student.account'] = array('like',"%".I('es_account')."%");
		}
		if(isset($_POST['es_stuname'])&&!empty($_POST['es_stuname'])){
			$map['zyyj_student.name'] = array('like',"%".I('es_stuname')."%");
		}
		if(isset($_POST['es_status'])&&!empty($_POST['es_status'])){
			$isgg = intval(I('es_status'));
			if($isgg!=3){
				$map['zyyj_student_practice.status']=$isgg;
			}
		}
		if(isset($_POST['es_level']) && !empty($_POST['es_level'])){
			$mao['zyyj_exam_level.level_id'] = I('es_level'); 
		}
		if(isset($_POST['es_program'])&&!empty($_POST['es_program'])){
			$map['zyyj_exam_level.program_id'] = I('es_program');
		}
		if(isset($_POST['es_major'])&&!empty($_POST['es_major'])){
			$map['zyyj_major.major_id'] = I('es_major');
		}
		if(isset($_POST['es_grade'])&&!empty($_POST['es_grade'])){
			$map['zyyj_grade.grade_id'] = I('es_grade');
		}
		if(isset($_POST['es_class'])&&!empty($_POST['es_class'])){
			$map['zyyj_class.class_id']  = I('es_class');
		}
		if(isset($_POST['es_course'])&&!empty($_POST['es_course'])){
			$map['zyyj_student_practice.course_id'] = I('es_course');
		}
		$map['zyyj_college.college_id'] = session('college_id');
		$map['zyyj_student_practice.type']=2;
		$join = $this->getjoin();
		$getscores = M('Student_practice')->join($join)->where($map)->field('
				zyyj_student_practice.practice_id,
				zyyj_course.name as cname,
				zyyj_exam_program.name as proname,
				zyyj_exam_level.name as lname,
				zyyj_student_practice.score as getscore, 
				zyyj_student_practice.status,
				zyyj_student.account,
				zyyj_student.name as stuname, 
				zyyj_class.name as class_name,
				zyyj_grade.name as grade_name,
				zyyj_major.name as major_name,
				zyyj_college.name as college_name,
				zyyj_student.gender as sex')->order('score desc')->limit(($pagenum-1)*$pagesize.','.$pagesize)->select();
		$nums = sizeof($getscores);
			if($nums ==0){
	            $jsonStr='{"total":'.$nums.',"rows":[]}';
	          }else{
	            $jsonStr='{"total":'.$nums.',"rows":'.json_encode($getscores).'}';
	        }
			echo $jsonStr;
	}

	/**
	 * [ExamScore_Getexcel 成绩导出方法]
	 */
	public function ExamScore_Getexcel(){
		//获取请求信息
		//
		//

		if(isset($_GET['account'])&&!empty($_GET['account'])){
			$map['zyyj_student.account'] = array('like',"%".I('account')."%");
		}
		if(isset($_GET['stuname'])&&!empty($_GET['stuname'])){
			$map['zyyj_student.name'] = array('like',"%".I('stuname')."%");
		}
		if(isset($_GET['status'])&&!empty($_GET['status'])){
			$isgg = intval(I('status'));
			if($isgg!=3){
				$map['zyyj_student_practice.status']=$isgg;
			}
		}
		if(isset($_GET['level_id']) && !empty($_GET['level_id'])){
			$map['zyyj_exam_level.level_id'] = I('level_id'); 
		}
		if(isset($_GET['program_id'])&&!empty($_GET['program_id'])){
			$map['zyyj_exam_level.program_id'] = I('program_id');
		}

		if(isset($_GET['major_id'])&&!empty($_GET['major_id'])){
			$map['zyyj_major.major_id'] = I('major_id');
		}
		if(isset($_GET['grade_id'])&&!empty($_GET['grade_id'])){
			$map['zyyj_grade.grade_id'] = I('grade_id');
		}
		if(isset($_GET['class_id'])&&!empty($_GET['class_id'])){
			$map['zyyj_class.class_id']  = I('class_id');
		}
		if(isset($_GET['course_id'])&&!empty($_GET['course_id'])){
			$map['zyyj_student_practice.course_id'] = I('course_id');
		}
		$map['zyyj_student_practice.type']=2;
		$map['zyyj_college.college_id'] = session('college_id');
		$join  = $this->getjoin();
		$data = M('Student_practice')->join($join)->where($map)->order('score desc')->field('
				zyyj_student.account,
				zyyj_student.name, 
			    zyyj_student_practice.score, 
			    zyyj_class.name as class_name,
			    zyyj_grade.name as grade_name')->select();
		/*$getname = M('College')->where(array('college_id'=>$_GET['college_id']))->field('name')->select();
		$collegename = $getname[0]['name'];
		$getname2 = M('Major')->where(array('major_id'=>$_GET['major_id']))->field('name')->select();
		$majorname = $getname2[0]['name'];*/
		$this->export($data);
	}
    function export($data){
		$title="成绩信息";
		$this->downloadPdf_comment($title, $data);
		
	}
	function downloadPdf_comment($title, $data) {
			//创建对象
			Vendor("PHPExcel.PHPExcel");
			Vendor("PHPExcel.PHPExcel.IOFactory");  
			$excel = new \PHPExcel();
			$objSheet=$excel->getActiveSheet();
			$objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->
			setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置excel文件默认水平垂直方向居中
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20); //A列宽度
			//Excel表格式,这里简略写了2列
			$letter = array('A','B','C','D','E','F');
			//表头数组
			$tableheader = array('学号', '姓名','成绩','班级','年级');
			//填充表头信息
			for($i = 0;$i < sizeof($tableheader);$i++) {
				$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
			}
			for ($i = 2;$i <= sizeof($data) + 1;$i++) {
				$j = 0;
				foreach ($data[$i - 2] as $key=>$value) {
					$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
					$j++;
				}
			}
			$title = $title.'.xls';
			$write = new \PHPExcel_Writer_Excel5($excel);
			ob_end_clean();
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
			header("Content-Type:application/force-download");
			header("Content-Type:application/vnd.ms-execl");
			header("Content-Type:application/octet-stream");
			header("Content-Type:application/download");;
			header('Content-Disposition:attachment;filename="'.$title.'"');
			header("Content-Transfer-Encoding:binary");
			$write->save('php://output');
	}
}
?>