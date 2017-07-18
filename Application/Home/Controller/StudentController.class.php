<?php
namespace Home\Controller;
use Think\Controller;
class StudentController extends BaseController {
	//全局变量  
// 	public $college_id;
	/**
	 * 学生管理-学生管理模块    ||  首页
	 */
	public function  Student_list() {
		//获取全局session
		$data['college_id'] = session('college_id');
		$this->assign('data',$data);
		//渲染视图
		$this->display();
	}
	
	/**
	 * 学生管理-学生管理模块    ||  获取所有的学生信息  以json格式返回数据   echo返回     
	 */
	public function Student_getAllStudent() {
		// 获取页面上传过来的查询字段
		$data = I('post.');
		
		//获取页面上传过来的page rows
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$order = $_POST['order'];
		$sort = $_POST['sort'];
		//学生姓名
		if (isset($data['name']) && !empty($data['name'])) {
			$map['zyyj_student.name'] = array('like','%'.$data['name'].'%');
		}
		//学号
		if (isset($data['account']) && !empty($data['account'])) {
			$map['zyyj_student.account'] = array('like','%'.$data['account'].'%');
		}
		//性别
		if (isset($data['gender']) && $data['gender'] != "") {
			$map['zyyj_student.gender'] = array('eq',$data['gender']);
		}
		//学院
		if (isset($data['college_id']) && $data['college_id'] != "") {
			$map['zyyj_student.college_id'] = array('eq',$data['college_id']);
		}
		//专业
		if (isset($data['major_id']) && $data['major_id'] != "") {
			$map['zyyj_student.major_id'] = array('eq',$data['major_id']);
		}
		//班级
		if (isset($data['class_id']) && $data['class_id'] != "") {
			$map['zyyj_student.class_id'] = array('eq',$data['class_id']);
		}
		//年级
		if (isset($data['grade_id']) && $data['grade_id'] != "") {
			$map['zyyj_student.grade_id'] = array('eq',$data['grade_id']);
		}
		$map['zyyj_student.is_now'] = array('eq',1);
		$offset = ($page-1)*$rows;
		//返回的对象
		$result = array();
		//操作数据库
		$student = M('student');
		//关联表 数组
		$joindata = array(' LEFT JOIN zyyj_college ON zyyj_student.college_id = zyyj_college.college_id','LEFT JOIN zyyj_major ON zyyj_student.major_id = zyyj_major.major_id','LEFT JOIN zyyj_grade ON zyyj_student.grade_id = zyyj_grade.grade_id','LEFT JOIN zyyj_class ON zyyj_student.class_id = zyyj_class.class_id');
		//查询字段数组
		$finddata = array('zyyj_student.*','zyyj_class.name'=>'class_name','zyyj_grade.name'=>'grade_name','zyyj_major.name'=>'major_name','zyyj_college.name'=>'college_name');
		//获取学生数据条数  total
		$rs = $student->join($joindata)->where($map)->field($finddata)->count();
		//判断是否获取到数据   如果条数为空（NAN）赋值为空
		if (is_null($rs)) {
			$rs = 0;
		}
		//添加total 值
		$result["total"] = $rs;
		//获取本页面数据
		//12月测试更改内容--
		if($sort=='name'){
			$sort = "convert(zyyj_student.name using gb2312)";
		} else {
			$sort = "convert(".$sort." using gb2312)";
		}
		//--
		$rs = $student->join($joindata)->where($map)->field($finddata)->order($sort.' '.$order)->limit($offset,$rows)->select();
		//判断是否查找到数据 
		if (empty($rs)) {
			//为空  
			$rs = array();
		}
		$result["rows"] = $rs;
		echo json_encode($result);
	}
	/**
	 * 获取查询数据
	 */
	public function Student_getAllSearchData() {
		//获取全局session
		$college_id = session('college_id');
		if ($college_id != '') {
			$map['zyyj_student.college_id'] = array('eq',$college_id);
		}
		//返回的对象
		$result = array();
		//操作数据库
		$student = M('student');
		//获取所有学生数据    如果是教学秘书登录  就只查询该学院的学生
		$rs = $student->where($map)->order('convert(name using gb2312) ASC')->select();
		//判断是否查找到数据
		if (empty($rs)) {
			//为空
			$rs = array();
		}
		$result["rows"] = $rs;
		//获取班级信息
		$result["class"] = $this->Student_getAllClass();
		//获取年级信息
		$result["grade"] = $this->Student_getAllGrade();
		//获取学院信息
		$result["college"] = $this->Student_getAllCollege();
		//获取专业信息
		$result["major"] = $this->Student_getAllMajor();
		echo json_encode($result);
	}
	/**
	 * 获取班级信息    
	 * 操作表：zyyj_class
	 */
	public function Student_getAllClass() {
		//获取全局session
		$college_id = session('college_id');
		$findId = '';
		if ($college_id != '') {
			//获取专业信息
			$major = $this->Student_getAllMajor();
			foreach($major as $k=>$val){
			   $findId = $findId.$val['major_id'].',';
			}
			//截取掉最后一个字符
			$findId = substr($findId,0,-1);
			$map['major_id']  = array('in',$findId);
			// var_dump($findId);exit;
		}
		$class = M('class');
		$rs = $class->where($map)->order('convert(name using gb2312) ASC')->select();
		return $rs;
		// return $findId;
	}
	
