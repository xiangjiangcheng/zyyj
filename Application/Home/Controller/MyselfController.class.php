<?php
namespace Home\Controller;
use Think\Controller;
class MyselfController extends BaseController {
	 /**
     * 读取个人资料
     */
    public function Myself_self(){
        $data = M('user')->where(array('user_id' => session('user_id')))->find();   //获取session中的id用户的信息
        $data['department'] = M('department')->where(array('department_id' => $data['department_id']))->field('name')->find();//查找部门
        $data['department'] = $data['department']?$data['department']['name']:"暂无";
        $data['gender'] = ($data['gender']&&($data['gender'] == 0))?0:1;
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * 修改个人资料
     */
    public function Myself_self_handle(){
        $data = array(
            'user_id' => I('id'),
            'realname' => I('realname'),
            'gender' => I('gender'),
            'email' => I('email'),
            'phone' => I('phone')
            );
        $result = M('user')->where(array('user_id' => $data['user_id']))->save($data);
        if (!$result) {
            $this->ajaxReturn(array('success' => 0));
        }
        session("user_id",$data['user_id']);
        session("realname",$data['realname']);
        $data['success'] = 1;
        $this->ajaxReturn($data);
    }
    /**
     * 加载修改密码视图
     */
    public function Myself_password(){
        $data = M('user')->where(array('user_id' => session('user_id')))->find();
    	$this->assign('data',$data)->display();
    }
    /**
     * 修改密码处理
     * 0:密码错误 2:更新失败 1:修改成功
     */
    public function Myself_password_handle(){
    	$data = array(
    		'user_id' => (int)I('user_id'),
    		'password' => I('password','','md5'),
    		'newpwd' => I('newpwd','','md5')
    		);
    	$data['password'] = sha1($data['password']);
    	$data['newpwd'] = sha1($data['newpwd']);
    	$result = M('user')->where(array('user_id' => $data['user_id'],'password' => $data['password']))->find();
    	if (!$result) {
    		$this->ajaxReturn(array('status' => 0));
    	}
    	$newdata = array(
    		'user_id' => $data['user_id'],
    		'password' => $data['newpwd']
    		);
    	$result = M('user')->where(array('user_id' => $data['user_id']))->save($newdata);
    	if(!$result){
    		$this->ajaxReturn(array('status' => 2));
    	}
    	$this->ajaxReturn(array('status' => 1));
    }
}