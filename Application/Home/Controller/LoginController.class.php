<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends BaseController {
     //登录验证
    public function Login_login(){
        $name=I('manager');
        $password=sha1(md5(I('password')));
		echo '$password';die;
        $user=M('user');
        $department=M("department");
        $map['name']=$name;
        $res=$user->where($map)->select();
        if($res['0']['password']==$password){
            $department_arr['department_id']=$res['0']['department_id'];
            $department_res=$department->where($department_arr)->select();
            session('college_id',$department_res['0']['college_id']);
            session("user_id",$res['0']['user_id']);
            session("post_id",$res['0']['post_id']);
            session("realname",$res['0']['realname']);
            $data['success']=1;
            $this->ajaxreturn($data,'json');
        }else{
            echo 0;
        }
    }
    //退出登录
    public function Login_loginout(){
        session('[destroy]');
        $this->redirect("Index/index");
    }
    //加载菜单页面
    public function Login_menu(){
        $this->display();
    }
    //获取菜单信息
    public function Login_get_menu(){
        $id = I('id');      //父节点id
        if($id==""||$id==null){
            $id=0;
        }
        $map['nid']=$id;
        $post=M('post');
        $menu=M('menu');
        $json='';
        $post_arr['post_id']=session('post_id');
        $res1=$post->where($post_arr)->select();//读取用户所拥有的菜单
        $menu_array=explode(";", $res1['0']['menu_id']);//分隔菜单id
        foreach ($menu_array as $row) {             //循环读出菜单
            $map['id']=$row;
            $res=$menu->where($map)->select();
            foreach ($res as $row) {
                $json .= json_encode($row).',';
            }
        }
        $json = substr($json, 0, -1);
        echo '['.$json.']';
    }
    /**
     * 读取个人资料
     */
    public function Login_self(){

        echo "dwadwa";

        echo "asd";

        $data = M('user')->where(array('user_id' => session('user_id')))->find();   //获取session中的id用户的信息
        $data['department'] = M('department')->where(array('department_id' => $data['department_id']))->field('name')->find();//查找部门
        $data['department'] = $data['department']?$data['department']['name']:"暂无";
        $data['gender'] = ($data['gender'] == 0)?'女':'男';
        $this->assign('data',$data);
        $this->display();

    }
    /**
     * 修改个人资料处理
     */
    public function Login_self_handle(){
        $data = array(
            'user_id' => I('id'),
            'realname' => I('realname'),
            'gender' => I('gender'),
            'email' => I('email'),
            'phone' => I('phone')
            );
        $data['gender'] = ($data['gender'] == '女')?0:1;
        $result = M('user')->where(array('user_id' => $data['user_id']))->save($data);
        if (!$result) {
            $this->ajaxReturn(array('success' => 0));
        }
        $data['success'] = 1;
        $this->ajaxReturn($data);
    }
}