	/**
	 * 根据年级id 查询该年级对应的班级
	 */
	public function Student_getClassByGradeId() {
		//获取数据
		$data = I('post.');
		//返回的对象
		$result = array();
		$class = M('class');
		$map['zyyj_class.grade_id'] = array('eq',$data['grade_id']);
		$rs = $class->where($map)->select();
		$result['class'] = $rs;
		echo json_encode($result);
	}
	/**
	 * 根据年级id+专业id 查询该条件对应的班级
	 */
	public function Student_getClassByGradeAndMajorId() {
		//获取数据
		$data = I('post.');
		// 		$data = I('get.');
		//返回的对象
		$result = array();
		$class = M('class');
		$map['zyyj_class.grade_id'] = array('eq',$data['grade_id']);
		$map['zyyj_class.major_id'] = array('eq',$data['major_id']);
		$rs = $class->where($map)->select();
		//判断是否获取到数据   如果条数为空（NAN）赋值为空
		if (is_null($rs)) {
			$rs = array();
		}
		$result['class'] = $rs;
		echo json_encode($result);
	}
	/**
	 * 获取年级信息
	 * 操作表：zyyj_grade
	 */
	public function Student_getAllGrade() {
		//获取
		$grade = M('grade');
		$rs = $grade->order('convert(name using gb2312) DESC')->select();
		return $rs;
	}
	
	/**
	 * 获取学院信息
	 * 操作表：zyyj_college
	 */
	public function Student_getAllCollege() {
		//获取
		$college = M('college');
		$rs = $college->select();
		return $rs;
	}
	/**
	 * 获取专业信息
	 * 操作表：zyyj_major
	 */
	public function Student_getAllMajor() {
		//获取全局session
		$college_id = session('college_id');
		if ($college_id != '') {
			$map['zyyj_major.college_id'] = array('eq',$college_id);
		} else {
			$map = array();
		}
		$major = M('major');
		$rs = $major->where($map)->order('convert(name using gb2312) ASC')->select();
		return $rs;
	}
	
	/**
	 * 根据学院id 查询该学院对应的专业
	 */
	public function Student_getMajorByCollegeId() {
		//获取数据
		$data = I('post.');
		$class = M('major');
		$map['zyyj_major.college_id'] = array('eq',$data['college_id']);
		$rs = $class->where($map)->select();
		//判断是否获取到数据   如果条数为空（NAN）赋值为空
		if (is_null($rs)) {
			$rs = array();
		}
		$result['major'] = $rs;
		echo json_encode($result);
	}
		
