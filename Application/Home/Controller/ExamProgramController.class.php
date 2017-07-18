<?php
namespace Home\Controller;
use Think\Controller;
class ExamProgramController extends BaseController {
     //展示和查找方案信息
    public function ExamProgram_list_show(){
        $examProgram = M('exam_program');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        
        $offset = ($page-1)*$rows;
        // echo $offset;die;
        $result = array();
        
        $where = "";
        $rs = $examProgram->count();
        // echo $rs;die;
        // $row = mysql_fetch_row($rs);
        
        $result["total"] = $rs;

        $rs = $examProgram->order("createdate desc")->limit($offset.','.$rows)->select();
        
        $items = array();
        
        $items = $rs;

        $result["rows"] = $items;

        echo json_encode($result);
    }

    //获取一个id的信息
    public function ExamProgram_getInfo(){
        $id = $_POST['id'];
        $examProgram = M('exam_program');
        // $checkpoint = M('checkpoint');
        $sp = M('student_practice');

        $where = "program_id='".$id."'";
        // $result['c_num'] = $checkpoint->where($where)->count();

        // $where = "programme_id='".$id."'";
        $rs = $examProgram->where($where)->select();

        $where = "programme_id='".$id."' and type = '2'";
        $result['user'] = $sp->where($where)->count();
        // echo  $sp->getLastSql();
        $result['others'] = $rs;
        // echo $sp->getLastSql();
        echo json_encode($result);
    }


    //加载视图
    public function ExamProgram_list(){
        $this->display();
    }
    

    //添加方案和修改方案操作
    public function ExamProgram_add(){
        // echo "is comming";
        $examProgram = M('exam_program');
        $examlevel = M('exam_level');
        $id = $_POST['exampro_edit_id'];
        $where = "program_id='".$id."'";
        $data1 = $examProgram->create();
        $data2 = $examlevel->create();
        $time = (int)$_POST['examprogram_cnum'];
        $date = time();  //得到当前日期时间
        
        // $data1['score'] = $_POST['programme_score'];
        $data1['createdate'] = date("y-m-d H:i:s",$date);
        // $data['major_id'] = $_POST['major'];
        $data1['name'] = $_POST['examprogram_name'];
        $data1['level_num'] = $_POST['examprogram_cnum'];

        


        if($id==''){
            $names = array();
            //判断是否重名
            $names = $examProgram->select();
            // echo "names:::".$names;
            foreach($names as $key=>$value){  
                foreach($names[$key] as $subkey=>$subval){
                    // echo $subval.' || ';
                    if($subkey == "name"){
                        // echo $subkey.' || ';
                        if($subval == $data1['name']){
                            $result = 2;
                            echo json_encode($result);
                            exit;
                        }
                    }               
                }
            }

            // die;
            $examProgram->add($data1);

            $id = $examProgram->getLastInsId();
            // for($i=1;$i<=$time;++$i){
            //建立对应 初级中级高级 的等级默认设置
            $data2['program_id'] = $id;
            $data2['name'] = "三级";
            $data2['total_question'] = '15';
            $data2['limit_time'] = '100';
            $data2['question_num'] = "5;5;5";
            $data2['total_score'] = "15";
            $data2['pass_score'] = "";
            $examlevel->add($data2);

            $data2['program_id'] = $id;
            $data2['name'] = "四级";
            $data2['total_question'] = '15';
            $data2['limit_time'] = '100';
            $data2['question_num'] = "5;5;5";
            $data2['total_score'] = "15";
            $data2['pass_score'] = "";
            $examlevel->add($data2);

            $data2['program_id'] = $id;
            $data2['name'] = "六级";
            $data2['total_question'] = '15';
            $data2['limit_time'] = '100';
            $data2['question_num'] = "5;5;5";
            $data2['total_score'] = "15";
            $data2['pass_score'] = "";
            $examlevel->add($data2);
            // }
            $result = 1;
        }else{
            $names = array();
            //判断是否重名
            $names = $examProgram->select();
            foreach($names as $key=>$value){  
                foreach($names[$key] as $subkey=>$subval){
                    if($subkey == "program_id"){
                        // echo $subkey.' || ';
                        if($subval == $id){
                            // echo "iscoming";
                            break;
                        }
                    }
                    // echo $subval.' || ';
                    if($subkey == "name"){
                        // echo $subkey.' || ';
                        if($subval == $data1['name']){
                            $result = 2;
                            echo json_encode($result);
                            exit;
                        }
                    }               
                }
            }


                 $examProgram->where($where)->save($data1);
                // }        

               $result = 3;        
        }
        

        echo json_encode($result);
        // echo var_dump($result);
        exit;
    }

    //删除方案
    public function ExamProgram_delete(){
        $id = $_POST['id'];
        $examProgram = M('exam_program');
        $examlevel = M('exam_level');
        $sp = M('student_practice');
        $where = "programme_id='".$id."' and type = '2'";
        $user = $sp->where($where)->count();
        if ($user != 0) {
            $result = 0;
            echo json_encode($result);
            exit;
        }
        $where = "program_id = {$id}";
        $result = $examlevel->where($where)->delete();
        // $class = M('class');
        // $where = "id=".$id;  
        $result = $examProgram->where($where)->delete();
        
        // echo $id;
        // echo $class->getLastSql();
        echo json_encode($result);
        // echo var_dump($result);
        exit;
        // echo $id;
    }


}