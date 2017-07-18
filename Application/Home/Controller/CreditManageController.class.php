<?php
	/**
	 * 学生积分管理控制器
	 */
namespace Home\Controller;
use Think\Controller;
class CreditManageController extends BaseController{
	/**
	 * 积分查看模板的display（积分排行榜）
	 */
	public function CreditManage_ShowCredit(){
		$this->display();
	}

	/**
	 * [Cmp 自定义排规则]
	 * @param $a 用于排序的数组
	 * @param $b 用于排序的数组
	 */
	private function Cmp($a,$b){
		if($a['tscore'] == $b['tscore']){  
			return 0;
		}   
		return($a['tscore']<$b['tscore']) ? -1 : 1;
	}

	/**
	 *获取筛选条件方法
	 */
	public function CreditManage_Getpost($post){
		//判断是否有按学号查询
		if(isset($post['account'])&&!empty($post['account'])){
			$map['zyyj_student.account'] = array('like',"%".$post['account']."%");
		}
		//判断是否有按姓名查询
		if(isset($post['stuname'])&&!empty($post['stuname'])){
			$map['zyyj_student.name'] = array('like',"%".$post['stuname']."%");
		}
		if(isset($post['majorid'])&&!empty($post['majorid'])){
			$map['zyyj_major.major_id'] = $post['majorid'];
		}
		if(isset($post['gradeid'])&&!empty($post['gradeid'])){
			$map['zyyj_grade.grade_id'] = $post['gradeid'];
		}
		if(isset($post['classid'])&&!empty($post['classid'])){
			$map['zyyj_class.class_id']  = $post['classid'];
		}
		$map['zyyj_college.college_id'] = session('college_id');
		return $map;
	}

