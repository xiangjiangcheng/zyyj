<?php
namespace Home\Controller;
use Think\Controller;
class CheckpointController extends BaseController {
     //展示和查找方案信息
    public function Checkpoint_list_show(){
        $checkpoint = M('checkpoint');
        $programme = M('programme');
        $student_practice = M('student_practice');

        //搜索条件
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $programme_name = isset($_POST['checkpoint_programme']) ? mysql_real_escape_string($_POST['checkpoint_programme']) : '';
        // $grade = isset($_POST['grade']) ? mysql_real_escape_string($_POST['grade']) : '';
        // $department = isset($_POST['department']) ? mysql_real_escape_string($_POST['department']) : '';
        if($programme_name == ''){
            $programme_name = $programme->order('createdate desc')->limit(1)->getField('programme_id');
            // echo $programme->getLastSql();
            // echo "programme_name:::".$programme_name;
        }
        $offset = ($page-1)*$rows;
        $result = array();
        // echo $programme_name;
        $where = "programme_id = '".$programme_name."'";
        // echo $programme->getLastSql();
        // echo $programme_name;
        $rs = $checkpoint->where($where)->count();
        
        $result["total"] = $rs;
        $rs = $checkpoint->where($where)->limit($offset.','.$rows)->select();
        // echo $checkpoint->getLastSql();      
        // $arr = explode(";",$str);
        foreach($rs as $key=>$value){  
            foreach($rs[$key] as $subkey=>$subval){
                // echo $subval.' || ';
                if($subkey == "question_num"){
                    $arr = explode(";",$subval);
                    $rs[$key]['c_difficult'] = $arr[0];
                    $rs[$key]['c_middle'] = $arr[1];
                    $rs[$key]['c_easy'] = $arr[2];
                }               
                
            }
        }

        $data = $student_practice->where('type=1')->field('programme_id')->select();
        foreach($data as $key=>$value){ 
             foreach($value as $k=>$v){ 
                $arr1 .= $v.',';
             } 
        } 
        $arr1 = substr($arr1, 0, -1);
             // rtrim($str, ",") 
        $where = "programme_id in (".$arr1.") and programme_id = '$programme_name'";
        $result['editable'] = $checkpoint->where($where)->count();
        if ($result['editable'] == null) {
            $result['editable'] = 0;
        }
        
        $items = array();
       
        $items = $rs;
        // echo var_dump($items);die;
        if($items == null){
            $result["rows"] = "";
        }else{
            $result["rows"] = $items;
        }
        
        $result["pcounts"] = $programme->count(); //获取方案数
        // echo $where;
        // echo $checkpoint->getLastSql();
         // echo var_dump($result);die;
        echo json_encode($result);
    }

    //获取一个id的信息
    public function Checkpoint_getInfo(){
        $id = $_POST['id'];
        $student_practice = M('student_practice');
        $checkpoint = M('checkpoint');
        $where = "checkpoint_id='".$id."'";
        $result['arrp'] = $student_practice->where($where)->count();
        // $data = $m->where("major_id='".$major."'")->field('grade_id')->select();
            // $arr = "";
            // foreach($data as $key=>$value){ 
            //      foreach($value as $k=>$v){ 
            //         $arr .= $v.',';
            //      } 
            // } 
            // $arr = substr($arr, 0, -1);
            //  // rtrim($str, ",") 
            // $where = "zyyj_class.grade_id in (".$arr.") and zyyj_class.department_id = '$department'";
        // $where = "programme_id='".$id."'";
        $rs = $checkpoint->where($where)->select();
        //获取当前关卡是否为已使用方案的关卡
        $data = $student_practice->field('programme_id')->select();
        foreach($data as $key=>$value){ 
             foreach($value as $k=>$v){ 
                $arr .= $v.',';
             } 
        } 
        $arr = substr($arr, 0, -1);
             // rtrim($str, ",") 
        $where = "programme_id in (".$arr.") and checkpoint_id='".$id."'";
        $result['editable'] = $checkpoint->where($where)->count();
        $arr = explode(";",$str);
        foreach($rs as $key=>$value){  
            foreach($rs[$key] as $subkey=>$subval){
                // echo $subval.' || ';
                if($subkey == "question_num"){
                    $arr = explode(";",$subval);
                    $rs[$key]['c_difficult'] = $arr[0];
                    $rs[$key]['c_middle'] = $arr[1];
                    $rs[$key]['c_easy'] = $arr[2];
                }               
                
            }
        }
        $result['others'] = $rs;
        // echo $checkpoint->getLastSql();
        echo json_encode($result);
    }


