<?php
namespace Home\Controller;
use Think\Controller;
/*
*考试管理接口
*编码：谢旷
*2016-8-3
*/
class ExamManageController extends Controller {

	//获取等级
	function ExamManage_levelInfo(){
		//接收学生id和课程id	
		$map1['stu_id']=I('stu_id');		//获取学生id		
		$map1['type']=2;		//设置类型为考试

		if(!I('stu_id')||!I('course_id')){
			$result['status']=0;
			$result['message']="缺少相关参数，数据加载失败";
			echo json_encode($result);exit;
		}

		$el = M('exam_level');		//实例化关卡，学生练习，方案模型
		$sp = M('student_practice');
		$ep = M('exam_program');
		
		$rs = $sp->where($map1)->order('checkpoint_id desc')->select();	//查找相关考试记录
		
		//判断该学生是否已经过某个方案
		if($rs==null){
			//获取最新方案
			$rs = $ep->order('createdate desc')->limit(1)->select();
			$map2['program_id']= $rs[0]['program_id'];		//获取最新方案id
			$result['total_level'] = $el->where($map2)->select();	//获取该方案下所有关卡信息
			$result['current_lid'] = 0;	//标识当前学生尚未闯关
			$result['passed'] = 1;		//不能开启下一关

		}else{
			$map2['program_id'] = $rs[0]['programme_id'];  //当前学生练习的方案id

			$result['total_level'] = $el->where($map2)->select();	//获取该方案下所有等级信息

			$map2['course_id']=I('course_id');		//获取课程id
			$map2['stu_id']=I('stu_id');		//获取学生id		
			$map2['type']=2;		//设置类型为考试

			$rs = $sp->where($map2)->order('checkpoint_id desc')->select();	//查找相关练习记录

			$result['current_lid'] = $rs[0]['checkpoint_id']; //学生当前练习关卡

			$result['passed'] = $rs[0]['status'];//学生是否能开启下一关（1：不能，2：能）
		}
		
		if($result['total_level'] == null){				//没有关卡信息
			$result['status']=1;
			$result['message']="等级尚未部署，请稍后重试";
			echo json_encode($result);exit;
		}

		$result['status']=1;
		$result['message']="等级信息加载成功";
		echo json_encode($result);exit;
	}
	
	//是否扣除积分
	function ExamManage_Judgement(){
		//接收学生id和课程id	
		$map1['stu_id']=I('stu_id');		//获取学生id	
		$map1['course_id']=I('course_id');		//获取课程id
		$map1['checkpoint_id']=I('level_id');	//获取等级id
		$map1['type']=2;		//设置类型为考试

		if(!I('stu_id')||!I('course_id')||!I('level_id')){
			$result['status']=0;
			$result['message']="缺少相关参数，数据加载失败";
			echo json_encode($result);exit;
		}

		$el = M('exam_level');		//实例化关卡，学生练习，方案 和 系统参数 模型
		$sp = M('student_practice');
		$ep = M('exam_program');
		$sys = M('system');
		
		$rs = $sp->where($map1)->order('checkpoint_id desc')->select();	//查找相关考试记录
		$lt = $sys->where("name = 'limit_time'")->select();//获取限制的考试次数
		$ss = $sys->where("name = 'sup_score'")->select();//获取叠加的分数
		
		//判断该学生是否已经考过
		if($rs==null){
			$result['status'] = 0;	//返回状态
			$result['message'] = "未超过考试次数，不需要扣除积分";	//返回信息
			$result['de_score'] = 0;  //需要扣除的积分
		}else{
			if(intval($rs[0]['num']) >= intval($lt[0]['value'])){
				$result['status'] = 1;	//返回状态
				$result['message'] = "需要扣除积分";	//返回信息
				$result['de_score'] = (intval($rs[0]['num'])-intval($lt[0]['value'])+1)*intval($ss[0]['value']);	//需要扣除的积分
			}else{
				$result['status'] = 0;	//返回状态
				$result['message'] = "未超过考试次数，不需要扣除积分";	//返回信息
				$result['de_score'] = 0;  //需要扣除的积分
			}
		}
		echo json_encode($result);exit;
	}
}