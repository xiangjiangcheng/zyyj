<?php
namespace Home\Controller;
use Think\Controller;
/*
*学生信息接口
*编码：谭继兵
*2016-8-2
*/
class StudentInterfaceController extends Controller {
	
	//进行登录
	function do_login(){
		//接收学号和密码	
		$map['account']=I('account');		//获取学生学号
		$password=sha1(md5(I('password')));//获取学生密码

		$json_array=array();
		$student=M('student');
		$map['status']=1;	//该学生必须是激活状态
		$res=$student->where($map)->select();//根据学号查询该学生
		if(count($res)<=0){				//没有该学生
			$json_array['status']=0;
			$json_array['message']="该学生不存在";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}
		if($res['0']['password']!=$password){	//密码不正确
			$json_array['status']=-1;
			$json_array['message']="密码不正确";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}else{									//验证成功
			session("stu_id",$res['0']['stu_id']);
			$data=array(
				"stu_id" => $res['0']['stu_id'] //学生id
			);
			$json_array['status']=1;
			$json_array['message']="登录成功";
			$json_array['data']=$data;
			echo json_encode($json_array);
		}
	}

	//获取个人资料
	function stu_getPersonInfo(){
	
		$map['stu_id']=I('stu_id');		//获取学生id
		$json_array=array();
		$student=M('student');
		$map['status']=1;	//该学生必须是激活状态
		$result=$student->where($map)->
		join('zyyj_major ON zyyj_student.major_id = zyyj_major.major_id' )->
		join('zyyj_class ON zyyj_student.class_id = zyyj_class.class_id' )->
		join('zyyj_college ON zyyj_student.college_id = zyyj_college.college_id' )->
		field('zyyj_student.name stu_name, zyyj_class.name stu_class, zyyj_major.name stu_major, zyyj_college.name stu_college, zyyj_student.account stu_account, zyyj_student.photo stu_photo')->
		select();//根据学号查询该学生
		if(count($result)<=0){				//没有该学生
			$json_array['status']=0;
			$json_array['message']="该学生不存在或数据缺失";
			$json_array['data']=null;
			echo json_encode($json_array);exit;		//缺少参数
		}else if(I('stu_id') == null){				
			$json_array['status']=-1;
			$json_array['message']="缺少传递参数";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}else{				//加载成功
			$json_array['status']=1;
			$json_array['message']="加载信息成功";
			$json_array['data']=$result;
			echo json_encode($json_array);exit;
		}
	}

	//学生修改密码
	function stu_modifyPwd(){
	
		$map['stu_id']=I('stu_id');		//获取学生id
		$data['password']=sha1(md5(I('password')));		//获取新密码

		$json_array=array();
		$student=M('student');
		$map['status']=1;				//该学生必须是激活状态
		$result=$student->where($map)->select();//根据学号查询该学生

		if(count($result)<=0){				//没有该学生
			$json_array['status']=-1;
			$json_array['message']="学生信息有误";
			echo json_encode($json_array);exit;
		}

		$result = $student->where($map)->save($data);	//保存密码
		if($result){
			$json_array['status']=1;
			$json_array['message']="修改成功";
			echo json_encode($json_array);exit;
		}else{
			$json_array['status']=0;
			$json_array['message']="修改失败";
			echo json_encode($json_array);exit;
		}
	}
}