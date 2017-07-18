<?php
/**
 * 系统用户管理控制器
 */
namespace Home\Controller;
use Think\Controller;

class SysUserManageController extends  BaseController{
	/*系统用户管理方法*/
    public function SysUserManage_User_Manage(){
       $this->display();
    }
    /*获取角色信息*/
    public function SysUserManage_GetPostInfo(){
        $post = M('Post')->field('post_id,name')->select();
        echo json_encode($post);
    }
    /*加载和查找用户表数据方法*/
    public function SysManage_GetUserInfo(){
      if(IS_AJAX){
        //获取post信息
        $pagenum = I('page');
        $pagesize = I('rows');
        //获取当前用户id,以排除输出当前用户本身
        $userid = session('user_id');
        $map['zyyj_user.user_id'] = array('NEQ',$userid);
        //判断是否有查询信息
        if(isset($_POST['user'])&&!empty($_POST['user'])){
          $map['zyyj_user.realname'] = array('like',"%".I('user')."%");
        }
        if(isset($_POST['college_id'])&&!empty($_POST['college_id'])){
          $map['zyyj_department.college_id'] = I('college_id');
        }
        //判断是否有按部门筛选
        if(isset($_POST['deptid'])&&!empty($_POST['deptid'])){
          $map['zyyj_user.department_id'] = I('deptid');
        }
        $map['zyyj_user.post_id'] =array('NEQ',1);
        $Ret = M('User')->join('LEFT JOIN zyyj_department ON zyyj_department.department_id=zyyj_user.department_id')->where($map)->field('zyyj_department.name as dptname,zyyj_department.*,zyyj_user.*')->limit(($pagenum-1)*$pagesize.','.$pagesize)->select();
        $total = sizeof($Ret);
        $jsonStr='';
        if($total ==0){
            $jsonStr='{"total":'.$total.',"rows":[]}';
        }else{
            $jsonStr='{"total":'.$total.',"rows":'.json_encode($Ret).'}';
        }
        echo $jsonStr;
      }
    }
    /*重置密码的方法*/
    public function SysUserManage_User_Resetpwd(){
        if(IS_AJAX){
            $userid=I('userid');
            $data['password']=sha1(md5(123456));
            $issuccess=M('user')->where(array('user_id'=>$userid))->save($data);
            $ret['success']=true;
        }else{
            $ret['success']=false;
        }
      $this->ajaxreturn($ret,'json');
    }
    /**
     * [SysUserManage_SetStatus 修改账号状态]
     */
    public function SysUserManage_SetStatus(){
      if(IS_AJAX){
        $userid = I('user_id');
        $status = intval(I('status'));
        if($status==1){
          $data['status'] = 0;
        }else{
          $data['status'] = 1;
        }
        $set = M("User")->where(array('user_id'=>$userid))->save($data);
        $ret['success'] = true;
        echo json_encode($ret);
      }
    }
    /*修改管理员信息的方法*/
    public function SysUserManage_User_Amend(){
      if(IS_AJAX){
        $userid = I('user_id');
        $data['status'] = I('status');
        $ARet = M('User')->where(array('user_id'=>$userid))->save($data);
        if($ARet!==false){
            $Aretinfo = array('success'=>true);
        }else{
            $Aretinfo = array('success'=>false);
        }
         $this->ajaxreturn($Aretinfo,'json');
      }
    }
    /**
     * 添加以及修改部门信息的方法
     */
    public function SysUserManage_Dpt_Insert(){
        if(IS_AJAX){
          $data['college_id'] = I('college_id');
          $data['name'] = I('name');
          $data['comment'] = I('comment');
          $data['parent_id'] = I('f_id');
          $type = I('Dtype');
          $dpt_id = I('dpt_id');
          if($type=='add'){
            $isset = M('Department')->where(array('college_id'=>$data['college_id'],'name'=>$data['name']))->count();
          }else{
            //当为修改信息的时候，判断修改过后是否有不部门名字的重复
            $sx['department_id'] = array('NEQ',$dpt_id);
            $sx['college_id'] = $data['college_id'];
            $sx['parent_id'] = $data['parent_id'];
            $sx['name'] = $data['name'];
            $isset = M('Department')->where($sx)->count();
          }
          if($isset!=0){
            $ret['success'] = false;
            $ret['msg'] = '操作失败，该部门已经存在！';
          }else{
            if(intval($data['parent_id'])==1){
              $tree_code = 1;
            }else{
              $fathercode = M('Department')->where(array('college_id'=>$data['college_id'],'department_id'=>$data['parent_id']))->field('tree_code')->select();
              $tree_code = intval($fathercode[0]['tree_code'])+1;
            }  
            $data['tree_code'] = $tree_code;
            if($type=='add'){
              $add = M('Department')->add($data);
              if($add){
                $ret['success'] = true;
                $ret['msg'] = '操作成功！';
              }else{
                $ret['success'] = false;
                $ret['msg'] = '操作失败,请重试！';
              }
            }else{
              $dpt_id = I('dpt_id');
              $edit = M('Department')->where(array('department_id'=>$dpt_id))->save($data);
              if($edit!==false){
                $ret['success'] = true;
                $ret['msg'] = '操作成功！';
               }else{
                $ret['success'] = false;
                $ret['msg'] = '操作失败,请选择上级部门！';
              }
            }
          }
          $this->ajaxreturn($ret,'json');
        }
    }
    /**
     * 部门信息删除方法
     */
    public function SysUserManage_Dpt_delete(){
      if(IS_AJAX){
        $dptid = I('department_id');
        //查询是否有关联数据
        $classnum = M('Class')->where(array('department_id'=>$dptid))->count();
        $usernum = M('User')->where(array('department_id'=>$dptid))->count();
        $dptnum  = M('Department')->where(array('parent_id'=>$dptid))->count();
        if($classnum!=0||$usernum!=0||$dptnum!=0){
            $ret['success'] = false;
            $ret['msg'] = '删除失败,请先删除与该部门关联的数据！';
        }else{
            $delete = M('Department')->where(array('department_id'=>$dptid))->delete();
            if($delete==0){
              $ret['success'] = false;
              $ret['msg'] = '删除失败,请重试！';
            }else{
              $ret['success'] = true;
              $ret['msg'] = '删除成功！';
            }
        }
        $this->ajaxreturn($ret,'json');
      }
    }
    //管理员信息删除方法
    public function SysUserManage_User_delete(){
      if(IS_AJAX){
        $data['user_id'] = I('user_id');
        $DRet = M('User')->where($data)->delete();
        if($DRet==0){
           $Retinfo['success']=false;
        }else{
          $Retinfo['success']=true;
        }
        $this->ajaxreturn($Retinfo,'json');
      }
    }
    //管理员添加方法
    public function SysUserManage_User_Insert(){
      if(IS_AJAX){
        $data['post_id']=I('post_id');
        $data['department_id']=I('department_id');
        $data['name'] = I('username');
        $data['password'] = sha1(md5(I('password')));
        $insert = M('User')->add($data);
        if($insert){
          $insetRet['success']=true;
        }else{
         $insetRet['success']=false;
        }
        $this->ajaxreturn($insetRet,'json');
      }
    }
    /*部门管理*/
    public function SysUserManage_Dpt_Manage(){
    	$this->display();
    }
   /*获取学院信息列表
    public function SysUserManage_Dpt_GetClist(){
      $major= M('College')->field('name,college_id')->select();
      echo json_encode($major);
    }*/

  
    /*获取部门信息*/
    //由于此方法要用于判断是否有父节点，故重写一个通过学院获取部门的方法
    public function SysUserManage_Dpt_GetDlist(){
      if(isset($_GET['college_id'])){
        $map['college_id'] = $_GET['college_id'];
      }
      $Dpt = M('Department')->where($map)->field('name,department_id')->select();
      $num = sizeof($Dpt);
      if($num==0){
        echo '[{"name":"无","department_id":-1}]';
      }else{
        echo json_encode($Dpt);
      }
    }
    /*通过学院关联获取部分部门信息*/
    public function SysUserManage_GetbDlist(){
      $collegeid = intval($_GET['cid']);
      $data['college_id'] = $collegeid;
      $nums = M('Department')->where($data)->field('name,department_id')->count();
      if($nums==0){
        echo '[{"name":"无","department_id":-1}]';
      }else{
        $CDpt = M('Department')->where($data)->field('name,department_id')->select();
        echo json_encode($CDpt);
      }
    }
    /*当前用户能管理部门信息获取方法*/
    public function SysUserManage_Dpt_GetInfo(){
      if(IS_AJAX){
        //获取分页信息
        $pagenum = I('page');
        $pagesize = I('rows');
        //判断是否有查询信息
        if(isset($_POST['dname'])&&!empty($_POST['dname'])){
          $data['zyyj_department.name'] = array('like',"%".I('dname')."%");
        }
        if(isset($_POST['college_id'])&&!empty($_POST['college_id'])){
          $data['zyyj_department.college_id'] = I('college_id');
        }
        $Dinfo = D('Department')->relation(true)->where($data)->limit(($pagenum-1)*$pagesize.','.$pagesize)->select();
        $total = sizeof($Dinfo);
      }
        $DRetinfo = '';
        if($total==0){
               $DRetinfo='{"total":'.$total.',"rows":[]}';
           }else{
               $DRetinfo='{"total":'.$total.',"rows":'.json_encode($Dinfo).'}';
        }
        echo $DRetinfo;
    }
}
?>