	/**
	 * 学生管理-学生管理模块    || 添加学生信息
	 */
	public function Student_addStudent() {
		//获取页面上传过来的数据
// 		$data = I('post.');
		$data = array(
				'name' => I('name'),
				'account' => I('account'),
				'gender' => I('gender'),
				'class_id' => I('class_id'),
				'grade_id' => I('grade_id'),
				'major_id' => I('major_id'),
				'college_id' => I('college_id'),
				'email' => I('email'),
				'password'=> sha1(md5('123456')),//管理员添加学生 密码默认为123456
				'phone' => I('phone')
		);
		//返回的对象
		$result = array();
		//操作数据库  先判断学生是否存在（学号唯一为标准）  ->  插入数据
		$student = M('student');
		//学号查询    同一个专业同一个年级下面  学号不能重复
		$map['account'] = array('eq',$data['account']);
		$map['college_id'] = array('eq',$data['college_id']);
		$map['major_id'] = array('eq',$data['major_id']);
		$map['grade_id'] = array('eq',$data['grade_id']);
		$rs = $student->where($map)->count();
		if ($rs == 1) {
			//学生存在
			$result["code"] = 0;
			$result["message"] = '学生添加失败！该学号已经存在';
		} else {
			//学生不存在   添加学生
// 			$student->add($data);
			$id = M('student')->data($data)->add();
			if (!empty($id)) {
				$result["code"] = 1;
				$result["message"] = '学生添加成功！';
			} else {
				$result["code"] = 2;
				$result["message"] = '学生添加失败！';
			}
		}
		$result["data"] = $data;
		echo json_encode($result);
// 		echo $this->ajaxReturn($data, 'json');
	}
	/**
	 * 学生管理-学生管理模块    || 根据学生id获取学生信息
	 */
	public function Student_getStudentById() {
		//获取页面上传过来的数据
// 		$data = I('post.');
		//返回的对象
		$result = array();
		$data = array(
				'stu_id' => I('stu_id'),
		);
		//实例化student
		$student = M('student');
		$map['stu_id'] = $data['stu_id'];
		$rs=$student->where($map)->select();
		//获取年级信息
		$result["grade"] = $this->Student_getAllGrade();
		//获取学院信息
		$result["college"] = $this->Student_getAllCollege();
		if (!empty($rs)) {
			$result["code"] = 1;
			$result["message"] = '学生信息查询成功！';
			$result["rs"] = $rs;
		} else {
			$result["code"] = 0;
			$result["message"] = '学生信息查询失败！';
		}
		echo json_encode($result);
	}
	/**
	 * 学生管理-学生管理模块    || 根据学生学号account获取学生信息
	 */
	public function Student_getStudentByAcc($account) {
		$flag = 0;
		//实例化student
		$student = M('student');
		$map['account'] = $account;
		$rs=$student->where($map)->select();
		if (!empty($rs)) {
			$flag  = 1; 
		} else {
			$flag  = 0; 
		}
		return $flag;
	}
	/**
	 * 学生管理-学生管理模块    || 学生导入时获取学院 年级
	 */
	public function Student_getStudentGraAndColl() {
		//返回的对象
		$result = array();
	
		//获取年级信息
		$result["grade"] = $this->Student_getAllGrade();
		//获取学院信息
		$result["college"] = $this->Student_getAllCollege();
		echo json_encode($result);
	}
	