    //加载视图
    public function Checkpoint_list(){
        $this->display();
    }
    //获取方案名
    public function Checkpoint_getProgramme(){
        $programme = M('programme');
        // $result = array();
        $result = $programme->order('createdate desc')->select();

        echo json_encode($result);
    }

    //添加关卡和修改关卡操作
    public function Checkpoint_add(){
        // echo "is comming";
        // $programme = M('programme');
        $checkpoint = M('checkpoint');
        $id = $_POST['checkpoint_edit_id'];
        $where = "checkpoint_id='".$id."'";
        // $data1 = $programme->create();
        $data1 = $checkpoint->create();
        
        $data1['name'] = $_POST['checkpoint_name'];
        $data1['total_score'] = $_POST['checkpoint_tscore'];
        // $data['major_id'] = $_POST['major'];
        $data1['pass_score'] = $_POST['checkpoint_pscore'];
        $c_difficult = $_POST['edit_checkpoint_difficult'];
        $c_middle = $_POST['edit_checkpoint_middle'];
        $c_easy = $_POST['edit_checkpoint_easy'];
        $data1['limit_time'] = $_POST['checkpoint_limit_time'];
        $data1['question_num'] = $c_difficult.';'.$c_middle.';'.$c_easy;
        // echo $_POST['programme_edit_id'];


        // $("#checkpoint_name").val(result['others'][0].name);            
        //     $("#checkpoint_tscore").val(result['others'][0].total_score);
        //     $("#checkpoint_pscore").val(result['others'][0].pass_score);
        //     $("#edit_checkpoint_difficult").val(result['others'][0].c_difficult);
        //     $("#edit_checkpoint_middle").val(result['others'][0].c_middle);
        //     $("#edit_checkpoint_easy").val(result['others'][0].c_easy);
        //     $("#checkpoint_limit_time").val(result['others'][0].limit_time);
        //     $("#checkpoint_arrpeo").val(result['arrp']);
        //     $("#checkpoint_edit_id").val(result['others'][0].checkpoint_id);
        if($id==''){
            $data1['programme_id'] = $_POST['programme_edit_id'];

            $result = $checkpoint->add($data1);

        }else{
            // $checkpoint->where($where)->delete();
            // for($i=1;$i<=$time;++$i){
            //     $data2['programme_id'] = $id;
            //     $data2['name'] = "第".$i."关";
            //     // $data['major_id'] = $_POST['major'];
            //     $data2['limit_time'] = '10';
            //     $data2['question_num'] = "5:5:5";
            //     $data2['total_score'] = "100";
            //     $data2['pass_score'] = "60";
            //     $result = $checkpoint->add($data2);
            // }
            $result = $checkpoint->where($where)->save($data1);
        }
        
        // if($result > 0){
        //     $result = "1";
        //     // echo 'nice';
        // }

        echo json_encode($result);
        // echo $checkpoint->getLastSql();
        // echo var_dump($result);
        exit;
        // echo $data;
    }

    //删除关卡
    public function Checkpoint_delete(){
        $id = $_POST['id'];
        $pid = $_POST['pid'];
        $checkpoint = M('checkpoint');
        $where = "checkpoint_id = {$id}";

        $result = $checkpoint->where($where)->delete();
        // echo "pid:::".$pid."||";
        $where = "programme_id = {$pid}";
        $time = $checkpoint->where($where)->count();//该方案下剩余的关卡数量
        $ids = $checkpoint->where($where)->getField('checkpoint_id
            ',true);
        if($result){
            for($i=1;$i<=$time;++$i){
                $name = "第".$i."关";
                $checkpoint->where('checkpoint_id = '.$ids[$i-1])->setField('name',$name);
            }           
        }
        // $result = $checkpoint->where('uid = 2')->setField('email','Jack@163.com');
        // echo "sql:::".$checkpoint->getLastSql()."||";
        echo json_encode($result);
        // echo var_dump($result);
        exit;
        // echo $id;
    }


}