<?php
/**
 * 学生管理控制器
 */
namespace Home\Controller;
use Think\Controller;

class StuManageController extends  BaseController {
	/*年级管理查看全部方法*/
    public function StuManage_Grade_Manage(){
        $this->display();
    }
    /*查询出年级信息*/
     public function StuManage_Gradelist(){
        if(IS_AJAX){
            //判断分页信息
            $pagenum = isset($_GET['page'])?intval($_GET['page']) : 1;
            $pagesize = isset($_GET['rows'])?intval($_GET['rows']) : 10;
            //判断是否有查询信息
            if(isset($_GET['name'])&&!empty($_GET['name'])){
                $map['zyyj_grade.name'] = array('like',"%".I('name')."%");
            }
            $Ret3=M('Grade')->where($map)->limit(($pagenum-1)*$pagesize.','.$pagesize)->order("convert(name using gb2312) DESC")->select();
            $total = sizeof($Ret3);
            //没有数据时，格式化输出，避免前端报错
            if($total ==0){
                 $jsonStr='{"total":'.$total.',"rows":[]}';
            }else{
                $jsonStr='{"total":'.$total.',"rows":'.json_encode($Ret3).'}';
            } 
            echo $jsonStr;
        }
     }
    /*添加年级方法*/
    public function StuManage_Grade_Add(){
        if(IS_AJAX){
            $data2['name'] = I('gradename');
            $data2['comment'] = I('comment');
            $Check=M('Grade')->where(array('name'=>$data2['name']))->count();
            if($Check!=0){
                $Addre['success']=false;
                $Addre['msg']='添加失败，年级信息有重复！';
            }else{
                $Ret6 = M('Grade')->add($data2);
                if($Ret6){
                    $Addre['success']=true;
                    $Addre['msg']='添加成功';
                }
            }
            $this->ajaxreturn($Addre,'json');
        }
    }
    /*验证年级信息是否存在方法*/
    public function StuManage_IssetGrade(){
        if(IS_AJAX){
            $thisname = I('gradename');
            $thismajorid = I('major_id');
            $Ret7 = M('Grade')->where(array('name'=>$thisname,'major_id'=>$thismajorid))->count();
            if($Ret7!=0){
                $retinfo6['success'] = false;
                $retinfo6['msg']  = '已经存在该年级!';   
            }else{
                $retinfo6['success'] = true;
                $retinfo6['msg']  = '可以使用！'; 
            }
            $this->ajaxreturn($retinfo6,'json');
        }
    }
    /*修改年级信息方法*/
    public function StuManage_Grade_Amend(){
        if(IS_AJAX){
            $gradeid = I('grade_id');
            $data['name'] = I('name');
            $data['comment'] = I('comment');
            $data2['name'] = I('name');
            $data2['grade_id'] = array('NEQ',$gradeid);
          	$isset = M('Grade')->where($data2)->count();
          	if($isset!=0){
          		$retinfo = array('success'=>false,'msg'=>':(  修改失败,有同名年级信息存在！');
          	}else{
          		$Ret4 = M('Grade')->where(array('grade_id'=>$gradeid))->save($data);
            	if($Ret4!==false){
               	 	$retinfo = array('success'=>true);
           	 	}else{
                	$retinfo = array('success'=>false);
            	}
          	}
            $this->ajaxreturn($retinfo,'json');
        }
    }
    /*删除年级信息方法*/
    public function StuManage_Grade_Delete(){
        if(IS_AJAX){
            $gradeid = I('grade_id');
            $Ret5 = M('Grade')->where(array('grade_id'=>$gradeid))->delete();
            if($Ret5!==false){
                $retinfo = array('success'=>true);
            }else{
                $retinfo = array('success'=>false);
            }
            $this->ajaxreturn($retinfo,'json');
        }
    }
}
?>