	/**
	 * 学生管理-学生管理模块    || 修改学生信息
	 */
	public function Student_updateStudent() {
		//获取页面上传过来的数据
		// 		$data = I('post.');
		$data = array(
				'stu_id' => I('stu_id'),
				'name' => I('name'),
				'account' => I('account'),
				'gender' => I('gender'),
				'class_id' => I('class_id'),
				'grade_id' => I('grade_id'),
				'major_id' => I('major_id'),
				'college_id' => I('college_id'),
				'email' => I('email'),
// 				'password'=> sha1(md5('123456')),
				'phone' => I('phone')
		);
		
		//先获取学生之前的信息
		$map['stu_id'] = $data['stu_id'];
		$stu=M('student')->where($map)->select();
		//判断是否选择学院  专业等信息
		if ($data['class_id'] == "") {
// 			echo $data['class_id'];
			$data['class_id'] = $stu['0']['class_id'];
		}
		if ($data['grade_id'] == "") {
			$data['grade_id'] = $stu['0']['grade_id'];
		}
		if ($data['major_id'] == "") {
			$data['major_id'] = $stu['0']['major_id'];
		}
		if ($data['college_id'] == "") {
			$data['college_id'] = $stu['0']['college_id'];
		}
// 		print_r($data);exit;
		//返回的对象
		$result = array();
		//实例化
		$student = M('student');
		$map['stu_id'] = array('eq',$data['stu_id']);
		$rs = $student->where($map)->save($data);
		if ($rs == false || $rs == 1) {
			$result["code"] = 1;
			$result["message"] = '学生信息修改成功！';
		} else {
				$result["code"] = 0;
				$result["message"] = '学生添加失败！';
		}
		echo json_encode($result);
	}
	/**
	 * 学生管理-学生管理模块    ||删除学生信息
	 */
	public function Student_deleteStudent() {
		//获取页面上传过来的数据
// 		$data = I('post.');
		//多条删除id  ‘,’隔开
		$ids = I('post.ids');
		//返回的对象
		$result = array();
		$data = array(
				'stu_id' => I('stu_id'),
		);
		//实例化student
		$student = M('student');
		$map['stu_id'] = array('IN',$ids);
		$rs = $student->where($map)->delete(); // 删除用户数据
	
		if (!empty($rs)) {
			$result["code"] = 1;
			$result["message"] = '学生信息删除成功！';
			$result["rs"] = $rs;
		} else {
			$result["code"] = 0;
			$result["message"] = '学生信息删除失败！';
		}
		echo json_encode($result);
	}
	
