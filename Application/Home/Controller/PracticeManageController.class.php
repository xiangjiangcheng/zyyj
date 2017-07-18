<?php
namespace Home\Controller;
use Think\Controller;
/*
*练习管理接口
*编码：谢旷
*2016-8-3
*/
class PracticeManageController extends Controller {
	
	//获取关卡页面信息
	function PracticeManage_CheckpointInfo(){
		//接收学生id和课程id	
		$map['stu_id']=I('stu_id');		//获取学生id		
		$map['type']=1;		//设置类型为练习


		if(!I('stu_id')||!I('course_id')){
			$result['status']=0;
			$result['message']="缺少相关参数，数据加载失败";
			echo json_encode($result);exit;
		}

		$cp = M('checkpoint');		//实例化关卡，学生练习，方案模型
		$sp = M('student_practice');
		$pr = M('programme');
		
		$rs = $sp->where($map)->order('checkpoint_id desc')->select();	//查找相关练习记录
		
		//判断该学生是否已经联系过某个方案
		if($rs==null){
			//获取最新方案
			$rs = $pr->order('createdate desc')->limit(1)->select();
			$map1['programme_id']= $rs[0]['programme_id'];		//获取最新方案id
			$result['total_checkpoint'] = $cp->where($map1)->select();	//获取该方案下所有关卡信息
			$result['current_cid'] = 0;	//标识当前学生尚未闯关
			$result['passed'] = 1;		//不能开启下一关

		}else{
			$map1['programme_id'] = $rs[0]['programme_id'];  //当前学生练习的方案id

			$result['total_checkpoint'] = $cp->where($map1)->select();	//获取该方案下所有关卡信息

			$map['course_id']=I('course_id');		//获取课程id

			$rs = $sp->where($map)->order('checkpoint_id desc')->select();	//查找相关练习记录

			$result['current_cid'] = $rs[0]['checkpoint_id']; //学生当前练习关卡

			$result['passed'] = $rs[0]['status'];//学生是否能开启下一关（1：不能，2：能）
		}
		// echo $cp->getLastSql();
		if($result['total_checkpoint'] == null){				//没有关卡信息
			$result['status']=1;
			$result['message']="关卡尚未部署，请稍后重试";
			echo json_encode($result);exit;
		}
		
		$result['status']=1;
		$result['message']="关卡信息加载成功";
		echo json_encode($result);exit;
	}
}