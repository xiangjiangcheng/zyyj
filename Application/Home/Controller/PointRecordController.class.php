<?php
	namespace Home\Controller;
	use Think\Controller;
	/**
	 * 用户部分记录读取
	 */
	class PointRecordController{
		//用户最新关卡记录读取
		public function PointRecord_getdata(){
			$stu_id = I('stu_id');
			$where = array(
				'type' => 1,
				'stu_id' => $stu_id
			);
			$data = M('student_practice')->
			join('LEFT JOIN zyyj_course ON zyyj_student_practice.course_id = zyyj_course.course_id
			LEFT JOIN zyyj_checkpoint ON zyyj_student_practice.checkpoint_id = zyyj_checkpoint.checkpoint_id')
			->field('zyyj_student_practice.practice_id,zyyj_course.name as coursename,zyyj_checkpoint.name as pointname')
			->where($where)->order('create_time desc')->limit(1)->find();
			if($data){
				$data['status'] = 1;//读取成功
				$data['message'] = '信息读取成功';
			}else{
				$data['status'] = -1;//读取失败
				$data['message'] = '信息读取失败';
			}
			echo json_encode($data);
		}

		//练习1考试2记录数据读取
		public function PointRecord_practicerecord(){
			$where = array(
				'stu_id' => I('stu_id'),
				'type' => I('type')
				);
			$data1 = M('student_practice')
			->join('LEFT JOIN zyyj_checkpoint ON zyyj_student_practice.checkpoint_id = zyyj_checkpoint.checkpoint_id')
			->where($where)->order('create_time DESC')->field('zyyj_student_practice.score as get_score, zyyj_student_practice.create_time,zyyj_checkpoint.name as pointname,zyyj_checkpoint.total_score')->find();
			$data2 = M('stu_score')->where($where)->order('create_date desc')->field('score as rankscore')->find();
			$data = array_merge($data1,$data2);
			if(!$data){
				$data['status'] = -1;//读取失败
				$data['message'] = '信息读取失败';
			}else{
				$data['status'] = 1;//读取成功
				$data['message'] = '信息读取成功';
				$data['rpercent'] = round(($data['get_score']/$data['total_score'])*100,2);
				$data['epercent'] = round((100-$data['rpercent']),2);
			}
			echo json_encode($data);
		}	
	}
?>