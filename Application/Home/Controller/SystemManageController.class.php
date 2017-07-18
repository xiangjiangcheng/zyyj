<?php
namespace Home\Controller;
use Think\Controller;
class SystemManageController extends BaseController {
     //系统参数
    public function SystemManage_SystemParameter_list_show(){
        $system = M('system');

        //搜索条件
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $result = array();

        $rs = $system->count();
        
        $result["total"] = $rs;
        $rs = $system->limit($offset.','.$rows)->select();
        
        $items = array();
        $items = $rs;

        if($items == null){
            $result["rows"] = "";
        }else{
            $result["rows"] = $items;
        }
        echo json_encode($result);
    }


    //加载视图
    public function SystemManage_SystemParameter_list(){
        $this->display();
    }

    //修改系统参数操作
    public function SystemManage_SystemParameter_edit(){
        if(IS_AJAX){
            $parameter_id = I('parameter_id');
            $data['value'] = I('value');

            $Ret4 = M('system')->where(array('parameter_id'=>$parameter_id))->save($data);
            if($Ret4!==false){
                $retinfo = array('success'=>true);
            }else{
                $retinfo = array('success'=>false);
            }

            $this->ajaxreturn($retinfo,'json');
        }
    }

}