<?php
namespace Home\Controller;
use Think\Controller;
/*
*学生头像上传接口
*编码：谢旷
*2016-8-8
*/
class StudentUpdateIconController extends Controller {
	
	function do_upload(){
		date_default_timezone_set('prc');		//设置时区，中国  
		$time=time();							//获取当前时间戳
		//接收学号和密码	
		$map['stu_id']=I('stu_id');		//获取学生学号

		$student=M('student');
		$map['status']=1;	//该学生必须是激活状态
		$res=$student->where($map)->select();//根据学号查询该学生

		if(!$_FILES['photo'] || !I('stu_id')){	//传递参数不足
        	$result['status']=-3;
			$result['message']="请求参数不足";
			echo json_encode($result);exit;
        }

		if(count($res)<=0){				//没有该学生或处未被激活状态
			$result['status']=0;
			$result['message']="该学生不存在或未被激活";
			echo json_encode($result);exit;
		}

        $upload = new \Think\Upload();// 实例化上传类
        $photo=$time."".rand(1,100000);		//时间戳加随机数作为文件名
        $upload->maxSize   =     553145728 ;// 设置附件上传大小
        $upload->saveName=$photo;		//设置上传文件后的文件名字
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型

        $info = $upload->uploadOne($_FILES['photo']);	//获取前台表单input名为photo的文件

        if(!$info) {// 上传错误
            $result['status']=-1;
			$result['message']="图片上传失败";
			echo json_encode($result);exit;
        }else{// 上传成功
        	$data['photo']=$info['savepath']."".$info['savename'];
            $result = $student->where($map)->save($data);
            $result['status']=1;
			$result['message']="图像信息保存成功";
			echo json_encode($result);exit;
    	}
	}
}