	/**
	 * 学生管理-学生管理模块    ||重置学生密码，重置后密码为123456
	 */
	public function Student_updatePwdStudent() {
		//获取页面上传过来的数据
		// 		$data = I('post.');
		//多条删除id  ‘,’隔开
		$ids = I('post.ids');
		//返回的对象
		$result = array();
		$data = array(
				'stu_id' => I('stu_id'),
		);
		//实例化student
		$student = M('student');
		$map['stu_id'] = array('IN',$ids);
		// 要修改的数据对象属性赋值
		$updatedata['password'] = sha1(md5('123456'));
		$rs = $student->where($map)->save($updatedata); // 重置学生用户密码   若无数据更新 返回false
		if ($rs != false) {
			$result["code"] = 1;
			$result["message"] = '所选学生密码均重置成功！';
		} else {
			$result["code"] = 0;
			$result["message"] = '所选学生密码均为默认值！无需重置！';
		}
		echo json_encode($result);
	}
	//学生导入
	public function Student_import(){
		date_default_timezone_set('prc');		//设置时区，中国
		$time=time();
		$info=array();
		$count=0;
		$student=M('student');
		$reaccount=''; //记录学号重复的行号，行与行之间用‘,’隔开
		$msg = ''; // 提示信息
		$result = array(); //返回的对象
		//接收表单数据
		$dataget = I('post.');
		$college_id = $dataget['college_id_import'];
		//获取全局session
		$college_id_session = session('college_id');
		if ($college_id != '') {
			$data['college_id'] = $college_id;
		} else {
			$data['college_id'] = $college_id_session;
		}
		$data['major_id'] = $dataget['major_id_import'];
		$data['grade_id'] = $dataget['grade_id_import'];
		$data['class_id'] = $dataget['class_id_import'];
		if (!empty($_FILES)) {
			$photo=$time."".rand(1,100000);
			$photo_url=date("Y-m-d",$time)."/".$photo;
			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize=3145728 ;// 设置附件上传大小
			$upload->exts=array('xls', 'xlsx', 'png', 'jpeg');// 设置附件上传类型
			$upload->saveName=$photo;
			$upload->savePath  = ''; // 设置附件上传（子）目录 框架默认在uploads
			// 上传文件
			$info   =    $upload->uploadOne($_FILES['import']);
			if(!$info) {// 上传错误提示错误信息
				$this->error($upload->getError());
			}else{// 上传成功
	
			}
			Vendor("PHPExcel.PHPExcel");
			$file_name=$upload->rootPath.$info['savepath'].$info['savename'];;
			$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			//判断导入表格后缀格式
			if ($extension == 'xlsx') {
				$objReader =\PHPExcel_IOFactory::createReader('Excel2007');
				$objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
			}else if($extension == 'xls'){
				$objReader =\PHPExcel_IOFactory::createReader('Excel5');
				$objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
			}
			$sheet =$objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();//取得总行数
			$highestColumn =$sheet->getHighestColumn(); //取得总列数
			
			//该for循环先 遍历一遍，判断1：是否学号有重复   并找出哪些数据重复，记录重复数据行号  2：是否按照说明正确填写数据 ，即学号 姓名  性别 三项为必填项
			for ($i = 2; $i <= $highestRow; $i++) {
				//只读取account这一列
				$data['name'] =$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
				$data['account'] =$objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue();
				$data['gender'] =$objPHPExcel->getActiveSheet()->getCell("C" .$i)->getValue();
				//判断是否按照说明  正确填写数据 ，即学号 姓名  性别 三项为必填项
				if ($data['name'] == '' || $data['account'] == '' || $data['gender'] == '') {
					$msg = '请正确填写学生导入模板文档！文档中*代表必填项！';
				}
				//操作数据库  先判断学生是否存在（学号唯一为标准）  ->
				//学号查询    同一个专业同一个年级下面  学号不能重复
				$map['account'] = array('eq',$data['account']);
				$map['college_id'] = array('eq',$data['college_id']);
				$map['major_id'] = array('eq',$data['major_id']);
				$map['grade_id'] = array('eq',$data['grade_id']);
				$rs = $student->where($map)->count();
				if ($rs >= 1) {
					//学号重复   
					$reaccount = $reaccount.($i-1).",";
				}
			}
			//学号没有重复
			if ($reaccount == '') {
				//如果没有重复数据  则重新遍历，并写入数据库
				for ($i = 2; $i <= $highestRow; $i++) {
					//先读取一行
					$data['name'] =$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
					$data['account'] =$objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue();
					$data['password'] = sha1(md5('123456'));
					$gender =$objPHPExcel->getActiveSheet()->getCell("C" .$i)->getValue();
					$data['email'] = $objPHPExcel->getActiveSheet()->getCell("D". $i)->getValue();
					$data['phone'] = $objPHPExcel->getActiveSheet()->getCell("E" .$i)->getValue();
					$status = $objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue();
					$studentflag = $objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue(); //是否是本班生：0：本班生  1：转班生
					if ($gender == '男') {
						$data['gender'] = 1;
					} else {
						$data['gender'] = 0;
					}
					if ($status == '') {
						$data['status'] = 1;
					} else {
						$data['status'] = $status;
					}
					//如果是转班生（换班或者降级） 则查询之前所在的班级记录，并将这些数据中的is_now字段置为禁用状态:is_now = 0
					//这里  是否应该直接自动排除  之前已经在其他班的情况？
					if ($studentflag = 1) {
						//转班生
						//学号查询    将该学号的所有其他数据设置为禁用状态  is_now字段为0，标识不在本班
						//status为1： 启用（默认启用）
						if ($status == '' || $status == 1) {
							$map['account'] = array('eq',$data['account']);
							$udata['is_now'] = 0;
							$rs = $student->where($map)->save($udata);
						}
					} else {
					}
					//添加学生
					$res=$student->data($data)->add();
					if($res){
						$count+=1;
					}
				}
			} else {
				//去掉$reaccount的最后一个字符
				$reaccount = substr($reaccount,0,strlen($reaccount)-1);
			}
		}else{
			$this->error("请选择导入的文件");
		}
		$result['reaccount'] = $reaccount;
		$result['msg'] = $msg;
		$result['count'] = $count;
		$result['data'] = $data;
		echo json_encode($result);exit;
	}
}