	/**
	 * 积分查看模板的数据记载
	 */
	public function CreditManage_GetInfo(){
		//初始化分页信息
		$pagenum = isset($_POST['page']) ? intval($_POST['page']):1;
		$pagesize = isset($_POST['rows']) ? intval($_POST['rows']):20;
		$flag = 0;
		if(isset($_GET['type']) && $_GET['type']=='opt'){
			$map = $this->CreditManage_Getpost($_GET);
			$flag = 1;
		}else{
			$map = $this->CreditManage_Getpost($_POST);
		}
		//多表关联查询出全部信息
		$score1=  M('Stu_score')->join('LEFT JOIN zyyj_student ON zyyj_student.stu_id = zyyj_stu_score.stu_id LEFT JOIN zyyj_class ON zyyj_class.class_id = zyyj_student.class_id LEFT JOIN zyyj_grade ON zyyj_grade.grade_id = zyyj_student.grade_id LEFT JOIN zyyj_major ON zyyj_major.major_id = zyyj_student.major_id LEFT JOIN zyyj_college ON zyyj_college.college_id = zyyj_student.college_id')
			->where($map)
			->field('zyyj_student.account,
					 zyyj_student.name as stuname,
					 sum(score) as tscore, 
					 zyyj_class.name as class_name,
					 zyyj_grade.name as grade_name,
					 zyyj_major.name as major_name,
					 zyyj_college.name as college_name,
					 zyyj_Stu_score.stu_id,
					 programme_id')
			->group('stu_id')->limit(($pagenum-1)*$pagesize.','.$pagesize);
		$score =$score1->select();
		$snum = sizeof($score);// 得到数组长度
		//由更新时间逆序获取方案表里的方案信息
		$dqp = M('Programme')->order('createdate desc')->select();
		//获当前最新的方案编号
	
		$dqpid = $dqp[0]['programme_id'];
		//获取当前最新的方案总分
		$dqscore = $dqp[0]['score'];
		$get = array();
		//将方案编号作为key键 保存方案对应的总分。
		$pnum = sizeof($dqp);
		for($i=0;$i<$pnum;$i++){
			$get[$dqp[$i]['programme_id']]=$dqp[$i]['score'];
		}
		//根据当前方案调整用户的积分
		for($i=0;$i<$snum;$i++){
			if($score[$i]['programme_id']!=$dqpid){
					if($get[$score[$i]['programme_id']]>=$dqscore){
						$score[$i]['tscore'] /=$get[$score[$i]['programme_id']]/$dqscore;
					}else{
						$score[$i]['tscore'] *=$dqscore/$get[$score[$i]['programme_id']];
					}
			}
		}
		//获取需要排序的列
		foreach ($score as $key => $row){
		     $tscore[$key]  = $row['tscore'];
		}
		array_multisort($tscore, SORT_DESC,$score);


		//判断请是导出还是加载数据
		if($flag){
			// 导出
			for($i=0;$i<$snum;$i++){
				unset($score[$i]['stu_id']);
				unset($score[$i]['programme_id']);
			}
			$this->export($score);
		}else{
			if($snum ==0){
	            $jsonStr='{"total":'.$snum.',"rows":[]}';
	          }else{
	            $jsonStr='{"total":'.$snum.',"rows":'.json_encode($score).'}';
	        }
			echo $jsonStr;
		}
	}

	function export($data){
		$title="积分信息表";
		$this->downloadPdf_comment($title, $data);
		
	}
	// 导出excl
	function downloadPdf_comment($title, $data) {
			//创建对象
			Vendor("PHPExcel.PHPExcel");
			Vendor("PHPExcel.PHPExcel.IOFactory");  
			$excel = new \PHPExcel();
			$objSheet=$excel->getActiveSheet();
			$objSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->
			setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//设置excel文件默认水平垂直方向居中
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(17); //A列宽度
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); //A列宽度
			//Excel表格式,这里简略写了2列
			$letter = array('A','B','C','D','E','F','G');
			//表头数组
			$tableheader = array('学号', '姓名','积分','班级','年级','专业','学院');
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


	/**
	 * 设置积分规则模板的display
	 */
	public function CreditManage_CreditRule(){
		$this->display();
	}

	public function CreditManage_Export(){
		print_r($_GET);
	}

	/**
	 * 获取积分兑换规则数据
	 */
	public function CreditManage_GetRule(){
		$Rule = M('Exhcangerule')->order('createdate desc')->select();
		echo json_encode($Rule);
	}
	/**
	 * 删除兑换规则方法
	 */
	public function CreditManage_DeletRule(){
		if(IS_AJAX){
			$ruleid = I('rule_id');
			$status = I('status');
			$delet = M('Exhcangerule')->where(array('rule_id'=>$ruleid))->delete();
			if($delet==0){
				$ret['success'] = false;
			}else{
				$ret['success'] = true;
			}
			echo json_encode($ret);
		}
	}
	/**
	 * 修改和添加兑换规则方法
	 */
	public function CreditManage_UpdateRule(){
		if(IS_AJAX){
			$ret=array();
			//获取请求的信息
			$ruleid = I('ruleid');
			$data['name'] = I('rulename');
			$data['score'] = I('needscore');
			$data['integral'] = I('getcredit');
			$data['status'] = intval(I('rulestatus'));
			$data['createdate'] = date("y-m-d H:i:s",time());
			
			if(I('type')=='add'){
				//首次添加的时候，默认为激活状态，其余情况为禁用状态
				$datenum = M('Exhcangerule')->count();
				if($datenum==0){
					$data['status'] = 1;
				}
				//判断是否有重名规则
				$isset = M('Exhcangerule')->where(array('name'=>$data['name']))->count();
				if($isset!=0){
					$ret['success'] = false;
					$ret['msg'] = '操作失败,有重名规则存在！';
				}else{
					//没有重名，插入数据库
					$insert = M('Exhcangerule')->add($data);
					if($insert){
						$ret['success'] = true;
						$ret['msg'] = '操作成功';
					}else{
						$ret['success'] = false;
						$ret['msg'] = '操作失败';
					}
				}
			}else{
				//如果是修改操作，验证操作是否合法
				$data2['name'] = $data['name'];
				$data2['rule_id'] = array('NEQ',$ruleid);
				$isset2 =  M('Exhcangerule')->where($data2)->count();
				if($isset2!=0){
					$ret['success'] = false;
					$ret['msg'] = '操作失败,有重名规则存在！';
				}else{
					//更新为激活状态时，将原有激活规则禁用
					if($data['status']==1){
						$data3['status']=0;
						$data3['createdate'] = date("y-m-d",time());
						$xg =M('Exhcangerule')->where(array('status'=>1))->save($data3);
						$update1 = M('Exhcangerule')->where(array('rule_id'=>$ruleid))->save($data);
						$ret['success'] = true;
						$ret['msg'] = '操作成功';
					}else{
					//更新为禁用状态时，判断原有状态，至少保证一条规则处于激活状态
						$find = M('Exhcangerule')->where(array('rule_id'=>$ruleid))->select();
						if($find[0]['status']==1){
							$ret['success'] = false;
							$ret['msg'] = '操作失败，请保证有一条规则处于激活状态！';
						}else{
							$update2 = M('Exhca  ngerule')->where(array('rule_id'=>$ruleid))->save($data);
							$ret['success'] = true;
							$ret['msg'] = '操作成功';
						}
					}
				}
			}
			$this->ajaxreturn($ret,'json');
		}
	}
	/**
	 * 闯关情况查看模板的display
	 */
	public function CreditManage_ShowStage(){
		$this->display();
	}
	public function CreditManage_Getstage(){
		$pagenum = isset($_POST['page']) ? intval($_POST['page']):1;
		$pagesize = isset($_POST['rows']) ? intval($_POST['rows']):20;
		//获取筛选条件
		$map = $this->CreditManage_Getpost($_POST);
		//$map['zyyj_student_practice.type'] = 2; //1练习，2考试
		$stage = M('Student_practice')->join('
			
			LEFT JOIN zyyj_programme ON zyyj_student_practice.programme_id = zyyj_programme.programme_id
			LEFT JOIN zyyj_checkpoint ON zyyj_checkpoint.checkpoint_id = zyyj_student_practice.checkpoint_id 
			
			LEFT JOIN zyyj_student  ON zyyj_student.stu_id  = zyyj_student_practice.stu_id 
			LEFT JOIN zyyj_class ON zyyj_class.class_id = zyyj_student.class_id 
			LEFT JOIN zyyj_grade ON zyyj_grade.grade_id =  zyyj_student.grade_id   
			LEFT JOIN zyyj_major ON zyyj_major.major_id = zyyj_student.major_id  
			LEFT JOIN zyyj_college ON zyyj_college.college_id = zyyj_student.college_id
			')
			->where($map)->field('
			
				zyyj_checkpoint.name as pointname,
				zyyj_programme.name as pname,
				zyyj_student.account,
				zyyj_student_practice.status,
				zyyj_student_practice.score,
				zyyj_student_practice.course_id as couname, 
				zyyj_student.name as stuname, 
				zyyj_class.name as class_name,
				zyyj_grade.name as grade_name,
				zyyj_major.name as major_name,
				zyyj_college.name as college_name')->limit(($pagenum-1)*$pagesize.','.$pagesize)->select();
			$length = sizeof($stage);
			if($length==0){
				$jsonStr='{"total":'.$length.',"rows":[]}';
         	}else{
          	 	$jsonStr='{"total":'.$length.',"rows":'.json_encode($stage).'}';
			}
			echo $jsonStr;
	}

	
	/**
	 * 积分兑换记录模板的display
	 */
	public function CreditManage_Exchangelist(){
		$this->display();
	}

	/**
	 * 积分兑换记录的数据获取
	 */
	public function CreditManage_GetList(){
		$pagenum = isset($_POST['page']) ? intval($_POST['page']):1;
		$pagesize = isset($_POST['rows']) ? intval($_POST['rows']):20;
		
		//获取 筛选条件
		$map = $this->CreditManage_Getpost($_POST);
		$exchange = M('Exchangeintegral')->join('
			LEFT JOIN zyyj_exhcangerule ON zyyj_exhcangerule.rule_id=zyyj_exchangeintegral.rule_id
			LEFT JOIN zyyj_student ON zyyj_student.stu_id=zyyj_exchangeintegral.stu_id
			LEFT JOIN zyyj_class ON zyyj_class.class_id = zyyj_student.class_id 
			LEFT JOIN zyyj_grade ON zyyj_grade.grade_id =  zyyj_student.grade_id   
			LEFT JOIN zyyj_major ON zyyj_major.major_id = zyyj_student.major_id  
			LEFT JOIN zyyj_college ON zyyj_college.college_id = zyyj_student.college_id'
			)->where($map)->field('zyyj_exhcangerule.name as rname,zyyj_student.account as account,zyyj_class.name  as class_name,zyyj_grade.name as grade_name,zyyj_major.name as major_name,zyyj_college.name as college_name,
			zyyj_student.name as stuname,zyyj_exchangeintegral.score as needscore,zyyj_exchangeintegral.integral as getscore,zyyj_student.gender as stusex,zyyj_exchangeintegral.createdate as time')
			->order('zyyj_exchangeintegral.createdate desc')->limit(($pagenum-1)*$pagesize.','.$pagesize)->select();
			$length = sizeof($exchange);
			if($length==0){
				$jsonStr='{"total":'.$length.',"rows":[]}';
         	}else{
          	 	$jsonStr='{"total":'.$length.',"rows":'.json_encode($exchange).'}';
			}
			echo $jsonStr;
	}
}
?>
