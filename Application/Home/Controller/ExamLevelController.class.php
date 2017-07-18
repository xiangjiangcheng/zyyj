<?php
namespace Home\Controller;
use Think\Controller;
class ExamLevelController extends BaseController {
     //展示和查找方案信息
    public function ExamLevel_list_show(){
        $exam_level = M('exam_level');
        $examProgram = M('exam_program');
        $student_practice = M('student_practice');

        //搜索条件
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $examProgram_name = isset($_POST['exam_level_programme']) ? mysql_real_escape_string($_POST['exam_level_programme']) : '';
        // $grade = isset($_POST['grade']) ? mysql_real_escape_string($_POST['grade']) : '';
        // $department = isset($_POST['department']) ? mysql_real_escape_string($_POST['department']) : '';
        //默认显示日期为最新的方案等级
        if($examProgram_name == ''){
            $examProgram_name = $examProgram->order('createdate desc')->limit(1)->getField('program_id');
            // echo $examProgram->getLastSql();
            // echo "examProgram_name:::".$examProgram_name;
        }
        $offset = ($page-1)*$rows;
        $result = array();
        // echo $examProgram_name;
        $where = "program_id = '".$examProgram_name."'";
        // echo $examProgram->getLastSql();
        // echo $examProgram_name;
        $rs = $exam_level->where($where)->count();
        
        $result["total"] = $rs;
        $rs = $exam_level->where($where)->limit($offset.','.$rows)->select();
        
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

        $data = $student_practice->where("type=2")->field('programme_id')->select();
        if($data != null){
            foreach($data as $key=>$value){ 
                 foreach($value as $k=>$v){ 
                    $arr1 .= $v.',';
                 } 
            } 
            $arr1 = substr($arr1, 0, -1);
                 // rtrim($str, ",") 
            $where = "program_id in (".$arr1.") and program_id = '$examProgram_name'";
            $result['editable'] = $exam_level->where($where)->count();
            // echo $exam_level->getLastSql();
            if ($result['editable'] == null) {
                $result['editable'] = 0;
            }
        }else{
            $result['editable'] = 0;
        }
        // echo $exam_level->getLastSql();
        $items = array();
       
        $items = $rs;
        // echo var_dump($items);die;
        $result["rows"] = $items;
        $result["pcounts"] = $examProgram->count(); //获取方案数
        // echo $where;
        // echo $exam_level->getLastSql();
         // echo var_dump($result);die;
        echo json_encode($result);
    }

    //获取一个id的信息
    public function ExamLevel_getInfo(){
        $id = $_POST['id'];
        $student_practice = M('student_practice');
        $exam_level = M('exam_level');
        $where = "level_id='".$id."'";
        $result['arrp'] = $student_practice->where($where)->count();
        
        $rs = $exam_level->where($where)->select();
        //获取当前关卡是否为已使用方案的关卡
        $data = $student_practice->field('program_id')->select();
        foreach($data as $key=>$value){ 
             foreach($value as $k=>$v){ 
                $arr .= $v.',';
             } 
        } 
        $arr = substr($arr, 0, -1);
             // rtrim($str, ",") 
        $where = "program_id in (".$arr.") and level_id='".$id."'";
        $result['editable'] = $exam_level->where($where)->count();
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
        // echo $exam_level->getLastSql();
        echo json_encode($result);
    }


    //加载视图
    public function ExamLevel_list(){
        $this->display();
    }
    //获取方案名
    public function ExamLevel_getProgramme(){
        $examProgram = M('exam_program');
        // $result = array();
        $result = $examProgram->order('createdate desc')->select();

        echo json_encode($result);
    }

    //添加关卡和修改关卡操作
    public function ExamLevel_add(){
        // echo "is comming";
        // $examProgram = M('examProgram');
        $exam_level = M('exam_level');
        $id = $_POST['exam_level_edit_id'];
        $where = "level_id='".$id."'";
        // $data1 = $examProgram->create();
        $data1 = $exam_level->create();
        
        $data1['name'] = $_POST['exam_level_name'];
        $data1['total_score'] = $_POST['exam_level_tscore'];
        // $data['major_id'] = $_POST['major'];
        $data1['pass_score'] = $_POST['exam_level_pscore'];
        $c_difficult = $_POST['edit_exam_level_difficult'];
        $c_middle = $_POST['edit_exam_level_middle'];
        $c_easy = $_POST['edit_exam_level_easy'];
        $data1['limit_time'] = $_POST['exam_level_limit_time'];
        $data1['question_num'] = $c_difficult.';'.$c_middle.';'.$c_easy;
        // echo $_POST['examProgram_edit_id'];

        if($id==''){
            $data1['program_id'] = $_POST['program_edit_id'];

            $result = $exam_level->add($data1);

        }else{
            
            $result = $exam_level->where($where)->save($data1);
        }
        
        // if($result > 0){
        //     $result = "1";
        //     // echo 'nice';
        // }

        echo json_encode($result);
        // echo $exam_level->getLastSql();
        // echo var_dump($result);
        exit;
        // echo $data;
    }

    // //删除关卡
    // public function ExamLevel_delete(){
    //     $id = $_POST['id'];
    //     $pid = $_POST['pid'];
    //     $exam_level = M('exam_level');
    //     $where = "level_id = {$id}";

    //     $result = $exam_level->where($where)->delete();
    //     // echo "pid:::".$pid."||";
    //     $where = "program_id = {$pid}";
    //     $time = $exam_level->where($where)->count();//该方案下剩余的关卡数量
    //     $ids = $exam_level->where($where)->getField('level_id
    //         ',true);
    //     if($result){
    //         for($i=1;$i<=$time;++$i){
    //             $name = "第".$i."关";
    //             $exam_level->where('exam_level_id = '.$ids[$i-1])->setField('name',$name);
    //         }           
    //     }
    //     // $result = $exam_level->where('uid = 2')->setField('email','Jack@163.com');
    //     // echo "sql:::".$exam_level->getLastSql()."||";
    //     echo json_encode($result);
    //     // echo var_dump($result);
    //     exit;
    //     // echo $id;
    // }


}